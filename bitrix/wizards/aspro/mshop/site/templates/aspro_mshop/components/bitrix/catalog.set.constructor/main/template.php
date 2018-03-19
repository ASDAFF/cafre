<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$intElementID = intval($arParams["ELEMENT_ID"]);
CJSCore::Init(array("popup"));
$countDefSetItems = count($arResult["SET_ITEMS"]["DEFAULT"]);
?>
<div class="bx_item_set_hor_container_big set_block">
	<button class="button transparent small popup_open" onclick="OpenCatalogSetPopup('<?=$intElementID?>');"><span><?=GetMessage("CATALOG_SET_CONSTRUCT")?></span></button>
	<div class="title"><?=GetMessage("CATALOG_SET_BUY_SET")?></div>
	<ul class="bx_item_set_hor">
		<li class="bx_item_set_hor_item plus" data-price="<?=$arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"]?>" data-old-price="<?=$arResult["ELEMENT"]["PRICE_VALUE"]?>" data-discount-diff-price="<?=$arResult["ELEMENT"]["PRICE_DISCOUNT_DIFFERENCE_VALUE"]?>">
			<div class="item_wrapp main_item item">
				<div class="item_inner">
					<div class="bx_item_set_img_container">
						<a href="<?=$arResult["ELEMENT"]["DETAIL_PAGE_URL"]?>">
							<?if ($arResult["ELEMENT"]["PREVIEW_PICTURE"]):?>
								 <?$img = CFile::ResizeImageGet($arResult["ELEMENT"]["PREVIEW_PICTURE"], array( "width" => 140, "height" => 140 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
								<img border="0" src="<?=$img["src"]?>" alt="<?=$arResult["ELEMENT"]["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
							<?elseif($arResult["ELEMENT"]["DETAIL_PICTURE"]["src"]):?>
								<img border="0" src="'<?=$arResult["ELEMENT"]["DETAIL_PICTURE"]["src"]?>" alt="<?=$arResult["ELEMENT"]["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
							<?else:?>
								<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arSetItem["NAME"];?>" title="<?=$arResult["ELEMENT"]["NAME"];?>" />
							<?endif;?>
						</a>
					</div>
					<div class="item_info">
						<div class="item-title">
							<a class="bx_item_set_linkitem" href="<?=$arResult["ELEMENT"]["DETAIL_PAGE_URL"]?>"><span><?=$arResult["ELEMENT"]["NAME"]?></span></a>
						</div>
						<div class="cost prices clearfix">
							<div class="price bx_item_set_price"><?=$arResult["ELEMENT"]["PRICE_PRINT_DISCOUNT_VALUE"]?></div>
							<?if (!($arResult["ELEMENT"]["PRICE_VALUE"] == $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"]) && $arParams["SHOW_OLD_PRICE"]=="Y"):?>
								<div class="price discount bx_item_set_price old"><strike><?=$arResult["ELEMENT"]["PRICE_PRINT_VALUE"]?></strike></div>
							<?endif?>
						</div>
					</div>
				</div>
			</div>
			<div class="item_plus separator"></div>
		</li>

		<?foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem):
			$strMeasure='';

			if($arItem["OFFERS"]){
				$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
			}else{
				if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
					$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
					$strMeasure=$arMeasure["SYMBOL_RUS"];
				}
			}?>
			<li class="bx_item_set_hor_item <?if ($key<$countDefSetItems-1) echo "plus"; else echo "equally"?> bx_default_set_items"
				data-price="<?=(($arItem["PRICE_CONVERT_DISCOUNT_VALUE"]) ? $arItem["PRICE_CONVERT_DISCOUNT_VALUE"] : $arItem["PRICE_DISCOUNT_VALUE"])?>"
				data-old-price="<?=(($arItem["PRICE_CONVERT_VALUE"]) ? $arItem["PRICE_CONVERT_VALUE"] : $arItem["PRICE_VALUE"])?>"
				data-discount-diff-price="<?=(($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"]) ? $arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"] : $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"])?>">
				<div class="item_wrapp item">
					<div class="item_inner">
						<div class="bx_item_set_img_container">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
								<?if ($arItem["PREVIEW_PICTURE"]):?>
									<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array( "width" => 140, "height" => 140 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
									<img border="0" src="<?=$img["src"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
								<?elseif($arItem["DETAIL_PICTURE"]["src"]):?>
									<img border="0" src="'<?=$arItem["DETAIL_PICTURE"]["src"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
								<?else:?>
									<img border="0" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arSetItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
								<?endif;?>
							</a>
						</div>
						<div class="item_info">
							<div class="item-title">
								<a class="bx_item_set_linkitem" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
							</div>
							<div class="cost prices clearfix">
								<div class="price bx_item_set_price"><?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>
								<?if (($arParams["SHOW_MEASURE"]=="Y") && $strMeasure){?>
									/<?=$strMeasure?>
								<?}?>
								</div>
								<?if ($arItem["PRICE_VALUE"] != $arItem["PRICE_DISCOUNT_VALUE"] && $arParams["SHOW_OLD_PRICE"]=="Y"):?>
									<div class="price discount bx_item_set_price old"><strike><?=$arItem["PRICE_PRINT_VALUE"]?></strike></div>
								<?endif?>
							</div>
						</div>
						<div class="bx_item_set_del" title="<?=GetMessage("CATALOG_SET_DELETE")?>" onclick="catalogSetDefaultObj_<? echo $intElementID; ?>.DeleteItem(this.parentNode.parentNode, '<?=$arItem["ID"]?>')"></div>
					</div>
				</div>
				<?if ($key<$countDefSetItems):?><div class="item_plus separator"></div><?endif;?>
			</li>
		<?endforeach?>
		<li class="bx_item_set_hor_item result_block">
			<div class="item r">
				<div class="item_inner">
					<div class="total_wrapp result">
						<div class="total_price bx_item_set_result_block">
							<span class="price_block cost prices">
								<div class="price bx_item_set_current_price"> <?=$arResult["SET_ITEMS"]["PRICE"]?></div>
								<?if($arResult["SET_ITEMS"]["OLD_PRICE"] && $arParams["SHOW_OLD_PRICE"]=="Y"):?>
									<div class="price discount">
										<strike class="bx_item_set_old_price"><?=$arResult["SET_ITEMS"]["OLD_PRICE"]?></strike>
									</div>
									<?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y" && $arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]){?>
										<div class="sale_block">
											<?
											$tmp_price=str_replace(" ", "", $arResult["SET_ITEMS"]["PRICE"]);
											$tmp_price_diff=str_replace(" ", "", $arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]);
											$percent=round(((int)$tmp_price_diff/(int)$tmp_price)*100, 2);?>
											<?if($percent && $percent<100){?>
												<div class="value">-<?=$percent;?>%</div>
											<?}?>
											<div class="text"><?=GetMessage("CATALOG_ECONOMY");?> <span><?=$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"];?></span></div>
											<div class="clearfix"></div>
										</div>
									<?}?>
								<?endif?>
							</span>
						</div>
						<div class="total_buttons">
							<div class="buttons_wrapp clearfix">
								<button  onclick="catalogSetDefaultObj_<?=$intElementID;?>.Add2Basket();" class="button big_btn"><span><?=GetMessage("CATALOG_SET_BUY")?></span></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</li>
	</ul>
</div>

<?
$popupParams["AJAX_PATH"] = $this->GetFolder()."/ajax.php";
$popupParams["SHOW_OLD_PRICE"] = $arParams["SHOW_OLD_PRICE"];
$popupParams["SHOW_MEASURE"] = $arParams["SHOW_MEASURE"];
$popupParams["SHOW_DISCOUNT_PERCENT"] = $arParams["SHOW_DISCOUNT_PERCENT"];
$popupParams["SITE_ID"] = SITE_ID;
$popupParams["CURRENT_TEMPLATE_PATH"] = $this->GetFolder();
$popupParams["MESS"] = array(
	"CATALOG_SET_POPUP_TITLE" => GetMessage("CATALOG_SET_POPUP_TITLE"),
	"CATALOG_SET_POPUP_DESC" => GetMessage("CATALOG_SET_POPUP_DESC"),
	"CATALOG_SET_BUY" => GetMessage("CATALOG_SET_BUY"),
	"CATALOG_SET_SUM" => GetMessage("CATALOG_SET_SUM"),
	"CATALOG_SET_DISCOUNT" => GetMessage("CATALOG_SET_DISCOUNT"),
	"CATALOG_SET_WITHOUT_DISCOUNT" => GetMessage("CATALOG_SET_WITHOUT_DISCOUNT"),
	"CATALOG_SET_PRODUCTS" => GetMessage("CATALOG_SET_PRODUCTS"),
	"CATALOG_SET_ADD" => GetMessage("CATALOG_SET_ADD"),
	"CATALOG_SET_DELETE" => GetMessage("CATALOG_SET_DELETE"),
	"CATALOG_SET_MAIN_PRODUCT_BLOCK_TITLE" => GetMessage("CATALOG_SET_MAIN_PRODUCT_BLOCK_TITLE"),
	"CATALOG_SET_PRODUCTS_BLOCK_TITLE" => GetMessage("CATALOG_SET_PRODUCTS_BLOCK_TITLE"),
	"CATALOG_ECONOMY" => GetMessage("CATALOG_ECONOMY"),
);
$popupParams["ELEMENT"] = $arResult["ELEMENT"];
$popupParams["SET_ITEMS"] = $arResult["SET_ITEMS"];
$popupParams["DEFAULT_SET_IDS"] = $arResult["DEFAULT_SET_IDS"];
$popupParams["ITEMS_RATIO"] = $arResult["ITEMS_RATIO"];
?>

<script>


	$('.bx_item_set_hor_container_big').ready(function()
	{
		//$('.bx_item_set_hor_container_big').equalize({children: '.bx_item_set_hor_item:not(".result_block") .cost', reset: true});
		/*$('.bx_item_set_hor_container_big').equalize({children: '.bx_item_set_hor_item .item-title', reset: true});
		$('.bx_item_set_hor_container_big').equalize({children: 'bx_item_set_hor_item', reset: true});*/
		$('.bx_item_set_hor_container_big .bx_item_set_hor_item .item_wrapp').hover(
			function() { $(this).find(".bx_item_set_del").fadeIn(100); },
			function() { $(this).find(".bx_item_set_del").stop().fadeOut(333); }
		);

	});

	BX.message({
		setItemAdded2Basket: '<?=GetMessageJS("CATALOG_SET_ADDED2BASKET")?>',
		setButtonBuyName: '<?=GetMessageJS("CATALOG_SET_BUTTON_BUY")?>',
		setButtonBuyUrl: '<?=$arParams["BASKET_URL"]?>',
		setIblockId: '<?=$arParams["IBLOCK_ID"]?>',
		setOffersCartProps: <?=CUtil::PhpToJSObject($arParams["OFFERS_CART_PROPERTIES"])?>
	});

	BX.ready(function(){
		catalogSetDefaultObj_<?=$intElementID; ?> = new catalogSetConstructDefault(
			<?=CUtil::PhpToJSObject($arResult["DEFAULT_SET_IDS"])?>,
			'<? echo $this->GetFolder(); ?>/ajax.php',
			'<?=$arResult["ELEMENT"]["PRICE_CURRENCY"]?>',
			'<?=SITE_ID?>',
			'<?=$intElementID?>',
			'<?=($arResult["ELEMENT"]["DETAIL_PICTURE"]["src"] ? $arResult["ELEMENT"]["DETAIL_PICTURE"]["src"] : $this->GetFolder().'/images/no_foto.png')?>',
			<?=CUtil::PhpToJSObject($arResult["BASKET_QUANTITY"])?>
		);
	});

	if (!window.arSetParams)
	{
		window.arSetParams = [{'<?=$intElementID?>' : <?echo CUtil::PhpToJSObject($popupParams)?>}];
	}
	else
	{
		window.arSetParams.push({'<?=$intElementID?>' : <?echo CUtil::PhpToJSObject($popupParams)?>});
	}

	function OpenCatalogSetPopup(element_id)
	{
		if (window.arSetParams)
		{
			for(var obj in window.arSetParams)
			{
				for(var obj2 in window.arSetParams[obj])
				{
					if (obj2 == element_id)
						var curSetParams = window.arSetParams[obj][obj2]
				}
			}
		}

		BX.CatalogSetConstructor =
		{
			bInit: false,
			popup: null,
			arParams: {}
		}
		BX.CatalogSetConstructor.popup = BX.PopupWindowManager.create("CatalogSetConstructor_"+element_id, null, {
			autoHide: false,
			offsetLeft: 0,
			offsetTop: 0,
			overlay : true,
			draggable: {restrict:true},
			closeByEsc: false,
			closeIcon: { right : "12px", top : "12px"},
			titleBar: {content: BX.create("span", {html: "<h2><?=GetMessage("CATALOG_SET_POPUP_TITLE_BAR")?></h2>"})},
			content: '<div style="width:250px;height:250px; text-align: center;"><span style="position:absolute;left:50%; top:50%"><img src="<?=$this->GetFolder()?>/images/wait.gif"/></span></div>',
			events: {
				onAfterPopupShow: function()
				{
					BX.ajax.post(
						'<? echo $this->GetFolder(); ?>/popup.php',
						{
							lang: BX.message('LANGUAGE_ID'),
							site_id: BX.message('SITE_ID') || '',
							arParams:curSetParams
						},
						BX.delegate(function(result)
						{
							this.setContent(result);
							BX("CatalogSetConstructor_"+element_id).style.left = (window.innerWidth - BX("CatalogSetConstructor_"+element_id).offsetWidth)/2 +"px";
							var popupTop = $(window).scrollTop() + (window.innerHeight - BX("CatalogSetConstructor_"+element_id).offsetHeight)/2;
							/*console.log(document.body.scrollTop);
							console.log(window.innerHeight);
							console.log(BX("CatalogSetConstructor_"+element_id).offsetHeight);
							console.log(popupTop);*/
							BX("CatalogSetConstructor_"+element_id).style.top = popupTop > 0 ? popupTop+"px" : 0;
						},
						this)
					);
					$(window).resize();
				}
			}
		});

		BX.CatalogSetConstructor.popup.show();
	}
</script>