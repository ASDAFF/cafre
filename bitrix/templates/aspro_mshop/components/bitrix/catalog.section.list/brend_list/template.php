<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);
global $min_level;?>
<div class="internal_sections_list">
	<ul class="sections_list_wrapp">
		<?
		foreach($arResult["SECTIONS"] as $arItem):
			if($arItem['DEPTH_LEVEL']>$min_level||$arItem['ELEMENT_CNT']==0) continue;
if($arParams["SECTION_BRAND_CAT"]){
			$e_fater = '';
	$rsParentSection = CIBlockSection::GetByID($arItem["ID"]);
if ($arParentSection = $rsParentSection->GetNext())
{
   $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
   $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter, true, array("ID", "IBLOCK_ID","IBLOCK_SECTION_ID", "UF_BRAND_ID"));
   while ($arSect = $rsSect->GetNext())
   {
	   foreach(unserialize($arSect["~UF_BRAND_ID"]) as $ff){
		 if($arParams["SECTION_BRAND_CAT"] == strtoupper($ff[0]))
			$e_fater = 'on';
	   }
      
   }
}

if($e_fater != 'on')continue;
}
			$bParent=count($arItem["SECTIONS"]);			?>
			<li class="item <?=($arItem["SELECTED"]==1 ? "cur" : "")?>" <?/*data-id="<?=$arItem['ID']?>"*/?>>
				<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="<?=($bParent ? 'parent' : '')?>"><span><?=$arItem["NAME"]?></span></a>
				<?if($bParent):?>
					<div class="child_container">
						<div class="child_wrapp <?=($bDepth3 ? "bDepth3 clearfix" : "")?>">
							<ul class="child">
								<?foreach($arItem["SECTIONS"] as $arSection):
										if($arParams["SECTION_BRAND_CAT"]){
										$db_list = CIBlockSection::GetList(array(), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arSection["ID"], "IBLOCK_ID" => 26, "ELEMENT_SUBSECTIONS"=>"Y"), true, array("ID", "IBLOCK_ID","IBLOCK_SECTION_ID", "UF_BRAND_ID"));
										$section = $db_list->GetNext();
										$e = '';
										foreach(unserialize($section["~UF_BRAND_ID"]) as $f){
											if($arParams["SECTION_BRAND_CAT"] == strtoupper($f[0]))
											$e.= 'on';
										}
										if($e != 'on')continue;
										}
										
										
								?>
									<?if($arSection['ELEMENT_CNT']==0) continue;
									if(count($arSection["SECTIONS"])):?>
										<li class="bDepth3">
										<?if($arParams["SECTION_BRAND_CAT"]):?>
											<a class="menu_title <?=($arSection["SELECTED"] ? "cur" : "")?>" href="<?=$arSection["SECTION_PAGE_URL"].strtolower($arParams["SECTION_BRAND_CAT"]).'/'?>"><?=$arSection["NAME"]?></a>
										<?else:?>
											<a class="menu_title <?=($arSection["SELECTED"] ? "cur" : "")?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
										<?endif;?>
											<?foreach($arSection["SECTIONS"] as $arSubItem):
											if($arSubItem['ELEMENT_CNT']==0) continue;
											?>
										<?if($arParams["SECTION_BRAND_CAT"]):?>
										<a class="menu_item <?=($arSubItem["SELECTED"] ? "cur" : "")?>" data-id="<?=$arSubItem['ID']?>" href="<?=$arSubItem["SECTION_PAGE_URL"].strtolower($arParams["SECTION_BRAND_CAT"]).'/'?>"><?=$arSubItem["NAME"]?></a>
										<?else:?>
										<a class="menu_item <?=($arSubItem["SELECTED"] ? "cur" : "")?>" data-id="<?=$arSubItem['ID']?>" href="<?=$arSubItem["SECTION_PAGE_URL"]?>"><?=$arSubItem["NAME"]?></a>
										<?endif;?>
												
											<?endforeach;?>
										</li>
									<?else:?>
										<li class="menu_item <?=($arSection["SELECTED"] ? "cur" : "")?>" data-id="<?=$arSection['ID']?>">
										<?if($arParams["SECTION_BRAND_CAT"]):?>
										<a href="<?=$arSection["SECTION_PAGE_URL"].strtolower($arParams["SECTION_BRAND_CAT"]).'/'?>"><?=$arSection["NAME"]?></a>
										<?else:?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
										<?endif;?>
										</li>
									<?endif;?>
								<?endforeach;?>
							</ul>
						</div>
					</div>
				<?endif;?>
			</li>
		<?endforeach;?>
	</ul>
	<?$arSite = CSite::GetByID( SITE_ID )->Fetch();?>
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