<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<div class="wrap_md submenu_top">
	<?if (is_array($arResult) && !empty($arResult)):?>
	<?foreach( $arResult as $arItem ){?>
		<div class="menu_item iblock"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></div>
	<?}?>
	<?endif;?>
</div>