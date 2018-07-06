<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);
global  $catalog_section_name;
$catalog_section_name=$arResult['NAME'];
?>

<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?if(($arParams["AJAX_REQUEST"]=="N") || !isset($arParams["AJAX_REQUEST"])){?>
		<div class="top_wrapper">
			<div class="catalog_block">
	<?}?>
		<?
		

		$arSkuTemplate = array();
		if (!empty($arResult['SKU_PROPS'])){
			$arSkuTemplate=CMShop::GetSKUPropsArray($arResult['SKU_PROPS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"]);
		}
		$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());

		$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);
		?>
		<?foreach($arResult["ITEMS"] as $arItem){
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$arItemIDs=CMShop::GetItemsIDs($arItem);

			$totalCount = CMShop::GetTotalCount($arItem);
			if($totalCount==0) $totalCount=1;
			$arQuantityData = CMShop::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"]);

			$item_id = $arItem["ID"];
			$strMeasure = '';
			if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1'){
				if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
					$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
					$strMeasure = $arMeasure["SYMBOL_RUS"];
				}
				$arAddToBasketData = CMShop::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"]);
			}
			elseif($arItem["OFFERS"]){
				$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
			}
			$arAddToBasketData["ACTION"]='ADD';
			$arItem['CAN_BUY']='Y';
			?>
			<div class="catalog_item_wrapp">
				<div class="catalog_item item_wrap <?=(($_GET['q'])) ? 's' : ''?>" <?=$arItemIDs["strMainID"]?'id="'.$arItemIDs["strMainID"].'"':''?>>
					<div>
						<div class="image_wrapper_block">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb" id="<?=$arItem["ID"].$arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>">
								<div class="stickers">
									<?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
										<?foreach($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
											<div class="sticker_<?=strtolower($class);?>" title="<?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
										<?}?>
									<?endif;?>
								</div>
								<?
								$a_alt=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] );
								$a_title=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] );
								?>
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
								<?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
								<?//print_r($arWaterMark);?>
								<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array( "width" => 500, "height" => 500 ), BX_RESIZE_IMAGE_PROPORTIONAL,true, $arWaterMark);
								?>
									<amp-img  layout="responsive" src="<?=$img["src"]?>" width=<?=$img["width"]?> height=<?=$img["height"]?>></amp-img>
								<?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
									<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 500, "height" => 500 ), BX_RESIZE_IMAGE_PROPORTIONAL,true, $arWaterMark );?>
									<amp-img  layout="responsive" src="<?=$img["src"]?>" width=<?=$img["width"]?>  height=<?=$img["height"]?>></amp-img>
								<?elseif(!empty($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"])):?>
								<?$img = CFile::ResizeImageGet($arItem["PROPERTIES"]["MORE_PHOTO"]["VALUE"][0], array( "width" => 500, "height" => 500 ), BX_RESIZE_IMAGE_PROPORTIONAL,true, $arWaterMark );?>
									<amp-img  layout="responsive" src="<?=$img["src"]?>" width=<?=$img["width"]?>  height=<?=$img["height"]?>></amp-img>
								<?else:?>
									<amp-img  layout="responsive" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" width=300  height=300></amp-img>
								<?endif;?>
								
							</a>
						</div>
						
						<div class="item_info <?=$arParams["TYPE_SKU"]?>">
							<div class="item-title">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
							</div>
							<div class="descript"><p><?if(($arItem["PREVIEW_TEXT"] !== "NULL")&&($arItem["PREVIEW_TEXT"] != NULL)):?><?echo substr(strip_tags($arItem["PREVIEW_TEXT"]), 0, 100).'...';?><?elseif(($arItem["DETAIL_TEXT"] !== "NULL")&&($arItem["DETAIL_TEXT"] != NULL)):?><?echo substr(strip_tags($arItem["DETAIL_TEXT"]), 0, 100).'...';?><?endif;?></p></div>
							
							<div class="cost prices clearfix">
								<?
								/*$frame = $this->createFrame()->begin('');
								$frame->setBrowserStorage(true);*/
								?>
									<?if( $arItem["OFFERS"]){?>
										<?$minPrice = false;
										if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE'])){
											// $minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
											$minPrice = $arItem['MIN_PRICE'];
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
										$prefix = '';
										
										if($minPrice["DISCOUNT_VALUE"]>0&&$arParams["SHOW_OLD_PRICE"]=="Y"){?>
											<div class="price" id="<?=$arItem["ID"].$arItemIDs["ALL_ITEM_IDS"]['PRICE']; ?>">
												<?if(strlen($minPrice["PRINT_DISCOUNT_VALUE"])):?>
													<?=$prefix;?> <?=$minPrice["PRINT_DISCOUNT_VALUE"];?>
													<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
														/<?=$strMeasure?>
													<?}?>
												<?endif;?>
											</div>
											<div class="price discount" id="<?=$arItem["ID"].$arItemIDs["ALL_ITEM_IDS"]['PRICE_OLD']; ?>">
											<!--</strike>-->
												<span class="oldp-new" <?=(!$minPrice["DISCOUNT_DIFF"] ? 'style="display:none;"' : '')?>><?=$minPrice["PRINT_VALUE"];?></span>
											</div>
											<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
												<div class="sale_block" <?=(!$minPrice["DISCOUNT_DIFF"] ? 'style="display:none;"' : '')?>>
													<?$percent=round(($minPrice["DISCOUNT_DIFF"]/$minPrice["VALUE"])*100, 2);?>
													<div class="value">-<?=$percent;?>%</div>
													<?/*?><div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <span><?=$minPrice["PRINT_DISCOUNT_DIFF"];?></span></div><?*/?>
													<div class="clearfix"></div>
												</div>
											<?}?>
										<?}elseif($minPrice["DISCOUNT_VALUE"]>0){?>
											<div class="price" id="<?=$arItemIDs["ALL_ITEM_IDS"]['PRICE']?>">
												<?if(strlen($minPrice["PRINT_DISCOUNT_VALUE"])):?>
													<?=$prefix;?> <?=$minPrice['PRINT_DISCOUNT_VALUE'];?>
												<?endif;?>
											</div>
										<?}?>
									<?}
?>
								<?//$frame->end();?>
							</div>
							<?
							/*$frame = $this->createFrame()->begin('');
							$frame->setBrowserStorage(true);*/
							?>
							<?if( count( $arItem["OFFERS"] ) > 0 ){?>
								<?foreach($arItem["OFFERS"] as $arOffer){?>
									<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arOffer["ID"], $USER->GetUserGroupArray(), "N", $min_price_id, SITE_ID );
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
							<?}else{?>
								<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arItem["ID"], $USER->GetUserGroupArray(), "N", $min_price_id, SITE_ID );
								$arDiscount=array();
								if($arDiscounts)
									$arDiscount=current($arDiscounts);
								if($arDiscount["ACTIVE_TO"]){?>
									<div class="view_sale_block">
										<div class="count_d_block">
											<span class="active_to_<?=$arItem["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
											<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
											<span class="countdown countdown_<?=$arItem["ID"]?> values"></span>
											
										</div>
										<div class="quantity_block">
											<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
											<div class="values">
												<span class="item">
													<?=(int)$totalCount;?>
													<div class="text"><?=GetMessage("TITLE_QUANTITY");?></div>
												</span>
											</div>
										</div>
									</div>
								<?}?>
							<?}?>
							<?//$frame->end();?>
							<div class="hover_block">							
								<?if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1'):?>
									<div class="counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '')?>">
										
												<?="<a href='".$arItem["DETAIL_PAGE_URL"]."'>".$arAddToBasketData["HTML"]."</a>"?>
										
									</div>
								<?elseif($arItem["OFFERS"] && $minPrice["DISCOUNT_VALUE"]>0):?>
									
									
								<?endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?}?>
	<?if(($arParams["AJAX_REQUEST"]=="N") || !isset($arParams["AJAX_REQUEST"])){?>
			</div>
		</div>

	<?}?>
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		<div class="wrap_nav">
	<?}?>
		<div class="bottom_nav <?=$arParams["DISPLAY_TYPE"];?>" <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
			<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
		</div>
		
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		</div>
	<?}?>
<?}else{?>
	<div class="no_goods catalog_block_view">
		<div class="no_products">
			<div class="wrap_text_empty">
				<?if($_REQUEST["set_filter"]){?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products_filter.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}else{?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}?>
			</div>
		</div>
		<?if($_REQUEST["set_filter"]){?>
			<span class="button wide"><?=GetMessage('RESET_FILTERS');?></span>
		<?}?>
	</div>
<?}?>
