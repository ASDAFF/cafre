<?
$showLinkMore=\Bitrix\Main\Config\Option::get("aspro.mshop", "SHOW_MORE_ITEM_MENU_LINK", "Y", SITE_ID);
if($showLinkMore!="Y"){?>
	<script type="text/javascript">
		$('.child_wrapp .see_more').remove();
		$('.child_wrapp .d').show();
	</script>
<?}?>