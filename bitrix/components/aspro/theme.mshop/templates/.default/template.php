<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(false);?>
<!--noindex-->
<div class="style-switcher <?=($_COOKIE['styleSwitcher']=='open' ? 'active' : '')?>">
	<div class="header"><?=GetMessage("THEME_MODIFY")?><span class="switch"><img src="<?=$this->GetFolder()?>/images/cogs.png"></span></div>
	<form method="POST" name="style-switcher">
		<?if($arResult["ITEMS"] && is_array($arResult["ITEMS"])):?>
			<?foreach($arResult["ITEMS"] as $optionKey => $option):?>
				<?if($optionKey == 'CUSTOM_COLOR_THEME' || $optionKey == 'STORES_SOURCE') continue;?>
				<div class="block">
					<div class="block-title"><?=$option["NAME"];?></div>
					<div class="options" name="<?=$option["ID"]?>">
						<input type="hidden" name="<?=strToLower($option["ID"])?>" value="<?=strToLower($option["CURRENT_VALUE"])?>" />
						<?if($option["VALUES"] && is_array($option["VALUES"])):?>
							<?foreach($option["VALUES"] as $key => $value):?>
								<?$bCurrent = ($value["VALUE"] == $option["CURRENT_VALUE"]);?>
								<?if($option["ID"] == 'COLOR_THEME' && $value["VALUE"] == 'CUSTOM'):?>
									<?if(isset($arResult["ITEMS"]['CUSTOM_COLOR_THEME'])):?>
										<?
										$colorCode = str_replace('#', '', $arResult["ITEMS"]['CUSTOM_COLOR_THEME']['CURRENT_VALUE']);
										$colorCode = strToLower('#'.(strlen($colorCode) ? $colorCode : $arResult["ITEMS"]['CUSTOM_COLOR_THEME']['DEFAULT']));
										?>
										<a class="color custom_color_theme <?=($bCurrent ? 'active' : '')?>" rel="nofollow"  name="<?=$value["VALUE"]?>" data-option-id="<?=strToLower($option["ID"])?>" data-option-value="<?=strToLower($value["VALUE"])?>">
											<div class="color_block" style="background: <?=$colorCode?>" title="<?=$value["NAME"]?>"></div>
										</a>
										<input id="custom_picker" type="hidden" value="<?=$colorCode?>" name="custom_color_theme">
									<?endif;?>
									<?continue;?>
								<?endif;?>
								<a class="<?=($option["TYPE"] == "COLOR" ? 'color' : '')?> <?=($bCurrent ? 'active' : '')?>" rel="nofollow" href="javascript:;" name="<?=$value["VALUE"]?>" data-option-id="<?=strToLower($option["ID"])?>" data-option-value="<?=strToLower($value["VALUE"])?>" data-color="<?=$value["COMPONENT_VALUE"]?>">
									<?if($option["TYPE"] == "COLOR"):?>
										<div class="color_block" style="background: <?=$value["COMPONENT_VALUE"]?>" title="<?=$value["NAME"]?>"></div>
									<?elseif($option["TYPE"] == "TEXT"):?>
										<?=$value["NAME"]?>
									<?endif;?>
								</a>
							<?endforeach;?>
						<?endif;?>
					</div>
				</div>
			<?endforeach;?>
		<?endif;?>
		<div class="block" style="border-bottom:0;">
			<div class="buttons">
				<a class="reset" href="javascript:;"><?=GetMessage("THEME_RESET")?></a>
			</div>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			if($.cookie('styleSwitcher') == 'open'){
				$('.style-switcher').addClass('active');
			}
			
			$('#custom_picker').spectrum({
				preferredFormat: 'hex',
				showButtons: true,
				showInput: true,
				showPalette: false, 
				chooseText: '<?=GetMessage('CUSTOM_COLOR_CHOOSE')?>',
				cancelText: '<?=GetMessage('CUSTOM_COLOR_CANCEL')?>',
				containerClassName: 'custom_picker_container',
				replacerClassName: 'custom_picker_replacer',
				clickoutFiresChange: false,
				move: function(color) {
					var colorCode = color.toHexString();
					$('.custom_color_theme .color_block').attr('style', 'background:' + colorCode);
				},
				hide: function(color) {
					var colorCode = color.toHexString();
					$('.custom_color_theme .color_block').attr('style', 'background:' + colorCode);
				},
				change: function(color) {
					$('.custom_color_theme').addClass('active').siblings().removeClass('active');
					$('form[name=style-switcher] input[name=' + $('.custom_color_theme').data('option-id') + ']').val($('.custom_color_theme').data('option-value'));
					$('form[name=style-switcher]').submit();
				}
			});
			
			$('.custom_color_theme').click(function(e) {
				e.preventDefault();
				$('#custom_picker').spectrum('toggle');
				return false;
			});
			
			var curcolor = $('.color.active').data('color');
			if (curcolor != undefined && curcolor.length){
				$('#custom_picker').spectrum('set', curcolor);
				$('.color.custom_color_theme .color_block').attr('style', 'background:' + curcolor);
			}
			
			$('.style-switcher .switch').click(function(e){
				e.preventDefault();
				var styleswitcher = $(this).closest('.style-switcher');
				if(styleswitcher.hasClass('active')){
					styleswitcher.animate({left: '-' + styleswitcher.outerWidth() + 'px'}, 300).removeClass('active');
					$.removeCookie('styleSwitcher', {path: '/'});
				}
				else{
					styleswitcher.animate({left: '0'}, 300).addClass('active');
					var pos = styleswitcher.offset().top;
					if($(window).scrollTop() > pos){
						$('html, body').animate({scrollTop: pos}, 500);
					}
					$.cookie('styleSwitcher', 'open', {path: '/'});
				}
			});
			
			$('.style-switcher .reset').click(function(e){
				$('form[name=style-switcher]').append('<input type="hidden" name="theme" value="default" />');
				$('form[name=style-switcher]').submit();
			});
	
			
			$('.style-switcher .options a:not(.custom_color_theme)').click(function(e){
				$(this).addClass('active').siblings().removeClass('active');
				$('form[name=style-switcher] input[name=' + $(this).data('option-id') + ']').val($(this).data('option-value'));
				$('form[name=style-switcher]').submit();
			});
			
		});
		</script>
	</form>
</div>
<!--/noindex-->