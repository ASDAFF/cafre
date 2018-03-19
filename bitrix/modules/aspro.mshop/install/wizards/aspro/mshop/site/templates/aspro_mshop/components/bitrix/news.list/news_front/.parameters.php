<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"TITLE_BLOCK" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "Новости",
	),
	"ALL_URL" => Array(
		"NAME" => GetMessage("ALL_URL_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "company/news/",
	),
);
?>
