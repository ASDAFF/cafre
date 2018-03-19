<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<div class="catalog_section_list">
	<?foreach( $arResult["SECTIONS"] as $arItems ){
		$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
		<div class="section_item" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
			<table class="section_item_inner">	
				<tr>
					<?if ($arParams["SHOW_SECTION_LIST_PICTURES"]=="Y"):?>
						<?$collspan = 2;?>
						<td class="image">
							<?if($arItems["PICTURE"]["SRC"]):?>
								<?$img = CFile::ResizeImageGet($arItems["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
								<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItems["PICTURE"]["ALT"] ? $arItems["PICTURE"]["ALT"] : $arItems["NAME"])?>" title="<?=($arItems["PICTURE"]["TITLE"] ? $arItems["PICTURE"]["TITLE"] : $arItems["NAME"])?>" /></a>
							<?elseif($arItems["~PICTURE"]):?>
								<?$img = CFile::ResizeImageGet($arItems["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
								<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItems["PICTURE"]["ALT"] ? $arItems["PICTURE"]["ALT"] : $arItems["NAME"])?>" title="<?=($arItems["PICTURE"]["TITLE"] ? $arItems["PICTURE"]["TITLE"] : $arItems["NAME"])?>" /></a>
							<?else:?>
								<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItems["NAME"]?>" title="<?=$arItems["NAME"]?>" height="90" /></a>
							<?endif;?>
						</td>
					<?endif;?>
					<td class="section_info">
						<ul>
							<li class="name">
								<a href="<?=$arItems["SECTION_PAGE_URL"]?>"><span><?=$arItems["NAME"]?><? echo $arItems["ELEMENT_CNT"]?'&nbsp;<span class="grey">('.$arItems["ELEMENT_CNT"].')</span>':'';?></span></a> 
							</li>
							<?if($arItems["SECTIONS"]){
								foreach( $arItems["SECTIONS"] as $arItem ){?>
									<li class="sect"><a href="<?=$arItem["SECTION_PAGE_URL"]?>"><?=$arItem["NAME"]?><? echo $arItem["ELEMENT_CNT"]?'&nbsp;('.$arItem["ELEMENT_CNT"].')':'';?></a></li>
								<?}
							}?>
						</ul>
					</td>
					<?$arSection = CIBlockSection::GetList(array(), array( "IBLOCK_ID" => $arItems["IBLOCK_ID"], "ID" => $arItems["ID"] ), false, array( "ID", $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]))->GetNext();?>
					<?if ($arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]):?>
						<tr><td class="desc"<?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]?></span></td></tr>
					<?else:?>
						<tr><td class="desc"<?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arItems["DESCRIPTION"]?></span></td></tr>
					<?endif;?>
				</tr>
			</table>
		</div>
	<?}?>
</div>
<script>
	$(".catalog_section_list").ready(function(){
		//$('.catalog_section_list .section_item_inner .section_item_inner').equalize(); 
		//$('.catalog_section_list .section_item_inner').equalize({children: '.section_info'}); 
	});
</script>