<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

function pr($arX, $disp='block') {
	echo '<pre style="display:'.$disp.'">';
	print_r($arX);
	echo '</pre>';
}

function workTime($start_time)
{
	return microtime(true) - $start_time;
}

$start_time = microtime(true);

$arTPcsv=file($_SERVER["DOCUMENT_ROOT"].'/include/import/product2brand.csv');
foreach($arTPcsv as $key=>$str):
	//echo $str.'<br>';
	if($key==0) continue;
	$arTP=explode(';', $str);
	if($arTP[1]!='NULL'):
		$arBr2Pr[$arTP[0]]=$arTP[1];
	endif;
endforeach;
/**/

$res=CIBlockElement::GetList(array(), array('IBLOCK_ID'=>8, 'ACTIVE'=>'Y'), false, false, array('ID', 'IBLOCK_ID', 'NAME', 'XML_ID'));
while ($arB=$res->GetNext()):
	$arBrands[$arB['XML_ID']]=$arB['ID'];
endwhile;

//pr($arBr2Pr['81420385']);
//echo '?';
//echo $arBr2Pr['81422609'].'<br>';

$res=CIBlockElement::GetList(array(), array('IBLOCK_ID'=>26, 'PROPERTY_BRAND'=>false), false, false, array('ID', 'IBLOCK_ID', 'NAME', 'XML_ID'));
$arP=$res->GetNext();
$i=0;
while ($arP=$res->GetNext()):
	if($arBr2Pr[$arP['XML_ID']]!=''):
		$arProdsFromSite[$arP['XML_ID']]=array('ID'=>$arP['ID'], 'NAME'=>$arP['NAME']);
		$i++;
		if($i>1000): 
			break;
		endif;
	else:
		continue;
	endif;
endwhile;
//$arOffer=$res->GetNext();
pr($arProdsFromSite);
foreach($arProdsFromSite as $xml_id=>$arProd):
	//echo $xml_id.'<br>';
	$brand_id=$arBrands[$arBr2Pr[$xml_id]];
	$arProp=array('BRAND'=>$brand_id);
	//pr($arProp);
	CIBlockElement::SetPropertyValuesEx($arProd['ID'], 26, $arProp);
endforeach;

echo workTime($start_time).'<br>';
exit;


//$arNewBrands=array_diff_key($arBrands, $arBrandsFromSite);

//pr($arNewBrands);

echo workTime($start_time).'<br>';
//exit;

$el = new CIBlockElement;
foreach($arNewBrands as $xml_id=>$arBr):
	/*$arLoadProductArray = Array('IBLOCK_ID'=>8, "NAME" =>$arBr['name'], "XML_ID" =>$xml_id, 'CODE'=>$arBr['code']);
	if($PRODUCT_ID = $el->Add($arLoadProductArray))
		echo "New ID: ".$PRODUCT_ID.'<br>';
	else
		echo "Error: ".$el->LAST_ERROR.'<br>';*/
	//break;
endforeach;

echo workTime($start_time).'<br>';

exit;


/*
$res=CIBlockElement::GetList(array(), array('IBLOCK_ID'=>27), false, false, array('ID', 'IBLOCK_ID'));
$i=0;
while ($arTP=$res->GetNext()):
	//pr($arTP);
	echo $arTP['ID'].'<br>';
	$res2 = CPrice::GetList(array(), array("PRODUCT_ID"=>$arTP['ID'], "CATALOG_GROUP_ID"=>1));
	while ($arPrice=$res2->Fetch()):
		pr($arPrice);
		//echo 'q ';
	endwhile;
	if($i++>30) break;
endwhile;
*/
//CPrice::Add($arFields);

echo 'qq';

?>


<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>