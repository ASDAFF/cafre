<?
$arResult['TITLE'] = (in_array('NAME', $arParams['FIELD_CODE']) ? $arResult['NAME'] : '');
$arResult['ADDRESS'] = (in_array('ADDRESS', $arParams['PROPERTY_CODE']) ? $arResult['PROPERTIES']['ADDRESS']['VALUE'] : '');
$arResult['ADDRESS'] = $arResult['TITLE'].((strlen($arResult['TITLE']) && strlen($arResult['ADDRESS'])) ? ', ' : '').$arResult['ADDRESS'];
$_SESSION['SHOP_TITLE'] = $arResult['ADDRESS'];
?>