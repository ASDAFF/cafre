<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult["ITEMS"]):?>
	<div class="specials_slider_wrapp">
		<ul class="tabs">
			<?foreach($arResult["ITEMS"] as $specials_code => $arItems):?>
				<li data-code="<?=$specials_code?>"><span><?=GetMessage($specials_code."_TITLE");?></span></li>
			<?endforeach;?>
			<li class="stretch"></li>
		</ul>
		<ul class="slider_navigation top">
			<?foreach($arResult["ITEMS"] as $specials_code => $arItems):?>
				<li class="specials_slider_navigation <?=$specials_code?>_nav" data-code="<?=$specials_code?>"></li>
			<?endforeach;?>
		</ul>
		<ul class="tabs_content">
			<?foreach($arResult["ITEMS"] as $specials_code => $arItems):?>
				<?			
				if(($arParams["SHOW_MEASURE"] == "Y") && ($arItem["CATALOG_MEASURE"])){
					$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
				}
				?>
				<li class="tab <?=$specials_code?>_wrapp" data-code="<?=$specials_code?>">
					<ul class="specials_slider <?=$specials_code?>_slides wr">
						<?foreach($arItems as $key => $arItem):?>
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
											
											if($minPrice["VALUE"]>$minPrice["DISCOUNT_VALUE"]){?>
												<div class="price"><?=GetMessage("CATALOG_FROM");?> <?=$minPrice["PRINT_DISCOUNT_VALUE"];?>
												<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
													/<?=$strMeasure?>
												<?}?>
												</div>
												<div class="price discount">
													<strike><?=$minPrice["PRINT_VALUE"];?></strike>
												</div>
												<div class="sale_block">
													<?$percent=round(($minPrice["DISCOUNT_DIFF"]/$minPrice["VALUE"])*100, 2);?>
													<?if($percent && $percent<100){?>
														<div class="value">-<?=$percent;?>%</div>
													<?}?>
													<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <?=$minPrice["PRINT_DISCOUNT_DIFF"];?></div>
													<div class="clearfix"></div>
												</div>
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
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]):?>
														<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"];?>
														<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
															/<?=$strMeasure?>
														<?}?>
														</div>
														<div class="price discount">
															<strike><?=$arPrice["PRINT_VALUE"];?></strike>
														</div>
														
														<div class="sale_block">
															<?$percent=round(($arPrice["DISCOUNT_DIFF"]/$arPrice["VALUE"])*100, 2);?>
															<?if($percent && $percent<100){?>
																<div class="value">-<?=$percent;?>%</div>
															<?}?>
															<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <?=$arPrice["PRINT_DISCOUNT_DIFF"];?></div>
															<div class="clearfix"></div>
														</div>
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
										<?=$arAddToBasketData["HTML"]?>
									</div>
								</div>
							</li>
						<?endforeach;?>
					</ul>
				</li>
			<?endforeach;?>
		</ul>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.specials_slider_wrapp .tabs > li').first().addClass('cur');
			$('.specials_slider_wrapp .slider_navigation > li').first().addClass('cur');
			$('.specials_slider_wrapp .tabs_content > li').first().addClass('cur');
			
			var flexsliderItemWidth = 210;
			var flexsliderItemMargin = 20;
			var sliderWidth = $('.specials_slider_wrapp').outerWidth();
			var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
			$('.specials_slider_wrapp .tabs_content > li.cur').flexslider({
				animation: 'slide',
				selector: '.specials_slider > li',
				slideshow: false,
				animationSpeed: 600,
				directionNav: true,
				controlNav: false,
				pauseOnHover: true,
				animationLoop: true, 
				itemWidth: flexsliderItemWidth,
				itemMargin: flexsliderItemMargin, 
				minItems: flexsliderMinItems,
				start: function(slider){
					slider.find('li').css('opacity', 1);
				},
				controlsContainer: '.specials_slider_navigation.cur',
			});
			
			$('.specials_slider_wrapp .tabs > li').click(function(){
				if(!$(this).hasClass('active')){
					var sliderIndex = $(this).index();
					$(this).addClass('active').addClass('cur').siblings().removeClass('active').removeClass('cur');
					$('.specials_slider_wrapp .slider_navigation > li:eq(' + sliderIndex + ')').addClass('cur').show().siblings().removeClass('cur');
					$('.specials_slider_wrapp .tabs_content > li:eq(' + sliderIndex + ')').addClass('cur').siblings().removeClass('cur');
					if(!$('.specials_slider_wrapp .tabs_content > li.cur .flex-viewport').length){
						$('.specials_slider_wrapp .tabs_content > li.cur').flexslider({
							animation: 'slide',
							selector: '.specials_slider > li',
							slideshow: false,
							animationSpeed: 600,
							directionNav: true,
							controlNav: false,
							pauseOnHover: true,
							animationLoop: true, 
							itemWidth: flexsliderItemWidth,
							itemMargin: flexsliderItemMargin, 
							minItems: flexsliderMinItems,
							start: function(slider){
								slider.find('li').css('opacity', 1);
							},
							controlsContainer: '.specials_slider_navigation.cur',
						});
					}
					$(window).resize();
				}
			});
			
			$(window).resize(function(){
				var sliderWidth = $('.specials_slider_wrapp').outerWidth();
				/*$('.specials_slider_wrapp .tabs_content > li.cur').css('height', '');
				$('.specials_slider_wrapp .tabs_content > li.cur .catalog_item').css('height', '');
				$('.specials_slider_wrapp .tabs_content > li.cur .catalog_item .item-title').css('height', '');
				$('.specials_slider_wrapp .tabs_content > li.cur .catalog_item .item_info').css('height', '');*/
				$('.specials_slider_wrapp .tabs_content .tab.cur .specials_slider .buttons_block').hide();
				$('.specials_slider_wrapp .tabs_content > li.cur').equalize({children: '.item-title'}); 
				$('.specials_slider_wrapp .tabs_content > li.cur').equalize({children: '.item_info'}); 
				$('.specials_slider_wrapp .tabs_content > li.cur').equalize({children: '.catalog_item'});
				
				var itemsButtonsHeight = $('.specials_slider_wrapp .tabs_content .tab.cur .specials_slider > li .buttons_block').height();
				var tabsContentUnhover = $('.specials_slider_wrapp .tabs_content .tab.cur').height() * 1;
				var tabsContentHover = tabsContentUnhover + itemsButtonsHeight+50;
				$('.specials_slider_wrapp .tabs_content .tab.cur').attr('data-unhover', tabsContentUnhover);
				$('.specials_slider_wrapp .tabs_content .tab.cur').attr('data-hover', tabsContentHover);
				$('.specials_slider_wrapp .tabs_content').height(tabsContentUnhover);
				if($('.specials_slider_wrapp .tabs_content > li.cur').length && typeof($('.specials_slider_wrapp .tabs_content > li.cur').data('flexslider')) !== 'undefined'){
					$('.specials_slider_wrapp .tabs_content > li.cur').data('flexslider').vars.minItems = flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
				//	$('.specials_slider_wrapp .tabs_content > li.cur').data('flexslider').vars.maxItems = 3;
					$('.specials_slider_wrapp .tabs_content > li.cur').flexslider(0);
				}
				if($('.specials_slider_wrapp .tabs_content > li.cur').find('.specials_slider > li').length > flexsliderMinItems){
					$('.specials_slider_wrapp .slider_navigation > li.cur').show();
					$('.specials_slider_wrapp .slider_navigation > li.cur > ul').show();
				}
				else{
					$('.specials_slider_wrapp .slider_navigation > li.cur').hide();
					$('.specials_slider_wrapp .slider_navigation > li.cur > ul').hide();
				}
			});
			
			$(window).resize();
		});
		</script>
		<?//$frame = $this->createFrame()->begin('');?>
		<script type="text/javascript">
		$(document).ready(function(){
			$(window).resize();
			$('.specials_slider > li').hover(
				function(){
					//if($(window).outerWidth()>550){
						var tabsContentHover = $(this).parents('.tab').attr('data-hover') * 1;
						$(this).parents('.tab').fadeTo(100, 1);
						$(this).parents('.tab').stop().css({'height': tabsContentHover});
						$(this).find('.buttons_block').fadeIn(750, 'easeOutCirc');
					//}
				},
				function(){
					//if($(window).outerWidth()>550){
						var tabsContentUnhoverHover = $(this).parents('.tab').attr('data-unhover') * 1;
						$(this).parents('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
						$(this).find('.buttons_block').stop().fadeOut(203);
					//}
				}
			);
		});
		</script>
		<?//$frame->end();?>
	</div>
<?endif;?>