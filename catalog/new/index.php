<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новинки");?>
<h1>Новинки</h1>
<?
global $hits;
$hits['PROPERTY_HIT']=313;
$APPLICATION->AddChainItem('Новинки', '/catalog/new/');
$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					'catalog_block',
					Array(
					"SHOW_ALL_WO_SECTION"=>"Y",
						"IBLOCK_TYPE" => 'new_cat',
						"IBLOCK_ID" => 26,
						"AJAX_REQUEST" => 'N',
						
						"ELEMENT_SORT_FIELD" => "PROPERTY_KOL_SORT_CAT",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "PROPERTY_SUM_PROD",
		"ELEMENT_SORT_ORDER2" => "desc",
						"FILTER_NAME" => 'hits',
						"INCLUDE_SUBSECTIONS" => 'Y',
						"USE_FILTER" => 'Y',
						"PAGE_ELEMENT_COUNT" => 20,
						"PROPERTY_CODE" => array(
			0 => "BRAND",
			1 => "SUM_PROD",
			2 => "k_350",
			3 => "k_330",
			4 => "k_345",
			5 => "k_351",
			6 => "k_331",
			7 => "k_334",
			8 => "k_333",
			9 => "k_326",
			10 => "k_335",
			11 => "k_332",
			12 => "PROP_162",
			13 => "PROP_2065",
			14 => "PROP_2054",
			15 => "PROP_2017",
			16 => "PROP_2055",
			17 => "PROP_2069",
			18 => "PROP_2062",
			19 => "PROP_2061",
			20 => "CML2_LINK",
			21 => "MORE_PHOTO",
			22 => "ON_PHOT",
			23 => "",
		),
						"OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "DETAIL_TEXT",
			3 => "CML2_LINK",
			4 => "DETAIL_PAGE_URL",
			5 => "",
		),
						"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "SIZES",
			2 => "COLOR_REF",
			3 => "",
		),
		
						'OFFER_TREE_PROPS' => array(),
						
						"BASKET_URL" => "/basket/",
						"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
						"CACHE_TYPE" =>'A',
						"CACHE_TIME" => '3600',
						"CACHE_GROUPS" => 'N',
						"BROWSER_TITLE" => 'N',
						"ADD_SECTIONS_CHAIN" => 'N',
						"HIDE_NOT_AVAILABLE" => 'N',
						"PRICE_CODE" => array(
			0 => "BASE",
			1 => "OPT",
			2 => "Интернет Розница",
		),
						"PAGER_TEMPLATE" => "main",
						
					)
				);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>