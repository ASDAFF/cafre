<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!$USER->IsAuthorized()){
	LocalRedirect(SITE_DIR."auth/?backurl=".SITE_DIR."order/");
}
?>