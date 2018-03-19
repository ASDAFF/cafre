<?
IncludeModuleLangFile(__FILE__);

Class CWebslonPhotoframe 
{
	function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
	{
		if($GLOBALS['APPLICATION']->GetGroupRight("main") < "R")
			return;

		$MODULE_ID = 'webslon.photoframe';

		/*$menuItem = array(
			//"parent_menu" => "global_menu_services",
			//"parent_menu" => "global_menu_services",
			"parent_menu" => "webslon",
			"section" => $MODULE_ID,
			"sort" => 110,
			"text" => GetMessage("WEBSLON_CHART_MENU"),
			"title" => GetMessage("WEBSLON_CHART_MENU"),
			"url" => "settings.php?lang=ru&mid=".$MODULE_ID,
			"icon" => "fileman_menu_icon",
			"page_icon" => "fileman_menu_icon",
			//"index_icon" => "statistics_page_icon",
			"items_id" => $MODULE_ID."_items",
			"more_url" => array(),
			"items" => array()
		);

		if(isset($aGlobalMenu["webslon"]["items"])){
			$aMenu = $aGlobalMenu["webslon"]["items"];
		}else{
			$aMenu = array();
		}

		if(!isset($aMenu["visual"])){
			$aMenu["visual"] = array(
				//"parent_menu" => "global_menu_services",
				//"parent_menu" => "global_menu_services",
				"parent_menu" => "webslon",
				"section" => $MODULE_ID,
				"sort" => 110,
				"text" => GetMessage("WEBSLON_VISUAL_MENU"),
				"title" => GetMessage("WEBSLON_VISUAL_MENU"),
				//"url" => "settings.php?lang=ru&mid=webslon.sku",
				"icon" => "sale_menu_icon_orders",
				"page_icon" => "sale_menu_icon_orders",
				//"index_icon" => "statistics_page_icon",
				"items_id" => $MODULE_ID."_items",
				"more_url" => array(),
			);
		};
		
		$aMenu["shop"]["items"][] = $menuItem;
		*/
		
		if(!isset($aMenu["webslon_lead"])){
			$aMenu["webslon_lead"] = array(
				"parent_menu" => "webslon",
				"menu_id" => "webslon_lead",
				"section" => $MODULE_ID,
				"sort" => 5000,
				"text" => GetMessage("WEBSLON_MAKE_ORDER_MENU"),
				"title" => GetMessage("WEBSLON_MAKE_ORDER_MENU"),
				"url" => "http://www.web-slon.ru/contacts/",
				"icon" => "sale_menu_icon_buyers",
				"page_icon" => "sale_menu_icon_buyers",
				"items_id" => $MODULE_ID."_items",
				"more_url" => array(),
				"items" => array()
			);
		};
		if(!isset($aGlobalMenu["webslon"]))
		$aGlobalMenu["webslon"] = array(
			"menu_id" => "webslon",
            "page_icon" => "statistics_title_icon",
            "index_icon" => "statistics_page_icon",
            "text" => GetMessage("WEBSLON_MENU"),
            "title" => GetMessage("WEBSLON_MENU"),
            "icon" => "iblock_menu_icon",
            "sort" => 120,
            "items_id" => "webslon_main",
            "help_section" => "webslon_main",
            "items" => $aMenu,
        );
	}
}
?>