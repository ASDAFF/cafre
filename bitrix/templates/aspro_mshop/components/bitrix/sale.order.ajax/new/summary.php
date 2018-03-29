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

<div class="order__items">
	<!--<h4><?//=GetMessage("SALE_PRODUCTS_SUMMARY");?></h4>-->
	<div class="order__message order__message_items">
							<div class="pinkgirl">
								<img src="<?=SITE_TEMPLATE_PATH?>/pinkgirl/pinkgirl4.png" alt="">
							</div>
							<p>Посмотри, сколько полезного лежит в твоей корзине</p>
	</div>
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
			
				<?
				/*$arResult["GRID"]["ROWS"][] = array(
            "id" => 99999999999,
            "data" => Array
                (
                    "ID" => 99999999999,
                    "~ID" => 99999999999,
                    "CALLBACK_FUNC",
                    "~CALLBACK_FUNC" ,
                    "MODULE" => "catalog",
                    "~MODULE" => "catalog",
                    "PRODUCT_ID" => 99999999999,
                    "~PRODUCT_ID" => 99999999999,
                    "QUANTITY" => 1,
                    "~QUANTITY" => 1,
                    "DELAY" => "N",
                    "~DELAY" => "N",
                    "CAN_BUY" => "Y",
                    "~CAN_BUY" => "Y",
                    "PRICE" => 0,
                    "~PRICE" => 0,
                    "WEIGHT" => 0,
                    "~WEIGHT" => 0.00,
                    "NAME" => "Ваш подарок уже в корзине",
                    "~NAME" => "Ваш подарок уже в корзине",
                    "CURRENCY" => "RUB",
                    "~CURRENCY" => "RUB",
                    "CATALOG_XML_ID" => 27,
                    "~CATALOG_XML_ID" => 27,
                    "VAT_RATE" => 0,
                    "~VAT_RATE" => 0.00,
                    "NOTES" => "Розничная цена",
                    "~NOTES" => "Розничная цена",
                    "DISCOUNT_PRICE" => 0,
                    "~DISCOUNT_PRICE" => 0,
                    "PRODUCT_PROVIDER_CLASS" => "CCatalogProductProvider",
                    "~PRODUCT_PROVIDER_CLASS" => "CCatalogProductProvider",
                    "DIMENSIONS",
                    "~DIMENSIONS",
                    "TYPE",
                    "~TYPE",
                    "SET_PARENT_ID",
                    "~SET_PARENT_ID" , 
                    "DETAIL_PAGE_URL" => "/catalog/volosy/okrashivanie/kraska_dlya_volos/krem_kraska_dlya_volos_studio_kapous_ottenok_5_85_svetlo_korichnevyy_makhagonovyy_100_ml/",
                    "~DETAIL_PAGE_URL" => "/catalog/volosy/okrashivanie/kraska_dlya_volos/krem_kraska_dlya_volos_studio_kapous_ottenok_5_85_svetlo_korichnevyy_makhagonovyy_100_ml/",
                    "PRICE_FORMATED" => "0 руб.",
                    "WEIGHT_FORMATED" => "0 г",
                    "DISCOUNT_PRICE_PERCENT" => 0,
                    "DISCOUNT_PRICE_PERCENT_FORMATED" => 0,
                    "PROPS" => Array
                        (
                        ),

                    "MEASURE_TEXT" ,
                    "MEASURE" ,
                    "PREVIEW_PICTURE" => 301165,
                    "PREVIEW_TEXT" => "Ваш подарок уже в корзине",
                    "PREVIEW_PICTURE_SRC" => "",
                    "DETAIL_PICTURE_SRC",
                    "BASE_PRICE" => 0,
                    "SUM" => "0 руб."
                ),

            "actions" => Array
                (
                ),

            "columns" => Array
                (
                    "PROPS" , 
                    "PREVIEW_PICTURE"
                ),

            "editable" => 1
);*/
				
$i = 0;
$len = count($arResult["GRID"]["ROWS"]);
				foreach ($arResult["GRID"]["ROWS"] as $k => $arData):
					$arItem = (isset($arData["columns"][$arColumn["id"]])) ? $arData["columns"] : $arData["data"];
					$class_td='';
					if(!$bShowNameWithPicture){
						$class_td="no_img";
					}
					if($i == 0){
					?>
					<tr class="pod">
					<td class="itemphoto thumb-cell">
					<a class="thumb">
					<img src="/bitrix/templates/aspro_mshop/images/podarok.jpg" alt="Ваш подарок уже в корзине" title="Ваш подарок уже в корзине" width="80" height="80">
					</a>
					</td>
					<td class="item name-cell">
					<a>Ваш подарок уже в корзине</a>
					</td>
					<td class="price cost-cell">
					<div class="cost prices clearfix">
					<div class="price">0 руб.</div>
					</div>
					</td>
					<td class="custom quantity "></td>
					<td class="custom sum "></td>
					</tr>
					<?}?>
					<?
					/*$masurl = explode("/",$arItem["DETAIL_PAGE_URL"]);
					unset($masurl[0]);unset($masurl[1]);$fruit = array_pop($masurl);$fruit2 = array_pop($masurl);
					$category="";
					foreach($masurl as $ket => $val_sec){				
						$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, '=CODE' => $val_sec));
if ($arSection = $rsSections->Fetch())
{
	if(!next($masurl)){
$category.= $arSection['NAME'];
}else{
	$category.= $arSection['NAME'].'/';
}
}					}
 date-category="<?=$category?>"
*/
					/*$mxResult = CCatalogSku::GetProductInfo($arItem["PRODUCT_ID"]);
					$db_props = CIBlockElement::GetProperty(26, $mxResult["ID"], array("sort" => "asc"), Array("CODE"=>"CATALOG_BREND"));
if($ar_props = $db_props->Fetch()){
$res = CIBlockSection::GetByID($ar_props["VALUE"]);
if($ar_res = $res->GetNext())
  $name_brand = $ar_res['NAME'];
	}*/?>
					<tr class="tov_order" data-idtov="<?=$arItem["PRODUCT_ID"]?>" data-nametov="<?=$arItem["NAME"];?>" data-price="<?=$arItem["PRICE"];?>" data-brand="<?=$name_brand;?>">
<?
					if ($bShowNameWithPicture):
					?>
						<td class="itemphoto thumb-cell">
							
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
									<div class="block_q">
									<a data-but="minus" class="minus">-</a>
									<?=$arItem[$arColumn["id"]]?>
									<a data-but="plus" class="plus">+</a>
									<input type="hidden" class="idtov" value="<?=$k?>"/>
									</div>
									
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
				<?
				$i++;
				endforeach;?>
			</tbody>
		</table>
	</div>

	<div class="bx_ordercart_order_pay">
		<div class="bx_ordercart_order_pay_right">
			<table class="bx_ordercart_order_sum">
				<tbody>
					<!--<tr>
						<td class="custom_t1" colspan="<?//=$colspan?>" class="itog"><?//=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></td>
						<td class="custom_t2" class="price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
					</tr>
					<tr>
						<td class="custom_t1" colspan="<?//=$colspan?>" class="itog"><?//=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></td>
						<td class="custom_t2" class="price"><?//=$arResult["ORDER_PRICE_FORMATED"]?></td>
					</tr>-->
					<?
					/*if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
					{
						?>
						<tr>
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</td>
							<td class="custom_t2" class="price"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?></td>
						</tr>
						<?
					}*/
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
						<tr class="sum_new_bas">
							<td class="custom_t1" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></td>
							<td class="custom_t2" class="price"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
						</tr>
						<?
					}

					if ($bUseDiscount):?>
						<tr class="sum_new_bas">
							<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
							<td class="custom_t2 fwb" class="price">
								<div class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></div>
								<strike><?=$arResult["PRICE_WITHOUT_DISCOUNT"]?></strike>
							</td>
						</tr>
					<?else:?>
						<tr class="sum_new_bas">
							<td class="custom_t1 fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("SOA_TEMPL_SUM_IT")?></td>
							<td class="custom_t2 fwb" class="price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></td>
							
						</tr>
					<?
					endif;
					?>
					<span class="total_bas" style="display:none;"><?=$arResult["ORDER_PRICE"];?></span>
				</tbody>
			</table>
			<div style="clear:both;"></div>
		</div>
		<!--<div style="clear:both;"></div>
		<div class="bx_section_bottom">
			<h3><?//=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></h3>
			<div class="bx_block w100"><textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:100%;min-height:120px"><?//=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea></div>
			<input type="hidden" name="" value="">
			<div style="clear: both;"></div><br />
		</div>-->
	</div>
</div>
