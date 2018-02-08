<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<div class="brands_slider_wrapp">
	<ul class="brands_slider">
		<?foreach($arResult["ITEMS"] as $arItem){?>
			<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<?if( is_array($arItem["PREVIEW_PICTURE"]) ){?>
				<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<?if($arItem["PROPERTIES"]["URL_BRAND"]["VALUE"]):?>
					<a href="<?=$arItem["PROPERTIES"]["URL_BRAND"]["VALUE"]?>">
						<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
					</a>
				<?else:?>
				<img border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
				<?endif;?>
				</li>
			<?}?>
		<?}?>
	</ul>
	<div class="brands_slider_navigation absolute"></div>
</div>
<script>
	$(".brands_slider_wrapp").flexslider({
		animation: "slide",
		selector: ".brands_slider > li",
		slideshow: false,
		animationSpeed: 600,
		directionNav: true,
		controlNav: false,
		pauseOnHover: true,
		itemWidth: 179,                
		animationLoop: true,
		controlsContainer: ".brands_slider_navigation",
	});
</script>