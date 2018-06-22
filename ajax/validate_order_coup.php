<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

global $APPLICATION;
$cup = CCatalogDiscountCoupon::IsExistCoupon($_REQUEST['coup']);

if($cup){
$cup_act = \Bitrix\Sale\DiscountCouponsManager::getData(
$_REQUEST['coup'],
TRUE
);
if($cup_act["ACTIVE"] == "Y"){
/*$prc_s = \Bitrix\Sale\Discount::getApplyResult(
 true
);
$sum = 0;
foreach($prc_s["RESULT"]["BASKET"] as $v1){
	foreach($v1 as $v2){
		if($prc_s["COUPON_LIST"][$cup_act["COUPON"]]["COUPON"] == $v2["COUPON_ID"]){
			$exp_prc = explode('(', $v2["DESCR"][0]);
			$sum += (int)$exp_prc[1];	
}
	}
}*/	
echo json_encode(array(
        'result' => 'yes',
        'CoupName' => iconv('WINDOWS-1251', 'UTF-8', $cup_act['DISCOUNT_NAME'])
			));
CCatalogDiscountCoupon::SetCoupon($_REQUEST['coup']);
			}else{
				echo json_encode(array(
        'result' => 'no'));
			}
}else{
echo json_encode(array(
        'result' => 'no'));
}

?>