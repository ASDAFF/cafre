<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;
if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateData['TEMPLATE_THEME']);
}
CJSCore::Init(array("popup"));
?>
<?global $compare_items;
$compare_items=array_keys($_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"]);
if(CModule::IncludeModule("sale")){
	$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY"));
	$basket_items = array();
	$delay_items = array();
	$subscribe_items = array();
	//$compare_items = array();
	while($arBasketItems = $dbBasketItems->GetNext()){			
		if($arBasketItems["DELAY"]=="N" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
			$basket_items[] = $arBasketItems["PRODUCT_ID"];
		} 
		elseif($arBasketItems["DELAY"]=="Y" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
			$delay_items[] = $arBasketItems["PRODUCT_ID"];
		}
		elseif($arBasketItems["SUBSCRIBE"]=="Y"){
			$subscribe_items[] = $arBasketItems["PRODUCT_ID"];
		}
	}	
}
?>
<script>
	touchItemBlock('.catalog_item a');
	<?if(is_array($compare_items) && !empty($compare_items)):?>
		<?foreach( $compare_items as $item ){?>
			$('.compare_item.to[data-item=<?=$item?>]').hide();
			$('.compare_item.in[data-item=<?=$item?>]').show();
			if ($('.compare_item[data-item=<?=$item?>]').find(".value.added").length){ 
				$('.compare_item[data-item=<?=$item?>]').addClass("added"); 
				$('.compare_item[data-item=<?=$item?>]').find(".value").hide(); 
				$('.compare_item[data-item=<?=$item?>]').find(".value.added").css('display','inline-block'); 
			}
		<?}?>
	<?endif;?>
	<?if(is_array($delay_items) && !empty($delay_items)):?>
		<?foreach( $delay_items as $item ){?>
			$('.wish_item.to[data-item=<?=$item?>]').hide();
			$('.wish_item.in[data-item=<?=$item?>]').show(); 
			if ($('.wish_item[data-item=<?=$item?>]').find(".value.added").length) {
				$('.wish_item[data-item=<?=$item?>]').addClass("added");
				$('.wish_item[data-item=<?=$item?>]').find(".value").hide(); 
				$('.wish_item[data-item=<?=$item?>]').find(".value.added").css('display','inline-block'); 
			}
		<?}?>
	<?endif;?>
</script>
