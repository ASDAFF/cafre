<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(strlen($arResult["ERROR_MESSAGE"])):?>
	<div class="border_block">
		<?=ShowError($arResult["ERROR_MESSAGE"]);?>
	</div>
<?else:?>
	<div class="module-order-history orderdetail">
		<table class="module-orders-list colored">
			<tbody>
				<tr class="vl">
					<td><?=GetMessage('SPOD_ORDER_STATUS')?></td>
					<td><?=$arResult["STATUS"]["NAME"]?> (<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_STATUS_FORMATED"]?>)</td>
				</tr>
				<tr>
					<td><?=GetMessage('SPOD_ORDER_PRICE')?></td>
					<td>
						<?=$arResult["PRICE_FORMATED"]?>
						<?if(floatval($arResult["SUM_PAID"])):?>
							(<?=GetMessage('SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
						<?endif;?>
					</td>
				</tr>
		<?if(intval($arResult["USER_ID"])):?>
			<tr class="title"><td colspan="2"><h4><?=GetMessage('SPOD_ACCOUNT_DATA')?></h4></td></tr>
			<?if(strlen($arResult["USER_NAME"])):?>
				<tr class="vl">
					<td><?=GetMessage('SPOD_ACCOUNT')?></td>
					<td><?=$arResult["USER_NAME"]?></td>
				</tr>
			<?endif;?>
			<tr class="vl">
				<td><?=GetMessage('SPOD_LOGIN')?></td>
				<td><?=$arResult["USER"]["LOGIN"]?></td>
			</tr>
			<tr class="vl">
				<td><?=GetMessage('SPOD_EMAIL')?></td>
				<td><a href="mailto:<?=$arResult["USER"]["EMAIL"]?>"><?=$arResult["USER"]["EMAIL"]?></a></td>
			</tr>
		<?endif;?>
		<tr class="title"><td colspan="2"><h4><?=GetMessage('SPOD_ORDER_PROPERTIES')?></h4></td></tr>
				<tr class="vl">
					<td><?=GetMessage('SPOD_ORDER_PERS_TYPE')?>:</td>
					<td><?=$arResult["PERSON_TYPE"]["NAME"]?></td>
				</tr>
				<?/*				
				<tr>
					<td><?=GetMessage('SPOD_ORDER_COMPLETE_SET')?>:</td>
					<td></td>
				</tr>
				*/?>
				<?foreach($arResult["ORDER_PROPS"] as $prop):?>
					<?/*
					<?if($prop["SHOW_GROUP_NAME"] == "Y"):?>
						<tr class="gn">
							<td colspan="2"><?=$prop["GROUP_NAME"]?></td>
						</tr>
					<?endif;?>
					*/?>
					<tr class="vl">
						<td><?=$prop['NAME']?></td>
						<td>
							<?if($prop["TYPE"] == "CHECKBOX"):?>
								<?=GetMessage('SPOD_'.($prop["VALUE"] == "Y" ? 'YES' : 'NO'))?>
							<?else:?>
								<?=$prop["VALUE"]?>
							<?endif;?>
						</td>
					</tr>
				<?endforeach;?>
				<?if(!empty($arResult["USER_DESCRIPTION"])):?>
					<tr>
						<td><?=GetMessage('SPOD_ORDER_USER_COMMENT')?></td>
						<td><?=$arResult["USER_DESCRIPTION"]?></td>
					</tr>
				<?endif?>
		<tr class="title"><td colspan="2"><h4><?=GetMessage('SPOD_ORDER_PAYMENT')?></h4></td></tr>
				<tr class="vl">
					<td><?=GetMessage('SPOD_PAY_SYSTEM')?></td>
					<td>
						<?if(intval($arResult["PAY_SYSTEM_ID"])):?>
							<?=$arResult["PAY_SYSTEM"]["NAME"]?>
						<?else:?>
							<?=GetMessage("SPOD_NONE")?>
						<?endif;?>
					</td>
				</tr>
				<tr class="vl">
					<td><?=GetMessage('SPOD_ORDER_PAYED')?></td>
					<td>
						<?if($arResult["PAYED"] == "Y"):?>
							<?=GetMessage('SPOD_YES')?>
							(<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_PAYED_FORMATED"]?>)
						<?else:?>
							<?=GetMessage('SPOD_NO')?>
							<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
								&nbsp;&nbsp;&nbsp;
								<a class="button22" href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank">
									<span><?=GetMessage("SPOD_REPEAT_PAY")?></span>
								</a>
							<?endif;?>
						<?endif?>
					</td>
				</tr>
				<tr class="vl">
					<td><?=GetMessage("SPOD_ORDER_DELIVERY")?></td>
					<td>
						<?if(strpos($arResult["DELIVERY_ID"], ":") !== false || intval($arResult["DELIVERY_ID"])):?>
							<?=$arResult["DELIVERY"]["NAME"]?>

							<?if(intval($arResult['STORE_ID']) && !empty($arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']])):?>

								<?$store = $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']];?>
								<div class="bx_ol_store">
									<div class="bx_old_s_row_title">
										<?=GetMessage('SPOD_TAKE_FROM_STORE')?>: <b><?=$store['TITLE']?></b>

										<?if(!empty($store['DESCRIPTION'])):?>
											<div class="bx_ild_s_desc">
												<?=$store['DESCRIPTION']?>
											</div>
										<?endif?>
									</div>
									<?if(!empty($store['ADDRESS'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_ADDRESS')?></b>: <?=$store['ADDRESS']?>
										</div>
									<?endif;?>
									<?if(!empty($store['SCHEDULE'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_WORKTIME')?></b>: <?=$store['SCHEDULE']?>
										</div>
									<?endif;?>
									<?if(!empty($store['PHONE'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_PHONE')?></b> <?=$store['PHONE']?>
										</div>
									<?endif;?>
									<?if(!empty($store['EMAIL'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_EMAIL')?></b>: <a href="mailto:<?=$store['EMAIL']?>"><?=$store['EMAIL']?></a>
										</div>
									<?endif;?>
									<?if(($store['GPS_N'] = floatval($store['GPS_N'])) && ($store['GPS_S'] = floatval($store['GPS_S']))):?>
										<div id="bx_old_s_map">
											<div class="bx_map_buttons">
												<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" id="map-show">
													<?=GetMessage('SPOD_SHOW_MAP')?>
												</a>

												<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" id="map-hide">
													<?=GetMessage('SPOD_HIDE_MAP')?>
												</a>
											</div>
											<?ob_start();?>
												<div><?$mg = $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']]['IMAGE'];?>
													<?if(!empty($mg['SRC'])):?><img src="<?=$mg['SRC']?>" width="<?=$mg['WIDTH']?>" height="<?=$mg['HEIGHT']?>"><br /><br /><?endif?>
													<?=$store['TITLE']?></div>
											<?$ballon = ob_get_contents();?>
											<?ob_end_clean();?>
											<?
												$mapId = '__store_map';
												$mapParams = array(
												'yandex_lat' => $store['GPS_N'],
												'yandex_lon' => $store['GPS_S'],
												'yandex_scale' => 16,
												'PLACEMARKS' => array(
													array(
														'LON' => $store['GPS_S'],
														'LAT' => $store['GPS_N'],
														'TEXT' => $ballon
													)
												));
											?>
											<div id="map-container">
												<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view", ".default", array(
													"INIT_MAP_TYPE" => "MAP",
													"MAP_DATA" => serialize($mapParams),
													"MAP_WIDTH" => "100%",
													"MAP_HEIGHT" => "200",
													"CONTROLS" => array(
														0 => "SMALLZOOM",
													),
													"OPTIONS" => array(
														0 => "ENABLE_SCROLL_ZOOM",
														1 => "ENABLE_DBLCLICK_ZOOM",
														2 => "ENABLE_DRAGGING",
													),
													"MAP_ID" => $mapId
													),
													false
												);?>
											</div>
											<?CJSCore::Init();?>
											<script>
												new CStoreMap({mapId:"<?=$mapId?>", area: '.bx_old_s_map'});
											</script>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
						<?else:?>
							<?=GetMessage("SPOD_NONE")?>
						<?endif;?>
					</td>
				</tr>
				<?if($arResult["TRACKING_NUMBER"]):?>
					<tr class="vl">
						<td><?=GetMessage('SPOD_ORDER_TRACKING_NUMBER')?></td>
						<td><?=$arResult["TRACKING_NUMBER"]?></td>
					</tr>
				<?endif;?>
				<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] != "Y"):?>
					<tr>
						<td colspan="2">
							<?
							$ORDER_ID = $ID;
							include($arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]);
							?>
						</td>
					</tr>
				<?endif;?>
			</tbody>
		</table>
		<h4><?=GetMessage('SPOD_ORDER_BASKET')?></h4>
		<table class="module-orders-list colored goods">
			<thead>
				<tr>			
					<td colspan="2"><?=GetMessage('SPOD_NAME')?></td>
					<td><?=GetMessage('SPOD_QUANTITY')?></td>
					<td><?=GetMessage('SPOD_PRICE')?></td>
					<?if($arResult['HAS_PROPS']):?>
						<td class=""><?=GetMessage('SPOD_PROPS')?></td>
					<?endif;?>
					<?if($arResult['HAS_DISCOUNT']):?>
						<td class="vdscnt"><?=GetMessage('SPOD_DISCOUNT')?></td>
					<?endif;?>
					<td><?=GetMessage('SPOD_PRICETYPE')?></td>
				</tr>
			</thead>
			<tbody>
				<?foreach($arResult["BASKET"] as $prod):?>
					<tr>
						<?
						$hasLink = !empty($prod["DETAIL_PAGE_URL"]);
						if($imgID = ($prod["PREVIEW_PICTURE"] ? $prod["PREVIEW_PICTURE"] : $prod["DETAIL_PICTURE"])){
							$prod['PICTURE'] = CFile::ResizeImageGet($imgID, array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
						}
						$hasImg = strlen($prod['PICTURE']['src']);
						?>
						<?if($hasImg):?>
							<td class="vimg">
								<?if($hasLink):?>
									<a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank">
								<?endif;?>
								<?if($prod['PICTURE']['src']):?>
									<img src="<?=$prod['PICTURE']['src']?>" width="<?=$prod['PICTURE']['width']?>" height="<?=$prod['PICTURE']['height']?>" alt="<?=$prod['NAME']?>" />
								<?endif;?>
								<?if($hasLink):?>
									</a>
								<?endif;?>
							</td>
						<?endif;?>							
						<td class="vname" <?=($hasImg ? '' : 'colspan="2"')?>>
							<?if($hasLink):?>
								<a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank">
							<?endif;?>
							<?=htmlspecialcharsEx($prod["NAME"])?>
							<?if($hasLink):?>
								</a>
							<?endif;?>
						</td>
						<td class="vqnt">
							<?=$prod["QUANTITY"]?>
							
							<?if(strlen($prod['MEASURE_TEXT'])):?>
								<?=$prod['MEASURE_TEXT']?>.
							<?else:?>
								<?=GetMessage('SPOD_DEFAULT_MEASURE')?>.
							<?endif;?>
						</td>
						<td class="price"><?=$prod["PRICE_FORMATED"]?></td>
						<?if($arResult['HAS_PROPS']):?>
							<td>
								<?if(is_array($prod["PROPS"]) && !empty($prod["PROPS"])):?>
									<table cellspacing="0" class="bx_ol_sku_prop">
										<?foreach($prod["PROPS"] as $prop):?>
											<?if(!empty($prop['SKU_VALUE']) && $prop['SKU_TYPE'] == 'image'):?>
												<tr>
													<td>
														<nobr><?=$prop["NAME"]?>:</nobr>
														<?/*<img src="<?=$prop['SKU_VALUE']['PICT']['SRC']?>" width="<?=$prop['SKU_VALUE']['PICT']['WIDTH']?>" height="<?=$prop['SKU_VALUE']['PICT']['HEIGHT']?>" title="<?=$prop['SKU_VALUE']['NAME']?>" alt="<?=$prop['SKU_VALUE']['NAME']?>" />*/?>
													</td>
													<td style="padding-left: 10px !important"><?=$prop['SKU_VALUE']['NAME']?></td>
												</tr>
											<?else:?>
												<tr>
													<td><nobr><?=$prop["NAME"]?>:</nobr></td>
													<td style="padding-left: 10px !important"><?=$prop["VALUE"]?></td>
												</tr>
											<?endif;?>
										<?endforeach?>
									</table>
								<?endif;?>
							</td>
						<?endif;?>
						<?if($arResult['HAS_DISCOUNT']):?>
							<td class="vdscnt"><?=$prod["DISCOUNT_PRICE_PERCENT_FORMATED"]?></td>
						<?endif;?>
						<td><?=htmlspecialcharsEx($prod["NOTES"])?></td>
					</tr>
				<?endforeach;?>
			</tbody>
		</table>
		<div class="result-row">
			<div class="result">
				<table class="module-orders-list result">
					<tbody>
						<? ///// WEIGHT ?>
						<?if(floatval($arResult["ORDER_WEIGHT"])):?>
							<tr class="order_property d">
								<td class="custom_t1"><?=GetMessage('SPOD_TOTAL_WEIGHT')?>:</td>
								<td class="custom_t2 r"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
							</tr>
						<?endif;?>
						<? ///// PRICE SUM ?>
						<tr class="order_property d">
							<td class="custom_t1"><?=GetMessage('SPOD_PRODUCT_SUM')?>:</td>
							<td class="custom_t2 r"><?=$arResult['PRODUCT_SUM_FORMATTED']?></td>
						</tr>
						<? ///// DELIVERY PRICE: print even equals 2 zero ?>
						<?if(strlen($arResult["PRICE_DELIVERY_FORMATED"])):?>
							<tr class="order_property d">
								<td class="custom_t1"><?=GetMessage('SPOD_DELIVERY')?>:</td>
								<td class="custom_t2 r"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></td>
							</tr>
						<?endif;?>
						<? ///// TAXES DETAIL ?>
						<?foreach($arResult["TAX_LIST"] as $tax):?>
							<tr class="order_property d">
								<td class="custom_t1"><?=$tax["TAX_NAME"]?>:</td>
								<td class="custom_t2 r"><?=$tax["VALUE_MONEY_FORMATED"]?></td>
							</tr>	
						<?endforeach;?>
						<? ///// TAX SUM ?>
						<?if(floatval($arResult["TAX_VALUE"])):?>
							<tr class="order_property d">
								<td class="custom_t1"><?=GetMessage('SPOD_TAX')?>:</td>
								<td class="custom_t2 r"><?=$arResult["TAX_VALUE_FORMATED"]?></td>
							</tr>
						<?endif;?>
						<? ///// DISCOUNT ?>
						<?if(floatval($arResult["DISCOUNT_VALUE"])):?>
							<tr class="order_property d">
								<td class="custom_t1"><?=GetMessage('SPOD_DISCOUNT')?>:</td>
								<td class="custom_t2 r"><?=$arResult["DISCOUNT_VALUE_FORMATED"]?></td>
							</tr>
						<?endif;?>
						<tr class="order_property price">
							<td class="custom_t1 fwb"><?=GetMessage('SPOD_SUMMARY')?>:</td>
							<td class="custom_t2 fwb r"><?=$arResult["PRICE_FORMATED"]?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<a href="<?=$arResult["URL_TO_LIST"]?>?COPY_ORDER=Y&ID=<?=$arResult["ID"];?>" class="button vbig_btn"><?=GetMessage("SPOL_T_COPY_ORDER_DESCR")?></a>
			<?if($arResult["CANCELED"] == "N" && $arResult["CAN_CANCEL"] == "Y"):?>
				<a class="button vbig_btn transparent" href="<?=$arResult["URL_TO_CANCEL"]?>">
					<?=GetMessage("SPOD_ORDER_CANCEL")?>
				</a>
			<?endif;?>
			<div class="clear"></div>
		</div>
	</div>
<?endif;?>
<?$APPLICATION->SetTitle(GetMessage('SPOD_ORDER').' '.GetMessage('SPOD_NUM_SIGN').$arResult["ACCOUNT_NUMBER"].' '.GetMessage("SPOD_FROM").' '.$arResult["DATE_INSERT_FORMATED"]);?>
<?$APPLICATION->AddChainItem(GetMessage('SPOD_ORDER').' '.GetMessage('SPOD_NUM_SIGN').$arResult["ACCOUNT_NUMBER"]);?>