<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);
	
	$class_block="s_".randString();
	$arTab=array();
	if($arResult["SHOW_SLIDER_PROP"]){?>
		<div class="tab_slider_wrapp specials <?=$class_block;?> best_block">
			<div class="top_blocks">
				<ul class="tabs">
					<?$i=1;
					foreach($arResult["TABS"] as $code=>$title):?>
						<li data-code="<?=$code?>" <?=($i==1 ? "class='cur'" : "")?>><span><?=$title;?></span></li>
						<?$i++;?>
					<?endforeach;?>
					<li class="stretch"></li>
				</ul>
				<ul class="slider_navigation top">
					<?$i=1;
					foreach($arResult["TABS"] as $code=>$title):?>
						<li class="tabs_slider_navigation <?=$code?>_nav <?=($i==1 ? "cur" : "")?>" data-code="<?=$code?>"></li>
						<?$i++;?>
					<?endforeach;?>
				</ul>
			</div>
			<ul class="tabs_content">
				<?foreach($arResult["TABS"] as $code=>$title){?>
					<li class="tab <?=$code?>_wrapp" data-code="<?=$code?>">
						<ul class="tabs_slider <?=$code?>_slides wr">
							<?
							$GLOBALS[$arParams["FILTER_NAME"]]=array("PROPERTY_HIT_VALUE" => array($title));
							if($arParams["SECTION_ID"]){
								$GLOBALS[$arParams["FILTER_NAME"]]["SECTION_ID"]=$arParams["SECTION_ID"];
								$GLOBALS[$arParams["FILTER_NAME"]]["INCLUDE_SUBSECTIONS"] = "Y"; 
							}?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:catalog.top",
								"main",
								array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"FILTER_NAME" => $arParams["FILTER_NAME"],
									"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
									"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
									"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
									"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
									"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
									"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
									"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
									"BASKET_URL" => $arParams["BASKET_URL"],
									"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
									"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
									"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
									"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
									"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
									"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
									"ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
									"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
									"PROPERTY_CODE" => $arParams["PROP_CODE"],
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
									"CACHE_FILTER" => "Y",
									"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
									"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
									"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
									"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
									"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
									"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
									"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
									"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
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
					start: function(slider){
						slider.find('li').css('opacity', 1);
					},
					controlsContainer: '.tabs_slider_navigation.cur',
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
								start: function(slider){
									slider.find('li').css('opacity', 1);
								},
								controlsContainer: '.tabs_slider_navigation.cur',
							});
						}
						$(window).resize();
					}
				});
				$(window).resize(function(){
						var sliderWidth = $('.tab_slider_wrapp.<?=$class_block;?>').outerWidth();
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').css('height', '');
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur .catalog_item .item-title').css('height', '');
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur .catalog_item .item_info').css('height', '');
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider .buttons_block').hide();
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item-title'}); 
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.item_info'}); 
					//setTimeout(function(){
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content > li.cur').equalize({children: '.catalog_item'});
						var itemsButtonsHeight = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur .tabs_slider > li .buttons_block').height();
						var tabsContentUnhover = $('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').height() * 1;
						/*if(tabsContentUnhover<350)
							tabsContentUnhover=400;*/
						var tabsContentHover = tabsContentUnhover + itemsButtonsHeight+50;
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-unhover', tabsContentUnhover);
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content .tab.cur').attr('data-hover', tabsContentHover);
						$('.tab_slider_wrapp.<?=$class_block;?> .tabs_content').height(tabsContentUnhover);
					//},250);
				});
				$(window).resize();
				$('.<?=$class_block;?> .tabs_slider li').hover(
					function(){
						var tabsContentHover = $(this).parents('.tab').attr('data-hover') * 1;
						$(this).parents('.tab').fadeTo(100, 1);
						$(this).parents('.tab').stop().css({'height': tabsContentHover});
						$(this).find('.buttons_block').fadeIn(750, 'easeOutCirc');
					},
					function(){
						var tabsContentUnhoverHover = $(this).parents('.tab').attr('data-unhover') * 1;
						$(this).parents('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
						$(this).find('.buttons_block').stop().fadeOut(103);
					}
				);
			})
		</script>
	<?}?>