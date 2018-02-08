<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);
global $min_level;?>
<div class="internal_sections_list">
	<ul class="sections_list_wrapp">
		<?foreach($arResult["SECTIONS"] as $arItem):
			if($arItem['DEPTH_LEVEL']>$min_level||$arItem['ELEMENT_CNT']==0) continue;
			$bParent=count($arItem["SECTIONS"]);			?>
			<li class="item <?=($arItem["SELECTED"]==1 ? "cur" : "")?>" <?/*data-id="<?=$arItem['ID']?>"*/?>>
				<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="<?=($bParent ? 'parent' : '')?>"><span><?=$arItem["NAME"]?></span></a>
				<?if($bParent):?>
					<div class="child_container">
						<div class="child_wrapp <?=($bDepth3 ? "bDepth3 clearfix" : "")?>">
							<ul class="child">
								<?foreach($arItem["SECTIONS"] as $arSection):?>
									<?if($arSection['ELEMENT_CNT']==0) continue;
									if(count($arSection["SECTIONS"])):?>
										<li class="bDepth3">
											<a class="menu_title <?=($arSection["SELECTED"] ? "cur" : "")?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a>
											<?foreach($arSection["SECTIONS"] as $arSubItem):
											if($arSubItem['ELEMENT_CNT']==0) continue;
											?>
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
			//$(".internal_sections_list .sections_list_wrapp").hide();
		}

		$('.left_block .internal_sections_list li.item > a.parent').click(function(e) {
			e.preventDefault();
			$(this).parent().find('.child_container').slideToggle();
		});
	});
</script>