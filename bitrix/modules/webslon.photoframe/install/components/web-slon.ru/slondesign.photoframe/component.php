<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if ($this->StartResultCache()){
	$arResult["IMAGE_URL"] = $arParams["IMAGE_URL"];
	$arResult["IMAGE_WIDTH"] = $arParams["IMAGE_WIDTH"];
	$arResult["IMAGE_HEIGHT"] = $arParams["IMAGE_HEIGHT"];
	$arResult["IMAGE_ALT"] = $arParams["IMAGE_ALT"];
	$arResult["IMAGE_TITLE"] = $arParams["IMAGE_TITLE"];
	$arResult["USE_DYNAMIC_FRAME"] = $arParams["USE_DYNAMIC_FRAME"];
	$arResult["DYNAMIC_FRAME_SHADOW_URL"] = $arParams["DYNAMIC_FRAME_SHADOW_URL"];
	$arResult["DYNAMIC_FRAME_BORDER_WIDTH"] = $arParams["DYNAMIC_FRAME_BORDER_WIDTH"];
	$arResult["LOAD_FANCYBOX"] = $arParams["LOAD_FANCYBOX"];
	$arResult["LOAD_JQUERY"] = $arParams["LOAD_JQUERY"];
	$arResult["USE_FANCYBOX"] = $arParams["USE_FANCYBOX"];
	$arResult["USE_3D"] = $arParams["USE_3D"];
	$arResult["3D_IMAGES_COUNT"] = intval($arParams["3D_IMAGES_COUNT"]);
	for($i = 0; $i < $arResult["3D_IMAGES_COUNT"]; $i++){
		$arResult["3D_IMAGES_URL_".$i] = $arParams["3D_IMAGES_URL_".$i];
	};
	$arResult["CLEARFIX"] = $arParams["CLEARFIX"];
	
	if($arParams["FANCYBOX_TITLE"] != ""){
		$arResult["FANCYBOX_TITLE"] = $arParams["FANCYBOX_TITLE"];
	}else{
		$arResult["FANCYBOX_TITLE"] = $arParams["IMAGE_ALT"];
	};
	
	$style_text = "";
	if($arResult["USE_DYNAMIC_FRAME"]=="N"){
		if($arParams["FRAME_URL"] != ""){
			$style_text .= "background:url(".$arParams["FRAME_URL"].");";
		};
		if($arParams["FRAME_WIDTH"] != ""){
			$style_text .= "width:".$arParams["FRAME_WIDTH"]."px;";
		};
		if($arParams["FRAME_HEIGHT"] != ""){
			$style_text .= "height:".$arParams["FRAME_HEIGHT"]."px;";
		};
	}else{
		if($arParams["DYNAMIC_FRAME_BORDER_WIDTH"] != ""){
			$style_text .= "border-width: ".$arParams["DYNAMIC_FRAME_BORDER_WIDTH"]."px;";
		};
		if($arParams["DYNAMIC_FRAME_BORDER_COLOR"] != ""){
			$style_text .= "border-color: ".$arParams["DYNAMIC_FRAME_BORDER_COLOR"].";";
		};
		if($arParams["DYNAMIC_FRAME_BORDER_STYLE"] != ""){
			$style_text .= "border-style: ".$arParams["DYNAMIC_FRAME_BORDER_STYLE"].";";
		};				
	};
	$arResult["FRAME_STYLE"] = $style_text;

	$this->IncludeComponentTemplate();
}
?>
