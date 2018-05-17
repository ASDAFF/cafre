<?php
$_SERVER["DOCUMENT_ROOT"] = '/var/www/www-root/data/www/cafre.ru';
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

	$APPLICATION->IncludeComponent(
		"lets:catalog.smart.filter",
		"tz_filter",
		Array(
			"HIDE_NOT_AVAILABLE"=> "L",
			"IBLOCK_TYPE" => "new_cat",
			"IBLOCK_ID" => 26,
			"SECTION_ID" => $arSect2['ID'],
			"FILTER_NAME" => "MSHOP_SMART_FILTER",
			"PRICE_CODE" => array(
			0 => "BASE",
			1 => "OPT",
			2 => "Интернет Розница",
			),
			"CACHE_TYPE" => "N",
			"CACHE_TIME" => "",
			"CACHE_NOTES" => "",
			"CACHE_GROUPS" => "N",
			"SAVE_IN_SESSION" => "N",
			"XML_EXPORT" => "Y",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			"SHOW_HINTS" => "Y",
			'CONVERT_CURRENCY' => "Y",
			'CURRENCY_ID' => "RUB",
			"INSTANT_RELOAD" => "Y",
			"VIEW_MODE" => "vertical",
			"SEF_MODE" => "Y",
			"SEF_RULE" => "/catalog/#SECTION_CODE_PATH#/f-#SMART_FILTER_PATH#/",
			"SMART_FILTER_PATH" => "",
			"SECTION_DETAIL_PAGE"=>$arSect2["SECTION_PAGE_URL"],
			"ROOT" => $root_filt,
			"DOOM" => $dom_filt,
		),
		$component);
  
?>