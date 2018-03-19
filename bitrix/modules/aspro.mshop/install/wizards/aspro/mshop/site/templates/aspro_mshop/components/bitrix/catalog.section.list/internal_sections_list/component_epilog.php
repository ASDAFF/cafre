<?global $MShopSectionID;?>
<script type="text/javascript">
$(document).ready(function() {
	var MShopSectionID = '<?=($MShopSectionID ? $MShopSectionID : 0)?>';
	$('.internal_sections_list .cur').removeClass('cur');
	$('*[data-id="'+ MShopSectionID + '"]').addClass('cur').parents('.child_container').parent().addClass('cur');
	$('*[data-id="'+ MShopSectionID + '"]').parent().find('.menu_title').addClass('cur');
});
</script>