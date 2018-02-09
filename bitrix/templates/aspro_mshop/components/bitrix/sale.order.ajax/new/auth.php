<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="module-authorization">
	<div class="authorization-cols">

			<div class="auth-title">
				Если вы уже совершали покупки, введите email и пароль
			</div>
			<div class="form-block">
					<form method="post" action="" name="order_auth_form">
						<?=bitrix_sessid_post()?>
						<?foreach ($arResult["POST"] as $key => $value){?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
						<?}?>
						<div class="col-3 registration">
						<div class="r form-control">
							<label>E-mail <span class="star">*</span></label>
							<input type="text" name="MY_LOGIN" maxlength="30" size="30" value="<?=$arResult["AUTH"]["USER_LOGIN"]?>">
							<a href="<?=$arParams["PATH_TO_AUTH"]?>forgot-password/?back_url=<?= urlencode($APPLICATION->GetCurPageParam()); ?>"><?echo GetMessage("STOF_FORGET_PASSWORD")?></a>
						</div>
						</div>
						<div class="col-3 registration">
						<div class="r form-control">
							<label><?echo GetMessage("STOF_PASSWORD")?> <span class="star">*</span></label>
							<input type="password" class="required" name="MY_PASS" maxlength="30" size="30">
						</div>
						</div>
						<div class="col-3 registration but-r">							
							<div class="buttons">
								<input type="submit" class="button vbig_btn wides" id="do_authorize" value="<?echo GetMessage("STOF_NEXT_STEP")?>">
							</div>
						</div>
						<input type="hidden" value="auth_basket" name="label">
					</form>					
				
			</div>
	</div>
	<div class="filter block"></div>
</div>
<script type="text/javascript">
	
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
				data: $('form[name=order_auth_form]').serialize(),
				type: "POST",
				url: "/ajax/auth_order.php",
				success: function(e) {					
					if(e=='yes') {
						$(document).find('[code=EMAIL]').val($("form[name=order_auth_form]").find('[name="MY_LOGIN"]').val());
						$("form[name=order_auth_form]").closest('.form-block').prev().text('Вы успешно авторизованы').next().remove();						
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
