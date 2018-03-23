<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
	
$all_b=array();	
$arFilter = Array('IBLOCK_ID'=>26, "DEPTH_LEVEL"=>3);
 $db_list = CIBlockSection::GetList(Array(), $arFilter, true);
 // $db_list->NavStart(20);
  while($ar_result = $db_list->GetNext())
  {
	  
	  if(strripos($ar_result["SECTION_PAGE_URL"], "vse_brendy"))continue;
	  $uf = array();
	$arSelect = Array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_CATALOG_BREND");
	$arFilter2 = Array("IBLOCK_ID"=>26, "ACTIVE"=>"Y","IBLOCK_SECTION_ID"=>$ar_result["ID"], "!PROPERTY_CATALOG_BREND"=>FALSE, "INCLUDE_SUBSECTIONS"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter2, Array("PROPERTY_CATALOG_BREND"), Array(), $arSelect);
while($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 if(in_array($arFields["PROPERTY_CATALOG_BREND_VALUE"], array_keys($all_b))){
	 $uf[]=$all_b[$arFields["PROPERTY_CATALOG_BREND_VALUE"]];
 }else{
	  $arFilter2 = Array('IBLOCK_ID'=>26, "ID"=>$arFields["PROPERTY_CATALOG_BREND_VALUE"]);
  $db_list2 = CIBlockSection::GetList(Array(), $arFilter2, false, array("UF_IMG_BRAND"));
  if($ar_result2 = $db_list2->GetNext())
  {
	  $all_b[$ar_result2["ID"]]=array($ar_result2["NAME"], CFile::GetPath($ar_result2["UF_IMG_BRAND"]), $ar_result2["CODE"]);
	  $uf[]=array($ar_result2["NAME"], CFile::GetPath($ar_result2["UF_IMG_BRAND"]), $ar_result2["CODE"]);
  }
	 }
	  //$new_mas[$arFields["IBLOCK_SECTION_ID"]][]=array($arFields["PROPERTY_CATALOG_BREND_VALUE"]);
}
$bs = new CIBlockSection;
	$arFields3 = Array(
  "IBLOCK_ID" => 26,
  "UF_BRAND_ID" => serialize($uf)
  );
$res2 = $bs->Update($ar_result["ID"], $arFields3);
  }
  
    echo "<pre>";
	  print_r($uf);
	  echo "</pre>";
//$result = array_unique($new_mas);
	/*	$brand_list = '';
	  foreach($new_mas as $key=>$val){
		   

	  }
	 echo "<pre>";
	
	  print_r($new_mas);
	  echo "</pre>";
	  
	*/
	  //UF_BRAND_ID
  ?>