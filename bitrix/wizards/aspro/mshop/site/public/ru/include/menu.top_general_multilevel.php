<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_general_multilevel", 
	array(
		"ROOT_MENU_TYPE" => "top_general",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "2",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"IBLOCK_CATALOG_TYPE" => "aspro_mshop_catalog",
		"IBLOCK_CATALOG_ID" => "#IBLOCK_CATALOG_ID#",
		"IBLOCK_CATALOG_DIR" => SITE_DIR."catalog/",
		"COMPONENT_TEMPLATE" => "top_general_multilevel",
		"PRICE_CODE" => array(
			0 => "BASE",
		)
	),
	false
);?>