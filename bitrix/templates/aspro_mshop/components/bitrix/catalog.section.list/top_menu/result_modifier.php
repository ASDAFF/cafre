<?
	$arSections = array();
	$step2=0;
	foreach( $arResult["SECTIONS"] as $arItem ):
	if($arItem["ELEMENT_CNT"]==0) continue;
		if( $arItem["DEPTH_LEVEL"] == 1 ):
			$arSections[$arItem["ID"]] = $arItem;
		elseif( $arItem["DEPTH_LEVEL"] == 2 ):
			$step2=$arItem["IBLOCK_SECTION_ID"];
			$arSections[$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;
		elseif( $arItem["DEPTH_LEVEL"] == 3 ):
			$arSections[$step2]['SECTIONS'][$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;
		endif;
	endforeach;
	
	$arResult["SECTIONS"] = $arSections;
?>