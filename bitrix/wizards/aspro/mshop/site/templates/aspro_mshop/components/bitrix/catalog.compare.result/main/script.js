BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function()
{
	var CompareClass = function(wrapObjId)
	{
		this.wrapObjId = wrapObjId;
	};

	CompareClass.prototype.MakeAjaxAction = function(url, id, iblockid)
	{
		BX.showWait(BX(this.wrapObjId));
		BX.ajax.post(
			url,
			{
				ajax_action: 'Y'
			},
			BX.proxy(function(result)
			{
				BX(this.wrapObjId).innerHTML = result;
				if(typeof id !== undefined && typeof iblockid !== undefined){
					jsAjaxUtil.InsertDataToNode(arMShopOptions['SITE_DIR'] + 'ajax/show_compare_preview_top.php', 'compare_line', false);
				}
				BX.closeWait();
			}, this)
		);
	};

	return CompareClass;
})();