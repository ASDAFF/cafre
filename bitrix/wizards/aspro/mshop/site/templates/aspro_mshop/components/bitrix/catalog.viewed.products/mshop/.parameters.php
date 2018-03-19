<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"TITLE_BLOCK" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "Ранее вы смотрели",
	),
	"DISPLAY_WISH_BUTTONS" => Array(
		"NAME" => GetMessage("DISPLAY_WISH_BUTTONS_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_COMPARE" => Array(
		"NAME" => GetMessage("DISPLAY_COMPARE_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_MEASURE" => Array(
		"NAME" => GetMessage("SHOW_MEASURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
?>
