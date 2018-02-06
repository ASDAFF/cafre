<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подписка на новости");
?>
<div class="left_block">
	<?$APPLICATION->IncludeComponent("bitrix:menu", "left_menu", array(
		"ROOT_MENU_TYPE" => "left",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
		),
		false
	);?>	
</div>
<div class="right_block">
	<?$APPLICATION->IncludeComponent(
		"bitrix:subscribe.edit",
		"main",
		Array(
			"AJAX_MODE" => "N",
			"SHOW_HIDDEN" => "N",
			"ALLOW_ANONYMOUS" => "Y",
			"SHOW_AUTH_LINKS" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"SET_TITLE" => "N",
			"AJAX_OPTION_SHADOW" => "Y",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N"
		),
	false
	);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>