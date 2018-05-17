<div class="left_block catalog <?=strtolower($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"])?>">
	<?if(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy')===false) {
		$nav = CIBlockSection::GetNavChain(false, $arResult['VARIABLES']['SECTION_ID']);
		while($section_p = $nav->GetNext()) {
			if(!(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy/')===false)) {
				if($section_p['DEPTH_LEVEL']==2) {$parent_sec = $section_p['ID'];}		
			}
			else {
				if($section_p['DEPTH_LEVEL']==1) {$parent_sec = $section_p['ID'];}		
			}
		}
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			"brend_list",
			Array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				//"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				"SECTION_ID" => $parent_sec,				
				"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
				"ADD_SECTIONS_CHAIN" => "N",
				"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
				//"OPENED" => $_COOKIE["KSHOP_internal_sections_list_OPENED"],
				"TOP_DEPTH" => "4",
				"SECTION_BRAND_CAT"=>strtoupper($array_brand_sec),
			),$component
		);
	}
	else {
		CModule::IncludeModule('iblock');
		$arFilter = Array("IBLOCK_ID"=>29, 'NAME'=>$arSection["ID"]);
		$res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, array('ID', 'DETAIL_TEXT'));
		$allgroup=array();
		if($ob = $res->GetNextElement())	{
			$element=$ob->GetFields();
			$menu=unserialize($element['~DETAIL_TEXT']);?>
			<div class="internal_sections_list">
			<?foreach($menu['PARENT'] as $razdel):?>
				<h4><a href="/catalog/<?=$razdel['CODE']?>/<?=$arSection['CODE']?>/"><?=$razdel['NAME']?></a></h4>
				<ul class="sections_list_wrapp">
					<?$prev=0;
					foreach($menu['CHILD'][$razdel['ID']]['ITEMS'] as $item) {
						$bParent=count($menu['CHILD'][$item['ID']]['ITEMS']);		?>
						<li class="item <?=(false ? "cur" : "")?>" >
							<a href="/catalog/<?=$razdel['CODE']?>/<?=$item['CODE']?>/<?=$arSection['CODE']?>/" class="<?=($bParent ? 'parent' : '')?>"><span><?=$item["NAME"]?></span></a>
							<?if($bParent):?>
								<div class="child_container">
									<div class="child_wrapp">
										<ul class="child">
											<?foreach($menu['CHILD'][$item['ID']]['ITEMS'] as $SubArSection):?>
												<li class="menu_item <?=(false ? "cur" : "")?>" ><a href="/catalog/<?=$razdel['CODE']?>/<?=$item['CODE']?>/<?=$SubArSection['CODE']?>/<?=$arSection['CODE']?>/"><?=$SubArSection["NAME"]?></a></li>
											<?endforeach;?>
										</ul>
									</div>
								</div>
							<?endif;?>
						</li>
					<?} ?>			
				</ul>
			<?endforeach;?>				
			</div>
			<script>
					$(".internal_sections_list").ready(function(){
						$(".internal_sections_list .title .inner_block").click(function(){ 
							$(this).find('.hider').toggleClass("opened");
							$(this).closest(".internal_sections_list").find(".title").toggleClass('opened');
							$(this).closest(".internal_sections_list").find(".sections_list_wrapp").slideToggle(200); 
							$.cookie.json = true;			
							$.cookie("MSHOP_internal_sections_list_HIDE", $(this).find('.hider').hasClass("opened"),{path: '/',	domain: '',	expires: 360});
						});
						if($.cookie("MSHOP_internal_sections_list_HIDE") == 'false'){
							$(".internal_sections_list .title").removeClass("opened");
							$(".internal_sections_list .title .hider").removeClass("opened");
							//$(".internal_sections_list .sections_list_wrapp").hide();
						}
						$('.left_block .internal_sections_list li.item > a.parent').click(function(e) {
							e.preventDefault();
							$(this).parent().find('.child_container').slideToggle();
						});
					});
			</script>
		<?}
	}	
	if($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]=="VERTICAL"&& $section['IBLOCK_SECTION_ID']!=5338){
		include_once("filter.php");
	}?>
	<div class="deliverybanner">
		<img src="https://test.cafre.ru/bitrix/templates/aspro_mshop/images/banners/cafre_Mail_257х631.jpg" alt="Бесплатная доставка от 2000 руб."/>
	</div>
</div>