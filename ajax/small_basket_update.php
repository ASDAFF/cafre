<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("aspro.mshop");
?>
<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "top", array(
"PATH_TO_BASKET" => SITE_DIR."basket/",
"PATH_TO_ORDER" => SITE_DIR."order/"
)
);?>