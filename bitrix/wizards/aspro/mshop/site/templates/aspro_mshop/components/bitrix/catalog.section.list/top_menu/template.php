<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="child cat_menu">
	<div class="child_wrapp">
		<?$index=0;?>
		<?$arUrl=explode("?", $_SERVER["REQUEST_URI"]);?>
		<?foreach( $arResult["SECTIONS"] as $arItems ){?>
			<?$index++;?>
			<ul <?if(!($index%3)):?>class="last"<?endif;?>>
				<li class="menu_title"><a href="<?=$arItems["SECTION_PAGE_URL"]?>"><?=$arItems["NAME"]?></a></li>
				<?if($arItems["SECTIONS"]):?>
					<?$i = 0;?>
					<?foreach($arItems["SECTIONS"] as $arItem ):?>
						<li  <?=($i > 4 ? 'class="d menu_item" style="display: none;"' : 'class="menu_item"')?>><a href="<?=$arItem["SECTION_PAGE_URL"]?>" <?=($arUrl[0]==$arItem["SECTION_PAGE_URL"] ? "class='current'" : "")?>><?=$arItem["NAME"]?></a></li>
						<?++$i;?>
					<?endforeach;?>
					<?if(count($arItems["SECTIONS"] ) > 5 ):?>
						<!--noindex-->
						<li class="see_more">
							<a rel="nofollow" href="javascript:;"><?=GetMessage('CATALOG_VIEW_MORE')?></a>
						</li>
						<!--/noindex-->
					<?endif;?>
				<?endif;?>
			</ul>
		<?}?>
	</div>
</div>
