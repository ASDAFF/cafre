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

$arTPcsv=file('category_data_final_nodescr.csv');
//=file('category_data_final_short.csv');
//=file($_SERVER["DOCUMENT_ROOT"].'/include/import/category_data.csv');
foreach($arTPcsv as $key=>$str):
	if($key>0):
		//echo $str;
		$arTP=explode(';', $str);
		$arCats[$arTP[0]]=array('cat_id'=>$arTP[0], 'name'=>$arTP[1], 'sort'=>$arTP[4], 'active'=>$arTP[5]);
	endif;
	//if($key>10) break;
endforeach;

//pr($arCats);
echo workTime($start_time).'<br>';
//exit;

//$dbs=CIBlockSection::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>26), false, array('ID', 'XML_ID', 'IBLOCK_SECTION_ID'));
$dbs=CIBlockSection::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>26, '<TIMESTAMP_X'=>'09.11.2017'), false, array('ID', 'XML_ID', 'SORT', 'TIMESTAMP_X'));
$i=0;
while ($arSect=$dbs->GetNext()):
	$arSectFromSite[$arSect['XML_ID']]=array('ID'=>$arSect['ID'], 'STAMP'=>$arSect['TIMESTAMP_X']);
	//$i++;
	//if($i>25) break;
endwhile;
echo sizeof($arSectFromSite);
//pr($arSectFromSite);
echo '<hr><hr>';
exit;
$bs = new CIBlockSection;
$i=0;
foreach ($arSectFromSite as $xml_id=>$arCatFromSite):
	if($arCats[$xml_id]['active']==1):
		$arFields=array('SORT'=>$arCats[$xml_id]['sort']);
	else:
		$arFields=array('SORT'=>$arCats[$xml_id]['sort'], 'ACTIVE'=>'N');
	endif;
	$sect_id=$arCatFromSite['ID'];
	echo 'xml='.$xml_id.'<br>';
	pr($arFields);
	if($resupd=$bs->Update($sect_id, $arFields)):
		echo $sect_id.'<br>';
		//continue;
	else:
		echo $bs->LAST_ERROR.'<hr>';
	endif;/**/
	$i++;
	//if($i>20):
		//echo $sect_id;
		break;
	//endif;
endforeach;
echo workTime($start_time).'<br>';
exit;



foreach ($arSectFromSite as $xml_id=>$arCatFromSite):
	if($arCats[$xml_id]['parent']==0) 
		continue;/**/
	if(!$arCatFromSite['SECTION_ID']):
		//$arFields = array('IBLOCK_ID'=>26, 'NAME'=>$arCat['name'], 'XML_ID'=>$arCat['cat_id'], 'CODE'=>$arCat['code']);
		$sect_id=$arCatFromSite['ID'];
		$arFields = array('IBLOCK_SECTION_ID'=>$arSectFromSite[$arCats[$xml_id]['parent']]['ID']);
		//echo '<hr>'.$sect_id;
		//pr($arCatFromSite);
		//pr($arFields);
		//pr($arCats[$xml_id]);
		/*if($ID = $bs->Add($arFields))
			echo $ID.'<br>';
		else
			echo $bs->LAST_ERROR.'<br>';*/
		/*if($resupd=$bs->Update($sect_id, $arFields)):
			echo $sect_id.'<br>';
		else:
			echo $bs->LAST_ERROR.'<hr>';
		endif;*/
		//echo '<br>'.$i.'<hr>';
		$i++;
		if($i>99) break;
	endif;
endforeach;/**/

echo workTime($start_time).'<br>';
exit;
//exit;

//$res=CIBlockElement::GetList(array(), array('IBLOCK_ID'=>27, 'XML_ID'=>$arTPs[0]['offer_id']), false, false, array('ID', 'IBLOCK_ID', 'NAME'));
//$arOffer=$res->GetNext();
//pr($arOffer);

/*
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


*/
?>


<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>