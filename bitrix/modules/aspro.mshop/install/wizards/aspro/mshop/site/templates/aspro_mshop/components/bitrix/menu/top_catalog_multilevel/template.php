<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult):?>
	<ul class="menu bottom">
		<?foreach($arResult as $key => $arItem):?>			
			<li class="menu_item_l1 <?=(!$key ? ' first' : '')?><?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["PARAMS"]["ACTIVE"]=="Y" ? ' active' : '')?>">
				<a class="<?=($arItem["SELECTED"] ? ' current' : '')?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				<?if($arItem["IS_PARENT"]):?>
					<div class="child submenu">
						<div class="child_wrapp">
							<?foreach($arItem["CHILD"] as $i => $arSubItem):?>
								<?if(count($arSubItem["CHILD"])):?>
									<div class="depth3<?=($i > 4 ? ' d' : '')?>"<?=($i > 4 ? ' style="display:none;"' : '')?>>
										<a class="title<?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
										<?if($arSubItem["CHILD"] && is_array($arSubItem["CHILD"])):?>
											<?foreach($arSubItem["CHILD"] as $ii => $arSubItem3):?>
												<a class="<?=($arSubItem3["SELECTED"] ? ' current' : '')?><?=($ii > 4 ? ' d' : '')?>" href="<?=$arSubItem3["LINK"]?>"<?=($ii > 4 ? ' style="display:none;"' : '')?>><?=$arSubItem3["TEXT"]?></a>
											<?endforeach;?>
											<?if(count($arSubItem["CHILD"]) > 5):?>
												<!--noindex-->
												<a class="see_more" rel="nofollow" href="javascript:;"><?=GetMessage('CATALOG_VIEW_MORE')?></a>
												<!--/noindex-->
											<?endif;?>
										<?endif;?>
									</div>
								<?else:?>
									<a class="<?=($arSubItem["SELECTED"] ? ' current' : '')?><?=($i > 4 ? ' d' : '')?>" href="<?=$arSubItem["LINK"]?>"<?=($i > 4 ? ' style="display:none;"' : '')?>><?=$arSubItem["TEXT"]?></a>
								<?endif;?>
							<?endforeach;?>
							<?if(count($arItem["CHILD"]) > 5):?>
								<!--noindex-->
								<a class="see_more" rel="nofollow" href="javascript:;"><?=GetMessage('CATALOG_VIEW_MORE')?></span></a>
								<!--/noindex-->
							<?endif;?>
						</div>
					</div>
				<?endif;?>
			</li>
		<?endforeach;?>
		<li class="more menu_item_l1">
			<a><?=GetMessage("CATALOG_VIEW_MORE_")?><i></i></a>
			<div class="child cat_menu"><div class="child_wrapp"></div></div>
		</li>
		<li class="stretch"></li>
	</ul>
	<script type="text/javascript">
	var menu = $('.catalog_menu ul.menu');
	var extendedItemsContainer = $(menu).find('li.more');
	var extendedItemsSubmenu = $(extendedItemsContainer).find('.child_wrapp');
	var extendedItemsContainerWidth = $(extendedItemsContainer).outerWidth();
	
	var reCalculateMenu = function(){
		$(menu).find('li:not(.stretch)').show();
		$(extendedItemsSubmenu).html('');
		$(extendedItemsContainer).removeClass('visible');
		calculateMenu();
	}
	
	var calculateMenu = function(){
		var menuWidth = $(menu).outerWidth();	
		$(menu).css('display', '');			
		$('.catalog_menu .menu > li').each(function(index, element){
			if(!$(element).is('.more')&&!$(element).is('.stretch')){
				var itemOffset = $(element).position().left;
				var itemWidth = $(element).outerWidth();
				var submenu = $(element).find('.submenu'); 
				var submenuWidth = $(submenu).outerWidth();
				if($(submenu).length){
					if(index != 0){
						$(submenu).css({'marginLeft': (itemWidth - submenuWidth) / 2});
					}
				}
				var bLast = index == $('.catalog_menu .menu > li').length - 3;
				
				if(itemOffset + itemWidth + (bLast ? 0 : extendedItemsContainerWidth) > menuWidth || $(extendedItemsContainer).is('.visible')){
					if(!$(extendedItemsContainer).is('.visible')){
						$(extendedItemsContainer).addClass('visible').css('display', '');
					}
					var menuItem = $(element).clone();
					
					var menuItemTitleA = $(menuItem).find('> a');
					$(menuItem).find('.depth3').find('a:not(.title)').remove();
					$(menuItem).wrapInner('<ul ' + (($(extendedItemsSubmenu).find('> ul').length % 3 == 2) ? 'class="last"' : '') + '></ul>');
					$(menuItem).find('ul').prepend('<li class="menu_title ' + $(menuItem).attr('class') + '"><a href="' + menuItemTitleA.attr('href') + '">' + menuItemTitleA.text() + '</a></li>');
					$(menuItem).find('ul > li').removeClass('menu_item_l1');
					menuItemTitleA.remove();
					$(menuItem).find('.child_wrapp > a').each(function() {
						$(this).wrap('<li class="menu_item"></li>');
					});
					$(menuItem).find('.child_wrapp > .depth3').each(function() {
						$(this).find('a.title').wrap('<li class="menu_item"></li>');
					});
					$(menuItem).find('li.menu_item').each(function() {
						$(menuItem).find('ul').append('<li class="menu_item ' + $(this).find('> a').attr('class') +'" style="' + $(this).find('> a').attr('style') +'">' + $(this).html() + '</li>');
					});
					$(menuItem).find('.child.submenu').remove();
					
					
					$(extendedItemsSubmenu).append($(menuItem).html());
					$(element).hide();
				}
				else{
					$(element).show();
					if(bLast){
						$(element).css('border-right-width', '0px');
					}
				}
			}
			if(!extendedItemsSubmenu.html().length){
				extendedItemsContainer.hide();
			}
		});
		$('.catalog_menu .menu .see_more a.see_more').removeClass('see_more');
		$('.catalog_menu .menu li.menu_item a').removeClass('d');
		$('.catalog_menu .menu li.menu_item a').removeAttr('style');
	}
	
	if($(window).outerWidth() > 600){
		calculateMenu();
		$(window).load(function(){
			reCalculateMenu();
		});
	}
	
	$(document).ready(function() {
		$('.catalog_menu .menu > li:not(.current):not(.more):not(.stretch) > a').click(function(){
			$(this).parents('li').siblings().removeClass('current');
			$(this).parents('li').addClass('current');
		});
		
		$('.catalog_menu .menu .child_wrapp a').click(function(){
			$(this).siblings().removeClass('current');
			$(this).addClass('current');
		});
	});
	</script>
<?endif;?>