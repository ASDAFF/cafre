<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<div class="news_block news">
	<div class="top_block">
		<?
		$title_block=($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('NEWS_TITLE'));
		$url=($arParams["ALL_URL"] ? $arParams["ALL_URL"] : "company/news/");
		?>
		<div class="title_block"><?=$title_block;?></div>
		<a href="<?=SITE_DIR.$url;?>"><?=GetMessage('ALL_NEWS')?></a>
	</div>
	<div class="info_block">
		<div class="news_items">
			<?foreach($arResult["ITEMS"] as $arItem){
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="item box-sizing">
					<?if($arItem['PREVIEW_PICTURE']){?>
						<div class="image">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
								<?$img=CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array( 'width' => 90, 'height' => 90 ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, false, false, 80)?>
								<img class="img-responsive" src="<?=$img["src"];?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>">
							</a>
						</div>
					<?}?>
					<div class="info">
						<div class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
						<a class="name" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
						<div class="preview"><?=$arItem['PREVIEW_TEXT'];?></div>
					</div>
				</div>
			<?}?>
		</div>
	</div>
</div>