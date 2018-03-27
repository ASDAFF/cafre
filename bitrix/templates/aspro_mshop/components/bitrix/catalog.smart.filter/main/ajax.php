<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->RestartBuffer();
unset($arResult["COMBO"]);
$arResult['SEF_SET_FILTER_URL']=str_replace('/f-clear', '', $arResult['SEF_SET_FILTER_URL']);
echo CUtil::PHPToJSObject($arResult, true);
?>