<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->RestartBuffer();
unset($arResult["COMBO"]);
$arResult['SEF_SET_FILTER_URL']=str_replace('/f-clear', '', $arResult['SEF_SET_FILTER_URL']);
if(substr_count($arResult['SEF_SET_FILTER_URL'], '-is-')==1 && substr_count($arResult['SEF_SET_FILTER_URL'], 'f-catalog_brend-is')==1 ) 
	$arResult['SEF_SET_FILTER_URL']=str_replace('f-catalog_brend-is-', '', $arResult['SEF_SET_FILTER_URL']);


echo CUtil::PHPToJSObject($arResult, true);
?>