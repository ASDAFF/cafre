<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule('iblock'); 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
if(isset($_GET)&&$_GET['start']=='Y') {
	$IBLOCK_ID =26;
	$SETUP_FILE_NAME='/sitemap_iblock_0.xml';
	if (!$fp = @fopen($_SERVER["DOCUMENT_ROOT"].$SETUP_FILE_NAME, "wb"))
	{
		echo 'error file';
	}
	else {
		@fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>');
		@fwrite($fp, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
		$arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y');
		$rsE = CIBlockElement::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilter, false, false);
		while ($tovar = $rsE->GetNextElement())
		{
			$arFields = $tovar->GetFields(); 
			@fwrite($fp, "<url><loc>https://".$_SERVER['SERVER_NAME'].$arFields['DETAIL_PAGE_URL']."</loc><lastmod>".date('c', strtotime($arFields['TIMESTAMP_X']))."</lastmod></url>");			
		}
		@fwrite($fp, '</urlset>');
		header('HTTP/1.1 200 OK');			
			header('Location: https://'.$_SERVER['SERVER_NAME'].'/bitrix/admin/sitemap_element.php');
			exit;
	}
}
else {?>
<a href="?start=Y">Начать</a>
<?}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>