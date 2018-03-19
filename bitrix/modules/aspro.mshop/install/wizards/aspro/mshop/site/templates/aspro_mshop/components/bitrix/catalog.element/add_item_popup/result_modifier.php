<?
	if(intval($arParams["OFFER_ID"]))
	{
		foreach($arResult["OFFERS"] as $key=>$offer)
		{
			if($offer["ID"]==intval($arParams["OFFER_ID"]))
			{
				foreach ($offer["PRICES"] as $key=>$price){ $curPrice=$price["VALUE"]; break; }
				foreach ($offer["PRICES"] as $key=>$price)
				{	
					if ($curPrice>$price["VALUE"]) 	{ $curPrice = $price["VALUE"]; $priceId = $key; }
				}
				unset($arResult["PRICES"]);
				$arResult["PRICES"][] = $offer["PRICES"][$key];
			}
		}
	}
?>