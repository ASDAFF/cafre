<?
	include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
	CHTTP::SetStatus("404 Not Found");
	@define("ERROR_404","Y");
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	LocalRedirect('/');
	$APPLICATION->SetTitle("�������� �� �������");
	//$USER->Authorize(1);
?>
<style>h1,.breadcrumbs{display:none;}</style>
<table class="page_not_found" width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="image"><img src="<?=SITE_TEMPLATE_PATH?>/images/404.png" alt="404" title=":-(" /></td>
		<td class="description">
			<div class="title404">������ 404</div>
			<div class="subtitle404">�������� �� �������</div>
			<div class="descr_text404">����������� ������ ����� ��� �����<br />�������� �� ����������</div><br/>
			<a class="button big_btn" href="<?=SITE_DIR?>"><span>������� �� �������</span></a>
			<div class="back404">��� <a onclick="history.back()">��������� �����</a></div>
		</td>
	</tr>
</table>
<?$APPLICATION->IncludeComponent(
    "bitrix:main.map",
    "tz_smap_404",
    Array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "COL_NUM" => "1",
        "COMPONENT_TEMPLATE" => "tz_smap",
        "LEVEL" => "1",
        "SET_TITLE" => "N",
        "SHOW_DESCRIPTION" => "N"
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>