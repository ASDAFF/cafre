<?
	$arSections = array();
	foreach( $arResult["SECTIONS"] as $arItem ):
		if( $arItem["DEPTH_LEVEL"] == 1 ):
			$arSections[$arItem["ID"]] = $arItem;
		elseif( $arItem["DEPTH_LEVEL"] == 2 ):
			$arSections[$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;
		endif;
	endforeach;
	
	$arResult["SECTIONS"] = $arSections;
?>