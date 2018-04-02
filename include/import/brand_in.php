<?php
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
?>
  <?php
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/YesBrand.csv';
/* function object2file($value, $filename)
{
    $str_value = serialize($value);
    
    $f = fopen($filename, 'w');
    fwrite($f, $str_value);
    fclose($f);
}
function object_from_file($filename)
{
    $file = file_get_contents($filename);
    $value = unserialize($file);
    return $value;
}
*/
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();
 $result = array();
    foreach ($get_csv as $key => $value2) {
		/*$arSelect2 = Array("ID", "NAME", "XML_ID", "PROPERTY_CATALOG_BREND");
		$arFilter2 = Array("IBLOCK_ID"=>26, "PROPERTY_CATALOG_BREND" => FALSE, "ID"=>$value2[1]); //,"XML_ID"=>$value2[0]
$res = CIBlockElement::GetList(Array(), $arFilter2, false, Array(), $arSelect2);
if($ar_fields = $res->GetNext())
	{
		if($value2[4] == "Аксеcсуары Dewal")$value2[4] = "Dewal";
		if($value2[4] == "Кинетикс")$value2[4] = "Kinetics";
		if($value2[4] == "Lucas' Cosmetics")$value2[4] = "Lucas` Cosmetics";
		if($value2[4] == "Kanebo (пенка в форме розочки)")$value2[4] = "Kanebo";
		if($value2[4] == "Camillen 60")$value2[4] = "Camillen";
		if($value2[4] == "Brasil Cacau Professional")$value2[4] = "Brasil Cacau";
$arFilter = array('IBLOCK_ID' => 26, 'NAME'=>$value2[4].'%');
$rsSections = CIBlockSection::GetList(array(), $arFilter);
if($arSection = $rsSections->Fetch())
{
	//print_r($arSection);
	echo '<pre>';
	echo "NAME: ".$ar_fields["NAME"]."<br />";
	echo "ID: ".$ar_fields["ID"]."<br />";
	echo "PROPERTY_CATALOG_BREND: ".$ar_fields["PROPERTY_CATALOG_BREND_VALUE"]."<br />";
	echo "ID_SEC: ".$arSection["ID"]."<br />";
	echo "NAME_SEC: ".$arSection["NAME"]."<br />";
	echo '</pre>';
	//CIBlockElement::SetPropertyValueCode($ar_fields["ID"], "CATALOG_BREND", $arSection["ID"]);
		
}
		
	}*/
	}
	
	$arSelect2 = Array("ID", "NAME", "XML_ID", "PROPERTY_CATALOG_BREND");
		$arFilter2 = Array("IBLOCK_ID"=>26, "PROPERTY_CATALOG_BREND" => FALSE); //,"XML_ID"=>$value2[0]
$res = CIBlockElement::GetList(Array(), $arFilter2, false, Array(), $arSelect2);
$i = 0;
while($ar_fields = $res->GetNext())
	{
		echo '<pre>';
		print_r($ar_fields);
		echo '</pre>';
		echo $i++;
	}

//CIBlockElement::SetPropertyValuesEx(90132, false, array("CATALOG_BREND" => 6212));
//
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}
?>
