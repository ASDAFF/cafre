<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��������");
?>
<?global $TEMPLATE_OPTIONS;?>
<div class="wrapper_inner">
<ul class="shops">
	<li class="shops__item">
		<div class="shops__photos">
			<img src="/upload/iblock/b4a/b4a6d5a3f14dad4466db4157f9c91934.jpg" alt="">
			<img src="/upload/iblock/32a/32a0d684d2b448c3faf0a1c271f791a4.jpg"/>
		</div>
		<p>�� ������ (��������� ����� 14)</p>
	</li>
	<li class="shops__item">
		<div class="shops__photos">
			<img src="/upload/iblock/676/67667d2b13fac5a70a2e97ad7cf9cc07.jpg" alt="">
			<img src="/upload/iblock/3c9/3c9ce8489ad6bc031085b17e4f7b56d8.jpg"/>
		</div>
		<p>�� ����� (�������� ����������� 86�)</p>
	</li>
	<li class="shops__item">
		<div class="shops__photos">
			<img src="/upload/iblock/1cf/1cfec9cef48d107516e3a2f0490bcf86.jpg" alt="">
			<img src="/upload/iblock/ede/ede94d168b82720af390e85e9aefc785.jpg"/>
		</div>
		<p>�� ������� (����������� ���������� 2)</p>
	</li>
	<li class="shops__item">
		<div class="shops__photos">
			<img src="/upload/iblock/a25/a25cfa9d27e7afa9b23b926c687069a8.jpg" alt="">
			<img src="/upload/iblock/d52/d5248ec4a9014ceb4e3b9599d5169093.jpg"/>
		</div>
		<p>�� ��� (������� �������������� 1)</p>
	</li>
</ul>
</div>
<?/*if($TEMPLATE_OPTIONS["STORES_SOURCE"]["CURRENT_VALUE"] != 'IBLOCK'):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.store", 
		"main", 
		array(
			"SEF_MODE" => "Y",
			"SEF_FOLDER" => "/contacts/stores/",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"PHONE" => "Y",
			"SCHEDULE" => "Y",
			"SET_TITLE" => "Y",
			"TITLE" => "",
			"MAP_TYPE" => "1",
			"COMPONENT_TEMPLATE" => "main",
			"SEF_URL_TEMPLATES" => array(
				"liststores" => "",
				"element" => "#store_id#/",
			)
		),
		false
	);?>
<?else:?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"shops", 
	array(
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"IBLOCK_ID" => "4",
		"NEWS_COUNT" => "100",
		"USE_FILTER" => "N",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "NAME",
		"SORT_ORDER2" => "ASC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/contacts/stores/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_SHADOW" => "N",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_PANEL" => "N",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"USE_PERMISSIONS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "EMAIL",
			1 => "ADDRESS",
			2 => "MAP",
			3 => "METRO",
			4 => "SCHEDULE",
			5 => "PHONE",
			6 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "Y",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_TEXT",
			3 => "DETAIL_PICTURE",
			4 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "EMAIL",
			1 => "ADDRESS",
			2 => "MAP",
			3 => "METRO",
			4 => "SCHEDULE",
			5 => "PHONE",
			6 => "MORE_PHOTOS",
			7 => "",
		),
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "��������",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "shops",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "N",
		"SET_LAST_MODIFIED" => "N",
		"ADD_ELEMENT_CHAIN" => "Y",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "��������",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
);?>
<?endif;*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>