<?
IncludeModuleLangFile(__FILE__);

if($APPLICATION->GetGroupRight("mod_csv")!="D")
{
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"section" => "mod_csv",
		"sort" => 200,
		"text" => "Ипорт CSV",
		"title" => "Ипорт CSV",
		"icon" => "subscribe_menu_icon",
		"page_icon" => "subscribe_page_icon",
		"items_id" => "menu_subscribe",
		"items" => array(
			array(
				"text" => "Загрузить товары",
				"url" => "import_csv_tov.php?lang=".LANGUAGE_ID,
				"more_url" => array("import_csv.php"),
				"title" => "Загрузить товары"
			),
			array(
				"text" => "Загрузить торговые предложения",
				"url" => "import_csv_torg.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "Загрузить торговые предложения"
			),
			array(
				"text" => "Загрузить свойства товаров",
				"url" => "import_csv_svoiz.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "Загрузить свойства товаров"
			),
			array(
				"text" => "Связь товаров с разделами",
				"url" => "import_csv_sviaz.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "Связь товаров с разделами"
			),
			
			array(
				"text" => "Связь товаров с фото",
				"url" => "import_csv_photo.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "Связь товаров с фото"
			),
			array(
				"text" => "Удаление элементов",
				"url" => "delet_elem_block.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "Удаление элементов"
			),
		)
	);

	return $aMenu;
}
return false;
?>