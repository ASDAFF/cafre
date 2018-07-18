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
<h1><?=(strpos($APPLICATION->GetCurPage(), '/catalog/')===false)?$APPLICATION-https://test.cafre.ru/catalog/?clear_cache=y&amp=y#development=1>ShowTitle(true):$APPLICATION->ShowViewContent('h1');?></h1>