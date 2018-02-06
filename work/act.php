<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");


$num=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, 'PREVIEW_PICTURE'=>false), array()); //'ACTIVE'=>'N'
echo $num.'<br>';
$num2=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, 'PREVIEW_PICTURE'=>false, 'PROPERTY_MORE_PHOTO'=>false), array()); //'ACTIVE'=>'N'
echo $num2.'<br>';
exit;
$dbb=CIBlockElement::GetList(array('ID'=>'ASC'), array('IBLOCK_ID'=>26, 'PREVIEW_PICTURE'=>false, 'PROPERTY_MORE_PHOTO'=>false), false, false, array('IBLOCK_ID', 'ID', 'XML_ID', 'NAME'));
$i=0;
file_put_contents('nopict.txt','');
while($arP=$dbb->GetNext()):
	//pr($arP);
	$i++;
	file_put_contents('nopict.txt',$arP['XML_ID'].';'.$arP['NAME']."\n", FILE_APPEND);
	//if($i>10) break;
endwhile;
echo '<hr>';
exit;

$arTPcsv=file($_SERVER["DOCUMENT_ROOT"].'/include/import/product_data33.csv');
foreach($arTPcsv as $key=>$str):
	if($key>0):
		//echo $str;
		$arTP=explode(';', $str);
		if($arTP[1]==0):
			$arTPs[trim($arTP[0])]=array('xml_id'=>trim($arTP[0]), 'active'=>$arTP[1]);
		endif;
	endif;
	//if($key>7500) break;
endforeach;
echo sizeof($arTPs).'<hr>';
//pr($arTPs);
//exit;

//, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'PROPERTY_CODE1C', 'IBLOCK_SECTION_ID', 'ACTIVE'));
	$el = new CIBlockElement;
	$arFields=array('ACTIVE'=>'Y');
	$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, 'ACTIVE'=>'N'), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'ACTIVE'));
	while($arP=$dbb->GetNext()):
		$arNoact[$arP['XML_ID']]=$arP;
		if(is_array($arTPs[$arP['XML_ID']])):
			$inoact++;
			//echo $arP['ID'].' '.$arP['XML_ID'].'<br>';
		else:
			$iact++;
			$el->Update($arP['ID'], $arFields);
			//echo $arP['ID'].' '.$arP['XML_ID'].'<br>';
			if($iact>500) break;
		endif;
	endwhile;
echo 'iact='.$iact.', inoact='.$inoact.'<br>';

//$arNoP=array_diff_key($arTPs, $arNoact);
//pr($arNoP);

?>
