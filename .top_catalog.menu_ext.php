<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	global $APPLICATION;
	$aMenuLinksExt = $APPLICATION->IncludeComponent(
		"bitrix:menu.sections", "",
		Array(
            /*"IBLOCK_TYPE" => "aspro_mshop_catalog", 
            "IBLOCK_ID" => "13",*/			
            "IBLOCK_TYPE" => "new_cat", 
			"IBLOCK_ID" => "26", 
			"DEPTH_LEVEL" => "3", 
			"CACHE_TYPE" => "Y", 
			"CACHE_TIME" => "3600",
		)
	);
	$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);



?>