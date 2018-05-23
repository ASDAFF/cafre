<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$brands=array();
$props=array();
global $GLOBAL;
if($arResult["ITEMS"]){

	//echo $arParams['SECTION_DETAIL_PAGE'];
echo '<pre>';
//print_r();
echo '</pre>';

foreach($arResult['COMBO'] as $combo) {
	$str=array();
	
	foreach($combo as $key => $combo_props) {
		if($key == 250 || $key == 1 || $key == 3 || $key == 'OPT' || $key == 'BASE' || $key == 'Интернет Розница' || $combo_props == false || $combo_props == ''|| $combo_props == NULL)continue;
		if(!in_array($combo_props, array_keys($props[$key]))) {			
			foreach($arResult['ITEMS'][$key]['VALUES'] as $value2) {
				if($value2['VALUE']==$combo_props) {
					$str[]=strtolower($arResult['ITEMS'][$key]["CODE"]).'-is-'.$value2['URL_ID'].'/';
					$props[$key][$combo_props]=strtolower($arResult['ITEMS'][$key]["CODE"]).'-is-'.$value2['URL_ID'].'/';					
				}
			}			
		}
		else {			
			$str[]=$props[$key][$combo_props];
		}		
	}
	
	if(!empty($str)) {
		$GLOBAL = $arParams["SECTION_ID"];
		$strbrand='';
		//ищем раздел-бренд
		if($combo[250]) {
			if(!in_array($combo[250], array_keys($brands))) {
				foreach($arResult['ITEMS'][250]['VALUES'] as $value) {
					if($value['VALUE']==$combo[250]) {
						$resBrand = CIBlockSection::GetByID($value['URL_ID']);
						$resBrand = $resBrand->GetNext();						
						$strbrand=$resBrand['CODE'].'/';
						$brands[$combo[250]]=$resBrand['CODE'].'/';
					}
				}
			}
			else $strbrand=$brands[$combo[250]];
		}	
		
		
		
		foreach($str as $num1 => $chpu) {
			if(!$strbrand=='') {
				
				$user_b1 = $arParams["DOOM_VARS"]->createElement("url");
		$login_b1 = $arParams["DOOM_VARS"]->createElement("loc", 'https://cafre.ru'.$arParams['SECTION_DETAIL_PAGE'].$strbrand.'f-'.$chpu);
		$d_b1 = new DateTime(date());
		$password_b1 = $arParams["DOOM_VARS"]->createElement("lastmod", $d_b1->format('Y-m-d\TH:i:s').'+03:00'); 
		$user_b1->appendChild($login_b1); 
		$user_b1->appendChild($password_b1);
		$arParams["ROOT_VARS"]->appendChild($user_b1);
				//echo $arParams['SECTION_DETAIL_PAGE'].$strbrand.'f-'.$chpu."<br>";
		provEach($arParams['SECTION_DETAIL_PAGE'].$strbrand.'f-'.$chpu, $str, $num1, $arParams["ROOT_VARS"], $arParams["DOOM_VARS"]);
		/*$user_b2 = $arParams["DOOM_VARS"]->createElement("url");
		$login_b2 = $arParams["DOOM_VARS"]->createElement("loc", provEach($arParams['SECTION_DETAIL_PAGE'].$strbrand.'f-'.$chpu, $str, $num1));
		$d_b2 = new DateTime(date());
		$password_b2 = $arParams["DOOM_VARS"]->createElement("lastmod", $d_b2->format('Y-m-d\TH:i:s').'+03:00'); 
		$user_b2->appendChild($login_b2); 
		$user_b2->appendChild($password_b2);
		$arParams["ROOT_VARS"]->appendChild($user_b2);*/
			}
			
			
		provEach($arParams['SECTION_DETAIL_PAGE'].'f-'.$chpu, $str, $num1, $arParams["ROOT_VARS"], $arParams["DOOM_VARS"]);
		}
		/*
		//echo '<pre>';
		//print_r($arParams['SECTION_DETAIL_PAGE'].$str);
		//echo '</pre>';
		*/
	}
	//break;
}



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
					if($arItem["CODE"]!="IN_STOCK"){/*?>
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
						<?*/}
			
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
	/*if($key==250) {
							//echo $ar["VALUE"].' - '.$arParams["SECTION_DETAIL_PAGE"].$ar["URL_ID"].'/ <br />';
	}else{
		//echo $ar["VALUE"].' - '.$arParams["SECTION_DETAIL_PAGE"].'f-'.mb_strtolower(str_replace(" ", "_", $arItem["CODE"])).'-is-'.$ar["URL_ID"].'/ <br />';
	}*/
							//echo 'Количество элементов: '.$ar["ELEMENT_COUNT"].'<br />';
							//print_r($ar);
							}
				}
}?>