<?
// phpinfo();exit;
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");
		$arSelect = Array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_CODE1C", "CODE");
$arFilter = Array("IBLOCK_ID"=>26);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
$re2s = CIBlockSection::GetByID($arFields["IBLOCK_SECTION_ID"]);
if($ar_re2s = $re2s->GetNext()){
  //$ids_sec[] = $ar_re2s['ID'];
 }else{

	echo '<pre>';
	print_r($arFields);
	  echo '</pre>';

	  //$ids_del[] = $arFields["ID"];
	 
	  $mas[] = $arFields["IBLOCK_SECTION_ID"];

 /*$db_old_groups = CIBlockElement::GetElementGroups($arFields["ID"], true);
if($ar_group = $db_old_groups->Fetch()){
     echo '<pre>';
	 echo $arFields["ID"];
 print_r($ar_group);
 echo '</pre>';
 $el = new CIBlockElement;
 $arLoadProductArray = Array(
  "IBLOCK_SECTION" => $ar_group["ID"]
  );
$res2 = $el->Update($arFields["ID"], $arLoadProductArray);
}*/

 }
}
	/*	$arSelect3 = Array("ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_CODE1C", "PROPERTY_CML2_LINK");
$arFilter3 = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$ids_del);
$res3 = CIBlockElement::GetList(Array(), $arFilter3, false, Array(), $arSelect3);
while($ob3 = $res3->GetNextElement())
{
 $arFields3 = $ob3->GetFields();
  echo '<pre>';
 print_r($arFields3);
	  echo '</pre>';
	  $del = CIBlockElement::Delete($arFields3["ID"]);
}*/
 
 
echo implode(',', $mas);
 
?>