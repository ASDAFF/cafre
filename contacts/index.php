<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��������");
?>
<div class="wrapper_inner">
	<div class="contacts_left clearfix">
		<div class="store_description">
			<div class="store_property">
				<div class="title">�����</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/address.php", Array(), Array("MODE" => "html", "NAME" => "�����"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">�������</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => "�������"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">Email</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?>
				</div>
			</div>
			<div class="store_property">
				<div class="title">����� ������</div>
				<div class="value">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/schedule.php", Array(), Array("MODE" => "html", "NAME" => "����� ������"));?>
				</div>
			</div>
		<p>���������:</p>
		<div class="store_property"><div class="value">������ ������������: ��� "�����"</div></div>
		<div class="store_property"><div class="value">��./���. �����: 432071, �. ���������, �. 39, ���� 95</div></div>
		<div class="store_property"><div class="value">�������: 8(800) 775-38-78</div></div>
		<div class="store_property"><div class="value">�����: ���������, �. 39, ���� 95</div></div>
		<div class="store_property"><div class="value">�-mail: info@cafre.ru</div></div>
		<div class="store_property"><div class="value">���: 7325153965</div></div>
		<div class="store_property"><div class="value">����: 1177325008530</div></div>
		<div class="store_property"><div class="value">���: 732501001</div></div>
		</div>
	</div>
	<div class="contacts_right clearfix">
		<blockquote><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("CONTACTS_TEXT")));?></blockquote>
		<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-feedback-block");?>
		<?$APPLICATION->IncludeComponent("bitrix:form.result.new", "inline",
			Array(
				"WEB_FORM_ID" => "3",
				"IGNORE_CUSTOM_TEMPLATE" => "N",
				"USE_EXTENDED_ERRORS" => "Y",
				"SEF_MODE" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
				"LIST_URL" => "",
				"EDIT_URL" => "",
				"SUCCESS_URL" => "?send=ok",
				"CHAIN_ITEM_TEXT" => "",
				"CHAIN_ITEM_LINK" => "",
				"VARIABLE_ALIASES" => Array(
					"WEB_FORM_ID" => "WEB_FORM_ID",
					"RESULT_ID" => "RESULT_ID"
				)
			)
		);?>
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-feedback-block", "");?>
	</div>
</div>
<div class="clearboth"></div>
<div class="contacts_map">
	<?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view", 
	"map", 
	array(
		"API_KEY" => "AIzaSyDcRvC__j3tSJLDcKYiPbvg4z75quenpaA",
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:55.75567215872764;s:10:\"google_lon\";d:37.60761724722134;s:12:\"google_scale\";i:18;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:0:\"\";s:3:\"LON\";d:37.60764956474327;s:3:\"LAT\";d:55.75567424904235;}}}",
		"MAP_WIDTH" => "100%",
		"MAP_HEIGHT" => "400",
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
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>