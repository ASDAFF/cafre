#!/usr/bin/php -q
<?
$_SERVER["DOCUMENT_ROOT"] = "/var/www/www-root/data/www/cafre.ru";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
/*подключаем xml файл*/
$xml1= simplexml_load_file("https://estel.m-cosmetica.ru/export_feed/google_merchant_mcm.xml", "SimpleXMLElement", LIBXML_NOCDATA);

$result_more = array();
/*проходим циклом по xml документу*/
foreach ($xml1->channel as $sort){
	foreach($sort->item as $v){
		$namespaces = $v->getNamespaces(true);
$dc = $v->children($namespaces["g"]);
//	$num = preg_replace('!^(-?)0*!', '\\1', $value2[1]); // для всех ситуаций

$arSelect = Array("ID", "NAME", "PROPERTY_artIk", "PREVIEW_PICTURE");
$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_artIk"=>iconv('UTF-8','WINDOWS-1251',$dc->mpn));
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
if($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 /*
 echo '<pre>';
 print_r();echo '<br/>';
 print_r(iconv('UTF-8','WINDOWS-1251',$dc->mpn));echo '<br/>';
  echo 'NAME: ';print_r($arFields["NAME"]);echo '<br/>';
 echo '1C: '; print_r($arFields["PROPERTY_artIk_VALUE"]);echo '<br/>';
  echo 'PICTURE: '; print_r($arFields["PREVIEW_PICTURE"]);
 echo '</pre>';
 	
//  echo '<pre>';
 //print_r(CFile::MakeFileArray($dc->image_link));
  //echo '</pre>';
 */
  if((string)$dc->image_link[0]){
	  $linkfile = CFile::MakeFileArray((string)$dc->image_link[0]);
	  $new_onphot = CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("ON_PHOT" => $linkfile));
	}

}
	}
}
?>