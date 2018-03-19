<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("3Dmodel_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("3Dmodel_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/ys_wt.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(	
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "rz_core",
			"NAME" => GetMessage("ROMZA_CORE"),
			"SORT" => 30
		)

	),
);

?>