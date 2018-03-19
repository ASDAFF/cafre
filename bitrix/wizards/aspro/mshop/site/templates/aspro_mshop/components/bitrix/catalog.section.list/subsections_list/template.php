<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="articles-list sections wrap_md">
	<?foreach($arResult["SECTIONS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<section class="item iblock" id="<?=$this->GetEditAreaId($arItem['ID']);?>">	
			<?if($arItem["PICTURE"]["SRC"]):?>
				<?$img = CFile::ResizeImageGet($arItem["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
				<div class="left-data">
					<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PICTURE"]["ALT"] ? $arItem["PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PICTURE"]["TITLE"] ? $arItem["PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
				</div>
			<?elseif($arItem["~PICTURE"]):?>
				<?$img = CFile::ResizeImageGet($arItem["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
				<div class="left-data">
					<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PICTURE"]["ALT"] ? $arItem["PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PICTURE"]["TITLE"] ? $arItem["PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
				</div>
			<?else:?>
				<div class="left-data">
					<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" height="90" /></a>
				</div>
			<?endif;?>
			<div class="right-data">
				<div class="item-title"><a href="<?=$arItem["SECTION_PAGE_URL"]?>"><span><?=$arItem["NAME"]?>&nbsp;</span><?=($arItem["ELEMENT_CNT"] ? '<span class="grey">('.$arItem["ELEMENT_CNT"].')</span>' : '')?></a></div>
				<?$arSection = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arResult["SECTION"]["IBLOCK_ID"], "ID" => $arItem["ID"]), true, array("ID", "UF_SECTION_DESCR"))->GetNext();?>
				<?if($arSection["UF_SECTION_DESCR"]):?>
					<div class="preview-text"><?=$arSection["UF_SECTION_DESCR"]?></div>
				<?elseif($arItem["DESCRIPTION"]):?>
					<div class="preview-text"><?=$arItem["DESCRIPTION"]?></div>
				<?endif;?>
			</div>
		</section>
	<?endforeach;?>
</div>
<?if($arResult["SECTION"]["DESCRIPTION"]):?>
	<hr class="long"/>
	<div class="main_description"><?=$arResult["SECTION"]["DESCRIPTION"]?></div>
<?else:?>
	<?$arSection = CIBlockSection::GetList(array(), array( "IBLOCK_ID" => $arResult["SECTION"]["IBLOCK_ID"], "ID" => $arResult["ID"] ), false, array( "ID", "UF_SECTION_DESCR"))->GetNext();?>
	<?if ($arSection["UF_SECTION_DESCR"]):?>
		<hr class="long"/>
		<div class="main_description"><?=$arSection["UF_SECTION_DESCR"]?></div>
	<?endif;?>
<?endif;?>