<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="section module-cart">
<div class="title summary"><?=GetMessage("SOA_TEMPL_SUM_TITLE")?></div>

<table class="colored summary">
	<thead>
		<tr>
			<td class="thumb-cell"></td>
			<td class="name-cell"><?=GetMessage("SOA_TEMPL_SUM_NAME")?></td>
			<td class="order_item_props"><?=GetMessage("SOA_TEMPL_SUM_PROPS")?></td>
			<td class="order_item_price_type"><?=GetMessage("SOA_TEMPL_SUM_PRICE_TYPE")?></td>
			<td class="order_item_discount"><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?></td>
			<td class="order_item_weight"><?=GetMessage("SOA_TEMPL_SUM_WEIGHT")?></td>
			<td class="order_item_quantity"><?=GetMessage("SOA_TEMPL_SUM_QUANTITY")?></td>
			<td class="order_item_price"><?=GetMessage("SOA_TEMPL_SUM_PRICE")?></td>
		</tr>
	</thead>
	<tbody>
	<?
	foreach($arResult["BASKET_ITEMS"] as $arBasketItems)
	{
		?>
		<tr>
			<td class="thumb-cell">
				<?if (count($arBasketItems["DETAIL_PICTURE"]) > 0):?>
					<?$img = CFile::ResizeImageGet($arBasketItems["DETAIL_PICTURE"], Array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" =>  $arParams["DISPLAY_IMG_HEIGHT"]));?>
					<img src="<?=$img["src"]?>" alt="<?=$arBasketItems["NAME"]?>" title="<?=$arBasketItems["NAME"]?>" />
				<?elseif(count($arBasketItems["PREVIEW_PICTURE"]) > 0):?>	
					<?$img = CFile::ResizeImageGet($arBasketItems["DETAIL_PICTURE"], Array("width" => $arParams["DISPLAY_IMG_WIDTH"], "height" =>  $arParams["DISPLAY_IMG_HEIGHT"]));?>
					<img src="<?=$img["src"]?>" alt="<?=$arBasketItems["NAME"]?>" title="<?=$arBasketItems["NAME"]?>" />
				<?else:?>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png"  alt="<?=$arBasketItems["NAME"]?>" title="<?=$arBasketItems["NAME"]?>">
				<?endif;?>
			</td>
			<td class="name-cell"><?=$arBasketItems["NAME"]?><span class="order_item_quantity_small">, <b><?=$arBasketItems["QUANTITY"]?> <?=GetMessage("MEASURE");?></b></span></td>
			<td class="order_item_props"><? foreach($arBasketItems["PROPS"] as $val) { echo $val["NAME"].": ".$val["VALUE"]."<br />"; } ?></td>
			<td class="order_item_price_type"><?=$arBasketItems["NOTES"]?></td>
			<td class="order_item_discount"><?=$arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"]?></td>
			<td class="order_item_weight"><?=$arBasketItems["WEIGHT_FORMATED"]?></td>
			<td class="order_item_quantity"><?=$arBasketItems["QUANTITY"]?></td>
			<td align="right" class="cost_cell"><?=$arBasketItems["PRICE_FORMATED"]?></td>
		</tr>
		<?
	}
	?>
	</tbody>
	
	<tr>
		<td class="name-cell"></td>
		<td class="order_item_props"></td>
		<td class="order_item_price_type"></td>
		<td class="order_item_discount"></td>
		<td class="order_item_weight"></td>
		<td class="order_item_quantity"></td>
		<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></b></td>
		<td align="right"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
	</tr>
	<tr>
		<td class="name-cell"></td>
		<td class="order_item_props"></td>
		<td class="order_item_price_type"></td>
		<td class="order_item_discount"></td>
		<td class="order_item_weight"></td>
		<td class="order_item_quantity"></td>
		<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></b></td>
		<td align="right"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
	</tr>
	<?
	if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
	{
		?>
		<tr>
			<td class="name-cell"></td>
			<td class="order_item_props"></td>
			<td class="order_item_price_type"></td>
			<td class="order_item_discount"></td>
			<td class="order_item_weight"></td>
			<td class="order_item_quantity"></td>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</b></td>
			<td align="right"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?>
			</td>
		</tr>
		<?
	}
	if(!empty($arResult["arTaxList"]))
	{
		foreach($arResult["arTaxList"] as $val)
		{
			?>
			<tr>
				<td class="name-cell"></td>
				<td class="order_item_props"></td>
				<td class="order_item_price_type"></td>
				<td class="order_item_discount"></td>
				<td class="order_item_weight"></td>
				<td class="order_item_quantity"></td>
				<td align="right"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
				<td align="right"><?=$val["VALUE_MONEY_FORMATED"]?></td>
			</tr>
			<?
		}
	}
	if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
	{
		?>
		<tr>
			<td class="name-cell"></td>
			<td class="order_item_props"></td>
			<td class="order_item_price_type"></td>
			<td class="order_item_discount"></td>
			<td class="order_item_weight"></td>
			<td class="order_item_quantity"></td>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></b></td>
			<td align="right"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
		</tr>
		<?
	}
	?>
	<tr>
		<td class="name-cell"></td>
		<td class="order_item_props"></td>
		<td class="order_item_price_type"></td>
		<td class="order_item_discount"></td>
		<td class="order_item_weight"></td>
		<td class="order_item_quantity"></td>
		<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_IT")?></b></td>
		<td align="right"><b><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></b>
		</td>
	</tr>
	<?
	if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
	{
		?>
		<tr>
			<td class="name-cell"></td>
			<td class="order_item_props"></td>
			<td class="order_item_price_type"></td>
			<td class="order_item_discount"></td>
			<td class="order_item_weight"></td>
			<td class="order_item_quantity"></td>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></b></td>
			<td align="right"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
		</tr>
		<?
	}
	?>
</table>


<br /><br />
<div class="title"><?=GetMessage("SOA_TEMPL_SUM_ADIT_INFO")?></div>

<table class="sale_order_table">
	<tr>
		<td class="order_comment">
			<div><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></div>
			<textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
		</td>
	</tr>
</table>



</div>