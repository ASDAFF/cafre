<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="internal_sections_list">
	<div class="title opened">
		<div class="inner_block"><?=GetMessage("CATALOG_TITLE");?>
			<span class="hider opened"></span>
		</div>
	</div>
	<ul class="sections_list_wrapp">
		<?foreach($arResult["SECTIONS_TREE"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$bDepth3 = false;
			if($bParent = count($arItem["SECTIONS"])){
				foreach($arItem["SECTIONS"] as $i){
					if($i["SECTIONS"] && is_array($i["SECTIONS"])){
						$bDepth3 = true;
						break;
					}
				}
			}
			?>		
			<li class="item <?=($arItem["SELECTED"] ? "cur" : "")?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-id="<?=$arItem['ID']?>">
				<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="<?=($bParent ? 'parent' : '')?>"><span><?=$arItem["NAME"]?></span></a>
				<?if($bParent):?>
					<div class="child_container">
						<div class="child_wrapp <?=($bDepth3 ? "bDepth3 clearfix" : "")?>">
							<ul class="child">
								<?foreach($arItem["SECTIONS"] as $arSection):?>
									<?if(count($arSection["SECTIONS"])):?>
										<li class="bDepth3">
											<a class="menu_title <?=($arSection["SELECTED"] ? "cur" : "")?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
											<?foreach($arSection["SECTIONS"] as $arSubItem):?>
												<a class="menu_item <?=($arSubItem["SELECTED"] ? "cur" : "")?>" data-id="<?=$arSubItem['ID']?>" href="<?=$arSubItem["SECTION_PAGE_URL"]?>"><?=$arSubItem["NAME"]?></a>
											<?endforeach;?>
										</li>
									<?else:?>
										<li class="menu_item <?=($arSection["SELECTED"] ? "cur" : "")?>" data-id="<?=$arSection['ID']?>"><a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></li>
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
			$(".internal_sections_list .sections_list_wrapp").hide();
		}

		$('.left_block .internal_sections_list li.item > a.parent').click(function(e) {
			e.preventDefault();
			$(this).parent().find('.child_container').slideToggle();
		});
	});
</script>