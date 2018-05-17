#!/usr/bin/php -q		
<?
$_SERVER["DOCUMENT_ROOT"] = "/var/www/cafre_prob/data/www/cafre.test.letsrock.pro";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
/*подключаем xml файл*/
$file = 'https://estel.m-cosmetica.ru/export_feed/google_merchant_mcm.xml';
$xml1= simplexml_load_file($file, "SimpleXMLElement", LIBXML_NOCDATA);
$result_more = array();
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/image_import.txt', 'start '.date('d.m.Y H:i:s'));
/*проходим циклом по xml документу*/
foreach ($xml1->channel as $sort){
	foreach($sort->item as $v){	
		$namespaces = $v->getNamespaces(true);
		$dc = $v->children($namespaces["g"]);
		$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_artIk", "PREVIEW_PICTURE", "PROPERTY_MORE_PHOTO", "PROPERTY_*");
		$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_artIk"=>iconv('UTF-8','WINDOWS-1251',$dc->mpn));
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
		if($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			if($dc->additional_image_link){
				$rs = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], [], ['CODE' => 'MORE_PHOTO']);
				while($arFileProp = $rs->Fetch()) {	
					$serverfile_more = CFile::GetByID($arFileProp['VALUE']);
					$ar_more[$arFileProp['PROPERTY_VALUE_ID']] = $serverfile_more->arResult[0]["ORIGINAL_NAME"];
				}
				foreach($dc->additional_image_link as $picgal){
					$strurl_more = explode('/',(string)$picgal[0]);
					if(in_array($strurl_more[6], $ar_more)) {						
						unset($ar_more[array_search($strurl_more[6], $ar_more)]);
					}					
					else $adding = CIBlockElement::SetPropertyValueCode($arFields["ID"], 'MORE_PHOTO',  array('VALUE'=>CFile::MakeFileArray((string)$picgal[0]),'DESCRIPTION'=>'arMorexml'));					
				}
				foreach($ar_more as $k=> $del) {
					$dbRes = $DB->Query( "DELETE FROM b_iblock_element_property WHERE ID=".IntVal($k) );
				}
			}
			if((string)$dc->image_link[0]){
				$strurl = explode('/',(string)$dc->image_link[0]);
				$serverfile = CFile::GetByID($arFields["PREVIEW_PICTURE"]);
				if($strurl[6] != $serverfile->arResult[0]["ORIGINAL_NAME"]) {
					$linkfile = CFile::MakeFileArray((string)$dc->image_link[0]);
					$new_onphot = CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("ON_PHOT" => $linkfile));
					$el = new CIBlockElement;
					$arLoadProductArray = Array("PREVIEW_PICTURE" => $linkfile,);
					$res = $el->Update($arFields["ID"], $arLoadProductArray);
				}
			}
		}
	}
}
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/image_import.txt', 'end '.date('d.m.Y H:i:s'), FILE_APPEND);
//30 5 * * * /usr/bin/php -f /var/www/www-root/data/www/cafre.ru/include/import/import_xml.php
?>