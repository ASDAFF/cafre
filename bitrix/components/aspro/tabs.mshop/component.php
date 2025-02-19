<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?\Bitrix\Main\Loader::includeModule('iblock');
$arTabs=$arFilterProp=$arShowProp=array();

$arResult["SHOW_SLIDER_PROP"] = false;
if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])){
	$arrFilter = array();
}
else{
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arrFilter))
		$arrFilter = array();
}

$arFilter = array( "ACTIVE" => "Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"]);
if($arParams["SECTION_ID"]){
	$arFilter[]=array("SECTION_ID"=>$arParams["SECTION_ID"],"INCLUDE_SUBSECTIONS"=>"Y" );
}elseif($arParams["SECTION_CODE"]){
	$arFilter[]=array("SECTION_CODE"=>$arParams["SECTION_CODE"],"INCLUDE_SUBSECTIONS"=>"Y" );
}
$rsProp = CIBlockPropertyEnum::GetList(Array("sort"=>"asc", "id"=>"desc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE"=>$arParams["TABS_CODE"]));
while($arProp=$rsProp->Fetch()){
	$arShowProp[$arProp["EXTERNAL_ID"]]=$arProp["VALUE"];
}

if($arShowProp){
	$arSelect = array(
		"LIMIT" => $arParams["PAGE_ELEMENT_COUNT"],
		"ID",
	);

	foreach($arShowProp as $key=>$prop){
		$arItems=array();
		$rsItem=CIBlockElement::GetList( array(), array_merge( $arFilter, $arrFilter, array( "PROPERTY_".$arParams["TABS_CODE"]."_VALUE" => array($prop) ) ), false, false, $arSelect);
		while($arItem=$rsItem->Fetch()){
			$arItems[]=$arItem["ID"];
		}
		if( $arItems ){
			$arTabs[$key]=$prop;
			$arResult["SHOW_SLIDER_PROP"] = true;
		}
	}
}else{
	echo "���������� ��������";
	die();
}
$arParams["PROP_CODE"] = $arParams["TABS_CODE"];
$arResult["TABS"] = $arTabs;
$this->IncludeComponentTemplate();?>