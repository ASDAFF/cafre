<?
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
 CModule::IncludeModule("iblock");
 CModule::IncludeModule("catalog");
 include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php"); //Класс для открытия файа csv

$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/SecListNew2.csv'; //Сам файл csv
try {
/*
Функция записи в файл, делал чтобы посмотреть что выводит, так как вылазила ошибка 504, потому что очень много товаров, а по 1 не нужно выводить, так как связь работает
	function object2file($value, $filename)
{
    $str_value = serialize($value);
    
    $f = fopen($filename, 'w');
    fwrite($f, $str_value);
    fclose($f);
}
*/
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();
 $result = array();
    foreach ($get_csv as $value2) {//получаем csv по столбцам, все уже настроенно, выбор идет по коду 1с
	if($value2[3] == "SECTION_ID" || $value2[3] == "#Н/Д"){continue;} //исключал товары у которых нет id разделов
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$value2[0]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C'));

	if($ob2 = $res->GetNextElement()){
	$arFields2 = $ob2->GetFields();
		//echo $value2[3];
		//записываем в массив , данные об id товара и его связь со столбцом id разделов, в csv они уже такие как на сайте
		$result[$arFields2["ID"]][] = $value2[3];
		
	}
/*	
Старый вариант связи
	$arr[] = Array("ID"=>$value2[3]);
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
/*Здесь обращение к функции для записи txt
object2file($result, 'array.txt');

echo '<pre>';
print_r($result);
echo '</pre>';
*/

//Здесь связываем товары с разделами, даже если у товара много разделов, закоментировал, чтобы при открытии ничего сразу не стало привязыватся
	foreach($result as $key => $val){
//CIBlockElement::SetElementSection($key, $val);
	}

	
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}

?>