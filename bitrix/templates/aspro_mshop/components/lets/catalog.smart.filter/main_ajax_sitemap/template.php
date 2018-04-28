<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
print_r($arResult['COMBO']);
if($arResult["ITEMS"]){
	
				foreach($arResult["HIDDEN"] as $arItem):?>
				<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
				<?endforeach;
				$isFilter=false;
				

				//not prices
				foreach($arResult["ITEMS"] as $key=>$arItem)
				{
					
					if(
						empty($arItem["VALUES"])
						|| isset($arItem["PRICE"])
					)
						continue;

					if (
						$arItem["DISPLAY_TYPE"] == "A"
						&& (
							$arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
						)
					)
						continue;
					$isFilter=true;
					if($arItem["CODE"]!="IN_STOCK"){?>
							<div class="bx_filter_parameters_box_title" >
								<span>
									<?=( $arItem["CODE"] == "MINIMUM_PRICE" ? GetMessage("PRICE") : $arItem["NAME"] );?>
									<div class="char_name">
										<div class="props_list">
											<?if($arParams["SHOW_HINTS"]){
												if(!$arItem["FILTER_HINT"]){
													$prop = CIBlockProperty::GetByID($arItem["ID"], $arParams["IBLOCK_ID"])->GetNext();
													$arItem["FILTER_HINT"]=$prop["HINT"];
												}?>
												<?if( $arItem["FILTER_HINT"] && strpos( $arItem["FILTER_HINT"],'line')===false){?>
													<div class="hint"><span class="icon"><i>?</i></span><div class="tooltip" style="display: none;"><?=$arItem["FILTER_HINT"]?></div></div>
												<?}?>
											<?}?>
										</div>
									</div>
								</span>
							</div>
						<?}
							//echo $arParams["SECTION_DETAIL_PAGE"];
							foreach($arItem["VALUES"] as $val => $ar){
							if($ar["ELEMENT_COUNT"] == 0 || strpos($arParams["SECTION_DETAIL_PAGE"], 'vse_brendy'))continue;
							$user_filt = $arParams["DOOM"]->createElement("url");
							if($key==250) {
    $login_filt = $arParams["DOOM"]->createElement("loc", 'https://cafre.ru'.$arParams["SECTION_DETAIL_PAGE"].$ar["URL_ID"].'/');
							}else{
	$login_filt = $arParams["DOOM"]->createElement("loc", 'https://cafre.ru'.$arParams["SECTION_DETAIL_PAGE"].'f-'.mb_strtolower(str_replace(" ", "_", $arItem["CODE"])).'-is-'.$ar["URL_ID"].'/');
							}
$d_filt = new DateTime(date());
    $password_filt = $arParams["DOOM"]->createElement("lastmod", $d_filt->format('Y-m-d\TH:i:s').'+03:00'); 
    $user_filt->appendChild($login_filt); 
    $user_filt->appendChild($password_filt);
    $arParams["ROOT"]->appendChild($user_filt); 
	if($key==250) {
							echo $ar["VALUE"].' - '.$arParams["SECTION_DETAIL_PAGE"].$ar["URL_ID"].'/ <br />';
	}else{
		echo $ar["VALUE"].' - '.$arParams["SECTION_DETAIL_PAGE"].'f-'.mb_strtolower(str_replace(" ", "_", $arItem["CODE"])).'-is-'.$ar["URL_ID"].'/ <br />';
	}
							//echo 'Количество элементов: '.$ar["ELEMENT_COUNT"].'<br />';
							//print_r($ar);
							}
				}
}?>