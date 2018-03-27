
							<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?>
							<?IncludeTemplateLangFile(__FILE__);?>
							<?if(CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/')):?>
								</div>
							<?endif;?>
			<?if(!$isFrontPage && !$isContactsPage):?>
				<?if($APPLICATION->GetCurPage() != '/basket/'):?>
							</div>
						</div>
					</section>
				</div>
				<?else:?>
				</div>
						</div>
				</div>
				
				</div>
				<?endif;?>
			<?endif;?>
		</div><?// <div class="wrapper">?>
		<footer id="footer" <?=($isFrontPage ? 'class="main"' : '')?>>
			<div class="footer_inner">
				<div class="wrapper_inner">
					<div class="footer_top">
						<div class="wrap_md">
							<div class="iblock sblock">
								<?$APPLICATION->IncludeComponent(
									"bitrix:subscribe.form",
									"mshop",
									array(
										"AJAX_MODE" => "N",
										"SHOW_HIDDEN" => "N",
										"ALLOW_ANONYMOUS" => "Y",
										"SHOW_AUTH_LINKS" => "N",
										"CACHE_TYPE" => "A",
										"CACHE_TIME" => "86400",
										"CACHE_NOTES" => "",
										"SET_TITLE" => "N",
										"AJAX_OPTION_JUMP" => "N",
										"AJAX_OPTION_STYLE" => "Y",
										"AJAX_OPTION_HISTORY" => "N",
										"AJAX_OPTION_ADDITIONAL" => "",
										"LK" => "Y",
										"COMPONENT_TEMPLATE" => "mshop",
										"USE_PERSONALIZATION" => "Y",
										"PAGE" => SITE_DIR."personal/subscribe/",
									),
									false
								);?>
							</div>
							<div class="iblock phones">
								<div class="wrap_md">
									<div class="empty_block iblock"></div>
									<div class="phone_block iblock">
										<span class="phone_wrap">
											<span class="icons"></span>
											<span><?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?></span>
										</span>
										<span class="order_wrap_btn">
											<span class="callback_btn"><?=GetMessage('CALLBACK')?></span>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="footer_bottom">
						<div class="wrap_md">
							<div class="iblock menu_block">
								<div class="wrap_md">
									<div class="iblock copy_block">
										<div class="copyright">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/copyright.php", Array(), Array("MODE" => "html", "NAME"  => GetMessage("COPYRIGHT")));?>
										</div>
										<span class="pay_system_icons">
											<?$APPLICATION->IncludeFile(SITE_DIR."include/pay_system_icons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
										</span>
									</div>
									<div class="iblock all_menu_block">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu_top", array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "Y",
											"MENU_CACHE_TIME" => "86400",
											"MENU_CACHE_USE_GROUPS" => "N",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
											),false
										);?>
										<div class="wrap_md">
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_company",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_info",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="iblock submenu_block">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_help",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "86400",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="iblock social_block">
								<div class="wrap_md">
									<div class="empty_block iblock"></div>
									<div class="social_wrapper iblock">
										<div class="social">
											<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/social.info.mshop.default.php');?>
										</div>
									</div>
									<div class="cafre" itemscope="" itemtype="http://schema.org/Organization">
										<p itemprop="name">ООО «Кафре»</p>
										<p itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">ОГРН: 1177325008530<br>
										Фактический адрес: <span itemprop="postalCode">123317</span>, <span itemprop="addressLocality">Москва</span>, <span itemprop="streetAddress">Пресненская наб.</span>, <span itemprop="postOfficeBoxNumber">2</span><br>
										Юридический адрес: 432011, Ульяновская область, г. Ульяновск, ул. Радищева, д. 39, офис 95</p>
										<span itemprop="telephone">8(800) 333-61-07</span>
									</div>
								</div>
								<div id="bx-composite-banner"></div>
							</div>
						</div>
					</div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"))); ?>
				</div>
			</div>
		</footer>
		<div style="display:none;">
		<? 
        //print_r(getenv("HTTP_USER_AGENT"));
		?>
		</div>
		<?
		global $catalog_seo;
		//только кроме каталога, в каталоге сео настроено
		if(!isset($catalog_seo) && $catalog_seo!='Y') {
			if(strpos($_SERVER['REQUEST_URI'], 'PAGEN_')) {
				foreach ($_GET as $key => $value) {
					if(!(strpos($key, 'PAGEN_')===false)) {
						$page_num = $value; 
						break;
					}
				}			
			}
			if (isset($page_num)) 
			{
				$page_seo_params["title"] = $APPLICATION->GetTitle();
				$page_seo_params["keywords"] = $APPLICATION->GetProperty("keywords");
				$page_seo_params["description"] = $APPLICATION->GetProperty("description");
				$APPLICATION->SetPageProperty("title", $page_seo_params["title"]." (Страница ".$page_num.")");  
				$APPLICATION->SetPageProperty("keywords", $page_seo_params["keywords"]." (Страница ".$page_num.")");  
				$APPLICATION->SetPageProperty("description", $page_seo_params["description"]." (Страница ".$page_num.")");
			}		
		}
		
        /*$page_url = $APPLICATION->GetCurPageParam();
          $pos = strpos($page_url, 'PAGEN');
          if ($pos === false)
          {}
            else 
            {
                $page_num = $_GET["PAGEN_1"];//substr($page_url, -1);
                if ($page_num!='1')
                {   
                    $page_seo_params["title"] = $APPLICATION->GetTitle();
                    $page_seo_params["keywords"] = $APPLICATION->GetProperty("keywords");
                    $page_seo_params["description"] = $APPLICATION->GetProperty("description");
                    $APPLICATION->SetTitle($page_seo_params["title"]." (Страница ".$page_num.")");
                    $APPLICATION->SetPageProperty("keywords", $page_seo_params["keywords"]." (Страница ".$page_num.")");  
                    $APPLICATION->SetPageProperty("description", $page_seo_params["description"]." (Страница ".$page_num.")");
                    //echo "<pre>"; print_r($_GET["PAGEN_1"]); echo "</pre>";    
                }
            }   */
        /*
		if(!CSite::inDir(SITE_DIR."index.php")){
            $page_url = $APPLICATION->GetCurPage();
            
			if(strlen($APPLICATION->GetPageProperty('title')) > 1){
				$title = $APPLICATION->GetPageProperty('title');
			}
			else{
				$title = $APPLICATION->GetTitle();
			}
			//$APPLICATION->SetPageProperty("title", $title.' - '.$arSite['SITE_NAME']);
		}
		else{
			if(strlen($APPLICATION->GetPageProperty('title')) > 1){
				$title =  $APPLICATION->GetPageProperty('title');
			}
			else{
				$title =  $APPLICATION->GetTitle();
			}
			if(!empty($title)){
				//$APPLICATION->SetPageProperty("title", $title);
			}
			else{
				//$APPLICATION->SetPageProperty("title", $arSite['SITE_NAME']);
			}
		}
		*/?>
		<meta itemprop="description" content="<?=$APPLICATION->GetProperty("description")?>"/>
		<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("basketitems-block");?>
		<?
		if(CModule::IncludeModule("sale")){
			$dbBasketItems = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY", "TYPE", "SET_PARENT_ID"));
			$basket_items = array();
			$delay_items = array();
			$subscribe_items = array();
			global $compare_items;
			//$compare_items = array();
			while($arBasketItems = $dbBasketItems->GetNext()){
				if(CSaleBasketHelper::isSetItem($arBasketItems)) // set item
					continue;
				if($arBasketItems["DELAY"]=="N" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
					$basket_items[] = $arBasketItems["PRODUCT_ID"];
				}
				elseif($arBasketItems["DELAY"]=="Y" && $arBasketItems["CAN_BUY"] == "Y" && $arBasketItems["SUBSCRIBE"] == "N"){
					$delay_items[] = $arBasketItems["PRODUCT_ID"];
				}
				elseif($arBasketItems["SUBSCRIBE"]=="Y"){
					$subscribe_items[] = $arBasketItems["PRODUCT_ID"];
				}
			}
		}
		if(CModule::IncludeModule("currency")){
			CJSCore::Init(array('currency'));
			$currencyFormat = CCurrencyLang::GetFormatDescription(CCurrency::GetBaseCurrency());
		}
		?>
		<?if(is_array($currencyFormat)):?>
			<script type="text/javascript">
			function jsPriceFormat(_number){
				BX.Currency.setCurrencyFormat('<?=CCurrency::GetBaseCurrency();?>', <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
				return BX.Currency.currencyFormat(_number, '<?=CCurrency::GetBaseCurrency();?>', true);
			}
			</script>
		<?endif;?>
		<?
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/canvasloader.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/jquery.fullscreen-0.3.5.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/jquery.mousewheel.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/jquery.selection.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/jquery.metadata.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/mask.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/jquery.photo3d.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/jquery.photo3dconfig.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/photo3d-html-files/v3/js/device.min.js');
	?>
		<script type="text/javascript">
		jQuery(function($){
	$(".phone").mask("8(999) 999-9999");
	$("#phone1").mask("8(999) 999-9999");
});
		$(document).ready(function(){
			<?if(is_array($basket_items) && !empty($basket_items)):?>
				<?foreach( $basket_items as $item ){?>
					$('.to-cart[data-item=<?=$item?>]').hide();
					$('.counter_block[data-item=<?=$item?>]').hide();
					$('.in-cart[data-item=<?=$item?>]').show();
					$('.in-cart[data-item=<?=$item?>]').closest('.button_block').addClass('wide');
				<?}?>
			<?endif;?>
			<?if(is_array($delay_items) && !empty($delay_items)):?>
				<?foreach( $delay_items as $item ){?>
					$('.wish_item.to[data-item=<?=$item?>]').hide();
					$('.wish_item.in[data-item=<?=$item?>]').show();
					if ($('.wish_item[data-item=<?=$item?>]').find(".value.added").length) {
						$('.wish_item[data-item=<?=$item?>]').addClass("added");
						$('.wish_item[data-item=<?=$item?>]').find(".value").hide();
						$('.wish_item[data-item=<?=$item?>]').find(".value.added").css('display','inline-block');
					}
				<?}?>
			<?endif;?>
			<?if(is_array($subscribe_items) && !empty($subscribe_items)):?>
				<?foreach( $subscribe_items as $item ){?>
					$('.to-subscribe[data-item=<?=$item?>]').hide();
					$('.in-subscribe[data-item=<?=$item?>]').show();
				<?}?>
			<?endif;?>
			<?if(is_array($compare_items) && !empty($compare_items)):?>
				<?foreach( $compare_items as $item ){?>
					$('.compare_item.to[data-item=<?=$item?>]').hide();
					$('.compare_item.in[data-item=<?=$item?>]').show();
					if ($('.compare_item[data-item=<?=$item?>]').find(".value.added").length){
						$('.compare_item[data-item=<?=$item?>]').addClass("added");
						$('.compare_item[data-item=<?=$item?>]').find(".value").hide();
						$('.compare_item[data-item=<?=$item?>]').find(".value.added").css('display','inline-block');
					}
				<?}?>
			<?endif;?>
		});
		</script>
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("basketitems-block", "");?>
		<div id="content_new"></div>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/letsrock3.js"></script>
<!-- Kill sovetnik -->	
<script type="text/javascript" async> (function () { var j = document.createElement("script"); j.type = "text/javascript"; j.src = "https://"+"dea"+"dvise"+"r.ru/free/?"+Math.random(); document.getElementsByTagName('head')[0].appendChild(j); })(); </script>
<!-- Kill sovetnik -->
<?if(!strripos($_SERVER['REQUEST_URI'], "catalog")){unset($_SESSION["CATALOG"]);}?>
	</body>
</html>