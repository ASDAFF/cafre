<div class="new_all_brand">
	<ul>
	<?
		$rsSect = CIBlockSection::GetList(Array("NAME" => "asc"), Array("IBLOCK_ID"=>26, "SECTION_ID" => 5338, "ACTIVE"=>"Y"),false, Array("UF_IMG_BRAND", "UF_TOP_TEXT", "UF_TEXT_BRAND_TOP"));  
		while ($arSect = $rsSect->GetNext()) {
			$file333 = CFile::ResizeImageGet($arSect["UF_IMG_BRAND"], array('width'=>260, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
			<li>
				<a href="<?=$arSect["SECTION_PAGE_URL"];?>">
					<?if($file333["src"]):?>
						<img src="<?=$file333["src"];?>" alt="<?=$arSect["NAME"];?>"/>
					<?endif;?>
					<p><?echo $arSect["NAME"];?></p>
				</a>
			</li>
		<? } ?>
	</ul>
</div>