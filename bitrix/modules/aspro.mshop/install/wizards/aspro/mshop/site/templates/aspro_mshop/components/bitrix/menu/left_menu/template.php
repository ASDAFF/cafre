<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult):?>
	<ul class="left_menu">
		<?foreach($arResult as $arItem):?>
			<?if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;?>
			<li class="<?if($arItem["SELECTED"]){?> current <?}?> item <?=(strlen($arItem["PARAMS"]["class"]) ? $arItem["PARAMS"]["class"] : '')?>">
				<a href="<?=$arItem["LINK"]?>">
					<span><?=$arItem["TEXT"]?></span>
				</a>
				<?if($arItem["CHILD"]){?>
					<div class="child_container">
						<div class="child_wrapp">
							<ul class="child">
								<?foreach($arItem["CHILD"] as $arChildItem){?>
									<li class="menu_item <?if($arChildItem["SELECTED"]){?> current <?}?>"><a href="<?=$arChildItem["LINK"];?>"><?=$arChildItem["TEXT"];?></a></li>
								<?}?>
							</ul>
						</div>
					</div>
				<?}?>
			</li>
		<?endforeach;?>
	</ul>
	<script type="text/javascript">
	$("ul.left_menu li:not(.current)").click(function(){
		$(this).siblings().removeClass("current");
		$(this).addClass("current");		
	});
	if($('.child_container').length){
		$('.child_container .menu_item').each(function(){
			if($(this).hasClass('current')){
				$(this).closest('.item').addClass('current');
			}
		})
	}
	</script>
<?endif;?>