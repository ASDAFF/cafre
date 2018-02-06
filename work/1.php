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

$arTPcsv=file($_SERVER["DOCUMENT_ROOT"].'/include/import/offer_data.csv');
foreach($arTPcsv as $key=>$str):
	if($key>5999):
		//echo $str;
		$arTP=explode(';', $str);
		$arTPs[]=array('offer_id'=>$arTP[0], 'price'=>$arTP[3]);
	endif;
	if($key>7500) break;
endforeach;

//pr($arTPs);

echo workTime($start_time).'<br>';
//exit;

//$res=CIBlockElement::GetList(array(), array('IBLOCK_ID'=>27, 'XML_ID'=>$arTPs[0]['offer_id']), false, false, array('ID', 'IBLOCK_ID', 'NAME'));
//$arOffer=$res->GetNext();
//pr($arOffer);


foreach($arTPs as $key=>$arTP):
	echo $key.'<br>';
	$res=CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>27, 'XML_ID'=>$arTP['offer_id']), false, false, array('ID', 'IBLOCK_ID', 'NAME'));
	$arOffer=$res->GetNext();
	$arFields = Array("PRODUCT_ID" =>$arOffer['ID'], "CATALOG_GROUP_ID" =>1, "PRICE" =>$arTP['price'], "CURRENCY" => "RUB");
	$res2 = CPrice::GetList(array(), array("PRODUCT_ID"=>$arOffer['ID'], "CATALOG_GROUP_ID"=>1));
	if($arPrice=$res2->Fetch()):
		//continue;
		//pr($arPrice);
		if($arPrice['PRICE']==$arTP['price']):
			//echo $key.'<br>';
			continue;
		else:
			CPrice::Update($arPrice["ID"], $arFields);
		endif;
	else:
		CPrice::Add($arFields);
		echo $arTP['offer_id'].' - '.$arOffer['ID'].'<br>';
		//echo 'newprice<hr>';
	endif;
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