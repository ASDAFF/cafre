<?
global $MSHOP_SMART_FILTER, $filter_h1, $catalog_section_name, $catalog_seo, $brend_in_catalog;
if($brend_in_catalog) $filter_h1=$filter_h1.' '.$brend_in_catalog;
$catalog_seo='Y';	
if(strpos($_SERVER['REQUEST_URI'], 'PAGEN_')) {
	foreach ($_GET as $key => $value) {
		if(!(strpos($key, 'PAGEN_')===false)) {
			$page_num = $value; 
			break;
		}
	}			
}

if (isset($page_num)||!empty($MSHOP_SMART_FILTER))  {
	$page_seo_params["title"] = $APPLICATION->GetTitle();
	if (empty($MSHOP_SMART_FILTER)) {
		if ($page_num!='1') {
			$APPLICATION->SetPageProperty("title", $page_seo_params["title"]." (Страница ".$page_num.")");   
		}
    }
	elseif (($page_num=='1'||!isset($page_num))&&!empty($MSHOP_SMART_FILTER)) {
        $APPLICATION->SetPageProperty("title",  str_replace_once($catalog_section_name, $catalog_section_name.$filter_h1, $page_seo_params["title"]));  
    }
	else {
		$APPLICATION->SetPageProperty("title",  str_replace_once($catalog_section_name, $catalog_section_name.$filter_h1, $page_seo_params["title"])." (Страница ".$page_num.")");  
	}
}

if($arSection["IBLOCK_SECTION_ID"]==5338&&$arResult["VARIABLES"]["SECTION_ID"]==0&&$arResult["VARIABLES"]["SECTION_CODE"]=='') {
	$page_seo_params["title"] = $arSection['NAME'].($arSection['RUSNAME']!=''?' ('.$arSection['RUSNAME'].')':'');
}		
else{
	$page_seo_params["title"] = $APPLICATION->GetTitle().($arSection['RUSNAME']!=''?' ('.$arSection['RUSNAME'].')':'');
}

if( !(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy/')===false) && substr_count($arResult['VARIABLES']['SECTION_CODE_PATH'], '/')==1 ) {
	$this->SetViewTarget('h1');echo $page_seo_params["title"].$filter_h1;$this->EndViewTarget();
	$APPLICATION->SetPageProperty("title", "Каталог товаров бренда ".$page_seo_params["title"].$filter_h1."".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	$APPLICATION->SetPageProperty("keywords", $page_seo_params["title"].", купить ".$page_seo_params["title"].$filter_h1.", каталог ".$page_seo_params["title"].$filter_h1.", косметика ".$page_seo_params["title"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));  
	$APPLICATION->SetPageProperty("description", "Покупая косметику ".$page_seo_params["title"].$filter_h1." в интернет-магазине Cafre – вы получаете гарантию самый низкой цены и высшее качество продукции. У нас самый широкий ассортимент товаров ".$page_seo_params["title"].$filter_h1." в России.".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));   
}
elseif(!(strpos($arResult['VARIABLES']['SECTION_CODE_PATH'], 'vse_brendy/')===false) && substr_count($arResult['VARIABLES']['SECTION_CODE_PATH'], '/')>1 ) {
	$nav = CIBlockSection::GetNavChain(false, $arResult['VARIABLES']['SECTION_ID']);
	while($section_p = $nav->GetNext()) {
		if($section_p['DEPTH_LEVEL']==1) continue;		
		if($section_p['DEPTH_LEVEL']==2) {$dop_h1_brend = $section_p['NAME'];}		
		elseif($section_p['ID']!=$arResult['VARIABLES']['SECTION_ID']&&$section_p['DEPTH_LEVEL']==4) {$dop_h1_1 = $section_p['NAME'];}		
		elseif($section_p['ID']!=$arResult['VARIABLES']['SECTION_ID']&&$section_p['DEPTH_LEVEL']!=3) $dop_h1_a .= ' '.$section_p['NAME'];
		else $dop_h1 = $section_p['NAME'];
	} 
	$page_seo_params["title"] = ($dop_h1_1?$dop_h1_1:$dop_h1.$filter_h1).' '.$dop_h1_brend.$dop_h1_a.($dop_h1_1?' '.$dop_h1.$filter_h1:'');
	$this->SetViewTarget('h1');echo $page_seo_params["title"];$this->EndViewTarget();
	$APPLICATION->SetPageProperty("title", $page_seo_params["title"]." - купить по низким ценам в интернет-магазине Cafre".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	$APPLICATION->SetPageProperty("keywords", $page_seo_params["title"].", купить ".$page_seo_params["title"].(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	$APPLICATION->SetPageProperty("description", "".$page_seo_params["title"].", огромный ассортимент. Гарантия качества от производителя и лучшие цены на рынке - в наличии!".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));   
}
else {
	if($section["UF_FILT_H"] && $filter_h1){
		$this->SetViewTarget('h1');echo $section["UF_FILT_H"].$filter_h1;$this->EndViewTarget();
	}
	elseif($section["UF_SEO_H"]){
	$this->SetViewTarget('h1');echo $section["UF_SEO_H"].$filter_h1;$this->EndViewTarget();
	}else{
	$this->SetViewTarget('h1');echo $section["NAME"].$filter_h1;$this->EndViewTarget();	
	}
	$APPLICATION->SetPageProperty("keywords", $page_seo_params["title"].$filter_h1.", купить ".$page_seo_params["title"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
	if($section["UF_SEO_DESC"]){
		$APPLICATION->SetPageProperty("description", "".$section["UF_SEO_DESC"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));   
	}else{
		$exp_getfilt = explode('f-',$APPLICATION->GetCurPage());
		if($exp_getfilt){
			$exp_getfilt2 = explode('/',$APPLICATION->GetCurPage());
			$exp_getfilt3 = array();
			$new_desc = '';
			foreach($exp_getfilt2 as $get){
				if(strpos($get, '-is-')){
					$get2 = trim($get, 'f-');
					$exp_getfilt3[] = explode('-is-', $get2);
				}
			}
			$mas_getfil = array();
			foreach($exp_getfilt3 as $vf){
				$arSelect_filt = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
				$arFilter_filt = Array("IBLOCK_ID"=>31, "ACTIVE"=>"Y", "XML_ID"=>strtoupper($vf[0]));
				$res_filt = CIBlockElement::GetList(Array(), $arFilter_filt, false, Array(), $arSelect_filt);
				while($ob_filt = $res_filt->GetNextElement()) {
					$arProps_filt = $ob_filt->GetProperties();
					foreach($arProps_filt["NAME_FARM"]["VALUE"] as $kk => $prop){
						if($prop != $vf[1])continue;
						$new_desc.= $arProps_filt["NAME_FARM"]["DESCRIPTION"][$kk]." ";
					}
				}
			}
			$exbsec_d = explode('catalog_brend-is-',$arResult["VARIABLES"]["SMART_FILTER_PATH"]);
			if($exbsec_d[1]){
				$APPLICATION->SetPageProperty("description", " Огромный ассортимент товаров из раздела «".$section["NAME"]." ".iconv("UTF-8", "WINDOWS-1251", mb_strtolower(iconv("WINDOWS-1251", "UTF-8", $new_desc))).$exbsec_d[1]."» представлены у нас по самым низким ценам. Гарантии качества от производителя и подарки в каждом заказе - в наличии!".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
			}else{
				$APPLICATION->SetPageProperty("description", " Огромный ассортимент товаров из раздела «".$section["NAME"]." ".iconv("UTF-8", "WINDOWS-1251", mb_strtolower(iconv("WINDOWS-1251", "UTF-8", $new_desc)))."» представлены у нас по самым низким ценам. Гарантии качества от производителя и подарки в каждом заказе - в наличии!".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
			}
		}else{
			$APPLICATION->SetPageProperty("description", "".$page_seo_params["title"].$filter_h1.", огромный ассортимент. Гарантия качества от производителя и лучшие цены на рынке - в наличии!".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));   
		}
	}
	if(substr_count($arResult['VARIABLES']['SECTION_CODE_PATH'], '/')>0) {
		if($section["UF_FILT_H"] && $filter_h1){
			$APPLICATION->SetPageProperty("title", $section["UF_FILT_H"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
		}
		elseif($section["UF_SEO_TITLE"]){
			$APPLICATION->SetPageProperty("title", $section["UF_SEO_TITLE"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
		}else{
			$APPLICATION->SetPageProperty("title", $page_seo_params["title"].$filter_h1." - купить по низким ценам в интернет-магазине Cafre".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
		}
	}else{
		if($section["UF_FILT_H"] && $filter_h1){
			$APPLICATION->SetPageProperty("title", $section["UF_FILT_H"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
		}
		elseif($section["UF_SEO_TITLE"]){?>
			<span class="old-sec-name"><?echo $section["UF_SEO_TITLE"];?></span>
		<?
		$APPLICATION->SetPageProperty("title", $section["UF_SEO_TITLE"].$filter_h1.(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
		}else{
		$APPLICATION->SetPageProperty("title", $page_seo_params["title"].$filter_h1." - купить по низким ценам в интернет-магазине Cafre".(isset($page_num)&&$page_num!='1'?" (Страница ".$page_num.")":''));
		}
	}
}
if($section["UF_SEO_H"]){?>
	<span class="old-sec-name"><?echo $section["UF_SEO_H"];?></span>
<?}else{	?>
	<span class="old-sec-name"><?echo $section["NAME"];?></span>
<? }?>