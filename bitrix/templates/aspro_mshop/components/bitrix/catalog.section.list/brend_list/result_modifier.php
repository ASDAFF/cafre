<?
global $MShopSectionID;
global $min_level;
$min_level=0;
foreach($arResult["SECTIONS"] as $i => $arSection){
	if($min_level==0) $min_level=$arSection['DEPTH_LEVEL'];
	if($arSection['ELEMENT_CNT']==0||(!(strpos($arSection['SECTION_PAGE_URL'], 'vse_brendy/')===false)&&!$arParams['SECTION_ID'])) unset($arResult["SECTIONS"][$i]);
	else $arPointers[$arSection['ID']] = &$arResult["SECTIONS"][$i];
}
foreach($arResult["SECTIONS"] as $i => $arSection){		
	if(!$pid = $arSection['IBLOCK_SECTION_ID']){
		$arResult['SECTIONS_TREE'][] = &$arResult["SECTIONS"][$i];
	}

	$arResult["SECTIONS"][$i]['SELECTED'] = $arSection["ID"] === $MShopSectionID;
	$arPointers[$pid]['SECTIONS'][$arSection['ID']] = &$arResult["SECTIONS"][$i];
}

if($MShopSectionID){
	$pid = $arPointers[$MShopSectionID]['IBLOCK_SECTION_ID'];
	
	while($pid){
		$arPointers[$pid]['SELECTED'] = true;
		$pid = $arPointers[$pid]['IBLOCK_SECTION_ID'];
	}
}
?>