<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	"main", 
	array(
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "PROPS",
			3 => "DELETE",
			4 => "DELAY",
			5 => "TYPE",
			6 => "PRICE",
			7 => "QUANTITY",
			8 => "SUM",
		),
		"OFFERS_PROPS" => array(
			0 => "SIZES",
			1 => "COLOR_REF",
		),
		"PATH_TO_ORDER" => SITE_DIR."order/",
		"HIDE_COUPON" => "N",
		"PRICE_VAT_SHOW_VALUE" => "Y",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"USE_PREPAYMENT" => "N",
		"SET_TITLE" => "N",
		"AJAX_MODE_CUSTOM" => "Y",
		"SHOW_MEASURE" => "Y",
		"PICTURE_WIDTH" => "100",
		"PICTURE_HEIGHT" => "100",
		"SHOW_FULL_ORDER_BUTTON" => "Y",
		"SHOW_FAST_ORDER_BUTTON" => "Y",
		"COMPONENT_TEMPLATE" => "main",
		"QUANTITY_FLOAT" => "N",
		"ACTION_VARIABLE" => "action"
	),
	false
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.bigdata.products", 
	"mshop", 
	array(
		"LINE_ELEMENT_COUNT" => "5",
		"TEMPLATE_THEME" => "blue",
		"DETAIL_URL" => "",
		"BASKET_URL" => SITE_DIR."basket/",
		"ACTION_VARIABLE" => "ACTION",
		"PRODUCT_ID_VARIABLE" => "ID",
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PRICE_COUNT" => "1",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PRICE_VAT_INCLUDE" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => $arParams["MESS_NOT_AVAILABLE"],
		"PAGE_ELEMENT_COUNT" => "20",
		"SHOW_FROM_SECTION" => "N",
		"IBLOCK_TYPE" => "aspro_mshop_catalog",
		"IBLOCK_ID" => "#IBLOCK_CATALOG_ID#",
		"DEPTH" => "2",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "N",
		"HIDE_NOT_AVAILABLE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_ELEMENT_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_ELEMENT_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"ID" => "",
		"={\"PROPERTY_CODE_\".\$arParams[\"IBLOCK_ID\"]}" => $arParams["LIST_PROPERTY_CODE"],
		"={\"CART_PROPERTIES_\".\$arParams[\"IBLOCK_ID\"]}" => $arParams["PRODUCT_PROPERTIES"],
		"RCM_TYPE" => "bestsell",
		"={\"OFFER_TREE_PROPS_\".\$ElementOfferIblockID}" => $arParams["OFFER_TREE_PROPS"],
		"={\"ADDITIONAL_PICT_PROP_\".\$ElementOfferIblockID}" => $arParams["OFFER_ADD_PICT_PROP"],
		"COMPONENT_TEMPLATE" => "basket",
		"SHOW_PRODUCTS_#IBLOCK_CATALOG_ID#" => "Y",
		"PROPERTY_CODE_#IBLOCK_CATALOG_ID#" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_#IBLOCK_CATALOG_ID#" => array(
		),
		"ADDITIONAL_PICT_PROP_#IBLOCK_CATALOG_ID#" => "MORE_PHOTO",
		"LABEL_PROP_#IBLOCK_CATALOG_ID#" => "-",
		"PROPERTY_CODE_#IBLOCK_SKU_ID#" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_#IBLOCK_SKU_ID#" => array(
			0 => "undefined",
		),
		"ADDITIONAL_PICT_PROP_#IBLOCK_SKU_ID#" => "MORE_PHOTO",
		"OFFER_TREE_PROPS_#IBLOCK_SKU_ID#" => array(
			0 => "-",
		),
		"DISPLAY_COMPARE" => "Y"
	),
	false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>