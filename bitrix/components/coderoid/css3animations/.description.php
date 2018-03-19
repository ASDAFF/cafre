<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeTemplateLangFile(__FILE__);

$arComponentDescription = array(
    "NAME" => GetMessage("CODEROID_css3animations_NAME"),
    "DESCRIPTION" => GetMessage("CODEROID_css3animations_DESCRIPTION"),
    "ICON" => "component.gif",
    "CACHE_PATH" => "Y",
    "SORT" => 70,
	"PATH" => array(
        "ID" => GetMessage("CODEROID_COMPONENTS"),
	),
);
?>