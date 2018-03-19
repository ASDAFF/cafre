<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");

?>

<div class="welcome-panel">
  <h2>Импорт свзяи товаров с разделами из CSV-файла</h2>
  <p>Загрузите файл формата CSV, дождитесь обработки файла. Это может занять некоторое время.</p> 
  <a href="?GO=on">Погнали</a>
  <?php
if($_GET["GO"] == "on"){
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/SecListNew2.csv';
 
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();
 $result = array();
    foreach ($get_csv as $value2) {
	if($value2[3] == "SECTION_ID" || $value2[3] == "#Н/Д"){continue;}
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$value[0]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C'));

	if($ar_fields = $res->GetNext())
	{
		//echo $value2[3];
		$result[$ar_fields["ID"]][] = $value2[3];
		
	}
/*		$arr[] = Array("ID"=>$value2[3]);
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
	}
echo '<pre>';
print_r($result);
echo '</pre>';

/*	foreach($result as $key => $val){
//CIBlockElement::SetElementSection($key, $val);
	}
*/
	
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}
}
?>
  <p id="response"></p>
 </div>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");