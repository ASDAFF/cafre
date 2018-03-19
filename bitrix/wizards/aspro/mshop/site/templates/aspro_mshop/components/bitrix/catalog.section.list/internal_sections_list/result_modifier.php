<?
global $MShopSectionID;
foreach($arResult["SECTIONS"] as $i => $arSection){
	$arPointers[$arSection['ID']] = &$arResult["SECTIONS"][$i];
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