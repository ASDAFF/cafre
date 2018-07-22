<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_general_multilevel", 
	array(
		"ROOT_MENU_TYPE" => "top_general",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "360000",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "4",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
	),
	false
);?>