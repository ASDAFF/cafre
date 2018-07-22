<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);
$catalog=false;
$catalog_3_level=false;
$catalog_level=0;
?>
<?if($arResult):?>
	<a class="mobile-nav__open-link"><i class="icon"></i></a>	
	<ul class="mobile-nav__items">
		<?foreach($arResult as $arItem):
			if(strpos($arItem['LINK'], '/catalog/')===false  || $arItem['LINK']=='/catalog/new/' || $arItem['LINK']=='/catalog/hits/'|| $arItem['LINK']=='/catalog/sale/') {
				if($catalog) {
					$catalog=false;
					$catalog_3_level=false;
					if($catalog_3_level==true) {?>
						</ul>						
					<?}
					if($catalog_level>1) {?>						
						<br/>
						</li>
						</ul>
					<?}?>					
					</div>					
					</li>
					</ul>
					</li>
				<?}?>
			<li class="mobile-nav__item">
				<a href="<?=$arItem["LINK"]?>" class="mobile-nav__link"><span><?=$arItem["TEXT"]?></span></a>
			</li>
			<?} elseif(!$catalog) {
				$catalog=true; ?>
				<li class="mobile-nav__item  catalog">
					<a href="/catalog/" class="mobile-nav__link"><span>Каталог</span></a>
						<ul class="mobile-nav__catalog-l1">
							<li class="child cat_menu">
								<div class="child_wrapp">
								
								<ul class="mobile-nav__item">
									<li class="menu_title"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>	
									<?
									$catalog_level=1;
									if($arItem['IS_PARENT']) {?>
									<li class="mobile-nav__item-lvl2">
									<?}	
			} else {
				if($arItem['DEPTH_LEVEL']<$catalog_level) {
					if($catalog_level==3) {?>
						</ul>
					<?}?>
					<br>
					<?if($arItem['DEPTH_LEVEL']==1) {?>	
						</li>
						</ul>
					<?}
				}
				if($arItem['DEPTH_LEVEL']==$catalog_level&&$catalog_level==2) {?><br><?}
				if($arItem['DEPTH_LEVEL']==1) {
					$catalog_3_level=false;?>
					<ul class="mobile-nav__item">
						<li class="menu_title"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>	
						<? if($arItem['IS_PARENT']) {?>
							<li class="mobile-nav__item-lvl2">
						<?}?>
				<?} elseif($arItem['DEPTH_LEVEL']==2) {
					$catalog_3_level=false;?>
					<span class="menu_item"><a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a></span>
				<?} else {
					if(!(strpos($arItem['LINK'], 'vse_brendy')===false)) continue;
					$catalog_3_level=true;
					if($catalog_level==2) {?><ul class="child_wrapp"><?}
					?>
					<li class="menu_item"><a href="<?=$arItem["LINK"]?>" ><?=$arItem["TEXT"]?></a></li>
				<?}
				$catalog_level=$arItem['DEPTH_LEVEL'];?>
			<?}?>
		<?endforeach;
		if($catalog) {
					$catalog=false;
					if($catalog_3_level==true) {?>
						</ul>						
					<?}
					if($catalog_level>1) {?>						
						<br/>
						</li>
						</ul>
					<?}?>					
					</div>					
					</li>
					</ul>
					</li>
			<?}?>						
		<li class="mobile-nav__item">
			<?$APPLICATION->IncludeComponent("bitrix:search.form", "top", array(
				"PAGE" => $arParams["IBLOCK_CATALOG_DIR"],
				"USE_SUGGEST" => "N",
				"USE_SEARCH_TITLE" => "Y",
				"INPUT_ID" => "title-search-input4",
				"CONTAINER_ID" => "title-search4"
				), false
			);?>
		</li>
	</ul>	
	<div class="search_block">
		<span class="icon"></span>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".mobile-nav__open-link").click(function(){
				$(".mobile-nav__items").toggleClass("opened").slideToggle(200);
			});
			$(".mobile-nav__link").on('click', function(e) {
				var addressValue = $(this).attr("href");
				if(addressValue == "/catalog/") {
					e.preventDefault();
					$(".mobile-nav__catalog-l1 .child").toggleClass("opened");	
				}
			})
		});
	</script>
<?endif;?>