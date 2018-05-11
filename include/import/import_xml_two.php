#!/usr/bin/php -q
<?
$_SERVER["DOCUMENT_ROOT"] = "/var/www/www-root/data/www/cafre.ru";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
/*подключаем xml файл*/
$xml1= simplexml_load_file("https://estel.m-cosmetica.ru/export_feed/google_merchant_mcm.xml", "SimpleXMLElement", LIBXML_NOCDATA);

$result_more = array();
foreach ($xml1->channel as $sort){
	foreach($sort->item as $v){
		$namespaces = $v->getNamespaces(true);
$dc = $v->children($namespaces["g"]);
$arSelect = Array("ID", "NAME", "PROPERTY_artIk", "PREVIEW_PICTURE");
$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_artIk"=>iconv('UTF-8','WINDOWS-1251',$dc->mpn));
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
if($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();

	if($dc->additional_image_link){
	foreach($dc->additional_image_link as $picgal){
		$result_more[$arFields["ID"]][] = array('VALUE'=>CFile::MakeFileArray((string)$picgal[0]),'DESCRIPTION'=>'arMorexml');
	}
	}

}
	}
}
if($result_more){
foreach($result_more as $key => $val){
CIBlockElement::SetPropertyValuesEx($key, 26, array("MORE_PHOTO" => $val));
}
}
?>