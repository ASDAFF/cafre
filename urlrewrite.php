<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/([\\w\\d\\-]+)?(/)?(([\\w\\d\\-]+)(/)?)?#",
		"RULE" => "REQUEST_OBJECT=\$1&METHOD=\$4",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/personal/history-of-orders/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.order",
		"PATH" => "/personal/history-of-orders/index.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/personal/order/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.order",
		"PATH" => "/personal/order/index.php",
	),
	array(
		"CONDITION" => "#^/info/articles/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/info/articles/index.php",
	),
	array(
		"CONDITION" => "#^/company/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/news/index.php",
	),
	array(
		"CONDITION" => "#^/info/article/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/info/article/index.php",
	),
	array(
		"CONDITION" => "#^/info/brands/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/info/brands/index.php",
	),
	array(
		"CONDITION" => "#^/info/brand/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/info/brand/index.php",
	),
	array(
		"CONDITION" => "#^/products/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/products/index.php",
	),
	array(
		"CONDITION" => "#^/services/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/services/index.php",
	),
	array(
		"CONDITION" => "#^/catalog/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/sale/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/sale/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),
);

?>