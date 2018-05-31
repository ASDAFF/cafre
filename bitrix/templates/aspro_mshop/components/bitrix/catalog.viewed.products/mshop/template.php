<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$frame = $this->createFrame()->begin();
	
	$class_block="s_".randString();
	$title_block_viewed=($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('VIEWED_TITLE'));
	$title_block_best=($arParams["TITLE_BLOCK_BEST"] ? $arParams["TITLE_BLOCK_BEST"] : GetMessage('BEST_TITLE'));
	$arTab=array();
	if($arResult['ITEMS']){
		$arTab["VIEWED"]=$title_block_viewed;
	}
	if($arParams['SHOW_TOP_ELEMENTS'] !="N"){
		$arTab["BEST"]=$title_block_best;
	}
	if($arTab){?>
		<div class="tab_slider_wrapp <?=$class_block;?> best_block">
			<div class="top_blocks">
				<ul class="tabs">
					<?$i=1;
					foreach($arTab as $code=>$title):?>
						<li data-code="<?=$code?>" <?=($i==1 ? "class='cur'" : "")?>><span><?=$title;?></span></li>
						<?$i++;?>
					<?endforeach;?>
					<li class="stretch"></li>
				</ul>
				<ul class="slider_navigation top">
					<?$i=1;
					foreach($arTab as $code=>$title):?>
						<li class="tabs_slider_navigation <?=$code?>_nav <?=($i==1 ? "cur" : "")?>" data-code="<?=$code?>"></li>
						<?$i++;?>
					<?endforeach;?>
				</ul>
			</div>
			<ul class="tabs_content">
				<?foreach($arTab as $code=>$title){?>
					<li class="tab <?=$code?>_wrapp" data-code="<?=$code?>">
						<ul class="tabs_slider <?=$code?>_slides wr">
							<?if($code=="VIEWED"){
								$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
								$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
								$elementDeleteParams = array('CONFIRM' => GetMessage('CVP_TPL_ELEMENT_DELETE_CONFIRM'));
								?>
								<?foreach ($arResult['ITEMS'] as $key => $arItem){
									$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $elementEdit);
									$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $elementDelete, $elementDeleteParams);
									$strMainID = $this->GetEditAreaId($arItem['ID']);
									$strTitle = (
										isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
										? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
										: $arItem['NAME']
									);
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
									<li class="catalog_item" id="<?=$strMainID;?>">
										<div class="image_wrapper_block">
											<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
												<?if($arItem["DISPLAY_PROPERTIES"]["HIT"]){?>
													<div class="stickers">
														<?foreach($arItem["DISPLAY_PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
															<div class="sticker_<?=strtolower($class);?>" title="<?=$arItem["DISPLAY_PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
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
												<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
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
																<?/*<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <?=$minPrice["PRINT_DISCOUNT_DIFF"];?></div>*/?>
																<div class="clearfix"></div>
															</div>
														<?}?>
													<?}else{?>
														<div class="price"><?=$minPrice['PRINT_DISCOUNT_VALUE'];?>
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
											<?//print_r($arItem);?>
											<?$res = CIBlockElement::GetByID($arItem["ID"]);
									if($ar_res = $res->GetNext()):
									?>
									<div class="descript">
				<p><?if(($ar_res['PREVIEW_TEXT'] !== "NULL")&&($ar_res['PREVIEW_TEXT'] != NULL)):?><?echo substr(strip_tags($ar_res['PREVIEW_TEXT']), 0, 100).'...';?><?elseif(($ar_res['DETAIL_TEXT'] !== "NULL")&&($ar_res['DETAIL_TEXT'] != NULL)):?><?echo substr(strip_tags($ar_res["DETAIL_TEXT"]), 0, 100).'...';?><?endif;?></p></div>
				<?endif;?>
											<div class="buttons_block clearfix">
												<?foreach($arItem["OFFERS"] as $val):
				$totalCount = CMShop::GetTotalCount($val);
							//$arAddToBasketData = CMShop::GetAddToBasketArray($arOffer, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'big_btn read_more');
							$val['IS_OFFER'] = 'Y';
							$val['IBLOCK_ID'] = $arResult['IBLOCK_ID'];
							if($val['CATALOG_QUANTITY']<=0)  $totalCount=1;							
							$arAddToBasketData = CMShop::GetAddToBasketArray($val, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small bt');
							$arAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.$arOfferProps.'" data-item', $arAddToBasketData["HTML"]);
							if($val['CATALOG_QUANTITY']<=0)  $arAddToBasketData["HTML"] = str_replace('В корзину', 'Под заказ', str_replace('to-cart', 'to-cart transparent', $arAddToBasketData["HTML"]));							
				?>
				
			<?if($minPrice["VALUE"]>0) {?>
				<div class="buys_wrapp o_<?=$val["ID"];?>">
								<div class="counter_wrapp">
									<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $val["CAN_BUY"]):?>
										<div class="counter_block" data-item="<?=$val["ID"];?>">
											<span class="minus">-</span>
											<input type="text" class="text amouttov" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
											<span class="plus" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
										</div>
									<?endif;?>
									<div data-nametov="<?=$arItem["NAME"]?>" data-urltov="https://cafre.ru<?=$arItem["DETAIL_PAGE_URL"]?>" data-imgtov="https://cafre.ru<?=$img["src"];?>" data-pricetov="<?=$arItem['MIN_PRICE']['DISCOUNT_VALUE']?>" data-idoffer="<?=$arItem["ID"]?>" class="button_block button_block_view <?=(($arAddToBasketData["ACTION"] == "ORDER" /*&& !$arOffer["CAN_BUY"]*/) || !$val["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>" 
                                    <?=($arAddToBasketData["ACTION"] != "ORDER" && $val["CAN_BUY"])?"onclick=\"yaCounter37955450.reachGoal('cart'); ga('send', 'event', 'cart', 'submit'); return true;\"":"";?>>
										<!--noindex-->
											<?=$arAddToBasketData["HTML"]?>
										<!--/noindex-->
									</div>
								</div>
							</div>
			<?}?>
				<?endforeach;?>
											</div>
										</div>
									</li>
								<?}
							}else{?>
								<?
								$GLOBALS[$arParams["FILTER_NAME"]]=array("!PROPERTY_HIT" => false);
								if($arParams["TOP_SECTION_ID"]){
									$GLOBALS[$arParams["FILTER_NAME"]]["SECTION_ID"]=$arParams["TOP_SECTION_ID"];
									$GLOBALS[$arParams["FILTER_NAME"]]["INCLUDE_SUBSECTIONS"] = "Y"; 
								}?>
								<?$APPLICATION->IncludeComponent(
									"bitrix:catalog.top",
									"main",
									array(
										"TITLE_BLOCK" => $arParams["SECTION_TOP_BLOCK_TITLE"],
										"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
										"IBLOCK_ID" => $arParams["IBLOCK_ID"],
										"FILTER_NAME" => $arParams["FILTER_NAME"],
										"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
										"ELEMENT_SORT_FIELD" => $arParams["TOP_ELEMENT_SORT_FIELD"],
										"ELEMENT_SORT_ORDER" => $arParams["TOP_ELEMENT_SORT_ORDER"],
										"ELEMENT_SORT_FIELD2" => $arParams["TOP_ELEMENT_SORT_FIELD2"],
										"ELEMENT_SORT_ORDER2" => $arParams["TOP_ELEMENT_SORT_ORDER2"],
										"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
										"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
										"BASKET_URL" => $arParams["BASKET_URL"],
										"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
										"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
										"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
										"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
										"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
										"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
										"ELEMENT_COUNT" => $arParams["ELEMENT_COUNT"],
										"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
										"PROPERTY_CODE" => $arParams["TOP_PROPERTY_CODE"],
										"PRICE_CODE" => $arParams["PRICE_CODE"],
										"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
										"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
										"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
										"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
										"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
										"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
										"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
										"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
										"CACHE_TYPE" => $arParams["CACHE_TYPE"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
										"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
										"OFFERS_FIELD_CODE" => $arParams["TOP_OFFERS_FIELD_CODE"],
										"OFFERS_PROPERTY_CODE" => $arParams["TOP_OFFERS_PROPERTY_CODE"],
										"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
										"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
										"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
										"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
										"OFFERS_LIMIT" => $arParams["TOP_OFFERS_LIMIT"],
										'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
										'CURRENCY_ID' => $arParams['CURRENCY_ID'],
										'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
										'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
										'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
										'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
										'LABEL_PROP' => $arParams['LABEL_PROP'],
										'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
										'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

										'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
										'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
										'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
										'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
										'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
										'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
										'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
										'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
										'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
										'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
										'ADD_TO_BASKET_ACTION' => $basketAction,
										'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
										'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
									),
									false, array("HIDE_ICONS"=>"Y")
								);?>
							<?}?>
						</ul>
					</li>
				<?}?>
			</ul>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs > li').first().addClass('cur');
				$('.tab_slider_wrapp.<?=$class_block;?> .slider_navigation > li').first().addClass('cur');
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li').first().addClass('cur');
				
				var flexsliderItemWidth = 210;
				var flexsliderItemMargin = 20;
				
				var sliderWidth = $('.tab_slider_wrapp.<?=$class_block;?>').outerWidth();
				var flexsliderMinItems = Math.floor(sliderWidth / (flexsliderItemWidth + flexsliderItemMargin));
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').flexslider({
					animation: 'slide',
					selector: '.tabs_slider .catalog_item',
					slideshow: false,
					animationSpeed: 600,
					directionNav: true,
					controlNav: false,
					pauseOnHover: true,
					animationLoop: true, 
					itemWidth: flexsliderItemWidth,
					itemMargin: flexsliderItemMargin, 
					minItems: flexsliderMinItems,
					controlsContainer: '.tabs_slider_navigation.cur',
					start: function(slider){
						slider.find('li').css('opacity', 1);
					}
				});
				
				$('.tab_slider_wrapp.<?=$class_block;?> .tabs > li').click(function(){
					if(!$(this).hasClass('active')){
						var sliderIndex = $(this).index();
						$(this).addClass('active').addClass('cur').siblings().removeClass('active').removeClass('cur');
						$('.tab_slider_wrapp.<?=$class_block;?> .slider_navigation > li:eq(' + sliderIndex + ')').addClass('cur').show().siblings().removeClass('cur');
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li:eq(' + sliderIndex + ')').addClass('cur').siblings().removeClass('cur');
						if(!$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur .flex-viewport').length){
							$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').flexslider({
								animation: 'slide',
								selector: '.tabs_slider .catalog_item',
								slideshow: false,
								animationSpeed: 600,
								directionNav: true,
								controlNav: false,
								pauseOnHover: true,
								animationLoop: true, 
								itemWidth: flexsliderItemWidth,
								itemMargin: flexsliderItemMargin, 
								minItems: flexsliderMinItems,
								controlsContainer: '.tabs_slider_navigation.cur',
							});
						}
						$(window).resize();
					}
				});
				
				$(window).resize(function(){
					var sliderWidth = $('.tab_slider_wrapp.<?=$class_block;?>').outerWidth();
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').css('height', '');
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider .buttons_block').hide();
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item-title'}); 
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item_info'}); 
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.catalog_item'});
					var itemsButtonsHeight = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider > li .buttons_block').height();
					var tabsContentUnhover = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').height() * 1;
					var tabsContentHover = tabsContentUnhover + itemsButtonsHeight+50;
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-unhover', tabsContentUnhover);
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-hover', tabsContentHover);
					$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content').height(tabsContentUnhover);
				});
				
				$(window).resize();
				/*$('.<?=$class_block;?> .tabs_slider li').hover(
					function(){
						var tabsContentHover = $(this).parents('.tab').attr('data-hover') * 1;
						$(this).parents('.tab').fadeTo(100, 1);
						$(this).parents('.tab').stop().css({'height': tabsContentHover});
						$(this).find('.buttons_block').fadeIn(750, 'easeOutCirc');
					},
					function(){
						var tabsContentUnhoverHover = $(this).parents('.tab').attr('data-unhover') * 1;
						$(this).parents('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
						$(this).find('.buttons_block').stop().fadeOut(203);
					}
				);*/
			})
		</script>
	<?}?>
<?$frame->beginStub();?>
<?$frame->end();?>