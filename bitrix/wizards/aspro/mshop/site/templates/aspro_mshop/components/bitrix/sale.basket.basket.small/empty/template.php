<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog")) break;
$normalCount = $summ = $delayCount = $subscribeCount = $naCount = 0;
$arItems=$arDelayItems=$arSubscribeItems=array();
usort($arResult["ITEMS"], 'CMShop::cmpByID');
foreach($arResult["ITEMS"] as $arItem){
	if($arItem["DELAY"] == "Y"){
		++$delayCount;
		$arDelayItems[]=array(
			"PRODUCT_ID" => $arItem["PRODUCT_ID"],
		);
	}
	elseif($arItem["SUBSCRIBE"] == "Y"){
		++$subscribeCount;
		$arSubscribeItems[]=array(
			"PRODUCT_ID" => $arItem["PRODUCT_ID"],
		);
	}
	elseif($arItem["CAN_BUY"] == "Y"){
		++$normalCount;
		$summ += $arItem["PRICE"] * $arItem["QUANTITY"];
		$arItems[]=array(
			"PRICE" => $arItem["PRICE"]*$arItem["QUANTITY"],
			"QUANTITY" => $arItem["QUANTITY"],
			"PRODUCT_ID" => $arItem["PRODUCT_ID"],
		);
	}
	else{
		++$naCount;
	}
}
$cur = CCurrencyLang::GetCurrencyFormat(CCurrency::GetBaseCurrency());
echo json_encode(array("TOTAL_COUNT" => $normalCount, "TOTAL_SUMM" => $summ, "WISH_COUNT" => $delayCount, "SUBSCRIBE_COUNT" => $subscribeCount, "NOT_AVAILABLE_COUNT" => $naCount, "ITEMS" => $arItems, "DELAY_ITEMS" => $arDelayItems, "SUBSCRIBE_ITEMS" => $arSubscribeItems, "CURRENCY" => $cur["CURRENCY"]));
?>