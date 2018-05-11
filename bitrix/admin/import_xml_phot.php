<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
CModule::includeModule('file');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<div class="welcome-panel">
  <h2>Обновление фото фидом м-косметики</h2>
  <a href="?GO=on">Погнали</a>
  <?
  if($_GET["GO"]){
/*подключаем xml файл*/
$xml1= simplexml_load_file("https://estel.m-cosmetica.ru/export_feed/google_merchant_mcm.xml");

$result_more = array();
/*проходим циклом по xml документу*/
foreach ($xml1->channel as $sort){
	foreach($sort->item as $v){
		$namespaces = $v->getNamespaces(true);
$dc = $v->children($namespaces["g"]);
//	$num = preg_replace('!^(-?)0*!', '\\1', $value2[1]); // для всех ситуаций
$arSelect = Array("ID", "NAME", "PROPERTY_CODE1C", "PREVIEW_PICTURE");
$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C"=>str_pad($dc->id,  11, "0", STR_PAD_LEFT));
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
if($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
/* echo '<pre>';
//print_r($v);
//echo str_pad($dc->id,  11, "0", STR_PAD_LEFT); echo '<br/>';
echo $dc->id; echo '<br/>';
echo $dc->image_link; echo '<br/>';
	echo '</pre>';*/
	foreach($dc->additional_image_link as $picgal){
		//echo '<pre>';
		//print_r();
		//echo '</pre>';
		//CFile::MakeFileArray(
		$result_more[$arFields["ID"]][] = array('VALUE'=>CFile::MakeFileArray($picgal),'DESCRIPTION'=>'arMorexml');
	}
/* echo '<pre>';
 echo 'ID: ';print_r($arFields["ID"]); echo '<br/>';
  echo 'NAME: ';print_r($arFields["NAME"]);echo '<br/>';
 echo '1C: '; print_r($arFields["PROPERTY_CODE1C_VALUE"]);echo '<br/>';
  echo 'PICTURE: '; print_r($arFields["PREVIEW_PICTURE"]);
 echo '</pre>';*/
 
//  echo '<pre>';
 //print_r(CFile::MakeFileArray($dc->image_link));
  //echo '</pre>';
 $new_onphot = CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("ON_PHOT" => CFile::MakeFileArray($dc->image_link)));
  
$el = new CIBlockElement;
$arLoadProductArray = Array(
  "PREVIEW_PICTURE" => CFile::MakeFileArray($dc->image_link),
  );
	$res = $el->Update($arFields["ID"], $arLoadProductArray);

}
	}
	
//echo'ID:'.$sort->id.' Имя:'.utf_win($sort->name, "w").'<BR>';
}
/*$arr = array('https://estel.m-cosmetica.ru/pictures/product/big/29235_big.jpg', 'https://estel.m-cosmetica.ru/pictures/product/big/29236_big.jpg');
	foreach($arr as $picgal){
		//echo '<pre>';
		//print_r();
		//echo '</pre>';
		//CFile::MakeFileArray(
		$result_more[83719][] = array('VALUE'=>CFile::MakeFileArray($picgal),'DESCRIPTION'=>'arMorexml');
	}*/
foreach($result_more as $key => $val){
CIBlockElement::SetPropertyValuesEx($key, 26, array("MORE_PHOTO" => $val));
}
}
  ?>
  <p id="response"></p>
 </div>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>