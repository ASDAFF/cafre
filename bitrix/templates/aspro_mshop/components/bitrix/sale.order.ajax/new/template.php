<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N"){
	if(!empty($arResult["ERROR"])){
		foreach($arResult["ERROR"] as $v) {
			echo ShowError($v);
		}
	}
	elseif(!empty($arResult["OK_MESSAGE"])){
		if (count($arResult["OK_MESSAGE"])) {
			echo '<h2>';
		}
		foreach($arResult["OK_MESSAGE"] as $v) {
			echo ShowNote($v);
		}
		if (count($arResult["OK_MESSAGE"])) {
			echo '</h2>';
		}
	}
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	return;
}
elseif(!$_REQUEST["ORDER_ID"]){
	// check min order price
	$price=0;
	foreach($arResult["BASKET_ITEMS"] as $arItem){
		if($arItem["CAN_BUY"]=="Y" && $arItem["DELAY"]=="N"){
			$price += ($arItem["PRICE"]*$arItem["QUANTITY"]);
			$currency = $arItem["CURRENCY"];
		}
	}
	$arError = CMshop::checkAllowDelivery($price,$currency);
	if($arError["ERROR"]){
		LocalRedirect($arParams["PATH_TO_BASKET"]);
	}
}
?>
<?if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			$APPLICATION->RestartBuffer();
			?>
			<script type="text/javascript">
				window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}

	}
}

//$APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));
?>

<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
if (!function_exists("getColumnName"))
{
	function getColumnName($arHeader)
	{
		return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
	}
}

if (!function_exists("cmpBySort"))
{
	function cmpBySort($array1, $array2)
	{
		if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
			return -1;

		if ($array1["SORT"] > $array2["SORT"])
			return 1;

		if ($array1["SORT"] < $array2["SORT"])
			return -1;

		if ($array1["SORT"] == $array2["SORT"])
			return 0;
	}
}
?>
<script>
$.cookie("checked","N");

function InitOrderJS(){
	try{
		$(document).ready(function(){
			if(arMShopOptions['THEME']['PHONE_MASK'].length){
				var base_mask = arMShopOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
				$('input.phone').inputmask('mask', {'mask': arMShopOptions['THEME']['PHONE_MASK'] });
				$('form[name="ORDER_FORM"] input.phone').blur(function(){
					if( $(this).val() == base_mask || $(this).val() == '' ){
						if( $(this).hasClass('required') ){
							$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
						}
					}
				});
			}
		});
	}
	catch(e){}
}
</script>
<div class="bx_order_make">
	<?
	unset($_COOKIE["checked"]);
	echo $_SESSION["checked"];
	if(!$USER->IsAuthorized() )
	{
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	}

		if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
		{
			if(strlen($arResult["REDIRECT_URL"]) == 0)
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
			}
		}
		else
		{
			?>
			<script type="text/javascript">
			InitOrderJS();

			<?if(CSaleLocation::isLocationProEnabled()):?>

				<?
				// spike: for children of cities we place this prompt
				$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
				?>

				BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
					'source' => $this->__component->getPath().'/get.php',
					'cityTypeId' => intval($city['ID']),
					'messages' => array(
						'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
						'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
						'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
							'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
							'#ANCHOR_END#' => '</a>'
						)).'</div>'
					)
				))?>);

			<?endif?>

			var BXFormPosting = false;
			function submitForm(val)
			{
				if (BXFormPosting === true)
					return true;
				if($(document).find("form[name=order_auth_form]").length>0) {
					$(document).find("form[name=order_auth_form]").next("#error").remove();
					$.ajax({
						data: {label: 'findEmail', email: $(document).find('[code=EMAIL]').val()},
						type: "POST",
						url: "/ajax/auth_order.php",
						success: function(e) {
							if(e!='no') {
								$(document).find("form[name=order_auth_form]").after('<p id="error">�������������, ����� email ��� ����������</p>');								
								$(document).find("form[name=order_auth_form] [name=MY_LOGIN]").val($(document).find('[code=EMAIL]').val());
								var scrollTop = $(document).find('form[name=order_auth_form]').offset().top;
								$('html, body').stop().animate({
									scrollTop: scrollTop
								}, 500); 
							}
							else {
								BXFormPosting = true;
								if(val != 'Y')
									BX('confirmorder').value = 'N';
								var orderForm = BX('ORDER_FORM');
								BX.showWait();
								<?if(CSaleLocation::isLocationProEnabled()):?>
									BX.saleOrderAjax.cleanUp();
								<?endif?>
								BX.ajax.submit(orderForm, ajaxResult);
							}
						}
					});
				}
				else {
					BXFormPosting = true;
						if(val != 'Y')
							BX('confirmorder').value = 'N';
						var orderForm = BX('ORDER_FORM');
						BX.showWait();
						<?if(CSaleLocation::isLocationProEnabled()):?>
							BX.saleOrderAjax.cleanUp();
						<?endif?>
						BX.ajax.submit(orderForm, ajaxResult);
				}
				return true;
			}

			function ajaxResult(res)
			{
				var orderForm = BX('ORDER_FORM');
				try
				{
					// if json came, it obviously a successfull order submit

					var json = JSON.parse(res);
					BX.closeWait();

					if (json.error)
					{
						BXFormPosting = false;
						return;
					}
					else if (json.redirect)
					{
						window.top.location.href = json.redirect;
					}
				}
				catch (e)
				{
					// json parse failed, so it is a simple chunk of html

					BXFormPosting = false;
					BX('order_form_content').innerHTML = res;

					<?if(CSaleLocation::isLocationProEnabled()):?>
						BX.saleOrderAjax.initDeferredControl();
					<?endif?>
				}

				BX.closeWait();
				BX.onCustomEvent(orderForm, 'onAjaxSuccess');
			}

			function SetContact(profileId)
			{
				BX("profile_change").value = "Y";
				submitForm();
			}

			BX.addCustomEvent('onAjaxSuccess', function(){
			   InitOrderJS();
			});

			</script>
			<?if($_POST["is_ajax_post"] != "Y")
			{
				?><form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
				<?=bitrix_sessid_post()?>
				<div id="order_form_content">
				<?
			}
			else
			{
				$APPLICATION->RestartBuffer();
			}

			if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
			{
				?>
				<input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
				<?
			}

			if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
			{
				foreach($arResult["ERROR"] as $v)
					echo ShowError($v);
				?>
				<script type="text/javascript">
					top.BX.scrollToNode(top.BX('ORDER_FORM'));
				</script>
				<?
			}

			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");?>

			
			<div class="wrap_md">
				<?if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d"){?>
					<div class="l_block iblock">
						<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
					</div>
					<div class="r_block iblock">
						<?if($info = CModule::CreateModuleObject('sale')){
							$testVersion = '15.0.0';
							if(CheckVersion($testVersion, $info->MODULE_VERSION)){
								include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
							}
							else{
								include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery_new.php");
							}
						}
						?>
					</div>
				<?}else{?>
					<div class="l_block iblock">
						<?if($info = CModule::CreateModuleObject('sale')){
							$testVersion = '15.0.0';
							if(CheckVersion($testVersion, $info->MODULE_VERSION)){
								include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
							}
							else{
								include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery_new.php");
							}
						}
						?>
					</div>
					<div class="r_block iblock">
						<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
					</div>
				<?}?>
			</div>
			
			
			
			
			<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");
		
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
			if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
				echo $arResult["PREPAY_ADIT_FIELDS"];
			?>

			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>
					</div>
					<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
					<input type="hidden" name="profile_change" id="profile_change" value="N">
					<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
					<input type="hidden" name="json" value="Y">
					<div class="bx_ordercart_order_pay_center"><a href="javascript:;" id="ORDER_CONFIRM_BUTTON" onclick="submitForm('Y'); return false;" class="checkout button big_btn"><span><?=GetMessage("SOA_TEMPL_BUTTON")?></span></a></div>
<div class="chek_politik">
			<input type="checkbox" checked/>
<div class="tex_check_politik2">
	�������� ������� <a href="/politik/" target="_blank">�������� �� ��������� ������������ ������</a>
	</div>
	<br/><br/>
<input type="checkbox" checked/>
<div class="tex_check_politik2">
	� ��������� <a href="#" class="agreement-drop">������ ���������� � ��������</a>
	</div>
	<div class="agreement-info">
	<h2>��������� ������</h2>
	<p>
	1. ������� � �����������, ������������ � ��������� ������.<br />
� ��������� ������, ���� �� ��������� �� ������� ����, ��������������� ������� ����� ��������� �������� � �������� � ��������� ������������ ������:<br />
���������� � �������� � ������������ ���������������� �����Ż (��� 7325153965), �������������� ������������������� ������������ �� ������� ������ ������������� �������� ��� �������� ������ �CAFRE�.<br />
������� � ��������� ��������, ���������� ��������� ������������ ��������, ������������ ������ ����������� ����, � ���������� �������� ��������� �����-������� ������ (����� - ��������) �� ��������, ������������ � ������, ������� ��� � ����������.<br />
������������ � ����� ���������� ����, ��������� (���������������) ��������� ������ �� ������������� ��������.<br />
������������������� ����������� � ����������, �������������� �������� ���� ������������ ������ ����������� ����������� �� �����, ������� ����� ���� ������������ ��������� ��� ���������� ������ ����������.<br />
����� � ������������ ����������� ����������, ��������� ��� ��������� ������������ � ���� �������� � ������ www.cafre.ru.<br />
���������-������� � ��������-������� �� ������� ����������-������������� �������, ����������� �� �����.<br />
������ � ������ �����-�������, �� ������� � �� ������������ � ����������� ������� � ������������ � ������� ����������� ���������� � ��������������� ������� ��������-��������.<br />
������ � ����������� ����������� � ��������-�������� ������ �� ������� � �������� �������, ��������� ����������� � ��������-��������, � ��������������� �������� ����������� ���� �������� (����������� �����, ����������� �� �����) �/��� ����������� ����������� �� ��������.<br />
����������� ������ � ������ ����, ������������ ���������, �������������� �������� ������ ����������, ���� �������������� �� ����� � �� ���� �������� ��������� ������� � ����������� ��� ����������.<br />
������� ������ � �������������� �������� ����������� ������� ������.
	<br /><br />
	2. ����� ���������<br />
� ������������ �� ������� 437 ������������ ������� ���������� ��������� ������ �������� �������� ��������� �������, ������������ ���������� �����, � � ������ �������� ���������� ���� �������, ���������� ���� ��������� ������� � ���������� ������ ������ � ��� �������� �� ��������, ���������� � ��������� ������. � ������������ � ������� 3 ������ 438 �� ��, ���� ���������� ������ ����������� �������� �������� ������ ��������, ��� �������� ������������ ���������� �������� �����-������� ������ �� ��������, ������������� � ��������� ������ � �� �����.
�������� � ���������� �����������, ��� �������� ����������� �����- � ���������������, � ����� ����� ������� � ������������, ������������ � ������������ ��� ���������� � ���������� �������� ��������� �����-������� ������.
��������� ������ ����� ��������-�������, ���������� ������������� ��������� ������� ��������� ������, � ����� �������, ��������� �� �����. 
� ���������� ����� ����������� � ��������� ����������� ��������� ������������ ������� �� (��������� �����-�������, ����� �� �� ������ ���� ������������, ������������� ������������� �� �� 27.09.2007 �. �612 ��� ����������� ������ ������� ������� ������������� �������� � ���� ��������� ������������ ���������������� ��.
�������� ��������� �� ����� ����� ������� ��������� � ��������� ������, � ����� � ��� ���������� ��������� �������������� �������������� ������� ��������� � ������, ����������� �� �����. 
<br /><br />
3. ������� ��������<br />
�������� ��������� �������� � ������������� ���������� ��� ������� ������������� ������������� �������� (����� �� ������ �������� - �����) �� ��������������� �������, ���������� � ��������, � ���������� ��������� ������� ���� ����� � �������� �� ��������, ��������������� ��������� ���������.
<br /><br />
	4. ����� � ����������� ������<br />
�������� ���������:<br />
� ������� ���������� ���������� �������� ���������� ���������� ����� ������������ ����� ����������� �� ��������, ������������� ��������� ������� � � ������������ � ������������ ������������ ���������������� ��. <br />
�������� (����������) ��������� ������, ���������� ������������ ���� �������� � ��������� �������� � ������������ ���������������� �����Ż (����� - ��������) ������������ ���� ������������ ������, � ��� �����: �������, ���, ��������; ����� ��������, ������� ��� ����� � �����������; ����� ����������� �����; ����� ����������.<br />
��� ���������� ������������ ������ � ��������� ������ ����������: ���� ������������� ������; �� ��������������; ����������; ��������; ��������� (����������, ���������); �������������, ��������������� (� ��� ����� �������� �� ���������� ���������� ��������� � �������������� ��������); �������������; ������������; �����������.<br />
�������� ����� ����� �� �������� ������������ ������ ���������� ������������ ��������� (���������� �������) � ����� �������� ���������� ���������� ��������� �������.<br />
���������� �������� �������� � ��������� ��������� � ��� ������������ ������������ ������������ ������ ���������� � ������� ������������������ ������ ���������� ������ ������, � ����� ���� ����������� � ����������� �������.
�������� ������ �������������� ���������� ������������ ������� ��������� ������������ ������ ����������.<br />
���������� ����������� � ���, ��� ��� ������������ ������, ���������� ����������, ����� ���� �������� ������� ����� � �����, ��������� � ��������� ������, ��� ��� ���������� ������������ �������� �� ����������� � ����������� ������ � ��������� ������.
���������� ����� ������������� ��������� � ������� ����� ����� ������������ � ������������ ���� ������������ ������ � ����� ���������� ������������, ������������ �� ��������� �������� ��������������� ����� � �������, � ��� ����� ��� ���������� ������������� �������� � ������������, �������������� ������������, �������� ��������� ���������� ���������.<br />
���������� ������������ ���� �������� � ���, ��� �������� ��� �������������� �� ���� ������ ����������������� � ����������� ����� ������������� ������ ��������� � ����������� � ������� ��������� ������� �����, �������, ��, �� �������������: �������� ��������, sms-��������, ����������� �����, �������, ���� �������� � ��., ��� ������� ���������� ������ �������� ������ ������������ ���������������� �� � ����� ������ ������������ ������.<br />
��� �������� ������������ ������ ���������� ������� ����� �������� ������������� ���, ���������� ������������ ������ ����������, � ���, ��� ��� ������ �������� ����������������� � ����� ���� ������������ ���� � �����, ��� ������� ��� ��������, � ������� �� ����� ������� ��� ���������� ����� �������.<br />
�������� ������������ ������������������ ��������������� ����������� ������������ ������, �� ������ �� �������������������� �������, �����������, ���������������. � ����� ������ ���������� ������ ��������� �������� ����� ������������ ������ �/��� ����������� ��������, ���������� ���� ������������ ������, �������� ��������� �� �������� ��� �������� ��������� �� ����������� �����, ������ ���, ������� � ����� ��������.
<br /><br />
�������� ����� �����:<br />
�������� ������� ��������� ������; ���� �� �����, ��������� � ��������-��������; ������� ������ ������; ������� � ����� �������� ������; � ����� ���� �������, ��������� � ��������� ������ ��� � ��������-��������.<br />
��� ������������ � ����������� ���������� ���� ����� � ����������� �� ����������� � ����������� ������ (��������) ������� �����.
<br /><br />
���������� ���������:<br />
�� ������� ���������� ������ �� ����� � ������������ � ����������� � ���������, �������������� � ��������� ������, � ����� � ����� ���������, ���������� �� �����, � ��� ����� � ������ �� �����, �������������� � ��������-��������.<br />
�� ���������� ��������� ����� ������������ ����� ����������� ��������� ������ �������� ���� ������������ ������, ����������� ��� ������������� ���������� � ����������� ��� ���������� ������ � ��������� � �������� ���������� ����������� �� ������.<br />
�������� ���������� ����� � ��� �������� �� �������� ��������� ������.<br />
��������� �������, ������������� � ��������� ������, � ����� ���� �������, ��������� �� �����.
<br /><br />
5. ������� ���������� ������<br />
����� ���������� ����� ���� �������� ����������� ���������� ����������� ����� ������ �� �����. ��� ���������� ������ ����������� ����������� ����� �� �����, ���������� ��� ����� ������������, ��� �� ���������� � ��������� ������� ������� ����� ��������-�������, ���������� �� ����� � � ��������� ������, � ��������� ������������ �������� ��� ����������, ����������� ��� ����������� ���������� � ���������� ������.<br />
���� �� ������ �������� ����������� ����������� ���������� ��� ����������� ����������� ����������� ������, �������� ����������� �� ���� ���������� �� �������� ��� ����� �������� ��������� �� ��������� ����������� ����� ����������� �����, � ������� 24 (�������� �������) ����� ����� ��������� ������ �� ����������. ���������� ������ ����������� ������� ����� � ���� ���������� ��� ������������, ���� ������������ ���� �����. � ������ ����������� ������ ���������� � ������� 24 (�������� �������) ����� � ������� ����������� ���������� ���������, �������� ������ ������������ ����� ���������� � ������ ������.<br />
� ������ ������������� � ���������� ��������, ���������� ������� � ������������� ������, ����� ����������� ������, ���������� ������ ���������� � �������� �� �������� ��� ����������� ����� ��� ��������� ����������� ����������.
<br /><br />
6. ������� ��������<br />
�������� ������ �� ������ ������� �������������� ���������� ������� ��� ������ ������. � �������� �������� � ���������� �� ������ ���������� ����� ������������ � ��������������� ������� ����� ��������� � ������ (https://estel.m-cosmetica.ru/pages/shipping).
���������� ������ � ����� ������ ������� ���������� �� ������. � ������ ���� �����������, ������������ �� ������ ������, �������������� ���� �������� ��������� �������� ������, ����� ������� ���������� � ����� ������ ��������� �� �����������.<br />
� ������ ���� ����� �� ��� ������� ���������� �� ���� ����������, ��������� �������� ����� ���� ����������� ��� ������� ������������ ��������� � ����������� ����� ������ �������� � ��� ������� ������ ����������� ��������� ��������� �������� ������.
��� ��������� ������ ���������� ��������� ������������ ������������� ������ ������, ������������� � ���������� ��������� � �������� ���� ������������� ������. ������� ������ �������������� �������� ���������� �� ������ ���������. ������� ������ ��� ��������� ������ ���������� ����� ��������� �� ��������������� ������, ������� ����� ������� ����������� ������ (����� ���������������� ��������), �������������� ���������� ������������� ������ ������ ��� ����������������� ���������.
<br /><br />
7. ������� ������<br />
���� �� ����� ������������ ��������� � ������������� ������� � ����������� �� ����� � ���������� ������. ���� �� �����, ��������� �� ����� ����� ���������� �� ��� �� ����� ��������� � ��������� ������� � ��������� ��������� ���� �CAFRE�. ���� ������ ����� ���� �������� ��������� � ������������� �������. ��� ���� ���� �� ���������� ����������� ����� ��������� �� ��������.<br />
���������� ����� �������� ������ ��� �����, ������� ���� � ������� �� ������ � ������ ���������� ������.<br />
 ������ ������ ����������� ������������ � ������ ����� �� ����������������� ��������:<br />
�	������������� �� ��������� ���� ��������;<br />
�	��������� ������� ��� �������� �� ����� ����������.
<br /><br />
8. ������� ������<br />
���������� ������ ���������� �� ������ ����������� �������� � ����� ����� �� ��� ��������, � ����� �������� ������ � � ������� 7 (����) ����������� ���� � ������� ������� ������ ����������� ��� ���������� ��������� �������:<br />
�	�������� �������� ��� (������������ ��������, ��������� ������, ������);<br />
�	��������� ��������������� �������� ������;<br />
�	����� �� ����� ������ ������������;<br />
�	������� ���������� �� �����, �������������� ���� ������� ������������� ������ (�������� ��� �������� ���).<br />
��� �������� ������ ����������� �������� ���������� ������������ ��������� ������, �� ����������� ��������� ��������. ������� ���������� �� �������� �������� ������ ����������� �������� ��������� �� �����������. ���� �������� �������� ������� � 10 (������) ������� ���� �� ��� �������� �������� ������������� ������ � �������������� ����������� ���������������� ����������� ����������. �������� �������� ������������ ������� �� ��� ��������� ����, ��������� � ��������� �� ������. 
������� ������ �������� �������������� ����� �������� ������ �� ������: 432071, �.���������, ��.��������, 39, ���� 95.<br />
�������� ������ ������������� �������� � ��� ������� �������������� � ������������ � ����������� ������������ ���������������� ��.<br />
�������� ����������, �������������� �������� ������ � ������������ �������:<br />
1.	��������� �� ������� ������.<br />
2.	�������� �� �����, �������������� ���� ������� ������������� ������ � �������� (�������� ��� �������� ���).<br />
3.	����� ���������, ��������������� �������� (������� ���������� ��/�������������, ��������� ������������� �������� ���������� ��, ���������� �� ������ ���������� ��������, ������� �����, ������������ �����).<br />
<br /><br />
9. ����������� ������������� ������<br />
����������� ���� �� �����, ��������������� � ������������ � ����������� ����������������� ��.
<br /><br /> 
10. ��������������� ������<br />
����� �� ������ ������������� �� ��������������� �� ������ ��� ��������� ������������ ����� ������������ �� ���������� ��������, ���� ��� ������������ ���� ������� ���������������� ������������� ����. <br />
�� ������������ ��� ������������ ���������� ������� ���������� �������� (������������� ����������� ������ ��������) ������� ����� ��������������� � ������������ � ����������������� ���������� ���������.<br />
 ��� �����, ��������� � ������������� ��� ������������ ����������� ����� ������������ �� ���������� ��������, ������� ����� ������ ����� �����������. ������������� ������� ���������� ��� ������ ���������� 30 ����������� ����.
� ������ �� ���������� �������� � ���� �����������, ����� ����� ����������� � �������� ������� � ������������ � ����������� ����������������� ���������� ���������.
	</p>
	</div>
	<script>
jQuery(function($){
    $(".phone").mask("8(999) 999-9999");
	$(".agreement-drop").click(function(e){
		e.preventDefault();
        var t = $(this).parents('.chek_politik').find('.agreement-info');
        t.stop().slideToggle(200);
	});
});
</script>
			</div> 
				</form>
				<?
				if($arParams["DELIVERY_NO_AJAX"] == "N")
				{
					?>
					<div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
					<?
				}
			}
			else
			{
				?>
				<script type="text/javascript">
					top.BX('confirmorder').value = 'Y';
					top.BX('profile_change').value = 'N';
				</script>
				<?
				die();
			}
		}
	
	?>
	</div>
</div>
<script>
	$('body').addClass('order_page');
</script>
<?if(CSaleLocation::isLocationProEnabled()):?>

	<div style="display: none">
		<?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.steps", 
			".default", 
			array(
			),
			false
		);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.search", 
			".default", 
			array(
			),
			false
		);?>
	</div>

<?endif?>