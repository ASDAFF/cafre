<?
//header('Content-Type: text/html;charset=utf-8');
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

$arFilterOrder['ID']=107;
//$arFilterOrder['DATE_FROM']='23.11.2017';//$_REQUEST['start_date'];
//$arFilterOrder['DATE_TO']='';//$_REQUEST['end_date'];

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
		$arOrder['PRODUCTS'][]=array('OFFER_ID'=>$arPr['XML_ID'], 'CODE1C'=>$arPr['PROPERTY_CML2_LINK_PROPERTY_CODE1C_VALUE'], 'NAME'=>$arP['NAME'], 'QUANTITY'=>$arP['QUANTITY'], 'PRICE'=>intval($arP['PRICE']));
	endwhile;
endwhile;
//pr($arOrder);
//exit;
//$eol="\n";
$eol='';
//
$strOrdersXML='<Zakaz xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">'.$eol;
	$strOrdersXML.='<SID xmlns="MC">26</SID>'.$eol;
	$strOrdersXML.='<UID xmlns="MC">cafre'.$arOrder['ORDER']['ID'].'</UID>'.$eol;
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
	//$strOrdersXML.='<utm_medium xsi:nil="true" xmlns="MC" /><utm_source xsi:nil="true" xmlns="MC" /><utm_campaign xsi:nil="true" xmlns="MC" /><bIndex xsi:nil="true" xmlns="MC" /><bRegion xsi:nil="true" xmlns="MC" /><bRaion xsi:nil="true" xmlns="MC" /><bCity xsi:nil="true" xmlns="MC" /><bNpunkt xsi:nil="true" xmlns="MC" /><bStreet xsi:nil="true" xmlns="MC" /><bHouse xsi:nil="true" xmlns="MC" /><bCase xsi:nil="true" xmlns="MC" /><bApartment xsi:nil="true" xmlns="MC" /><tHouse xsi:nil="true" xmlns="MC" /><tCase xsi:nil="true" xmlns="MC" /><tApartment xsi:nil="true" xmlns="MC" />';
	//$strOrdersXML.='<utm_medium xmlns="MC" /><utm_source xmlns="MC" /><utm_campaign xmlns="MC" /><dType xmlns="MC" /><bIndex xmlns="MC" /><bRegion xmlns="MC" /><bRaion xmlns="MC" /><bCity xmlns="MC" /><bNpunkt xmlns="MC" /><bStreet xmlns="MC" /><bHouse xmlns="MC" /><bCase xmlns="MC" /><bApartment xmlns="MC" /><tHouse xmlns="MC" /><tCase xmlns="MC" /><tApartment xmlns="MC" />';
$strOrdersXML.='</Zakaz>';

$s=iconv("windows-1251", "utf-8", $strOrdersXML);
file_put_contents('zakaz.xml', $s);
?>
<?
$s2='<Zakaz><SID xmlns="MC">26</SID><UID xmlns="MC">846</UID><Date xmlns="MC">2017-11-30T15:49:05.8341351+03:00</Date><PrCode xmlns="MC" /><Comm xmlns="MC" /><Name xmlns="MC">test</Name><Phone xmlns="MC">+7123454878</Phone><Address xmlns="MC">assa</Address><eMail xmlns="MC">test@test.ru</eMail><Gds xmlns="MC"><ID>00000001582</ID><Qty>1</Qty><Cost>100</Cost></Gds><utm_medium xmlns="MC" /><utm_source xmlns="MC" /><utm_campaign xmlns="MC" /><dType xmlns="MC" /><bIndex xmlns="MC" /><bRegion xmlns="MC" /><bRaion xmlns="MC" /><bCity xmlns="MC" /><bNpunkt xmlns="MC" /><bStreet xmlns="MC" /><bHouse xmlns="MC" /><bCase xmlns="MC" /><bApartment xmlns="MC" /><tHouse xmlns="MC" /><tCase xmlns="MC" /><tApartment xmlns="MC" /></Zakaz>';
$obTest=wsdl('TransferExternalOrder', array('sourceData'=>$s)); //array('sourceData'=>$s)
pr($obTest);

//$client = new SoapClient('http://37.18.74.47:8002/WebServices/Granite.Gateway.ExportService.asmx?WSDL');
//pr($client);
//echo $client->__getLastRequestHeaders();

//echo '$obTest->TransferExternalOrderResult->ExportedData->string:<br>'.iconv("utf-8", "windows-1251", $obTest->TransferExternalOrderResult->ExportedData->string);

echo '<br>';
$ch=$obTest->TransferExternalOrderResult->IsSuccessfully;
if(is_bool($ch))
	echo 'IsSuccessfully is bool<br>';
if ($obTest->TransferExternalOrderResult->IsSuccessfully)
	echo 'IsSuccessfully=true';
else
	echo 'IsSuccessfully=false';
echo $obTest->TransferExternalOrderResult->IsSuccessfully;


?>
