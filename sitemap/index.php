<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.map",
	"tz_smap",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COL_NUM" => "1",
		"COMPONENT_TEMPLATE" => "tz_smap",
		"LEVEL" => "3",
		"SET_TITLE" => "Y",
		"SHOW_DESCRIPTION" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>