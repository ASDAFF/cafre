<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?if($arResult):?>
	<a class="mobile-nav__open-link"><i class="icon"></i></a>

	<ul class="mobile-nav__items">
		<?foreach($arResult as $arItem):?>
			<li class="mobile-nav__item <?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["LINK"] == $arParams["IBLOCK_CATALOG_DIR"] ? ' catalog' : '')?>">
				<a href="<?=$arItem["LINK"]?>" class="mobile-nav__link"><span><?=$arItem["TEXT"]?></span></a>

				<?if($arItem["IS_PARENT"] == 1):?>
					<div class="mobile-submenu">
						<div class="mobile-submenu__inner">
							<?foreach($arItem["CHILD"] as $arSubItem):?>
								<a class="mobile-submenu__link <?=($arSubItem["SELECTED"] ? ' current' : '')?>" href="<?=$arSubItem["LINK"]?>"><?=$arSubItem["TEXT"]?></a>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
				
				<?if($arItem["LINK"] == "/catalog/"):?>
					<ul class="mobile-nav__catalog-l1">
						<?$APPLICATION->IncludeComponent(
								"bitrix:catalog.section.list",
								"top_menu",
								Array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_CATALOG_ID"],
									"SECTION_ID" => "",
									"SECTION_CODE" => "",
									"COUNT_ELEMENTS" => "N",
									"TOP_DEPTH" => "1",
									"SECTION_FIELDS" => array(0 => "",1 => "",),
									"SECTION_USER_FIELDS" => array(0 => "",1 => "",),
									"SECTION_URL" => "",
									"CACHE_TYPE" => "N",
									"CACHE_TIME" => "86400",
									"URL" => $_SERVER["REQUEST_URI"],
									"CACHE_GROUPS" => "N",
									"ADD_SECTIONS_CHAIN" => "N"
								)
							);?>
					</ul>
				<?endif;?>
			</li>
		<?endforeach;?>
					
		<!-- <li class="mobile-nav__stretch"></li> -->
	
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