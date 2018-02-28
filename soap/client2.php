<?php
function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    $str=str_replace($rus, $lat, $str);
    $str= str_replace(' ', '_', $str);
    return strtolower($str);
}

$xml=@file_get_contents('php://input');
file_put_contents('0.txt', print_r($xml, true), FILE_APPEND);
$xml = simplexml_load_string($xml);

foreach ($xml->Request as $info) {
	
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



	//добавление картинки
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
		$hit=(string)$info->IsHit;
		$recommend=(string)$info->IsRecommend;
		$new=(string)$info->IsNew;
		$sale=(string)$info->IsSale;
		
		if($hit=='true') $PROP['HIT'][]=311;
		if($recommend=='true') $PROP['HIT'][]=312;
		if($new=='true') $PROP['HIT'][]=313;
		if($sale=='true') $PROP['HIT'][]=314;
		

		$arLoadProductArray = Array(
  			"IBLOCK_ID"      => 26,
  			"PROPERTY_VALUES"=> $PROP,
  			"NAME"           => iconv("utf-8","windows-1251",(string)$info->Name),
  			"CODE"           => CUtil::translit(iconv("utf-8","windows-1251",(string)$info->Name), "ru", $arTransParams),
  			"ACTIVE"         => "N", 
  			"PREVIEW_TEXT"   => iconv("utf-8","windows-1251",(string)$info->Anons),
  			"DETAIL_TEXT"    => iconv("utf-8","windows-1251",(string)$info->Description),
  			"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/image.gif")
  		);

		if($PRODUCT_ID = $el->Add($arLoadProductArray))
  			{
  				//зададим разделы

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
						"QUANTITY" => 1
					);
					CCatalogProduct::Add($arFields);
				}
  			}
		else {
			
  			echo "Error: ".translit($el->LAST_ERROR);
  			die();
		}
  		//добавим картинки

		foreach ($info->Pictures->Image as  $pic) {
			$IsMainImage=(string)$pic->IsMainImage;	
			$OriginName=(string)$pic->OriginName;	
			$OriginName=$_SERVER['DOCUMENT_ROOT'].$OriginName;	
			$SortOrder=(int)$pic->SortOrder;	
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
					
					if($res)  {}			
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
						unlink($OriginName);
        			}        		
				}
			}

		}
		echo 'true';

	}
}


