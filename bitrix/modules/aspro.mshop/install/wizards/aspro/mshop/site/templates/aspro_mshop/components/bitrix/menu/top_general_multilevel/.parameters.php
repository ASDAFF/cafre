<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
\Bitrix\Main\Loader::includeModule('catalog');
$arPrice = CCatalogIBlockParameters::getPriceTypesList();
$arTemplateParameters = array(
	"IBLOCK_CATALOG_TYPE" => Array(
		"NAME" => GetMessage("IBLOCK_CATALOG_TYPE"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"IBLOCK_CATALOG_ID" => Array(
		"NAME" => GetMessage("IBLOCK_CATALOG_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"IBLOCK_CATALOG_DIR" => Array(
		"NAME" => GetMessage("IBLOCK_CATALOG_DIR"),
		"TYPE" => "STRING",
		"DEFAULT" => "/catalog/",
	),
	"PRICE_CODE" => array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arPrice,
	),
);

?>
