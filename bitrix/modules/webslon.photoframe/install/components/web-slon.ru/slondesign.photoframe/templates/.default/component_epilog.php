<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
   die();
   
if($arResult["LOAD_JQUERY"]=="Y") CJSCore::Init("jquery");

if($arResult["LOAD_FANCYBOX"]=="Y"){
	if(!isset($GLOBALS["WEBSLON_FANCYBOX_LOADED"]) || !$GLOBALS["WEBSLON_FANCYBOX_LOADED"]){
	
		
		// Add mousewheel plugin (this is optional)
		$APPLICATION->AddHeadScript($templateFolder."/fancybox/lib/jquery.mousewheel-3.0.6.pack.js");
	
		// Add fancyBox main JS and CSS files
		$APPLICATION->AddHeadScript($templateFolder."/fancybox/source/jquery.fancybox.js");
		$APPLICATION->SetAdditionalCSS($templateFolder."/fancybox/source/jquery.fancybox.css");
	
		// Add Button helper (this is optional)
		$APPLICATION->SetAdditionalCSS($templateFolder."/fancybox/source/helpers/jquery.fancybox-buttons.css");
		$APPLICATION->AddHeadScript($templateFolder."/fancybox/source/helpers/jquery.fancybox-buttons.js");
	
		// Add Thumbnail helper (this is optional)
		$APPLICATION->SetAdditionalCSS($templateFolder."/fancybox/source/helpers/jquery.fancybox-thumbs.css");
		$APPLICATION->AddHeadScript($templateFolder."/fancybox/source/helpers/jquery.fancybox-thumbs.js");
	
		// Add Media helper (this is optional)
		$APPLICATION->AddHeadScript($templateFolder."/fancybox/source/helpers/jquery.fancybox-media.js");

		$GLOBALS["WEBSLON_FANCYBOX_LOADED"] = true;
	}	
}
if($arResult["USE_FANCYBOX"]=="Y" || $arResult["USE_3D"]=="Y"){
	if(!isset($GLOBALS["WEBSLON_PHOTOFRAME_JS_LOADED"]) || !$GLOBALS["WEBSLON_PHOTOFRAME_JS_LOADED"]){
		$APPLICATION->AddHeadScript($templateFolder."/js/webslon.photoframe.js");
		$GLOBALS["WEBSLON_PHOTOFRAME_JS_LOADED"] = true;
	}
}   
?>