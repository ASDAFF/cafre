<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	global $APPLICATION;
	$aMenuLinksExt = $APPLICATION->IncludeComponent(
		"bitrix:menu.sections", "",
		Array(
            /*"IBLOCK_TYPE" => "aspro_mshop_catalog", 
            "IBLOCK_ID" => "13",*/			
            "IBLOCK_TYPE" => "new_cat", 
			"IBLOCK_ID" => "26", 
			"DEPTH_LEVEL" => "3", 
			"CACHE_TYPE" => "A", 
			"CACHE_TIME" => "3600",
		)
	);
	$db_sec = CIBlockSection::GetList(Array(), Array('IBLOCK_ID'=>26, 'CNT_ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y'), true, array('SECTION_PAGE_URL', 'IBLOCK_SECTION_ID'));																
	while($sec_result = $db_sec->GetNext()){
		if($sec_result['ELEMENT_CNT']>0 || $sec_result['IBLOCK_SECTION_ID']==5338) 	
			$no_epmty[]=$sec_result['SECTION_PAGE_URL'];
	}

	foreach($aMenuLinksExt as $k=> $link) {
		
		if(!in_array($link[1],  $no_epmty))
			unset($aMenuLinksExt[$k]);
	}
	$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);



?>