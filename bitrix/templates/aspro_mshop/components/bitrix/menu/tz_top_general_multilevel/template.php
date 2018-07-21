<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetAdditionalCSS("/include/new_menu.css");
$this->setFrameMode(true);
$numSubLevel = -1;
$numSubLevel3 = -1;
$prevLevel = 0;
$subSubArray = array();
$subSubArray2 = array();
$brend=false;
$brend_list=array();
?>
	<ul class="menu adaptive">
		<li class="menu_opener"><a><?=GetMessage('MENU_NAME')?></a><i class="icon"></i></li>
	</ul>
	<ul class="menu_level">
		<?
		$brn = 0;
		foreach($arResult as $key => $arItem):
	
		?> 
			<?if($arItem["DEPTH_LEVEL"]==1):
			
			?>
				<?if($prevLevel>1){
					if($brend) {						
					} else {?>
						</ul>
					</div>
					<?if(!empty($subSubArray)){?>
						<div class="menu__item-lvl menu__item-lvl_2">
							<?foreach($subSubArray as $k=>$arr_li) {?>
								<ul data-lvl="<?=$k?>">
									<?foreach($arr_li as $li) {
										echo $li;
									}?>
								</ul>
							<?}?>
						</div>
						<div class="menu__item-lvl menu__item-lvl_3">
						<?foreach($subSubArray2 as $k2=> $arr_li2) {
							?>
							<ul data-lvl="<?=$k2?>" data-name="">
									<?foreach($arr_li2 as $li2) {
										
										if(!unserialize($li2["SVIZ_BR"])) continue;										
										foreach(unserialize($li2["SVIZ_BR"]) as $newvalb){
											$arFilter3 = Array('IBLOCK_ID'=>26, "ACTIVE"=>"Y", 'NAME'=>$newvalb[0]);
										 $db_list3 = CIBlockSection::GetList(Array(), $arFilter3, false, array("UF_BRAND_ID"));
										  if(!($ar_result3 = $db_list3->GetNext()))continue;
											?>
										<li>
										<a href="<?echo $li2["LINK"].$newvalb[2].'/';?>" class="<?($li2["SELECTED"] ? ' current' : '')?>"><span><?=$newvalb[0]?></span></a>
										</li>
										<?
										
										}
										//echo $li2;
									}?>
								</ul>
							<?}?>
						
							
						</div>
						<div class="menu__drop-add">						
						</div>							
					<?}
					$subSubArray=array(); 
					$subSubArray2=array(); 
				}?>
					</div>
					</li>
				<?} elseif($prevLevel>0) {?>
					</li>
				<?}?>
				<?
				//if($arItem["LINK"] == "/catalog/vse_brendy/") continue;
				if($arItem["LINK"] != "/catalog/vse_brendy/"){?>
				<li class="menu__item <?=($arItem["LINK"]=='/catalog/sale/'||$arItem["LINK"]=='/catalog/hits/'||$arItem["LINK"]=='/catalog/new/' ? ' to_right' : '')?><?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["PARAMS"]["ACTIVE"]=="Y" ? ' active' : '')?>">
                <a class="<?=($arItem["SELECTED"] ? ' current' : '')?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				<?}else{?>
					<li style="display:none;">
				<?}?>
				<?if($arItem["IS_PARENT"]):?>
					<div class="menu__drop">
					<?if(strpos($arItem["LINK"], 'vse_bren')===false) {?>
						<div class="menu__item-lvl menu__item-lvl_1">
							<ul>
							<?} else {
								$brend=true;
							} ?>
				<?endif;?>
			<?endif;?>
			<?if($arItem["DEPTH_LEVEL"]==2&&!$brend):?>
			
				<li <?if($arItem["IS_PARENT"]) {$numSubLevel++; ?>data-lvl="<?=$numSubLevel?>"<?}?>>
				
					<a class="<?=($arItem["SELECTED"] ? ' current' : '')?>" href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span></a>
				</li>
			<?endif;?>
			<?if($arItem["DEPTH_LEVEL"]==2&&$brend):			
			endif;?>
			<?if($arItem["DEPTH_LEVEL"]==3&&!$brend):
			if(unserialize($arItem["SVIZ_BR"]) == FALSE){
				$subSubArray[$numSubLevel][]='<li><a href="'.$arItem["LINK"].'" class="'.($arItem["SELECTED"] ? ' current' : '').'"><span>'.$arItem["TEXT"].'</span></a></li>';
			}else{
			$numSubLevel3++;
				$subSubArray[$numSubLevel][]='<li data-lvl="'.$numSubLevel3.'"><a href="'.$arItem["LINK"].'" class="'.($arItem["SELECTED"] ? ' current' : '').'"><span>'.$arItem["TEXT"].'</span></a></li>';
			}
			endif;?>
			<?if($arItem["DEPTH_LEVEL"]==3&&!$brend):
			
			if(unserialize($arItem["SVIZ_BR"]) == FALSE) continue;
			
			$subSubArray2[$numSubLevel3][]=$arItem;
			endif;?>
			
			<?if(is_integer($key)) $prevLevel = $arItem["DEPTH_LEVEL"];?>			
		
			
        <?endforeach;?>
		<?if($prevLevel>1){?>
					<?if($brend) {
					} else {?>
						</ul>
					</div>
					<?if(!empty($subSubArray)){?>
						<div class="menu__item-lvl menu__item-lvl_2">
							<?foreach($subSubArray as $k=>$arr_li) {?>
								<ul data-lvl="<?=$k?>">
									<?foreach($arr_li as $li) {
										echo $li;
									}?>
								</ul>
							<?}?>
						</div>
						<div class="menu__drop-add">
							<div class="menu__cell">
								<div class="menu__ads">
									<img src="https://cafre.ru/upload/iblock/143/143932bc8469910d1890222c2a78f483.jpg" alt="">
								</div>
							</div>
						</div>						
					<?}					
				}?>
					</div>
					</li>
				<?} elseif($prevLevel>0) {?>
				<?if($arItem["LINK"] != "/catalog/vse_brendy/"){?>
					</li>
				<?}?>
				<?}?>
	</ul>	