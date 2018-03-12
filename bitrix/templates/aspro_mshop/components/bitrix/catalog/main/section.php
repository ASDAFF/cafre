<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?$arParams["ADD_SECTIONS_CHAIN"] = (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : "Y");
CModule::IncludeModule("iblock");

global ${$arParams['FILTER_NAME']};	
$brends=true;
// get current section ID
global $TEMPLATE_OPTIONS, $MShopSectionID;
$arPageParams = $arSection = $section = array();

if($arResult["VARIABLES"]["SECTION_ID"] > 0){
	$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("CODE", "ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID", "UF_RUSNAME","UF_SECTION_DESCR", $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID", "UF_RATINGVALUE", "UF_RATINGCOUNT"));
	$section = $db_list->GetNext();
	/*if($section['IBLOCK_SECTION_ID']==5538&&strpos($APPLICATION->GetCurPage(), 'vse_brendy/')===false) {
		$brends=false;
		$sections=explode('/', $arResult['VARIABLES']['SECTION_CODE_PATH']);
		$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $sections[count($sections)-2], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID","SECTION_PAGE_URL", "UF_RUSNAME","UF_SECTION_DESCR", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID"));
		while($section = $db_list->GetNext()) {
			if($section['SECTION_PAGE_URL'].$sections[count($sections)-1].'/'==$APPLICATION->GetCurPage()&&$section['IBLOCK_SECTION_ID']==5338) {
				$arResult["VARIABLES"]["SECTION_ID"]=$section['ID'];
				$arResult["VARIABLES"]["SECTION_CODE"]=$section['CODE'];
				$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),array("IBLOCK_ID" => $arParams["IBLOCK_ID"], 'ACTIVE'=>'Y','SECTION_ID'=>5538, 'CODE'=>$sections[count($sections)-1]));
				if ($arSect = $rsSect->GetNext())
				{
					${$arParams['FILTER_NAME']}['=PROPERTY_250'][]=$arSect['ID'];
				}
				unset($sections[count($sections)-1]);
				$arResult["VARIABLES"]["SECTION_CODE_PATH"]=implode('/', $sections);
			}
			else $section=false;			
		}
	}*/
}
elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0){
	$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("CODE", "ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID", "UF_RUSNAME", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID"));
	$section = $db_list->GetNext();
	/*if($section['IBLOCK_SECTION_ID']==5538&&strpos($APPLICATION->GetCurPage(), 'vse_brendy/')===false) {
		$brends=false;
		$sections=explode('/', $arResult['VARIABLES']['SECTION_CODE_PATH']);
		$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $sections[count($sections)-2], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID","SECTION_PAGE_URL", "UF_RUSNAME","UF_SECTION_DESCR", $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID"));
		while($section = $db_list->GetNext()) {
			if($section['SECTION_PAGE_URL'].$sections[count($sections)-1].'/'==$APPLICATION->GetCurPage()&&$section['IBLOCK_SECTION_ID']==5338) {
				$arResult["VARIABLES"]["SECTION_ID"]=$section['ID'];
				$arResult["VARIABLES"]["SECTION_CODE"]=$section['CODE'];
				$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),array("IBLOCK_ID" => $arParams["IBLOCK_ID"],'ACTIVE'=>'Y', 'CODE'=>$sections[count($sections)-1]));
				if ($arSect = $rsSect->GetNext())
				{
					${$arParams['FILTER_NAME']}['=PROPERTY_250'][]=$arSect['ID'];
				}
				unset($sections[count($sections)-1]);
				$arResult["VARIABLES"]["SECTION_CODE_PATH"]=implode('/', $sections);
			}	
			else $section=false;			
		}
	}*/
}
else {
	$sections=explode('/', $arResult['VARIABLES']['SECTION_CODE_PATH']);	
	foreach($sections as $i=>$path) {		
		if(strpos($path, 'f-')===0) unset($sections[$i]);
	}
	$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $sections[count($sections)-2], 
		"IBLOCK_ID" => $arParams["IBLOCK_ID"]), true, array("CODE", "ID", "IBLOCK_ID", "NAME", "DESCRIPTION","IBLOCK_SECTION_ID","SECTION_PAGE_URL", "UF_RUSNAME","UF_SECTION_DESCR", 
		$arParams["SECTION_DISPLAY_PROPERTY"], $arParams["LIST_BROWSER_TITLE"], $arParams["LIST_META_KEYWORDS"], $arParams["LIST_META_DESCRIPTION"], "IBLOCK_SECTION_ID"));
	while($section2 = $db_list->GetNext()) {
		if($section2['SECTION_PAGE_URL'].$sections[count($sections)-1].'/'=='/catalog/'.implode('/', $sections).'/') {
			$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),array("IBLOCK_ID" => $arParams["IBLOCK_ID"], 'ACTIVE'=>'Y','CODE'=>$sections[count($sections)-1]));
			if ($section = $rsSect->GetNext())
			{
				if($section['IBLOCK_SECTION_ID']==5338) {
					$brends=false;
					$arResult["VARIABLES"]["SECTION_ID"]=$section2['ID'];
					$arResult["VARIABLES"]["SECTION_CODE"]=$section2['CODE'];
					${$arParams['FILTER_NAME']}['=PROPERTY_250'][]=$section['ID'];	
					$arResult["VARIABLES"]["SMART_FILTER_PATH"]=
						str_replace(implode('/', $sections), '', $arResult["VARIABLES"]['SECTION_CODE_PATH']). '/catalog_brend-is-'.$section['CODE'] ;
					if(strpos($arResult["VARIABLES"]["SMART_FILTER_PATH"], '/f-')===0) $arResult["VARIABLES"]["SMART_FILTER_PATH"]=substr($arResult["VARIABLES"]["SMART_FILTER_PATH"], 3);
					//$arResult["VARIABLES"]["SMART_FILTER_PATH"]=str_replace('catalog_brend-is-', '', $arResult["VARIABLES"]["SMART_FILTER_PATH"]);
					//echo $arResult["VARIABLES"]["SMART_FILTER_PATH"];
					/*unset($sections[count($sections)-1]);
					$arResult["VARIABLES"]["SECTION_CODE_PATH"]=implode('/', $sections);*/
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
?>
	<div class="left_block catalog <?=strtolower($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"])?>">
		<?
		/*if(!(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy/')===false)) {
			$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'],'ID' => $arSection["IBLOCK_SECTION_ID"]);
			$rsSect = CIBlockSection::GetList(array(),$arFilter, false);
			while ($arSect = $rsSect->GetNext())
			{
				$parent_sec=$arSect["IBLOCK_SECTION_ID"];
			}
		}*/
		if(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy')===false) {
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
				),$component
			);
		}
		else {
			CModule::IncludeModule('iblock');
			$arFilter = Array("IBLOCK_ID"=>29, 'NAME'=>$arSection["ID"]);
			$res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, array('ID', 'DETAIL_TEXT'));
			$allgroup=array();
			if($ob = $res->GetNextElement())
			{
				$element=$ob->GetFields();
				$menu=unserialize($element['~DETAIL_TEXT']);
				?>
				<div class="internal_sections_list">
					<?foreach($menu['PARENT'] as $razdel):?>
						<h4><a href="/catalog/<?=$razdel['CODE']?>/<?=$arSection['CODE']?>/"><?=$razdel['NAME']?></a></h4>
						<ul class="sections_list_wrapp">
						<?
						$prev=0;
						foreach($menu['CHILD'][$razdel['ID']]['ITEMS'] as $item) {
							$bParent=count($menu['CHILD'][$item['ID']]['ITEMS']);			?>
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
				<?
			}
		}	
		if($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]=="VERTICAL"){?>
			<?include_once("filter.php")?>
		<?}?>
		
	</div>
	<div class="right_block clearfix catalog" id="right_block_ajax">
	<?
	if($brends && strpos($APPLICATION->GetCurPage(), '/catalog/vse_brendy/')===false && empty($MSHOP_SMART_FILTER))  {
		$APPLICATION->ShowViewContent('filter_dop');
		
	}
	
	if(empty(${$arParams['FILTER_NAME']}) || $arSection["IBLOCK_SECTION_ID"]==5338) {
			//$res = CIBlockSection::GetByID($arResult["VARIABLES"]["SECTION_ID"]);
			//$ar_res = $res->GetNext();
			$ar_result=CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"26", "ID"=>$arSection["ID"]),false, Array("UF_IMG_BRAND", "UF_TOP_TEXT"));
	?>	
	<?if($res2=$ar_result->GetNext()):?>
	<?if($res2["UF_IMG_BRAND"]){?>
	<?$file = CFile::ResizeImageGet($res2["UF_IMG_BRAND"], array('width'=>266, 'height'=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
		<div class="top_brand_block" style="min-height: 70px;margin-top:0;">
		<div class="img_brand_sec"><img src="<?=$file["src"];?>"/></div>
		<div class="text_brand_sec"><p><?echo $res2["~UF_TOP_TEXT"];?></p></div>
		</div>
	<?}?>
	<?endif;
	}
	?>
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
                    <? /*echo "<pre>"; print_r($arParams["IBLOCK_TYPE"]); echo "</pre>";
                    echo "<pre>"; print_r($arParams["IBLOCK_ID"]); echo "</pre>";
                    echo "<pre>"; print_r($arParams["FILTER_NAME"]); echo "</pre>";
                    echo "<pre>"; print_r($arParams["FILTER_FIELD_CODE"]); echo "</pre>";
                    echo "<pre>"; print_r($arParams["FILTER_PROPERTY_CODE"]); echo "</pre>";
                    echo "<pre>"; print_r($arParams["PRICE_CODE"]); echo "</pre>";?>
                    <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.smart.filter",
                    "tz_filter",
                    Array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "FILTER_NAME" => $arParams["FILTER_NAME"],
                        "FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
                         "PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "FILTER_VIEW_MODE" => "VERTICAL",
                    ),
                    $component
                );
                    */?>
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
				//$frame->SetAnimation(true);?>
			<?}
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
			// $template = "catalog_".$display."_new";
			$template = "catalog_".$display;
			?>
                <? //echo "<pre>"; print_r($arResult); echo "</pre>";
                $arFilter = Array('IBLOCK_ID'=>$arParams['IBLOCK_ID'],'ID'=>$arResult["VARIABLES"]["SECTION_ID"], 'GLOBAL_ACTIVE'=>'Y');
                $db_list = CIBlockSection::GetList(Array("timestamp_x"=>"DESC"), $arFilter, false, Array("UF_SEO_TEXT"));
                if($path_seo_text = $db_list->GetNext()):
                endif;?>


                <div class="section-seo-text"><?=$path_seo_text["UF_SEO_TEXT"];?></div><br>
				<div class="sort_header view_<?=$display?>">
				<!--noindex-->
					<div class="sort_filter" style="display:none;">
						<?	
						$arAvailableSort = array();
						$arSorts = $arParams["SORT_BUTTONS"];
						if(in_array("POPULARITY", $arSorts)){
							$arAvailableSort["SHOWS"] = array("SHOWS", "desc");
						}
						if(in_array("NAME", $arSorts)){
							$arAvailableSort["NAME"] = array("NAME", "asc");
						}
						if(in_array("PRICE", $arSorts)){ 
							$arSortPrices = $arParams["SORT_PRICES"];
							if($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE"){
								$arAvailableSort["PRICE"] = array("PROPERTY_".$arSortPrices, "desc");
							}
							else{
								$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
								$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc"); 
							}
						}
						if(in_array("QUANTITY", $arSorts)){
							$arAvailableSort["CATALOG_AVAILABLE"] = array("QUANTITY", "desc");
						}
						$sort = "SHOWS";
						if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]){
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
							<?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';?>
							<a rel="nofollow" href="<?=$APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'))?>" class="sort_btn <?=($sort == $key ? 'current' : '')?> <?=$sort_order?> <?=$key?>" rel="nofollow">
								<i class="icon" title="<?=GetMessage('SECT_SORT_'.$key)?>"></i><span><?=GetMessage('SECT_SORT_'.$key)?></span><i class="arr"></i>
							</a>
						<?endforeach;?>
						<?
						if($sort == "PRICE"){
							$sort = "catalog_PRICE_3"; //$arAvailableSort["PRICE"][0];  было это
						}
						if($sort == "CATALOG_AVAILABLE"){
							$sort = "CATALOG_QUANTITY";
						}
						?>
					</div>
					<div class="sort_display">	
						<?foreach($arDisplays as $displayType):?>
							<a rel="nofollow" href="<?=$APPLICATION->GetCurPageParam('display='.$displayType, 	array('display'))?>" class="sort_btn <?=$displayType?> <?=($display == $displayType ? 'current' : '')?>"><i title="<?=GetMessage("SECT_DISPLAY_".strtoupper($displayType))?>"></i></a>
						<?endforeach;?>
					</div>
				<!--/noindex-->
			</div>
			<?if($isAjax=="Y"){
				$APPLICATION->RestartBuffer();
			}?>
			<?
			$show = $arParams["PAGE_ELEMENT_COUNT"];
			/*if(array_key_exists("show", $_REQUEST)){
				if(intVal($_REQUEST["show"]) && in_array(intVal($_REQUEST["show"]), array(20, 40, 60, 80, 100))){
					$show = intVal($_REQUEST["show"]); $_SESSION["show"] = $show;
				}
				elseif($_SESSION["show"]){
					$show=intVal($_SESSION["show"]);
				}
			}*/
			?>
			<?/*$frame = new \Bitrix\Main\Page\FrameHelper("banner-block");
			$frame->begin('');
				global $arBasketItems;
			$frame->end();*/?>
			<?if($isAjax=="N"){?>
				<div class="ajax_load <?=$display;?>">
			<?}/*?>
			<div class="baner_top_sec">
			<?
			$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE");
			$arFilter = Array("IBLOCK_ID"=>28, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
			$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
			while($ob = $res->GetNextElement())
			{
 $arFields = $ob->GetFields();
if($arFields["PREVIEW_PICTURE"]){
			?>
			
			<img src="<?=CFile::GetPath($arFields["PREVIEW_PICTURE"]);?>" class="baner_top_sec_img"/>
            <?
}
			}
			?>
			</div>
			<?*/
			
											
										?>
				<?
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
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
						"PAGE_ELEMENT_COUNT" => $show,
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
				);?>
			<?if($isAjax=="N"){?>
				<?if($posSectionDescr=="BOTTOM"){?>
					<?if ($arSection["DESCRIPTION"]):?>
						<div class="group_description_block bottom">
							<div><?=$arSection["DESCRIPTION"]?></div>
						</div>
					<?elseif($arSection["UF_SECTION_DESCR"]):?>
						<div class="group_description_block bottom">
							<div><?=$arSection["UF_SECTION_DESCR"]?></div>
						</div>
					<?endif;?>
				<?}?>
				<div class="clear"></div>
				</div>
			<?}?>
			<?if($isAjax=="Y") {
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.plugin.min.js',true);
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown.min.js',true);
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown-ru.js',true);
				//die();
			}?>
			<?if($isAjax!="Y"){?>
				<?$frame->end();?>
			<?}?>
			<?if($isAjax=="Y"){
				die();
			}?>
		</div>
	</div>
<?//endif;?>
<?
$basketAction='';
if($arParams["SHOW_TOP_ELEMENTS"]!="N"){
	if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y'){
		$basketAction = (isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '');
	}else{
		$basketAction = (isset($arParams['TOP_ADD_TO_BASKET_ACTION']) ? $arParams['TOP_ADD_TO_BASKET_ACTION'] : '');
	}
}?>
<?
			//$res = CIBlockSection::GetByID($arResult["VARIABLES"]["SECTION_ID"]);
			//$ar_res = $res->GetNext();
			$ar_result=CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"26", "ID"=>$arResult["VARIABLES"]["SECTION_ID"]),false, Array("UF_BOTTOM_TEXT"));
	?>
	<?if($res2=$ar_result->GetNext()):?>
	<?if($res2["UF_BOTTOM_TEXT"]){?>
		<div class="bottom_brand_block">
		<div class="brand__content">
			<span class="brand__content-title">Содержание</span>
		</div>
		<div class="brand__description">
			<?=htmlspecialcharsBack($res2["UF_BOTTOM_TEXT"]);?>
		</div>
		<p class="text_brand_sec_bot"></p>
		</div>
	<?}?>
<?endif;?>
<div class="baner_bot_sec">
			<?
			$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE");
			$arFilter = Array("IBLOCK_ID"=>28, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
			$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
			while($ob = $res->GetNextElement())
			{
 $arFields = $ob->GetFields();
if($arFields["PREVIEW_PICTURE"]){
			?>
			
			<img src="<?=CFile::GetPath($arFields["PREVIEW_PICTURE"]);?>" class="baner_bot_sec_img"/>
            <?
}
			}
			?>
			</div>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.viewed.products", 
	"mshop", 
	array(
		"COMPONENT_TEMPLATE" => "main",
		"BASKET_ATCTION" => $basketAction,
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "",
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"PRODUCT_SUBSCRIPTION" => $arParams['PRODUCT_SUBSCRIPTION'],
		"SHOW_MEASURE" => $arParams['SHOW_MEASURE'],
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		"PAGE_ELEMENT_COUNT" => $arParams["VIEWED_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
		"TEMPLATE_THEME" => "blue",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
		"PROPERTY_CODE_".$arParams["IBLOCK_ID"] => $arParams["LIST_PROPERTY_CODE"],
		"TOP_PROPERTY_CODE" => $arParams["TOP_PROPERTY_CODE"],
		"CART_PROPERTIES_".$arParams["IBLOCK_ID"] => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_".$arParams["IBLOCK_ID"] => "MORE_PHOTO",
		"LABEL_PROP_".$arParams["IBLOCK_ID"] => "-",
		"TITLE_BLOCK" => $arParams["VIEWED_BLOCK_TITLE"],
		"TITLE_BLOCK_BEST" => $arParams["SECTION_TOP_BLOCK_TITLE"],
		"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"SHOW_TOP_ELEMENTS" => $arParams["SHOW_TOP_ELEMENTS"],
		"TOP_ELEMENT_SORT_FIELD" => $arParams["TOP_ELEMENT_SORT_FIELD"],
		"TOP_ELEMENT_SORT_ORDER" => $arParams["TOP_ELEMENT_SORT_ORDER"],
		"TOP_ELEMENT_SORT_FIELD2" => $arParams["TOP_ELEMENT_SORT_FIELD2"],
		"TOP_ELEMENT_SORT_ORDER2" => $arParams["TOP_ELEMENT_SORT_ORDER2"],
		"ELEMENT_COUNT" => $arParams["TOP_ELEMENT_COUNT"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"TOP_OFFERS_FIELD_CODE" => $arParams["TOP_OFFERS_FIELD_CODE"],
		"TOP_OFFERS_PROPERTY_CODE" => $arParams["TOP_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"TOP_OFFERS_LIMIT" => $arParams["TOP_OFFERS_LIMIT"],
		"TOP_SECTION_ID" => $section["ID"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],

	),
	false, array("HIDE_ICONS"=>"Y")
);?>
<?
//$GLOBALS["CATALOG_CURRENT_ELEMENT_ID"] = $ElementID;

	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
    $ElementOfferIblockID = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
?>

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
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
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
);
?>
<script type="text/javascript">
/*$(".sort_filter a").on("click", function(){
	if($(this).is(".current")){
		$(this).toggleClass("desc").toggleClass("asc");
	}
	else{
		$(this).toggleClass("desc").toggleClass("asc");
		$(this).addClass("current").siblings().removeClass("current");
	}
});*/

$(".sort_display a:not(.current)").on("click", function() {
	$(this).addClass("current").siblings().removeClass("current");
});

$(".number_list a:not(.current)").on("click", function() {
	$(this).addClass("current").siblings().removeClass("current");
});
</script>
<div  style="display:none;">
<span itemtype="https://schema.org/Product" itemscope="">
	<meta content="<?$APPLICATION->ShowTitle()?>" itemprop="name">
	<meta itemprop="description" content="<?=$APPLICATION->GetProperty("description")?>" />
	<span itemtype="https://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating" class="mc-star-count">
		<span itemprop="ratingValue"><?=$section["UF_RATINGVALUE"]?></span> / <span itemprop="ratingCount"><?=$section["UF_RATINGCOUNT"]?></span>
		<meta itemprop="worstRating" content="1" />
		<meta itemprop="bestRating" content="5" />
	</span>
	<span itemtype="https://schema.org/AggregateOffer"; itemscope="" itemprop="offers"> 
	<?

//"CATALOG_PRICE_1" => "ASC" "nPageSize"=>1
$arFilter=Array('IBLOCK_ID'=>26, 'GLOBAL_ACTIVE'=>'Y', 'SECTION_ID'=>$arResult["VARIABLES"]["SECTION_ID"]);
$db_list = CIBlockSection::GetList(Array(), $arFilter, true);
while($ar_result = $db_list->GetNext())
{
    $arrayID[] = $ar_result['ID'];
}


$db_price_min = CIBlockElement::GetList(
        array("SORT"=>"ASC"), 
        array("IBLOCK_ID" => 26, "SECTION_ID"=> $arrayID, "ACTIVE"=>"Y"), 
        false, 
        false,
        array("ID")
    );
$i=0;
while($ar_fields = $db_price_min->GetNext())
{
 $i++;
 $secid[] = $ar_fields["ID"];
}

$db_price_max2 = CIBlockElement::GetList(
        array("CATALOG_PRICE_1" => "DESC"), 
        array("IBLOCK_ID" => 27, "ACTIVE"=>"Y", "PROPERTY_CML2_LINK" => $secid), 
        false, 
        array("nPageSize"=>1),
        array("ID")
    );
	$db_price_min2 = CIBlockElement::GetList(
        array("CATALOG_PRICE_1" => "ASC"), 
        array("IBLOCK_ID" => 27, "ACTIVE"=>"Y", "PROPERTY_CML2_LINK" => $secid), 
        false, 
        array("nPageSize"=>1),
        array("ID")
    );
	?>
	<meta content="<?=$i?>" itemprop="offerCount">
	<?
while($ar_fields = $db_price_max2->GetNext())
{
?>
	<meta content="<?=$ar_fields["CATALOG_PRICE_1"]?>" itemprop="highPrice">
<?
}
while($ar_fields2 = $db_price_min2->GetNext())
{
?>
	<meta content="<?=$ar_fields2["CATALOG_PRICE_1"]?>" itemprop="lowPrice">
<?
}	
	/*echo $arParams["IBLOCK_ID"];
	echo $arResult["VARIABLES"]["SECTION_ID"];*/
	?>
		<meta content="RUB" itemprop="priceCurrency">
		<link itemprop="availability" href="https://schema.org/InStock">  
	</span>
</span>
</div>
<?
global $MSHOP_SMART_FILTER, $filter_h1, $catalog_section_name, $catalog_seo, $brend_in_catalog;
//if(!$brends) $filter_h1=' '.$brend_in_catalog.' '.$filter_h1;
if($brend_in_catalog) $filter_h1=' '.$brend_in_catalog.$filter_h1;
		$catalog_seo='Y';	
		if(strpos($_SERVER['REQUEST_URI'], 'PAGEN_')) {
			foreach ($_GET as $key => $value) {
				if(!(strpos($key, 'PAGEN_')===false)) {
					$page_num = $value; 
					break;
				}
			}			
		}
        if (isset($page_num)||!empty($MSHOP_SMART_FILTER)) 
        {
            $page_seo_params["title"] = $APPLICATION->GetTitle();
            if (empty($MSHOP_SMART_FILTER))
            {
				if ($page_num!='1') {
					$APPLICATION->SetPageProperty("title", $page_seo_params["title"]." (Страница ".$page_num.")");   
				}
            }
			elseif (($page_num=='1'||!isset($page_num))&&!empty($MSHOP_SMART_FILTER))
            {
                $APPLICATION->SetPageProperty("title",  str_replace_once($catalog_section_name, $catalog_section_name.$filter_h1, $page_seo_params["title"]));  
            }
			else {
				$APPLICATION->SetPageProperty("title",  str_replace_once($catalog_section_name, $catalog_section_name.$filter_h1, $page_seo_params["title"])." (Страница ".$page_num.")");  
			}
        }
if($arSection["IBLOCK_SECTION_ID"]==5338&&$arResult["VARIABLES"]["SECTION_ID"]==0&&$arResult["VARIABLES"]["SECTION_CODE"]=='') {
	$page_seo_params["title"] = $arSection['NAME'].($arSection['RUSNAME']!=''?' ('.$arSection['RUSNAME'].')':'');
}		
else $page_seo_params["title"] = $APPLICATION->GetTitle().($arSection['RUSNAME']!=''?' ('.$arSection['RUSNAME'].')':'');
if( !(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy/')===false) && substr_count($arResult['VARIABLES']['SECTION_CODE_PATH'], '/')==1 ) {
	$this->SetViewTarget('h1');echo $page_seo_params["title"].$filter_h1;$this->EndViewTarget();
	$APPLICATION->SetPageProperty("title", "Каталог товаров бренда ".$page_seo_params["title"].$filter_h1."".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	$APPLICATION->SetPageProperty("keywords", $page_seo_params["title"].", купить ".$page_seo_params["title"].$filter_h1.", каталог ".$page_seo_params["title"].$filter_h1.", косметика ".$page_seo_params["title"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));  
	$APPLICATION->SetPageProperty("description", "Покупая косметику ".$page_seo_params["title"].$filter_h1." в интернет-магазине Cafre – вы получаете гарантию самый низкой цены и высшее качество продукции. У нас самый широкий ассортимент товаров ".$page_seo_params["title"].$filter_h1." в России.".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));   
}
elseif(!(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy/')===false) && substr_count($arResult['VARIABLES']['SECTION_CODE_PATH'], '/')>1 ) {
	$nav = CIBlockSection::GetNavChain(false, $arResult['VARIABLES']['SECTION_ID']);
	while($section_p = $nav->GetNext()) {
		if($section_p['DEPTH_LEVEL']==1) continue;		
		if($section_p['DEPTH_LEVEL']==2) {$dop_h1_brend = $section_p['NAME'];}		
		elseif($section_p['ID']!=$arResult['VARIABLES']['SECTION_ID']&&$section_p['DEPTH_LEVEL']==4) {$dop_h1_1 = $section_p['NAME'];}		
		elseif($section_p['ID']!=$arResult['VARIABLES']['SECTION_ID']&&$section_p['DEPTH_LEVEL']!=3) $dop_h1_a .= ' '.$section_p['NAME'];
		else $dop_h1 = $section_p['NAME'];
	} 
	$page_seo_params["title"] = ($dop_h1_1?$dop_h1_1:$dop_h1.$filter_h1).' '.$dop_h1_brend.$dop_h1_a.($dop_h1_1?' '.$dop_h1.$filter_h1:'');
	$this->SetViewTarget('h1');echo $page_seo_params["title"];$this->EndViewTarget();
	$APPLICATION->SetPageProperty("title", $page_seo_params["title"]." - Купить по низким ценам в интернет-магазине Cafre".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	$APPLICATION->SetPageProperty("keywords", $page_seo_params["title"].", купить ".$page_seo_params["title"].(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	$APPLICATION->SetPageProperty("description", "".$page_seo_params["title"].", огромный ассортимент. Гарантия качества от производителя и лучшие цены на рынке - в наличии!".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));   
}
else {
	$this->SetViewTarget('h1');echo $page_seo_params["title"].$filter_h1;$this->EndViewTarget();
	$APPLICATION->SetPageProperty("keywords", $page_seo_params["title"].$filter_h1.", купить ".$page_seo_params["title"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	$APPLICATION->SetPageProperty("description", "".$page_seo_params["title"].$filter_h1.", огромный ассортимент. Гарантия качества от производителя и лучшие цены на рынке - в наличии!".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));   
	if(substr_count($arResult['VARIABLES']['SECTION_CODE_PATH'], '/')>0) {
		$APPLICATION->SetPageProperty("title", $page_seo_params["title"].$filter_h1." - Купить по низким ценам в интернет-магазине Cafre".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	}
}

?>
