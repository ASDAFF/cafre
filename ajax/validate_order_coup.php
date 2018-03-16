<?
require("../bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;


CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$cup = CCatalogDiscountCoupon::IsExistCoupon($_REQUEST['coup']);

if($cup){
$cup_act = \Bitrix\Sale\DiscountCouponsManager::getData(
$_REQUEST['coup'],
TRUE
);
if($cup_act["ACTIVE"] == "Y"){	
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