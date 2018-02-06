<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

$arFilterOrder['DATE_FROM']='23.11.2017';//$_REQUEST['start_date'];
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
		$arProps[$arProp['ORDER_PROPS_ID']]=array('NAME'=>$arProp['NAME'], 'VALUE'=>$propval);
	endwhile;
	$dbu=CUser::GetByID($arOrd['USER_ID']);
	$arUser = $dbu->GetNext();
	$arStatus=CSaleStatus::GetByID($arOrd['STATUS_ID']);
	$arDelivery=CSaleDelivery::GetByID($arOrd['DELIVERY_ID']);
	$arPayment=CSalePaySystem::GetByID($arOrd['PAY_SYSTEM_ID']);
	$arOrders[$arOrd['ID']]['ORDER']=array('ID'=>$arOrd['ID'], 'DATE_INSERT'=>$arOrd['DATE_INSERT'], 'STATUS'=>array('STATUS_ID'=>$arOrd['STATUS_ID'], 'STATUS_NAME'=>$arStatus['NAME']), 'PRICE_DELIVERY'=>$arOrd['PRICE_DELIVERY'], 'PRICE'=>$arOrd['PRICE'], 'COMMENTS'=>$arOrd['USER_DESCRIPTION'], 'DELIVERY'=>$arDelivery['NAME'], 'PAYMENT'=>$arPayment['NAME']);//$arOrd;
	$arOrders[$arOrd['ID']]['USER']=array('NAME'=>$arUser['NAME'], 'LOGIN'=>$arUser['LOGIN']);
	$arOrders[$arOrd['ID']]['PROPS']=$arProps;
	$dbb=CSaleBasket::GetList(array(), array('ORDER_ID'=>$arOrd['ID']), false, false, array('ID','PRODUCT_ID', 'NAME', 'QUANTITY', 'PRICE'));
	while($arP=$dbb->GetNext()):
		//pr($arP);
		$dbp=CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>27, 'ID'=>$arP['PRODUCT_ID']), false, false, array('IBLOCK_ID', 'ID', 'XML_ID', 'PROPERTY_CML2_LINK','PROPERTY_CML2_LINK.ID', 'PROPERTY_CML2_LINK.PROPERTY_CODE1C'));
		$arPr=$dbp->GetNext();
		//pr($arPr);
		$arOrders[$arOrd['ID']]['PRODUCTS'][]=array('OFFER_ID'=>$arPr['XML_ID'], 'CODE1C'=>$arPr['PROPERTY_CML2_LINK_PROPERTY_CODE1C_VALUE'], 'NAME'=>$arP['NAME'], 'QUANTITY'=>$arP['QUANTITY'], 'PRICE'=>$arP['PRICE']);
	endwhile;
endwhile;
//pr($arOrders);
$eol="\n";
//$eol='';

//$strOrdersXML='<sourceData>';
$strOrdersXML='';
foreach($arOrders as $arOrder):
	$strOrdersXML.='<order>'.$eol;
	$strOrdersXML.='<orderID>'.$arOrder['ORDER']['ID'].'</orderID>'.$eol;
	$strOrdersXML.='<orderDate>'.$arOrder['ORDER']['DATE_INSERT'].'</orderDate>'.$eol;
	$strOrdersXML.='<orderStatus>'.$arOrder['ORDER']['STATUS_NAME'].'</orderStatus>'.$eol;
	$strOrdersXML.='<orderPriceDelivery>'.round($arOrder['ORDER']['PRICE_DELIVERY'], 2).'</orderPriceDelivery>'.$eol;
	$strOrdersXML.='<orderPrice>'.round($arOrder['ORDER']['PRICE'], 2).'</orderPrice>'.$eol;
	$strOrdersXML.='<orderDelivery>'.$arOrder['ORDER']['DELIVERY'].'</orderDelivery>'.$eol;
	$strOrdersXML.='<orderPayment>'.$arOrder['ORDER']['PAYMENT'].'</orderPayment>'.$eol;
	$strOrdersXML.='<orderComments>'.$arOrder['ORDER']['COMMENTS'].'</orderComments>'.$eol;
	$strOrdersXML.='<productsInOrder>'.$eol;
	foreach($arOrder['PRODUCTS'] as $arProduct):
		$strOrdersXML.='<product>'.$eol;
		$strOrdersXML.='<offerID>'.$arProduct['OFFER_ID'].'</offerID>'.$eol;
		$strOrdersXML.='<code1c>'.$arProduct['CODE1C'].'</code1c>'.$eol;
		$strOrdersXML.='<name>'.$arProduct['NAME'].'</name>'.$eol;
		$strOrdersXML.='<quantity>'.$arProduct['QUANTITY'].'</quantity>'.$eol;
		$strOrdersXML.='<price>'.$arProduct['PRICE'].'</price>'.$eol;
		$strOrdersXML.='</product>'.$eol;
	endforeach;
	$strOrdersXML.='</productsInOrder>'.$eol;
	$strOrdersXML.='</order>'.$eol;
endforeach;
//$strOrdersXML.='</sourceData>';
$s=iconv("windows-1251", "utf-8", $strOrdersXML);
//file_put_contents('log.txt', $strOrdersXML."\n", FILE_APPEND);

//$strOrd=serialize($arOrders);
//echo $strOrd;
$obTest=wsdl('TransferExternalOrder', array('sourceData'=>$s));
pr($obTest);
echo '<br>theend';
?>