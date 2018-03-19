<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (is_array($arResult["RESULT"]))
{
	if ($arResult["RESULT"]["RESULT"] == "NEXT_STEP")
		require("step.php");
	else
	{
		if ($arResult["RESULT"]["RESULT"] == "ERROR")
			echo ShowError($arResult["RESULT"]["TEXT"]);
		elseif ($arResult["RESULT"]["RESULT"] == "NOTE")
			echo ShowNote($arResult["RESULT"]["TEXT"]);
		elseif ($arResult["RESULT"]["RESULT"] == "OK")
		{
			$free_delivery_text=COption::GetOptionString("aspro.mshop", "EXPRESSION_FOR_FREE_DELIVERY", GetMessage("EXPRESSION_FOR_FREE_DELIVERY_DEFAULT"), SITE_ID);
			// echo GetMessage('SALE_SADC_RESULT').": <b>".(strlen($arResult["RESULT"]["VALUE_FORMATTED"]) > 0 ? $arResult["RESULT"]["VALUE_FORMATTED"] : number_format($arResult["RESULT"]["VALUE"], 2, ',', ' '))."</b>";
			echo GetMessage('SALE_SADC_RESULT').": <b>".(doubleval($arResult["RESULT"]["VALUE_FORMATTED"]) > 0 ? $arResult["RESULT"]["VALUE_FORMATTED"] : $free_delivery_text)."</b>";
			if ($arResult["RESULT"]["TRANSIT"] > 0)
			{
				echo '<br />';
				echo GetMessage('SALE_SADC_TRANSIT').': <b>'.$arResult["RESULT"]["TRANSIT"].'</b>';
			}

			if ($arResult["RESULT"]["PACKS_COUNT"] > 1)
			{
				echo '<br />';
				echo GetMessage('SALE_SADC_PACKS').': <b>'.$arResult["RESULT"]["PACKS_COUNT"].'</b>';
			}

		}
	}
}

if ($arParams["STEP"] == 0)
	require("start.php");
?>