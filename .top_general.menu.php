<?
global $APPLICATION;
$aMenuLinks = Array(
	Array(
		"����", 
		"/catalog/hits/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"�������", 
		"/catalog/new/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"�����", 
		"/catalog/sale/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"� ��������", 
		"/company/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"��������", 
		"/contacts/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"������, �������� � ������� ������", 
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