<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if($_REQUEST['prodid']){
	\Bitrix\Main\Loader::includeModule('sale');
	if(CSaleBasket::Delete($_REQUEST['prodid'])){
		echo json_encode(array(
        'result' => 'Y'));
	}else{
		echo json_encode(array(
        'result' => 'N'));
	}
}
?>