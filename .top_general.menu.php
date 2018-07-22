<?
global $APPLICATION;
$aMenuLinks = Array(
	Array(
		"Хиты", 
		"/catalog/hits/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Новинки", 
		"/catalog/new/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Акции", 
		"/catalog/sale/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"О компании", 
		"/company/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Контакты", 
		"/contacts/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Оплата, доставка и возврат товара", 
		"/help/info_order/", 
		Array(), 
		Array(), 
		"" 
	)
);
	$aMenuLinksExt = $APPLICATION->IncludeComponent(
		"lets:menu.sections", "",
		Array(		
            "IBLOCK_TYPE" => "new_cat", 
			"IBLOCK_ID" => "26", 
			"DEPTH_LEVEL" => "3", 
			"CACHE_TYPE" => "A", 
			"CACHE_TIME" => "360000",
		)
	);
	$aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks);	

?>