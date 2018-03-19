<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
ini_set('max_execution_time', '0');
@ignore_user_abort(true);

?>

<div class="welcome-panel">
  <h2>Импорт товаров из CSV-файла</h2>
  <p>Загрузите файл формата CSV, дождитесь обработки файла. Это может занять некоторое время.</p>
<a href="?GO=on">Погнали</a>
<?php
if($_GET["GO"] == "on"){
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/product_data.csv';
 
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();

    foreach ($get_csv as $value) { //Проходим по строкам
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID");
$arFilter = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value[0]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>99999), $arSelect);
$el = new CIBlockElement;
$PROP = array();
$PROP["artIk"] = $value[1];  // свойству с кодом 12 присваиваем значение "Белый"

$arLoadProductArray = Array(
  "MODIFIED_BY"    => 1, // элемент изменен текущим пользователем
  "IBLOCK_ID"      => 26,
  "CODE" =>  $value[21],
  "PROPERTY_VALUES"=> $PROP,
  "NAME"           => $value[2],
  "ACTIVE"         => "Y",            // активен
  "XML_ID"		=> $value[0],
  "PREVIEW_TEXT_TYPE" =>"html",
  "DETAIL_TEXT_TYPE" =>"html",
  "PREVIEW_TEXT"   => $value[7],
  "DETAIL_TEXT"    => $value[8],
  "DETAIL_PICTURE" => false
  );
  
if($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 $res = $el->Update($arFields["ID"], $arLoadProductArray);
 echo '<br/>';
 echo 'update_tovar:'.$arFields["ID"];
 
}else{
	$PRODUCT_ID = $el->Add($arLoadProductArray);
	echo '<br/>';
	echo "New ID_tovar: ".$PRODUCT_ID;
}
	}
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}
}
?>

 </div>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");