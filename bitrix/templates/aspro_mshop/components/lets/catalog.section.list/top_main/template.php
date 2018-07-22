<?if(!empty($arResult['SECTIONS'])):
$catalog_level=0;
$drop_level=-1;
$brend_level=0;
$lis=array();
$brends=array();		
?>
<ul class="menu adaptive">
   <li class="menu_opener"><a>меню</a><i class="icon"></i></li>
</ul>
<ul class="menu_level">
	<?foreach($arResult['SECTIONS'] as $arItem): 
		if($arItem['DEPTH_LEVEL']==1) {
		if($catalog_level>1) {?>
			</ul>
			</div><?
			if(!empty($lis)) {?>
				<div class="menu__item-lvl menu__item-lvl_2">
					<?foreach($lis as $k =>$text) {?>
						<ul data-lvl="<?=$k?>"><?=$text?></ul>
					<?}?>
				</div>
			<?}
			if(!empty($brends)) {?>
				<div class="menu__item-lvl menu__item-lvl_3">
					<?foreach($brends as $k =>$text) {?>
						<ul data-lvl="<?=$k?>"><?=$text?></ul>
					<?}?>
				</div>
			<?}?>
			<div class="menu__drop-add"></div>
			</div>
		<?}
		if($catalog_level>0) {?></li><?}
		$lis=array();
		$brends=array();		
		?>
		<li class="menu__item ">
			<a href="<?=$arItem['SECTION_PAGE_URL']?>"><?=$arItem['NAME']?></a>
			<?if($arItem['LEFT_MARGIN']+1<$arItem['RIGHT_MARGIN']) {?>
				<div class="menu__drop">
					<div class="menu__item-lvl menu__item-lvl_1">
						<ul>
			<?}?>
		<?} elseif($arItem['DEPTH_LEVEL']==2) {
			if($arItem['LEFT_MARGIN']+1<$arItem['RIGHT_MARGIN']) $drop_level++;?>
			<li <?if($arItem['LEFT_MARGIN']+1<$arItem['RIGHT_MARGIN']) {?>data-lvl="<?=$drop_level?>"<?}?>>
                <a href="<?=$arItem['SECTION_PAGE_URL']?>"><span><?=$arItem['NAME']?></span></a>
            </li>
		<? 
		} elseif($arItem['DEPTH_LEVEL']==3) {
			$lis[$drop_level].='<li '.($arItem['UF_BRAND_ID']?'data-lvl="'.$brend_level.'"':'').'><a href="'.$arItem['SECTION_PAGE_URL'].'" ><span>'.$arItem['NAME'].'</span></a></li>';
			if($arItem['UF_BRAND_ID']) {									
										foreach(unserialize($arItem["~UF_BRAND_ID"]) as $newvalb){
											$brends[$brend_level].='<li><a href="'.$arItem['SECTION_PAGE_URL'].$newvalb[2].'/" ><span>'.$newvalb[0].'</span></a></li>';
										}
				
				$brend_level++;				
			}
			?>
		<?}
		$catalog_level=$arItem['DEPTH_LEVEL'];
		?>
	<?endforeach;
	
	if($catalog_level>1) {?>
			</ul>
			</div><?
			if(!empty($lis)) {?>
				<div class="menu__item-lvl menu__item-lvl_2">
					<?foreach($lis as $k =>$text) {?>
						<ul data-lvl="<?=$k?>"><?=$text?></ul>
					<?}?>
				</div>
			<?}
			if(!empty($brends)) {?>
				<div class="menu__item-lvl menu__item-lvl_3">
					<?foreach($brends as $k =>$text) {?>
						<ul data-lvl="<?=$k?>"><?=$text?></ul>
					<?}?>
				</div>
			<?}?>
			<div class="menu__drop-add"></div>
			</div>
		<?}
		if($catalog_level>0) {?></li><?}?>
	<li class="menu__item  to_right">
      <a href="/catalog/hits/">хиты</a>
   </li>
   <li class="menu__item  to_right">
      <a href="/catalog/new/">новинки</a>
   </li>
   <li class="menu__item  to_right">
      <a href="/catalog/sale/">акции</a>
   </li>
</ul>
<?endif;?>