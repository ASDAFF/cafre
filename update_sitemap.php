<?php
$_SERVER["DOCUMENT_ROOT"] = '/var/www/www-root/data/www/cafre.ru';
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

//----Page----//
  $dom4 = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root4 = $dom4->createElement("urlset"); // Создаём корневой элемент
  $root4->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom4->appendChild($root4);
  $arr_page_text = array('https://cafre.ru/company/', 'https://cafre.ru/help/info_order/', 'https://cafre.ru/contacts/stores/', 'https://cafre.ru/contacts/', 'https://cafre.ru/politik/', 'https://cafre.ru/company/return/', 'https://cafre.ru/help/', 'https://cafre.ru/help/warranty/', 'https://cafre.ru/info/faq/', 'https://cafre.ru/sitemap/', 'https://cafre.ru/info/articles/');
  foreach($arr_page_text as $vp){
		$user4 = $dom4->createElement("url");
		$login4 = $dom4->createElement("loc", $vp);
		$d4 = new DateTime(date());
		$password4 = $dom4->createElement("lastmod", $d4->format('Y-m-d\TH:i:s').'+03:00'); 
		$user4->appendChild($login4); 
		$user4->appendChild($password4);
		$root4->appendChild($user4);
  }
	$dom4->save($_SERVER["DOCUMENT_ROOT"]."/sitemap_page.xml");
//----EndPage----//

//----News----//
  $dom3 = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root3 = $dom3->createElement("urlset"); // Создаём корневой элемент
  $root3->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom3->appendChild($root3);
  $arSelect_elem = Array("ID", "NAME", "DATE_ACTIVE_FROM", "DETAIL_PAGE_URL");
$arFilter_elem = Array("IBLOCK_ID"=>9, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res_elem = CIBlockElement::GetList(Array(), $arFilter_elem, false, Array(), $arSelect_elem);
while($ob_elem = $res_elem->GetNextElement())
{
 $arFields_elem = $ob_elem->GetFields();
  $year = explode('.', $arFields_elem["DATE_ACTIVE_FROM"]);
 $page = str_replace("#YEAR#", $year[2], $arFields_elem["DETAIL_PAGE_URL"]);
 $user3 = $dom3->createElement("url");
    $login3 = $dom3->createElement("loc", 'https://cafre.ru'.$page);
$d3 = new DateTime($arFields_elem["DATE_ACTIVE_FROM"]);
    $password3 = $dom3->createElement("lastmod", $d3->format('Y-m-d\TH:i:s').'+03:00'); 
    $user3->appendChild($login3); 
    $user3->appendChild($password3);
    $root3->appendChild($user3); 
}
$dom3->save($_SERVER["DOCUMENT_ROOT"]."/sitemap_news.xml");
//----EndNews----//

//----Sec----//
 $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root = $dom->createElement("urlset"); // Создаём корневой элемент
  $root->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom->appendChild($root);
  
   $dom11 = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root11 = $dom11->createElement("urlset"); // Создаём корневой элемент
  $root11->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom11->appendChild($root11);
  $arFilter = array('IBLOCK_ID' => 26, "ACTIVE"=>"Y"); 
   $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
   while ($arSect = $rsSect->GetNext())
   {
	 
	  if(strpos($arSect["SECTION_PAGE_URL"], 'vse_brendy') && ($arSect["DEPTH_LEVEL"] != 1 && $arSect["DEPTH_LEVEL"] !=2))continue;
    $user = $dom->createElement("url");
    $login = $dom->createElement("loc", 'https://cafre.ru'.$arSect["SECTION_PAGE_URL"]);
$d = new DateTime($arSect["DATE_CREATE"]);
    $password = $dom->createElement("lastmod", $d->format('Y-m-d\TH:i:s').'+03:00');
    $user->appendChild($login); 
    $user->appendChild($password);
    $root->appendChild($user);
  }
  $dom->save($_SERVER["DOCUMENT_ROOT"]."/sitemap_sec.xml");
//----EndSec----//

//----Tov----//
  $dom5 = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root5 = $dom5->createElement("urlset"); // Создаём корневой элемент
  $root5->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom5->appendChild($root5);
$arSelect_tov = Array("ID", "NAME", "DATE_ACTIVE_FROM", "DETAIL_PAGE_URL");
$arFilter_tov = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res_tov = CIBlockElement::GetList(Array(), $arFilter_tov, false, Array(), $arSelect_tov);
while($ob_tov = $res_tov->GetNextElement())
{
 $arFields_tov = $ob_tov->GetFields();
 $user5 = $dom5->createElement("url");
    $login5 = $dom5->createElement("loc", 'https://cafre.ru'.$arFields_tov["DETAIL_PAGE_URL"]);
$d5 = new DateTime($arFields_tov["DATE_ACTIVE_FROM"]);
    $password5 = $dom5->createElement("lastmod", $d5->format('Y-m-d\TH:i:s').'+03:00'); 
    $user5->appendChild($login5); 
    $user5->appendChild($password5);
    $root5->appendChild($user5); 
}
$dom5->save($_SERVER["DOCUMENT_ROOT"]."/sitemap_tov.xml");
//----EndTov----//

$dom_filt = new domDocument("1.0", "utf-8");
  $root_filt = $dom_filt->createElement("urlset");
  $root_filt->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom_filt->appendChild($root_filt);
$arr_filt_sec = array();
	
	$arFilter2 = array('IBLOCK_ID' => 26, "ACTIVE"=>"Y"); 
   $rsSect2 = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter2);
   $GLOBAL = '';
   $num_sec = 1;
   while ($arSect2 = $rsSect2->GetNext())
   {
	if( !(strpos($arSect2['SECTION_PAGE_URL'], '/vse_brendy/')===false)) continue;
	
	$dom_filt_n = new domDocument("1.0", "utf-8");
  $root_filt_n = $dom_filt_n->createElement("urlset");
  $root_filt_n->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom_filt_n->appendChild($root_filt_n);
	$APPLICATION->IncludeComponent(
		"lets:catalog.smart.filter",
		"main_ajax_sitemap",
		Array(
			"HIDE_NOT_AVAILABLE"=> "L",
			"IBLOCK_TYPE" => "new_cat",
			"IBLOCK_ID" => 26,
			"SECTION_ID" => $arSect2['ID'],
			"FILTER_NAME" => "MSHOP_SMART_FILTER",
			"PRICE_CODE" => array(
			0 => "BASE",
			1 => "OPT",
			2 => "Интернет Розница",
			),
			"CACHE_TYPE" => "N",
			"CACHE_TIME" => "",
			"CACHE_NOTES" => "",
			"CACHE_GROUPS" => "N",
			"SAVE_IN_SESSION" => "N",
			//"XML_EXPORT" => "Y",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			//"SHOW_HINTS" => "Y",
			'CONVERT_CURRENCY' => "Y",
			'CURRENCY_ID' => "RUB",
			//"INSTANT_RELOAD" => "Y",
			"VIEW_MODE" => "vertical",
			//"SEF_MODE" => "Y",
			"SEF_RULE" => "/catalog/#SECTION_CODE_PATH#/f-#SMART_FILTER_PATH#/",
			"SMART_FILTER_PATH" => "",
			"SECTION_DETAIL_PAGE"=>$arSect2["SECTION_PAGE_URL"],
			"ROOT" => $root_filt,
			"DOOM" => $dom_filt,
			"ROOT_VARS"=>$root_filt_n,
			"DOOM_VARS"=>$dom_filt_n,
		),
		$component);
		//print_r($GLOBAL);
		if($GLOBAL == $arSect2['ID']){
   $dom_filt_n->save($_SERVER["DOCUMENT_ROOT"]."/sitemap_filt_raz_".$arSect2['CODE']."_vars.xml");
   $arr_filtr[] = "sitemap_filt_raz_".$arSect2['CODE']."_vars.xml";
		}
   }
 
   $dom_filt->save($_SERVER["DOCUMENT_ROOT"]."/sitemap_filt_raz.xml");

 //----FaterSiteMap----// 
$arr_site = array("sitemap_page.xml", "sitemap_news.xml", "sitemap_sec.xml", "sitemap_tov.xml", "sitemap_filt_raz.xml");  
$result_site = array_merge($arr_site, $arr_filtr);

   $dom2 = new domDocument("1.0", "utf-8");
  $root2 = $dom2->createElement("sitemapindex");
  $root2->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
  $dom2->appendChild($root2);
  foreach($result_site as $sitemap){
    $user2 = $dom2->createElement("sitemap"); // Создаём узел "user"
    $login2 = $dom2->createElement("loc", 'https://cafre.ru/'.$sitemap);
$d2 = new DateTime(date());
    $password2 = $dom2->createElement("lastmod", $d2->format('Y-m-d\TH:i:s').'+03:00');
    $user2->appendChild($login2); 
    $user2->appendChild($password2);
    $root2->appendChild($user2);
 }
  $dom2->save($_SERVER["DOCUMENT_ROOT"]."/sitemap.xml");
  //----EndFaterSiteMap----// 
 

?>