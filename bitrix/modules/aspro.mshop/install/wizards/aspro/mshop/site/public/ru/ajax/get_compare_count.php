<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	header('Content-type: application/json');
	$APPLICATION->IncludeComponent("bitrix:catalog.compare.list","empty",Array("IBLOCK_TYPE" => "aspro_mshop_catalog","IBLOCK_ID" => "#IBLOCK_CATALOG_ID#"), false, array("HIDE_ICONS"=>"Y"));
?>