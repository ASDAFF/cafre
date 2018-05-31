<?php
$_SERVER["DOCUMENT_ROOT"] = '/var/www/www-root/data/www/test.cafre.ru';
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

//----Sec----//
global $GLOBAL;
$arFilter = array('IBLOCK_ID' => 26, "ACTIVE"=>"Y"); 
$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/vse_brendy.csv', '');
while ($arSect = $rsSect->GetNext()) {	
	$GLOBAL=''; 
	
	if( !(strpos($arSect['SECTION_PAGE_URL'], '/vse_brendy/')===false)) {			
		if($arSect["DEPTH_LEVEL"] > 2) continue;
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/vse_brendy.csv', 'https://cafre.ru'.$arSect["SECTION_PAGE_URL"].PHP_EOL, FILE_APPEND);	
		continue;
	}	
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/'.$arSect["CODE"].'.csv', '');
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/'.$arSect["CODE"].'.csv', 'https://cafre.ru'.$arSect["SECTION_PAGE_URL"].PHP_EOL, FILE_APPEND);	
	
	$APPLICATION->IncludeComponent(
		"lets:catalog.smart.filter",
		"main_ajax_sitemapCSV",
		Array(
			"HIDE_NOT_AVAILABLE"=> "L",
			"IBLOCK_TYPE" => "new_cat",
			"IBLOCK_ID" => 26,
			"SECTION_ID" => $arSect['ID'],
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
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			'CONVERT_CURRENCY' => "Y",
			'CURRENCY_ID' => "RUB",
			"VIEW_MODE" => "vertical",
			"SEF_RULE" => "/catalog/#SECTION_CODE_PATH#/f-#SMART_FILTER_PATH#/",
			"SMART_FILTER_PATH" => "",
			"SECTION_DETAIL_PAGE"=>$arSect["SECTION_PAGE_URL"],
			"TEK_URL"=>'https://cafre.ru'.$arSect["SECTION_PAGE_URL"],
		),
		$component
	);	
	$str=$GLOBAL;
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/'.$arSect["CODE"].'.csv', $str.PHP_EOL, FILE_APPEND);	
	
}

//----EndSec----//
?>