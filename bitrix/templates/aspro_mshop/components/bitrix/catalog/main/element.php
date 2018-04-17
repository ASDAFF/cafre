<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$arParams["ADD_ELEMENT_CHAIN"] = (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : "N");

CModule::IncludeModule("iblock");

if($arResult["VARIABLES"]["ELEMENT_CODE"]=='') $arResult["VARIABLES"]["ELEMENT_CODE"]=$arResult['VARIABLES']['SECTION_CODE_PATH'];



// get current section ID
global $MShopSectionID;
$section = array();
if($arResult["VARIABLES"]["SECTION_ID"] > 0){
	$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
	$section = $db_list->GetNext();
}
elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0){
	$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
	$section = $db_list->GetNext();
}
if(!$section["ID"]){
	if($arResult["VARIABLES"]["ELEMENT_ID"] > 0){

		$resElement = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "ID" => $arResult["VARIABLES"]["ELEMENT_ID"]), false, false, array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID"));
		//$arElement = $resElement->Fetch();
		while ($tovar = $resElement->GetNextElement())
		{
			$arElement = $tovar->GetFields(); 
		}
		$APPLICATION->AddHeadString('<link href="https://'.SITE_SERVER_NAME.$arElement['DETAIL_PAGE_URL'].'" rel="canonical" />',true);	
		if($arElement["IBLOCK_SECTION_ID"] && !$section){
			$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arElement["IBLOCK_SECTION_ID"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
			$section = $db_list->GetNext();
		}
	}
	elseif(strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0){
		$resElement = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]), false, false, array("ID","IBLOCK_ID", "DETAIL_PAGE_URL",  "IBLOCK_SECTION_ID"));
		//$arElement = $resElement->Fetch();
		while ($tovar = $resElement->GetNextElement())
		{
			$arElement = $tovar->GetFields(); 
		}
		$APPLICATION->AddHeadString('<link href="https://'.SITE_SERVER_NAME.$arElement['DETAIL_PAGE_URL'].'" rel="canonical" />',true);	
		if($arElement["IBLOCK_SECTION_ID"] && !$section){
			$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arElement["IBLOCK_SECTION_ID"]), true, array("ID", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], $arParams["SECTION_PREVIEW_PROPERTY"], "IBLOCK_SECTION_ID"));
			$section = $db_list->GetNext();
		}
	}
}
else {
	if($arResult["VARIABLES"]["ELEMENT_ID"] > 0){

		$resElement = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "ID" => $arResult["VARIABLES"]["ELEMENT_ID"]), false, false, array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID"));
		//$arElement = $resElement->Fetch();
		while ($tovar = $resElement->GetNextElement())
		{
			$arElement = $tovar->GetFields(); 
		}
		$APPLICATION->AddHeadString('<link href="https://'.SITE_SERVER_NAME.$arElement['DETAIL_PAGE_URL'].'" rel="canonical" />',true);	
	}
	elseif(strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0){
		$resElement = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]), false, false, array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID"));
		//$arElement = $resElement->Fetch();
		while ($tovar = $resElement->GetNextElement())
		{
			$arElement = $tovar->GetFields(); 
		}
		$APPLICATION->AddHeadString('<link href="https://'.SITE_SERVER_NAME.$arElement['DETAIL_PAGE_URL'].'" rel="canonical" />',true);	
	}
}
$MShopSectionID = $section["ID"];

global $TEMPLATE_OPTIONS;
?>
<div class="catalog_detail">
    <?
        $tmp="main";
        /*global $USER;
        if($USER->IsAdmin()) {
                $tmp="main2";
                 } */
    ?>
	<?$ElementID = $APPLICATION->IncludeComponent(
		"bitrix:catalog.element",
		$tmp,
		Array(
			"TYPE_SKU" => $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"],
			"SKU_DETAIL_ID" => (isset($_GET[$arParams["SKU_DETAIL_ID"]]) && strlen($arParams["SKU_DETAIL_ID"]) && $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"] == "TYPE_1" ? $_GET[$arParams["SKU_DETAIL_ID"]] : "" ),
			"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
			"IBLOCK_REVIEWS_TYPE" => $arParams["IBLOCK_REVIEWS_TYPE"],
			"IBLOCK_REVIEWS_ID" => $arParams["IBLOCK_REVIEWS_ID"],
			"SEF_MODE_BRAND_SECTIONS" => $arParams["SEF_MODE_BRAND_SECTIONS"],
			"SEF_MODE_BRAND_ELEMENT" => $arParams["SEF_MODE_BRAND_ELEMENT"],
			"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
			"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
			"SET_LAST_MODIFIED" => "Y",
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"MESSAGE_404" => $arParams["MESSAGE_404"],
			"SHOW_404" => $arParams["SHOW_404"],
			"FILE_404" => $arParams["FILE_404"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
			"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
			"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
			"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
			"USE_ALSO_BUY" => $arParams["USE_ALSO_BUY"],
			'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
			'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"SKU_DISPLAY_LOCATION" => $arParams["SKU_DISPLAY_LOCATION"],
			"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
			"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
			"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
			"USE_STORE" => $arParams["USE_STORE"],
			"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
			"USE_STORE_SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
			"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
			"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
			"STORE_PATH" => $arParams["STORE_PATH"],
			"MAIN_TITLE" => $arParams["MAIN_TITLE"],
			"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"IBLOCK_STOCK_ID" => $arParams["IBLOCK_STOCK_ID"],
			"SEF_MODE_STOCK_SECTIONS" => $arParams["SEF_MODE_STOCK_SECTIONS"],
			"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
			"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"USE_ELEMENT_COUNTER" => $arParams["USE_ELEMENT_COUNTER"],
			"USE_RATING" => $arParams["USE_RATING"],
			"USE_REVIEW" => $arParams["USE_REVIEW"],
			"FORUM_ID" => $arParams["FORUM_ID"],
			"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
			"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
			"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
			"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			"SHOW_BRAND_PICTURE" => $arParams["SHOW_BRAND_PICTURE"],
			"PROPERTIES_DISPLAY_LOCATION" => $arParams["PROPERTIES_DISPLAY_LOCATION"],
			"PROPERTIES_DISPLAY_TYPE" => $arParams["PROPERTIES_DISPLAY_TYPE"],
			"SHOW_ADDITIONAL_TAB" => $arParams["SHOW_ADDITIONAL_TAB"],
			"SHOW_ASK_BLOCK" => $arParams["SHOW_ASK_BLOCK"],
			"ASK_FORM_ID" => $arParams["ASK_FORM_ID"],
			"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
			"SHOW_HINTS" => $arParams["SHOW_HINTS"],
			"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
			"SHOW_KIT_PARTS" => $arParams["SHOW_KIT_PARTS"],
			"SHOW_KIT_PARTS_PRICES" => $arParams["SHOW_KIT_PARTS_PRICES"],
			"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
			"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
			'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
			'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
			"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
			"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
			"USER_FIELDS" => $arParams['USER_FIELDS'],
			"FIELDS" => $arParams['FIELDS'],
			"STORES" => $arParams['STORES'],
			"BIG_DATA_RCM_TYPE" => $arParams['BIG_DATA_RCM_TYPE'],
			"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		),
		$component
	);?>
</div>
<div class="clearfix"></div>
<?
$arAllValues=$arSimilar=$arAccessories=array();
/*similar goods*/
$rsSimilar = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "EXPANDABLES"));
while ($obSimilar = $rsSimilar->Fetch()){
	if($obSimilar['VALUE'])
		$arExpValues[] = $obSimilar['VALUE'];
}
if($arExpValues){
	$arAllValues["EXPANDABLES"]=$arExpValues;
}
/*accessories goods*/
$rsAccessories = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $ElementID, "sort", "asc", array("CODE" => "ASSOCIATED"));
while ($obAccessories = $rsAccessories->Fetch()){
	if($obAccessories['VALUE'])
		$arAccessories[] = $obAccessories['VALUE'];
}
if($arAccessories){
	$arAllValues["ASSOCIATED"]=$arAccessories;
}
?>

<?if($arAccessories || $arExpValues || (ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N'))){?>
	<?$class_block="s_".randString();
	$arTab=array();
	if($arExpValues){
		$arTab["EXPANDABLES"]=GetMessage("EXPANDABLES_TITLE");
	}
	if($arAccessories){
		$arTab["ASSOCIATED"]=GetMessage("ASSOCIATED_TITLE");
	}
	/* Start Big Data */
	if(ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')){
		$arTab["RECOMENDATION"]=GetMessage("RECOMENDATION_TITLE");
	}?>
	<div class="specials_tabs_section1 specials_slider_wrapp1 specials tab_slider_wrapp <?=$class_block;?>">
		<div class="top_blocks">
			<ul class="tabs">
				<?$i=1;
				foreach($arTab as $code=>$title):?>
					<li data-code="<?=$code?>" <?=($i==1 ? "class='cur'" : "")?>><span><?=$title;?></span></li>
					<?$i++;?>
				<?endforeach;?>
				<li class="stretch"></li>
			</ul>
			<ul class="slider_navigation top">
				<?$i=1;
				foreach($arTab as $code=>$title):?>
					<li class="tabs_slider_navigation <?=$code?>_nav <?=($i==1 ? "cur" : "")?>" data-code="<?=$code?>"></li>
					<?$i++;?>
				<?endforeach;?>
			</ul>
		</div>
		<ul class="tabs_content">
			<?foreach($arTab as $code=>$title){?>
				<li class="tab <?=$code?>_wrapp" data-code="<?=$code?>">
					<?if($code=="RECOMENDATION"){?>
						<?
						$GLOBALS["CATALOG_CURRENT_ELEMENT_ID"] = $ElementID;
						if(Loader::includeModule("catalog")){
							$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
						}
						$ElementOfferIblockID = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
						?>
<!--<div class="detail-bigdata" data-detailurl="<?//=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"];?>" data-secid="<?//=$arResult["VARIABLES"]["SECTION_ID"];?>" data-seccode="<?//=$arResult["VARIABLES"]["SECTION_CODE"];?>" data-secelemid="<?//=$arResult["VARIABLES"]["SECTION_ID"];?>" data-secelemcode="<?//=$arResult["VARIABLES"]["SECTION_CODE"];?>" data-id="<?//=$ElementID;?>" data-elementofferiblockid="<?//=$ElementOfferIblockID;?>">
</div>-->
							<?$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", "main", array(
							"LINE_ELEMENT_COUNT" => 5,
							"TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
							"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
							"BASKET_URL" => $arParams["BASKET_URL"],
							"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action")."_cbdp",
							"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
							"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
							"ADD_PROPERTIES_TO_BASKET" => "N",
							"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
							"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
							"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
							"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
							"PRICE_CODE" => $arParams["PRICE_CODE"],
							"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
							"PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
							"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
							"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
							"SHOW_NAME" => "Y",
							"SHOW_IMAGE" => "Y",
							"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
							"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
							"MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
							"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
							"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
							"PAGE_ELEMENT_COUNT" => 10,
							"SHOW_FROM_SECTION" => "N",
							"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"DEPTH" => "2",
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
							"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
							"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => $arParams['ADD_PICT_PROP'],
							"LABEL_PROP_".$arParams["IBLOCK_ID"] => "-",
							"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
							"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
							"CURRENCY_ID" => $arParams["CURRENCY_ID"],
							"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
							"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
							"SECTION_ELEMENT_ID" => $arResult["VARIABLES"]["SECTION_ID"],
							"SECTION_ELEMENT_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
							"ID" => $ElementID,
							"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
							"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => $arParams["PRODUCT_PROPERTIES"],
							"RCM_TYPE" => (isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : ''),
							"OFFER_TREE_PROPS_".$ElementOfferIblockID => $arParams["OFFER_TREE_PROPS"],
							"ADDITIONAL_PICT_PROP_".$ElementOfferIblockID => $arParams['OFFER_ADD_PICT_PROP'],
							"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
							"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
							),
							false,
							array("HIDE_ICONS" => "Y")
						);
						?>
					<?}else{?>
						<ul class="tabs_slider <?=$code?>_slides wr">
							<?$GLOBALS['arrFilter'.$code] = array( "ID" => $arAllValues[$code] );?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:catalog.top",
								"main",
								array(
									"TITLE_BLOCK" => $arParams["SECTION_TOP_BLOCK_TITLE"],
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"FILTER_NAME" => 'arrFilter'.$code,
									"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
									"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
									"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
									"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
									"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
									"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
									"BASKET_URL" => $arParams["BASKET_URL"],
									"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
									"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
									"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
									"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
									"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
									"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
									"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
									"ELEMENT_COUNT" => 10,
									"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
									"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
									"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
									"PRICE_CODE" => $arParams["PRICE_CODE"],
									"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
									"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
									"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
									"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
									"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
									"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
									"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
									"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
									"CACHE_FILTER" => $arParams["CACHE_FILTER"],
									"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
									"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
									"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
									"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
									"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
									"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
									"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
									"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID' => $arParams['CURRENCY_ID'],
									'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
									'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
									'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
									'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
									'LABEL_PROP' => $arParams['LABEL_PROP'],
									'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
									'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

									'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
									'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
									'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
									'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
									'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
									'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
									'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
									'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
									'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
									'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
									'ADD_TO_BASKET_ACTION' => $basketAction,
									'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
									'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
								),
								false, array("HIDE_ICONS"=>"Y")
							);?>
						</ul>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			
			$('.tab_slider_wrapp.<?=$class_block;?> .tabs > li').first().addClass('cur');
			$('.tab_slider_wrapp.<?=$class_block;?> .slider_navigation > li').first().addClass('cur');
			$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li').first().addClass('cur');
			
			var flexsliderItemWidth = 210;
			var flexsliderItemMargin = 20;
			
			var sliderWidth = $('.tab_slider_wrapp.<?=$class_block;?>').outerWidth();
			var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
			$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').flexslider({
				animation: 'slide',
				selector: '.tabs_slider .catalog_item',
				slideshow: false,
				animationSpeed: 600,
				directionNav: true,
				controlNav: false,
				pauseOnHover: true,
				animationLoop: true, 
				itemWidth: flexsliderItemWidth,
				itemMargin: flexsliderItemMargin, 
				minItems: flexsliderMinItems,
				controlsContainer: '.tabs_slider_navigation.cur',
				start: function(slider){
					slider.find('li').css('opacity', 1);
				}
			});
			
			$('.tab_slider_wrapp.<?=$class_block;?> .tabs > li').on('click', function(){
				if(!$(this).hasClass('active')){
					var sliderIndex = $(this).index();
					$(this).addClass('active').addClass('cur').siblings().removeClass('active').removeClass('cur');
					$('.tab_slider_wrapp.<?=$class_block;?> .slider_navigation > li:eq(' + sliderIndex + ')').addClass('cur').show().siblings().removeClass('cur');
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li:eq(' + sliderIndex + ')').addClass('cur').siblings().removeClass('cur');
					if(!$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur .flex-viewport').length){
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').flexslider({
							animation: 'slide',
							selector: '.tabs_slider .catalog_item',
							slideshow: false,
							animationSpeed: 600,
							directionNav: true,
							controlNav: false,
							pauseOnHover: true,
							animationLoop: true, 
							itemWidth: flexsliderItemWidth,
							itemMargin: flexsliderItemMargin, 
							minItems: flexsliderMinItems,
							controlsContainer: '.tabs_slider_navigation.cur',
						});
					}
					$(window).resize();
				}
			});
			
			$(window).resize(function(){
				var sliderWidth = $('.tab_slider_wrapp.<?=$class_block;?>').outerWidth();
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').css('height', '');
				// $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider .buttons_block').hide();
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item-title'}); 
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item_info'}); 
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.catalog_item'});
				var itemsButtonsHeight = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider li .buttons_block').height();
				var tabsContentUnhover = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').height() * 1;
				var tabsContentHover = tabsContentUnhover + itemsButtonsHeight+50;
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-unhover', tabsContentUnhover);
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-hover', tabsContentHover);
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content').height(tabsContentUnhover);
			});
			
			$(window).resize();
			$(document).on({
				mouseover: function(e){
					var tabsContentHover = $(this).closest('.tab').attr('data-hover') * 1;
					$(this).closest('.tab').fadeTo(100, 1);
					$(this).closest('.tab').stop().css({'height': tabsContentHover});
					// $(this).find('.buttons_block').fadeIn(450, 'easeOutCirc');
				},
				mouseleave: function(e){
					var tabsContentUnhoverHover = $(this).closest('.tab').attr('data-unhover') * 1;
					$(this).closest('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
					// $(this).find('.buttons_block').stop().fadeOut(233);
				}
			}, '.<?=$class_block;?> .tabs_slider li');
		})
	</script>
<?}?>


<?/*if(ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')):?>
	<?if($ElementID > 0):?>
		<?
		$GLOBALS["CATALOG_CURRENT_ELEMENT_ID"] = $ElementID;
		if(Loader::includeModule("catalog")){
			$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
		}
		$ElementOfferIblockID = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
		?>
		<?$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", "", array(
			"LINE_ELEMENT_COUNT" => 5,
			"TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action")."_cbdp",
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"ADD_PROPERTIES_TO_BASKET" => "N",
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
			"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
			"SHOW_DISCOUNT_PERCENT" => "N",
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
			"SHOW_NAME" => "Y",
			"SHOW_IMAGE" => "Y",
			"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
			"MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
			"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
			"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
			"PAGE_ELEMENT_COUNT" => 5,
			"SHOW_FROM_SECTION" => "N",
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"DEPTH" => "2",
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
			"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => $arParams['ADD_PICT_PROP'],
			"LABEL_PROP_".$arParams["IBLOCK_ID"] => "-",
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"SECTION_ELEMENT_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_ELEMENT_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"ID" => $ElementID,
			"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
			"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => $arParams["PRODUCT_PROPERTIES"],
			"RCM_TYPE" => (isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : ''),
			"OFFER_TREE_PROPS_".$ElementOfferIblockID => $arParams["OFFER_TREE_PROPS"],
			"ADDITIONAL_PICT_PROP_".$ElementOfferIblockID => $arParams['OFFER_ADD_PICT_PROP']
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);
		?>
	<?endif;?>
<?endif;*/?>


<?
/*get SKU iblock*/
//$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
?>
<div class="detail_footer">
<?/*$APPLICATION->IncludeComponent(
	"bitrix:catalog.viewed.products", 
	"main", 
	array(
		"COMPONENT_TEMPLATE" => "main",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "",
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		"PAGE_ELEMENT_COUNT" => $arParams["VIEWED_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => "3",
		"TEMPLATE_THEME" => "blue",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
		"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => array(
			0 => "HIT",
			1 => "",
		),
		"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => "MORE_PHOTO",
		"LABEL_PROP_".$arParams["IBLOCK_ID"] => "-",
		"TITLE_BLOCK" => GetMessage('VIEWED_BEFORE'),
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
		/*"PROPERTY_CODE_26" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_26" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_26" => "MORE_PHOTO",
		"OFFER_TREE_PROPS_26" => array(
			0 => "-",
		),
		"SHOW_MEASURE" => $arParams['SHOW_MEASURE']
	),
	false, array("HIDE_ICONS"=>"Y")
);*/?>
</div>
<?
$page_seo_params["title"] = $APPLICATION->GetTitle();
$this->SetViewTarget('h1');echo $page_seo_params["title"];$this->EndViewTarget();?>