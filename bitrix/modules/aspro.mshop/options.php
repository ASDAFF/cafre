<?
global $APPLICATION;
IncludeModuleLangFile(__FILE__);
$RIGHT = $APPLICATION->GetGroupRight(CMShop::moduleID);

if($RIGHT >= "R"){
	$res = CMShop::getModuleOptionsList();
	$arTabs = $res["TABS"];

	$tabControl = new CAdminTabControl("tabControl", $arTabs);

	if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT >= "W" && check_bitrix_sessid()){
		global $APPLICATION, $CACHE_MANAGER;
		
		if(strlen($RestoreDefaults) > 0){
			COption::RemoveOption(CMShop::moduleID);
			COption::RemoveOption(CMShop::moduleID, "NeedGenerateCustomTheme");
			$APPLICATION->DelGroupRight(CMShop::moduleID); 
		}
		else{
			COption::RemoveOption(CMShop::moduleID, "sid");	
			foreach($arTabs as $key => $arTab){
				foreach($arTab["OPTIONS"] as $arOption){
					if($arOption[0] == "COLOR_THEME" && $_REQUEST[$arOption[0]."_".$arTab["SITE_ID"]] === 'CUSTOM'){
						COption::SetOptionString(CMShop::moduleID, "NeedGenerateCustomTheme", 'Y', '', $arTab["SITE_ID"]);
					}
					$arOption[0] = $arOption[0]."_".$arTab["SITE_ID"];
					CMShop::__AdmSettingsSaveOption_EX(CMShop::moduleID, $arOption); 
				}
				
				CBitrixComponent::clearComponentCache('bitrix:catalog.element', $arTab["SITE_ID"]);
				CBitrixComponent::clearComponentCache('bitrix:catalog.section', $arTab["SITE_ID"]);
				CBitrixComponent::clearComponentCache('bitrix:catalog.store.amount', $arTab["SITE_ID"]);
			}
		}

		UnRegisterModuleDependences("main", "OnEndBufferContent", CMShop::moduleID, "CMShop", "InsertCounters");
		RegisterModuleDependences("main", "OnEndBufferContent", CMShop::moduleID, "CMShop", "InsertCounters");
		
		if(CMShop::IsCompositeEnabled()){
			$obCache = new CPHPCache();
			$obCache->CleanDir("", "html_pages");
			CMShop::EnableComposite();
		}
		
		$APPLICATION->RestartBuffer();
	}
	
	CJSCore::Init(array("jquery"));
	CAjax::Init();
	?>
	<?if(!count($arTabs)):?>
		<div class="adm-info-message-wrap adm-info-message-red">
			<div class="adm-info-message">
				<div class="adm-info-message-title"><?=GetMessage("NO_SITE_INSTALLED", array("#SESSION_ID#"=>bitrix_sessid_get()))?></div>
				<div class="adm-info-message-icon"></div>
			</div>
		</div>
	<?else:?>
		<?$tabControl->Begin();?>
		<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>" class="mshop_options" ENCTYPE="multipart/form-data">
			<?=bitrix_sessid_post();?>
			<script>
			function CheckActive(){
				$('input[name^="USE_WORD_EXPRESSION"]').each(function() {
					var input = this;
					var isActiveUseExpressions = $(input).attr('checked') == 'checked';
					var tab = $(input).parents('.adm-detail-content-item-block');
					if(!isActiveUseExpressions){
						tab.find('input[name^="MAX_AMOUNT"]').attr('disabled', 'disabled');
						tab.find('input[name^="MIN_AMOUNT"]').attr('disabled', 'disabled');
						tab.find('input[name^="EXPRESSION_FOR_MIN"]').attr('disabled', 'disabled');
						tab.find('input[name^="EXPRESSION_FOR_MAX"]').attr('disabled', 'disabled');
						tab.find('input[name^="EXPRESSION_FOR_MID"]').attr('disabled', 'disabled');
					}
					else{
						tab.find('input[name^="MAX_AMOUNT"]').removeAttr('disabled');
						tab.find('input[name^="MIN_AMOUNT"]').removeAttr('disabled');
						tab.find('input[name^="EXPRESSION_FOR_MIN"]').removeAttr('disabled');
						tab.find('input[name^="EXPRESSION_FOR_MAX"]').removeAttr('disabled');
						tab.find('input[name^="EXPRESSION_FOR_MID"]').removeAttr('disabled');
					}
				});
				
				$('select[name^="BUYMISSINGGOODS"]').each(function() {
					var select = this;
					var BuyMissingGoodsVal = $(select).val();
					var tab = $(select).parents('.adm-detail-content-item-block');
					tab.find('input[name^="EXPRESSION_SUBSCRIBE_BUTTON"]').attr('disabled', 'disabled');
					tab.find('input[name^="EXPRESSION_SUBSCRIBED_BUTTON"]').attr('disabled', 'disabled');
					tab.find('input[name^="EXPRESSION_ORDER_BUTTON"]').attr('disabled', 'disabled');
					if(BuyMissingGoodsVal == 'SUBSCRIBE'){
						tab.find('input[name^="EXPRESSION_SUBSCRIBE_BUTTON"]').removeAttr('disabled');
						tab.find('input[name^="EXPRESSION_SUBSCRIBED_BUTTON"]').removeAttr('disabled');
					}
					else if(BuyMissingGoodsVal == 'ORDER'){
						tab.find('input[name^="EXPRESSION_ORDER_BUTTON"]').removeAttr('disabled');
					}
				});
			}
			
			$(document).ready(function() {
				CheckActive();
				
				$('form.mshop_options').submit(function(e) {
					$(this).attr('id', 'mshop_options');
					jsAjaxUtil.ShowLocalWaitWindow('id', 'mshop_options', true);
					$(this).find('input').removeAttr('disabled');
				});
				
				$('input[name^="USE_WORD_EXPRESSION"], select[name^="BUYMISSINGGOODS"]').change(function() {
					CheckActive();
				});
				
				$('select[name^="SHOW_QUANTITY_FOR_GROUPS"]').change(function() {
					var val = $(this).val();
					var tab = $(this).parents('.adm-detail-content-item-block');
					var sqcg = tab.find('select[name^="SHOW_QUANTITY_COUNT_FOR_GROUPS"]');
					
					var isAll = false;
					if(val){
						isAll = val.indexOf('2') !== -1;
					}
					
					if(!isAll){
						$(this).find('option').each(function() {
							if($(this).attr('selected') != 'selected'){
								sqcg.find('option[value="' + $(this).attr('value') + '"]').removeAttr('selected');
							}
						});
					}
				});
				
				$('select[name^="SHOW_QUANTITY_COUNT_FOR_GROUPS"]').change(function(e) {
					e.stopPropagation();
					var val = $(this).val();
					var tab = $(this).parents('.adm-detail-content-item-block');
					var sqg_val = tab.find('select[name^="SHOW_QUANTITY_FOR_GROUPS"]').val();
					
					if(!sqg_val){
						$(this).find('option').removeAttr('selected');
						return;
					}
					
					var isAll = false;
					if(sqg_val){
						isAll = sqg_val.indexOf('2') !== -1;
					}
					
					if(!isAll && val){
						for(i in val){
							var g = val[i];
							if(sqg_val.indexOf(g) === -1){
								$(this).find('option[value="' + g + '"]').removeAttr('selected');
							}
						}
					}
				});
			});
			</script>
			<?
			foreach($arTabs as $key => $arTab){
				$tabControl->BeginNextTab();
				if($arTab["SITE_ID"]){
					foreach($arTab["OPTIONS"] as $arOption){
						$arOption[0] = $arOption[0]."_".$arTab["SITE_ID"];
						CMShop::__AdmSettingsDrawRow_EX(CMShop::moduleID, $arOption, $arTab["SITE_ID"]);
					}	
				}
			}
			if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && check_bitrix_sessid()){
				if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"]) > 0) LocalRedirect($_REQUEST["back_url_settings"]);
				else LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());	
			}
			?>
			<?$tabControl->Buttons();?>
			<input <?if($RIGHT < "W") echo "disabled"?> type="submit" name="Apply" class="submit-btn" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
			<?if(strlen($_REQUEST["back_url_settings"]) > 0): ?>
				<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
				<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
			<?endif;?>
			<?if(CMShop::IsCompositeEnabled()):?>
				<div class="adm-info-message"><?=GetMessage("WILL_CLEAR_HTML_CACHE_NOTE")?></div><div style="clear:both;"></div>
				<script type="text/javascript">
				$(document).ready(function() {
					$('input[name^="THEME_SWITCHER"]').change(function() {
						var ischecked = $(this).attr('checked');
						if(typeof(ischecked) != 'undefined'){
							if(!confirm("<?=GetMessage("NO_COMPOSITE_NOTE")?>")){
								$(this).removeAttr('checked');
							}
						}
					});

					$('select[name^="SCROLLTOTOP_TYPE"]').change(function() {
					var posSelect = $(this).parents('table').first().find('select[name^="SCROLLTOTOP_POSITION"]');
					if(posSelect){
						var posSelectTr = posSelect.parents('tr').first();
						var isNone = $(this).val().indexOf('NONE') != -1;
						if(isNone){
							if(posSelectTr.is(':visible')){
								posSelectTr.fadeOut();
							}
						}
						else{
							if(!posSelectTr.is(':visible')){
								posSelectTr.fadeIn();
							}
							var isRound = $(this).val().indexOf('ROUND') != -1;
							var isTouch = posSelect.val().indexOf('TOUCH') != -1;
							if(isRound && !!posSelect){
								posSelect.find('option[value^="TOUCH"]').attr('disabled', 'disabled');
								if(isTouch){
									posSelect.val(posSelect.find('option[value^="PADDING"]').first().attr('value'));
								}
							}
							else{
								posSelect.find('option[value^="TOUCH"]').removeAttr('disabled');
							}
						}
					}
				});

				$('select[name^="SCROLLTOTOP_TYPE"]').change();
				});
				</script>
			<?endif;?>
		</form>
		<?$tabControl->End();?>
	<?endif;?>
<?}
else{?>
	<?//if ($RIGHT >="R"):?><?=CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));?>
<?}?>