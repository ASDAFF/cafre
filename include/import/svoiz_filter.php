<?php
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
?>
  <?php
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/filters.csv';
 
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();
 $result = array();
    foreach ($get_csv as $value2) {

	//$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$value[1]);
	//$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C'));

	//if($ar_fields = $res->GetNext())
	//{
		//echo $value2[3];
		//$result[$ar_fields["ID"]][] = $value2[3];
		
	//}
/*	/	$arr[] = Array("ID"=>$value2[3]);
$arSelect2 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_CODE1C", "SECTION_ID");
$arFilter2 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_CODE1C"=>$value2[0]);
$res2 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize"=>100),$arSelect2);
if($ob2 = $res2->GetNextElement()){
		$arFields2 = $ob2->GetFields();
		$result[$arFields2["ID"]][] = $value2[3];
/*$itemsSection = GetIBlockSectionList(26,false,false,Array("sort"=>"asc"),Array($vv));
if($arItem = $itemsSection->GetNext()) { 
$result[$arFields2["ID"]][] = $arItem["ID"];
}

}*/
if($value2[4] != "NULL"){
	$ressss[$value2[4]][]="Тип краски";

}
	}
echo '<pre>';
print_r($ressss);
echo '</pre>';

/*	foreach($result as $key => $val){
//CIBlockElement::SetElementSection($key, $val);
	}
*/
	
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}

?>
