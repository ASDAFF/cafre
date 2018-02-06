<?
// phpinfo();exit;
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");

/*function get_brand_id($brand_xml_id) {
	$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>8, 'SECTION_ID'=>0, 'XML_ID'=>$brand_xml_id), false, false, array('ID', 'IBLOCK_ID', 'XML_ID'));
	$arB=$dbb->GetNext();
	return $arB['ID'];
}*/

$start_time = microtime(true);


$obTest=wsdl('GetAllProducts');
echo sizeof($obTest->GetAllProductsResult->ExportedData->ProductDTO).'<br>';
echo 'time get soap='.workTime2($start_time).'<br>';

//pr($obTest[0]);
//exit;

$arProducts=array();
$en0=0;
$en1=0;
$no1c=0;
$nobrand=0;
$arBrandsIds=array();
foreach($obTest->GetAllProductsResult->ExportedData->ProductDTO as $key=>$obProd):
	//pr($obProd); exit;
	$brand_id=$obProd->Brand->BrandId;
	if(!in_array($brand_id, $arBrandsIds)&&$brand_id>0):
		$arBrandsIds[]=$brand_id;
	endif;
	$code1c=trim($obProd->code1C);
	if($code1c!=''):
		$arProducts[$code1c]=array('NAME'=>iconv("utf-8", "windows-1251", $obProd->Name), 'PREVIEW_TEXT'=>iconv("utf-8", "windows-1251", $obProd->BriefDescription), 'DETAIL_TEXT'=>iconv("utf-8", "windows-1251", $obProd->Description), 'ENABLED'=>$obProd->Enabled, 'BRAND_ID'=>$brand_id, 'CODE'=>trim($obProd->UrlPath), 'RECOMMENDED'=>$obProd->Recomended, 'ISWEEKGOODS'=>$obProd->IsWeekGoods, 'ONSALE'=>$obProd->OnSale, 'code1C'=>$code1c, 'articul'=>iconv("utf-8", "windows-1251", trim($obProd->ArtNo)), 'product_id'=>$obProd->ID); //$obProd->ID
		if($brand_id==115||$brand_id==113):
			echo $code1c.' - '.$brand_id.', ';
		elseif($brand_id==''):
			//echo $code1c.' - nobrand,';
			$nobrand++;
		endif;
		if($obProd->Enabled==1):
			$en1++;
		else:
			$en0++;
			//pr($obProd);
			//echo '<hr>';
			//echo $obProd->ID.' '.iconv("utf-8", "windows-1251", trim($obProd->ArtNo)).' code1c='.$code1c.'<br>';
		endif;
	else:
		$no1c++;
		$arProductsNo1c[$obProd->ID]=array('NAME'=>iconv("utf-8", "windows-1251", $obProd->Name), 'PREVIEW_TEXT'=>iconv("utf-8", "windows-1251", $obProd->BriefDescription), 'DETAIL_TEXT'=>iconv("utf-8", "windows-1251", $obProd->Description), 'ENABLED'=>$obProd->Enabled, 'BRAND_ID'=>$brand_id, 'CODE'=>trim($obProd->UrlPath), 'RECOMMENDED'=>$obProd->Recomended, 'ISWEEKGOODS'=>$obProd->IsWeekGoods, 'ONSALE'=>$obProd->OnSale, 'code1C'=>$code1c, 'articul'=>iconv("utf-8", "windows-1251", trim($obProd->ArtNo)));
	endif;
	//if($obProd->ID==10910):
	//	pr($obProd);
	//endif;
endforeach;
unset($obTest);
echo '<br>nobrand='.$nobrand.'<br>';
echo sizeof($arBrandsIds).'<br>';
sort($arBrandsIds);
//pr($arBrandsIds);
pr ($arProducts['00000009998']);
pr ($arProducts['00000001491']);
pr ($arProducts['00000001676']);
exit;
//echo $arProducts['00000022355']['PREVIEW_TEXT'].'<br><hr>';
//echo $arProducts['00000022355']['DETAIL_TEXT'].'<br>';
//foreach ()
//exit;
/*$obTest=wsdl('GetAllProductOffers');
$arOffers=array();
foreach($obTest->GetAllProductOffersResult->ExportedData->OfferDTO as $key=>$obOffer):
	$arOffers[$obOffer->OfferId]=array('amount'=>$obOffer->Amount, 'price'=>$obOffer->Price, 'product_id'=>$obOffer->ProductId);
endforeach;
unset($obTest);
*/

//echo "en0=$en0, en1=$en1<br>";
echo 'time parse soap='.workTime2($start_time).'<br>';
//exit;

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
//pr($arProductsFromSiteActive);
echo 'timeFromSite='.workTime2($start_time).'<br>';
//exit;


$arNewProducts=array_diff_key($arProducts, $arProductsFromSite);
$arOldProducts=array_diff_key($arProductsFromSiteActive, $arProducts); // активные товары, которых нет в выгрузке
//unset($arProducts);
echo 'new from 1c ='.sizeof($arNewProducts).'<br>';
//pr($arNewProducts);
echo 'old from site ='.sizeof($arOldProducts);
//pr($arOldProducts);
echo '<hr>';
//exit;
$ideact=0;
$el = new CIBlockElement;
//деактиваци€ отсутствующих
$arFields=array('ACTIVE'=>'N');
foreach($arOldProducts as $xml_id=>$arOldProd):
	if($arOldProd['ACTIVE']!='Y'):
		continue;
	else:
		if($el->Update($arOldProd['ID'], $arFields)):
			//echo $arOldProd['ID'].' '.$xml_id;
			$ideact++;
		else:
			echo 'Error: '.$el->LAST_ERROR;
		endif;/**/
	endif;
	//if($ideact>250) break;
endforeach;
//eof деактиваци€ отсутствующих
echo '<br>'.$ideact.' деактивировано, врем€='.workTime2($start_time);
echo '<hr>';
//деактиваци€ существующих
$arActualProduct=array_intersect_key($arProductsFromSite, $arProducts);
//echo sizeof($arActualProduct);
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
echo '<br>'.$ideact.' деактивировано, врем€='.workTime2($start_time);
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
	//pr($arLoadProductArray);
	if($PRODUCT_ID = $el->Add($arLoadProductArray)):
		echo "New ID: ".$PRODUCT_ID.'<br>';
		$iadd++;
	else: 
		echo "Error: ".$el->LAST_ERROR.'<br>';
	endif;
endforeach;
echo '<br>'.$iadd.' добавлено, врем€='.workTime2($start_time);
//eof добавление новых товаров

?>