<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("test");
?>
<input type="text" name="coupon" width="300" height="50"/>
<p class="err_cup"></p>
<script>
$('[name=coupon]').on('keyup', function(e) {
	var coup = e.target.value;
	
	$.ajax({
				url: "/ajax/validate_order_coup.php", 
					type: "post",
					dataType: "json",
					data: { 
						"coup": coup
					},
				success: function(d) {			
					if(d.result=='yes') { 
						$('[name=coupon]').css("color","green");
					}else{
						$(".err_cup").text(d.result);
					}
					console.log(d);
				}
				
	});	
	
	
});
</script>
<?

//$cup = CCatalogDiscountCoupon::IsExistCoupon("SL-LOG7R-8Q7L4NL");
//$cup = CCatalogDiscountCoupon::SetCoupon("SL-LOG7R-8Q7L4NL"); 
//print_r($cup);
/*$cup = \Bitrix\Sale\DiscountCouponsManager::getData(
"SL-LOG7R-8Q7L4NL",
TRUE
);*/
//print_r($cup);

global $APPLICATION;
// устновим cookie на 2 года, действительного только для каталога /ru/
$arr.= '111,';
$arr.= '211,';
$APPLICATION->set_cookie("BASss", $arr);

// Печатаем массив, содержащий актуальную на текущий момент корзину
$item_bask = explode(",", $_COOKIE["addBasId"]);
echo "<pre>";
print_r($item_bask);
echo "</pre>";

$APPLICATION->IncludeComponent(
	"lets:catalog.viewed.products", 
	"main_index", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADDITIONAL_PICT_PROP_26" => "ON_PHOT",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"BASKET_URL" => "/basket/",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CART_PROPERTIES_26" => array(
			0 => "",
			1 => "",
		),
		"COMPONENT_TEMPLATE" => "main_index",
		"CONVERT_CURRENCY" => "N",
		"DEPTH" => "",
		"DETAIL_URL" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_ID" => "26",
		"IBLOCK_TYPE" => "new_cat",
		"LABEL_PROP_26" => "-",
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"PAGE_ELEMENT_COUNT" => "8",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PROPERTY_CODE_26" => array(
			0 => "",
			1 => "",
		),
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ID" => "",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_IMAGE" => "Y",
		"SHOW_NAME" => "Y",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_PRODUCTS_26" => "Y",
		"TEMPLATE_THEME" => "blue",
		"USE_PRODUCT_QUANTITY" => "N",
		"PROPERTY_CODE_27" => array(
			0 => "CML2_LINK",
			1 => "",
		),
		"CART_PROPERTIES_27" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_27" => "",
		"OFFER_TREE_PROPS_27" => array(
		),
		"TITLE_BLOCK" => "Ранее вы смотрели",
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DISPLAY_COMPARE" => "Y",
		"SHOW_MEASURE" => "N"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>