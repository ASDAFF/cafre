<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
</div>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bDiscountColumn = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<div class="bx_ordercart basket_wrapp">
	<h4><?=GetMessage("SALE_PRODUCTS_SUMMARY");?></h4>
	<div class="bx_ordercart_order_table_container module-cart">
		<table class="colored">
			<thead>
				<tr>
					<?
					$bPreviewPicture = false;
					$bDetailPicture = false;
					$imgCount = 0;

					// prelimenary column handling
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn){
						if ($arColumn["id"] == "PROPS")
							$bPropsColumn = true;
						if ($arColumn["id"] == "NOTES")
							$bPriceType = true;
						if ($arColumn["id"] == "PREVIEW_PICTURE")
							$bPreviewPicture = true;
						if ($arColumn["id"] == "DETAIL_PICTURE")
							$bDetailPicture = true;
						if ($arColumn["id"] == "DISCOUNT_PRICE_PERCENT_FORMATED")
							$bDiscountColumn = true;
					}

					if ($bPreviewPicture || $bDetailPicture)
						$bShowNameWithPicture = true;
					


					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):

						if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES", "DISCOUNT_PRICE_PERCENT_FORMATED"))) // some values are not shown in columns in this template
							continue;

						if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture || $arColumn["id"] == "DETAIL_PICTURE")
							continue;

						if ($arColumn["id"] == "NAME" && $bShowNameWithPicture):
						?>
							<td class="thumb-cell"></td><td class="item name-th">
						<?
							echo GetMessage("SALE_PRODUCTS");
						elseif ($arColumn["id"] == "NAME" && !$bShowNameWithPicture):
						?>
							<td class="item name-th">
						<?
							echo $arColumn["name"];
						elseif ($arColumn["id"] == "PRICE"):
						?>
							<td class="price">
						<?
							echo $arColumn["name"];
						else:
						?>
							<td class="custom">
						<?
							echo $arColumn["name"];
						endif;
						?>
							</td>
					<?endforeach;?>
				</tr>
			</thead>

			<tbody>
				<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData):
					$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];
					$class_td='';
					if(!$bShowNameWithPicture){
						$class_td="no_img";
					}?>
				<tr>
					<?
					if ($bShowNameWithPicture):
					?>
						<td class="itemphoto thumb-cell">
							<?//print_r($arItem)?>
							<?if( strlen($arItem["PREVIEW_PICTURE_SRC"])>0 ){?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
									<img src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
							<?}elseif( strlen($arItem["DETAIL_PICTURE_SRC"])>0 ){?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
									<img src="<?=$arItem["DETAIL_PICTURE_SRC"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
							<?}else{?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" width="80" height="80" />
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
							<?}?>
							<?if (!empty($arData["data"]["BRAND"])):?>
								<div class="bx_ordercart_brand">
									<img alt="" src="<?=$arData["data"]["BRAND"]?>" />
								</div>
							<?endif;?>
						</td>
					<?
					endif;

					// prelimenary check for images to count column width
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn){
						$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];
						if (is_array($arItem[$arColumn["id"]])){
							foreach ($arItem[$arColumn["id"]] as $arValues){
								if ($arValues["type"] == "image")
									$imgCount++;
							}
						}
					}

					foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):
						$class = ($arColumn["id"] == "PRICE_FORMATED") ? "price" : "";
						if (in_array($arColumn["id"], array("PROPS", "TYPE", "NOTES", "DISCOUNT_PRICE_PERCENT_FORMATED"))) // some values are not shown in columns in this template
							continue;

						if ($arColumn["id"] == "PREVIEW_PICTURE" && $bShowNameWithPicture || $arColumn["id"] == "DETAIL_PICTURE")
							continue;

						$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];

						if ($arColumn["id"] == "NAME"):
							$width = 50 - ($imgCount * 20);
						?>
							<td class="item name-cell <?=$class_td;?>" style1="width:<?=$width?>%">

								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
									<?=$arItem["NAME"]?>
								<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>

								<div class="bx_ordercart_itemart">
									<?
									if ($bPropsColumn):
										foreach ($arItem["PROPS"] as $val):?>
											<div class="bx_item_detail_size">
												<?echo "<span class='titles'>".$val["NAME"].":</span><div class='values'>".$val["VALUE"]."</div>";?>
											</div>
										<?endforeach;
									endif;
									?>
								</div>
								<?
								if (is_array($arItem["SKU_DATA"])):
									foreach ($arItem["SKU_DATA"] as $propId => $arProp):

										// is image property
										$isImgProperty = false;
										foreach ($arProp["VALUES"] as $id => $arVal)
										{
											if (isset($arVal["PICT"]) && !empty($arVal["PICT"]))
											{
												$isImgProperty = true;
												break;
											}
										}

										$full = (count($arProp["VALUES"]) > 5) ? "full" : "";

										if ($isImgProperty): // iblock element relation property
										?>
											<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">

												<span class="bx_item_section_name_gray">
													<?=$arProp["NAME"]?>:
												</span>

												<div class="bx_scu_scroller_container">

													<div class="bx_scu">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%;margin-left:0%;">
														<?
														foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

															$selected = "";
															foreach ($arItem["PROPS"] as $arItemProp):
																if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																{
																	if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																		$selected = "class=\"bx_active\"";
																}
															endforeach;
														?>
															<li style="width:10%;" <?=$selected?>>
																<a href="javascript:void(0);">
																	<span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span>
																</a>
															</li>
														<?
														endforeach;
														?>
														</ul>
													</div>

													<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
													<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
												</div>

											</div>
										<?
										else:
										?>
											<div class="bx_item_detail_size_small_noadaptive <?=$full?>">

												<span class="bx_item_section_name_gray">
													<?=$arProp["NAME"]?>:
												</span>

												<div class="bx_size_scroller_container">
													<div class="bx_size">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" style="width: 200%; margin-left:0%;">
															<?
															foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

																$selected = "";
																foreach ($arItem["PROPS"] as $arItemProp):
																	if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																	{
																		if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																			$selected = "class=\"bx_active\"";
																	}
																endforeach;
															?>
																<li style="width:10%;" <?=$selected?>>
																	<a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a>
																</li>
															<?
															endforeach;
															?>
														</ul>
													</div>
													<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
													<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
												</div>

											</div>
										<?
										endif;
									endforeach;
								endif;
								?>
							</td>
						<?elseif ($arColumn["id"] == "PRICE_FORMATED"):?>
							<td class="price cost-cell <?=( $bPriceType ? 'notes' : '' );?> <?=$class_td;?>">
								<?//print_r($arItem)?>
								<div class="cost prices clearfix">
									<?if (strlen($arItem["NOTES"]) > 0 && $bPriceType):?>
										<div class="price_name"><?=$arItem["NOTES"]?></div>
									<?endif;?>
									<?if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 && $bDiscountColumn ){?>
										<div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
										<div class="price discount"><strike><?=SaleFormatCurrency(($arItem["DISCOUNT_PRICE"]+$arItem["PRICE"]), $arItem["CURRENCY"])?></strike></div>
										<div class="sale_block">
											<?if($arItem["DISCOUNT_PRICE_PERCENT"] && $arItem["DISCOUNT_PRICE_PERCENT"]<100){?>
												<div class="value">-<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"];?></div>
											<?}?>
											<div class="text"><?=GetMessage("ECONOMY")?> <?=SaleFormatCurrency(round($arItem["DISCOUNT_PRICE"]), $arItem["CURRENCY"]);?></div>
											<div class="clearfix"></div>
										</div>
										<?$bUseDiscount = true;?>
									<?}else{?>
										<div class="price"><?=$arItem["PRICE_FORMATED"];?></div>
									<?}?>
								</div>
							</td>
						<?elseif ($arColumn["id"] == "DISCOUNT"):?>
							<td class="custom <?=$class_td;?>">
								<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?>
							</td>
						<?elseif ($arColumn["id"] == "DETAIL_PICTURE" && $bPreviewPicture):?>
							<td class="itemphoto <?=$class_td;?>">
								<div class="bx_ordercart_photo_container">
									<?
									$url = "";
									if ($arColumn["id"] == "DETAIL_PICTURE" && strlen($arData["data"]["DETAIL_PICTURE_SRC"]) > 0)
										$url = $arData["data"]["DETAIL_PICTURE_SRC"];

									if ($url == "")
										$url = SITE_TEMPLATE_PATH."/images/no_photo_medium.png";

									if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arData["data"]["DETAIL_PAGE_URL"] ?>"><?endif;?>
										<div class="bx_ordercart_photo" style="background-image:url('<?=$url?>')"></div>
									<?if (strlen($arData["data"]["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
								</div>
							</td>
						<?elseif (in_array($arColumn["id"], array("QUANTITY", "WEIGHT_FORMATED", "SUM"))):?>
							<td class="custom <?=strtolower($arColumn["id"]);?> <?=$class_td;?>">
								<?if($arColumn["id"]=="SUM"){?>
									<div class="cost prices"><div class="price"><?=$arItem[$arColumn["id"]]?></div></div>
								<?}else{?>
									<?=$arItem[$arColumn["id"]]?>
								<?}?>
							</td>
						<?else: // some property value

							if (is_array($arItem[$arColumn["id"]])):

								foreach ($arItem[$arColumn["id"]] as $arValues)
									if ($arValues["type"] == "image")
										$columnStyle = "width:20%";
							?>
							<td class="custom <?=$class_td;?>" style="<?=$columnStyle?>">
								<?
								foreach ($arItem[$arColumn["id"]] as $arValues):
									if ($arValues["type"] == "image"):
									?>
										<div class="bx_ordercart_photo_container">
											<div class="bx_ordercart_photo" style="background-image:url('<?=$arValues["value"]?>')"></div>
										</div>
									<?
									else: // not image
										echo $arValues["value"]."<br/>";
									endif;
								endforeach;
								?>
							</td>
							<?
							else: // not array, but simple value
							?>
							<td class="custom <?=$class_td;?>" style="<?=$columnStyle?>">
								<?
									echo $arItem[$arColumn["id"]];
								?>
							</td>
							<?
							endif;
						endif;
					endforeach;
					?>
				</tr>
				<?endforeach;?>
			</tbody>
		</table>
	</div>

	<div class="bx_ordercart_order_pay">
		<div class="bx_ordercart_order_pay_right">
			<table class="bx_ordercart_order_sum">
				<tbody>
					<tr>
						<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></td>
						<td class="custom_t2" class="price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
					</tr>
					<tr>
						<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></td>
						<td class="custom_t2" class="price"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
					</tr>
					<?
					if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
					{
						?>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</td>
							<td class="custom_t2" class="price"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?></td>
						</tr>
						<?
					}
					if(!empty($arResult["TAX_LIST"]))
					{
						foreach($arResult["TAX_LIST"] as $val)
						{
							?>
							<tr>
								<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
								<td class="custom_t2" class="price"><?=$val["VALUE_MONEY_FORMATED"]?></td>
							</tr>
							<?
						}
					}
					if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
					{
						?>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></td>
							<td class="custom_t2" class="price"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
						</tr>
						<?
					}
					if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
					{
						?>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></td>
							<td class="custom_t2" class="price"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
						</tr>
						<?
					}

					if ($bUseDiscount):?>
						<tr>
							<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
							<td class="custom_t2 fwb" class="price">
								<div class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></div>
								<strike><?=$arResult["PRICE_WITHOUT_DISCOUNT"]?></strike>
							</td>
						</tr>
					<?else:?>
						<tr>
							<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
							<td class="custom_t2 fwb" class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
						</tr>
					<?
					endif;
					?>
				</tbody>
			</table>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>
		<div class="bx_section_bottom">
			<h3><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></h3>
			<div class="bx_block w100"><textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea></div>
			<input type="hidden" name="" value="">
			<div style="clear: both;"></div><br />
		</div>
	</div>
</div>
