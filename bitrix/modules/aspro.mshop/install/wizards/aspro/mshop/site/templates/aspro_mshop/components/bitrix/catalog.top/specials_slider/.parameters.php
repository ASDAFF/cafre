<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"FILTER_NAME" => Array(
		"NAME" => GetMessage("FILTER_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "arrTopFilter",
	),
	"SHOW_MEASURE" => Array(
		"NAME" => GetMessage("SHOW_MEASURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"SPECIALS_CODE1" => Array(
		"NAME" => GetMessage("SPECIALS_CODE1"),
		"TYPE" => "STRING",
		"DEFAULT" => "STOCK",
	),
	"SPECIALS_CODE2" => Array(
		"NAME" => GetMessage("SPECIALS_CODE2"),
		"TYPE" => "STRING",
		"DEFAULT" => "HIT",
	),
	"SPECIALS_CODE3" => Array(
		"NAME" => GetMessage("SPECIALS_CODE3"),
		"TYPE" => "STRING",
		"DEFAULT" => "RECOMMEND",
	),
	"SPECIALS_CODE4" => Array(
		"NAME" => GetMessage("SPECIALS_CODE4"),
		"TYPE" => "STRING",
		"DEFAULT" => "NEW",
	),
);
?>
