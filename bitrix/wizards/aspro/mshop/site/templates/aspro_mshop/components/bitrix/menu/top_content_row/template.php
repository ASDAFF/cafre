<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty( $arResult ) ){?>
	<ul class="menu">
		<?foreach( $arResult as $key => $arItem ){?>
			<li <?if( $arItem["SELECTED"] ):?> class="current"<?endif?> >
				<a href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span></a>
			</li>
		<?}?>
	</ul>
	<script>
		$(".content_menu .menu > li:not(.current) > a").click(function()
		{
			$(this).parents("li").siblings().removeClass("current");
			$(this).parents("li").addClass("current");
		});
	</script>
<?}?>