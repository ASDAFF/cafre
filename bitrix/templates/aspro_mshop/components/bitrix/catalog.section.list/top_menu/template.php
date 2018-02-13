<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="child cat_menu">
	<div class="child_wrapp">
		<?$arUrl=explode("?", $_SERVER["REQUEST_URI"]);?>
		<?foreach( $arResult["SECTIONS"] as $arItems ){?>
			<div class="mobile-nav__item">
			<li class="menu_title"><a href="<?=$arItems["SECTION_PAGE_URL"]?>"><?=$arItems["NAME"]?></a></li>	
			<?if($arItems["SECTIONS"]):?>
				<div class="mobile-nav__item-lvl2">
				<?foreach($arItems["SECTIONS"] as $arItem ):?>
					<li class="menu_item"><a href="<?=$arItem["SECTION_PAGE_URL"]?>" <?=($arUrl[0]==$arItem["SECTION_PAGE_URL"] ? "class='current'" : "")?>><?=$arItem["NAME"]?></a></li>
					<?if($arItem["SECTIONS"]):?>
						<div class="child_wrapp">
							<?foreach($arItem["SECTIONS"] as $arItem2 ):?>
								<li class="menu_item"><a href="<?=$arItem2["SECTION_PAGE_URL"]?>" <?=($arUrl[0]==$arItem2["SECTION_PAGE_URL"] ? "class='current'" : "")?>><?=$arItem2["NAME"]?></a></li>
							<?endforeach;?>
						</div>
					<?endif;?>
				<?endforeach;?>
				</div>
			<?endif;?>
			</div>
		<?}?>
	</div>
</div>
