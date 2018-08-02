<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Магазин");
?>
<style>

@font-face {
  font-family: 'Futura';
  src: url('fonts/FuturaPT-Bold.eot');
  src: url('fonts/FuturaPT-Bold.eot?#iefix') format('embedded-opentype'),
    url('fonts/FuturaPT-Bold.woff') format('woff'),
    url('fonts/FuturaPT-Bold.ttf') format('truetype');
  font-weight: bold;
  font-style: normal;
}

@font-face {
  font-family: 'Futura';
  src: url('fonts/FuturaPT-Demi.eot');
  src: url('fonts/FuturaPT-Demi.eot?#iefix') format('embedded-opentype'),
    url('fonts/FuturaPT-Demi.woff') format('woff'),
    url('fonts/FuturaPT-Demi.ttf') format('truetype');
  font-weight: 600;
  font-style: normal;
}

@font-face {
  font-family: 'Futura';
  src: url('fonts/FuturaPT-Book.eot');
  src: url('fonts/FuturaPT-Book.eot?#iefix') format('embedded-opentype'),
    url('fonts/FuturaPT-Book.woff') format('woff'),
    url('fonts/FuturaPT-Book.ttf') format('truetype');
  font-weight: normal;
  font-style: normal;
}

body {
  font-family: 'Futura', sans-serif;
}



</style>
<?global $TEMPLATE_OPTIONS;?>
<div class="wrapper_inner">
<section class="middle">
        <div class="container">
				  <div class="shop-page">

            <h3>
              <strong>Любите совершать покупки,</strong> рассматривая витрины и бродя по магазинам?<br>
              <strong>Тогда вам к нам!</strong> В нашем магазине вы найдёте:
            </h3>

            <div class="shop-page__list">
              <ul>
                <li>самые популярные косметические бренды: Wella Professional, Londa Professional, Estel Professional, Schwarzkopf Professional, Dewal Professional, Teana laboratories, Planet Nails, Ga.Ma Professional, Aravia Professional, CND и многие, многие другие!</li>
                <li>шикарные новинки профессиональной косметики по доступной цене! «Вкусные» цены, полностью аналогичные стоимости товаров на сайте. Никакой наценки!</li>
                <li>соблазнительные акции интернет-магазина и всегда вежливый персонал!</li>
              </ul>
            </div>

            <div class="alrt">
              <h3>Совершайте покупки с удовольствием вместе с «CAFRE»</h3>
            </div>

            <div class="shop-page__pics">
              <a href="/img/img1.png" class="fancy">
                <img src="/img/img1.png" alt="">
              </a>
              <a href="/img/img2.png" class="fancy">
                <img src="/img/img2.png" alt="">
              </a>
              <a href="/img/img3.png" class="fancy">
                <img src="/img/img3.png" alt="">
              </a>
            </div>

            <div class="shop-page__contact">
              <div class="shop-page__toggle">
                <div class="tgl">
                  <span class="tgl__name">Адрес магазина</span>
                  <div class="tgl__content">
                    <p>г. Москва, ТЦ Афимолл, Пресненская набережная, 2</p>
                  </div>
                </div>
                <div class="tgl">
                  <span class="tgl__name">Часы работы</span>
                  <div class="tgl__content">
				  <p>
				  Пн. - Вс.: с 9:00 до 21:00
				  </p>
				  </div>
                </div>
                <div class="tgl">
                  <span class="tgl__name">Акции</span>
                  <div class="tgl__content"></div>
                </div>
                <div class="tgl">
                  <span class="tgl__name">Подарочные сертификаты</span>
                  <div class="tgl__content"></div>
                </div>
                <div class="tgl">
                  <span class="tgl__name">Клубная карта</span>
                  <div class="tgl__content"></div>
                </div>
              </div>
              <div class="shop-page__map">
				<?$APPLICATION->IncludeComponent(
					"bitrix:map.google.view",
					"map",
					array(
						"API_KEY" => "AIzaSyDcRvC__j3tSJLDcKYiPbvg4z75quenpaA",
						"INIT_MAP_TYPE" => "ROADMAP",
						"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:55.75567215872764;s:10:\"google_lon\";d:37.60761724722134;s:12:\"google_scale\";i:18;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:0:\"\";s:3:\"LON\";d:37.60764956474327;s:3:\"LAT\";d:55.75567424904235;}}}",
						"MAP_WIDTH" => "100%",
						"MAP_HEIGHT" => "240",
						"CONTROLS" => array(
						),
						"OPTIONS" => array(
							0 => "ENABLE_DBLCLICK_ZOOM",
							1 => "ENABLE_DRAGGING",
						),
						"MAP_ID" => "AIzaSyDcRvC__j3tSJLDcKYiPbvg4z75quenpaA",
						"ZOOM_BLOCK" => array(
							"POSITION" => "right center",
						),
						"COMPONENT_TEMPLATE" => "map"
					),
					false
				);?>
              </div>
            </div>

          </div>
				</div>
      </section>
      

	  </div>
<?/*
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
