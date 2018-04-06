<?
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");

$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/valprice.csv';
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();
 $result = array();
    foreach ($get_csv as $key => $value2) {
		if($value2[1] == NULL || strchr($value2[1], "-"))continue;
		$value2[1] = intval(str_replace(' ','',$value2[1]));
		$arSelect2 = Array("ID", "NAME", "XML_ID", "PROPERTY_SUM_PROD", "PROPERTY_CODE1C");
		//$num = intval($value2[1]); // если число влезает в PHP_INT_SIZE байт 
//$num = ltrim($value2[1], '0'); // если числа не будут отрицательными 
$num = preg_replace('!^(-?)0*!', '\\1', $value2[0]); // для всех ситуаций
$arFilter2 = Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C" => $num, "PROPERTY_SUM_PROD" => FALSE); //,"XML_ID"=>$value2[0], 
$res = CIBlockElement::GetList(Array(), $arFilter2, false, Array(), $arSelect2);
if($ar_fields = $res->GetNext())
	{
echo '<pre>';
print_r($ar_fields);
print_r($value2[1]);
echo '</pre>';
//CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("SUM_PROD" => $value2[1]));
	}
	}
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}
?>