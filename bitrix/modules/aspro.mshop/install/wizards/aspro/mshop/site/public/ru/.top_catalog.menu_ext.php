<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	global $APPLICATION;
	$aMenuLinksExt = $APPLICATION->IncludeComponent(
		"bitrix:menu.sections", "",
		Array(
			"IBLOCK_TYPE" => "aspro_mshop_catalog", 
			"IBLOCK_ID" => "#IBLOCK_CATALOG_ID#", 
			"DEPTH_LEVEL" => "3", 
			"CACHE_TYPE" => "A", 
			"CACHE_TIME" => "3600",
		)
	);
	$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>