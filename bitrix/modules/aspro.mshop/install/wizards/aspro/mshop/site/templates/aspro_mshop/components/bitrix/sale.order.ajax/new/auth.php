<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<script>
<!--
function ChangeGenerate(val)
{
	if(val)
	{
		document.getElementById("sof_choose_login").style.display='none';
	}
	else
	{
		document.getElementById("sof_choose_login").style.display='block';
		document.getElementById("NEW_GENERATE_N").checked = true;
	}

	try{document.order_reg_form.NEW_LOGIN.focus();}catch(e){}
}
//-->
</script>
<div class="module-authorization">
	<div class="authorization-cols">
		<div class="col authorization">
			<div class="auth-title">
				<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
					<?echo GetMessage("STOF_2REG")?>
				<?endif;?>
			</div>
			<div class="form-block">
				<div class="form_wrapp">
					<form method="post" action="" name="order_auth_form">
						<?=bitrix_sessid_post()?>
						<?foreach ($arResult["POST"] as $key => $value){?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
						<?}?>
						<div class="r form-control">
							<label>E-mail <span class="star">*</span></label>
							<input type="text" name="USER_LOGIN" maxlength="30" size="30" value="<?=$arResult["AUTH"]["USER_LOGIN"]?>">
						</div>
						<div class="r form-control">
							<label><?echo GetMessage("STOF_PASSWORD")?> <span class="star">*</span></label>
							<input type="password" class="required" name="USER_PASSWORD" maxlength="30" size="30">
						</div>
						<div class="but-r">
							<div class="filter block"><a href="<?=$arParams["PATH_TO_AUTH"]?>forgot-password/?back_url=<?= urlencode($APPLICATION->GetCurPageParam()); ?>"><?echo GetMessage("STOF_FORGET_PASSWORD")?></a></div>
							<div class="buttons">
								<input type="submit" class="button vbig_btn wides" value="<?echo GetMessage("STOF_NEXT_STEP")?>">
								<input type="hidden" name="do_authorize" value="Y">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col registration">
			<div class="auth-title">
				<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
					<?echo GetMessage("STOF_2NEW")?>
				<?endif;?>
			</div>
			<div class="form-block">
				<div class="form_wrapp">
					<?if($arResult["AUTH"]["new_user_registration"]=="Y"){?>
						<form method="post" action="" name="order_reg_form">
							<?=bitrix_sessid_post()?>
							<?foreach ($arResult["POST"] as $key => $value){?>
								<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
							<?}?>
							<div class="r form-control">
								<label><?echo GetMessage("STOF_NAME")?> <span class="star">*</span></label>
								<input type="text" class="required" name="NEW_NAME" size="40" value="<?=$arResult["AUTH"]["NEW_NAME"]?>">
							</div>
							<div class="r form-control">
								<label><?echo GetMessage("STOF_LASTNAME")?> <span class="star">*</span></label>
								<input type="text" class="required" name="NEW_LAST_NAME" size="40" value="<?=$arResult["AUTH"]["NEW_LAST_NAME"]?>">
							</div>
							<div class="r form-control">
								<label>E-Mail <span class="star">*</span></label>
								<input type="text" class="required" name="NEW_EMAIL" size="40" value="<?=$arResult["AUTH"]["NEW_EMAIL"]?>">
							</div>
							<div class="r form-control" style="display: none;">
								<label><?echo GetMessage("STOF_LOGIN")?> <span class="star">*</span></label>
								<input type="text" class="required" name="NEW_LOGIN" size="30" value="<?=$arResult["AUTH"]["NEW_EMAIL"]?>">
							</div>
							<div class="r form-control">
								<label><?echo GetMessage("STOF_PASSWORD")?> <span class="star">*</span></label>
								<input type="password" class="required" name="NEW_PASSWORD" size="30">
							</div>
							<div class="r form-control">
								<label><?echo GetMessage("STOF_RE_PASSWORD")?> <span class="star">*</span></label>
								<input type="password" class="required" name="NEW_PASSWORD_CONFIRM" size="30">
							</div>
							<?if ($arResult["AUTH"]["captcha_registration"] == "Y"):?>
								<div class="form-control captcha-row clearfix">
									<?echo GetMessage("CAPTCHA_REGF_TITLE")?>:<br />
									<div class="captcha_image">
										<input type="hidden" name="captcha_sid" value="<?echo $arResult["AUTH"]["capCode"]?>" />
										<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["AUTH"]["capCode"]?>" width="180" height="40" alt="CAPTCHA" />
										<div class="captcha_reload"></div>
									</div>
									<div class="captcha_input">
										<input type="text" name="captcha_word" maxlength="50" value="" />
									</div>
								</div>
							<?endif?>
							<div class="but-r">
								<div class="buttons">
									<input type="submit" class="button vbig_btn wides" value="<?echo GetMessage("STOF_NEXT_STEP")?>">
									<input type="hidden" name="do_register" value="Y">
								</div>
							</div>
							<?
						/*if($arResult["AUTH"]["captcha_registration"] == "Y") //CAPTCHA
						{
							?>
							<tr>
								<td><br /><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b></td>
							</tr>
							<tr>
								<td>
									<input type="hidden" name="captcha_sid" value="<?=$arResult["AUTH"]["capCode"]?>">
									<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["AUTH"]["capCode"]?>" width="180" height="40" alt="CAPTCHA">
								</td>
							</tr>
							<tr valign="middle">
								<td>
									<span class="starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:<br />
									<input type="text" name="captcha_word" size="30" maxlength="50" value="">
								</td>
							</tr>
							<?
						}*/
						?>
						</form>
					<?}?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	
	$(document).ready(function(){
		$.fn.keyupDelay = function( cb, delay ){
			if( delay == null ){
				delay = 1000;
			}
			var timer = 0;
			return $( this ).on( 'keyup', function( e ){
				clearTimeout( timer );
				timer = setTimeout( cb, delay );
			} );
		};
		$('input[name="NEW_EMAIL"]').keyupDelay( function(){
			$('input[name="NEW_LOGIN"]').val($('input[name="NEW_EMAIL"]').val());
		}, 200);
		$("form[name=order_auth_form]").validate({
			rules: {
				USER_LOGIN: {
					email: true,
					required:true
				}
			}
		});
		
		$("form[name=order_reg_form]").validate(); 
	});
	</script>
<?/*
<table class="order-auth authorization-cols" style="width: 100%;">
	<tr>
		<td>
			<div class="auth-title">
			<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
				<?echo GetMessage("STOF_2REG")?>
			<?endif;?>
			</div>
		</td>
		<td>
			<div class="auth-title">
			<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
				<?echo GetMessage("STOF_2NEW")?>
			<?endif;?>
			</div>
		</td>
	</tr>
	<tr>
		<td style="vertical-align: top; padding-right: 50px;">
			<div class="form-block">
			</div>
			<br/>
		</td>
		<td>
			<div class="form-block">
			</div>
			<br/>
		</td>
	</tr>
	
	<tr>
		<td style="vertical-align: top; padding-right: 50px;" class="">
			<form method="post" action="" name="order_auth_form">
				<?=bitrix_sessid_post()?>
				<?
				foreach ($arResult["POST"] as $key => $value)
				{
				?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
				<?
				}
				?>
				<table class="sale_order_full_table">

					<tr>
						<td><?echo GetMessage("STOF_LOGIN_PROMT")?></td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("STOF_LOGIN")?> <span class="starrequired">*</span><br />
							<input type="text" name="USER_LOGIN" maxlength="30" size="30" value="<?=$arResult["AUTH"]["USER_LOGIN"]?>"></td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("STOF_PASSWORD")?> <span class="starrequired">*</span><br />
							<input type="password" name="USER_PASSWORD" maxlength="30" size="30"></td>
					</tr>
					<tr>
						<td nowrap><a href="<?=$arParams["PATH_TO_AUTH"]?>forgot-password/?back_url=<?= urlencode($APPLICATION->GetCurPageParam()); ?>"><?echo GetMessage("STOF_FORGET_PASSWORD")?></a></td>
					</tr>
					<tr>
						<td >
						<br/>
							<input type="submit" class="button vbig_btn wides" value="<?echo GetMessage("STOF_NEXT_STEP")?>">
							<input type="hidden" name="do_authorize" value="Y">
						</td>
					</tr>
				</table>
			</form>
		</td>		
		<td style="vertical-align: top;">
			<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
				<form method="post" action="" name="order_reg_form">
					<?=bitrix_sessid_post()?>
					<?
					foreach ($arResult["POST"] as $key => $value)
					{
					?>
					<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
					<?
					}
					?>
					<table class="sale_order_full_table">
						<tr>
							<td nowrap>
								<?echo GetMessage("STOF_NAME")?> <span class="starrequired">*</span><br />
								<input type="text" name="NEW_NAME" size="40" value="<?=$arResult["AUTH"]["NEW_NAME"]?>">&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
						<tr>
							<td nowrap>
								<?echo GetMessage("STOF_LASTNAME")?> <span class="starrequired">*</span><br />
								<input type="text" name="NEW_LAST_NAME" size="40" value="<?=$arResult["AUTH"]["NEW_LAST_NAME"]?>">&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
						<tr>
							<td nowrap>
								E-Mail <span class="starrequired">*</span><br />
								<input type="text" name="NEW_EMAIL" size="40" value="<?=$arResult["AUTH"]["NEW_EMAIL"]?>">&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
						<?/*if($arResult["AUTH"]["new_user_registration_email_confirmation"] != "Y"):?>
						<tr>
							<td style="padding: 15px;"><input type="radio" id="NEW_GENERATE_N" name="NEW_GENERATE" style="width:auto; vertical-align: middle;" value="N" OnClick="ChangeGenerate(false)"<?if ($_POST["NEW_GENERATE"] == "N") echo " checked";?>> <label for="NEW_GENERATE_N"><?echo GetMessage("STOF_MY_PASSWORD")?></label></td>
						</tr>
						<?endif;?>
						<?if($arResult["AUTH"]["new_user_registration_email_confirmation"] != "Y"):?>
						<tr>
							<td style="padding-left: 15px;">
								<div id="sof_choose_login">
									<table>
						<?endif;//?>
										<tr style="display: none;">
											<td><?echo GetMessage("STOF_LOGIN")?> <span class="starrequired">*</span><br />
												<input type="text" name="NEW_LOGIN" size="30" value="<?=$arResult["AUTH"]["NEW_EMAIL"]?>">
											</td>
										</tr>
										<tr>
											<td>
												<?echo GetMessage("STOF_PASSWORD")?> <span class="starrequired">*</span><br />
												<input type="password" name="NEW_PASSWORD" size="30">
											</td>
										</tr>
										<tr>
											<td>
												<?echo GetMessage("STOF_RE_PASSWORD")?> <span class="starrequired">*</span><br />
												<input type="password" name="NEW_PASSWORD_CONFIRM" size="30">
											</td>
										</tr>
						<?/*if($arResult["AUTH"]["new_user_registration_email_confirmation"] != "Y"):?>
									</table>
								</div>
							</td>
						</tr>
						<?endif;?>
						<?/*if($arResult["AUTH"]["new_user_registration_email_confirmation"] != "Y"):?>
						<tr>
							<td style="padding-left: 15px;">
								<input type="radio" id="NEW_GENERATE_Y" name="NEW_GENERATE" value="Y"  style="width:auto; vertical-align: middle;" OnClick="ChangeGenerate(true)"<?if ($POST["NEW_GENERATE"] != "N") echo " checked";?>> <label for="NEW_GENERATE_Y"><?echo GetMessage("STOF_SYS_PASSWORD")?></label>
								<script language="JavaScript">
								<!--
								ChangeGenerate(<?= (($_POST["NEW_GENERATE"] != "N") ? "true" : "false") ?>);
								//-->
								</script>
							</td>
						</tr>
						<?endif;//?>
						<?
						if($arResult["AUTH"]["captcha_registration"] == "Y") //CAPTCHA
						{
							?>
							<tr>
								<td><br /><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b></td>
							</tr>
							<tr>
								<td>
									<input type="hidden" name="captcha_sid" value="<?=$arResult["AUTH"]["capCode"]?>">
									<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["AUTH"]["capCode"]?>" width="180" height="40" alt="CAPTCHA">
								</td>
							</tr>
							<tr valign="middle">
								<td>
									<span class="starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>:<br />
									<input type="text" name="captcha_word" size="30" maxlength="50" value="">
								</td>
							</tr>
							<?
						}
						?>
						<tr>
							<td>
							<br/>
								<input type="submit" class="button vbig_btn wides"  value="<?echo GetMessage("STOF_NEXT_STEP")?>">
								<input type="hidden" name="do_register" value="Y">
							</td>
						</tr>
					</table>
				</form>
			<?endif;?>
		</td>
	</tr>
</table>
*/?>
<br /><br />
<?echo GetMessage("STOF_REQUIED_FIELDS_NOTE")?><br /><br />
<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
	<?echo GetMessage("STOF_EMAIL_NOTE")?><br /><br />
<?endif;?>
<?echo GetMessage("STOF_PRIVATE_NOTES")?>
