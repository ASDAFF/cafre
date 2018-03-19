<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!--noindex-->
	<div class="wraps_icon_block">
		<a href="<?=$arParams["COMPARE_URL"]?>" class="link" title="<?=GetMessage("CATALOG_COMPARE_ELEMENTS");?>"></a>
		<div class="count">
			<span>
				<div class="items">
					<a href="<?=$arParams["COMPARE_URL"]?>"><?=count($arResult);?></a>
				</div>
			</span>
		</div>
	</div>
	<div class="clearfix"></div>
<!--/noindex-->
<?if($arResult){
	global $compare_items;
	foreach($arResult as $key=>$arItem){
		$compare_items[] = $key;
	}
}?>