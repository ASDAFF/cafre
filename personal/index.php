<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>
<?
global $USER;
if(!$USER->isAuthorized()){
	LocalRedirect(SITE_DIR.'auth/?backurl='.SITE_DIR.'personal/');
}
else{
	LocalRedirect(SITE_DIR.'personal/personal-data');
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>