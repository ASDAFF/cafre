<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<ul class="submenu">
	<?if (is_array($arResult) && !empty($arResult)):?>
	<?foreach( $arResult as $arItem ){?>
		<li class="menu_item<?=($arItem["SELECTED"]?" selected":"");?>"><a href="<?=$arItem["LINK"]?>"><?=iconv('cp-1251', 'utf-8',$arItem["TEXT"])?></a></li>
		<?if (is_array($arResult["ITEMS"]) && !empty($arResult["ITEMS"])):?>
			<?foreach( $arItem["ITEMS"] as $arSubItem ){?>
				<li class="menu_subitem<?=($arItem["SELECTED"]?" selected":"");?>"><a href="<?=$arSubItem["LINK"]?>"><?=iconv('cp-1251', 'utf-8',$arItem["TEXT"])?></a></li>
			<?}?>
		<?endif;?>
	<?}?>
	<?endif;?>
</ul>