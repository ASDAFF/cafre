<?php
$_SERVER["DOCUMENT_ROOT"] = '/var/www/www-root/data/www/test.cafre.ru';
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
require($_SERVER["DOCUMENT_ROOT"].'/phpQuery.php');
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/0.log', date('d.m.Y H:i:s'));
function get_xml_page($url) {
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 $page = curl_exec($ch);
 curl_close($ch);
 return $page;
}
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
$urls=array();
//----Sec----//
global $GLOBAL;
$arFilter = array('IBLOCK_ID' => 26, "ACTIVE"=>"Y"); 
$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
while ($arSect = $rsSect->GetNext()) {
	$GLOBAL=array();	
	if( !(strpos($arSect['SECTION_PAGE_URL'], '/vse_brendy/')===false)) {			
		if($arSect["DEPTH_LEVEL"] > 2) continue;
		$urls[]='https://cafre.ru'.$arSect["SECTION_PAGE_URL"];
		continue;
	}	
	$urls[]='https://cafre.ru'.$arSect["SECTION_PAGE_URL"];	
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
	foreach ($str as $key => $value) {
		$urls[]=$value;
	}	
}
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/2.csv', '');
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/0.csv', '');
foreach ($urls as $key => $value) {
	if(strpos($value, 'schwarzkopf_professional	')) continue;
	if($key<1690) continue;
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/0.csv', $value.PHP_EOL, FILE_APPEND);
	sleep(1);
	$results_page = get_xml_page($value);
    $results = phpQuery::newDocument($results_page);
    $elements = $results->find('h1')->text();
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/2.csv', $elements.PHP_EOL, FILE_APPEND);
}
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/0/0.log', date('d.m.Y H:i:s').PHP_EOL, FILE_APPEND);
//----EndSec----//
?>