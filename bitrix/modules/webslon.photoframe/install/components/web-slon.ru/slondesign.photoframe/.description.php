<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$arComponentDescription = array(
    "NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_COMPONENT_NAME"),
    "DESCRIPTION" => GetMessage("SLONDESIGN_PHOTOFRAME_COMPONENT_DESCR"),
    "ICON" => "/images/icon_popup.gif",
    "CACHE_PATH" => "Y",
    "PATH" => array(
		"ID" => "webslonComponentsMenu",
		"NAME" => GetMessage("WEBSLON_COMPONENTS_MENU"),
		"CHILD" => array(
			"ID" => "webslonComponentsDesignMenu",
			"NAME" => GetMessage("WEBSLON_COMPONENTS_DESIGN_MENU"),
		),
	),
);
?>
