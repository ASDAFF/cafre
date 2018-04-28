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
						/*ksort($brend_list);
						$brend=false;
						sort($en_alphabet);
						sort($ru_alphabet);?>

						<div class="menu__brands brnds">
							<div class="brnds__top">
								<ul class="brnds__alph brnds__alph_en">
									<li><a href="#"><?=implode('</a></li>
									<li><a href="#">', $en_alphabet)?></a></li>
								</ul>
								<ul class="brnds__alph brnds__alph_ru">
									<li><a href="#"><?=implode('</a></li>
									<li><a href="#">', $ru_alphabet)?></a></li>
								</ul>
								<a href="#" class="brnds__all cur">Все</a>
							</div>
							<div class="brnds__main">
								<div class="brnds__caption">
									
								</div>
								<ul class="brnds__list">
									<?echo implode('', $brend_list);?>
								</ul>
								<div class="brnds__ads">
									<img src="https://cafre.ru/upload/iblock/143/143932bc8469910d1890222c2a78f483.jpg">
								</div>
							</div>
						</div><?
						$brend_list=array();*/
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
							//menu__drop-add
							//print_r($arr_li2[key($arr_li2)]["SVIZ_BR"]);
							?>
							<ul data-lvl="<?=$k2?>" data-name="">
									<?foreach($arr_li2 as $li2) {
										
										if(!unserialize($li2["SVIZ_BR"])) continue;
										//echo $li2["LINK"];
										?>
										
										<?
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
						<!--<div class="menu__cell">
								<span>Найти нужный бренд вы сможете в выбранной категории</span>	
							</div>-->
							<div class="menu__cell">
								<div class="menu__ads">
									<img src="https://cafre.ru/upload/iblock/143/143932bc8469910d1890222c2a78f483.jpg" alt="">
								</div>
							</div>	
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
				<?if($arItem["LINK"] != "/catalog/vse_brendy/"){?>
				<li class="menu__item <?=($arItem["SELECTED"] ? ' current' : '')?><?=($arItem["PARAMS"]["ACTIVE"]=="Y" ? ' active' : '')?>">
                <a class="<?=($arItem["SELECTED"] ? ' current' : '')?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				<?}?>
				<?if($arItem["IS_PARENT"]):?>
					<div class="menu__drop">
					<?if(strpos($arItem["LINK"], 'vse_bren')===false) {?>
						<div class="menu__item-lvl menu__item-lvl_1">
							<ul>
							<?} else {
								$brend=true;
								$db_sec = CIBlockSection::GetList(Array(), Array('NAME'=>$arItem['TEXT'], 'GLOBAL_ACTIVE'=>'Y'), false, array('ID', 'IBLOCK_ID'));																
								if($sec_result = $db_sec->GetNext())
								{
									$arFilter = Array('IBLOCK_ID'=>$sec_result['IBLOCK_ID'], 'SECTION_ID'=>$sec_result['ID'], 'GLOBAL_ACTIVE'=>'Y');
									$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, false, array('ID', 'NAME', 'IBLOCK_ID', 'UF_IMG_BRAND'));																
									while($ar_result = $db_list->GetNext())
									{
										$brend_pic[$ar_result['NAME']]=$ar_result['UF_IMG_BRAND'];
									}
								} 
							} ?>
				<?endif;?>
			<?endif;?>
			<?if($arItem["DEPTH_LEVEL"]==2&&!$brend):?>
			
				<li <?if($arItem["IS_PARENT"]) {$numSubLevel++; ?>data-lvl="<?=$numSubLevel?>"<?}?>>
				<?/*	$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, 'NAME' => $arItem["TEXT"]));
if ($arSection = $rsSections->Fetch())
{?>
<?				$arSelect = Array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_CATALOG_BREND");
	$arFilter2 = Array("IBLOCK_ID"=>26, "ACTIVE"=>"Y","IBLOCK_SECTION_ID"=>$arSection["ID"]);
	$res = CIBlockElement::GetList(Array(), $arFilter2, Array("PROPERTY_CATALOG_BREND"), Array(), $arSelect);
if($ob = $res->GetNextElement())?>
<?}*/?>
					<a class="<?=($arItem["SELECTED"] ? ' current' : '')?>" href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span></a>
				</li>
			<?endif;?>
			<?if($arItem["DEPTH_LEVEL"]==2&&$brend):
			/*
			$brn++;
				if(preg_match('[^A-Za-z]', substr($arItem["TEXT"], 0, 1))) {
					if(!in_array(substr($arItem["TEXT"], 0, 1), $ru_alphabet)) $ru_alphabet[]=substr($arItem["TEXT"], 0, 1);
				}
				else {
					if(!in_array(substr($arItem["TEXT"], 0, 1), $en_alphabet)) $en_alphabet[]=substr($arItem["TEXT"], 0, 1);
				}
				$file = CFile::ResizeImageGet($brend_pic[$arItem["TEXT"]], array('width'=>180, 'height'=>60), BX_RESIZE_IMAGE_PROPORTIONAL, true);  
				if($brn == 1 || $brn == 2 || $brn == 3 || $brn == 4 || $brn == 5 || $brn == 6 || $brn == 7){				
				$brend_list[$arItem["TEXT"]]='<li class="brnd"><a href="'.$arItem["LINK"].'"><img src="'.$file['src'].'" alt=""><span><b>'.$arItem["TEXT"].'</b></span></a></li>';
				}else{
					$brend_list[$arItem["TEXT"]]='<li class="brnd"><a href="'.$arItem["LINK"].'"><img src="'.$file['src'].'" alt=""><span>'.$arItem["TEXT"].'</span></a></li>';
				}*/
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
			//$b_a = unserialize($arItem["SVIZ_BR"]);
			/*foreach(unserialize($arItem["SVIZ_BR"]) as $newvalb){
				$subSubArray2[$numSubLevel][]='<li><a href="'.$arItem["LINK"].$newvalb[2].'" class="'.($arItem["SELECTED"] ? ' current' : '').'">'.$newvalb[0].'</a></li>';
			}*/
			$subSubArray2[$numSubLevel3][]=$arItem;
			//echo $b_a[key($b_a)][2];
			endif;?>
			
			<?if(is_integer($key)) $prevLevel = $arItem["DEPTH_LEVEL"];?>			
		
			
        <?endforeach;?>
		<?if($prevLevel>1){?>
					<?if($brend) {
						//ksort($brend_list);
						/*$brend=false;
						sort($en_alphabet);
						sort($ru_alphabet);?>
						<div class="menu__brands brnds">
							<div class="brnds__top">
								<ul class="brnds__alph brnds__alph_en">
									<li><a href="#"><?=implode('</a></li>
									<li><a href="#">', $en_alphabet)?></a></li>
								</ul>
								<ul class="brnds__alph brnds__alph_ru">
									<li><a href="#"><?=implode('</a></li>
									<li><a href="#">', $ru_alphabet)?></a></li>
								</ul>
								<a href="#" class="brnds__all cur">Все</a>
							</div>
							<div class="brnds__main">
								<div class="brnds__caption">
									
								</div>
								<ul class="brnds__list">
									<?echo implode('', $brend_list);?>
								</ul>
								<div class="brnds__ads">
									<img src="https://cafre.ru/upload/iblock/143/143932bc8469910d1890222c2a78f483.jpg">
								</div>
							</div>
						</div><?
						$brend_list=array();*/
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
							<!--<div class="menu__cell">
								<span>Найти нужный бренд вы сможете в выбранной категории</span>	
							</div>-->
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