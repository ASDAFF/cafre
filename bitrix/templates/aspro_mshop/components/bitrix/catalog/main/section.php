<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);

if(count($_GET)>1 && isset($_GET['_escaped_fragment_'])) {
	CHTTP::SetStatus("404 Not Found");	
}
else {
?>
<?$arParams["ADD_SECTIONS_CHAIN"] = (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : "Y");
CModule::IncludeModule("iblock");

if($arResult['VARIABLES']['SECTION_CODE_PATH']!='' && $arResult["VARIABLES"]["SECTION_ID"] == 0) {
	$res = CIBlockElement::GetList(Array(), array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "CODE"=>$arResult['VARIABLES']['SECTION_CODE_PATH']), false, false, array("ID")); 
	$kol=intval($res->SelectedRowsCount());
}
else $kol=0;
if($kol>0) {
	include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/element.php");	
}
else {
	//start section
	global ${$arParams['FILTER_NAME']};	
	$brends=true;
	
	// get current section ID
	global $TEMPLATE_OPTIONS, $MShopSectionID;
	$arPageParams = $arSection = $section = array();

	if($arResult["VARIABLES"]["SECTION_ID"] > 0){
		$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("CODE", "ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID", "UF_RUSNAME","UF_SECTION_DESCR", $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID", "UF_RATINGVALUE", "UF_RATINGCOUNT", "UF_SEO_TITLE", "UF_SEO_DESC", "UF_SEO_H", "UF_FILT_H"));
		$section = $db_list->GetNext();
	}
	elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0){
		$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("CODE", "ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID", "UF_RUSNAME", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID", "UF_SEO_TITLE", "UF_SEO_DESC", "UF_SEO_H", "UF_FILT_H"));
		$section = $db_list->GetNext();
	}
	else {
		$array_brand_sec = '';
		$sections=explode('/', $arResult['VARIABLES']['SECTION_CODE_PATH']);	
		$end=false;
		foreach($sections as $i=>$path) {		
			if(strpos($path, 'f-')===0) $end=true;
			if($end) unset($sections[$i]);
		}
		$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $sections[count($sections)-2], 
			"IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("CODE", "ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID","SECTION_PAGE_URL", "UF_RUSNAME","UF_SECTION_DESCR", 
		$arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID", "UF_SEO_TITLE", "UF_SEO_DESC", "UF_SEO_H", "UF_FILT_H"));
		while($section2 = $db_list->GetNext()) {
			if($section2['SECTION_PAGE_URL'].$sections[count($sections)-1].'/'=='/catalog/'.implode('/', $sections).'/') {
				$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),array("IBLOCK_ID" => $arParams["IBLOCK_ID"], 'ACTIVE'=>'Y','CODE'=>$sections[count($sections)-1]));
				if ($section = $rsSect->GetNext()) {
					if($section['IBLOCK_SECTION_ID']==5338) {
						$brends=false;
						$arResult["VARIABLES"]["SECTION_ID"]=$section2['ID'];
						$arResult["VARIABLES"]["SECTION_CODE"]=$section2['CODE'];
						${$arParams['FILTER_NAME']}['=PROPERTY_250'][]=$section['ID'];	
						$arResult["VARIABLES"]["SMART_FILTER_PATH"]=str_replace(implode('/', $sections), '', $arResult["VARIABLES"]['SECTION_CODE_PATH']). '/catalog_brend-is-'.$section['CODE'] ;
						if(strpos($arResult["VARIABLES"]["SMART_FILTER_PATH"], '/f-')===0) $arResult["VARIABLES"]["SMART_FILTER_PATH"]=substr($arResult["VARIABLES"]["SMART_FILTER_PATH"], 3);
						$array_brand_sec.= $section['CODE'];
						$section=$section2;
					}
					else $section=false;
				}				
			}		
		}
	}

	if($section){
		$arSection["ID"] = $section["ID"];
		$arSection["NAME"] = $section["NAME"];
		$arSection["CODE"] = $section["CODE"];
		$arSection["RUSNAME"] = $section["UF_RUSNAME"];
		$arSection["IBLOCK_SECTION_ID"] = $section["IBLOCK_SECTION_ID"];
	
		if($arSection["IBLOCK_SECTION_ID"]==5338) {
			${$arParams['FILTER_NAME']}['=PROPERTY_250']=$arSection['ID'];
			$arResult["VARIABLES"]["SECTION_ID"]=0;
			$arResult["VARIABLES"]["SECTION_CODE"]='';	
		}
	
		if($section[$arParams["SECTION_DISPLAY_PROPERTY"]]){
			$arDisplayRes = CUserFieldEnum::GetList(array(), array("ID" => $section[$arParams["SECTION_DISPLAY_PROPERTY"]]));
			if($arDisplay = $arDisplayRes->GetNext()){
				$arSection["DISPLAY"] = $arDisplay["XML_ID"];
			}
		}
		$arSection["SEO_DESCRIPTION"] = $section[$arParams["SECTION_PREVIEW_PROPERTY"]];
		if(strlen($section["DESCRIPTION"]))
			$arSection["DESCRIPTION"] = $section["~DESCRIPTION"];
		if(strlen($section["UF_SECTION_DESCR"]))
			$arSection["UF_SECTION_DESCR"] = $section["UF_SECTION_DESCR"];  
		$APPLICATION->SetPageProperty("title", $section[$arParams["LIST_BROWSER_TITLE"]]);
		$APPLICATION->SetPageProperty("keywords", $section[$arParams["LIST_META_KEYWORDS"]]);
		$APPLICATION->SetPageProperty("description", $section[$arParams["LIST_META_DESCRIPTION"]]);
		$iSectionsCount = CIBlockSection::GetCount(array("SECTION_ID" => $arSection["ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"));
		$posSectionDescr = COption::GetOptionString("aspro.mshop", "SHOW_SECTION_DESCRIPTION", "BOTTOM", SITE_ID);
	}
	else CHTTP::SetStatus("404 Not Found");
	$MShopSectionID = $arSection["ID"];
	
	if($arResult["VARIABLES"]["SECTION_ID"]==5338){
		include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/brands.php");
	}else{
		include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/menu.php");?>		
		<div class="right_block clearfix catalog" id="right_block_ajax">
		
			<? 
			// && (empty($MSHOP_SMART_FILTER) || (count($MSHOP_SMART_FILTER)==1 && isset($MSHOP_SMART_FILTER['FACET_OPTIONS'])))
			if($brends && strpos($APPLICATION->GetCurPage(), '/catalog/vse_brendy/')===false)  {
				$APPLICATION->ShowViewContent('filter_dop');		
			}
			if(empty(${$arParams['FILTER_NAME']}) || $arSection["IBLOCK_SECTION_ID"]==5338) {
				$ar_result=CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"26", "ID"=>$arSection["ID"]),false, Array("UF_IMG_BRAND", "UF_TOP_TEXT", "UF_TEXT_BRAND_TOP"));?>	
				<?if($res2=$ar_result->GetNext()):?>
					<?if($res2["UF_IMG_BRAND"]){?>
						<?$file = CFile::ResizeImageGet($res2["UF_IMG_BRAND"], array('width'=>266, 'height'=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
						<div class="top_brand_block" style="min-height: 70px;margin-top:0;">
							<div class="img_brand_sec"><img src="<?=$file["src"];?>" alt=""/></div>
							<div class="text_brand_sec"><?echo $res2["~UF_TOP_TEXT"];?></div>
						</div>
					<?}?>
				<?endif;
			}else{
				$exp_mas = explode("/", $APPLICATION->GetCurPage());
				$fruit30 = array_pop($exp_mas);
				$ar_result=CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>30, "PROPERTY_RAZ_D"=>$arSection["ID"], "XML_ID"=>array_pop($exp_mas)),false, Array("XML_ID", "NAME"));
				if($res2=$ar_result->GetNext()):
					if($res2["PREVIEW_TEXT"]){
						$exbsec = explode('catalog_brend-is-',$arResult["VARIABLES"]["SMART_FILTER_PATH"]);
						if($exbsec[1]){
							$arFilter_bs = array("IBLOCK_ID"=>26, "=CODE"=>$exbsec[1]); // выберет потомков без учета активности
							$rsSect_bs = CIBlockSection::GetList(array(),$arFilter_bs, false, array("UF_IMG_BRAND"));
						}  ?>
						<div class="top_brand_block" style="min-height: 70px;margin-top:0;">
							<?if($arSect_bs = $rsSect_bs->GetNext()):
								$file = CFile::ResizeImageGet($arSect_bs["UF_IMG_BRAND"], array('width'=>266, 'height'=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);	?>
								<div class="img_brand_sec"><img src="<?=$file["src"];?>" alt=""/></div>
							<?endif;?>
							<div class="text_brand_sec"><?echo $res2["PREVIEW_TEXT"];?></div>
						</div>
					<?}?>
				<?else:?>
					<?$exbsec = explode('catalog_brend-is-',$arResult["VARIABLES"]["SMART_FILTER_PATH"]);
					if($exbsec[1]){
						$arFilter_bs = array("IBLOCK_ID"=>26, "=CODE"=>$exbsec[1]); // выберет потомков без учета активности
						$rsSect_bs = CIBlockSection::GetList(array(),$arFilter_bs, false, array("UF_IMG_BRAND"));
						if ($arSect_bs = $rsSect_bs->GetNext())  {?>
							<?$file = CFile::ResizeImageGet($arSect_bs["UF_IMG_BRAND"], array('width'=>266, 'height'=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
							<div class="top_brand_block" style="min-height: 70px;margin-top:0;">
								<div class="img_brand_sec"><img src="<?=$file["src"];?>" alt=""/></div>
							</div>
						<?}
					}?>
				<?endif;
			}?>
			<?$isAjax="N";?>
			<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"  && isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y" || (isset($_GET["ajax_basket"]) && $_GET["ajax_basket"]=="Y")){
				$isAjax="Y";
			}?>
			<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["ajax_get_filter"]) && $_GET["ajax_get_filter"] == "Y" ){
				$isAjaxFilter="Y";
			}?>
			<?if($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]=="HORIZONTAL"){			?>
				<div class="filter_horizontal">
					<?include_once("filter.php")?>
				</div>
			<?}?>
			
			<div class="inner_wrapper">
			<!--startsort-->
				<?if($posSectionDescr=="TOP"){?>
					<?if ($arSection["DESCRIPTION"]):?>
						<div class="group_description_block top">
							<div><?=$arSection["DESCRIPTION"]?></div>
						</div>
					<?elseif($arSection["UF_SECTION_DESCR"]):?>
						<div class="group_description_block top">
							<div><?=$arSection["UF_SECTION_DESCR"]?></div>
						</div>
					<?endif;?>
				<?}?>
                    
				<?if('Y' == $arParams['USE_FILTER']):?>
					<div class="adaptive_filter">
						<a class="filter_opener<?=($_REQUEST["set_filter"] == "y" ? " active" : "")?>"><i></i><span><?=GetMessage("CATALOG_SMART_FILTER_TITLE")?></span></a>
					</div>
					<script type="text/javascript">
					$(document).on('click','.filter_opener',function(){
						//$(".filter_opener").click(function(){
						$(this).toggleClass("opened");
						$(".bx_filter_vertical, .bx_filter").slideToggle(333);
					});
					</script>
				<?endif;?>

				<?if($isAjax=="N"){
					$frame = new \Bitrix\Main\Page\FrameHelper("viewtype-block");
					$frame->begin();
				}
				$arDisplays = array("block", "list", "table");
				if(array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]){
					if($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))){
						$display = trim($_REQUEST["display"]);
						$_SESSION["display"]=trim($_REQUEST["display"]);
					}
					elseif($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))){
						$display = $_SESSION["display"];
					}
					elseif($arSection["DISPLAY"]){
						$display = $arSection["DISPLAY"];
					}
					else{
						$display = $arParams["DEFAULT_LIST_TEMPLATE"];
					}
				}
				else{
					$display = "block";
				}
				$template = "catalog_".$display;
                
                $arFilter = Array('IBLOCK_ID'=>$arParams['IBLOCK_ID'],'ID'=>$arResult["VARIABLES"]["SECTION_ID"], 'GLOBAL_ACTIVE'=>'Y');
                $db_list = CIBlockSection::GetList(Array("timestamp_x"=>"DESC"), $arFilter, false, Array("UF_SEO_TEXT"));
                if($path_seo_text = $db_list->GetNext()):
                endif;?>

                <div class="section-seo-text"><?=$path_seo_text["UF_SEO_TEXT"];?></div><br>
				
				
				<div class="sort_header view_<?=$display?>">
					<!--noindex-->
					<div class="sort_filter" >
						<?	
						$arAvailableSort = array();
						$arAvailableSort["PRICE"] = array("PROPERTY_MIN_PRICE", "desc", 'по цене'); 
						$arAvailableSort["ID"] = array("ID", "desc", 'по новизне');
						$arAvailableSort["POPULAR"] = array("PROPERTY_ZZ_COUNT", "asc", 'по популярности');
						
						if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || 
							(array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]){
							if($_REQUEST["sort"]){
								$sort = ToUpper($_REQUEST["sort"]); 
								$_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
							}
							elseif($_SESSION["sort"]){
								$sort = ToUpper($_SESSION["sort"]);
							}
							else{
								$sort = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
							}
						}

						$sort_order=$arAvailableSort[$sort][1];
						if((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ) || $arParams["ELEMENT_SORT_ORDER"]){
							if($_REQUEST["order"]){
								$sort_order = $_REQUEST["order"];
								$_SESSION["order"] = $_REQUEST["order"];
							}
							elseif($_SESSION["order"]){
								$sort_order = $_SESSION["order"];
							}
							else{
								$sort_order = ToLower($arParams["ELEMENT_SORT_ORDER"]);
							}
						}
						?>
						<?foreach($arAvailableSort as $key => $val):?>
							<?$newSort = ($key == 'POPULAR'&&$key!=$sort)?$sort_order:($sort_order == 'desc' ? 'asc' : 'desc');?>
							<a href="<?=$APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'))?>" class="sort_btn <?=($sort == $key ? 'current' : '')?> <?=$sort_order?>" rel="nofollow">
								<i class="icon" ></i><span><?=$val[2]?></span><i class="arr"></i>
							</a>
						<?endforeach;
						if($sort=='PRICE') $sort="PROPERTY_MIN_PRICE";
						if($sort=='POPULAR') $sort="PROPERTY_ZZ_COUNT";?>
					</div>
					<!--/noindex-->
				</div>
				
				
				<?if($isAjax=="Y"){
					$APPLICATION->RestartBuffer();
				}
				$show = $arParams["PAGE_ELEMENT_COUNT"];
				if($isAjax=="N"){?>
					<div class="ajax_load <?=$display;?>">
				<?}
				global $itemcount;
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					$template,
					Array(
					"SHOW_ALL_WO_SECTION"=>$arResult["VARIABLES"]["SECTION_ID"]||$arSection["IBLOCK_SECTION_ID"]==5338?"Y":"N",
						"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
						"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"BASKET_ITEMS" => $arBasketItems,
						"ELEMENT_SORT_FIELD" => $sort,
						"AJAX_REQUEST" => $isAjax,
						// "AJAX_REQUEST_FILTER" => $isAjaxFilter,
						"ELEMENT_SORT_ORDER" => $sort_order,
						/*"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
						"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],*/
						"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
						"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
						"PAGE_ELEMENT_COUNT" =>(isset($_GET['_escaped_fragment_']) && $_GET['_escaped_fragment_']=='')?'20000000':$show,
						"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
						"DISPLAY_TYPE" => $display,
						"TYPE_SKU" => $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"],
						"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
						"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
						"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
						"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
						"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
						"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
						"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
						'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
						"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
						"AJAX_MODE" => $arParams["AJAX_MODE"],
						"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
						"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
						"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
						"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
						"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
						"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
						"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
						"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
						"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
						"SET_TITLE" => $arParams["SET_TITLE"],
						"SET_STATUS_404" => $arParams["SET_STATUS_404"],
						"SHOW_404" => $arParams["SHOW_404"],
						"CACHE_FILTER" => $arParams["CACHE_FILTER"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
						"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
						"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
						"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
						"PAGER_TITLE" => $arParams["PAGER_TITLE"],
						"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
						"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
						"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
						"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
						"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
						"AJAX_OPTION_ADDITIONAL" => "",
						"ADD_CHAIN_ITEM" => "N",
						"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
						"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
						"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
						"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
						"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
						"CURRENCY_ID" => $arParams["CURRENCY_ID"],
						"USE_STORE" => $arParams["USE_STORE"],
						"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
						"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
						"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
						"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
						"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
						"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
						"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
						"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
						"SHOW_HINTS" => $arParams["SHOW_HINTS"],
						"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
						"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
						"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
						"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
						"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                        "SECTION_USER_FIELDS" => array("UF_SEO_TEXT"), //вывод пользовательского свойства раздела краткое описение
					), $component, array("HIDE_ICONS" => $isAjax)
				);
				//$activeElements = CIBlockSection::GetSectionElementsCount($arResult["VARIABLES"]["SECTION_ID"], Array("CNT_ACTIVE"=>"Y"));
				//print_r($arResult["VARIABLES"]["SECTION_ID"]);
				$res = CIBlockSection::GetByID($arResult["VARIABLES"]["SECTION_ID"]);
				if($ar_res = $res->GetNext()){
				if($ar_res["DEPTH_LEVEL"] == 1){
				$fater_raz = 'none';
				}else{
				$fater_raz = $ar_res['IBLOCK_SECTION_ID'];
				}
				}
				/*if($array_brand_sec){
					$res2 = CIBlockSection::GetByID($fater_raz);
				if($ar_res2 = $res2->GetNext()){
				$fater_raz = $ar_res2['IBLOCK_SECTION_ID'];
				}
				}*/
				//print_r($fater_raz);
				if(($itemcount <= 5) && !strpos($APPLICATION->GetCurPage(),'vse_brendy') && ($fater_raz != 'none')){
				echo '<div class="rec-sec">
				<div class="top_block">
								<div class="title_block">Вас может заинтересовать</div>
			</div>';
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					$template,
					Array(
					"SHOW_ALL_WO_SECTION"=>$arResult["VARIABLES"]["SECTION_ID"]||$arSection["IBLOCK_SECTION_ID"]==5338?"Y":"N",
						"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"SECTION_ID" => $fater_raz,
						//"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"BASKET_ITEMS" => $arBasketItems,
						"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
						"AJAX_REQUEST" => $isAjax,
						// "AJAX_REQUEST_FILTER" => $isAjaxFilter,
						"ELEMENT_SORT_ORDER" => "asc",
						//"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
						"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
						"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
						"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
						"PAGE_ELEMENT_COUNT" => 8,
						"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
						"DISPLAY_TYPE" => $display,
						"TYPE_SKU" => $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"],
						"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
						"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
						"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
						"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
						"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
						"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
						"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
						'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
						"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
						"AJAX_MODE" => $arParams["AJAX_MODE"],
						"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
						"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
						"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
						"CACHE_TYPE" =>'N', //$arParams["CACHE_TYPE"]
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
						"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
						"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
						"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
						"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
						"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
						"SET_TITLE" => $arParams["SET_TITLE"],
						"SET_STATUS_404" => $arParams["SET_STATUS_404"],
						"SHOW_404" => $arParams["SHOW_404"],
						"CACHE_FILTER" => $arParams["CACHE_FILTER"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
						"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
						"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => $arParams["PAGER_TITLE"],
						"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
						"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
						"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
						"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
						"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
						"AJAX_OPTION_ADDITIONAL" => "",
						"ADD_CHAIN_ITEM" => "N",
						"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
						"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
						"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
						"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
						"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
						"CURRENCY_ID" => $arParams["CURRENCY_ID"],
						"USE_STORE" => $arParams["USE_STORE"],
						"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
						"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
						"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
						"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
						"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
						"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
						"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
						"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
						"SHOW_HINTS" => $arParams["SHOW_HINTS"],
						"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
						"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
						"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
						"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
						"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                        "SECTION_USER_FIELDS" => array("UF_SEO_TEXT"), //вывод пользовательского свойства раздела краткое описение
					), $component, array("HIDE_ICONS" => $isAjax)
				);
				echo '</div>';
				}
				if($isAjax=="N"){?>
					<div class="clear"></div>
					</div>
				<?}?>
				<?if($isAjax=="Y") {
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.plugin.min.js',true);
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown.min.js',true);
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown-ru.js',true);
				}?>
				<?if($isAjax!="Y"){?>
					<?$frame->end();?>
				<?}?>
				<?if($isAjax=="Y"){
					die();
				}?>
				<!--endsort-->
			</div>
			
		</div>
		<?
		$basketAction='';
		if($arParams["SHOW_TOP_ELEMENTS"]!="N"){
			if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y'){
				$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
			}else{
				$basketAction = (isset($arParams['TOP_ADD_TO_BASKET_ACTION']) ? $arParams['TOP_ADD_TO_BASKET_ACTION'] : '');
			}
		}
		include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/text_bottom.php");	?>
		<div class="block_viewed" data-basketaction="<?=$basketAction;?>" data-topsecid="<?=$section["ID"];?>"></div>
		<?
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
		$ElementOfferIblockID = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
		?>
		<!--<div class="block_bigdata" data-detailurl="<?//=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"];?>" data-secid="<?//=$arResult["VARIABLES"]["SECTION_ID"];?>" data-seccode="<?//=$arResult["VARIABLES"]["SECTION_CODE"];?>" data-secelemid="<?//=$arResult["VARIABLES"]["SECTION_ID"];?>" data-secelemcode="<?//=$arResult["VARIABLES"]["SECTION_CODE"];?>" data-id="<?//=$ElementID;?>" data-elementofferiblockid="<?//=$ElementOfferIblockID;?>"></div>-->
		<?$APPLICATION->IncludeComponent("bitrix:catalog.bigdata.products", "mshop", array(
	"LINE_ELEMENT_COUNT" => 5,
	"TEMPLATE_THEME" => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
	"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
	"BASKET_URL" => $arParams["BASKET_URL"],
	"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action")."_cbdp",
	"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
	"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
	"ADD_PROPERTIES_TO_BASKET" => "N",
	"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
	"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
	"SHOW_OLD_PRICE" => $arParams['SHOW_OLD_PRICE'],
	"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
	"PRICE_CODE" => $arParams["PRICE_CODE"],
	"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
	"PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
	"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
	"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
	"SHOW_NAME" => "Y",
	"SHOW_IMAGE" => "Y",
	"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
	"MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
	"MESS_BTN_DETAIL" => $arParams['MESS_BTN_DETAIL'],
	"MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
	"MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
	"PAGE_ELEMENT_COUNT" => 10,
	"SHOW_FROM_SECTION" => "N",
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"DEPTH" => "2",
	"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
	"CACHE_TIME" => '120',//$arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
	"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => $arParams['ADD_PICT_PROP'],
	"LABEL_PROP_".$arParams["IBLOCK_ID"] => "-",
	"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
	"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
	"CURRENCY_ID" => $arParams["CURRENCY_ID"],
	"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
	"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"SECTION_ELEMENT_ID" => $arResult["VARIABLES"]["SECTION_ID"],
	"SECTION_ELEMENT_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"ID" => $ElementID,
	"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
	"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => $arParams["PRODUCT_PROPERTIES"],
	"RCM_TYPE" => (isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : ''),
	"OFFER_TREE_PROPS_".$ElementOfferIblockID => $arParams["OFFER_TREE_PROPS"],
	"ADDITIONAL_PICT_PROP_".$ElementOfferIblockID => $arParams['OFFER_ADD_PICT_PROP'],
	"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
	"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
	),
	false,
	array("HIDE_ICONS" => "Y")
		);?>
		<script type="text/javascript">
		$(".number_list a:not(.current)").on("click", function() {
			$(this).addClass("current").siblings().removeClass("current");
		});
		</script>
		<?include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/schema.php");	
	}
	include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/seo.php");					
}
// end section 
}
?>