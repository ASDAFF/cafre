#!/usr/bin/php -q
<?
$_SERVER["DOCUMENT_ROOT"] = "/var/www/www-root/data/www/cafre.ru";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

	if(CModule::IncludeModule('iblock')) {}
	
$arSelect = Array("ID", "IBLOCK_ID", "NAME");
$arFilter2 = Array("IBLOCK_ID"=>26, "PROPERTY_MIN_PRICE"=>false);
$res = CIBlockElement::GetList(Array('ID'=>'asc'), $arFilter2, false, false, $arSelect);		
while($ob = $res->GetNext()) {
	$PRICE=array();
	$resT = CIBlockElement::GetList(Array('ID'=>'asc'), Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$ob['ID']), false, false, $arSelect);		
	while($tp = $resT->GetNext()) {
		$rsPrices = CPrice::GetList(array(), array("PRODUCT_ID" => $tp['ID']));
 		while($arPrice = $rsPrices->Fetch()) {
			$PRICE[]=$arPrice["PRICE"];
		}
	}
	CIBlockElement::SetPropertyValuesEx(
		$ob['ID'],
		26,
		array(
			"MIN_PRICE" => min($PRICE),
		)
	);
}
	
	function getChildrenSection($id) {
		$childs=array();
		$all = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), array('IBLOCK_ID' => 26,  "SECTION_ID"=>$id));
		while ($child = $all->Fetch())
		{
			$childs[]=$child['ID'];
			$subchilds=getChildrenSection($child['ID']);
			$childs=array_merge($childs, $subchilds);			
		}
		return $childs;
	}
		
	function elementSections($id, $in_brends) {
		$arFilter = Array("IBLOCK_ID"=>26, "ACTIVE"=>"Y","GLOBAL_ACTIVE"=>"Y", array("LOGIC"=>"OR","PROPERTY_CATALOG_BREND"=>$id, "SECTION_ID"=>$id), "INCLUDE_SUBSECTIONS"=>'Y');
		$res = CIBlockElement::GetList(Array("ID"=>"DESC"), $arFilter, false, false, array('ID'));
		$allgroup=array();
		while($ob = $res->GetNextElement())
		{
			$element=$ob->GetFields();
			$db_old_groups = CIBlockElement::GetElementGroups($element['ID'], true);
			while($ar_group = $db_old_groups->Fetch()) {
				if(in_array($ar_group['ID'], $in_brends))  continue;
				if(!in_array($ar_group['ID'], $allgroup)) $allgroup[] = $ar_group["ID"];
			}
		}
		return $allgroup;
	}

	
	$arFilter = array('IBLOCK_ID' => 26,  "SECTION_ID"=>5338);
	$rsSections = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilter);
	while ($arSection = $rsSections->Fetch())
	{
		$in_brends=getChildrenSection($arSection['ID']);
		$in_brends[]=$arSection['ID'];
		$allgroups=elementSections($arSection['ID'], $in_brends);

		$menu=array();
		foreach($allgroups as $i) {
			$nav = CIBlockSection::GetNavChain(false, $i);
			$parent=0;
			while($section_p = $nav->Fetch()) {
				if( $section_p['DEPTH_LEVEL']==1) {
					if(!in_array($section_p['ID'], array_keys($menu['PARENT']))) $menu['PARENT'][$section_p['ID']]=$section_p;
					$parent=$section_p['ID'];
				}
				else {
					if(!in_array($section_p['ID'], array_keys($menu['CHILD']))) $menu['CHILD'][$section_p['ID']]=$section_p;
					if(!in_array($section_p['ID'], array_keys($menu['CHILD'][$section_p['IBLOCK_SECTION_ID']]['ITEMS']))) $menu['CHILD'][$section_p['IBLOCK_SECTION_ID']]['ITEMS'][$section_p['ID']]=$section_p;
				}
			}
		}
		
		$el = new CIBlockElement;
		$arLoadProductArray = Array(
			"IBLOCK_SECTION_ID" => false, 
			"IBLOCK_ID"      => 29,
			"NAME"           => $arSection['ID'],
			"DETAIL_TEXT"    => serialize($menu)
		);
		$arFilter = Array("IBLOCK_ID"=>29, "NAME"=>$arSection['ID']);
		$res = CIBlockElement::GetList(Array("ID"=>"DESC"), $arFilter, false, false);
		if($ob = $res->GetNextElement()) {
			$ID=$ob->GetFields();
			$el->Update($ID['ID'], $arLoadProductArray);
		}
		else $el->Add($arLoadProductArray);
		
	}	


?>