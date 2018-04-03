<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
$arSelect2 = Array("ID", "NAME", "XML_ID", "PROPERTY_CATALOG_BREND");
$arFilter2 = Array("IBLOCK_ID"=>26, "PROPERTY_CATALOG_BREND" => 4810); //,"XML_ID"=>$value2[0]
$res = CIBlockElement::GetList(Array(), $arFilter2, false, Array(), $arSelect2);
	while($ar_fields = $res->GetNext())
	{
		echo "<pre>";
		print_r($ar_fields);
		echo "</pre>";
		//CIBlockElement::SetPropertyValueCode($ar_fields["ID"], "CATALOG_BREND", 6691);
	}

?>