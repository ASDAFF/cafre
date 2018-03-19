<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");
?>
<div class="section info">
<h3><?=GetMessage("SOA_TEMPL_PROP_INFO")?></h3>
	
</div>
<div class="info_block">
		<?$bHideProps = true;
		if (is_array($arResult["ORDER_PROP"]["USER_PROFILES"]) && !empty($arResult["ORDER_PROP"]["USER_PROFILES"])):
			if ($arParams["ALLOW_NEW_PROFILE"] == "Y"):?>
				<div class="bx_section">
					<h4><?=GetMessage("SOA_TEMPL_PROP_CHOOSE")?></h4>
					<div class="bx_block1">
						<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
							<option value="0"><?=GetMessage("SOA_TEMPL_PROP_NEW_PROFILE")?></option>
							<?
							foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
							{
								?>
								<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
								<?
							}
							?>
						</select>
						<div style="clear: both;"></div>
					</div>
				</div>
			<?else:?>
				<div class="bx_section">
					<h4><span class="text"><?=GetMessage("SOA_TEMPL_EXISTING_PROFILE")?></span></h4>
					<div class="bx_block1">
							<?
							if (count($arResult["ORDER_PROP"]["USER_PROFILES"]) == 1)
							{
								foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
								{
									echo "<strong>".$arUserProfiles["NAME"]."</strong>";
									?>
									<input type="hidden" name="PROFILE_ID" id="ID_PROFILE_ID" value="<?=$arUserProfiles["ID"]?>" />
									<?
								}
							}
							else
							{
								?>
								<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
									<?
									foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
									{
										?>
										<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
										<?
									}
									?>
								</select>
								<?
							}
							?>
						<div style="clear: both;"></div>
					</div>
				</div>
			<?
			endif;
		else:
			$bHideProps = false;
		endif;
		?>
		<div class="clearfix"></div>
	
	<div class="bx_section">
		<?
		if (array_key_exists('ERROR', $arResult) && is_array($arResult['ERROR']) && !empty($arResult['ERROR'])){
			$bHideProps = false;
		}
		?>
		<h4 <?if ($bHideProps){?> class="slides hover" onclick="fGetBuyerProps(this)"<?}?>>
			<span class="text"><?=GetMessage("SOA_TEMPL_BUYER_INFO")?></span>
			<?if ($bHideProps && $_POST["showProps"] != "Y"):?>
				<span class="slide opener_icon no_bg">
					<i title="<?=GetMessage('SOA_TEMPL_BUYER_SHOW');?>"></i>
				</span>
			<?
			elseif (($bHideProps && $_POST["showProps"] == "Y")):
			?>
				<span class="slide opener_icon no_bg">
					<i title="<?=GetMessage('SOA_TEMPL_BUYER_HIDE');?>"></i>
				</span>
			<?
			endif;
			?>
			<input type="hidden" name="showProps" id="showProps" value="<?=($_POST["showProps"] == 'Y' ? 'Y' : 'N')?>" />
		</h4>
		<div id="sale_order_props" <?=($bHideProps && $_POST["showProps"] != "Y")?"style='display:none;'":''?>>
			<?
			PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], $arParams["TEMPLATE_LOCATION"]);
			PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], $arParams["TEMPLATE_LOCATION"]);
			?>
		</div>
	</div>
<script type="text/javascript">
	function fGetBuyerProps(el)
	{
		var show = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW')?>';
		var hide = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE')?>';
		var status = BX('sale_order_props').style.display;
		var startVal = 0;
		var startHeight = 0;
		var endVal = 0;
		var endHeight = 0;
		var pFormCont = BX('sale_order_props');
		pFormCont.style.display = "block";
		pFormCont.style.overflow = "hidden";
		pFormCont.style.height = 0;
		var display = "";
		var ell=BX.findChild(el, { 'class':'opener_icon' }, false);
		var ell_i=BX.findChild(ell, { tagName:'i' }, false);
		if (status == 'none')
		{
			ell_i.setAttribute('title', '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE');?>');
			BX.removeClass(el, 'slides');
			BX.addClass(ell, 'opened');
			startVal = 0;
			startHeight = 0;
			endVal = 100;
			endHeight = pFormCont.scrollHeight;
			display = 'block';
			BX('showProps').value = "Y";
			//el.innerHTML = hide;
		}
		else
		{
			ell_i.setAttribute('title', '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW');?>');
			setTimeout(function(){
				BX.addClass(el, 'slides');
			}, 400);
			BX.removeClass(ell, 'opened');
			startVal = 100;
			startHeight = pFormCont.scrollHeight;
			endVal = 0;
			endHeight = 0;
			display = 'none';
			BX('showProps').value = "N";
			pFormCont.style.height = startHeight+'px';
			//el.innerHTML = show;
		}

		(new BX.easing({
			duration : 700,
			start : { opacity : startVal, height : startHeight},
			finish : { opacity: endVal, height : endHeight},
			transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
			step : function(state){
				pFormCont.style.height = state.height + "px";
				pFormCont.style.opacity = state.opacity / 100;
			},
			complete : function(){
					BX('sale_order_props').style.display = display;
					BX('sale_order_props').style.height = '';

					pFormCont.style.overflow = "visible";
			}
		})).animate();
	}
</script>

<?if(!CSaleLocation::isLocationProEnabled()):?>
	<div style="display:none;">

		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.ajax.locations",
			$arParams["TEMPLATE_LOCATION"],
			array(
				"AJAX_CALL" => "N",
				"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
				"REGION_INPUT_NAME" => "REGION_tmp",
				"CITY_INPUT_NAME" => "tmp",
				"CITY_OUT_LOCATION" => "Y",
				"LOCATION_VALUE" => "",
				"ONCITYCHANGE" => "submitForm()",
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);?>

	</div>
<?endif?>
