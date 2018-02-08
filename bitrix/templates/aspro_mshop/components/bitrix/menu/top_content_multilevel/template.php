<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty( $arResult ) ){?>
	<ul class="menu">
		<?foreach( $arResult as $key => $arItem ){?>
			<li<?if( $arItem["SELECTED"] ):?> class="current"<?endif?>>
				<a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a>
				<?if( $arItem["IS_PARENT"] == 1 ){?>
					<div class="child submenu">
						<?if($arItem["IS_PARENT"]==1):?><b class="space"></b><?endif;?>
						<div class="child_wrapp">
							<?foreach( $arItem["ITEMS"] as $arSubItem ){?>
								<a <?if( $arSubItem["SELECTED"] ):?>class="current"<?endif?> href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
							<?}?>
						</div>
					</div>
				<?}?>
			</li>
			<?if($arResult[$key+1]):?><li class="separator"></li><?endif;?>
		<?}?>
	</ul>
	<script>
		$(".content_menu .menu > li:not(.current) > a").click(function()
		{
			$(this).parents("li").siblings().removeClass("current");
			$(this).parents("li").addClass("current");
		});
		$(".content_menu .menu .child_wrapp a").click(function()
		{
			$(this).siblings().removeClass("current");
			$(this).addClass("current");
		});
	</script>
<?}?>