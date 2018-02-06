<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
	CModule::IncludeModule('iblock');
	
	$arSelect = Array("ID", "NAME", "XML_ID");
	$arFilter = Array("IBLOCK_ID"=>24, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$arSelect2 = Array("ID", "NAME", "XML_ID","PROPERTY_CML2_LINK");
	$arFilter2 = Array("IBLOCK_ID"=>25, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>9999), $arSelect);
	$res2 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize"=>9999), $arSelect2);


	/*
	 * $res->arResult
	 * $res2->arResult
	 */

	foreach ($res->arResult as $key) {
		$id = $key["ID"];
		$xml = $key["XML_ID"];

		foreach ($res2->arResult as $key2) {
			if($key2["XML_ID"] == $xml) {
				// CIBlockElement::SetPropertyValuesEx($key2["ID"], false, array("CML2_LINK" => $id));
				echo "<pre>";
				print_r($key2);
				echo "</pre>";
			}
		}
	}
?>