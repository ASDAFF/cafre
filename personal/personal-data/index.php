<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Персональные данные");
	if(!$USER->isAuthorized()){LocalRedirect(SITE_DIR.'auth');} else {
?>
	<div class="left_block">
	<div>
	<p>
	<h5>
	Бонусных баллов: 
								<?
								$arSelect = Array("ID", "NAME", "PROPERTY_ATT_BONUS", "PROPERTY_ATT_USER");
								$arFilter = Array("IBLOCK_ID"=>32, "ACTIVE"=>"Y", "PROPERTY_ATT_USER_VALUE"=>$GLOBALS['USER']->GetID());
								$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
								//$arrp = array();
								if($ob = $res->GetNextElement())
								{
									$arFields = $ob->GetFields();
									if($arFields["PROPERTY_ATT_USER_VALUE"] == $GLOBALS['USER']->GetID()){
										echo ($arFields["PROPERTY_ATT_BONUS_VALUE"]?$arFields["PROPERTY_ATT_BONUS_VALUE"]:'0');
									}else{
										echo '0';
									}
								}?>
	</h5>
	</p>
	</div>
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
		<?$APPLICATION->IncludeComponent("bitrix:main.profile", "profile", array(
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"SET_TITLE" => "N",
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