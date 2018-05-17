<?//описания у брендов перетсали выводиться
if($array_brand_sec){
$exp_mas2 = explode("/", $APPLICATION->GetCurPage());
	$fruit30 = array_pop($exp_mas2);
	//$fruit2 = array_pop($exp_mas2);
	//print_r();array_pop($exp_mas2)
	$ar_result_br=CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>30, "PROPERTY_RAZ_D"=>$arSection["ID"], "XML_ID"=>$array_brand_sec, "ACTIVE"=>'Y'),false, Array("XML_ID", "NAME", "PROPERTY_*"));
	if($res3=$ar_result_br->GetNext()){?>
		<?if($res3["DETAIL_TEXT"]){?>
			<div class="bottom_brand_block" data-b="<?=$res3['ID']?>">
				<div class="brand__content">
					<span class="brand__content-title">Содержание</span>
				</div>
				<div class="brand__description">
					<?=htmlspecialcharsBack($res3["DETAIL_TEXT"]);?>
				</div>
				<p class="text_brand_sec_bot"></p>
			</div>
		<?}
}
}elseif($arSection["DESCRIPTION"]){?>
	<div class="bottom_brand_block">
		<div class="brand__content">
			<span class="brand__content-title">Содержание</span>
		</div>
		<div class="brand__description">
			<?=htmlspecialcharsBack($arSection["DESCRIPTION"]);?>
		</div>
		<p class="text_brand_sec_bot"></p>
		</div>
<?}else{
	$ar_result=CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"26", "ID"=>$arResult["VARIABLES"]["SECTION_ID"]>0?$arResult["VARIABLES"]["SECTION_ID"]:$arSection["ID"]),false, Array("UF_BOTTOM_TEXT"));
	if($res2=$ar_result->GetNext()):?>
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
	<?endif;
}?>

	<div class="baner_bot_sec">
		<?
		$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE");
		$arFilter = Array("IBLOCK_ID"=>28, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
		while($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			if($arFields["PREVIEW_PICTURE"]){?>			
				<img src="<?=CFile::GetPath($arFields["PREVIEW_PICTURE"]);?>" class="baner_bot_sec_img"/>
            <?}
		}?>
	
	</div>