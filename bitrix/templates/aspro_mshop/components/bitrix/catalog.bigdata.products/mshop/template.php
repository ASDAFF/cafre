<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");
$templateData = array(
	//'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);
$injectId = 'bigdata_recommeded_products_'.rand();?>
<!-- type="application/javascript"-->
<script>
	BX.cookie_prefix = '<?=CUtil::JSEscape(COption::GetOptionString("main", "cookie_name", "BITRIX_SM"))?>';
	BX.cookie_domain = '<?=$APPLICATION->GetCookieDomain()?>';
	BX.current_server_time = '<?=time()?>';

	BX.ready(function(){
		bx_rcm_recommendation_event_attaching(BX('<?=$injectId?>_items'));
	});
</script>
<?if (isset($arResult['REQUEST_ITEMS'])){
	CJSCore::Init(array('ajax'));
	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');?>

	<div id="<?=$injectId?>" class="bigdata_recommended_products_container"></div>
	<!-- type="application/javascript"-->
	<script>
		BX.ready(function(){
			bx_rcm_get_from_cloud(
				'<?=CUtil::JSEscape($injectId)?>',
				<?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>,
				{
					'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
					'template': '<?=CUtil::JSEscape($signedTemplate)?>',
					'site_id': '<?=CUtil::JSEscape(SITE_ID)?>',
					'rcm': 'yes'
				}
			);
		});
	</script>

	<?$frame->end();
	return;
}
if($arResult['ITEMS']){?>
	<input type="hidden" name="bigdata_recommendation_id" value="<?=htmlspecialcharsbx($arResult['RID'])?>">
	<div id="<?=$injectId?>_items" class="bigdata_recommended_products_items">
		<?$class_block="s_".randString();?>
		<div class="viewed_slider common_product wrapper_block recomendation <?=$class_block;?>">
			<div class="top_block">
				<?$title_block=($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('RECOMENDATION_TITLE'));?>
				<div class="title_block"><?=$title_block;?></div>
			</div>
			<ul class="viewed_navigation slider_navigation top_big"></ul>
			<div class="all_wrapp">
				<div class="content_inner tab">
					<ul class="tabs_slider slides wr">
						<?foreach ($arResult['ITEMS'] as $key => $arItem){
							$strTitle = (
								isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
								? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
								: $arItem['NAME']
							);
							$totalCount = CMShop::GetTotalCount($arItem);
							$arQuantityData = CMShop::GetQuantityArray($totalCount);
							$arItem["FRONT_CATALOG"]="Y";
							$arItem["RID"]=$arResult["RID"];
							$arAddToBasketData = CMShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);
							$strMainID = $this->GetEditAreaId($arItem['ID'] . $key);
							
							$strMeasure='';
							if($arItem["OFFERS"]){
								$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
							}else{
								if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
									$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
									$strMeasure=$arMeasure["SYMBOL_RUS"];
								}
							}
							?>
							<li class="catalog_item" id="<?=$strMainID;?>">
								<div class="image_wrapper_block">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>?RID=<?=$arResult["RID"]?>" class="thumb">
										<?if($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"]){?>
											<div class="stickers">
												<?foreach($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
													<div class="sticker_<?=strtolower($class);?>" title="<?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
												<?}?>
											</div>
										<?}?>
										<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
											<div class="like_icons">
												<?if($arItem["CAN_BUY"] && empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
													<div class="wish_item_button">
														<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>"><i></i></span>
														<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>"><i></i></span>
													</div>
												<?endif;?>
												<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
													<div class="compare_item_button">
														<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
														<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
													</div>
												<?endif;?>
											</div>
										<?endif;?>
										<?$arWaterMark = Array(
            array(
                "name" => "watermark",
                "position" => "bottomright", // Положение
                "type" => "image",
                "size" => "100",
                "file" => $_SERVER["DOCUMENT_ROOT"]."/upload/watermark_cafre.png", // Путь к картинке
                "fill" => "exact",
                "alpha_level" => "50",
            )
            );?>
										<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
										
											<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true, $arWaterMark );?>
											<img border="0" src="<?=$img["src"]?>" alt="<?=$strTitle;?>" title="<?=$strTitle;?>" />
										<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
											<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true, $arWaterMark );?>
											<img border="0" src="<?=$img["src"]?>" alt="<?=$strTitle;?>" title="<?=$strTitle;?>" />
										<?elseif(!empty($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"])):?>
													<?$img = CFile::ResizeImageGet($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true, $arWaterMark );?>
													<img border="0" src="<?=$img["src"]?>" alt="<?=$strTitle;?>" title="<?=$strTitle;?>" />
										<?else:?>
											<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$strTitle;?>" title="<?=$strTitle;?>" />
										<?endif;?>
									</a>
								</div>
								<div class="item_info">
									<div class="item-title">
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>?RID=<?=$arResult["RID"]?>"><span><?=$arItem["NAME"]?></span></a>
									</div>
									<?=$arQuantityData["HTML"];?>
									<div class="cost prices clearfix">
										<?if($arItem["OFFERS"]):?>
											<?$minPrice = false;
											if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
												$minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
											
											if($minPrice["VALUE"]>$minPrice["DISCOUNT_VALUE"] && $arParams["SHOW_OLD_PRICE"]=="Y"){?>
												<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$minPrice["PRINT_DISCOUNT_VALUE"];?>
												<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
													/<?=$strMeasure?>
												<?}?>
												</div>
												<div class="price discount">
													<strike><?=$minPrice["PRINT_VALUE"];?></strike>
												</div>
												<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
													<div class="sale_block">
														<?$percent=round(($minPrice["DISCOUNT_DIFF"]/$minPrice["VALUE"])*100, 2);?>
														<?if($percent && $percent<100){?>
															<div class="value">-<?=$percent;?>%</div>
														<?}?>
														<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <?=$minPrice["PRINT_DISCOUNT_DIFF"];?></div>
														<div class="clearfix"></div>
													</div>
												<?}?>
											<?}else{?>
												<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$minPrice['PRINT_DISCOUNT_VALUE'];?>
												<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
													/<?=$strMeasure?>
												<?}?>
												</div>
											<?}?>
										<?elseif($arItem["PRICES"]):?>
											<?
											$arCountPricesCanAccess = 0;
											foreach($arItem["PRICES"] as $key => $arPrice){
												if($arPrice["CAN_ACCESS"]){
													++$arCountPricesCanAccess;
												}
											}?>
											<?foreach($arItem["PRICES"] as $key => $arPrice):?>
												<?if($arPrice["CAN_ACCESS"]):
													$percent=0;?>
													<?$price = CPrice::GetByID($arPrice["ID"]);?>
													<?if($arCountPricesCanAccess > 1):?>
														<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
													<?endif;?>
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"] && $arParams["SHOW_OLD_PRICE"]=="Y"):?>
														<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?>
														<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
															/<?=$strMeasure?>
														<?}?>
														</div>
														<div class="price discount">
															<strike><?=$arPrice["PRINT_VALUE"];?></strike>
														</div>
														<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
															<div class="sale_block">
																<?$percent=round(($arPrice["DISCOUNT_DIFF"]/$arPrice["VALUE"])*100, 2);?>
																<?if($percent && $percent<100){?>
																	<div class="value">-<?=$percent;?>%</div>
																<?}?>
																<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <?=$arPrice["PRINT_DISCOUNT_DIFF"];?></div>
																<div class="clearfix"></div>
															</div>
														<?}?>
													<?else:?>
														<div class="price"><?=$arPrice["PRINT_VALUE"];?>
														<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
															/<?=$strMeasure?>
														<?}?>
														</div>
													<?endif;?>
												<?endif;?>
											<?endforeach;?>
										<?endif;?>
									</div>
									<?$res = CIBlockElement::GetByID($arItem["ID"]);
									if($ar_res = $res->GetNext()):
									?>
									<div class="descript">
				<p><?if(($ar_res['PREVIEW_TEXT'] !== "NULL")&&($ar_res['PREVIEW_TEXT'] != NULL)):?><?echo substr(strip_tags($ar_res['PREVIEW_TEXT']), 0, 100).'...';?><?elseif(($ar_res['DETAIL_TEXT'] !== "NULL")&&($ar_res['DETAIL_TEXT'] != NULL)):?><?echo substr(strip_tags($ar_res["DETAIL_TEXT"]), 0, 100).'...';?><?endif;?></p></div>
				<?endif;?>
				<?if($minPrice['VALUE']>0) {?>
				<div class="buttons_block clearfix">
				<?foreach($arItem["OFFERS"] as $val):
				$totalCount = CMShop::GetTotalCount($val);
							//$arAddToBasketData = CMShop::GetAddToBasketArray($arOffer, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'big_btn read_more');
							$val['IS_OFFER'] = 'Y';
							$val['IBLOCK_ID'] = $arResult['IBLOCK_ID'];
							$arAddToBasketData = CMShop::GetAddToBasketArray($val, val['CATALOG_QUANTITY']==0?1:$totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small bt');
							$arAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.$arOfferProps.'" data-item', $arAddToBasketData["HTML"]);
							if($val['CATALOG_QUANTITY']==0)  $arAddToBasketData["HTML"] = str_replace('В корзину', 'Под заказ', str_replace('to-cart', 'to-cart transparent', $arAddToBasketData["HTML"]));							
				?>
				
			
				<div class="buys_wrapp o_<?=$val["ID"];?>">
								<div class="counter_wrapp">
									<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $val["CAN_BUY"]):?>
										<div class="counter_block" data-item="<?=$val["ID"];?>">
											<span class="minus">-</span>
											<input type="text" class="text amouttov" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
											<span class="plus" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
										</div>
									<?endif;?>
									<?//print_r($arItem['MIN_PRICE']);?>
									<div data-nametov="<?=$arItem["NAME"]?>" data-urltov="https://cafre.ru<?=$arItem["DETAIL_PAGE_URL"]?>" data-imgtov="https://cafre.ru<?=$img["src"];?>" data-pricetov="<?=$arItem['MIN_PRICE']['DISCOUNT_VALUE']?>" data-idoffer="<?=$arItem["ID"]?>" class="button_block button_block_big <?=(($arAddToBasketData["ACTION"] == "ORDER" /*&& !$arOffer["CAN_BUY"]*/) || !$val["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>" 
                                    <?=($arAddToBasketData["ACTION"] != "ORDER" && $val["CAN_BUY"])?"onclick=\"yaCounter37955450.reachGoal('cart'); ga('send', 'event', 'cart', 'submit'); return true;\"":"";?>>
										<!--noindex-->
											<?=$arAddToBasketData["HTML"]?>
										<!--/noindex-->
									</div>
								</div>
							</div>
				
				<?endforeach;?>
					
				</div>
				<?}?>
								</div>
							</li>
						<?}?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.button_block_big').on('click', function(e) {
        var name = $(this).attr("data-nametov");
		var url = $(this).attr("data-urltov");
		var img = $(this).attr("data-imgtov");
		var price = parseInt($(this).attr("data-pricetov"));
		var quant = parseInt($(this).siblings(".counter_block").find(".amouttov").val()); 
		var amout = price*quant;
		carrotquest.track('$cart_added', {

            "$name": name,

           "$url": url,

           "$amount": amout,

          "$img": img,

});
		/*$.ajax({
					url: "/ajax/btn_onbasket.php", 
					type: "post",
					dataType: "json",
					data: { 
						"name": name,
						"url": url,
						"img": img,
						"amout": amout
					},
				
					success: function(data){
						if(data.result){
						console.log(data.result);
						}
					}
				});*/
    });
			var flexsliderItemWidth = 210;
			var flexsliderItemMargin = 20;
			var sliderWidth = $('.specials_slider_wrapp').outerWidth();
			//var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
			$('.viewed_slider.<?=$class_block;?> .content_inner').flexslider({
				animation: 'slide',
				selector: '.slides > li',
				slideshow: false,
				animationSpeed: 600,
				directionNav: true,
				controlNav: false,
				pauseOnHover: true,
				animationLoop: true, 
				itemWidth: flexsliderItemWidth,
				itemMargin: flexsliderItemMargin, 
				//minItems: flexsliderMinItems,
				controlsContainer: '.viewed_navigation',
				start: function(slider){
					slider.find('li').css('opacity', 1);
				}
			});
			$(window).resize(function(){
				var itemsButtonsHeight = $('.wrapper_block.<?=$class_block;?> .wr > li .buttons_block').height();
				$('.wrapper_block.<?=$class_block;?> .wr .buttons_block').hide();
				if($('.wrapper_block.<?=$class_block;?> .all_wrapp .content_inner').attr('data-hover') ==undefined){
					var tabsContentUnhover = ($('.wrapper_block.<?=$class_block;?> .all_wrapp').height() * 1)+20;
					var tabsContentHover = tabsContentUnhover + itemsButtonsHeight+50;
					$('.wrapper_block.<?=$class_block;?> .all_wrapp .content_inner').attr('data-unhover', tabsContentUnhover);
					$('.wrapper_block.<?=$class_block;?> .all_wrapp .content_inner').attr('data-hover', tabsContentHover);
					$('.wrapper_block.<?=$class_block;?> .all_wrapp').height(tabsContentUnhover);
					$('.wrapper_block.<?=$class_block;?> .all_wrapp .content_inner').addClass('absolute');
				}
			});
			$(window).resize();
			$('.wrapper_block.<?=$class_block;?> .wr > li').hover(
				function(){
					//if($(window).outerWidth()>550){
						var tabsContentHover = $(this).closest('.content_inner').attr('data-hover') * 1;
						$(this).closest('.content_inner').fadeTo(100, 1);
						$(this).closest('.content_inner').stop().css({'height': tabsContentHover});
						$(this).find('.buttons_block').fadeIn(450, 'easeOutCirc');
					//}
				},
				function(){
					//if($(window).outerWidth()>550){
						var tabsContentUnhoverHover = $(this).closest('.content_inner').attr('data-unhover') * 1;
						$(this).closest('.content_inner').stop().animate({'height': tabsContentUnhoverHover}, 100);
						$(this).find('.buttons_block').stop().fadeOut(233);
					//}
				}
			);
			
		});
	</script>
<?}
$frame->end();?>