<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="section">
	<script type="text/javascript">
		function changePaySystem(param)
		{
			if (BX("account_only") && BX("account_only").value == 'Y') // PAY_CURRENT_ACCOUNT checkbox should act as radio
			{
				if (param == 'account')
				{
					if (BX("PAY_CURRENT_ACCOUNT"))
					{
						BX("PAY_CURRENT_ACCOUNT").checked = true;
						BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
						BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

						// deselect all other
						var el = document.getElementsByName("PAY_SYSTEM_ID");
						for(var i=0; i<el.length; i++)
							el[i].checked = false;
					}
				}
				else
				{
					BX("PAY_CURRENT_ACCOUNT").checked = false;
					BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
					BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
				}
			}
			else if (BX("account_only") && BX("account_only").value == 'N')
			{
				if (param == 'account')
				{
					if (BX("PAY_CURRENT_ACCOUNT"))
					{
						BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

						if (BX("PAY_CURRENT_ACCOUNT").checked)
						{
							BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
							BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
						}
						else
						{
							BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
							BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
						}
					}
				}
			}

			submitForm();
		}
	</script>
	<div class="title"><?=GetMessage("SOA_TEMPL_PAY_SYSTEM")?></div>
	<div class="sale_order_table paysystem">
		<?
		if ($arResult["PAY_FROM_ACCOUNT"] == "Y")
		{
			$accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";
			?>
			<div class="bx_element account">
				<input type="hidden" id="account_only" value="<?=$accountOnly?>" />
				<input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
				<label for="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT_LABEL" onclick="changePaySystem('account');" class="<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo "selected"?>">
					<input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT" value="Y"<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo " checked=\"checked\"";?>>
					<img src="<?=SITE_TEMPLATE_PATH?>/components/bitrix/sale.order.ajax/main/images/logo-default-ps.gif" alt="" <?=($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")?"class=\"active\"":"";?> />
					<div class="desc">
						<div class="name"><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT")?></div>
						<div class="desc">
							<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT1")." <b>".$arResult["CURRENT_BUDGET_FORMATED"].'</b>'?></div>
							<? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"):?>
								<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT3")?></div>
							<? else:?>
								<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT2")?></div>
							<? endif;?>
						</div>
					</div>
				</label>
			</div>
			<?
		}

		uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value

		foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
		{
			if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) > 0 || intval($arPaySystem["PRICE"]) > 0)
			{
				if (count($arResult["PAY_SYSTEM"]) == 1)
				{
					?>
					<div class="ps_logo">
						<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();"
							/>
						<label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
							<?
							if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
								$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
							else:
								$imgUrl = $templateFolder."/images/logo-default-ps.gif";
							endif;
							?>
							<img src="<?=$imgUrl?>"/>
							<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
								<div class="paysystem_name"><?=$arPaySystem["PSA_NAME"];?></div>
							<?endif;?>
						</label>
						<div class="clear"></div>
					</div>
					<?
				}
				else // more than one
				{
				?>
					<div class="ps_logo">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();" />
						<label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
							<?
							if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
								$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
							else:
								$imgUrl = $templateFolder."/images/logo-default-ps.gif";
							endif;
							?>
							<img src="<?=$imgUrl?>" />
							<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
								<div class="paysystem_name"><?=$arPaySystem["PSA_NAME"];?></div>
							<?endif;?>
						</label>
						<div class="clear"></div>
					</div>
				<?
				}
			}

			if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) == 0 && intval($arPaySystem["PRICE"]) == 0)
			{
				if (count($arResult["PAY_SYSTEM"]) == 1)
				{
					?>
					<div class="ps_logo">
						<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();"
							/>
						<label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
						<?
						if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
							$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
						else:
							$imgUrl = $templateFolder."/images/logo-default-ps.gif";
						endif;
						?>
						<img src="<?=$imgUrl?>" />
						<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
							<div class="paysystem_name"><?=$arPaySystem["PSA_NAME"];?></div>
						<?endif;?>
					</div>
				<?
				}
				else // more than one
				{
				?>
					<div class="ps_logo">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();" />

						<label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
							<?
							if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
								$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
							else:
								$imgUrl = $templateFolder."/images/logo-default-ps.gif";
							endif;
							?>
							<img src="<?=$imgUrl?>" />
							<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
								<div class="paysystem_name">
									<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
										<?=$arPaySystem["PSA_NAME"];?>
									<?else:?>
										<?="&nbsp;"?>
									<?endif;?>
								</div>
							<?endif;?>
						</label>
					</div>
				<?
				}
			}
		}
		?>
		<div style="clear: both;"></div>
	</div>
</div>