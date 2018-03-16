<?php
set_time_limit(0);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
		use Bitrix\Main,
			Bitrix\Main\Loader,
			Bitrix\Main\Config\Option,
			Bitrix\Sale,
			Bitrix\Sale\Order,
			Bitrix\Sale\Basket,
			Bitrix\Main\Application,
			Bitrix\Sale\DiscountCouponsManager;
function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    $str=str_replace($rus, $lat, $str);
    $str= str_replace(' ', '_', $str);
    return strtolower($str);
}
function addOneProduct($info) {
	$arTransParams = array(
		"max_len" => 100,
		"change_case" => 'L',
		"replace_space" => '_',
		"replace_other" => '_',
		"delete_repeat_replace" => true
	);
//создадим товар
	
		$el = new CIBlockElement;
		$PROP = array();
		$PROP['CODE1C']=(string)$info->Code;
		$PROP['artIk']=(string)$info->ArtNo;

		$brend=iconv("utf-8","windows-1251",(string)$info->Brand);
		$brend=array($brend, $brend.' %');
		if($brend!=''&&$brend) {
			$arSelect = Array("ID");
			$arFilter = Array("IBLOCK_ID"=>8, "NAME"=>$brend);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
			if($ob = $res->GetNextElement())
			{
				$arFields = $ob->GetFields();
 				$PROP['BRAND']=$arFields['ID'];
			}
		}
		
		$arLoadProductArray = Array(
  			"IBLOCK_ID"      => 26,
  			"PROPERTY_VALUES"=> $PROP,
  			"NAME"           => iconv("utf-8","windows-1251",(string)$info->Name),
  			"CODE"           => CUtil::translit(iconv("utf-8","windows-1251",(string)$info->Name), "ru", $arTransParams),
  			"ACTIVE"         => "N", 
			"PREVIEW_TEXT_TYPE"=>'html',
			"DETAIL_TEXT_TYPE"=>'html',
  			"PREVIEW_TEXT"   => iconv("utf-8","windows-1251",(string)$info->Anons),
  			"DETAIL_TEXT"    => iconv("utf-8","windows-1251",(string)$info->Description),
  			"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/image.gif")
  		);
  		

		if($PRODUCT_ID = $el->Add($arLoadProductArray))
  			{
  				//создадим торговое предложение

  				$el2 = new CIBlockElement;
				$PROP = array();
				$PROP['CML2_LINK']=$PRODUCT_ID;
		
				$arLoadProductArray = Array(
  					"IBLOCK_SECTION_ID" => false,  
  					"IBLOCK_ID"      => 27,
  					"PROPERTY_VALUES"=> $PROP,
  					"NAME"           => iconv("utf-8","windows-1251",(string)$info->Name),
  					"ACTIVE"         => "Y"           
  				);
		
				if($TP_ID = $el2->Add($arLoadProductArray)) {
					//создадим цену и остатки для него	
					$Count=(int)$info->Count;
					
					foreach ($info->Currency->Price as $pricetype) { 
					
						if($pricetype->PriceType=='Base') {
							$price=(string)$pricetype->Value;
							$PRICE_TYPE_ID = 1;
							$arFields = Array(
								"PRODUCT_ID" => $TP_ID,
								"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
								"PRICE" => $price,
								"CURRENCY" => "RUB"
							);
							$res = CPrice::GetList(
								array(),
								array(
									"PRODUCT_ID" => $TP_ID,
									"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
								)
							);
							if ($arr = $res->Fetch())
							{
								if(CPrice::Update($arr["ID"], $arFields)) {}
							}
							else
							{
								if(CPrice::Add($arFields)) {}					
							}
						}
					}
						
					$arFields = array(
						"ID" => $TP_ID, 
						"QUANTITY" => (int)$info->Count
					);
					CCatalogProduct::Add($arFields);
				}
				
				//добавим картинки
				foreach ($info->Pictures->Image as  $pic) {
					$IsMainImage=(string)$pic->IsMainImage;	
					$OriginName=(string)$pic->OriginName;	
					$OriginName=$_SERVER['DOCUMENT_ROOT'].$OriginName;	
					$SortOrder=(int)$pic->SortOrder;	
					$SortOrder--;
					
					$el = new CIBlockElement;
					if($IsMainImage==='true') {
						$arLoadProductArray = Array(
							"PREVIEW_PICTURE" => CFile::MakeFileArray($OriginName)
						);
						$res=$el->Update($PRODUCT_ID, $arLoadProductArray);
					
						if($res)  {}			
						else echo $el->LAST_ERROR;
						unlink($OriginName);
					}	
					else {
						$resPic = CIBlockElement::GetList(Array(), array("IBLOCK_ID"=>26,'ID'=>$PRODUCT_ID), false, Array("nPageSize"=>1), array('ID', 'IBLOCK_ID'));
						if($obPic = $resPic->GetNextElement()){ 
							$pics = $obPic->GetProperties(); 
							$num=-1;
							foreach ($pics['MORE_PHOTO']['VALUE'] as $key => $value) {
								$num++;
								if($num==$SortOrder) $num++;
								$file=CFile::GetFileArray($value);
								$newmassive[$num]=Array("VALUE"=>CFile::MakeFileArray($file['SRC']));                		
							}
							$newmassive[$SortOrder]=Array("VALUE"=>CFile::MakeFileArray($OriginName));    
							ksort($newmassive);
							CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, 26, array("MORE_PHOTO"=>$newmassive));
							unlink($OriginName);
						}        		
					}
				}		
  			}
}

$xml=@file_get_contents('php://input');

$xml = simplexml_load_string($xml);
foreach ($xml->Request as $info) {
file_put_contents('0.txt', (string)$info->Operation, FILE_APPEND);
	//обновление цены
	if((string)$info->Operation=='SetWholesalePriceBy1cCode') {
		$code=(string)$info->Code;		
		
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
		CModule::IncludeModule('catalog');
		CModule::IncludeModule('sale');
		CModule::IncludeModule('iblock');
		
		for ($i=0; $i < strlen($code); $i++) { 		
			if(!$code[$i]=='0') break;
		}	
		$code= substr($code, $i);
		$arSelect = Array("ID");
		$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C"=>array(str_repeat("0", 11-strlen($code)).$code, $code));
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
		
		if($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arSelect = Array("ID");
			$arFilter = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arFields['ID']) ;
			$resTP = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);		
			if($obTP = $resTP->GetNextElement()) {
				$arFieldsTP = $obTP->GetFields();				
				$PRODUCT_ID = $arFieldsTP['ID'];

				foreach ($info->Currency->Price as $pricetype) { 
					
					if($pricetype->PriceType=='Base') {
						$price=(string)$pricetype->Value;
						$PRICE_TYPE_ID = 1;
						$arFields = Array(
							"PRODUCT_ID" => $PRODUCT_ID,
							"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
							"PRICE" => $price,
							"CURRENCY" => "RUB"
						);
						$res = CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $PRODUCT_ID,
								"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
							)
						);
						if ($arr = $res->Fetch())
						{
							if(CPrice::Update($arr["ID"], $arFields))
								echo "true";
						}
						else
						{
							if(CPrice::Add($arFields))
								echo "true";					
						}
					}
				}		


			}
		}
		else {
			echo 'ErrorCode';
		}
	}
	
	//обновление остатков
	if((string)$info->Operation=='UpdateAmountByCodeFrom1c') {
		$code=(string)$info->Code;		
		$Count=(int)$info->Count;
		
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
		CModule::IncludeModule('catalog');
		CModule::IncludeModule('sale');
		CModule::IncludeModule('iblock');
		
		for ($i=0; $i < strlen($code); $i++) { 		
			if(!$code[$i]=='0') break;
		}	
		$code= substr($code, $i);
		$arSelect = Array("ID");
		$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C"=>array(str_repeat("0", 11-strlen($code)).$code, $code));
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
		
		if($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arSelect = Array("ID");
			$arFilter = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arFields['ID']) ;
			$resTP = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);		
			if($obTP = $resTP->GetNextElement()) {
				$arFieldsTP = $obTP->GetFields();				
				if(CCatalogProduct::Update($arFieldsTP['ID'], Array("QUANTITY"=>$Count)))
					echo "true";
			}
		}
		else {
			echo 'ErrorCode';
		}
	}

	//добавление картинки
	if((string)$info->Operation=='AddImage') {
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
		CModule::IncludeModule('iblock');

		$code=(string)$info->Code;	
		$IsMainImage=(string)$info->IsMainImage;	
		$OriginName=(string)$info->OriginName;	
		$OriginName=$_SERVER['DOCUMENT_ROOT'].$OriginName;	
		$SortOrder=(int)$info->SortOrder;	
		$SortOrder--;
		for ($i=0; $i < strlen($code); $i++) { 		
			if(!$code[$i]=='0') break;
		}	
		$code= substr($code, $i);
		$arSelect = Array("ID");
		$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C"=>array(str_repeat("0", 11-strlen($code)).$code, $code));
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
		
		if($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$el = new CIBlockElement;
			if($IsMainImage==='true') {
				$arLoadProductArray = Array(
	    			"PREVIEW_PICTURE" => CFile::MakeFileArray($OriginName)
				);
				$res=$el->Update($arFields['ID'], $arLoadProductArray);
				
				if($res) 
					echo 'true';				
				else echo $el->LAST_ERROR;
				unlink($OriginName);
			}
			else {
				$resPic = CIBlockElement::GetList(Array(), array("IBLOCK_ID"=>26,'ID'=>$arFields['ID']), false, Array("nPageSize"=>1), array('ID', 'IBLOCK_ID'));
        		if($obPic = $resPic->GetNextElement()){ 
            		$pics = $obPic->GetProperties(); 
            		$num=-1;
            		foreach ($pics['MORE_PHOTO']['VALUE'] as $key => $value) {
            			$num++;
            			if($num==$SortOrder) $num++;
            			$file=CFile::GetFileArray($value);
                		$newmassive[$num]=Array("VALUE"=>CFile::MakeFileArray($file['SRC']));                		
            		}
            		$newmassive[$SortOrder]=Array("VALUE"=>CFile::MakeFileArray($OriginName));            		
            		ksort($newmassive);
            		CIBlockElement::SetPropertyValuesEx($arFields['ID'], 26, array("MORE_PHOTO"=>$newmassive));
            		echo 'true';
					unlink($OriginName);
        		}        		
			}
		}
		else {
			echo 'ErrorCode';
		}
	}



	//добавление товара
	if((string)$info->Operation=='AddProduct') {
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
		
		CModule::IncludeModule('iblock');
		CModule::IncludeModule('catalog');
		CModule::IncludeModule('sale');
		$arTransParams = array(
			"max_len" => 100,
			"change_case" => 'L',
			"replace_space" => '_',
			"replace_other" => '_',
			"delete_repeat_replace" => true
		);
		//создадим товар
		$el = new CIBlockElement;
		$PROP = array();
		$PROP['CODE1C']=(string)$info->Code;
		$PROP['artIk']=(string)$info->ArtNo;

		$brend=iconv("utf-8","windows-1251",(string)$info->Brand);
		$brend=array($brend, $brend.' %');
		if($brend!=''&&$brend) {
			$arSelect = Array("ID");
			$arFilter = Array("IBLOCK_ID"=>8, "NAME"=>$brend);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
			if($ob = $res->GetNextElement())
			{
				$arFields = $ob->GetFields();
 				$PROP['BRAND']=$arFields['ID'];
			}
		}
		//$hit=(string)$info->IsHit;
		//$recommend=(string)$info->IsRecommend;
		//$new=(string)$info->IsNew;
		//$sale=(string)$info->IsSale;
		
		//if($hit=='true') $PROP['HIT'][]=311;
		//if($recommend=='true') $PROP['HIT'][]=312;
		//if($new=='true') $PROP['HIT'][]=313;
		//if($sale=='true') $PROP['HIT'][]=314;
		

		$arLoadProductArray = Array(
  			"IBLOCK_ID"      => 26,
  			"PROPERTY_VALUES"=> $PROP,
  			"NAME"           => iconv("utf-8","windows-1251",(string)$info->Name),
  			"CODE"           => CUtil::translit(iconv("utf-8","windows-1251",(string)$info->Name), "ru", $arTransParams),
  			"ACTIVE"         => "N", 
			"PREVIEW_TEXT_TYPE"=>'html',
			"DETAIL_TEXT_TYPE"=>'html',
  			"PREVIEW_TEXT"   => iconv("utf-8","windows-1251",(string)$info->Anons),
  			"DETAIL_TEXT"    => iconv("utf-8","windows-1251",(string)$info->Description),
  			"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/image.gif")
  		);

		if($PRODUCT_ID = $el->Add($arLoadProductArray))
  			{
  				//создадим торговое предложение

  				$el2 = new CIBlockElement;
				$PROP = array();
				$PROP['CML2_LINK']=$PRODUCT_ID;
		
				$arLoadProductArray = Array(
  					"IBLOCK_SECTION_ID" => false,  
  					"IBLOCK_ID"      => 27,
  					"PROPERTY_VALUES"=> $PROP,
  					"NAME"           => iconv("utf-8","windows-1251",(string)$info->Name),
  					"ACTIVE"         => "Y"           
  				);
		
				if($TP_ID = $el2->Add($arLoadProductArray)) {
					//создадим цену и остатки для него	
					$Count=(int)$info->Count;
					
					foreach ($info->Currency->Price as $pricetype) { 
					
						if($pricetype->PriceType=='Base') {
							$price=(string)$pricetype->Value;
							$PRICE_TYPE_ID = 1;
							$arFields = Array(
								"PRODUCT_ID" => $TP_ID,
								"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
								"PRICE" => $price,
								"CURRENCY" => "RUB"
							);
							$res = CPrice::GetList(
								array(),
								array(
									"PRODUCT_ID" => $TP_ID,
									"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
								)
							);
							if ($arr = $res->Fetch())
							{
								if(CPrice::Update($arr["ID"], $arFields)) {}
							}
							else
							{
								if(CPrice::Add($arFields)) {}					
							}
						}
					}
						
					$arFields = array(
						"ID" => $TP_ID, 
						"QUANTITY" => (int)$info->Count
					);
					CCatalogProduct::Add($arFields);
				}
				
				//добавим картинки
				foreach ($info->Pictures->Image as  $pic) {
					$IsMainImage=(string)$pic->IsMainImage;	
					$OriginName=(string)$pic->OriginName;	
					$OriginName=$_SERVER['DOCUMENT_ROOT'].$OriginName;	
					$SortOrder=(int)$pic->SortOrder;	
					$SortOrder--;
					
					$el = new CIBlockElement;
					if($IsMainImage==='true') {
						$arLoadProductArray = Array(
							"PREVIEW_PICTURE" => CFile::MakeFileArray($OriginName)
						);
						$res=$el->Update($PRODUCT_ID, $arLoadProductArray);
					
						if($res)  {}			
						else echo $el->LAST_ERROR;
						unlink($OriginName);
					}	
					else {
						$resPic = CIBlockElement::GetList(Array(), array("IBLOCK_ID"=>26,'ID'=>$PRODUCT_ID), false, Array("nPageSize"=>1), array('ID', 'IBLOCK_ID'));
						if($obPic = $resPic->GetNextElement()){ 
							$pics = $obPic->GetProperties(); 
							$num=-1;
							foreach ($pics['MORE_PHOTO']['VALUE'] as $key => $value) {
								$num++;
								if($num==$SortOrder) $num++;
								$file=CFile::GetFileArray($value);
								$newmassive[$num]=Array("VALUE"=>CFile::MakeFileArray($file['SRC']));                		
							}
							$newmassive[$SortOrder]=Array("VALUE"=>CFile::MakeFileArray($OriginName));    
							ksort($newmassive);
							CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, 26, array("MORE_PHOTO"=>$newmassive));
							unlink($OriginName);
						}        		
					}
				}
		
  			}
		else {
			
  			echo "Error: ".translit($el->LAST_ERROR);
  			die();
		}
  		
		
		echo 'true';
	}

	if((string)$info->Operation=='AddProducts') {
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
		
		CModule::IncludeModule('iblock');
		CModule::IncludeModule('catalog');
		CModule::IncludeModule('sale');
		
		foreach ($info->Products->Product as $element) {
			addOneProduct($element);
		}
		echo 'true';  		
		
	}
	
	if((string)$info->Operation=='UpdateOrder') {		
		if (!Loader::IncludeModule('sale'))
			die();
		if (!Loader::IncludeModule('iblock'))
			die();
		
		$order = Sale\Order::load((int)$info->OrderNumber);
		$basket = $order->getBasket();
		
		foreach($info->Cart->Item as $item) {
			$code=(string)$item->Code;
			for ($i=0; $i < strlen($code); $i++) { 		
				if(!$code[$i]=='0') break;
			}	
			$code= substr($code, $i);
			$resTov = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C"=>array(str_repeat("0", 11-strlen($code)).$code, $code)), false, Array("nPageSize"=>1), Array("ID", "NAME"));
			if($obTov = $resTov->GetNextElement()) {
				$arFieldsTov = $obTov->GetFields();
				$resTP = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arFieldsTov['ID']), false, Array("nPageSize"=>1), Array("ID"));		
				if($obTP = $resTP->GetNextElement()) {
					$arFieldsTP = $obTP->GetFields();				
					$basket_1c[$arFieldsTP['ID']]=array('summ'=>(int)$item->Price,'amount'=>(int)$item->Amount, 'name'=>$arFieldsTov['NAME']);
				}
			}
		}
		foreach ($basket as $basketItem) {
			if(in_array($basketItem->getField('PRODUCT_ID'), array_keys($basket_1c))) {
				if($basketItem->getQuantity()!=$basket_1c[$basketItem->getField('PRODUCT_ID')]['amount'])
					$basketItem->setField('QUANTITY',  $basket_1c[$basketItem->getField('PRODUCT_ID')]['amount']);
				$basketItem->setFields(array(
				'PRICE' => $basket_1c[$basketItem->getField('PRODUCT_ID')]['summ'],
				'CUSTOM_PRICE' => 'Y',
				"IGNORE_CALLBACK_FUNC"  => "Y"));
				unset($basket_1c[$basketItem->getField('PRODUCT_ID')]);
			}
			else {				
				$basketItem->delete();
			}
		}
		foreach($basket_1c as $newProduct=>$tovar) {
			$item = $basket->createItem('catalog', $newProduct);
			$item->setFields(array(
				'PRICE' => $tovar['summ'],
				'CUSTOM_PRICE' => 'Y',
				"IGNORE_CALLBACK_FUNC"  => "Y",
				'NAME' => $tovar['name'],
				'QUANTITY' => $tovar['amount'],
				'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
				'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
				'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
			));
		}
		
		$basket->save();
		
		$shipmentCollection = $order->getShipmentCollection();
		        	
			foreach ($shipmentCollection as $shipment) { 
	            if (!$shipment->isSystem()){ 
					$shipment->setField('TRACKING_NUMBER', (string)$info->DeliveryTrackNumber);  		
					if($order->getDeliveryPrice()!=$info->DeliveryCost) $shipment->setField('BASE_PRICE_DELIVERY', (int)$info->DeliveryCost);  
					
					$shipmentItemCollection = $shipment->getShipmentItemCollection();
					foreach ($basket as $item)
					{
						$shipmentItem = $shipmentItemCollection->createItem($item);
						$shipmentItem->setQuantity(1);
					} 
				}
        	}	
        
		
		$order->setField('STATUS_ID', (string)$info->OrderStateId);
		if($info->DeliveryMethodName) {
			$method=iconv("utf-8","windows-1251",(string)$info->DeliveryMethodName);
			if(strpos($order->getField('USER_DESCRIPTION'), $method)===false) 
				$order->setField('USER_DESCRIPTION', $order->getField('USER_DESCRIPTION').'<br>'.iconv("utf-8","windows-1251",'Способ доставки: ').$method);
		}
		
		$order->setField('PRICE', (int)$info->OrderTotalSum);
		
        if($order->save()) echo "true";			
	}
}