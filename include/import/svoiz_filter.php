<?php
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
?>
  <?php
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/filters.csv';
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

	$arSelect2 = Array("ID", "NAME", "XML_ID", "PROPERTY_TYPE_COLOR", "PROPERTY_NUMBER_BASE", "PROPERTY_OTTENOC", "PROPERTY_COLOR", "PROPERTY_TYPE_VOLOS", "PROPERTY_SVIST_TOV", "PROPERTY_UHOD", "PROPERTY_OBIEM", "PROPERTY_CODE1C");
		//$num = intval($value2[1]); // если число влезает в PHP_INT_SIZE байт 
//$num = ltrim($value2[1], '0'); // если числа не будут отрицательными 
$num = preg_replace('!^(-?)0*!', '\\1', $value2[1]); // для всех ситуаций
$arFilter2 = Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C" => $num); //,"XML_ID"=>$value2[0]
$res = CIBlockElement::GetList(Array(), $arFilter2, false, Array(), $arSelect2);
	//$arf=array('IBLOCK_ID'=>26, "XML_ID" => $value[0]);
	//$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID', "NAME",'IBLOCK_ID', 'XML_ID', 'PROPERTY_CODE1C'));
	if($ar_fields = $res->GetNext())
	{
		
	//$mas[] = $ar_fields;
		/*if($value2[3] != "NULL"){
			if($ar_fields["PROPERTY_TYPE_COLOR_VALUE"] != FALSE) continue;
			$db_enum_list = CIBlockProperty::GetPropertyEnum("TYPE_COLOR", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[3]));
if($ar_enum_list = $db_enum_list->GetNext())
{
 //id property  echo $ar_enum_list["ID"].'<br/>';
// id tov echo $ar_fields["ID"].'<br/><br/>';
CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("TYPE_COLOR" => $ar_enum_list["ID"]));
}		
		}if($value2[4] != "NULL"){
			if($ar_fields["PROPERTY_NUMBER_BASE_VALUE"] != FALSE) continue;
			$db_enum_list = CIBlockProperty::GetPropertyEnum("NUMBER_BASE", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[4]));
if($ar_enum_list = $db_enum_list->GetNext())
{
CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("NUMBER_BASE" => $ar_enum_list["ID"]));
}
		}
		if($value2[5] != "NULL"){
			if($ar_fields["PROPERTY_OTTENOC_VALUE"] != FALSE) continue;
			$db_enum_list = CIBlockProperty::GetPropertyEnum("OTTENOC", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[5]));
if($ar_enum_list = $db_enum_list->GetNext())
{
 CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("OTTENOC" => $ar_enum_list["ID"]));
}
		}
		if($value2[6] != "NULL"){
			if($ar_fields["PROPERTY_COLOR_VALUE"] != FALSE) continue;
			$db_enum_list = CIBlockProperty::GetPropertyEnum("COLOR", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[6]));
if($ar_enum_list = $db_enum_list->GetNext())
{
 CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("COLOR" => $ar_enum_list["ID"]));
}
		}
		if($value2[7] != "NULL"){
			if($ar_fields["PROPERTY_TYPE_VOLOS_VALUE"] != FALSE) continue;
			$db_enum_list = CIBlockProperty::GetPropertyEnum("TYPE_VOLOS", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[7]));
if($ar_enum_list = $db_enum_list->GetNext())
{
 CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("TYPE_VOLOS" => $ar_enum_list["ID"]));
}
		}
		if($value2[8] != "NULL"){
			if($ar_fields["PROPERTY_SVIST_TOV_VALUE"] != FALSE) continue;
				$db_enum_list = CIBlockProperty::GetPropertyEnum("SVIST_TOV", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[8]));
if($ar_enum_list = $db_enum_list->GetNext())
{
CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("SVIST_TOV" => $ar_enum_list["ID"]));
}
		}
		
		
		if($value2[9] != "NULL"){
			if($ar_fields["PROPERTY_UHOD_VALUE"] != FALSE) continue;
						$db_enum_list = CIBlockProperty::GetPropertyEnum("UHOD", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[9]));
if($ar_enum_list = $db_enum_list->GetNext())
{
CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("UHOD" => $ar_enum_list["ID"]));
}
		}
		if($value2[10] != "NULL"){
			if($ar_fields["PROPERTY_OBIEM_VALUE"] != FALSE) continue;
								$db_enum_list = CIBlockProperty::GetPropertyEnum("OBIEM", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>$value2[10]));
if($ar_enum_list = $db_enum_list->GetNext())
{
CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("OBIEM" => $ar_enum_list["ID"]));
}
		}
		/*


	}
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

}
	}

/*
$db_enum_list = CIBlockProperty::GetPropertyEnum("TYPE_COLOR", Array(), Array("IBLOCK_ID"=>26, "VALUE"=>"Yes"));
if($ar_enum_list = $db_enum_list->GetNext())
{
  $db_important_news = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>26, "PROPERTY"=>array("TYPE_COLOR"=>$ar_enum_list["ID"])));
   echo '<pre>';
print_r($ar_enum_list);
echo '</pre>';
}

/*	foreach($result as $key => $val){
//CIBlockElement::SetElementSection($key, $val);*/
}
	}

	//object2file($mas, 'array2.txt');
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}

?>
