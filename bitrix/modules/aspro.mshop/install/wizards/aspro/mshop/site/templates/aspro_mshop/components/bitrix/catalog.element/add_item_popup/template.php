<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="popup-intro"><div class="pop-up-title"><?=GetMessage("ITEM_POPUP_TITLE")?></div></div>
<a class="jqmClose close"><i></i></a>

<?if (!empty($arResult)):?>
	<div class="form-wr catalog_item"><tr>
		<table width="100%">
			<?if (!empty($arResult["PREVIEW_PICTURE"])||!empty($arResult["DETAIL_PICTURE"])):?>
				<td class="image">
					<?if( !empty($arResult["PREVIEW_PICTURE"]) ):?>
						<img border="0" src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arResult["PREVIEW_PICTURE"]["ALT"]?$arResult["PREVIEW_PICTURE"]["ALT"]:$arResult["NAME"]);?>" title="<?=($arResult["PREVIEW_PICTURE"]["TITLE"]?$arResult["PREVIEW_PICTURE"]["TITLE"]:$arResult["NAME"]);?>" />
					<?elseif( !empty($arResult["DETAIL_PICTURE"])):?>
						<?$img = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array( "width" => 170, "height" => 170 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
						<img border="0" src="<?=$img["src"]?>" alt="<?=($arResult["PREVIEW_PICTURE"]["ALT"]?$arResult["PREVIEW_PICTURE"]["ALT"]:$arResult["NAME"]);?>" title="<?=($arResult["PREVIEW_PICTURE"]["TITLE"]?$arResult["PREVIEW_PICTURE"]["TITLE"]:$arResult["NAME"]);?>" />		
					<?else:?>
						<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arResult["PREVIEW_PICTURE"]["ALT"]?$arResult["PREVIEW_PICTURE"]["ALT"]:$arResult["NAME"]);?>" title="<?=($arResult["PREVIEW_PICTURE"]["TITLE"]?$arResult["PREVIEW_PICTURE"]["TITLE"]:$arResult["NAME"]);?>" />
					<?endif;?>
				</td>
			<?endif;?>
			<td class="product_description">
				<a class="item_name" href="<?=$arResult["DETAIL_PAGE_URL"];?>"><span><?=$arResult["NAME"]?></span></a><br />
				<?if( $arResult["CAN_BUY"] && $arResult["CATALOG_QUANTITY"] ){?>
					<div class="price_block">
						<?foreach( $arResult["PRICES"] as $key => $arPrice ){?>
							<?if( $arPrice["CAN_ACCESS"] ){?>
								<?if( $arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"] ){?>
									<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></div>
									<div class="price discount"><?=$arPrice["PRINT_VALUE"]?></div>
								<?}else{?><div class="price"><?=$arPrice["PRINT_VALUE"]?></div><?}?>
							<?}?>
						<?}?>					
					</div>
				<?}elseif( !empty( $arResult["OFFERS"] ) ){?>
					<div class="price_block">
						<?foreach( $arResult["PRICES"] as $key => $arPrice ){?>
							<?if( $arPrice["CAN_ACCESS"] ){?>
								<?if( $arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"] ){?>
									<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></div>
									<div class="price discount"><?=$arPrice["PRINT_VALUE"]?></div>
								<?}else{?><div class="price"><?=$arPrice["PRINT_VALUE"]?></div><?}?>
							<?}?>
						<?}?>
					</div>
				<?}?>
			</td></tr>
		</table>
		
		<div class="buttons_wrapp">
			<a href="<?=SITE_DIR?>basket/" class="button30"><span><?=GetMessage("GO_TO_ORDER")?></span></a>
			<a class="proceed"><span class="pseudo"><?=GetMessage("BACK_TO_CATALOG")?></span></a>
		</div>
	</div>
	<script>
		$('.add_item_point').closest('.popup').jqmAddClose('.jqmClose');
		$('.add_item_point').closest('.popup').jqmAddClose('.proceed');
	</script>
<?endif;?>