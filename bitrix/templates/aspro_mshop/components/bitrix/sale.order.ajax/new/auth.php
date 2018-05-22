<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="order__cell">
							<form method="post" action="" name="order_auth_form" class="order__block order__log">
							<?=bitrix_sessid_post()?>
						<?foreach ($arResult["POST"] as $key => $value){?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
						<?}?>
								<div class="order__message order__message_log">
									<div class="pinkgirl">
										<img src="<?=SITE_TEMPLATE_PATH?>/pinkgirl/pinkgirl2.png" alt="">
									</div>
									<p>Введи свой логин и пароль (если уже есть)</p>
								</div>
								<label class="order__lbl">
									<span>E-Mail</span>
									<input class="order__inp" name="MY_LOGIN" type="text" value="<?=$arResult["AUTH"]["USER_LOGIN"]?>">
									<a href="<?=$arParams["PATH_TO_AUTH"]?>forgot-password/?back_url=<?= urlencode($APPLICATION->GetCurPageParam()); ?>"><?echo GetMessage("STOF_FORGET_PASSWORD")?></a>
								</label>
								<label class="order__lbl">
									<span><?echo GetMessage("STOF_PASSWORD")?></span>
									<input class="order__inp" class="required" type="password" name="MY_PASS">
								</label>
								<input type="submit" class="button vbig_btn wides" id="do_authorize" value="<?echo GetMessage("STOF_NEXT_STEP")?>">
								<input type="hidden" value="auth_basket" name="label">
							</form>
</div>
<!--<div class="module-authorization">
	<div class="authorization-cols">

			<div class="auth-title">
				Если вы уже совершали покупки, введите email и пароль
			</div>
			<div class="form-block">
					<form method="post" action="" name="order_auth_form">
						
						<div class="col-3 registration">
						<div class="r form-control">
							<label>E-mail <span class="star">*</span></label>
							<input id="name2" type="text" name="MY_LOGIN" maxlength="30" size="30" value="<?=$arResult["AUTH"]["USER_LOGIN"]?>">
							
						</div>
						</div>
						<div class="col-3 registration">
						<div class="r form-control">
							<label><?//echo GetMessage("STOF_PASSWORD")?> <span class="star">*</span></label>
							<input type="password" class="required" name="MY_PASS" maxlength="30" size="30">
						</div>
						</div>
						<div class="col-3 registration but-r">							
							<div class="buttons">
								<input type="submit" class="button vbig_btn wides" id="do_authorize" value="<?//echo GetMessage("STOF_NEXT_STEP")?>">
							</div>
						</div>
						<input type="hidden" value="auth_basket" name="label">
					</form>					
				
			</div>
	</div>
	<div class="filter block"></div>
</div>-->
<script type="text/javascript">
/********принимал новую отправку с ajax********/
/*$('.vbig_btn').on('click', function(e){
	e.preventDefault();
	$(".order").prepend('<h3 class="on_authord">Вы успешно авторизованы</h3>');
});
*/
	$(document).ready(function(){
		
		$("form[name=order_auth_form]").validate({
			rules: {
				USER_LOGIN: {
					email: true,
					required:true
				}
			},
			submitHandler: function(form) {
				$("form[name=order_auth_form]").next("#error").remove();
				$.ajax({
				url: "/ajax/auth_order.php", 
					type: "post",
					dataType: "json",
					data: $('form[name=order_auth_form]').serialize(),
				success: function(e) {			
					
					if(e.result=='yes') { 
						$(document).find('[code=EMAIL]').val($("form[name=order_auth_form]").find('[name="MY_LOGIN"]').val());
						$(document).find('[code=NAME]').val(e.name);
						$(document).find('[code=PHONE]').val(e.phone);
						$("form[name=order_auth_form]").closest('.order__cell').prev().html('').next().remove();
						$("form[name=order_auth_form]").remove();
						$(".order").append('<h3 class="on_authord">Вы успешно авторизованы</h3>');
						$('.order__row').css("display", "none");
						$('.module-enter').removeClass('no-have-user').addClass('have-user').html('<a href="/personal/" class="reg" rel="nofollow"><span>Личный кабинет</span></a><a href="<?=SITE_DIR?>?logout=yes" class="exit_link" rel="nofollow"><span>Выход</span></a>');
					}
					else {
						$("form[name=order_auth_form]").after('<p id="error">Логин или пароль указаны неверно</p>');
					}                
				}
			});	
			
			}
		});
		
	});

	</script>
