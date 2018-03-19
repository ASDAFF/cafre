<?$APPLICATION->IncludeComponent(
	"aspro:social.info.mshop", 
	".default", 
	array(
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_GROUPS" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"VK" => "#SITE_VK#",
		"ODN" => "#SITE_ODNOKLASSNIKI#",
		"FACE" => "#SITE_FACEBOOK#",
		"TWIT" => "#SITE_TWITTER#",
		"MAIL" => "#SITE_MAILRU#",
		"INST" => "#SITE_INSTAGRAM#"
	),
	false
);?> 