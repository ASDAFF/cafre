<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame()->begin();?>
<div class="subscribe-form"  id="subscribe-form">
	<div class="wrap_bg">
		<div class="top_block box-sizing">
			<div class="image"></div>
			<div class="text">
				<div class="title"><?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_title.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TOP_BLOCK"),));?></div>
				<div class="more"><?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TEXT_BLOCK"),));?></div>
			</div>
		</div>
		<form action="<?=$arResult["FORM_ACTION"];?>" class="sform box-sizing">
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<label for="sf_RUB_ID_<?=$itemValue["ID"]?>" class="hidden">
					<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> /> <?=$itemValue["NAME"]?>
				</label>
			<?endforeach;?>
			<div class="email_wrap form-control">
				<input type="email" name="sf_EMAIL" required size="20" value="<?=$arResult["EMAIL"]?>" placeholder="<?=GetMessage("subscr_form_email_title")?>" />
			</div>
			<div class="button_wrap form-control">
				<input type="submit" name="OK" class="button transparent white medium" value="<?=($arResult["EMAIL"] ? GetMessage("subscr_form_button_change") : GetMessage("subscr_form_button"));?>" />
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function(){
		$("form.sform").validate({
			rules:{ "sf_EMAIL": {email: true} }
		});
	})

	$(window).scroll(function() {
		var x = $(window).scrollTop()/$(document).height();		
		x = parseInt(x * 1000-400);
		if (x>-3) x=-3;		
		$('.subscribe-form .wrap_bg').stop().animate({'background-position-x': '10%', 'background-position-y':  x + 'px' }, 500, 'swing');		
		
	});
	
</script>
<?$frame->end();?>
