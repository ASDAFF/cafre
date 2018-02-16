<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$this->setFrameMode(true);
$currencyList = '';
if (!empty($arResult['CURRENCIES'])){
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
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
$strMainID = $this->GetEditAreaId($arResult['ID']);

$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$arItemIDs=CMShop::GetItemsIDs($arResult, "Y");
$totalCount = CMShop::GetTotalCount($arResult);
$arQuantityData = CMShop::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], "Y");

$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
$useStores = $arParams["USE_STORE"] == "Y" && $arResult["STORES_COUNT"] && $arQuantityData["RIGHTS"]["SHOW_QUANTITY"];
$showCustomOffer=(($arResult['OFFERS'] && $arParams["TYPE_SKU"] !="N") ? true : false);
if($showCustomOffer){
	$templateData['JS_OBJ'] = $strObName;
}
$strMeasure='';
if($arResult["OFFERS"]){
	$strMeasure=$arResult["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
}else{
	if (($arParams["SHOW_MEASURE"]=="Y")&&($arResult["CATALOG_MEASURE"])){
		$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
		$strMeasure=$arMeasure["SYMBOL_RUS"];
	}
	$arAddToBasketData = CMShop::GetAddToBasketArray($arResult, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'big_btn');
}
$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);
?>
<div dtaa-rel="<?=$arResult['DETAIL_PAGE_URL']?>" class="item_main_info <?=(!$showCustomOffer ? "noffer" : "");?>" id="<?=$arItemIDs["strMainID"];?>">
	<div class="img_wrapper">
		<div class="stickers">
			<?if (is_array($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
				<?foreach($arResult["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
					<div class="sticker_<?=strtolower($class);?>" title="<?=$arResult["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
				<?}?>
			<?endif;?>
		</div>
		<div class="item_slider">
			<?reset($arResult['MORE_PHOTO']);
			$arFirstPhoto = current($arResult['MORE_PHOTO']);?>
			<div class="slides">
				<?//if($showCustomOffer){?>
					<!-- <div class="offers_img wof">
						<?
						$alt=$arFirstPhoto["ALT"];
						$title=$arFirstPhoto["TITLE"];
						?>
						<?if($arFirstPhoto["BIG"]["src"]){?>
                       <a href="<?=$arFirstPhoto["BIG"]["src"]?>" class="fancy_offer" title="<?=$title;?>">
								<img id="<?echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SMALL']['src']; ?>" alt="<?=$alt;?>" title="<?=$title;?>">
                           	</a>
						<?}else{?>
							<a href="javascript:void(0)" class="fancy_offer" title="<?=$title;?>">
								<img id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SRC']; ?>" alt="<?=$alt;?>" title="<?=$title;?>">
             			</a>
                        
						<?}?>
					</div>
                      -->
                   
				<?//}else{
					$arWaterMark = Array(
            array(
                "name" => "watermark",
                "position" => "bottomright", // Положение
                "type" => "image",
                "size" => "100",
                "file" => $_SERVER["DOCUMENT_ROOT"]."/upload/watermark_cafre.png", // Путь к картинке
                "fill" => "exact",
                "alpha_level" => "50",
            )
            );
					if($arResult["MORE_PHOTO"]){
if($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"]){
?>
<li id="photo-0" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
<?
$title = $arResult["PROPERTIES"]["ON_PHOT"]["VALUE"];
$alt = $arResult["PROPERTIES"]["ON_PHOT"]["VALUE"];
?>
<?$file1 = CFile::ResizeImageGet($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"], array( "width" => 540, "height" => 350 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>
<?$file2 = CFile::ResizeImageGet($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"], array(), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>
								<?if(!$isEmpty){?>
								
                                    <a href="<?=$file2["src"];?>" <?=($bIsOneImage ? '' : 'rel="item_slider"')?> class="fancy" title="<?=$title;?>">
                                <img border="0" src="<?=$file1["src"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
                                    </a>
                                <?}else{?>
		                        <img border="0" src="<?=$file1["src"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?}?>
                                
</li>
<?
											   }
						foreach($arResult["MORE_PHOTO"]  as $i => $arImage){
if($arImage["ID"] == $arResult["PROPERTIES"]["ON_PHOT"]["VALUE"])continue;
?>
<?$imgbig = CFile::ResizeImageGet($arImage["ID"], array( "width" => 530, "height" => 340 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>
<?$imgbig2 = CFile::ResizeImageGet($arImage["ID"], array(), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>
							<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
							<?
							$alt=$arImage["ALT"];
							$title=$arImage["TITLE"];
							?>
				<li id="photo-<?=$i?>" <?if($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"]){if(!$i){echo 'class="current"style="display: none;"';}else{echo 'style="display: none;"';}}else{if(!$i) {echo 'class="current"';}else{echo 'style="display: none;"';}}?>>
								<?if(!$isEmpty){?>
                                    <a href="<?=$imgbig2["src"]?>" <?=($bIsOneImage ? '' : 'rel="item_slider"')?> class="fancy" title="<?=$title;?>">
                                <img border="0" src="<?=$imgbig["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
                                    </a>
                                <?}else{?>
                        <img border="0" src="<?=$arImage["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?}?>
                                
							</li>
						<?}
					}
				//}?>
                <?if ($arResult["PROPERTIES"]["FOTO_3D"]["VALUE"] != ""):?>
             <li><img id='image' id="photo-<?=$i++?>"  class="photo3d-preview-image {maxZoom:400, frames:72, yframes:5, useSeparateFrames:true, startImmediately:true, autoPlay:true}" alt="<?=$alt;?>" title="<?=$title;?>" src=<?=$arResult["PROPERTIES"]["FOTO_3D"]["VALUE"]?> border='0' width='600' height='400' /></li>
               <?endif?> 
			</div>
           <?/*thumbs*/?>
			<?if(!$showCustomOffer){
				if(count($arResult["MORE_PHOTO"]) > 1):?>
					<div class="wrapp_thumbs">
						<div class="thumbs" style="max-width:<?=ceil(((count($arResult['MORE_PHOTO']) <= 4 ? count($arResult['MORE_PHOTO']) : 4) * 70) - 10)?>px;">
							<ul class="slides_block" id="thumbs">
								<?foreach($arResult["MORE_PHOTO"]as $i => $arImage):
?>
                                <li <?=(!$i ? 'class="current"' : '')?>>
                                        <span><img border="0" src="<?=$arImage["THUMB"]["src"]?>" alt="<?=$arImage["ALT"];?>" title="<?=$arImage["TITLE"];?>" /></span>  
                                    </li>
								<?endforeach;?>
                                <?if ($arResult["PROPERTIES"]["FOTO_3D"]["VALUE"] != ""):?>
             <li><img id='image' id="photo-<?=$i++?>"  class="photo3d-preview-image" alt="<?=$alt;?>" title="<?=$title;?>" src=<?=$arResult["PROPERTIES"]["FOTO_3D"]["VALUE"]?> border='0' width="402" height="686" /></li>
               <?endif?>
							</ul>
						</div>
						<span class="thumbs_navigation"></span>
					</div>
				<?endif;?>
			<?}else{?>
				<div class="wrapp_thumbs">
					<?foreach ($arResult['OFFERS'] as $key => $arOneOffer){
						if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
							continue;
						$strVisible = (($key == $arResult['OFFERS_SELECTED'] && $arOneOffer['MORE_PHOTO_COUNT']>1) ? '' : 'none');
						?>
						<div class="sliders" data-id="<?=$arOneOffer['ID'];?>" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['SLIDER_CONT_OF_ID'].$arOneOffer['ID']; ?>" style="display: <? echo $strVisible; ?>;">
							<div class="thumbs" style="max-width:<?=ceil(((count($arOneOffer['MORE_PHOTO']) <= 4 ? count($arOneOffer['MORE_PHOTO']) : 4) * 70) - 10)?>px;">
								<ul class="slides_block" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['SLIDER_LIST_OF_ID'].$arOneOffer['ID']; ?>">
									<?
if($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"]){
?>
<li data-value="" data-big="">
<?$imgbig = CFile::ResizeImageGet($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"], array( "width" => 52, "height" => 56 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>
			<span class="cnt"><img border="0" src="<?=$imgbig["src"]?>" alt="" title="" /></span>
</li>
<?
}
foreach ($arOneOffer['MORE_PHOTO'] as $key2 => &$arOnePhoto){
	if($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"] == $arOnePhoto["ID"])continue;
?>
<?$imgbig2 = CFile::ResizeImageGet($arOnePhoto["ID"], array( "width" => 52, "height" => 56 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>

										<li data-value="<? echo $arOneOffer['ID'].'_'.$arOnePhoto['ID']; ?>" data-big="<?=$arOnePhoto['BIG']['src']; ?>">
											<span class="cnt"><img border="0" src="<?=$imgbig2['src'];?>" alt="<?=$arOnePhoto["ALT"];?>" title="<?=$arOnePhoto["TITLE"];?>" /></span>
										</li>
<?}
									unset($arOnePhoto);?>
								</ul>
							</div>
						</div>
					<?}?>
					<span class="thumbs_navigation hidden_block"></span>
				</div>
			<?}?>
		</div>
        
		<?/*mobile*/?>
		<?if(!$showCustomOffer){?>
			<div class="item_slider flex">
				<ul class="slides">
					<?if($arResult["MORE_PHOTO"]){
if($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"]){?>
<li id="photo-0" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>

<?$imgbig2 = CFile::ResizeImageGet($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"], array( "width" => 290, "height" => 290 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>

								<?if(!$isEmpty){?>
									<a href="<?=$imgbig2["src"]?>" rel="item_slider_flex" class="fancy" title="<?=$title;?>" >
										<img border="0" src="<?=$imgbig2["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
									</a>
								<?}else{?>
									<img border="0" src="<?=$imgbig2["src"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?}?>
							</li>
<?
}
						foreach($arResult["MORE_PHOTO"] as $i => $arImage){
if($arImage["ID"] == $arResult["PROPERTIES"]["ON_PHOT"]["VALUE"])continue;
?>
							<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
							<li id="photo-<?=$i?>" <?=(!$i ? 'class="current"' : 'style="display: none;"')?>>
								<?
								$alt=$arImage["ALT"];
								$title=$arImage["TITLE"];
								?>
<?$imgbig2 = CFile::ResizeImageGet($arImage["ID"], array( "width" => 290, "height" => 290 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>

								<?if(!$isEmpty){?>
									<a href="<?=$imgbig2["src"]?>" rel="item_slider_flex" class="fancy" title="<?=$title;?>" >
										<img border="0" src="<?=$imgbig2["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
									</a>
								<?}else{?>
									<img border="0" src="<?=$imgbig2["src"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?}?>
							</li>
						<?}
					}?>
				</ul>
			</div>
		<?}else{

			foreach ($arResult['OFFERS'] as $key => $arOneOffer){
				if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
					continue;

				$strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
				?>
				<div class="item_slider flex" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['SLIDER_CONT_OFM_ID'].$arOneOffer['ID']; ?>" style="display: <? echo $strVisible; ?>;">
					<ul class="slides" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['SLIDER_LIST_OFM_ID'].$arOneOffer['ID']; ?>">
<?if($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"]){?>
<li data-value="">
<?$imgbig2 = CFile::ResizeImageGet($arResult["PROPERTIES"]["ON_PHOT"]["VALUE"], array( "width" => 290, "height" => 290 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>

								<a href="<?=$imgbig2["src"]?>" rel="item_slider-<?=$arOneOffer['ID'];?>" class="fancy" title="<?=$title;?>">
									<img border="0" src="<?=$imgbig2["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>"/>
								</a>
							</li>
<?}
						foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto){
if($arOnePhoto["ID"] == $arResult["PROPERTIES"]["ON_PHOT"]["VALUE"])continue;
?>
<?$imgbig2 = CFile::ResizeImageGet($arOnePhoto["ID"], array( "width" => 290, "height" => 290 ), BX_RESIZE_IMAGE_PROPORTIONAL,true ,$arWaterMark);?>

							<li data-value="<? echo $arOneOffer['ID'].'_'.$arOnePhoto['ID']; ?>">
								<?
								$alt=$arOnePhoto["ALT"];
								$title=$arOnePhoto["TITLE"];
								?>
								<a href="<?=$imgbig2["src"]?>" rel="item_slider-<?=$arOneOffer['ID'];?>" class="fancy" title="<?=$title;?>">
									<img border="0" src="<?=$imgbig2["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>"/>
								</a>
							</li>
						<?}
						unset($arOnePhoto);?>
					</ul>
				</div>
			<?}?>
		<?}?>
		<script type="text/javascript">
			$(".thumbs").flexslider({
				animation: "slide",
				selector: ".slides_block > li",
				slideshow: false,
				animationSpeed: 600,
				directionNav: true,
				controlNav: false,
				pauseOnHover: true,
				itemWidth: 60,
				itemMargin: 10,
				animationLoop: true,
				controlsContainer: ".thumbs_navigation",
				//asNavFor: '.detail .galery #slider'
			});

			$(".item_slider.flex").flexslider({
				animation: "slide",
				slideshow: true,
				slideshowSpeed: 10000,
				animationSpeed: 600,
				directionNav: false,
				pauseOnHover: true,
				animationLoop: false,
			});

			$('.item_slider .thumbs li').first().addClass('current');

			$('.item_slider .thumbs').delegate('li:not(.current)', 'click', function(){
				$(this).addClass('current').siblings().removeClass('current').parents('.item_slider').find('.slides li').fadeOut(333);
				$(this).parents('.item_slider').find('.slides li').eq($(this).index()).addClass('current').stop().fadeIn(333);
			});
		</script>
	</div>
	<div class="right_info">
		<div class="info_item">
			<?if((!$arResult["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" && $arResult["CAN_BUY"]) || ($arParams["DISPLAY_COMPARE"] == "Y") || strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || $arResult["BRAND_ITEM"]){?>
				<div class="top_info">
					<div class="wrap_md">
						<?if($arResult["BRAND_ITEM"]){?>
							<div class="brand iblock">
								<?if(!$arResult["BRAND_ITEM"]["IMAGE"]):?>
									<b class="block_title"><?=GetMessage("BRAND");?>:</b>
									<a href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>"><?=$arResult["BRAND_ITEM"]["NAME"]?></a>
								<?else:?>
									<a class="brand_picture" href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>">
										<img border="0" src="<?=$arResult["BRAND_ITEM"]["IMAGE"]["src"]?>" alt="<?=$arResult["BRAND_ITEM"]["NAME"]?>" title="<?=$arResult["BRAND_ITEM"]["NAME"]?>" />
									</a>
								<?endif;?>
							</div>
						<?}?>
						<?if(((!$arResult["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N") || ($arParams["DISPLAY_COMPARE"] == "Y")) || (strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer))):?>
							<div>
								<?if((!$arResult["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N") || ($arParams["DISPLAY_COMPARE"] == "Y")):?>
									<div class="like_icons iblock">
										<?
										/*$frame = $this->createFrame()->begin('');
										$frame->setBrowserStorage(true);*/
										?>
											<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
												<?if(!$arResult["OFFERS"]):?>
													<div class="wish_item text" data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>">
														<span class="value pseudo" title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>" ><span><?=GetMessage('CT_BCE_CATALOG_IZB')?></span></span>
														<span class="value pseudo added" title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>"><span><?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?></span></span>
													</div>
												<?elseif($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1'):?>
													<?foreach($arResult["OFFERS"] as $arOffer):?>
														<?if($arOffer['CAN_BUY']):?>
															<div class="wish_item text o_<?=$arOffer["ID"];?>" style="display: none;" data-item="<?=$arOffer["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>">
																<span class="value pseudo <?=$arParams["TYPE_SKU"];?>" title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>"><span><?=GetMessage('CT_BCE_CATALOG_IZB')?></span></span>
																<span class="value pseudo added <?=$arParams["TYPE_SKU"];?>" title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>"><span><?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?></span></span>
															</div>
														<?endif;?>
													<?endforeach;?>
												<?endif;?>
											<?endif;?>
											<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
												<?if(!$arResult["OFFERS"]):?>
													<div data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-href="<?=$arResult["COMPARE_URL"]?>" class="compare_item text <?=($arResult["OFFERS"] ? $arParams["TYPE_SKU"] : "");?>" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['COMPARE_LINK']; ?>">
														<span class="value pseudo" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>"><span><?=GetMessage('CT_BCE_CATALOG_COMPARE')?></span></span>
														<span class="value pseudo added" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>"><span><?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?></span></span>
													</div>
												<?elseif($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1'):?>
													<?foreach($arResult["OFFERS"] as $arOffer):?>
														<div data-item="<?=$arOffer["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-href="<?=$arResult["COMPARE_URL"]?>" class="compare_item text <?=$arParams["TYPE_SKU"];?> o_<?=$arOffer["ID"];?>" style="display: none;">
															<span class="value pseudo" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>"><span><?=GetMessage('CT_BCE_CATALOG_COMPARE')?></span></span>
															<span class="value pseudo added" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>"><span><?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?></span></span>
														</div>
													<?endforeach;?>
												<?endif;?>
											<?endif;?>
										<?//$frame->end();?>
									</div>
								<?endif;?>
								<?if(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer)):?>
									<div class="article iblock" <?if($arResult['SHOW_OFFERS_PROPS']){?>id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_ARTICLE_DIV'] ?>" style="display: none;"<?}?>>
										<span class="block_title"><?=GetMessage("ARTICLE");?></span>
										<span class="value"><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span>
									</div>
								<?endif;?>
							</div>
						<?endif;?>
					</div>
				</div>
			<?}?>
			<div class="middle_info wrap_md">
				<div class="prices_block iblock">
					<div class="cost prices clearfix">
						<?
						/*$frame = $this->createFrame()->begin('');
						$frame->setBrowserStorage(true);*/
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
								$prefix='';
								if('N' == $arParams['TYPE_SKU'] || $arParams['DISPLAY_TYPE'] =='table'){
									$prefix=GetMessage("CATALOG_FROM");
								}
								if($arParams["SHOW_OLD_PRICE"]=="Y"){?>
									<div class="price price_on" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PRICE']; ?>">
										<?if(strlen($minPrice["PRINT_DISCOUNT_VALUE"])):?>
											<?=$prefix;?> <?=$minPrice["PRINT_DISCOUNT_VALUE"];?>
											<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
												/<?=$strMeasure?>
											<?}?>
										<?endif;?>
									</div>
									<div class="price discount" >
										<strike id="<?=$arItemIDs["ALL_ITEM_IDS"]['OLD_PRICE']?>" <?=(!$minPrice["DISCOUNT_DIFF"] ? 'style="display:none;"' : '')?>><?=$minPrice["PRINT_VALUE"];?></strike>
									</div>
									<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
										<div class="sale_block" <?=(!$minPrice["DISCOUNT_DIFF"] ? 'style="display:none;"' : '')?>>
											<?$percent=round(($minPrice["DISCOUNT_DIFF"]/$minPrice["VALUE"])*100, 2);?>
											<div class="value">-<?=$percent;?>%</div>
											<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <span><?=$minPrice["PRINT_DISCOUNT_DIFF"];?></span></div>
											<div class="clearfix"></div>
										</div>
									<?}?>
								<?}else{?>
									<div class="price price_on" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PRICE']; ?>">
										<?if(strlen($minPrice["PRINT_DISCOUNT_VALUE"])):?>
											<?=$prefix;?> <?=$minPrice['PRINT_DISCOUNT_VALUE'];?>
											<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
												/<?=$strMeasure?>
											<?}?>
										<?endif;?>
									</div>
								<?}?>
							<?}else{?>
								<?
								$arCountPricesCanAccess = 0;
								$min_price_id=0;
								foreach( $arResult["PRICES"] as $key => $arPrice ) { if($arPrice["CAN_ACCESS"]){$arCountPricesCanAccess++;} }?>
								<?foreach($arResult["PRICES"] as $key => $arPrice){?>
									<?if($arPrice["CAN_ACCESS"]){
										$percent=0;
										if($arPrice["MIN_PRICE"]=="Y"){
											$min_price_id=$arPrice["PRICE_ID"];
										}?>
										<?$price = CPrice::GetByID($arPrice["ID"]);?>
										<?if($arCountPricesCanAccess > 1):?>
											<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
										<?endif;?>
										<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"] && $arParams["SHOW_OLD_PRICE"]=="Y"){?>
											<div class="price" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PRICE']; ?>">
												<?if(strlen($arPrice["PRINT_DISCOUNT_VALUE"])):?>
													<?=$arPrice["PRINT_DISCOUNT_VALUE"];?>
													<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
														/<?=$strMeasure?>
													<?}?>
												<?endif;?>
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
													<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <span><?=$arPrice["PRINT_DISCOUNT_DIFF"];?></span></div>
													<div class="clearfix"></div>
												</div>
											<?}?>
										<?}else{?>
											<div class="price" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PRICE']; ?>">
												<?if(strlen($arPrice["PRINT_VALUE"])):?>
													<?=$arPrice["PRINT_VALUE"];?>
													<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
														/<?=$strMeasure?>
													<?}?>
												<?endif;?>
											</div>
										<?}?>
									<?}?>
								<?}?>
							<?}?>
						<?//$frame->end();?>
					</div>
					<?
					/*$frame = $this->createFrame()->begin('');
					$frame->setBrowserStorage(true);*/
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
										<script>
											$(document).ready(function(){
												if( $('.countdown').size() ){
													var active_to = $('.active_to_<?=$arOffer["ID"]?>').text(),
													date_to = new Date(active_to.replace(/(\d+)\.(\d+)\.(\d+)/, '$3/$2/$1'));
													$('.countdown_<?=$arOffer["ID"]?>').countdown({until: date_to, format: 'dHMS', padZeroes: true, layout: '{d<}<span class="days item">{dnn}<div class="text">{dl}</div></span>{d>} <span class="hours item">{hnn}<div class="text">{hl}</div></span> <span class="minutes item">{mnn}<div class="text">{ml}</div></span> <span class="sec item">{snn}<div class="text">{sl}</div></span>'}, $.countdown.regionalOptions['ru']);
												}
											})
										</script>
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
						<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arResult["ID"], $USER->GetUserGroupArray(), "N", $min_price_id, SITE_ID );
						$arDiscount=array();
						if($arDiscounts)
							$arDiscount=current($arDiscounts);
						if($arDiscount["ACTIVE_TO"]){?>
							<div class="view_sale_block">
								<div class="count_d_block">
									<span class="active_to_<?=$arResult["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
									<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
									<span class="countdown countdown_<?=$arResult["ID"]?> values"></span>
									<script>
										$(document).ready(function(){
											if( $('.countdown').size() ){
												var active_to = $('.active_to_<?=$arResult["ID"]?>').text(),
												date_to = new Date(active_to.replace(/(\d+)\.(\d+)\.(\d+)/, '$3/$2/$1'));
												$('.countdown_<?=$arResult["ID"]?>').countdown({until: date_to, format: 'dHMS', padZeroes: true, layout: '{d<}<span class="days item">{dnn}<div class="text">{dl}</div></span>{d>} <span class="hours item">{hnn}<div class="text">{hl}</div></span> <span class="minutes item">{mnn}<div class="text">{ml}</div></span> <span class="sec item">{snn}<div class="text">{sl}</div></span>'}, $.countdown.regionalOptions['ru']);
											}
										})
									</script>
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
					<?=$arQuantityData["HTML"];?>
					<?if($arParams["USE_RATING"] == "Y"):?>
						<div class="rating" style="display:none;">
							<?$APPLICATION->IncludeComponent(
							   "bitrix:iblock.vote",
							   "element_rating",
							   Array(
								  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
								  "IBLOCK_ID" => $arResult["IBLOCK_ID"],
								  "ELEMENT_ID" =>$arResult["ID"],
								  "MAX_VOTE" => 5,
								  "VOTE_NAMES" => array(),
								  "CACHE_TYPE" => $arParams["CACHE_TYPE"],
								  "CACHE_TIME" => $arParams["CACHE_TIME"],
								  "DISPLAY_AS_RATING" => 'vote_avg'
							   ),
							   $component, array("HIDE_ICONS" =>"Y")
							);?>
						</div>
					<?endif;?>
				</div>
				<div class="buy_block iblock">
					<?if($arResult["OFFERS"] && $showCustomOffer){?>
						<div class="sku_props">
						<span>В наличии</span>
							<?if (!empty($arResult['OFFERS_PROP'])){?>
								<div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
									<?foreach ($arSkuTemplate as $code => $strTemplate){
										if (!isset($arResult['OFFERS_PROP'][$code]))
											continue;
										echo str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate);
									}?>
								</div>
							<?}?>
							<?$arItemJSParams=CMShop::GetSKUJSParams($arResult, $arParams, $arResult, "Y");?>
							<script type="text/javascript">
								var <? echo $arItemIDs["strObName"]; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arItemJSParams, false, true); ?>);
							</script>
						</div>
					<?}?>
					<?if(!$arResult["OFFERS"]):?>
						<script>
							$(document).ready(function() {
								$('.catalog_detail .tabs_section .tabs_content .form.inline input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
							});
						</script>
						<div class="counter_wrapp">
							<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arResult["CAN_BUY"]):?>
								<div class="counter_block big_basket" data-offers="<?=($arResult["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arResult["ID"];?>" <?=(($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N") ? "style='display: none;'" : "");?>>
									<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
									<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
									<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
								</div>
							<?endif;?>
							<div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER" /*&& !$arResult["CAN_BUY"]*/) || !$arResult["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>" 
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
							?>
							<div class="buys_wrapp o_<?=$arOffer["ID"];?>">
								<div class="counter_wrapp">
									<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arOffer["CAN_BUY"]):?>
										<div class="counter_block big_basket" data-item="<?=$arOffer["ID"];?>" <?=($arParams["TYPE_SKU"]=="N" ? "style='display: none;'" : "");?>>
											<span class="minus">-</span>
											<input type="text" class="text" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
											<span class="plus" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
										</div>
									<?endif;?>
									<div class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER" /*&& !$arOffer["CAN_BUY"]*/) || !$arOffer["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>" 
                                    <?=($arAddToBasketData["ACTION"] != "ORDER" && $arOffer["CAN_BUY"])?"onclick=\"yaCounter37955450.reachGoal('cart'); ga('send', 'event', 'cart', 'submit'); return true;\"":"";?>>
										<!--noindex-->
											<?=$arAddToBasketData["HTML"]?>
										<!--/noindex-->
									</div>
								</div>
								<?if($arAddToBasketData["ACTION"] !== "NOTHING"):?>
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
						<span class="big_btn slide_offer button type_block"><i></i><span><?=GetMessage("BUY_BTN");?></span></span>
					<?endif;?>
				</div>
				<?if(strlen($arResult["PREVIEW_TEXT"]) != false && strlen($arResult["PREVIEW_TEXT"]) != "NULL"):?>
					<div class="preview_text"><?=$arResult["PREVIEW_TEXT"]?></div>
				<?endif;?>
			</div>
			<?if(is_array($arResult["STOCK"]) && $arResult["STOCK"]):?>
				<?foreach($arResult["STOCK"] as $key => $arStockItem):?>
					<div class="stock_board">
						<div class="title"><?=GetMessage("CATALOG_STOCK_TITLE")?></div>
						<div class="txt"><?=$arStockItem["PREVIEW_TEXT"]?></div>
						<a class="read_more" href="<?=$arStockItem["DETAIL_PAGE_URL"]?>"><?=GetMessage("CATALOG_STOCK_VIEW")?></a>
					</div>
				<?endforeach;?>
			<?endif;?>
			<div class="element_detail_text wrap_md">
				<p class="element_detail_text_strong">Закажите товар без предоплаты и уже через 3 дня забирайте его на почте</p>
				<div class="iblock sh">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')));?>
				</div>
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
			<ul>
				<?foreach($arResult["SET_ITEMS"] as $iii => $arSetItem):?>
					<li class="item">
						<div class="item_inner">
							<div class="image">
								<a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>">
									<?if($arSetItem["PREVIEW_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["PREVIEW_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<img border="0" src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?elseif($arSetItem["DETAIL_PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSetItem["DETAIL_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
										<img border="0" src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?else:?>
										<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
									<?endif;?>
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
															<strike><?=$arPrice["PRINT_VALUE"]?></strike>
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
	<?if($arResult['OFFERS']):?>
		<?if($arResult['OFFER_GROUP']):?>
			<?foreach($arResult['OFFERS'] as $arOffer):?>
				<?if(!$arOffer['OFFER_GROUP']) continue;?>
				<span id="<?=$arItemIDs['ALL_ITEM_IDS']['OFFER_GROUP'].$arOffer['ID']?>" style="display: none;">
					<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "main",
						array(
							"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
							"ELEMENT_ID" => $arOffer['ID'],
							"PRICE_CODE" => $arParams["PRICE_CODE"],
							"BASKET_URL" => $arParams["BASKET_URL"],
							"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
							"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
							"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
							"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
							"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
							"CURRENCY_ID" => $arParams["CURRENCY_ID"]
						), $component, array("HIDE_ICONS" => "Y")
					);?>
				</span>
			<?endforeach;?>
		<?endif;?>
	<?else:?>
		<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "main",
			array(
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"ELEMENT_ID" => $arResult["ID"],
				"PRICE_CODE" => $arParams["PRICE_CODE"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
				"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
				"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
				"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
				"CURRENCY_ID" => $arParams["CURRENCY_ID"]
			), $component, array("HIDE_ICONS" => "Y")
		);?>
	<?endif;?>
</div>
<div class="preim">
	<ul class="preim__list">
		<li class="preim__item">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/preim11.png" alt=""/>
			<h4><b>Доставим</b><br>без оплаты</h4>
			<p>Доставка товара в любой регион России с оплатой при получении</p>
		</li>
		<li class="preim__item">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/preim22.png" alt=""/>
			<h4><b>Подарок</b><br>к каждой покупке</h4>
			<p>При покупке на любую сумму вы получите гарантированный подарок</p>
		</li>
		<li class="preim__item">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/preim33.png" alt=""/>
			<h4><b>100%</b><br>гарантия возврата</h4>
			<p>Вам не подошел или не понравился купленный продукт? Мы вернем вам деньги</p>
		</li>
		<li class="preim__item">
			<img src="<?=SITE_TEMPLATE_PATH?>/images/preim44.png" alt=""/>
			<h4><b>Сомневаетесь</b><br>в выборе?</h4>
			<p>Получите консультацию специалиста бесплатно на нашем сайте</p>
		</li>
	</ul>
</div>
<div class="tabs_section">
	<ul class="tabs1 main_tabs1 tabs-head">
		<?
		$iTab = 0;
		$showProps = false;
		if($arResult["DISPLAY_PROPERTIES"]){
			foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
				if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))){
					if(!is_array($arProp["DISPLAY_VALUE"])){
						$arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);
					}
				}
				if(is_array($arProp["DISPLAY_VALUE"])){
					foreach($arProp["DISPLAY_VALUE"] as $value){
						if(strlen($value)){
							$showProps = true;
							break;
						}
					}
				}
			}
		}
		?>
		<?if($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N"):?>
			<li class="prices_tab<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("OFFER_PRICES")?></span>
			</li>
		<?endif;?>
		<?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"]) || count($arResult["SERVICES"]) || ((count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) && is_array($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"])) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("DESCRIPTION_TAB")?></span>
			</li>
		<?endif;?>
		<?if($arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB" && $showProps):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("PROPERTIES_TAB")?></span>
			</li>
		<?endif;?>
		<?if(strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO"]["VALUE"]) || strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"]) || $arResult["SECTION_FULL"]["UF_VIDEO"] || $arResult["SECTION_FULL"]["UF_VIDEO_YOUTUBE"]):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("VIDEO_TAB")?></span>
			</li>
		<?endif;?>
		<?if($arParams["USE_REVIEW"] == "Y"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>" id="product_reviews_tab">
				<span><?=GetMessage("REVIEW_TAB")?></span><span class="count empty"></span>
			</li>
		<?endif;?>
		<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
			<li class="product_ask_tab <?=(!($iTab++) ? ' current' : '')?>">
				<span>Вопрос технологу<?//=GetMessage('ASK_TAB')?></span>
			</li>
		<?endif;?>
		<?if($useStores && ($showCustomOffer || !$arResult["OFFERS"] )):?>
			<li class="stores_tab<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("STORES_TAB");?></span>
			</li>
		<?endif;?>
		<?/*if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<span><?=GetMessage("ADDITIONAL_TAB");?></span>
			</li>
		<?endif;*/?>
	</ul>
	<ul class="tabs_content tabs-body">
		<?$show_tabs = false;?>
		<?$iTab = 0;?>
		<?
		$showSkUName = ((in_array('NAME', $arParams['OFFERS_FIELD_CODE'])));
		$showSkUImages = false;
		if(((in_array('PREVIEW_PICTURE', $arParams['OFFERS_FIELD_CODE']) || in_array('DETAIL_PICTURE', $arParams['OFFERS_FIELD_CODE'])))){
			foreach ($arResult["OFFERS"] as $key => $arSKU){
				if($arSKU['PREVIEW_PICTURE'] || $arSKU['DETAIL_PICTURE']){
					$showSkUImages = true;
					break;
				}
			}
		}?>
		<?if($arResult["OFFERS"] && $arParams["TYPE_SKU"] !== "TYPE_1"):?>
			<li class="prices_tab<?=(!($iTab++) ? ' current' : '')?>">
				<table class="colored offers_table" cellspacing="0" cellpadding="0" width="100%" border="0">
					<thead>
						<tr>
							<?if($useStores):?>
								<td class="str"></td>
							<?endif;?>
							<?if($showSkUImages):?>
								<td class="property img" width="50"></td>
							<?endif;?>
							<?if($showSkUName):?>
								<td class="property"><?=GetMessage("CATALOG_NAME")?></td>
							<?endif;?>
							<?
							if($arResult["SKU_PROPERTIES"]){
								foreach ($arResult["SKU_PROPERTIES"] as $key => $arProp){?>
									<?if(!$arProp["IS_EMPTY"]):?>
										<td class="property">
											<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
										</td>
									<?endif;?>
								<?}
							}?>
							<td class="price_th"><?=GetMessage("CATALOG_PRICE")?></td>
							<?if($arQuantityData["RIGHTS"]["SHOW_QUANTITY"]):?>
								<td class="count_th"><?=GetMessage("AVAILABLE")?></td>
							<?endif;?>
							<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"  || $arParams["DISPLAY_COMPARE"] == "Y"):?>
								<td class="like_icons_th"></td>
							<?endif;?>
							<td colspan="3"></td>
						</tr>
					</thead>
					<tbody>
						<?$numProps = count($arResult["SKU_PROPERTIES"]);
						if($arResult["OFFERS"]){
							foreach ($arResult["OFFERS"] as $key => $arSKU){?>
								<?
								if($arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"]){
									$sMeasure = $arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"].".";
								}
								else{
									$sMeasure = GetMessage("MEASURE_DEFAULT").".";
								}
								$skutotalCount = CMShop::CheckTypeCount($arSKU["CATALOG_QUANTITY"]);
								$arskuQuantityData = CMShop::GetQuantityArray($skutotalCount, array('quantity-wrapp', 'quantity-indicators'));
								$arSKU["IBLOCK_ID"]=$arResult["IBLOCK_ID"];
								$arSKU["IS_OFFER"]="Y";
								$arskuAddToBasketData = CMShop::GetAddToBasketArray($arSKU, $skutotalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false);
								$arskuAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.$arOfferProps.'" data-item', $arskuAddToBasketData["HTML"]);
								?>
								<?$collspan = 1;?>
								<tr>
									<?if($useStores):?>
										<td class="opener">
											<?$collspan++;?>
											<span class="opener_icon"><i></i></span>
										</td>
									<?endif;?>
									<?if($showSkUImages):?>
										<?$collspan++;?>
										<td class="property">
											<?
											$srcImgPreview = $srcImgDetail = false;
											$imgPreviewID = ($arResult['OFFERS'][$key]['PREVIEW_PICTURE'] ? (is_array($arResult['OFFERS'][$key]['PREVIEW_PICTURE']) ? $arResult['OFFERS'][$key]['PREVIEW_PICTURE']['ID'] : $arResult['OFFERS'][$key]['PREVIEW_PICTURE']) : false);
											$imgDetailID = ($arResult['OFFERS'][$key]['DETAIL_PICTURE'] ? (is_array($arResult['OFFERS'][$key]['DETAIL_PICTURE']) ? $arResult['OFFERS'][$key]['DETAIL_PICTURE']['ID'] : $arResult['OFFERS'][$key]['DETAIL_PICTURE']) : false);
											if($imgPreviewID || $imgDetailID){
												$arImgPreview = CFile::ResizeImageGet($imgPreviewID ? $imgPreviewID : $imgDetailID, array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
												$srcImgPreview = $arImgPreview['src'];
											}
											if($imgDetailID){
												$srcImgDetail = CFile::GetPath($imgDetailID);
											}
											?>
											<?if($srcImgPreview || $srcImgDetail):?>
												<a href="<?=($srcImgDetail ? $srcImgDetail : $srcImgPreview)?>" class="fancy" rel="item_slider"><img src="<?=$srcImgPreview?>" alt="<?=$arSKU['NAME']?>" /></a>
											<?endif;?>
										</td>
									<?endif;?>
									<?if($showSkUName):?>
										<?$collspan++;?>
										<td class="property"><?=$arSKU['NAME']?></td>
									<?endif;?>
									<?foreach( $arResult["SKU_PROPERTIES"] as $arProp ){?>
										<?if(!$arProp["IS_EMPTY"]):?>
											<?$collspan++;?>
											<td class="property">
												<?if($arResult["TMP_OFFERS_PROP"][$arProp["CODE"]]){
													echo $arResult["TMP_OFFERS_PROP"][$arProp["CODE"]]["VALUES"][$arSKU["TREE"]["PROP_".$arProp["ID"]]]["NAME"];?>
												<?}else{
													if (is_array($arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"])){
														echo implode("/", $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"]);
													}else{
														echo $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"];
													}
												}?>
											</td>
										<?endif;?>
									<?}?>
									<td class="price">
										<div class="cost prices clearfix">
											<?
											$collspan++;
											$arCountPricesCanAccess = 0;
											foreach($arSKU["PRICES"] as $key => $arPrice){
												if($arPrice["CAN_ACCESS"]){
													$arCountPricesCanAccess++;
												}
											}?>
											<?foreach($arSKU["PRICES"] as $key => $arPrice){?>
												<?if($arPrice["CAN_ACCESS"]){?>
													<?if($arCountPricesCanAccess > 1):?>
														<?$price = CPrice::GetByID($arPrice["ID"]); ?>
														<div class="many_prices">
														<div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
													<?endif;?>
													<?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]){?>
														<div class="price">
															<?=$arPrice["PRINT_DISCOUNT_VALUE"];?>
															<?if(($arParams["SHOW_MEASURE"]=="Y")&&$arSKU["CATALOG_MEASURE_NAME"]):?><small>/<?=$arSKU["CATALOG_MEASURE_NAME"]?></small><?endif;?>
														</div>
														<div class="price discount">
															<strike><?=$arPrice["PRINT_VALUE"];?></strike>
														</div>
													<?}else{?>
														<div class="price">
															<?=$arPrice["PRINT_VALUE"];?>
															<?if(($arParams["SHOW_MEASURE"]=="Y")&&$arSKU["CATALOG_MEASURE_NAME"]):?><small>/<?=$arSKU["CATALOG_MEASURE_NAME"]?></small><?endif;?>
														</div>
													<?}?>
													<?if($arCountPricesCanAccess > 1){?>
														</div>
													<?}?>
												<?}?>
											<?}?>
										</div>
										<div class="adaptive text">
											<?if(strlen($arskuQuantityData["TEXT"])):?>
												<div class="count ablock">
													<?=$arskuQuantityData["HTML"]?>
												</div>
											<?endif;?>
											<!--noindex-->
												<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"  || $arParams["DISPLAY_COMPARE"] == "Y"):?>
													<div class="like_icons ablock">
														<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
															<?if($arSKU['CAN_BUY']):?>
																<div class="wish_item_button o_<?=$arSKU["ID"];?>">
																	<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item text to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
																	<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item text in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arSKU["IBLOCK_ID"]?>"><i></i></span>
																</div>
															<?endif;?>
														<?endif;?>
														<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
															<div class="compare_item_button o_<?=$arSKU["ID"];?>">
																<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to text <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>" ><i></i></span>
																<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added text <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>"><i></i></span>
															</div>
														<?endif;?>
													</div>
												<?endif;?>
												<div class="wrap_md">
													<?if($arskuAddToBasketData["ACTION"] == "ADD"):?>
														<?if($arskuAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && !count($arSKU["OFFERS"]) && $arskuAddToBasketData["ACTION"] == "ADD" && $arSKU["CAN_BUY"]):?>
															<div class="counter_block_wr iblock ablock">
																<div class="counter_block" data-item="<?=$arSKU["ID"];?>">
																	<span class="minus">-</span>
																	<input type="text" class="text" name="count_items" value="<?=($arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : "1");?>" />
																	<span class="plus">+</span>
																</div>
															</div>
														<?endif;?>
													<?endif;?>
													<div class="buy iblock ablock">
														<div class="counter_wrapp">
															<?=$arskuAddToBasketData["HTML"]?>
														</div>
													</div>
												</div>
												<?if($arskuAddToBasketData["ACTION"] == "ADD" && $arSKU["CAN_BUY"]):?>
													<div class="one_click_buy ablock">
														<span class="button small transparent one_click" data-item="<?=$arSKU["ID"]?>" data-offers="Y" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=($skutotalCount >= $arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : $skutotalCount)?>" data-props="<?=$arOfferProps?>" onclick="oneClickBuy('<?=$arSKU["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
															<span><?=GetMessage('ONE_CLICK_BUY')?></span>
														</span>
													</div>
												<?endif;?>
											<!--/noindex-->
										</div>
									</td>
									<?if(strlen($arskuQuantityData["TEXT"])):?>
										<?$collspan++;?>
										<td class="count">
											<?=$arskuQuantityData["HTML"]?>
										</td>
									<?endif;?>
									<!--noindex-->
										<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"  || $arParams["DISPLAY_COMPARE"] == "Y"):?>
											<td class="like_icons">
												<?$collspan++;?>
												<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
													<?if($arSKU['CAN_BUY']):?>
														<div class="wish_item_button o_<?=$arSKU["ID"];?>">
															<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item text to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
															<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item text in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arSKU["IBLOCK_ID"]?>"><i></i></span>
														</div>
													<?endif;?>
												<?endif;?>
												<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
													<div class="compare_item_button o_<?=$arSKU["ID"];?>">
														<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to text <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>" ><i></i></span>
														<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added text <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>"><i></i></span>
													</div>
												<?endif;?>
											</td>
										<?endif;?>
										<?if($arskuAddToBasketData["ACTION"] == "ADD"):?>
											<?if($arskuAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && !count($arSKU["OFFERS"]) && $arskuAddToBasketData["ACTION"] == "ADD" && $arSKU["CAN_BUY"]):?>
												<td class="counter_block_wr">
													<div class="counter_block" data-item="<?=$arSKU["ID"];?>">
														<?$collspan++;?>
														<span class="minus">-</span>
														<input type="text" class="text" name="count_items" value="<?=($arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : "1");?>" />
														<span class="plus">+</span>
													</div>
												</td>
											<?endif;?>
										<?endif;?>
										<td class="buy" <?=($arskuAddToBasketData["ACTION"] !== "ADD" || !$arSKU["CAN_BUY"] ? 'colspan="3"' : "")?>>
											<?if($arskuAddToBasketData["ACTION"] !== "ADD"  || !$arSKU["CAN_BUY"]):?>
												<?$collspan += 3;?>
											<?else:?>
												<?$collspan++;?>
											<?endif;?>
											<div class="counter_wrapp">
												<?=$arskuAddToBasketData["HTML"]?>
											</div>
										</td>
										<?if($arskuAddToBasketData["ACTION"] == "ADD" && $arSKU["CAN_BUY"]):?>
											<td class="one_click_buy">
												<?$collspan++;?>
												<span class="button small transparent one_click" data-item="<?=$arSKU["ID"]?>" data-offers="Y" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=($skutotalCount >= $arParams["DEFAULT_COUNT"] ? $arParams["DEFAULT_COUNT"] : $skutotalCount)?>" data-props="<?=$arOfferProps?>" onclick="oneClickBuy('<?=$arSKU["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
													<span><?=GetMessage('ONE_CLICK_BUY')?></span>
												</span>
											</td>
										<?endif;?>
									<!--/noindex-->
								</tr>
								<?if($useStores):?>
									<?$collspan--;?>
									<tr class="offer_stores"><td colspan="<?=$collspan?>">
										<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
												"PER_PAGE" => "10",
												"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
												"SCHEDULE" => $arParams["SCHEDULE"],
												"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
												"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
												"ELEMENT_ID" => $arSKU["ID"],
												"STORE_PATH"  =>  $arParams["STORE_PATH"],
												"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
												"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
												"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
												"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
												"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
												"USER_FIELDS" => $arParams['USER_FIELDS'],
												"FIELDS" => $arParams['FIELDS'],
												"STORES" => $arParams['STORES'],
											),
											$component
										);?>
									</tr>
								<?endif;?>
							<?}
						}?>
					</tbody>
				</table>
			</li>
		<?endif;?>
		<?if($arResult["DETAIL_TEXT"] || count($arResult["STOCK"]) || count($arResult["SERVICES"]) || ((count($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]) && is_array($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"])) || count($arResult["SECTION_FULL"]["UF_FILES"])) || ($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<?if(strlen($arResult["DETAIL_TEXT"]) != false && strlen($arResult["DETAIL_TEXT"]) != "NULL"):?>
					<div class="detail_text"><?=$arResult["DETAIL_TEXT"]?></div>
				<?endif;?>
				<?if($arResult["SERVICES"] && $showProps){?>
					<div class="wrap_md descr_div">
				<?}?>
				<?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB"):?>
					<?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
						<div class="props_block">
							<?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
								<?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
									<?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
									<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
										<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
											<div class="char">
												<div class="char_name">
													<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
												</div>
												<div class="char_value">
													<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
														<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
													<?else:?>
														<?=$arProp["DISPLAY_VALUE"];?>
													<?endif;?>
												</div>
											</div>
										<?endif;?>
									<?endif;?>
								<?endif;?>
							<?endforeach;?>
						</div>
					<?else:?>
						<div class="iblock char_block <?=(!$arResult["SERVICES"] ? 'wide' : '')?>">
							<h4><?=GetMessage("PROPERTIES_TAB");?></h4>
							<table class="props_list">
								<?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
									<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
										<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
											<tr>
												<td class="char_name">
													<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
												</td>
												<td class="char_value">
													<span>
														<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
															<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
														<?else:?>
															<?=$arProp["DISPLAY_VALUE"];?>
														<?endif;?>
													</span>
												</td>
											</tr>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</table>
						</div>
					<?endif;?>
				<?endif;?>
				<?if($arResult["SERVICES"]):?>
					<div class="iblock serv <?=($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE" ? "block_view" : "")?>">
						<h4><?=GetMessage("SERVICES_TITLE")?></h4>
						<div class="services_block">
							<?foreach($arResult["SERVICES"] as $arService):?>
								<span class="item">
									<a href="<?=$arService["DETAIL_PAGE_URL"]?>">
										<i class="arrow"><b></b></i>
										<span class="link"><?=$arService["NAME"]?></span>
									</a>
								</span>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<?if($arResult["SERVICES"] && $showProps){?>
					</div>
				<?}?>
				<?
				$arFiles = array();
				if($arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"]){
					$arFiles = $arResult["PROPERTIES"]["INSTRUCTIONS"]["VALUE"];
				}
				else{
					$arFiles = $arResult["SECTION_FULL"]["UF_FILES"];
				}
				if(is_array($arFiles)){
					foreach($arFiles as $key => $value){
						if(!intval($value)){
							unset($arFiles[$key]);
						}
					}
				}
				?>
				<?if($arFiles):?>
					<div class="files_block">
						<h4><?=GetMessage("DOCUMENTS_TITLE")?></h4>
						<div class="wrap_md">
							<div class="wrapp_docs iblock">
							<?
							$i=1;
							foreach($arFiles as $arItem):?>
								<?$arFile=CMShop::GetFileInfo($arItem);?>
								<div class="file_type clearfix <?=$arFile["TYPE"];?>">
									<i class="icon"></i>
									<div class="description">
										<a target="_blank" href="<?=$arFile["SRC"];?>"><?=$arFile["DESCRIPTION"];?></a>
										<span class="size"><?=GetMessage('CT_NAME_SIZE')?>:
											<?=$arFile["FILE_SIZE_FORMAT"];?>
										</span>
									</div>
								</div>
								<?if($i%3==0){?>
									</div><div class="wrapp_docs iblock">
								<?}?>
								<?$i++;?>
							<?endforeach;?>
							</div>
						</div>
					</div>
				<?endif;?>
			</li>
		<?endif;?>

		<?if($showProps && $arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
					<div class="props_block">
						<?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
							<?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
								<?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
								<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
									<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
										<div class="char">
											<div class="char_name">
												<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
											</div>
											<div class="char_value">
												<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
													<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
												<?else:?>
													<?=$arProp["DISPLAY_VALUE"];?>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
								<?endif;?>
							<?endif;?>
						<?endforeach;?>
					</div>
				<?else:?>
					<table class="props_list">
						<?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
							<?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE"))):?>
								<?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
									<tr>
										<td class="char_name">
											<span <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>class="whint"<?}?>><?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?><?=$arProp["NAME"]?></span>
										</td>
										<td class="char_value">
											<span>
												<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
													<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
												<?else:?>
													<?=$arProp["DISPLAY_VALUE"];?>
												<?endif;?>
											</span>
										</td>
									</tr>
								<?endif;?>
							<?endif;?>
						<?endforeach;?>
					</table>
				<?endif;?>
			</li>
		<?endif;?>

		<?if(strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO"]["VALUE"]) || strlen($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"]) || $arResult["SECTION_FULL"]["UF_VIDEO"] || $arResult["SECTION_FULL"]["UF_VIDEO_YOUTUBE"]):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<div class="video_block">
					<?if (!empty($arResult["DISPLAY_PROPERTIES"]["VIDEO"]["VALUE"])):?>
						<?=$arResult["DISPLAY_PROPERTIES"]["VIDEO"]["~VALUE"];?>
					<?elseif (!empty($arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["VALUE"])):?>
						<?=$arResult["DISPLAY_PROPERTIES"]["VIDEO_YOUTUBE"]["~VALUE"];?>
					<?elseif (!empty($arResult["SECTION_FULL"]['UF_VIDEO'])):?>
						<?=$arResult["SECTION_FULL"]['~UF_VIDEO'];?>
					<?elseif (!empty($arResult["SECTION_FULL"]['UF_VIDEO_YOUTUBE'])):?>
						<?=$arResult["SECTION_FULL"]['~UF_VIDEO_YOUTUBE'];?>
					<?endif;?>
				</div>
			</li>
		<?endif;?>

		<?if($arParams["USE_REVIEW"] == "Y"):?>
			<li class="<?=(!($iTab++) ? '' : '')?>"></li>
		<?endif;?>

		<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<div class="wrap_md forms">
					<div class="iblock text_block">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
					</div>
					<div class="iblock form_block">
						<div id="ask_block"></div>
					</div>
				</div>
			</li>
		<?endif;?>

		<?if($useStores && ($showCustomOffer || !$arResult["OFFERS"] )):?>
			<li class="stores_tab<?=(!($iTab++) ? ' current' : '')?>">
				<?if($arResult["OFFERS"]){
					if($showCustomOffer){?>
						<?foreach($arResult["OFFERS"] as $arOffer){?>
							<div class="sku_stores_<?=$arOffer["ID"]?>" style="display: none;">
								<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
										"PER_PAGE" => "10",
										"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
										"SCHEDULE" => $arParams["SCHEDULE"],
										"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
										"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
										"ELEMENT_ID" => $arOffer["ID"],
										"STORE_PATH"  =>  $arParams["STORE_PATH"],
										"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
										"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
										"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
										"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
										"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
										"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
										"USER_FIELDS" => $arParams['USER_FIELDS'],
										"FIELDS" => $arParams['FIELDS'],
										"STORES" => $arParams['STORES'],
									),
									$component
								);?>
							</div>
						<?}?>
					<?}?>
				<?}else{?>
					<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
							"PER_PAGE" => "10",
							"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
							"SCHEDULE" => $arParams["SCHEDULE"],
							"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
							"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
							"ELEMENT_ID" => $arResult["ID"],
							"STORE_PATH"  =>  $arParams["STORE_PATH"],
							"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
							"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
							"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
							"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
							"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
							"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
							"USER_FIELDS" => $arParams['USER_FIELDS'],
							"FIELDS" => $arParams['FIELDS'],
							"STORES" => $arParams['STORES'],
						),
						$component
					);?>
				<?}?>
			</li>
		<?endif;?>

<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
			<li class="<?=(!($iTab++) ? ' current' : '')?>">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ADDITIONAL_DESCRIPTION')));?>
			</li>
		<?endif;?>
	</ul>
</div>

<script type="text/javascript">
	$(document).on('click', ".item-stock .store_view", function(){
		scroll_block($('.tabs_section'));
	});

	$(".opener").click(function(){
		$(this).find(".opener_icon").toggleClass("opened");
		var showBlock = $(this).parents("tr").toggleClass("nb").next(".offer_stores").find(".stores_block_wrap");
		showBlock.slideToggle(200);
	});

	$(".tabs_section .tabs-head li").live("click", function(){
		if(!$(this).is(".current")){
			$(".tabs_section .tabs-head li").removeClass("current");
			$(this).addClass("current");
			$(".tabs_section ul.tabs_content li").removeClass("current");
			if($(this).attr("id") == "product_reviews_tab"){
				$(".shadow.common").hide(); $("#reviews_content").show();
			}
			else{
				$(".shadow.common").show();
				$("#reviews_content").hide();
				$(".tabs_section ul.tabs_content > li:eq("+$(this).index()+")").addClass("current");
			}
		}
	});

	$(".hint .icon").click(function(e){
		var tooltipWrapp = $(this).parents(".hint");
		tooltipWrapp.click(function(e){e.stopPropagation();})
		if(tooltipWrapp.is(".active")){
			tooltipWrapp.removeClass("active").find(".tooltip").slideUp(200);
		}
		else{
			tooltipWrapp.addClass("active").find(".tooltip").slideDown(200);
			tooltipWrapp.find(".tooltip_close").click(function(e){
				e.stopPropagation(); tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);
			});
			$(document).click(function(){
				tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);
			});
		}
	});
	$('.set_block').ready(function(){
		$('.set_block ').equalize({children: '.item:not(".r") .cost', reset: true});
		$('.set_block').equalize({children: '.item .item-title', reset: true});
		$('.set_block').equalize({children: '.item .item_info', reset: false});
		//$('.set_wrapp').equalize({children: 'li', reset: true});
	});
	BX.message({
		QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.mshop", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
		QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.mshop", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
		ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
		ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
		ONE_CLICK_BUY: '<? echo GetMessage("ONE_CLICK_BUY"); ?>',
		SITE_ID: '<? echo SITE_ID; ?>'
	})
</script> 