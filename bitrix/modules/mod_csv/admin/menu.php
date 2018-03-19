<?
IncludeModuleLangFile(__FILE__);

if($APPLICATION->GetGroupRight("mod_csv")!="D")
{
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"section" => "mod_csv",
		"sort" => 200,
		"text" => "����� CSV",
		"title" => "����� CSV",
		"icon" => "subscribe_menu_icon",
		"page_icon" => "subscribe_page_icon",
		"items_id" => "menu_subscribe",
		"items" => array(
			array(
				"text" => "��������� ������",
				"url" => "import_csv_tov.php?lang=".LANGUAGE_ID,
				"more_url" => array("import_csv.php"),
				"title" => "��������� ������"
			),
			array(
				"text" => "��������� �������� �����������",
				"url" => "import_csv_torg.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "��������� �������� �����������"
			),
			array(
				"text" => "��������� �������� �������",
				"url" => "import_csv_svoiz.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "��������� �������� �������"
			),
			array(
				"text" => "����� ������� � ���������",
				"url" => "import_csv_sviaz.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "����� ������� � ���������"
			),
			
			array(
				"text" => "����� ������� � ����",
				"url" => "import_csv_photo.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "����� ������� � ����"
			),
			array(
				"text" => "�������� ���������",
				"url" => "delet_elem_block.php?lang=".LANGUAGE_ID,
				"more_url" => array("subscr_edit.php"),
				"title" => "�������� ���������"
			),
		)
	);

	return $aMenu;
}
return false;
?>