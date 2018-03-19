<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"]))
{
	$arAvailableThemes = array();
	$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
	if (is_dir($dir) && $directory = opendir($dir))
	{
		while (($file = readdir($directory)) !== false)
		{
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$arAvailableThemes[] = $file;
		}
		closedir($directory);
	}

	if ($arParams["TEMPLATE_THEME"] == "site")
	{
		$solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
		if ($solution == "eshop")
		{
			$theme = COption::GetOptionString("main", "wizard_eshop_adapt_theme_id", "blue", SITE_ID);
			$arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
		}
	}
	else
	{
		$arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
	}
}
else
{
	$arParams["TEMPLATE_THEME"] = "blue";
}
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

foreach($arResult["ITEMS"] as $key => $arItem)
{
	if($arItem["CODE"]=="IN_STOCK"){
		sort($arResult["ITEMS"][$key]["VALUES"]);
		if($arResult["ITEMS"][$key]["VALUES"])
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"]=$arItem["NAME"];
	}
}

global $MSHOP_SMART_FILTER, $filter_h1, $brends, $brend_in_catalog;
$n=0;
foreach($MSHOP_SMART_FILTER as $num => $value) {
	if($num=='=PROPERTY_250_VALUE' || $num=='=PROPERTY_250') {
		if($arResult['ITEMS'][$property_id]['NAME']=='') {
			$res = CIBlockSection::GetByID($value[0]);
			if($ar_res = $res->GetNext())
				$arResult['ITEMS'][250]['VALUES'][$value[0]]['VALUE']=$ar_res['NAME'];
		}
		$brend_in_catalog=str_replace('..', '', $arResult['ITEMS'][250]['VALUES'][$value[0]]['VALUE']);
	}
	elseif(!strpos($num, 'PROPERTY')===false) {
		$n++;
		if($n==1) $filter_h1 ='. ';
		$property_id=intval( explode('_', $num)[1]  );
		
		$filter_h1 .= ($n==1?$arResult['ITEMS'][$property_id]['NAME']:', '.mb_strtolower($arResult['ITEMS'][$property_id]['NAME'], 'cp-1251') ).': '.mb_strtolower($arResult['ITEMS'][$property_id]['VALUES'][$value[0]]['VALUE'], 'cp-1251');
	}
}
?>


<?$this->SetViewTarget('filter_dop');?>

<?
global $brend_in_catalog, $est_brend;
foreach($arResult["ITEMS"][250]['VALUES'] as $num=>$arItem) {	

	if($arItem['CHECKED']) {
		$brends=array();
		$brend_in_catalog=str_replace('..', '', $arItem['VALUE']);
		$est_brend=true;
		break;
	}
	$brends[]=$num;
}
if(!empty($brends) && count($brends)>0) {?>
	<div class="top_brand_block" >
	<span class="select_brand">Выберите бренд</span>
	<div class="list_brands"> 
	<?
	$ar_result=CIBlockSection::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>"26", "ID"=>$brends, 'DEPTH_LEVEL'=>2),false, Array("UF_IMG_BRAND", "UF_URL_SVG", "NAME", 'CODE', 'ID'));
	while($res2=$ar_result->GetNext()) {?>
		<a href="<?=$APPLICATION->GetCurPage().$res2['CODE'].'/'?>" >
		<?if($res2["UF_URL_SVG"]){?>
		<img width="100" src="<?=$res2["UF_URL_SVG"];?>"/>
		<?}elseif($res2["UF_IMG_BRAND"]){?>
			<?$file = CFile::ResizeImageGet($res2["UF_IMG_BRAND"], array('width'=>266, 'height'=>160), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
			<img width="100" src="<?=$file["src"];?>"/>
		<?}else {?>
			<?=$res2["NAME"]?>
		<?}?>
		</a>
	<?}?>
	</div>
	</div>
<?}?>
<?$this->EndViewTarget();?>