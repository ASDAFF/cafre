<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Сменить пароль");
	if(!$USER->isAuthorized()){LocalRedirect(SITE_DIR.'auth');} else {
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
			"USE_EXT" => "Y",
			"DELAY" => "N",
			"ALLOW_MULTI_SELECT" => "N"
			),
			false
		);?>	
	</div>
	<div class="right_block">
		<?$APPLICATION->IncludeComponent("bitrix:main.profile", "change_password", array(
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"SET_TITLE" => "N",
			"USER_PROPERTY" => array(
			),
			"SEND_INFO" => "N",
			"CHECK_RIGHTS" => "N",
			"USER_PROPERTY_NAME" => "",
			"AJAX_OPTION_ADDITIONAL" => ""
			),
			false
		);?>
	</div>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>