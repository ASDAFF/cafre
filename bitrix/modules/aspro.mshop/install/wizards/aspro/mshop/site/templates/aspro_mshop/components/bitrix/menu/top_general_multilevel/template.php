<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult):?>
	<ul class="menu adaptive">
		<li class="menu_opener"><a><?=GetMessage('MENU_NAME')?></a><i class="icon"></i></li>
	</ul>
	<ul class="menu full">
		<?foreach($arResult as $arItem):?>
			<li class="menu_item_l1 <?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] ? ' catalog' : '')?>">
				<a href="<?=$arItem["LINK"]?>">
					<span><?=$arItem["TEXT"]?></span>
				</a>
				<?if($arItem["IS_PARENT"] == 1):?>
					<div class="child submenu line">
						<div class="child_wrapp">
							<?foreach($arItem["CHILD"] as $arSubItem):?>
								<a class="<?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				<?if($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"]):?>222
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section.list",
						"top_menu",
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
		<?endforeach;?>
		<li class="stretch"></li>
		<li class="search_row">
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
	<?global $TEMPLATE_OPTIONS;?>
	<div class="search_middle_block">
		<?$APPLICATION->IncludeComponent("bitrix:search.title", ( strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"])=="fly" ? "catalog" : "mshop"), array(
			"NUM_CATEGORIES" => "1",
			"TOP_COUNT" => "5",
			"ORDER" => "date",
			"USE_LANGUAGE_GUESS" => "Y",
			"CHECK_DATES" => "Y",
			"SHOW_OTHERS" => "N",
			"PAGE" => $arParams["IBLOCK_CATALOG_DIR"],
			"CATEGORY_0_TITLE" => GetMessage("CATEGORY_PRODUÑTCS_SEARCH_NAME"),
			"CATEGORY_0" => array(
				0 => 'iblock_'.$arParams["IBLOCK_CATALOG_TYPE"],
			),
			"CATEGORY_0_iblock_".$arParams["IBLOCK_CATALOG_TYPE"] => array(
				0 => $arParams["IBLOCK_CATALOG_ID"],
			),
			"SHOW_INPUT" => "Y",
			"INPUT_ID" => "title-search-input2",
			"CONTAINER_ID" => "title-search2",
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"PRICE_VAT_INCLUDE" => "Y",
			"SHOW_ANOUNCE" => "N",
			"PREVIEW_TRUNCATE_LEN" => "50",
			"SHOW_PREVIEW" => "Y",
			"PREVIEW_WIDTH" => "38",
			"PREVIEW_HEIGHT" => "38",
			"CONVERT_CURRENCY" => "N"
			),
			false,
			array(
			"HIDE_ICONS" => "Y"
			)
		);?>
	</div>
	<div class="search_block">
		<span class="icon"></span>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$("ul.menu.adaptive .menu_opener").click(function(){
			$(this).parents(".menu.adaptive").toggleClass("opened");
			$("ul.menu.full").toggleClass("opened").slideToggle(200);
		});
		
		$(".main-nav .menu > li:not(.current):not(.menu_opener) > a").click(function(){
			$(this).parents("li").siblings().removeClass("current");
			$(this).parents("li").addClass("current");
		});
		
		$(".main-nav .menu .child_wrapp a").click(function(){
			$(this).siblings().removeClass("current");
			$(this).addClass("current");
		});
	});
	</script>
<?endif;?>