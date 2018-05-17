<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Магазины");
?>
<?global $TEMPLATE_OPTIONS;?>
<div class="wrapper_inner">
<ul class="shops">
	<li class="shops__item">
		<div class="shops__photos">
		<img src="/upload/кафре-авеню/кафре-афимол/гудзон/photo_2018-05-16_17-43-46.jpg" alt="">
			<img src="/upload/кафре-авеню/кафре-афимол/гудзон/photo_2018-05-16_17-44-01.jpg" alt="">
			<img src="/upload/кафре-авеню/кафре-афимол/гудзон/photo_2018-05-16_17-44-07.jpg"/>
		</div>
		<p>ТЦ Гудзон (Каширское шоссе 14)</p>
	</li>
	<li class="shops__item">
		<div class="shops__photos">
			<img src="/upload/кафре-авеню/photo_2018-05-16_17-33-55.jpg" alt="">
			<img src="/upload/кафре-авеню/photo_2018-05-16_17-42-58.jpg"/>
			<img src="/upload/кафре-авеню/photo_2018-05-16_17-43-02.jpg"/>
		</div>
		<p>ТЦ Авеню (проспект Вернадского 86А)</p>
	</li>
	<li class="shops__item">
		<div class="shops__photos">
			<img src="/upload/кафре-авеню/кафре-афимол/photo_2018-05-16_17-43-12.jpg" alt="">
			<img src="/upload/кафре-авеню/кафре-афимол/photo_2018-05-16_17-43-31.jpg" alt="">
			<img src="/upload/кафре-авеню/кафре-афимол/photo_2018-05-16_17-43-37.jpg"/>
		</div>
		<p>ТЦ Афимолл (Пресненская набережная 2)</p>
	</li>
	<li class="shops__item">
		<div class="shops__photos">
		<img src="/upload/кафре-авеню/кафре-афимол/гудзон/рио/photo_2018-05-16_17-44-16.jpg" alt="">
			<img src="/upload/кафре-авеню/кафре-афимол/гудзон/рио/photo_2018-05-16_17-44-26.jpg" alt="">
			<img src="/upload/кафре-авеню/кафре-афимол/гудзон/рио/photo_2018-05-16_17-44-30.jpg"/>
		</div>
		<p>ТЦ Рио (Большая Черемушкинская 1)</p>
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
		"PAGER_TITLE" => "Магазины",
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
		"DETAIL_PAGER_TITLE" => "Страница",
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