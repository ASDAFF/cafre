<?
// phpinfo();exit;
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");



function GetAllProducts() {
	file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllProducts'."\n", FILE_APPEND);
	$start_time = microtime(true);
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
	//обновление существующих
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

$q=GetAllProducts();

//pr($obTest[0]);
//exit;

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
//exit;


//exit;



?>