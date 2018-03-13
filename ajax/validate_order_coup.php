<?
require("../bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$cup = CCatalogDiscountCoupon::IsExistCoupon($_REQUEST['coup']);

if($cup){
echo json_encode(array(
        'result' => 'yes',
        'Coup' => $_REQUEST['coup']
			));
			
}else{
echo json_encode(array(
        'result' => 'Не верный купон'));
}

?>