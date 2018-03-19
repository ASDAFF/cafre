<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
?>

<div class="welcome-panel">
  <h2>Импорт свойств товаров из CSV-файла</h2>
  <p>Загрузите файл формата CSV, дождитесь обработки файла. Это может занять некоторое время.</p> 
  <a href="?GO=on">Погнали</a>
  <?php
 if($_GET["GO"] == "on"){
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/product_property_value_data.csv';
 
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();
$result = array();
    foreach ($get_csv as $value22) {
$iblock_id = 26;
$property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id, "XML_ID"=>$value22[1]));
if($enum_fields = $property_enums->GetNext())
{
$arSelect44 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID");
$arFilter44 = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value22[0]);
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>99999), $arSelect44);
if($ob44 = $res44->GetNextElement()){
		$arFields44 = $ob44->GetFields();
// Установим новое значение для данного свойства данного элемента
$result[$arFields44["ID"]][$enum_fields["PROPERTY_CODE"]] = array("ID" => $enum_fields["ID"]);
}
 }	
 }
 $v2 = array();
foreach($result as $key => $v){
CIBlockElement::SetPropertyValuesEx($key, 26, $v);

} 
 echo '<pre>';
print_r($result);
echo '</pre>';
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