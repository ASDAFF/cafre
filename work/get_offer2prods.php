<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

$obTest=wsdl('GetAllProductOffers');
//pr($obTest);
//exit;
$arOffers=array();

foreach($obTest->GetAllProductOffersResult->ExportedData->OfferDTO as $key=>$obOffer):
	$code1c=trim($obOffer->RelatedProductCode1c);
	$arOffers[$obOffer->OfferId]=array('xml_id'=>$obOffer->OfferId, 'amount'=>$obOffer->Amount, 'price'=>$obOffer->Price, 'product_id'=>$obOffer->ProductId, 'code1c'=>$code1c);
endforeach;
unset($obTest);
pr($arOffers);
exit;
echo '<hr>';
//$numNo1c=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>false), array());
$numNoProduct=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>27, 'PROPERTY_CML2_LINK'=>false), array());
echo $numNo1c;
//$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>false), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'NAME','PROPERTY_CODE1C'));
/*while($arP=$dbb->GetNext()):
	pr($arP);
endwhile;*/

echo '<br>theend';

/*foreach ($arOffers as $xml_id=>$arOffer):
	if($arOffer['product_id']=='81420958'||$arOffer['product_id']=='4086'):
		echo $xml_id;
		pr($arOffer);
	endif;
endforeach;*/

?>
