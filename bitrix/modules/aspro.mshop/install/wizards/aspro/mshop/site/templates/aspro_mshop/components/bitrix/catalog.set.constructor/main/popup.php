<?define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->ShowAjaxHead();
$APPLICATION->AddHeadScript("/bitrix/js/main/dd.js");
if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog")) return;
if (SITE_CHARSET != "utf-8") $_REQUEST["arParams"] = $APPLICATION->ConvertCharsetArray($_REQUEST["arParams"], "utf-8", SITE_CHARSET);
if (!is_array($_REQUEST["arParams"]["ELEMENT"])) return;

CModule::IncludeModule("aspro.mshop");
\Bitrix\Main\Loader::includeModule('sale');

$curElementId = intval(CMShop::inputClean($_REQUEST["arParams"]["ELEMENT"]["ID"]));
$arCurElementInfo = CMShop::inputClean($_REQUEST["arParams"]["ELEMENT"]);
$arSetItemsInfo = CMShop::inputClean($_REQUEST["arParams"]["SET_ITEMS"]);
$arMessage = CMShop::inputClean($_REQUEST["arParams"]["MESS"]);
$curTemplatePath = CMShop::inputClean($_REQUEST["arParams"]["CURRENT_TEMPLATE_PATH"]);

$arSetElementsDefault = CMShop::inputClean($_REQUEST["arParams"]["SET_ITEMS"]["DEFAULT"]);
$arSetElementsOther = CMShop::inputClean($_REQUEST["arParams"]["SET_ITEMS"]["OTHER"]);

$setPrice = CMShop::inputClean($_REQUEST["arParams"]["SET_ITEMS"]["PRICE"]);
$setOldPrice = CMShop::inputClean($_REQUEST["arParams"]["SET_ITEMS"]["OLD_PRICE"]);
$setPriceDiscountDifference = CMShop::inputClean($_REQUEST["arParams"]["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]);

?>

<div class="bx_modal_container bx_kit set_block">
	<div class="bx_modal_body" id="bx_catalog_set_construct_popup_<?=$curElementId?>">
		<ul class="bx_kit_one_section">
			<li class="item_wrapp main_item">
				<div class="item_block_title"><?=$arMessage["CATALOG_SET_MAIN_PRODUCT_BLOCK_TITLE"]?></div>
				<div class="bx_kit_item item">
					<div class="bx_kit_item_children item_inner">
						<div class="bx_kit_img_container">
								<?if ($arCurElementInfo["PREVIEW_PICTURE"]):?>
									<?$img = CFile::ResizeImageGet($arCurElementInfo["PREVIEW_PICTURE"], array( "width" => 140, "height" => 140 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
									<img border="0" src="<?=$img["src"]?>" alt="<?=$arCurElementInfo["NAME"];?>" title="<?=$arCurElementInfo["NAME"];?>" />	
								<?elseif($arCurElementInfo["DETAIL_PICTURE"]["src"]):?>
									<img border="0" src="'<?=$arCurElementInfo["DETAIL_PICTURE"]["src"]?>" alt="<?=$arCurElementInfo["NAME"];?>" title="<?=$arCurElementInfo["NAME"];?>" />
								<?else:?>
									<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arCurElementInfo["NAME"];?>" title="<?=$arCurElementInfo["NAME"];?>" />
								<?endif;?>
									
						</div>
						<div class="item_info">
							<div class="item-title bx_kit_item_title">
								<a class="bx_item_set_linkitem" href="<?=$arCurElementInfo["DETAIL_PAGE_URL"]?>"><span><?=$arCurElementInfo["NAME"]?></span></a>
							</div>
							<div class="cost prices clearfix">	
								<div class="price bx_kit_item_price bx_price"><?=$arCurElementInfo["PRICE_PRINT_DISCOUNT_VALUE"]?></div>
								<?if ($arParams["SHOW_OLD_PRICE"]=="Y" && $arCurElementInfo["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?>
									<div class="price discount bx_kit_item_discount"><strike><?=$arCurElementInfo["PRICE_PRINT_VALUE"]?></strike></div>
								<?endif?>
							</div>
						</div>
					</div>
				</div>
				<div class="item_plus separator"></div>
			</li>
			
			<? $curCountDefaultSetItems = 0; ?>
			<?foreach($arSetElementsDefault as $key => $arItem):
				$strMeasure='';
				if($arItem["OFFERS"]){
					$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
				}else{
					if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
						$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
						$strMeasure=$arMeasure["SYMBOL_RUS"];
					}
				}?>
				<li class="item_wrapp ">
					<?if($key==0):?><div class="item_block_title"><?=$arMessage["CATALOG_SET_PRODUCTS_BLOCK_TITLE"]?></div><?endif;?>
					<div class="bx_kit_item item bx_drag_dest<?if ($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?> discount<?endif?>">
						<div class="bx_kit_item_children bx_kit_item_border item_inner">
							<div class="bx_kit_img_container">
									<?if ($arItem["PREVIEW_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array( "width" => 140, "height" => 140 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
										<img border="0" src="<?=$img["src"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />	
									<?elseif($arItem["DETAIL_PICTURE"]["src"]):?>
										<img border="0" src="'<?=$arItem["DETAIL_PICTURE"]["src"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
									<?else:?>
										<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
									<?endif;?>
											
							</div>
							<div class="item_info">
								<div class="item-title bx_kit_item_title" data-item-id="<?=$arItem["ID"]?>">
									<a class="bx_item_set_linkitem" href="<?=$arItem["DETAIL_PAGE_URL"]?>" target="_blank"><span><?=$arItem["NAME"]?></span></a>
								</div>
								<div class="cost prices clearfix">	
									<div class="price bx_kit_item_price bx_price"
										data-discount-price="<?=($arItem["PRICE_CONVERT_DISCOUNT_VALUE"]) ? $arItem["PRICE_CONVERT_DISCOUNT_VALUE"] : $arItem["PRICE_DISCOUNT_VALUE"]?>"
										data-price="<?=($arItem["PRICE_CONVERT_VALUE"]) ? $arItem["PRICE_CONVERT_VALUE"] : $arItem["PRICE_VALUE"]?>"
										data-discount-diff-price="<?=($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"]) ? $arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"] : $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]?>">
											<?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>
											<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
												/<?=$strMeasure?>
											<?}?>
									</div>
									<?if ($arParams["SHOW_OLD_PRICE"]=="Y" && $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?>
										<?$discount_price=($arItem["PRICE_CONVERT_VALUE"] ? $arItem["PRICE_CONVERT_VALUE"] : $arItem["PRICE_VALUE"]);?>
										<div class="price discount bx_kit_item_discount"><strike><?=SaleFormatCurrency($discount_price, $arItem["PRICE_CURRENCY"])?></strike></div>
									<?endif?>	
								</div>
							</div>	
							<div class="bx_kit_item_del" title="<?=$arMessage["CATALOG_SET_DELETE"]?>" onclick="catalogSetPopupObj.catalogSetDelete(this.parentNode);"></div>
						</div>
					</div>
					<?
						$curCountDefaultSetItems++;
						if ($curCountDefaultSetItems<3):?>
						<div class="item_plus separator"></div>
					<?else:?>
						<div class="item_equally separator"></div>
					<?endif?>
				<?endforeach?>
				</li>
				

			<?if ($curCountDefaultSetItems<3):
				for($j=1; $j<=(3-$curCountDefaultSetItems); $j++)
				{
			?>
				<li class="item_wrapp">
					<div class="bx_kit_item r bx_kit_item_border bx_kit_item_empty bx_drag_dest"></div>
					<?if ($j<3-$curCountDefaultSetItems):?><div class="item_plus"></div><?else:?><div class="item_equally"></div><?endif?>
				</li>
			<?
				}
			?>
			<?endif?>

			<li class="item_wrapp result_block">
				<div class="item r">
					<div class="item_inner total_wrapp">
						<div class="bx_kit_result cost prices clearfix <?if (!$setOldPrice && !$setPriceDiscountDifference):?>not_sale<?endif?>" id="bx_catalog_set_construct_price_block_<?=$curElementId?>">
							<div class="price bx_item_set_current_price" id="bx_catalog_set_construct_sum_price_<?=$curElementId?>"> <?=$setPrice;?></div>
							<?if($setOldPrice && $arParams["SHOW_OLD_PRICE"]=="Y"):?>
								<div class="price discount">
									<strike class="bx_item_set_old_price" id="bx_catalog_set_construct_sum_old_price_<?=$curElementId?>"><?=$setOldPrice?></strike>
								</div>
								<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y" && $setPriceDiscountDifference){?>
									<div class="sale_block" id="bx_catalog_set_construct_sum_diff_price_<?=$curElementId?>">
										<?
										$tmp_price=str_replace(" ", "", $setPrice);
										$tmp_price_diff=str_replace(" ", "", $setPriceDiscountDifference);
										$percent=round(((int)$tmp_price_diff/(int)$tmp_price)*100, 2);?>
										<?if($percent && $percent<100){?>
											<div class="value">-<?=$percent;?>%</div>
										<?}?>
										<div class="text"><?=$arMessage["CATALOG_ECONOMY"];?> <span><?=$setPriceDiscountDifference;?></span></div>
										<div class="clearfix"></div>
									</div>
								<?}?>
							<?endif?>
							<div class="buttons_wrapp">
								<button class="button big_btn" onclick="catalogSetPopupObj.Add2Basket();"><span><?=$arMessage["CATALOG_SET_BUY"]?></span></button>
							</div>
						</div>
					</div>
				</div>
			</li>
		</ul>

		<div class="bx_kit_two_section">
			<div class="title item_block_title"><?=$arMessage["CATALOG_SET_POPUP_TITLE"]?></div>
			<span class="bx_modal_description"><?=$arMessage["CATALOG_SET_POPUP_DESC"]?></span>
			<div class="slider_wrapp">
				<div class="bx_kit_two_section_ova clearfix">
					<div class="bx_kit_two_item_slider" id="bx_catalog_set_construct_slider_<?=$curElementId?>" data-style-left="0" style="left:0%;width:<?=(count($arSetElementsOther) <=5) ? 100 : 100 + 20*(count($arSetElementsOther)-5)?>%">
					<?if (is_array($arSetElementsOther)):?>
						<?foreach($arSetElementsOther as $arItem):?>
						<div class="bx_kit_item_slider bx_drag_obj" style="width:<?=(count($arSetElementsOther) <=5) ? "20" : (100/count($arSetElementsOther))?>%" data-main-element-id="<?=$curElementId?>">
							<div class="bx_kit_item item bx_kit_item_border<?if ($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?> discount<?endif?>">
								<div class="item_inner">
									<div class="bx_kit_img_container">
										<?if ($arItem["PREVIEW_PICTURE"]):?>
											<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array( "width" => 140, "height" => 140 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
											<img border="0" src="<?=$img["src"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />	
										<?elseif($arItem["DETAIL_PICTURE"]["src"]):?>
											<img border="0" src="'<?=$arItem["DETAIL_PICTURE"]["src"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
										<?else:?>
											<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
										<?endif;?>
									</div>
									<div class="item_info">
										<div class="item-title bx_kit_item_title" data-item-id="<?=$arItem["ID"]?>">
											<a class="bx_item_set_linkitem" href="<?=$arItem["DETAIL_PAGE_URL"]?>" target="_blank"><span><?=$arItem["NAME"]?></span></a>
										</div>
										<div class="cost prices clearfix">	
											<div class="price bx_kit_item_price bx_price"
												data-discount-price="<?=($arItem["PRICE_CONVERT_DISCOUNT_VALUE"]) ? $arItem["PRICE_CONVERT_DISCOUNT_VALUE"] : $arItem["PRICE_DISCOUNT_VALUE"]?>"
												data-price="<?=($arItem["PRICE_CONVERT_VALUE"]) ? $arItem["PRICE_CONVERT_VALUE"] : $arItem["PRICE_VALUE"]?>"
												data-discount-diff-price="<?=($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"]) ? $arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"] : $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]?>">
													<?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>
											</div>
											<?if ($arParams["SHOW_OLD_PRICE"]=="Y" && $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?>
												<?$discount_price=($arItem["PRICE_CONVERT_VALUE"] ? $arItem["PRICE_CONVERT_VALUE"] : $arItem["PRICE_VALUE"]);?>
												<div class="price discount bx_kit_item_discount"><strike><?=SaleFormatCurrency($discount_price, $arItem["PRICE_CURRENCY"])?></strike></div>
											<?endif?>	
										</div>
									</div>
								</div>
								<div class="bx_kit_item_add" title="<?=$arMessage["CATALOG_SET_ADD"]?>" onclick="catalogSetPopupObj.catalogSetAdd(this.parentNode);"></div>
							</div>
						</div>
						<?endforeach;?>
					<?endif?>
					</div>
				</div>
				<ul class="flex-direction-nav">
					<li class="arr_wrapp a_left flex-nav-prev disabled">
						<span class="bx_kit_item_slider_arrow_left flex-prev disabled" id="bx_catalog_set_construct_slider_left_<?=$curElementId?>" <?if (count($arSetElementsOther) < 5):?>style="display:none"<?endif?> onclick="catalogSetPopupObj.scrollItems('left')"><i></i></span>
					</li>
					<li class="arr_wrapp a_right flex-nav-next <?=(count($arSetElementsOther) <=5 ? "disabled" : "");?>">
						<span class="bx_kit_item_slider_arrow_right flex-next <?=(count($arSetElementsOther) <=5 ? "disabled" : "");?>" id="bx_catalog_set_construct_slider_right_<?=$curElementId?>" <?if (count($arSetElementsOther) < 5):?>style="display:none"<?endif?> onclick="catalogSetPopupObj.scrollItems('right')"><i></i></span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?
CJSCore::Init(array("popup"));
?>
<script>
	
	var equalizePopup = function ()
	{
		$('.bx_modal_container').equalize({children: '.bx_kit_one_section .item.bx_kit_item .cost', reset: true}); 
		$('.bx_modal_container').equalize({children: '.bx_kit_two_section .item.bx_kit_item .cost', reset: true}); 
		$('.bx_modal_container .item').equalize({children: '.item-title', reset: true}); 
		$('.bx_modal_container .item_wrapp').equalize({children: '.bx_kit_item_children', reset: true});
		$('.bx_modal_container .item_wrapp').equalize({children: '.bx_kit_item ', reset: true}); 
		
		$('.bx_modal_container .bx_kit_one_section').equalize({reset: true}); 
		$('.bx_modal_container .bx_kit_two_section .slider_wrapp').equalize({reset: true, children: ".bx_kit_item_slider"}); 
	};
	
	$('.bx_modal_container.bx_kit').ready(function()
	{
		equalizePopup();
		$('.bx_kit_item').hover(
			function() { 
							$(this).find(".bx_kit_item_add").fadeIn(100); 
							$(this).find(".bx_kit_item_del").fadeIn(100); 
							
						},
			function() { 
							$(this).find(".bx_kit_item_add").stop().fadeOut(333); 
							$(this).find(".bx_kit_item_del").stop().fadeOut(333); 
						}
		);
		
	});

	
	$(".popup-window-overlay").click(function()
	{
		$("#bx_catalog_set_construct_popup_<?=$curElementId?>").hide();
		$("#CatalogSetConstructor_<?=$curElementId?>").hide();
		$("#popup-window-overlay-CatalogSetConstructor_<?=$curElementId?>").hide();
	});
	
	var catalogSetPopupObj = new catalogSetConstructPopup(<?=count($arSetElementsOther)?>,
		<?=(count($arSetElementsOther) > 5) ? (100/count($arSetElementsOther)) : 20?>,
		"<?=CUtil::JSEscape($arCurElementInfo["PRICE_CURRENCY"])?>",
		"<?=CUtil::JSEscape($arCurElementInfo["PRICE_VALUE"])?>",
		"<?=CUtil::JSEscape($arCurElementInfo["PRICE_DISCOUNT_VALUE"])?>",
		"<?=CUtil::JSEscape($arCurElementInfo["PRICE_DISCOUNT_DIFFERENCE_VALUE"])?>",
		"<?=CMShop::inputClean($_REQUEST["arParams"]["AJAX_PATH"])?>",
		<?=CUtil::PhpToJSObject($_REQUEST["arParams"]["DEFAULT_SET_IDS"])?>,
		"<?=CMShop::inputClean($_REQUEST["arParams"]["SITE_ID"])?>",
		"<?=$curElementId?>",
		<?=CUtil::PhpToJSObject($_REQUEST["arParams"]["ITEMS_RATIO"])?>,
		"<?=$arCurElementInfo["DETAIL_PICTURE"]["src"] ? $arCurElementInfo["DETAIL_PICTURE"]["src"] : $curTemplatePath."/images/no_foto.png"?>"
	);

	BX.ready(function(){
		jsDD.Enable();

		var destObj = BX.findChildren(BX("bx_catalog_set_construct_popup_<?=$curElementId?>"), {className:"bx_drag_dest"}, true);
		for (var i=0; i<destObj.length; i++)
		{
			jsDD.registerDest(destObj[i]);
			destObj[i].onbxdestdragfinish =  catalogSetConstructDestFinish;  //node was thrown inside of dest
		}
		var dragObj = BX.findChildren(BX("bx_catalog_set_construct_popup_<?=$curElementId?>"), {className:"bx_drag_obj"}, true);
		for (var i=0; i<dragObj.length; i++)
		{
			dragObj[i].onbxdragstart = catalogSetConstructDragStart;
			dragObj[i].onbxdrag = catalogSetConstructDragMove;
			dragObj[i].onbxdraghover = catalogSetConstructDragHover;
			dragObj[i].onbxdraghout = catalogSetConstructDragOut;
			dragObj[i].onbxdragrelease = catalogSetConstructDragRelease;   //node was thrown outside of dest
			jsDD.registerObject(dragObj[i]);
		}
	});
	
</script>