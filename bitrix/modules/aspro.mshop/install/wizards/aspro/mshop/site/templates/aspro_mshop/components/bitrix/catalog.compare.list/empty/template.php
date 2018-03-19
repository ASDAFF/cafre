<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!CModule::IncludeModule("iblock")||!CModule::IncludeModule("catalog")) break;?>
<?
	if(count($arResult) > 0)	
	{
		global $compare_items;
		foreach($arResult as $key=>$arItem){ $compare_items[] = $key; }		
	}
?>

<?=json_encode(array("COMPARE_COUNT"=>count($compare_items), "ITEMS"=>$compare_items));?>