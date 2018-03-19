<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<form id="auth_params" action="<?=SITE_DIR?>ajax/show_personal_block.php">
	<input type="hidden" name="REGISTER_URL" value="<?=$arParams["REGISTER_URL"]?>" />
	<input type="hidden" name="FORGOT_PASSWORD_URL" value="<?=$arParams["FORGOT_PASSWORD_URL"]?>" />
	<input type="hidden" name="PROFILE_URL" value="<?=$arParams["PROFILE_URL"]?>" />
	<input type="hidden" name="SHOW_ERRORS" value="<?=$arParams["SHOW_ERRORS"]?>" />
</form>
<?
$frame = $this->createFrame()->begin('');
$frame->setBrowserStorage(true);
?>
<?if(!$USER->IsAuthorized()):?>
	<div class="module-enter no-have-user">
		<span class="avtorization-call enter"><span><?=GetMessage("AUTH_LOGIN_ENTER");?></span></span>
		<!--noindex--><a class="register" rel="nofollow" href="<?=$arParams["REGISTER_URL"];?>"><span><?=GetMessage("AUTH_LOGIN_REGISTER");?></span></a><!--/noindex-->
		<script type="text/javascript">
		$(document).ready(function(){
			jqmEd('enter', 'auth', '.avtorization-call.enter');
		});
		</script>
	</div>
<?else:?>
	<div class="module-enter have-user">
		<!--noindex-->
			<a href="<?=$arResult["PROFILE_URL"]?>" class="reg" rel="nofollow"><span><?=GetMessage("AUTH_LOGIN_BUTTON");?></span></a>
			<a href="<?=SITE_DIR?>?logout=yes" class="exit_link" rel="nofollow"><span><?=GetMessage("AUTH_LOGOUT_BUTTON");?></span></a>
		<!--/noindex-->
	</div>	
<?endif;?>
<?$frame->end();?>
