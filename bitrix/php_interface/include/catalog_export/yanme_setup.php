<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/load/yandex_setup.php");
//<title>Yandex_me</title>
/*
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
CModule::includeModule('file');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$xml = new DomDocument('1.0', 'utf-8'); //создаем новый экземпляр<
$rss = $xml->appendChild($xml->createElement('rss')); // добавляем тег rss
$rss->setAttribute('version', '2.0'); //атрибуты
$rss->setAttribute('xmlns:g', 'http://base.google.com/ns/1.0');//атрибуты/
$main_title = $rss->appendChild($xml->createElement('title'));
$main_title->appendChild($xml->createTextNode('Cafre.ru'));
$main_link = $rss->appendChild($xml->createElement('link'));
$main_link->appendChild($xml->createTextNode(ROOT));
$main_desc = $rss->appendChild($xml->createElement('description'));
$main_desc->appendChild($xml->createTextNode('Краткое описание'));

$arSelect44 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID", 'PROPERTY_*', "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PREVIEW_TEXT", "DETAIL_TEXT");
$arFilter44 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>10), $arSelect44);
while($ob44 = $res44->GetNextElement()){
  $arFields44 = $ob44->GetFields();
 /*
$item = $items->appendChild($xml->createElement('item'));
$id = $item->appendChild($xml->createElement('g:id'));
$id->appendChild($xml->createTextNode($arFields44['ID']));
$id_xml = $item->appendChild($xml->createElement('g:xml_id'));
$id_xml->appendChild($xml->createTextNode($arFields44['XML_ID']));
$title = $item->appendChild($xml->createElement('g:title'
));
$title->appendChild($xml->createTextNode($arFields44['NAME']));
$condition = $item->appendChild($xml->createElement('g:condition'));
$condition->appendChild($xml->createTextNode('new'));
$url = $item->appendChild($xml->createElement('g:url'));
$url->appendChild($xml->createTextNode('http://test.cafre.ru'.$arFields44['DETAIL_PAGE_URL']));
$price = $item->appendChild($xml->createElement('g:price'));
$price->appendChild($xml->createTextNode($arFields44['products_price']));
//img link
$image_link = $item->appendChild($xml->createElement('g:image_link'));
$image_link->appendChild(CFile::GetPath($arFields44['PREVIEW_PICTURE']));
$shipping = $item->appendChild($xml->createElement('g:shipping'));
$shipping->appendChild($xml->createTextNode('true'));
$description = $item->appendChild($xml->createElement('g:description'));
 if($arFields44['PREVIEW_TEXT'] && $arFields44['PREVIEW_TEXT'] != 'NULL'){
$description->appendChild($xml->createTextNode($arFields44['PREVIEW_TEXT']));
 }else{
$description->appendChild($xml->createTextNode($arFields44['DETAIL_TEXT'])); 
 }
$availability = $item->appendChild($xml->createElement('g:availability'));
$availability->appendChild($xml->createTextNode('in stock'));

echo '<pre>';
print_r($arFields44);
echo '</pre>';
}
$items = $rss->appendChild($xml->createElement('channel'));
/*
$xml->formatOutput = true; #-> устанавливаем выходной формат документа в true
if($xml->save('feed.xml')) {
echo 'Обновление фида завершилось успешно!
';
}else {
echo "Не удалось сохранить файл фида данных. Возможно у файла не достаточно прав доступа
";
}
$xml->save('feed.xml'); #-> сохраняем файл
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");*/
?>
