<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<div class="wrap_md submenu_top">
	<?if (is_array($arResult) && !empty($arResult)):?>
	<?foreach( $arResult as $arItem ){?>
	
		<div class="menu_item iblock"><span class="menuBot" style="font-size: 16px;font-weight: 600;line-height: 15px;color: #1d1a1a;"><?=$arItem["TEXT"]?></span></div>
	<?}?>
	<?endif;?>
</div>