<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if($_POST["CLEAR_ALL"]=="Y"){
	Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("basket-allitems-block");
	\Bitrix\Main\Loader::includeModule('sale');
	$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY"));
	$basket_items=$delay_items=$arDelItems=array();
	while($arBasketItems = $dbBasketItems->GetNext()){			
		if($arBasketItems["DELAY"]=="N" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
			$basket_items[] = $arBasketItems["ID"];
		} 
		elseif($arBasketItems["DELAY"]=="Y" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
			$delay_items[] = $arBasketItems["ID"];
		}
	}
	if($_POST["TYPE"]=="delay"){
		$arDelItems=$delay_items;
	}else{
		$arDelItems=$basket_items;
	}
	if($arDelItems){
		foreach($arDelItems as $id){
			CSaleBasket::Delete($id);
		}
	}
	Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("basket-allitems-block", "");
}elseif($_POST["delete_top_item"]=="Y"){
	\Bitrix\Main\Loader::includeModule('sale');
	CSaleBasket::Delete($_POST["delete_top_item_id"]);
}?>