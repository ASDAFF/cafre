<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	IncludeTemplateLangFile(__FILE__);
	$curPage = $APPLICATION->GetCurPage();
	global $APPLICATION, $TEMPLATE_OPTIONS, $arSite, $USER,$site_o_template;
	$site_o_template='/bitrix/templates/aspro_mshop';

	$arSite = CSite::GetByID(SITE_ID)->Fetch();	
?>
<!DOCTYPE html>
<html lang="ru" itemscope itemtype="https://schema.org/WebPage" amp>
<head>
	<link rel="canonical" href="<?=$curPage?>">
	<meta charset="utf-8">
	
	<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<script data-skip-moving="true" async src="https://cdn.ampproject.org/v0.js"></script>
	<script data-skip-moving="true" async src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js" custom-element="amp-sidebar"></script> 
	<script data-skip-moving="true" async src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js" custom-element="amp-analytics"></script> 

	<title itemprop="name"><?$APPLICATION->ShowTitle()?></title>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <script type="application/ld+json">
      {
        "@context": "http://schema.org",
        "@type": "NewsArticle",
        "headline": "Open-source framework for publishing content",
        "datePublished": "2015-10-07T12:02:41Z",
        "image": [
          "logo.jpg"
        ]
      }
    </script>
	
	<style amp-custom>
	@font-face {
	font-family: 'Futura';
	src: url('/bitrix/templates/aspro_mshop/css/../fonts/FuturaPT-Book.eot');
	src: url('/bitrix/templates/aspro_mshop/css/../fonts/FuturaPT-Book.eot?#iefix') format('embedded-opentype'),
		url('/bitrix/templates/aspro_mshop/css/../fonts/FuturaPT-Book.woff') format('woff'),
		url('/bitrix/templates/aspro_mshop/css/../fonts/FuturaPT-Book.ttf') format('truetype');
	font-weight: normal;
	font-style: normal;
}
main {
	width:100%;
	float:left;
	padding: 0 15px; 
	box-sizing: border-box;
}
		/*root level*/
		* {    font-family: 'Futura', sans-serif;}
		a {color: #EB5470;}
.catalog_section_list{border-top:1px solid #e5e5e5;font-size:0px;margin:41px 0px 4px;padding:60px 0px 0px;}
.catalog_section_list .section_item{line-height:20px;font-size:12px;width:100%;padding:0;margin:0 0 50px 0;display:inline-block;zoom:1;vertical-align:top;box-sizing:border-box;-moz-box-sizing:border-box;-o-box-sizing:border-box;-webkit-box-sizing:border-box;}
.catalog_section_list .section_item_inner {margin:0 45px 0 0;box-sizing:border-box;-moz-box-sizing:border-box;-o-box-sizing:border-box;-webkit-box-sizing:border-box;}
.section_item_inner .section_info a, .section_item_inner .section_info ul li.name a:hover{border-bottom:0px;font-weight:500;line-height:20px;}
.catalog_section_list .section_item li.name a{text-decoration:none;}
.catalog_section_list .section_item li.name a span{font-size:16px;font-weight:500;line-height:18px;}
.catalog_section_list .section_item li.name{display:block;margin-bottom:8px;}
.catalog_section_list .section_item li.sect{display:inline-block;padding:0px 5px 0px 0px;white-space:nowrap;}
.catalog_section_list .section_item .desc .desc_wrapp{display:inline-block;padding:0px;margin:14px 0px 0px;color:#888888;}


header {
	overflow: hidden;
width:100%;
    background: #f7f7f9;
    border-bottom: 1px solid #fff;
    min-height: 39px;
	    display: flex;
    justify-content: space-between;
	    align-items: center;
}
.phone_wrap .icons {
    width: 9px;
    height: 9px;
    background: url(/bitrix/templates/aspro_mshop/images/icons_wish.png) -29px -182px no-repeat;
    margin: 0px 10px 1px 0px;
	display: inline-block;
}
header a {
	color: #1d1a1a;
	font-size: 13px;
}
		.phone_wrap {
			float: left;
			padding: 10px 15px;
			display: block;
		}
		.hamburger {
			    margin: 0px 15px 0 0;
    float: right;
    background: #fff;
			border:none;
			    display: inline-block;
    height: 20px;
    width: 30px;
    background-image: linear-gradient(to top, transparent 15%, #1d1a1a 15%, #1d1a1a 30%, transparent 30%, transparent 50%, #1d1a1a 50%, #1d1a1a 65%, transparent 65%, transparent 85%, #1d1a1a 85%, #1d1a1a 100%);
		}
		
		
		

.mobile-nav__items {
	position: absolute;
	top: 100%; left: -15px; right: -15px;
	margin: 0; padding: 0;
	list-style: none;
	background: #fff;
	display: none;
	padding: 15px;
	box-sizing: border-box;
	border-width: 1px 0 1px 0;
	border-color: #f1f1f1;
	border-style: solid;
	box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

.mobile-nav__catalog-l1 .child {
	display: none;
}

.mobile-nav__catalog-l1 .child.opened  {
	
}

.mobile-nav__item {
	font-size: 13px;
	text-transform: uppercase;
	line-height: 40px;
}

.mobile-nav__item:before {
	display: none;
}

.mobile-nav__link {
	color: #000;
}

#sidebar1 ul {
	list-style-type:none;
}
.logo {
    max-width: 200px;
    display: block;
    float: left;
    margin: 15px;
    width: 82px;
}
.phone_block .phone_wrap {
    width: calc(100% - 30px);
    text-align: center;
	}
footer {
	width: 100%;
    float: left;
padding: 0 15px;
    box-sizing: border-box;
}
.copyright * {
	text-align:center;
}
.iblock.submenu_block ul {
	list-style-type: none;
	padding: 0;
}
.cafre {
	margin:5px 0 20px;
}
footer * {
	
}
.cost.prices {
	width: 100%;
	float:left;
	    margin: 0 0 10px;
}
/*stickers*/
.stickers { position: absolute; top: 3px; left: 3px; z-index: 2; }
.stickers [class*="sticker_"] { display: block; height: 45px; width: 45px; background: url(/bitrix/templates/aspro_mshop/css/../images/icons_goods.png) -1px -0px no-repeat; margin: -3px 0px 0px; }
.stickers .sticker_new{ background-position: -1px -0px; }
.stickers .sticker_recommend { background-position: -1px -184px; }
.stickers .sticker_stock { background-position: -1px -46px; }
.stickers .sticker_hit { background-position: -1px -92px; }
.stickers [class*="sticker_"]:first-child { margin-top: 0; }
.catalog_item.item_wrap  {
	position: relative;
	    margin-bottom: 25px;
    border-bottom: 1px solid #e4e4e4;
    padding-bottom: 15px;
}

.oldp-new {
    text-decoration: line-through;
    font-size: 11px;
    line-height: 15px;
    font-weight: normal;
}

.sale_block .text, .sale_block .value {
    float: left;
    background: #ffd02e;
    padding: 4px 8px 3px;
    font-size: 11px;
    line-height: 15px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin-top: 1px;
    margin-bottom: 1px;
}
.detail_order {
    background: #EB5470;
    padding: 10px 32px 10px;
    font-size: 20px;
    line-height: 28px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin-top: 1px;
    margin-bottom: 14px;
    display: inline-block;
    color: #ffffff;
}

.module-pagination { margin: 0; text-align: center; font-size: 0; 
padding: 15px 0px; position: relative; }
.module-pagination .nums a, .module-pagination .nums span { display: inline-block; width: 33px; height: 29px; text-align: center; line-height: 29px; margin: 0px 5px 5px 0px; text-decoration: none; font-size: 13px; font-weight: 600; border-radius: 2px; -webkit-border-radius: 2px; -moz-border-radius: 2px; }
.module-pagination .nums a:not(.cur):hover { background: #e3e3e3; }
.module-pagination .flex-direction-nav { position: absolute; width: 100%; }
.module-pagination .flex-direction-nav > li { position: absolute; }
.module-pagination .flex-direction-nav .flex-nav-next{ right: 0px }
.module-pagination .flex-direction-nav .disabled { display: none; }
.module-pagination .flex-direction-nav .flex-nav-prev { left: 0px }
.module-pagination .point_sep { cursor: default; display: inline-block; width: 27px; height: 29px; font-size: 0; background: url(/bitrix/templates/aspro_mshop/css/../images/pagination_sep.png) center no-repeat ; border: 0 ; vertical-align: bottom; }
.preim__item h4 {
width:100%;
}
	.preim__item img {
	float:left;
	margin: 0 10px 0 0px;
}
.preim__list {
	list-style-type: none;
	padding:0;
}
.item_main_info {
	position:relative; 
}
.brand_picture amp-img {
	max-width: 200px;
}

.element_detail_text {
    color: #000000;
    background: #f7f7f9;
    padding: 10px 15px 15px;
    margin-top: 15px;
}
	</style>
  
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
	</head>
<body>

<amp-sidebar id="sidebar1" layout="nodisplay" side="right">
  <ul>
    <li class="mobile-nav__item  current catalog">
				<a href="/catalog/" class="mobile-nav__link"><span>каталог</span></a>

						</li>
					<li class="mobile-nav__item ">
				<a href="/catalog/hits/" class="mobile-nav__link"><span>хиты</span></a>

								
							</li>
					<li class="mobile-nav__item ">
				<a href="/catalog/new/" class="mobile-nav__link"><span>новинки</span></a>

								
							</li>
					<li class="mobile-nav__item ">
				<a href="/catalog/sale/" class="mobile-nav__link"><span>акции</span></a>

								
							</li>
					<li class="mobile-nav__item ">
				<a href="/company/" class="mobile-nav__link"><span>о компании</span></a>

								
							</li>
					<li class="mobile-nav__item ">
				<a href="/contacts/" class="mobile-nav__link"><span>контакты</span></a>

								
							</li>
					<li class="mobile-nav__item ">
				<a href="/help/info_order/" class="mobile-nav__link"><span>оплата, доставка и возврат товара</span></a>

								
							</li>					
							
							
  </ul>
</amp-sidebar>
<amp-img  layout="responsive" src="/bitrix/templates/aspro_mshop/images/banners/cafre_Mail_1920х50.jpg" width=1920 height=50></amp-img>
<header>
<a href="/" class="logo">
							<amp-img  layout="responsive" src="<?=$site_o_template?>/images/cafre-logo.svg" width=300 height=143 ></amp-img>
									</a>
<span class="phone_wrap">
							<span class="icons"></span>
							<span class="phone_text">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
							</span>
						</span>
						
						
									
									
		<button class="hamburger" on='tap:sidebar1.toggle'></button>				
</header>
<main>
<h1><?=(strpos($APPLICATION->GetCurPage(), '/catalog/')===false)?$APPLICATION->ShowTitle(true):$APPLICATION->ShowViewContent('h1');?></h1>





<?/*

	<?
	if(!(strpos($curPage, '/catalog/')===false) && $curPage!='/catalog/') {		
		if(count($_GET)==1 && isset($_GET['_escaped_fragment_']) && $_GET['_escaped_fragment_']=='') {
			
		} 
		elseif($curPage!=$_SERVER['REQUEST_URI'] ){		
			echo '<meta name="robots" content="noindex"/>'; 
		}
		else {
			echo '<meta name="fragment" content="!">';
		}
	}
    elseif($curPage!=$_SERVER['REQUEST_URI']) {		
		//echo '<link rel="canonical" href="https://'.$_SERVER['HTTP_HOST'].$curPage.'">'; 
	}   
	?>
	<?$APPLICATION->ShowHead();?>
	<?$APPLICATION->AddHeadScript();?>
	<?if(CModule::IncludeModule("aspro.mshop")) {CMShop::Start(SITE_ID);}?>
	
	
	


	<script type="text/javascript" data-skip-moving="true">
	window.dataLayer = window.dataLayer || [];
</script>
<!-- Google Tag Manager -->
<script data-skip-moving="true">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KZXSS9');</script>
<!-- End Google Tag Manager -->
<!--Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-77132925-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-77132925-2');
</script>
<!-- CarrotQuest BEGIN -->
<script type="text/javascript">
    (function(){
      function Build(name, args){return function(){window.carrotquestasync.push(name, arguments);} }
      if (typeof carrotquest === 'undefined') {
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
        s.src = '//cdn.carrotquest.io/api.min.js';
        var x = document.getElementsByTagName('head')[0]; x.appendChild(s);
        window.carrotquest = {}; window.carrotquestasync = []; carrotquest.settings = {};
        var m = ['connect', 'track', 'identify', 'auth', 'open', 'onReady', 'addCallback', 'removeCallback', 'trackMessageInteraction'];
        for (var i = 0; i < m.length; i++) carrotquest[m[i]] = Build(m[i]);
      }
    })();
  carrotquest.connect('13181-68385f12e483ac8405da2f239e');
</script>
<!-- CarrotQuest END charset="UTF-8"-->
<script src="//cdn.sendpulse.com/9dae6d62c816560a842268bde2cd317d/js/push/ec14c57e30305057d4ce91fda0aacb93_1.js" ></script>
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = 'https://vk.com/rtrg?p=VK-RTRG-226610-9YY7s';</script>
</head>
<body id="main">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KZXSS9"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
	
	<?if(!CModule::IncludeModule("aspro.mshop")){?>
		<center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center>
		
		</body>
		</html>
		<?die();?>
	<?}?>

	<?$APPLICATION->IncludeComponent("aspro:theme.mshop", ".default", array("COMPONENT_TEMPLATE" => ".default"), false);?>
	<?CMShop::SetJSOptions();?>
	<?$isFrontPage = CSite::InDir(SITE_DIR.'index.php');?>
	<?$isContactsPage = CSite::InDir(SITE_DIR.'contacts/');?>
	<?$isBasketPage=CSite::InDir(SITE_DIR.'basket/');?>

	<div class="wrapper <?=($TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU_COLOR"] != "none" ? "has_menu" : "");?> h_color_<?=$TEMPLATE_OPTIONS["HEAD"]["CURRENT_HEAD_COLOR"];?> m_color_<?=$TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU_COLOR"];?> <?=($isFrontPage ? "front_page" : "");?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]);?> head_<?=strToLower($TEMPLATE_OPTIONS["HEAD"]["CURRENT_VALUE"]);?> banner_<?=strToLower($TEMPLATE_OPTIONS["BANNER_WIDTH"]["CURRENT_VALUE"]);?>">
		<div class="header_wrap <?=strtolower($TEMPLATE_OPTIONS["HEAD_COLOR"]["CURRENT_VALUE"])?>">
			<div class="deliverybanner deliverybanner__header">
				<img src="/bitrix/templates/aspro_mshop/images/banners/cafre_Mail_1920х50.jpg" alt="Бесплатная доставка от 2000 руб."/>
				<button class="deliverybanner__close"></button>
			</div>
			<div class="top-h-row">
				<div class="wrapper_inner">
					<div class="content_menu">
						<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top_content_row", 
	array(
		"ROOT_MENU_TYPE" => $TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU"],
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_TIME" => "86400",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"COMPONENT_TEMPLATE" => "top_content_row"
	),
	false
);?>
					</div>

					<div class="phones">
						<span class="phone_wrap">
							<span class="icons"></span>
							<span class="phone_text">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
							</span>
						</span>
					
						<span class="order_wrap_btn">
							<span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
						</span>
					</div>
				
					<div class="h-user-block" id="personal_block">
						<div class="form_mobile_block">
							<div class="search_middle_block">
								<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/search.title.catalog3.php');?>
							</div>
						</div>
						
						<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "top", array(
							"REGISTER_URL" => SITE_DIR."auth/registration/",
							"FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password/",
							"PROFILE_URL" => SITE_DIR."personal/",
							"SHOW_ERRORS" => "Y"
							)
						);?>
					</div>
					
					<div class="clearfix"></div>
				</div>
			</div>

			<header id="header">
				<div class="wrapper_inner">    
					<!--cellspacing="0" cellpadding="0" border="0"-->
					<table class="middle-h-row">
						<tr>
							<td class="mobile-table-cell">
								<div class="mobile-nav">
									<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/menu.top_general_multilevel.php');?>
								</div>
							</td>

							<td class="logo_wrapp">
								<div class="logo">
									<a href="/">
										<img src="<?=$site_o_template?>/images/cafre-logo.svg" alt="Cafre">
									</a>
							
									<?//CMShop::ShowLogo();?>
									<p>»нтернет-магазин профессиональной косметики c быстрой доставкой без предоплаты</p>
								</div>
							</td>
						
							<td  class="center_block">
								<div class="search">
									<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/search.title.catalog.php');?>
								</div>

								<div class="middle_phone">
									<div class="phones">
										<p>ѕрофессиональна€ консультаци€ специалиста-технолога</p>
										<span class="phone_wrap">
											<img src="<?=$site_o_template?>/images/online.png" alt="" />
											<!--<span class="icons"></span>-->
											<span class="phone_text">
												<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE")));?>
											</span>
										</span>
										<span class="order_wrap_btn">
											<span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
										</span>
									</div>
								</div>
							</td>
							
							<td class="basket_wrapp">
								<div class="wrapp_all_icons">
									<!--<div class="header-compare-block icon_block iblock" id="compare_line">
										<?//include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/catalog.compare.list.compare_top.php');?>
									</div>-->
									
									<div class="header-cart" id="basket_line">
									
										<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("header-cart");?>
										<?//CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);?>
										
										<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] == "FLY" && !$isBasketPage && !CSite::InDir(SITE_DIR.'order/')):?>
											<script type="text/javascript">
												$(document).ready(function() {
													$.ajax({
														url: arMShopOptions['SITE_DIR'] + 'ajax/basket_fly.php',
														type: 'post',
													
														success: function(html){
															$('#basket_line').append(html);
														}
													});
												});
											</script>
										<?endif;?>
										<div class="new_bas_small">
										<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "top", array(
											"PATH_TO_BASKET" => SITE_DIR."basket/",
											"PATH_TO_ORDER" => SITE_DIR."order/"
											)
										);?>
										</div>
										<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("header-cart", "");?>
										
									</div>
								</div>
								
								<div class="clearfix"></div>
							</td>
						</tr>
					</table>
				</div>
				<div class="bbrands">
	<div class="bbrands__wrap wrapper_inner">
		
		<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/menu.top_brand.php');?>
	</div>
</div>
				<div class="catalog_menu">
					<div class="wrapper_inner">
						<div class="wrapper_middle_menu">
							<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/menu.top_catalog_multilevel.php');?>
							<?//include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/menu.top_general_multilevel.php');?>
						</div>
					</div>
				</div>
			</header>
		</div>
		
		<?if(!$isFrontPage):?>
		<?if($APPLICATION->GetCurPage() != '/basket/'):?>
			<div class="wrapper_inner">                
				<section class="middle">
					<div class="container">
						<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "mshop", array(
							"START_FROM" => "0",
							"PATH" => "",
							"SITE_ID" => "-",
							"SHOW_SUBSECTIONS" => "N"
						),
						false
						);?>
						
						<h1><?=(strpos($APPLICATION->GetCurPage(), '/catalog/')===false)?$APPLICATION->ShowTitle(true):$APPLICATION->ShowViewContent('h1');?></h1>
						<?if($isContactsPage):?>
					</div>
				</section>
			</div>
		
		<?else:?>
			<div id="content">
				<?if(CSite::InDir(SITE_DIR.'company/') || CSite::InDir(SITE_DIR.'info/')):?>
					<div class="left_block">
						<?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
							"ROOT_MENU_TYPE" => "left",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_TIME" => "172800",
							"MENU_CACHE_USE_GROUPS" => "N",
							"MENU_CACHE_GET_VARS" => "",
							"MAX_LEVEL" => "1",
							"CHILD_MENU_TYPE" => "left",
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N" ),
							false, array( "ACTIVE_COMPONENT" => "Y" )
						);?>
					</div>
					
					<div class="right_block">
				<?endif;?>
		<?endif;?>
		<?else:?>
		<div class="wrapper_inner">                
					<div class="container">
					<div id="content">
						<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "mshop", array(
							"START_FROM" => "0",
							"PATH" => "",
							"SITE_ID" => "-",
							"SHOW_SUBSECTIONS" => "N"
						),
						false
						);?>
						
						<h1><?=(strpos($APPLICATION->GetCurPage(), '/catalog/')===false)?$APPLICATION->ShowTitle(true):$APPLICATION->ShowViewContent('h1');?></h1>
						
		<?endif;?>
		
		
		<?endif;?>
		
		<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();
		
		
		*/?>