<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$this->setFrameMode(true);
$currencyList = '';
if (!empty($arResult['CURRENCIES'])){
	$templateLibrary[] = 'currency';
}
$templateData = array(
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$arSkuTemplate = array();
if (!empty($arResult['SKU_PROPS'])){
	$arSkuTemplate=CMShop::GetSKUPropsArray($arResult['SKU_PROPS'], $arResult["SKU_IBLOCK_ID"], "list", $arParams["OFFER_HIDE_NAME_PROPS"]);
}



$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
$useStores = $arParams["USE_STORE"] == "Y" && $arResult["STORES_COUNT"] && $arQuantityData["RIGHTS"]["SHOW_QUANTITY"];
$showCustomOffer=(($arResult['OFFERS'] && $arParams["TYPE_SKU"] !="N") ? true : false);


$strMeasure='';
if($arResult["OFFERS"]){
	$strMeasure=$arResult["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
}else{
	if (($arParams["SHOW_MEASURE"]=="Y")&&($arResult["CATALOG_MEASURE"])){
		$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
		$strMeasure=$arMeasure["SYMBOL_RUS"];
	}
	
}
$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);
?>
<div data-rel="<?=$arResult['DETAIL_PAGE_URL']?>" class="item_main_info <?=(!$showCustomOffer ? "noffer" : "");?>" <?=$arItemIDs["strMainID"]?'id="'.$arItemIDs["strMainID"].'"':''?> itemscope="" itemtype="https://schema.org/Product">
<meta itemprop="name" content="<?=$arResult["NAME"]?>"/>
	<div class="img_wrapper">
		<div class="stickers">
			<?if (is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
				<?foreach($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
					<div class="sticker_<?=strtolower($class);?>" title="<?=$arResult["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
				<?}?>
			<?endif;?>
		</div>
		<?if($arResult["DETAIL_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array("width" => 500, "height" => 500), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<amp-img  layout="responsive" src="<?=$img['src']?>" width=<?=$img['width']?> height=<?=$img['height']?> ></amp-img>
									<?elseif($arResult["PREVIEW_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arResult["PREVIEW_PICTURE"], array("width" => 500, "height" => 500), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<amp-img  layout="responsive" src="<?=$img['src']?>" width=<?=$img['width']?> height=<?=$img['height']?> ></amp-img>
									<?else:?>
										<amp-img  layout="responsive" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" width=300 height=300 ></amp-img>
									<?endif;?>
		
	</div>
	<div class="right_info">
		<div class="info_item">
			<?if((!$arResult["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arResult["CAN_BUY"]) || ($arParams["DISPLAY_COMPARE"] == "Y") || strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || $arResult["PROPERTIES"]["CATALOG_BREND"]["VALUE"]){?>
				<div class="top_info">
					<div class="wrap_md">
						<?if($arResult["PROPERTIES"]["CATALOG_BREND"]["VALUE"]){?>
						<?  $arFilter2 = Array('IBLOCK_ID'=>26, "ID"=>$arResult["PROPERTIES"]["CATALOG_BREND"]["VALUE"]);
  $db_list2 = CIBlockSection::GetList(Array(), $arFilter2, false, array("UF_IMG_BRAND"));
  if($ar_result2 = $db_list2->GetNext())
  {
	  $all_b[$ar_result2["ID"]]=array($ar_result2["NAME"], CFile::GetPath($ar_result2["UF_IMG_BRAND"]), $ar_result2["CODE"]);
	  $file_b = CFile::ResizeImageGet($ar_result2["UF_IMG_BRAND"], array( "width" => 100, "height" => 100 ), BX_RESIZE_IMAGE_PROPORTIONAL,true);?>
							<div class="brand iblock">
								<?if(!$file_b["src"]):?>
									<b class="block_title"><?=GetMessage("BRAND");?>:</b>
									<a href="/catalog/vse_brendy/<?=$ar_result2["CODE"]?>/"><?=$ar_result2["NAME"]?></a>
								<?else:?>
									<a class="brand_picture" href="/catalog/vse_brendy/<?=$ar_result2["CODE"]?>/">
										<amp-img  layout="responsive" src="<?=$file_b["src"]?>" width=<?=$file_b["width"]?>  height=<?=$file_b["height"]?>></amp-img>
									</a>
								<?endif;?>
							</div>
							<?}?>
						<?}?>
					</div>
				</div>
			<?}?>
			<div class="middle_info wrap_md">
				<div class="prices_block iblock">
					<div class="cost prices clearfix">
						<?
						?>
							<?if( count( $arResult["OFFERS"] ) > 0 ){
								$minPrice = false;
								$min_price_id=0;
								if (isset($arResult['MIN_PRICE']) || isset($arResult['RATIO_PRICE'])){
									// $minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
									$minPrice = $arResult['MIN_PRICE'];
								}
								$offer_id=0;
								if($arParams["TYPE_SKU"]=="N"){
									$offer_id=$minPrice["MIN_ITEM_ID"];
								}
								$min_price_id=$minPrice["MIN_PRICE_ID"];
								if(!$min_price_id)
									$min_price_id=$minPrice["PRICE_ID"];
								if($minPrice["MIN_ITEM_ID"])
									$item_id=$minPrice["MIN_ITEM_ID"];
								if($arParams["SHOW_OLD_PRICE"]=="Y"&&$minPrice["DISCOUNT_VALUE"]>0){?>
									<div class="price price_on" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PRICE']; ?>">
										<?if(strlen($minPrice["PRINT_DISCOUNT_VALUE"])):?><?=$minPrice["PRINT_DISCOUNT_VALUE"];?>
											<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
											<?}?>
										<?endif;?>
									</div>
									<div class="price discount" >
									<!--<strike>-->
									<span class="oldp-new" id="<?=$arItemIDs["ALL_ITEM_IDS"]['OLD_PRICE']?>" <?=(!$minPrice["DISCOUNT_DIFF"] ? 'style="display:none;"' : '')?>>
										<?=$minPrice["PRINT_VALUE"];?></span>
									</div>
									<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
										<div class="sale_block" <?=(!$minPrice["DISCOUNT_DIFF"] ? 'style="display:none;"' : '')?>>
											<?$percent=round(($minPrice["DISCOUNT_DIFF"]/$minPrice["VALUE"])*100, 2);?>
											<div class="value">-<?=$percent;?>%</div>
											<div class="clearfix"></div>
										</div>
									<?}?>
								<?}elseif($minPrice["DISCOUNT_VALUE"]>0){?>
									<div class="price price_on" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PRICE']; ?>">
										<?if(strlen($minPrice["PRINT_DISCOUNT_VALUE"])):?>
											<?=$minPrice['PRINT_DISCOUNT_VALUE'];?>
											<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
											<?}?>
										<?endif;?>
									</div>
								<?}?>
								<div itemprop="offers" itemscope="" itemtype="https://schema.org/Offer" style="display:none;">
									<span class="price-val" itemprop="price" content="<?=$arResult["MIN_PRICE"]["DISCOUNT_VALUE"]?>"><?=$arResult["MIN_PRICE"]["DISCOUNT_VALUE"]?></span> 
									<span class="currency" itemprop="priceCurrency" content="RUB">руб.</span>
									<link itemprop="availability" href="https://schema.org/InStock">
								</div>
							<?}?>
					</div>
					<?
					?>
					<?if( count( $arResult["OFFERS"] ) > 0 ){?>
						<?foreach($arResult["OFFERS"] as $arOffer){?>
							<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arOffer["ID"], $USER->GetUserGroupArray(), "N", $min_price_id, SITE_ID );
							// print_r($arOffer);
							$arDiscount=array();
							if($arDiscounts)
								$arDiscount=current($arDiscounts);
							if($arDiscount["ACTIVE_TO"]){?>
								<div class="view_sale_block offers o_<?=$arOffer["ID"];?>" <?=($offer_id && $offer_id==$arOffer["ID"] ? "style='display: block;'" : "");?>>
									<div class="count_d_block">
										<span class="active_to_<?=$arOffer["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
										<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
										<span class="countdown countdown_<?=$arOffer["ID"]?> values"></span>
										
										
									</div>
									<div class="quantity_block">
										<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
										<div class="values">
											<span class="item">
												<?if($arParams["TYPE_SKU"]=="N"){?>
													<?=$totalCount;?>
												<?}else{?>
													<?=(int)$arOffer["CATALOG_QUANTITY"];?>
												<?}?>
												<div class="text"><?=GetMessage("TITLE_QUANTITY");?></div>
											</span>
										</div>
									</div>
								</div>
							<?}?>
						<?}?>
					<?}
					?>
					<?=$arQuantityData["HTML"];?>
					
					
				</div>
				<div class="buy_block iblock">
					<?if($arResult["OFFERS"] && $showCustomOffer){?>
						<div class="sku_props">					
							<?if (!empty($arResult['OFFERS_PROP'])){?>
								<div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
									<?foreach ($arSkuTemplate as $code => $strTemplate){
										if (!isset($arResult['OFFERS_PROP'][$code]))
											continue;
										echo str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate);
									}?>
								</div>
							<?}?>
							
							
						</div>
					<?}?>
					<?if(!$arResult["OFFERS"]):?>
						
						<div class="counter_wrapp">
							<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arResult["CAN_BUY"]):?>
								<div class="counter_block big_basket" data-offers="<?=($arResult["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arResult["ID"];?>" <?=(($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N") ? "style='display: none;'" : "");?>>
									<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
									<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
									<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
								</div>
							<?endif;?>
							<div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER" ) || !$arResult["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>" 
                            <?=($arAddToBasketData["ACTION"] != "ORDER" && $arResult["CAN_BUY"])?"onclick=\"yaCounter37955450.reachGoal('cart'); ga('send', 'event', 'cart', 'submit'); return true;\"":"";?>>
								<!--noindex-->
									<?=$arAddToBasketData["HTML"]?>
								<!--/noindex-->
							</div>
						</div>
						<?if($arAddToBasketData["ACTION"] !== "NOTHING"):?>
							<?if($arAddToBasketData["ACTION"] == "ADD" && $arResult["CAN_BUY"]):?>
								<div class="wrapp_one_click">
									<span class="transparent big_btn type_block button one_click" data-item="<?=$arResult["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=($totalCount >= $arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : $totalCount)?>" onclick="gtag('event', 'click', {'event_category': 'zakazcard'});oneClickBuy('<?=$arResult["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
										<span><?=GetMessage('ONE_CLICK_BUY')?></span>
									</span>
								</div>
							<?endif;?>
						<?endif;?>
					<?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] == 'TYPE_1'):?>
						<?foreach($arResult["OFFERS"] as $arOffer):?>
							<?
							$totalCount = CMShop::GetTotalCount($arOffer);
							//$arAddToBasketData = CMShop::GetAddToBasketArray($arOffer, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'big_btn read_more');
							$arOffer['IS_OFFER'] = 'Y';
							$arOffer['IBLOCK_ID'] = $arResult['IBLOCK_ID'];
							$arAddToBasketData = CMShop::GetAddToBasketArray($arOffer, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'big_btn');
							$arAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.$arOfferProps.'" data-item', $arAddToBasketData["HTML"]);
							if($arOffer['CATALOG_QUANTITY']<=0)  $arAddToBasketData["HTML"] = str_replace('В корзину', 'Под заказ', str_replace('to-cart', 'to-cart transparent', $arAddToBasketData["HTML"]));							
							?>
							<div class="buys_wrapp o_<?=$arOffer["ID"];?>">
								<div class="counter_wrapp">
									<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arOffer["CAN_BUY"]):?>
										<div class="counter_block big_basket" data-item="<?=$arOffer["ID"];?>" <?=($arParams["TYPE_SKU"]=="N" ? "style='display: none;'" : "");?>>
											<span class="minus">-</span>
											<input type="text" class="text amouttov" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
											<span class="plus" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
										</div>
										<div data-nametov="<?=$arOffer["NAME"]?>" data-urltov="https://cafre.ru<?=$arOffer["DETAIL_PAGE_URL"]?>" data-imgtov="https://cafre.ru<?=$imgbig2["src"];?>" data-pricetov="<?=$arOffer["PRICES"]["BASE"]["DISCOUNT_VALUE"]?>" data-idoffer="<?=$arResult["ID"]?>" class="button_block  <?=(($arAddToBasketData["ACTION"] == "ORDER" ) || !$arOffer["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>" 
											<?=($arAddToBasketData["ACTION"] != "ORDER" && $arOffer["CAN_BUY"])?"onclick=\"yaCounter37955450.reachGoal('cart'); ga('send', 'event', 'cart', 'submit'); return true;\"":"";?>>
											<!--noindex-->
												<?=$arAddToBasketData["HTML"]?>
											<!--/noindex-->
										</div>							
									<?endif;?>									
								</div>
								<?
								if($arAddToBasketData["ACTION"] !== "NOTHING" && $arOffer['CATALOG_QUANTITY']>0  ):
								?>
									<?if($arAddToBasketData["ACTION"] == "ADD" && $arOffer["CAN_BUY"]):?>
										<div class="wrapp_one_click">
											<span class="transparent big_btn type_block button one_click" data-item="<?=$arOffer["ID"]?>" data-iblockID="<?=$arOffer["IBLOCK_ID"]?>" data-quantity="<?=($totalCount >= $arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : $totalCount)?>" data-props="<?=$arOfferProps?>" onclick="gtag('event', 'click', {'event_category': 'zakazcard'});oneClickBuy('<?=$arOffer["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
												<span><?=GetMessage('ONE_CLICK_BUY')?></span>
											</span>
										</div>
									<?endif;?>
								<?endif;?>
							</div>
						<?endforeach;?>
					<?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] != 'TYPE_1'):?>
						<span class="detail_order"><?=GetMessage("BUY_BTN");?></span>
					<?endif;?>
				</div>
				<?if(strlen($arResult["PREVIEW_TEXT"]) != false && strip_tags(trim($arResult["PREVIEW_TEXT"])) != "NULL"):?>
					<div class="preview_text" itemprop="description"><?=$arResult["PREVIEW_TEXT"]?></div>
				<?endif;?>
			</div>

			
			<div class="element_detail_text wrap_md">
				<p class="element_detail_text_strong">Закажите товар без предоплаты и уже через 5 дней забирайте его в пункте выдачи или у курьера</p>

				
				<div class="iblock price_txt">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/element_detail_text.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('CT_BCE_CATALOG_DOP_DESCR')));?>
				</div>
			</div>
		</div>
	</div>
	<div class="clearleft"></div>

	<?if($arParams["SHOW_KIT_PARTS"] == "Y" && $arResult["SET_ITEMS"]):?>
		<div class="set_wrapp set_block">
			<div class="title"><?=GetMessage("GROUP_PARTS_TITLE")?></div>
			<?if($arSetItem["PREVIEW_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["PREVIEW_PICTURE"], array("width" => 340, "height" => 340), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<amp-img src=<?=$img["src"]?> layout="responsive" width=<?=$img['width']?> height=<?=$img['height']?>></amp-img>
									<?elseif($arSetItem["DETAIL_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["DETAIL_PICTURE"], array("width" => 340, "height" => 340), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<amp-img src=<?=$img["src"]?> layout="responsive" width=<?=$img['width']?> height=<?=$img['height']?>></amp-img>
									<?else:?>
										<amp-img src=<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png layout="responsive" width=300 height=300></amp-img>
										<img class="imgtop_elem" src="" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?endif;?>
									
									
			
			
			<ul>
				<?foreach($arResult["SET_ITEMS"] as $iii => $arSetItem):?>
					<li class="item">
						<div class="item_inner">
							<div class="image">
								<a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>">
									
								</a>
							</div>
							<div class="item_info">
								<div class="item-title">
									<a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>"><span><?=$arSetItem["NAME"]?></span></a>
								</div>
								<?if($arParams["SHOW_KIT_PARTS_PRICES"] == "Y"):?>
									<div class="cost prices clearfix">
										<?
										$arCountPricesCanAccess = 0;
										foreach($arSetItem["PRICES"] as $key => $arPrice){
											if($arPrice["CAN_ACCESS"]){
												$arCountPricesCanAccess++;
											}
										}?>
										<?foreach($arSetItem["PRICES"] as $key => $arPrice):?>
											<?foreach($arSetItem["PRICES"] as $key => $arPrice):?>
												<?if($arPrice["CAN_ACCESS"]):?>
													<?$price = CPrice::GetByID($arPrice["ID"]);?>
													<?if($arCountPricesCanAccess > 1):?>
														<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
													<?endif;?>
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]  && $arParams["SHOW_OLD_PRICE"]=="Y"):?>
														<div class="price">
															<?=$arPrice["PRINT_DISCOUNT_VALUE"];?>
															<?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?>
																<small>/<?=$strMeasure?></small>
															<?endif;?>
														</div>
														<div class="price discount">
															<!--<strike>-->
									<span class="oldp-new"><?=$arPrice["PRINT_VALUE"]?></span>
														</div>
													<?else:?>
														<div class="price">
															<?=$arPrice["PRINT_VALUE"];?>
															<?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?>
																<small>/<?=$strMeasure?></small>
															<?endif;?>
														</div>
													<?endif;?>
												<?endif;?>
											<?endforeach;?>
										<?endforeach;?>
									</div>
								<?endif;?>
							</div>
						</div>
					</li>
					<?if($arResult["SET_ITEMS"][$iii + 1]):?>
						<li class="separator"></li>
					<?endif;?>
				<?endforeach;?>
			</ul>
		</div>
	<?endif;?>
	
	
</div>

<div class="preim">
	<ul class="preim__list">
		<li class="preim__item">
			<amp-img  src="/bitrix/templates/aspro_mshop/images/preim11.png" width=55  height=55></amp-img>
			<h4><b>Доставим</b><br>без оплаты</h4>
			<p>Доставка товара в любой регион России с оплатой при получении</p>
		</li>
		<li class="preim__item">
			<amp-img   src="/bitrix/templates/aspro_mshop/images/preim22.png" width=55  height=55></amp-img>
			<h4><b>Подарок</b><br>к каждой покупке</h4>
			<p>При покупке на любую сумму вы получите гарантированный подарок</p>
		</li>
		<li class="preim__item">
			<amp-img  src="/bitrix/templates/aspro_mshop/images/preim33.png" width=55  height=55></amp-img>
			<h4><b>100%</b><br>гарантия возврата</h4>
			<p>Вам не подошел или не понравился купленный продукт? Мы вернем вам деньги</p>
		</li>
		<li class="preim__item">
			<amp-img  src="/bitrix/templates/aspro_mshop/images/preim44.png" width=55  height=55></amp-img>
			<h4><b>Сомневаетесь</b><br>в выборе?</h4>
			<p>Получите консультацию специалиста бесплатно на нашем сайте</p>
		</li>
	</ul>
</div>