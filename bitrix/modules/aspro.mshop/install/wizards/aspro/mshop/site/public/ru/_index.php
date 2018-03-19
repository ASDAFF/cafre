<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Классный интернет-магазин");
?>
<?global $SITE_THEME, $TEMPLATE_OPTIONS;?>
<?$APPLICATION->IncludeComponent(
	"aspro:com.banners.mshop", 
	"top_slider_banners", 
	array(
		"IBLOCK_TYPE" => "aspro_mshop_adv",
		"IBLOCK_ID" => "#IBLOCK_BANNERS_ID#",
		"TYPE_BANNERS_IBLOCK_ID" => "#IBLOCK_BANNERS_TYPES_ID#",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "10",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "TEXT_POSITION",
			1 => "TARGETS",
			2 => "TEXTCOLOR",
			3 => "URL_STRING",
			4 => "BUTTON1TEXT",
			5 => "BUTTON1LINK",
			6 => "BUTTON2TEXT",
			7 => "BUTTON2LINK",
			8 => "",
		),
		"CHECK_DATES" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"SITE_THEME" => $SITE_THEME,
		"BANNER_TYPE_THEME" => "TOP"
	),
	false
);?>

<div class="wrapper_inner">
	<?$APPLICATION->IncludeComponent("bitrix:news.list", "mshop", array(
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"IBLOCK_ID" => "#IBLOCK_TIZERS_ID#",
		"NEWS_COUNT" => "5",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "LINK",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
		),
		false
	);?>
</div>
<div class="wrapper_inner wides">
	<?$APPLICATION->IncludeComponent(
		"aspro:com.banners.mshop", 
		"mshop", 
		array(
			"IBLOCK_TYPE" => "aspro_mshop_adv",
			"IBLOCK_ID" => "#IBLOCK_BANNERS_ID#",
			"TYPE_BANNERS_IBLOCK_ID" => "#IBLOCK_BANNERS_TYPES_ID#",
			"SET_BANNER_TYPE_FROM_THEME" => "N",
			"NEWS_COUNT" => "6",
			"SORT_BY1" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_BY2" => "ID",
			"SORT_ORDER2" => "DESC",
			"PROPERTY_CODE" => array(
				0 => "TEXT_POSITION",
				1 => "TARGETS",
				2 => "TEXTCOLOR",
				3 => "URL_STRING",
				4 => "BUTTON1TEXT",
				5 => "BUTTON1LINK",
				6 => "BUTTON2TEXT",
				7 => "BUTTON2LINK",
				8 => "",
			),
			"CHECK_DATES" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"SITE_THEME" => $SITE_THEME,
			"BANNER_TYPE_THEME" => "FLOAT"
		),
		false
	);?>
</div>
<div class="grey_bg">
	<div class="wrapper_inner">
		<?$APPLICATION->IncludeComponent(
			"aspro:tabs.mshop", 
			"main", 
			array(
				"IBLOCK_TYPE" => "aspro_mshop_catalog",
				"IBLOCK_ID" => "#IBLOCK_CATALOG_ID#",
				"SECTION_ID" => "",
				"SECTION_CODE" => "",
				"TABS_CODE" => "HIT",
				"SECTION_USER_FIELDS" => array(
					0 => "",
					1 => "",
				),
				"ELEMENT_SORT_FIELD" => "sort",
				"ELEMENT_SORT_ORDER" => "asc",
				"ELEMENT_SORT_FIELD2" => "id",
				"ELEMENT_SORT_ORDER2" => "desc",
				"FILTER_NAME" => "arrFilterProp",
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_ALL_WO_SECTION" => "Y",
				"HIDE_NOT_AVAILABLE" => "N",
				"PAGE_ELEMENT_COUNT" => "20",
				"LINE_ELEMENT_COUNT" => "5",
				"PROPERTY_CODE" => array(
					0 => "",
					1 => "",
				),
				"OFFERS_LIMIT" => "10",
				"SECTION_URL" => "",
				"DETAIL_URL" => "",
				"BASKET_URL" => "#SITE_DIR#basket/",
				"ACTION_VARIABLE" => "action",
				"PRODUCT_ID_VARIABLE" => "id",
				"PRODUCT_QUANTITY_VARIABLE" => "quantity",
				"PRODUCT_PROPS_VARIABLE" => "prop",
				"SECTION_ID_VARIABLE" => "SECTION_ID",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "250000",
				"CACHE_GROUPS" => "N",
				"META_KEYWORDS" => "-",
				"META_DESCRIPTION" => "-",
				"BROWSER_TITLE" => "-",
				"ADD_SECTIONS_CHAIN" => "N",
				"DISPLAY_COMPARE" => "Y",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"CACHE_FILTER" => "Y",
				"PRICE_CODE" => array(
					0 => "BASE",
				),
				"USE_PRICE_COUNT" => "N",
				"SHOW_PRICE_COUNT" => "1",
				"PRICE_VAT_INCLUDE" => "Y",
				"PRODUCT_PROPERTIES" => array(
				),
				"USE_PRODUCT_QUANTITY" => "N",
				"CONVERT_CURRENCY" => "N",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"PAGER_TITLE" => "Товары",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => ".default",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"DISCOUNT_PRICE_CODE" => "",
				"AJAX_OPTION_ADDITIONAL" => "",
				"SHOW_ADD_FAVORITES" => "Y",
				"SECTION_NAME_FILTER" => "",
				"SECTION_SLIDER_FILTER" => "21",
				"COMPONENT_TEMPLATE" => "main",
				"OFFERS_FIELD_CODE" => array(
					0 => "ID",
					1 => "",
				),
				"OFFERS_PROPERTY_CODE" => array(
					0 => "",
					1 => "",
				),
				"OFFERS_SORT_FIELD" => "sort",
				"OFFERS_SORT_ORDER" => "asc",
				"OFFERS_SORT_FIELD2" => "id",
				"OFFERS_SORT_ORDER2" => "desc",
				"SHOW_MEASURE" => "Y",
				"OFFERS_CART_PROPERTIES" => array(
				),
				"DISPLAY_WISH_BUTTONS" => "Y",
				"SHOW_DISCOUNT_PERCENT" => "Y",
				"SHOW_OLD_PRICE" => "Y"
			),
			false
		);?>
	</div>
</div>
<div class="wrapper_inner">
	<?$APPLICATION->IncludeComponent("bitrix:news.list", "brands_slider", array(
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"IBLOCK_ID" => "#IBLOCK_BRANDS_ID#",
		"NEWS_COUNT" => "9999",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
		),
		false
	);?>
	<hr class="grey" />
	<div class="wrap_md">
		<div class="news_wrap">
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list", 
				"news_front", 
				array(
					"IBLOCK_TYPE" => "aspro_mshop_content",
					"IBLOCK_ID" => "#IBLOCK_NEWS_ID#",
					"NEWS_COUNT" => "2",
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "SORT",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "",
					"FIELD_CODE" => array(
						0 => "",
						1 => "",
					),
					"PROPERTY_CODE" => array(
						0 => "",
						1 => "",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "140",
					"ACTIVE_DATE_FORMAT" => "j F Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => "",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"COMPONENT_TEMPLATE" => "news_front",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"TITLE_BLOCK" => "Новости",
					"ALL_URL" => "company/news/"
				),
				false
			);?>
		</div>
		<div class="subscribe_wrap">
			<?$APPLICATION->IncludeComponent(
	"bitrix:subscribe.form", 
	"main", 
	array(
		"AJAX_MODE" => "N",
		"SHOW_HIDDEN" => "N",
		"ALLOW_ANONYMOUS" => "Y",
		"SHOW_AUTH_LINKS" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_NOTES" => "",
		"SET_TITLE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"LK" => "Y",
		"COMPONENT_TEMPLATE" => "main",
		"USE_PERSONALIZATION" => "Y",
		"PAGE" => SITE_DIR."personal/subscribe/",
		"URL_SUBSCRIBE" => SITE_DIR."subscribe/"
	),
	false
);?>
		</div>
	</div>
</div>
<div class="grey_bg">
	<div class="wrapper_inner">
		<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"news_akc_slider", 
	array(
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"IBLOCK_ID" => "#IBLOCK_STOCK_ID#",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "140",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "news_akc_slider",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"TITLE_BLOCK" => "Действующие акции",
	),
	false
);?>
	</div>
</div>
<?if($TEMPLATE_OPTIONS["STORES"]["CURRENT_VALUE"] != 'NO'):?>
	<?if($TEMPLATE_OPTIONS["STORES_SOURCE"]["CURRENT_VALUE"] != 'IBLOCK'):?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.store.list", 
			"mshop", 
			array(
				"SEF_MODE" => "N",
				"SEF_FOLDER" => "#SITE_DIR#contacts/stores/",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "86400",
				"PHONE" => "Y",
				"SCHEDULE" => "Y",
				"SET_TITLE" => "N",
				"TITLE" => "",
				"MAP_TYPE" => "1",
				"COMPONENT_TEMPLATE" => "mshop",
				"PATH_TO_ELEMENT" => "contacts/stores/#store_id#/",
				"TITLE_BLOCK" => "Наши магазины",
				"TYPE" => "LIGHT",
				"ALL_URL" => "contacts/stores/",
				"SITE_THEME" => $SITE_THEME,
			),
			false
		);?>
	<?else:?>
		<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"shops_front", 
	array(
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"IBLOCK_ID" => "#IBLOCK_SHOPS_ID#",
		"NEWS_COUNT" => "100",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "NAME",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "ADDRESS",
			1 => "PHONE",
			2 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"PREVIEW_TRUNCATE_LEN" => "140",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"TITLE_BLOCK" => "Наши магазины",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "shops_front",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_LAST_MODIFIED" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => ""
	),
	false
);?>
	<?endif;?>
<?endif;?>
<div class="grey_bg">
	<div class="wrapper_inner">
		<div class="wrap_md">
			<div class="md-50 img">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/front_img.php", Array(), Array( "MODE" => "html", "NAME" => GetMessage("FRONT_IMG"), )); ?>
			</div>
			<div class="md-50 big">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", "front", Array("AREA_FILE_SHOW" => "file","PATH" => SITE_DIR."include/front_info.php","EDIT_TEMPLATE" => ""));?>
			</div>
		</div>
	</div>
</div>
	<div class="wrapper_inner">
		<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.viewed.products", 
	"main", 
	array(
		"COMPONENT_TEMPLATE" => "main",
		"IBLOCK_TYPE" => "aspro_mshop_catalog",
		"IBLOCK_ID" => "#IBLOCK_CATALOG_ID#",
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"PAGE_ELEMENT_COUNT" => "10",
		"LINE_ELEMENT_COUNT" => "3",
		"TEMPLATE_THEME" => "blue",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "#SITE_DIR#basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_PRODUCTS_#IBLOCK_CATALOG_ID#" => "Y",
		"PROPERTY_CODE_#IBLOCK_CATALOG_ID#" => array(
			0 => "HIT",
			1 => "",
		),
		"CART_PROPERTIES_#IBLOCK_CATALOG_ID#" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_#IBLOCK_CATALOG_ID#" => "MORE_PHOTO",
		"LABEL_PROP_#IBLOCK_CATALOG_ID#" => "-",
		"TITLE_BLOCK" => "Ранее вы смотрели",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DISPLAY_COMPARE" => "Y",
		"PROPERTY_CODE_#IBLOCK_SKU_ID#" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_#IBLOCK_SKU_ID#" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_#IBLOCK_SKU_ID#" => "MORE_PHOTO",
		"OFFER_TREE_PROPS_#IBLOCK_SKU_ID#" => array(
			0 => "-",
		),
		"SHOW_MEASURE" => "Y"
	),
	false
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>