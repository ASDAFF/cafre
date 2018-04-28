<?
AddEventHandler("main", "OnEpilog", "ShowError404");
 function ShowError404() {
    if (CHTTP::GetLastStatus()=='404 Not Found') {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'] . '/404.php';   
    }
}

function str_replace_once($search, $replace, $text) 
{ 
   $pos = strpos($text, $search); 
   return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text; 
}
$incFile = dirname(__FILE__)."/1c_handler.php";
if(file_exists($incFile)) require_once($incFile);

if($_SERVER["HTTP_HOST"] == "mokeev.mixplus-dev.ru")
{
    LocalRedirect(
    'http://lakosa.ru'.$_SERVER["REQUEST_URI"],
    false,
    "301 Moved permanently"
);
}
AddEventHandler("main", "OnEndBufferContent", "removeType");
function removeType(&$content)
{
   $content = replace_output($content);
}
function replace_output($d)
{
   return str_replace(' type="text/javascript"', "", $d);
}


AddEventHandler("sale", "OnOrderSave", "sendOrder2WSDL"); 
function sendOrder2WSDL($order_id, $arFields, $arOrder, $isnew){
	
	if($isnew==1):
		CModule::IncludeModule("iblock");
		CModule::IncludeModule("catalog");
		CModule::IncludeModule("sale");
		$arFilterOrder['ID']=$order_id;
		$dbo=CSaleOrder::GetList(array('ID'=>'ASC'), $arFilterOrder, false, false, array('ID', 'DATE_INSERT','STATUS_ID','USER_ID', 'PRICE_DELIVERY', 'PRICE', 'DELIVERY_ID', 'PAY_SYSTEM_ID', 'USER_DESCRIPTION'));
		while($arOrd=$dbo->GetNext()):
			$dbprop=CSaleOrderPropsValue::GetList(array('SORT'=>'ASC'), array('ORDER_ID'=>$arOrd['ID']));
			while($arProp=$dbprop->GetNext()):
				//pr($arProp);
				if($arProp['ORDER_PROPS_ID']==6||$arProp['ORDER_PROPS_ID']==18):
					$arLoc=CSaleLocation::GetByID($arProp['VALUE']);
					//pr($arLoc);
					$propval=$arLoc['REGION_NAME'].', '.$arLoc['CITY_NAME'];
				else:
					$propval=$arProp['VALUE'];
				endif;
				$arProps[$arProp['CODE']]=$propval;//array('NAME'=>$arProp['NAME'], 'VALUE'=>);
			endwhile;
			$arProps['FULLADRES']='';
			if($arProps['ZIP']!='')
				$arProps['FULLADRES']=$arProps['ZIP'].', ';
			if($arProps['LOCATION']!='')
				$arProps['FULLADRES']=$arProps['LOCATION'].', ';
			if($arProps['ADDRESS']!='')
				$arProps['FULLADRES']=$arProps['ADDRESS'];
			$checkProps=print_r($arProps, true);
			$dbu=CUser::GetByID($arOrd['USER_ID']);
			$arUser = $dbu->GetNext();
			$arStatus=CSaleStatus::GetByID($arOrd['STATUS_ID']);
			$arDelivery=CSaleDelivery::GetByID($arOrd['DELIVERY_ID']);
			$arPayment=CSalePaySystem::GetByID($arOrd['PAY_SYSTEM_ID']);
			$newdate=str_replace('|', 'T', ConvertDateTime($arOrd['DATE_INSERT'], 'YYYY-MM-DD|HH:MI:SS')); //
			$arOrder['ORDER']=array('ID'=>$arOrd['ID'], 'DATE'=>$newdate, 'STATUS'=>array('STATUS_ID'=>$arOrd['STATUS_ID'], 'STATUS_NAME'=>$arStatus['NAME']), 'PRICE_DELIVERY'=>$arOrd['PRICE_DELIVERY'], 'PRICE'=>round($arOrd['PRICE'], 0), 'COMMENTS'=>$arOrd['USER_DESCRIPTION'], 'DELIVERY'=>$arDelivery['NAME'], 'PAYMENT'=>$arPayment['NAME']);//$arOrd;
			$arOrder['USER']=array('NAME'=>$arUser['NAME'], 'LOGIN'=>$arUser['LOGIN']);
			$arOrder['PROPS']=$arProps;
			$dbb=CSaleBasket::GetList(array(), array('ORDER_ID'=>$arOrd['ID']), false, false, array('ID','PRODUCT_ID', 'NAME', 'QUANTITY', 'PRICE'));
			while($arP=$dbb->GetNext()):
				//pr($arP);
				$dbp=CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>27, 'ID'=>$arP['PRODUCT_ID']), false, false, array('IBLOCK_ID', 'ID', 'XML_ID', 'PROPERTY_CML2_LINK','PROPERTY_CML2_LINK.ID', 'PROPERTY_CML2_LINK.PROPERTY_CODE1C'));
				$arPr=$dbp->GetNext();
				//pr($arPr);
				$arOrder['PRODUCTS'][]=array('OFFER_ID'=>$arPr['XML_ID'], 'CODE1C'=>$arPr['PROPERTY_CML2_LINK_PROPERTY_CODE1C_VALUE'], 'NAME'=>$arP['NAME'], 'QUANTITY'=>$arP['QUANTITY'], 'PRICE'=>round($arP['PRICE'], 0));
			endwhile;
		endwhile;
		$eol='';
		$strOrdersXML='';
			$strOrdersXML.='<SID xmlns="MC">26</SID>'.$eol;
			$strOrdersXML.='<UID xmlns="MC">'.$arOrder['ORDER']['ID'].'</UID>'.$eol;
			$strOrdersXML.='<Date xmlns="MC">'.$arOrder['ORDER']['DATE'].'</Date>'.$eol;
			$strOrdersXML.='<PrCode xmlns="MC" />';
			if($arOrder['ORDER']['COMMENTS']!=''):
				$strOrdersXML.='<Comm xmlns="MC">'.$arOrder['ORDER']['COMMENTS'].'</Comm>'.$eol;
			else:
				$strOrdersXML.='<Comm xmlns="MC" />';
			endif;
			$strOrdersXML.='<Name xmlns="MC">'.$arOrder['PROPS']['NAME'].'</Name>'.$eol;
			$strOrdersXML.='<Phone xmlns="MC">'.$arOrder['PROPS']['PHONE'].'</Phone>'.$eol;
			$strOrdersXML.='<Address xmlns="MC">'.$arOrder['PROPS']['FULLADRES'].'</Address>'.$eol;
			$strOrdersXML.='<eMail xmlns="MC">'.$arOrder['PROPS']['EMAIL'].'</eMail>'.$eol;
			foreach($arOrder['PRODUCTS'] as $arProduct):
				$strOrdersXML.='<Gds xmlns="MC">'.$eol;
				$strOrdersXML.='<ID>'.$arProduct['CODE1C'].'</ID>'.$eol;
				$strOrdersXML.='<Qty>'.$arProduct['QUANTITY'].'</Qty>'.$eol;
				$strOrdersXML.='<Cost>'.$arProduct['PRICE'].'</Cost>'.$eol;
				$strOrdersXML.='</Gds>'.$eol;
			endforeach;
			//$strOrdersXML.='<utm_medium xmlns="MC" /><utm_source xmlns="MC" /><utm_campaign xmlns="MC" /><dType xmlns="MC" /><bIndex xmlns="MC" /><bRegion xmlns="MC" /><bRaion xmlns="MC" /><bCity xmlns="MC" /><bNpunkt xmlns="MC" /><bStreet xmlns="MC" /><bHouse xmlns="MC" /><bCase xmlns="MC" /><bApartment xmlns="MC" /><tHouse xmlns="MC" /><tCase xmlns="MC" /><tApartment xmlns="MC" />';
		//$strOrdersXML.='</Zakaz>';
		//$s=iconv("windows-1251", "utf-8", $strOrdersXML);
		$s['SID']=26;
		$s['UID']=$arOrder['ORDER']['ID'];
		$s['Date']=$arOrder['ORDER']['DATE'];
		$s['PrCode']='';
		$s['Comm']=iconv("windows-1251", "utf-8", $arOrder['ORDER']['COMMENTS']);
		$s['Name']=iconv("windows-1251", "utf-8", $arOrder['PROPS']['NAME']);
		$s['Phone']=iconv("windows-1251", "utf-8", $arOrder['PROPS']['PHONE']);
		$s['Address']=iconv("windows-1251", "utf-8", $arOrder['PROPS']['FULLADRES']);
		$s['eMail']=iconv("windows-1251", "utf-8", $arOrder['PROPS']['EMAIL']);
		$s['Gds']=array();
		foreach($arOrder['PRODUCTS'] as $arProduct):
			array_push($s['Gds'], array('ID'=>$arProduct['CODE1C'], 'Qty'=>$arProduct['QUANTITY'], 'Cost'=>$arProduct['PRICE'] ));
		endforeach;
		
		$obTest=wsdl('NewZakaz', array('Zakaz'=>$s)); //array('sourceData'=>$s)
		$result=print_r($obTest, true);
		//$qF=print_r($arFields, true);
		//$qO=print_r($arOrder, true);
		//$qN=print_r($isnew, true);
		//file_put_contents($_SERVER["DOCUMENT_ROOT"].'/work/zakaz.xml', $qF.$qO.'is_new='.$qN."\n--\n", FILE_APPEND);
		//file_put_contents($_SERVER["DOCUMENT_ROOT"].'/work/zakaz.xml', $s."\n", FILE_APPEND); //, FILE_APPEND
		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/work/zakaz.xml', print_r($s, true), FILE_APPEND);
		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/work/zakaz.xml', 'res='.$result, FILE_APPEND);
		//file_put_contents($_SERVER["DOCUMENT_ROOT"].'/work/log.txt', "\n".$checkProps.' '.$arOrder['ORDER']['ID'].'res='.$result, FILE_APPEND);
	else:
		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/work/zakaz.xml', 'double:'.$arOrder['ID']."\n--\n", FILE_APPEND);
	endif;
}


function workTime2($start_time)
{
	return round(microtime(true) - $start_time, 3);
}


function pr($arX, $disp='block') {
	echo '<pre style="display:'.$disp.'">';
	print_r($arX);
	echo '</pre>';
}

function get_brand_id($brand_xml_id) {
	$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>8, 'SECTION_ID'=>0, 'XML_ID'=>$brand_xml_id), false, false, array('ID', 'IBLOCK_ID', 'XML_ID'));
	$arB=$dbb->GetNext();
	return $arB['ID'];
}

function get_product($code1c) {
	$dbb=CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$code1c), false, false, array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_CODE1C'));
	$arB=$dbb->GetNext();
	return array('ID'=>$arB['ID'], 'NAME'=>$arB['NAME']);
}

function wsdl($name, $arParams=array()) {
	//$server = '37.18.74.47:8002/WebServices/Granite.Gateway.ExportService.asmx';
	//$server = '37.18.74.47:8002/WebServices/Granite.Gateway.Export.Service.svc';
	//$server = 'estel.m-cosmetica.ru/webservices/Granite.Gateway.Export.Service.svc';
	$server = '185.147.81.84:11203/Trade/ws/MC.1cws';
	$wsdl_status = 0;
	$port = 80;
	$timeout = 10;
	$fp = @fsockopen ($server, $port, $errno, $errstr, $timeout);
	if ($fp) {//сначала проверяем доступен ли сейчас сервер
		$wsdl_status = 0;
		@fwrite ($fp, "HEAD / HTTP/1.0\r\nHost: $server:$port\r\n\r\n");
		if (strlen(@fread($fp,1024))>0) $wsdl_status = 1;
		fclose ($fp);
	}
	//if($wsdl_status>0)
	if(true) {
		//$client = new SoapClient('http://37.18.74.47:8002/WebServices/Granite.Gateway.ExportService.asmx?WSDL');
		//$client = new SoapClient('http://37.18.74.47:8002/WebServices/Granite.Gateway.Export.Service.svc?WSDL', array('cache_wsdl' => WSDL_CACHE_NONE));
		//$client = new SoapClient('http://estel.m-cosmetica.ru/webservices/Granite.Gateway.Export.Service.svc?WSDL', array('cache_wsdl' => WSDL_CACHE_NONE));
		$client = new SoapClient('http://185.147.81.84:11203/Trade/ws/MC.1cws?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
		if(sizeof($arParams>0))
			return  $client->$name($arParams);
		else
			return  $client->$name();
	}
	else 
		return false;
}

function GetAllProductOffers(){
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");
	//file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllProductOffers'."\n", FILE_APPEND);
	$obTest=wsdl('GetAllProductOffers');
	$arOffers=array();
	foreach($obTest->GetAllProductOffersResult->ExportedData->OfferDTO as $key=>$obOffer):
		$arOffers[$obOffer->OfferId]=array('amount'=>$obOffer->Amount, 'price'=>$obOffer->Price, 'product_id'=>$obOffer->ProductId);
	endforeach;
	$dboff=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>27), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'CATALOG_GROUP_1'));
	while($arOff=$dboff->GetNext()):
		$arOffersFromSite[$arOff['XML_ID']]=array('ID'=>$arOff['ID'], 'PRICE'=>$arOff['CATALOG_PRICE_1'], 'QUANTITY'=>$arOff['CATALOG_QUANTITY']);
	endwhile;
	$ip=0;
	$iq=0;
	foreach($arOffers as $xml_id=>$arOffer):
		if(is_array($arOffersFromSite[$xml_id])):
			if($arOffer['price']!=$arOffersFromSite[$xml_id]['PRICE']):
				$arPriceFromSite=CPrice::GetBasePrice($arOffersFromSite[$xml_id]['ID']);
				echo $xml_id.' prices!! '.$arOffer['price'].'<>'.$arOffersFromSite[$xml_id]['PRICE'].'<br>';
				if($arPriceFromSite['ID']>0):
					if(CPrice::Update($arPriceFromSite['ID'], array('PRICE'=>$arOffer['price']))):
						$ip++;
					endif;
				//else:
				endif;
			endif;
			if($arOffer['amount']!=$arOffersFromSite[$xml_id]['QUANTITY']):
				$arUpdQuant=array('QUANTITY'=>$arOffer['amount']);
				if(CCatalogProduct::Update($arOffersFromSite[$xml_id]['ID'], $arUpdQuant)):
					echo $xml_id.' '.$arOffersFromSite[$xml_id]['ID'].'!!!<hr>';/**/
					$iq++;
				endif;
			endif;
		endif;
		$i++;
	endforeach;
	file_put_contents('log.txt', $ip." prices updated\n".$iq." quantities updated\n", FILE_APPEND);
	//file_put_contents('log.txt', round(workTime($start_time), 3)."\n-------------------------\n", FILE_APPEND);
	return 'GetAllProductOffers();';
}

function getAllCategories() {
	file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllCategories'."\n", FILE_APPEND);
	$start_time = microtime(true);
	CModule::IncludeModule("iblock");
	$obTest=wsdl('GetAllCategories');
	foreach($obTest->GetAllCategoriesResult->ExportedData->CategoryDTO as $key=>$obCat):
		$cat_id=$obCat->CategoryId;
		$arCatsParents[$obCat->ParentCategory][]=$cat_id;
		$arCat=array('NAME'=>iconv("utf-8", "windows-1251", trim($obCat->Name)), 'ENABLED'=>$obCat->Enabled, 'PARENT_ID'=>$obCat->ParentCategory, 'SORT'=>intval($obCat->SortOrder), 'CODE'=>trim($obCat->UrlPath));
		$arCats[$obCat->CategoryId]= $arCat;
		if($obCat->Enabled==1)
			$en1++;
		else
			$en0++;
	endforeach;
	unset($obTest);
	echo 'time get soap='.workTime2($start_time).'<br>';
	// по уровням
	$arCats2Levels=array();
	for($level=1;$level<10;$level++):
		if($level==1):
			foreach($arCatsParents[0] as $cat_id):
				$arLevels[$level][$cat_id]=0;
				$arCats[$cat_id]['LEVEL']=$level;
			endforeach;
			unset($arCatsParents[0]);
		else:
			if($level>20) break;
			foreach($arLevels[$level-1] as $parent_id=>$id):
				foreach ($arCatsParents[$parent_id] as $cat_id):
					$arLevels[$level][$cat_id]=$parent_id;
					$arCats[$cat_id]['LEVEL']=$level;
				endforeach;
				unset($arCatsParents[$parent_id]);
			endforeach;
			if(sizeof($arCatsParents)<1) break;
		endif;
	endfor;
	//с сайта
	$dbs=CIBlockSection::GetList(array('DEPTH_LEVEL'=>'ASC', 'SORT'=>'ASC'), array('IBLOCK_ID'=>26), false, array('ID', 'XML_ID', 'IBLOCK_SECTION_ID', 'NAME', 'ACTIVE', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DESCRIPTION', 'SORT'));
	while($arS=$dbs->GetNext()):
		$arId2Xml[$arS['ID']]=$arS['XML_ID']; // соответствие id и xml_id
		$arSectionsFromSite[$arS['XML_ID']]=$arS;
		$arLevelsFromSite[$arS['DEPTH_LEVEL']][$arS['XML_ID']]=array('parent_id'=>$arS['IBLOCK_SECTION_ID'], 'parent_xml'=>$arId2Xml[$arS['IBLOCK_SECTION_ID']]);
		if($arS['ACTIVE']=='Y')
			$arSectionsFromSiteAct[$arS['XML_ID']]=$arS;
		else
			$arSectionsFromSiteNoAct[$arS['XML_ID']]=$arS;
	endwhile;
	//деактивация разделов, которых нет в выгрузке
	$arOldSects=array_diff_key($arSectionsFromSiteAct, $arCats);
	$bs = new CIBlockSection;
	$arFieldsNoAct=array('ACTIVE'=>'N');
	$ideact=0;
	foreach ($arOldSects as $xml_id=>$arOldSect):
		if($bs->Update($arOldSect['ID'], $arFieldsNoAct)):
			$ideact++;
		else:
			echo $arOldSect['ID'].' '.$bs->LAST_ERROR.'<br>';
		endif;
	endforeach;/**/
	//eof деактивация разделов
	//добавление новых по уровням
	$arNewAddedSections=array();
	foreach ($arLevels as $level=>$arLevel):
		$arLevelNewSects=array_diff_key($arLevel, $arSectionsFromSite);
		if (is_array($arLevelNewSects)&&sizeof($arLevelNewSects)):
			foreach($arLevelNewSects as $newcode=>$parentcode):
				$arFields2Add=array('IBLOCK_ID'=>26, 'NAME'=>$arCats[$newcode]['NAME'], 'SORT'=>$arCats[$newcode]['SORT'], 'CODE'=>$arCats[$newcode]['CODE'], 'ACTIVE'=>'Y', 'XML_ID'=>$newcode);
				if(isset($arSectionsFromSite[$parentcode])):
					echo $newcode.' => '.$arSectionsFromSite[$parentcode]['NAME'].'<br>';
					$arFields2Add['IBLOCK_SECTION_ID']=$arSectionsFromSite[$parentcode]['ID'];
					$ID = $bs->Add($arFields2Add);
					if($ID>0):
						$arNewAddedSections[$newcode]=$ID;
					else:
						echo $bs->LAST_ERROR.'<br>';
					endif;
				elseif(isset($arNewAddedSections[$parentcode])&&$arNewAddedSections[$parentcode]>0):
					echo $newcode.' => '.$arSectionsFromSite[$parentcode]['NAME'].'<br>';
					$arFields2Add['IBLOCK_SECTION_ID']=$arNewAddedSections[$parentcode];
					$ID = $bs->Add($arFields2Add);
					if($ID>0):
						$arNewAddedSections[$newcode]=$ID;
					else:
						echo $bs->LAST_ERROR.'<br>';
					endif;
				else:
					echo 'NOT ADDED '.$newcode.' '.$parentcode.'<br>';
				endif;
			endforeach;
		endif;
	endforeach;
	//eof добавление новых по уровням
	//проверка старых по уровням
	$iold=0;
	$arOldSects=array_intersect_key($arSectionsFromSite, $arCats);
	echo 'oldsects='.sizeof($arOldSects).'<br>';
	foreach($arOldSects as $xml_id=>$arSect):
		$arFields2Upd=array();
		if($arSect['NAME']!=$arCats[$xml_id]['NAME']):
			$arFields2Upd['NAME']=$arCats[$xml_id]['NAME'];
		endif;
		if($arSect['SORT']!=$arCats[$xml_id]['SORT']):
			if($arCats[$xml_id]['SORT']>0):
				echo $xml_id.' => '.$arSect['SORT'].' | '.$arCats[$xml_id]['SORT'].' - sort<br>';
				$arFields2Upd['SORT']=$arCats[$xml_id]['SORT'];
			endif;
		endif;
		if($arId2Xml[$arSect['IBLOCK_SECTION_ID']]!=$arCats[$xml_id]['PARENT_ID']):
			$arFields2Upd['IBLOCK_SECTION_ID']=$arSectionsFromSite[$arCats[$xml_id]['PARENT_ID']]['ID'];
		endif;
		if(sizeof($arFields2Upd)>0):
			if($bs->Update($arSect['ID'], $arFields2Upd, false)):
				$iold++;
				echo 'upd: '.$arSect['ID'].' '.$xml_id.'<br>';
			else:
				echo $arSect['ID'].' '.$bs->LAST_ERROR.'<br>';
			endif;/**/
		endif;
	endforeach;
	//eof проверка старых по уровням
	CIBlockSection::ReSort(26);
	file_put_contents('log.txt', round(workTime2($start_time), 3)."\n-------------------------\n", FILE_APPEND);
	return 'getAllCategories();';
}

function GetAllProducts() {
	file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllProducts'."\n", FILE_APPEND);
	$start_time = microtime(true);
	CModule::IncludeModule("iblock");
	$obTest=wsdl('GetAllProducts');
	echo sizeof($obTest->GetAllProductsResult->ExportedData->ProductDTO).'<br>';
	echo 'time get soap='.workTime2($start_time).'<br>';
	foreach($obTest->GetAllProductsResult->ExportedData->ProductDTO as $key=>$obProd):
		$brand_id=$obProd->Brand->BrandId;
		$code1c=trim($obProd->code1C);
		if($code1c!=''):
			$arProducts[$code1c]=array('NAME'=>iconv("utf-8", "windows-1251", $obProd->Name), 'PREVIEW_TEXT'=>iconv("utf-8", "windows-1251", $obProd->BriefDescription), 'DETAIL_TEXT'=>iconv("utf-8", "windows-1251", $obProd->Description), 'ENABLED'=>$obProd->Enabled, 'BRAND_ID'=>$brand_id, 'CODE'=>trim($obProd->UrlPath), 'RECOMMENDED'=>$obProd->Recomended, 'ISWEEKGOODS'=>$obProd->IsWeekGoods, 'ONSALE'=>$obProd->OnSale, 'code1C'=>$code1c, 'articul'=>iconv("utf-8", "windows-1251", trim($obProd->ArtNo)), 'product_id'=>$obProd->ID); //$obProd->ID
			if($obProd->Enabled==1):
				$en1++;
			else:
				$en0++;
			endif;
		/*else:
			$no1c++;
			$arProductsNo1c[$obProd->ID]=array('NAME'=>iconv("utf-8", "windows-1251", $obProd->Name), 'PREVIEW_TEXT'=>iconv("utf-8", "windows-1251", $obProd->BriefDescription), 'DETAIL_TEXT'=>iconv("utf-8", "windows-1251", $obProd->Description), 'ENABLED'=>$obProd->Enabled, 'BRAND_ID'=>$brand_id, 'CODE'=>trim($obProd->UrlPath), 'RECOMMENDED'=>$obProd->Recomended, 'ISWEEKGOODS'=>$obProd->IsWeekGoods, 'ONSALE'=>$obProd->OnSale, 'code1C'=>$code1c, 'articul'=>iconv("utf-8", "windows-1251", trim($obProd->ArtNo)));*/
		endif;
	endforeach;
	unset($obTest);
	echo 'time parse soap='.workTime2($start_time).'<br>';
	$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, '!PROPERTY_CODE1C'=>false), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'NAME', 'ACTIVE', 'PROPERTY_artIk', 'PROPERTY_CODE1C'));
	while($arP=$dbb->GetNext()):
		$arProductsFromSite[$arP['PROPERTY_CODE1C_VALUE']]=array('ID'=>$arP['ID'], 'NAME'=>$arP['NAME'], 'ACTIVE'=>$arP['ACTIVE'], 'ARTIKUL'=>$arP['PROPERTY_ARTIK_VALUE'], 'CODE1C'=>$arP['PROPERTY_CODE1C_VALUE'], 'XML_ID'=>$arP['XML_ID']); //$arP['XML_ID']
		if ($arP['ACTIVE']=='Y'):
			$arProductsFromSiteActive[$arP['PROPERTY_CODE1C_VALUE']]=array('ID'=>$arP['ID'], 'NAME'=>$arP['NAME'], 'ACTIVE'=>$arP['ACTIVE'], 'ARTIKUL'=>trim($arP['PROPERTY_ARTIK_VALUE']), 'CODE1C'=>$arP['PROPERTY_CODE1C_VALUE'], 'XML_ID'=>$arP['XML_ID']); //$arP['XML_ID']
		else:
			$arProductsFromSiteNoActive[$arP['PROPERTY_CODE1C_VALUE']]=array('ID'=>$arP['ID'], 'NAME'=>$arP['NAME'], 'ACTIVE'=>$arP['ACTIVE'], 'ARTIKUL'=>trim($arP['PROPERTY_ARTIK_VALUE']), 'CODE1C'=>$arP['PROPERTY_CODE1C_VALUE'], 'XML_ID'=>$arP['XML_ID']); //$arP['XML_ID']
		endif;
	endwhile;
	echo sizeof($arProductsFromSiteActive).' act<br>';
	echo sizeof($arProductsFromSiteNoActive).' noact<br>';
	echo 'timeFromSite='.workTime2($start_time).'<br>';
	$arNewProducts=array_diff_key($arProducts, $arProductsFromSite);
	$arOldProducts=array_diff_key($arProductsFromSiteActive, $arProducts); // активные товары, которых нет в выгрузке
	echo 'new from 1c ='.sizeof($arNewProducts).'<br>';
	echo 'old from site ='.sizeof($arOldProducts);
	$ideact=0;
	$el = new CIBlockElement;
	//деактивация отсутствующих
	$arFields=array('ACTIVE'=>'N');
	foreach($arOldProducts as $xml_id=>$arOldProd):
		if($arOldProd['ACTIVE']!='Y'):
			continue;
		else:
			if($el->Update($arOldProd['ID'], $arFields)):
				$ideact++;
			else:
				echo 'Error: '.$el->LAST_ERROR;
			endif;/**/
		endif;
	endforeach;
	//eof деактивация отсутствующих
	echo '<br>'.$ideact.' деактивировано, время='.workTime2($start_time);
	echo '<hr>';
	//деактивация существующих
	$arActualProduct=array_intersect_key($arProductsFromSite, $arProducts);
	$ideact=0;
	foreach ($arActualProduct as $xml_id=>$arProd):
		if($arProducts[$xml_id]['ENABLED']!=1):
			if($arProd['ACTIVE']!='Y'):
				continue;
			endif;
			if($el->Update($arProd['ID'], $arFields)):
				//echo $arProd['ID'].' '.$xml_id.'<br>';
				$ideact++;
			else:
				echo 'Error: '.$el->LAST_ERROR;
			endif;/**/
		endif;
	endforeach;
	echo '<br>'.$ideact.' деактивировано, время='.workTime2($start_time);
	//eof обновление существующих
	//добавление новых товаров
	$iadd=0;
	foreach($arNewProducts as $code1c=>$arNewProduct):
		$arProps=array('CODE1C'=>$code1c, 'artIk'=>$arNewProduct['articul']);
		$brand_id=get_brand_id($arNewProduct['BRAND_ID']);
		if($brand_id>0):
			$arProps['BRAND']=$brand_id;
		endif;
		$arLoadProductArray=array('IBLOCK_ID'=>26, 'NAME'=>$arNewProduct['NAME'], 'PREVIEW_TEXT'=>$arNewProduct['PREVIEW_TEXT'], 'DETAIL_TEXT'=>$arNewProduct['DETAIL_TEXT'], 'CODE'=>$arNewProduct['CODE'], 'XML_ID'=>$arNewProduct['product_id'], 'PROPERTY_VALUES'=>$arProps);
		if($PRODUCT_ID = $el->Add($arLoadProductArray)):
			echo "New ID: ".$PRODUCT_ID.'<br>';
			$iadd++;
		else: 
			echo "Error: ".$el->LAST_ERROR.'<br>';
		endif;
	endforeach;
	echo '<br>'.$iadd.' добавлено, время='.workTime2($start_time);
	file_put_contents('log.txt', round(workTime2($start_time), 3)."\n-------------------------\n", FILE_APPEND);
	//eof добавление новых товаров
	return 'GetAllProducts();';
}

function GetAllProductsCategories(){
	file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllProductsCategories'."\n", FILE_APPEND);
	$start_time = microtime(true);
	CModule::IncludeModule("iblock");
	$dbs=CIBlockSection::GetList(array('DEPTH_LEVEL'=>'ASC', 'SORT'=>'ASC'), array('IBLOCK_ID'=>26), false, array('ID', 'XML_ID'));
	while($arS=$dbs->GetNext()):
		$arSectId2Xml[$arS['ID']]=$arS['XML_ID']; // соответствие id и xml_id
		$arSectXml2Id[$arS['XML_ID']]=$arS['ID']; // соответствие xml_id и id 
	endwhile;
	$obTest=wsdl('GetAllProductsCategories');
	$arProds2Cats=array();
	foreach($obTest->GetAllProductsCategoriesResult->ExportedData->ProductLinkDTO as $key=>$obP2C):
		$arProds2Cats[trim($obP2C->ProductCodeOneC)][]=$obP2C->CategoryId;
		$arProds2CatsIds[trim($obP2C->ProductCodeOneC)][]=$arSectXml2Id[$obP2C->CategoryId];
	endforeach;
	unset($obTest);
	echo 'time parse soap='.workTime2($start_time).'<br>';
	$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, '!PROPERTY_CODE1C'=>false), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'PROPERTY_CODE1C', 'IBLOCK_SECTION_ID', 'ACTIVE'));
	$i=0;
		$arS2P=array();
	while($arP=$dbb->GetNext()):
		if(is_array($arProds2CatsIds[$arP['PROPERTY_CODE1C_VALUE']])):
			CIBlockElement::SetElementSection($arP['ID'], $arProds2CatsIds[$arP['PROPERTY_CODE1C_VALUE']]);
		else:
			$j++;
		endif;
		$i++;
	endwhile;
	//echo '<br>from site: '.$i.' '.$j.'<br>';
	echo 'time set sections ='.workTime2($start_time).'<br>';
	file_put_contents('log.txt', round(workTime2($start_time), 3)."\n-------------------------\n", FILE_APPEND);
	return 'GetAllProductsCategories();';
}

function GetNewOffers() {
	file_put_contents('log.txt', date('d.m.Y H:i:s').' GetNewOffers'."\n", FILE_APPEND);
	$start_time = microtime(true);
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");
	$obTest=wsdl('GetAllProductOffers');
	$arOffers=array();
	foreach($obTest->GetAllProductOffersResult->ExportedData->OfferDTO as $key=>$obOffer):
		$code1c=trim($obOffer->RelatedProductCode1c);
		$arOffers[$obOffer->OfferId]=array('amount'=>$obOffer->Amount, 'price'=>$obOffer->Price, 'product_id'=>$obOffer->ProductId, 'code1c'=>$code1c);
	endforeach;
	unset($obTest);
	$dboff=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>27), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'CATALOG_GROUP_1'));
	while($arOff=$dboff->GetNext()):
		$arOffersFromSite[$arOff['XML_ID']]=array('ID'=>$arOff['ID'], 'PRICE'=>$arOff['CATALOG_PRICE_1'], 'QUANTITY'=>$arOff['CATALOG_QUANTITY']);
	endwhile;
	$arNewOffers=array_diff_key($arOffers, $arOffersFromSite);
	echo 'new='.sizeof($arNewOffers).'<br>';
	$el = new CIBlockElement;
	foreach ($arNewOffers as $offer_id=>$arOffer):
		$arP=get_product($arOffer['code1c']);
		if($arP['ID']>0&&$arP['NAME']!=''):
			$arProps=array('CML2_LINK'=>$arP['ID']);
			$arLoadProductArray=array('IBLOCK_ID'=>27, 'NAME'=>$arP['NAME'], 'XML_ID'=>$offer_id, 'PROPERTY_VALUES'=>$arProps);
			if($new_offer_id = $el->Add($arLoadProductArray)):
				$arCatalogFields=array('QUANTITY'=>$arOffer['amount']);
				CCatalogProduct::Update($new_offer_id, $arCatalogFields);
				$arPriceFields=array('PRODUCT_ID'=>$new_offer_id, 'CURRENCY'=>'RUB', 'CATALOG_GROUP_ID'=>1, 'PRICE'=>$arOffer['price']);
				CPrice::Add($arPriceFields);
			else:
				echo "Error: ".$el->LAST_ERROR.'<br>';
			endif;
		endif;
	endforeach;/**/
	file_put_contents('log.txt', round(workTime2($start_time), 3)."\n-------------------------\n", FILE_APPEND);
	return 'GetNewOffers();';
}

// создаем обработчик событиЯ "OnBeforeIBlockElementUpdate" чтобы не удалЯть картинки длЯ товаров!
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "Copy_file");
    function Copy_file($arFields)
    {
        if($arFields["IBLOCK_ID"]==26||$arFields["IBLOCK_ID"]==27) {
			global $DB;
            $ELEMENT=$arFields['ID'];
			$dbRes = $DB->Query('SELECT * FROM `b_iblock_property` WHERE PROPERTY_TYPE ="F" and (IBLOCK_ID=26 or IBLOCK_ID=27) ' );
			$aRows = array();
			while ($row = $dbRes->Fetch()) {
				$ids[]=$row['ID'];
			}
			$ids= implode(', ', $ids);
			if($ids=='') $ids='0';
			$dbRes = $DB->Query('SELECT SUBDIR, FILE_NAME FROM b_iblock_element_property join b_file on VALUE=b_file.ID WHERE IBLOCK_PROPERTY_ID in('.$ids.') and IBLOCK_ELEMENT_ID="'.$ELEMENT.'" 
				UNION 
				SELECT SUBDIR, FILE_NAME FROM b_iblock_element join b_file on PREVIEW_PICTURE=b_file.ID WHERE b_iblock_element.ID="'.$ELEMENT.'"
				UNION 
				SELECT SUBDIR, FILE_NAME FROM b_iblock_element join b_file on DETAIL_PICTURE=b_file.ID WHERE b_iblock_element.ID="'.$ELEMENT.'"' );
			$aRows = array();
			while ($row = $dbRes->Fetch()) {
				if ( !file_exists( $_SERVER['DOCUMENT_ROOT'].'/upload/'.$row['SUBDIR'].'/'.$row['FILE_NAME'] ) ) echo "‚нимание! ”айл не найден!";
				if ( !is_dir( $_SERVER['DOCUMENT_ROOT'].'/upload/copy/'.$row['SUBDIR'] ) ) 
				mkdir($_SERVER['DOCUMENT_ROOT'].'/upload/copy/'.$row['SUBDIR'], 0777);
				$file = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$row['SUBDIR'].'/'.$row['FILE_NAME'];
				$copyfile = $_SERVER['DOCUMENT_ROOT'].'/upload/copy/'.$row['SUBDIR'].'/'.$row['FILE_NAME'];
				if (!copy($file, $copyfile)) {
					echo "не удалось скопировать $file";
				}
				$files[]='(NULL, "/upload/'.$row['SUBDIR'].'/'.$row['FILE_NAME'].'", "/upload/copy/'.$row['SUBDIR'].'/'.$row['FILE_NAME'].'")';
			}
			if(!empty($files)) $dbRes = $DB->Query('insert into time_file (id, real_file, copy_file) values '.implode(', ', $files) );
			
			
        }
    }
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "reCopy");
function reCopy() {
	global $DB;
		$dbRes = $DB->Query('SELECT * FROM `time_file` ' );
while($row = $dbRes->Fetch()) {
	
	if ( !file_exists( $_SERVER['DOCUMENT_ROOT'].$row['real_file'] ) ) {
		if ( !is_dir( substr($row['real_file'], 0, strripos($row['real_file'], '/')) ) ) 
			mkdir($_SERVER['DOCUMENT_ROOT'].substr($row['real_file'], 0, strripos($row['real_file'], '/')), 0777);
		$file = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$row['SUBDIR'].'/'.$row['FILE_NAME'];
		$copyfile = $_SERVER['DOCUMENT_ROOT'].'/upload/copy/'.$row['SUBDIR'].'/'.$row['FILE_NAME'];
		copy($_SERVER['DOCUMENT_ROOT'].$row['copy_file'], $_SERVER['DOCUMENT_ROOT'].$row['real_file']);
	}
	unlink($_SERVER['DOCUMENT_ROOT'].$row['copy_file']);
rmdir($_SERVER['DOCUMENT_ROOT'].substr($row['copy_file'], 0, strripos($row['copy_file'], '/')));
	$del_id[]=$row['id'];
}
if(!empty($del_id)) $dbRes = $DB->Query('delete from time_file where id in ('.implode(', ', $del_id).')' );
  return "reCopy();";
}
?>