<div  style="display:none;">
	<span itemtype="https://schema.org/Product" itemscope="">
		<meta content="<?$APPLICATION->ShowTitle()?>" itemprop="name">
		<meta itemprop="description" content="<?=$APPLICATION->GetProperty("description")?>" />
		<span itemtype="https://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating" class="mc-star-count">
			<span itemprop="ratingValue"><?=$section["UF_RATINGVALUE"]?></span> / <span itemprop="ratingCount"><?=$section["UF_RATINGCOUNT"]?></span>
			<meta itemprop="worstRating" content="1" />
			<meta itemprop="bestRating" content="5" />
		</span>
		<span itemtype="https://schema.org/AggregateOffer" itemscope="" itemprop="offers"> 
		<?
		$arFilter=Array('IBLOCK_ID'=>26, 'GLOBAL_ACTIVE'=>'Y', 'SECTION_ID'=>$arResult["VARIABLES"]["SECTION_ID"]);
		$db_list = CIBlockSection::GetList(Array(), $arFilter, true);
		while($ar_result = $db_list->GetNext()) {
			$arrayID[] = $ar_result['ID'];
		}
		$db_price_min = CIBlockElement::GetList(
			array("SORT"=>"ASC"), 
			array("IBLOCK_ID" => 26, "SECTION_ID"=> $arrayID, "ACTIVE"=>"Y"), 
			false, 
			false,
			array("ID")
		);
		$i=0;
		while($ar_fields = $db_price_min->GetNext()){
			$i++;
			$secid[] = $ar_fields["ID"];
		}
		$db_price_max2 = CIBlockElement::GetList(
			array("CATALOG_PRICE_1" => "DESC"), 
			array("IBLOCK_ID" => 27, "ACTIVE"=>"Y", "PROPERTY_CML2_LINK" => $secid), 
			false, 
			array("nPageSize"=>1),
			array("ID")
		);
		$db_price_min2 = CIBlockElement::GetList(
			array("CATALOG_PRICE_1" => "ASC"), 
			array("IBLOCK_ID" => 27, "ACTIVE"=>"Y", "PROPERTY_CML2_LINK" => $secid), 
			false, 
			array("nPageSize"=>1),
			array("ID")
		);	?>
		<meta content="<?=$i?>" itemprop="offerCount">
		<?while($ar_fields = $db_price_max2->GetNext()) {?>
			<meta content="<?=$ar_fields["CATALOG_PRICE_1"]?>" itemprop="highPrice">
		<?}
		while($ar_fields2 = $db_price_min2->GetNext()){?>
			<meta content="<?=$ar_fields2["CATALOG_PRICE_1"]?>" itemprop="lowPrice">
		<?}	?>
		<meta content="RUB" itemprop="priceCurrency">
		<link itemprop="availability" href="https://schema.org/InStock">  
		</span>
	</span>
</div>