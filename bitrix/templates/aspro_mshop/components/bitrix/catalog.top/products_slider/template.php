<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? $this->setFrameMode( true ); ?>
<?
$sliderID  = "specials_slider_wrapp_".randString();
$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption);
?>
<?if($arResult["ITEMS"]):?>
	<div class="common_product wrapper_block top_border1" id="<?=$sliderID?>">
		
		<ul class="slider_navigation top_big"></ul>
		<div class="all_wrapp">
			<div class="content_inner tab">
				<ul class="specials_slider slides wr">
					<?foreach($arResult["ITEMS"] as $key => $arItem):?>
						<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
						$totalCount = CMShop::GetTotalCount($arItem);
						$arQuantityData = CMShop::GetQuantityArray($totalCount);
						$arItem["FRONT_CATALOG"]="Y";
						$arAddToBasketData = CMShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);
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
						<li id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="catalog_item">
							<div class="image_wrapper_block">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
									<?if($arItem["DISPLAY_PROPERTIES"]["HIT"]){?>
										<div class="stickers">
											<?foreach($arItem["DISPLAY_PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
												<div class="sticker_<?=strtolower($class);?>" title="<?=$arItem["DISPLAY_PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
											<?}?>
										</div>
									<?}?>
									<?
									/*$frame = $this->createFrame()->begin('');
									$frame->setBrowserStorage(true);*/
									?>
										<?if( ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y")):?>
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
									<?//$frame->end();?>
									<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
										<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
										<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true );?>
										<img border="0" src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?else:?>
										<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?endif;?>
								</a>
							</div>
							<div class="item_info">
								<div class="item-title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
								</div>
								<?=$arQuantityData["HTML"];?>
								<div class="cost prices clearfix">
									<?
									/*$frame = $this->createFrame('cost', false)->begin();
									$frame->setBrowserStorage(true);*/
									?>
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
													<?/*<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <?=$minPrice["PRINT_DISCOUNT_DIFF"];?></div>*/?>
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
									<?//$frame->end();?>
								</div>
								<div class="buttons_block clearfix">
									<?
									/*$frame = $this->createFrame()->begin('');
									$frame->setBrowserStorage(true);*/
									?>
										<?=$arAddToBasketData["HTML"]?>
									<?//$frame->end();?>
								</div>
							</div>
						</li>
					<?endforeach;?>
				</ul>
			</div>
		</div>
		<?if($arParams["INIT_SLIDER"] == "Y"):?>
			<script type="text/javascript">
				$(document).ready(function(){
					var flexsliderItemWidth = 210;
					var flexsliderItemMargin = 20;
					var sliderWidth = $('#<?=$sliderID?>').outerWidth();
					var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
					
				
					$('#<?=$sliderID?> .content_inner').flexslider({
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
						minItems: flexsliderMinItems,
						controlsContainer: '#<?=$sliderID?> .slider_navigation',
						start: function(slider){
							slider.find('li').css('opacity', 1);
						}
					});
					
					$(window).resize(function(){
						var itemsButtonsHeight = $('.wrapper_block#<?=$sliderID;?> .wr > li .buttons_block').height();
						$('.wrapper_block#<?=$sliderID;?> .wr .buttons_block').hide();
						if($('.wrapper_block#<?=$sliderID;?> .all_wrapp .content_inner').attr('data-hover') ==undefined){
							var tabsContentUnhover = ($('.wrapper_block#<?=$sliderID;?> .all_wrapp').height() * 1)+20;
							var tabsContentHover = tabsContentUnhover + itemsButtonsHeight+50;
							$('.wrapper_block#<?=$sliderID;?> .all_wrapp .content_inner').attr('data-unhover', tabsContentUnhover);
							$('.wrapper_block#<?=$sliderID;?> .all_wrapp .content_inner').attr('data-hover', tabsContentHover);
							$('.wrapper_block#<?=$sliderID;?> .all_wrapp').height(tabsContentUnhover);
							$('.wrapper_block#<?=$sliderID;?> .all_wrapp .content_inner').addClass('absolute');
						}
					});
					if($('#<?=$sliderID?> .slider_navigation .flex-disabled').length > 1){
					$('#<?=$sliderID?> .slider_navigation').hide();
				}
				$('.wrapper_block#<?=$sliderID;?> .wr > li').hover(
					function(){
						var tabsContentHover = $(this).closest('.content_inner').attr('data-hover') * 1;
						$(this).closest('.content_inner').fadeTo(100, 1);
						$(this).closest('.content_inner').stop().css({'height': tabsContentHover});
						$(this).find('.buttons_block').fadeIn(750, 'easeOutCirc');
					},
					function(){
						var tabsContentUnhoverHover = $(this).closest('.content_inner').attr('data-unhover') * 1;
						$(this).closest('.content_inner').stop().animate({'height': tabsContentUnhoverHover}, 100);
						$(this).find('.buttons_block').stop().fadeOut(203);
					}
				);
				});
			</script>
			<?$frame = $this->createFrame()->begin('');?>
			<script type="text/javascript">
			$("#<?=$sliderID?>").ready(function(){
				$(window).resize();
			});
			</script>
			<?$frame->end();?>
		<?endif;?>
	</div>
<?else:?>
	<?$this->setFrameMode(true);?>
	<script type="text/javascript">
	$(document).ready(function(){
		$(".news_detail_wrapp .similar_products_wrapp").remove();
	}); /* dirty hack, remove this code */
	</script>
<?endif;?>