<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

//GetAllProductOffers();
//echo 'qq';
//exit;

function get_product33($code1c) {
	$dbb=CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$code1c), false, false, array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_CODE1C'));
	$arB=$dbb->GetNext();
	return array('ID'=>$arB['ID'], 'NAME'=>$arB['NAME']);
}

//file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllProductOffers'."\n", FILE_APPEND);



$obTest=wsdl('GetAllProductOffers');
//pr($obTest);
$arOffers=array();

foreach($obTest->GetAllProductOffersResult->ExportedData->OfferDTO as $key=>$obOffer):
	$code1c=trim($obOffer->RelatedProductCode1c);
	$arOffers[$obOffer->OfferId]=array('amount'=>$obOffer->Amount, 'price'=>$obOffer->Price, 'product_id'=>$obOffer->ProductId, 'code1c'=>$code1c);
endforeach;
unset($obTest);
pr($arOffers);
exit;

$dboff=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>27), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'CATALOG_GROUP_1'));
while($arOff=$dboff->GetNext()):
	$arOffersFromSite[$arOff['XML_ID']]=array('ID'=>$arOff['ID'], 'PRICE'=>$arOff['CATALOG_PRICE_1'], 'QUANTITY'=>$arOff['CATALOG_QUANTITY']);
endwhile;

$arNewOffers=array_diff_key($arOffers, $arOffersFromSite);

pr($arNewOffers);
$el = new CIBlockElement;
/*foreach ($arNewOffers as $offer_id=>$arOffer):
	$arP=get_product($arOffer['code1c']);
	if($arP['ID']>0&&$arP['NAME']!=''):
		$arProps=array('CML2_LINK'=>$arP['ID']);
		$arLoadProductArray=array('IBLOCK_ID'=>27, 'NAME'=>$arP['NAME'], 'XML_ID'=>$offer_id, 'PROPERTY_VALUES'=>$arProps);
		pr($arLoadProductArray);
		if($new_offer_id = $el->Add($arLoadProductArray)):
			$arCatalogFields=array('QUANTITY'=>$arOffer['amount']);
			pr($arCatalogFields);
			CCatalogProduct::Update($new_offer_id, $arCatalogFields);
			$arPriceFields=array('PRODUCT_ID'=>$new_offer_id, 'CURRENCY'=>'RUB', 'CATALOG_GROUP_ID'=>1, 'PRICE'=>$arOffer['price']);
			pr($arPriceFields);
			CPrice::Add($arPriceFields);
		else:
			echo "Error: ".$el->LAST_ERROR.'<br>';
		endif;
	endif;
endforeach;*/
exit;

//$arOffFromSite=$dboff->GetNext();
//pr($arOffersFromSite);

$ip=0;
$iq=0;




function GetAllProductOffers1(){
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
	file_put_contents('log.txt', round(workTime($start_time), 3)."\n---------