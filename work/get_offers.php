<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

//GetAllProductOffers();
//echo 'qq';
//exit;


//file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllProductOffers'."\n", FILE_APPEND);



$obTest=wsdl('GetAllProductOffers');
//pr($obTest);
$arOffers=array();

foreach($obTest->GetAllProductOffersResult->ExportedData->OfferDTO as $key=>$obOffer):
	$arOffers[$obOffer->OfferId]=array('amount'=>$obOffer->Amount, 'price'=>$obOffer->Price, 'product_id'=>$obOffer->ProductId);
endforeach;

$dboff=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>27), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'CATALOG_GROUP_1'));
while($arOff=$dboff->GetNext()):
	$arOffersFromSite[$arOff['XML_ID']]=array('ID'=>$arOff['ID'], 'PRICE'=>$arOff['CATALOG_PRICE_1'], 'QUANTITY'=>$arOff['CATALOG_QUANTITY']);
endwhile;

$arNewOffers=array_diff_key($arOffers, $arOffersFromSite);

pr($arNewOffers);
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
	file_put_contents('log.txt', round(workTime($start_time), 3)."\n-------------------------\n", FILE_APPEND);
	return 'GetAllProductOffers();';
}

?>