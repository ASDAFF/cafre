<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="info_block confirm">
	<div class="bx_section">
	<?if (!empty($arResult["ORDER"])){?>
	<?
	$dbBasket = CSaleBasket::GetList(Array("ID"=>"ASC"), Array("ORDER_ID"=>$arResult["ORDER"]["ID"]));
	$ar2 = array();
	while($arItems = $dbBasket->Fetch())
{
	 $ar2[] = $arItems;
}

$db_props = CSaleOrderPropsValue::GetOrderProps($arResult["ORDER"]["ID"]);
$arr3 = array();
while ($arProps = $db_props->Fetch())
{
 $arr3[] = $arProps;
}
foreach ($arr3 as $vel){
	if($vel['CODE'] == 'add'){

$tril = $vel['VALUE'];
$id_svoiz = $vel['ID'];

	}
}
CModule::IncludeModule("iblock");
 foreach ($ar2 as $key => $value)
                {
$intElementID = $value['PRODUCT_ID']; // ID предложения
$mxResult = CCatalogSku::GetProductInfo(
$intElementID
);
   $tovid = $mxResult['ID'];
   $res2 = CIBlockElement::GetByID($tovid);
if($ar_res2 = $res2->GetNext()){}
	/*echo '<pre>';
	print_r($ar_res2["DETAIL_PAGE_URL"]); 
	echo '</pre>';*/
				}
/*echo '<pre>';
print_r($ar2);
echo '</pre>';
*/
if($tril == 'false'){
	 ?>
	<script>
 $(function () {
        var tarifs=[<?
		$total = count($ar2);
		$counter = 0;

            foreach ($ar2 as $key => $value) {$counter++;
$intElementID = $value['PRODUCT_ID']; // ID предложения
$mxResult = CCatalogSku::GetProductInfo(
$intElementID
);
   $tovid = $mxResult['ID'];
   //echo $tovid;

   $db_props = CIBlockElement::GetProperty(26, $tovid, array("sort" => "asc"), Array("CODE"=>"BRAND"));
if($ar_props = $db_props->Fetch())
$res = CIBlockElement::GetByID($ar_props["VALUE"]);
if($ar_res = $res->GetNext())
	    $res2 = CIBlockElement::GetByID($tovid);
if($ar_res2 = $res2->GetNext())
$search_raz = explode("/", $ar_res2["DETAIL_PAGE_URL"]);
$nav = CIBlockSection::GetNavChain(false,$search_raz[2]);
			?>
                {
				"id": "<?=$value['PRODUCT_ID']?>",
				"name": "<?=$value['NAME']?>",
				"list_name": "<?=$value['NAME']?>",
				"brand": "<?=$ar_res['NAME']?>",
				"category": "<?while($arSectionPath = $nav->GetNext()){echo $arSectionPath["NAME"].'/';}?>",
				"list_position": <?=$key++;?>,
				"quantity": <?=$value['QUANTITY']?>,
				"price": '<?=round($value['PRICE'], 0)?>'
                }<?if($counter != $total){echo ',';}?>  
            <?}?>];
window.dataLayer = window.dataLayer || [];
dataLayer.push({
      'ecommerce': {
        'currencyCode': 'RUB',
        'purchase': {
          'actionField': {
            'id': "<?=$arResult["ORDER"]['ID']?>",
			'affiliation': 'cafre.ru',
            'revenue':  <?=round($arResult["ORDER"]["PRICE"])?>,
            'shipping': 0
          },
          'products': tarifs
        }
      },
      'event': 'gtm-ee-event',
      'gtm-ee-event-category': 'Enhanced Ecommerce',
      'gtm-ee-event-action': 'Purchase',
      'gtm-ee-event-non-interaction': 'False',
    }); 
 });
</script>
<?
  CSaleOrderPropsValue::Update($id_svoiz, array("ORDER_ID" => $arResult["ORDER"]["ID"], "CODE"=>"add", "VALUE" => "true"));
}
	?>
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
		
		<h3 class="bg_block">Спасибо, что доверили нам заботу о своей красоте!<?//=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></h3>
		<table class="sale_order_full_table">
			<tr>
				<td>
					<?/*= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
					<br /><br />
					<?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) */?>
					<p>В ближайшее время вам перезвонит сотрудник Call-центра для уточнения заказа и способа доставки.<br /><br />

					Вы также можете задать ему все интересующие вопросы о товарах и сервисе интернет-магазина Cafre.ru<br /><br />

					Режим работы Call – центра: с 09:00 до 21:00 по московскому времени ежедневно.<br /><br />

					Оплата осуществляется наложенным платежом при получении товара.<br /><br />

					Подробные условия в разделе <a href="/help/info_order/">«Оплата, доставка и возврат товара»</a></p>
				</td>
			</tr>
		</table>
		<?if (!empty($arResult["PAY_SYSTEM"])){?>
			<table class="sale_order_full_table pay" style="display:none;">
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
