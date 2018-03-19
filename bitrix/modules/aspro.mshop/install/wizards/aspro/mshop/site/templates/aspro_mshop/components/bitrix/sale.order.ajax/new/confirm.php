<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="info_block confirm">
	<div class="bx_section">
	<?if (!empty($arResult["ORDER"])){?>
		<?
		/*set user phone*/
		$orderID = $arResult["ORDER"]["ID"];
		
		if( $orderID ){
			$resOrder = CSaleOrderPropsValue::GetList( array("DATE_UPDATE" => "DESC"), array( "ORDER_ID" => $orderID ) );
			while( $item = $resOrder->fetch() ){
				$arOrder[$item["CODE"]] = $item;
			}
		}

		$arFields = array();
		$arUser=CUser::GetList(($by="personal_country"), ($order="desc"), array("ID"=>$GLOBALS["USER"]->getID()), array("FIELDS"=>array("PERSONAL_PHONE", "EMAIL", "ID")))->Fetch();
		if( !$arUser["PERSONAL_PHONE"] ){
			if( strlen( $arOrder["PHONE"]["VALUE"] ) ){
				$arFields["PERSONAL_PHONE"] = $arOrder["PHONE"]["VALUE"];
				$GLOBALS["USER"]->Update( $arUser["ID"], $arFields );
			}
		}?>
		
		<h3 class="bg_block"><?=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></h3>
		<table class="sale_order_full_table">
			<tr>
				<td>
					<?= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
					<br /><br />
					<?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
				</td>
			</tr>
		</table>
		<?if (!empty($arResult["PAY_SYSTEM"])){?>
			<table class="sale_order_full_table pay">
				<tr>
					<td class="ps_logo">
						<h5><?=GetMessage("SOA_TEMPL_PAY")?></h5>
						<?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
						<div class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div><br>
					</td>
				</tr>
				<?if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0){?>
					<tr>
						<td>
							<?if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y"){
								?>
								<script language="JavaScript">
									window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
								</script>
								<?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))))?><br/><br/>
								<a class="button big_btn" href="<?=$arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>" target="_blank"><?=GetMessage("PAY_ORDER")?></a>
								<?
								if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
								{
									?><br />
									<?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
									<?
								}
							}else{
								if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
								{
									try
									{
										include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
									}
									catch(\Bitrix\Main\SystemException $e)
									{
										if($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE)
											$message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
										else
											$message = $e->getMessage();

										echo '<span style="color:red;">'.$message.'</span>';
									}
								}
							}?>
						</td>
					</tr>
					<?
				}?>
			</table>
			<?
			if(!$_SESSION["EXISTS_ORDER"][$arResult["ORDER"]["ID"]]){?>
				<div class="ajax_counter"></div>
				<script>
					purchaseCounter('<?=$arResult["ORDER"]["ID"];?>', '<?=GetMessage("FULL_ORDER");?>');
				</script>
				<?
				$_SESSION["EXISTS_ORDER"][$arResult["ORDER"]["ID"]] = "Y";
			}?>
			<?
		}
	}else{?>
		<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />
		<table class="sale_order_full_table">
			<tr>
				<td>
					<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
					<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
				</td>
			</tr>
		</table>
		<?
	}?>
	</div>
</div>
