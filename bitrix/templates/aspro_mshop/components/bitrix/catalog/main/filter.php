<?if('Y' == $arParams['USE_FILTER']):?>
	<?
	if(CModule::IncludeModule("iblock")){
		$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y");
		if(0 < intval($arResult["VARIABLES"]["SECTION_ID"])){
			$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
		}
		elseif('' != $arResult["VARIABLES"]["SECTION_CODE"]){
			$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
		}
		$obCache = new CPHPCache();
		if($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")){
			$arCurSection = $obCache->GetVars();
		}
		else{
			$arCurSection = array();
			$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));
			if(defined("BX_COMP_MANAGED_CACHE")){
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache("/iblock/catalog");
				if($arCurSection = $dbRes->GetNext()){
					$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
				}
				$CACHE_MANAGER->EndTagCache();
			}
			else{
				if(!$arCurSection = $dbRes->GetNext()){
					$arCurSection = array();
				}
			}
			$obCache->EndDataCache($arCurSection);
		}
	}
	?>
	<?
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		($arParams["AJAX_FILTER_CATALOG"]=="Y" ? "main_ajax" : "main"),
		Array(
			"HIDE_NOT_AVAILABLE"=> $arParams["HIDE_NOT_AVAILABLE"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"SECTION_ID" => $arCurSection['ID'],
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_NOTES" => "",
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SAVE_IN_SESSION" => "N",
			"XML_EXPORT" => "Y",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			"SHOW_HINTS" => $arParams["SHOW_HINTS"],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			"INSTANT_RELOAD" => "Y",
			"VIEW_MODE" => strtolower($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]),
			"SEF_MODE" => $arParams["SEF_MODE"],
			"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
			"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
		),
		$component);
	?>
<?endif;?>