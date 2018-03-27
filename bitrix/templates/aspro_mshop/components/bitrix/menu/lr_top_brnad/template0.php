<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?$start_div = true;?>
<?//if($arResult):?>
	<ul class="menu adaptive">
		<li class="menu_opener"><a><?=GetMessage('MENU_NAME')?></a><i class="icon"></i></li>
	</ul>
	<ul class="menu full tz_menu_full">
    <?foreach($arResult as $key => $arItem):?>  
            <?if(($key > 12)||($arItem["LINK"] == "/catalog/aksessuary_i_tekhnika/")||($arItem["LINK"] == "/catalog/vybirayte_svoy_podarok/")):?>
                <?continue;?>          
            <?endif?> 
			<?if($arItem["DEPTH_LEVEL"]==1):?>
            <li class="menu_item_l1 <?=(!$key ? ' first' : '')?><?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["PARAMS"]["ACTIVE"]=="Y" ? ' active' : '')?>">
                <a class="<?=($arItem["SELECTED"] ? ' current' : '')?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				<?endif;?>
                <?if($arItem["IS_PARENT"]):?>
                    <?/*<div class="child submenu">*/?>
                    <div class="child cat_menu">
                        <div class="child_wrapp">
						
							<div class="menu__main">

                            <div class="tz_menu_block">
                            <?$start_div = false;?> 
									<?/*<div class="depth3<?//=($i > 4 ? ' d' : '')?>"<?//=($i > 4 ? ' style="display:none;"' : '')?>>*/
									$mystring2 = $arItem["LINK"];
									$findme2   = 'vse_brendy';
									$pos2 = strpos($mystring2, $findme2);
									if($pos2 == true):
									?>
									<div class="brands">
									<div class="brands__alphabet">
										<div class="brands__lang brands__lang_eng"></div>
										<div class="brands__lang brands__lang_rus"></div>
										<span class="brands__all-link">Все</span>
									</div>
									<div class="brands__all">
										<div>
								<?endif;?>

                            <?foreach($arItem["CHILD"] as $i => $arSubItem):?>
								 <?/*<div class="depth3<?//=($i > 4 ? ' d' : '')?>"<?//=($i > 4 ? ' style="display:none;"' : '')?>>*/
									$mystring = $arSubItem["LINK"];
									$findme   = 'vse_brendy';
									$pos = strpos($mystring, $findme);
									?>
                                <?if(count($arSubItem["CHILD"]) && $pos != true):?>
                                   
                                    <?if($start_div):?>
                                        <div class="tz_menu_block">
                                        <?$start_div = false;?>
                                    <?endif?>       

                                    <ul>
                                        <li class="menu_title">
                                            <a class="title<?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
                                        </li>
                                        <?/*<a class="title<?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>*/?>
                                        <?if($arSubItem["CHILD"] && is_array($arSubItem["CHILD"])):?>
											<li class="menu__doplist">
												<ul>
												<?foreach($arSubItem["CHILD"] as $ii => $arSubItem3):?>
													<li class="menu_item">
														<a class="<?=($arSubItem3["SELECTED"] ? ' current' : '')?><?//=($ii > 4 ? ' d' : '')?>" href="<?=$arSubItem3["LINK"]?>"<?//=($ii > 4 ? ' style="display:none;"' : '')?>><?=$arSubItem3["TEXT"]?></a>
													</li>
												<?endforeach;?>
												</ul>
											</li>
                                            <?/*if(count($arSubItem["CHILD"]) > 5):?>
                                                <!--noindex-->
                                                <a class="see_more" rel="nofollow" href="javascript:;"><?=GetMessage('CATALOG_VIEW_MORE')?></a>
                                                <!--/noindex-->
                                            <?endif;*/?>
                                        <?endif;?>
                                    </ul>
                                    <?/*</div>*/?>
									 <?if(in_array($arSubItem["TEXT"], $arResult["TZ_LAST_IN_GROUP"][$arItem["TEXT"]])):?>
                                    </div>
                                    <?$start_div = true;?>
                                <?endif?>   
                                <?else:?>
                                    <a data-img="https://cafre.ru/upload/iblock/143/143932bc8469910d1890222c2a78f483.jpg" class="<?=($arSubItem["SELECTED"] ? ' current' : '')?><?//=($i > 4 ? ' d' : '')?>" href="<?=$arSubItem["LINK"]?>"<?//=($i > 4 ? ' style="display:none;"' : '')?>><?=$arSubItem["TEXT"]?></a>
                                <?endif;?>
								  
                                   


                            <?endforeach;?>
							<?
							if($pos2 == true):
									?>
									</div>
									</div>
									<div class="brands__pic"></div>
									</div>
								<?endif;?>
                            <?/*if(count($arItem["CHILD"]) > 5):?>
                                <!--noindex-->
                                <a class="see_more" rel="nofollow" href="javascript:;"><?=GetMessage('CATALOG_VIEW_MORE')?></span></a>
                                <!--/noindex-->
                            <?endif;*/?>
                        </div>
						</div>
                    </div>
                <?endif;?>
				<?if($arItem["DEPTH_LEVEL"]==1):?>
            </li>
			<?endif;?>
        <?endforeach;?>
		<?/*foreach($arResult as $arItem):?>
			<li class="menu_item_l1 <?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] ? ' catalog' : '')?>">
				<a href="<?=$arItem["LINK"]?>">
					<span><?=$arItem["TEXT"]?></span>
				</a>
				<?if($arItem["IS_PARENT"] == 1):?>

					<div class="child cat_menu">

                        <div class="child_wrapp">
						<ul>
							<?foreach($arItem["CHILD"] as $arSubItem):?>
								<li class="menu_item">
                                    <a class="<?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
                                </li>
							<?endforeach;?>
						</ul>
                        </div>
					</div>
				<?endif;?>
				<?if($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"]):?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section.list",
						"tz_top_menu",
						Array(
							"IBLOCK_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
							"IBLOCK_ID" => $arParams["IBLOCK_CATALOG_ID"],
							"SECTION_ID" => "",
							"SECTION_CODE" => "",
							"COUNT_ELEMENTS" => "N",
							"TOP_DEPTH" => "2",
							"SECTION_FIELDS" => array(0 => "",1 => "",),
							"SECTION_USER_FIELDS" => array(0 => "",1 => "",),
							"SECTION_URL" => "",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "86400",
							"URL" => $_SERVER["REQUEST_URI"],
							"CACHE_GROUPS" => "N",
							"ADD_SECTIONS_CHAIN" => "N"
						)
					);?>
				<?endif;?>
			</li>
		<?endforeach;*/?>

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
			
			$('.menu__doplist').hide();
			$('.menu_item_l1').each(function(i, item) {
				$(item).find('.menu_title').first().addClass('active').next().show();
			});
			
			try {
				if(!extendedItemsSubmenu.html().length){
					extendedItemsContainer.hide();
				}
			} catch (e) {};
            
        });
        $('.catalog_menu .menu .see_more a.see_more').removeClass('see_more');
        $('.catalog_menu .menu li.menu_item a').removeClass('d');
        $('.catalog_menu .menu li.menu_item a').removeAttr('style');
    }
    
    if($(window).outerWidth() > 600){
		try {
			calculateMenu();
		} catch (e) {};
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
<?//endif;?>