<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
/*if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N"){
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
else*/if(!$_REQUEST["ORDER_ID"]){
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
<?/*if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{}*/
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


//$APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));

?>
<a name="order_form"></a>
<div class="order">

<!--order-checkout-->
<div id="order_form_div">
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
<!--<div class="bx_order_make">-->
	<?
	unset($_COOKIE["checked"]);
	echo $_SESSION["checked"];



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
				console.log(BXFormPosting+'bxBXFormPosting');

				if (BXFormPosting === true)
					return true;
				if($(document).find("form[name=order_auth_form]").length>0) {
					$(document).find("form[name=order_auth_form]").next("#error").remove();
					$.ajax({
						data: {label: 'findEmail', email: $(document).find('[code=EMAIL]').val()},
						type: "POST",
						url: "/ajax/auth_order.php",
						success: function(e) {
							console.log(e);
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
								if(val == 'Y' && ($('.order__cell').find('#name1').val() == '') && ($('.order__cell').find('#mail1').val() == '') && ($('.order__cell').find('#phone1').val() == '')){
									$('.errors').each(function(i, e){
										$(e).css('display', 'none');
									});
									$('.errors').parent().find('#order_form_content').css('padding-top', '15px');
									$('#order_form_content').before('<p class="errors"><font class="errortext">��� ����������� ��� ����������</font></p><p class="errors"><font class="errortext">E-Mail ����������� ��� ����������</font></p><p class="errors"><font class="errortext">������� ����������� ��� ����������</font></p>');
									BX('confirmorder').value = 'N';
								}
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
			?>
			<div style="display:none;">
			<?
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
	?>
</div>

			<div class="wrap_md">
				<?if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d"){?>
					<div class="l_block iblock">
						<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
					</div>
				<?}else{?>
					<div class="r_block iblock">
						<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");?>
					</div>
				<?}?>
			</div>




			<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");
		?>
		<!--<div class="order__next">
		<a href="javascript:;" id="ORDER_CONFIRM_BUTTON" onclick="submitForm('Y'); return false;" class="checkout button big_btn"><span><?=GetMessage("SOA_TEMPL_BUTTON")?></span>

						<div class="order__message order__message_next">
							<div class="pinkgirl">
								<img src="<?//=SITE_TEMPLATE_PATH?>/pinkgirl/pinkgirl3.png" alt="">
							</div>
							<p>� ������ ������ ��� �� ��� ������</p>
						</div>
						</a>
					</div>-->


		<?

			if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
				echo $arResult["PREPAY_ADIT_FIELDS"];
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");?>
			</div>
			<?
			if($_POST["is_ajax_post"] != "Y")
			{

				?>
					</div>

					<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
					<input type="hidden" name="profile_change" id="profile_change" value="N">
					<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
					<input type="hidden" name="json" value="Y">
<?if(!$_GET["ORDER_ID"]){
$prc_s = \Bitrix\Sale\Discount::getApplyResult(
 true
);
?>

<div class="order__delcoup">
	<div class="delivery_order">
	<span class="title_coup">�������� ������ ��������:</span>
	<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery_new.php");			?>
	</div>


	<label class="block_coup">
				<span class="title_coup">� ���� ���� ��������:</span>
				<div class="block_coup-wrap">
					<input type="text" name="coupon" class="coup_inp" value="<?=key($prc_s["COUPON_LIST"]);?>"/>
					<button class="button medium">���������</button>
				</div>
				<span class="name_coup"></span>
	</label>
</div>




<?
/*if($USER->IsAuthorized())
	{
	?>
<label class="block_ball">
			<!--<span class="title_ball">������ � �������:-->
								<?
								$numb = 0;
								$arSelect = Array("ID", "NAME", "PROPERTY_ATT_BONUS", "PROPERTY_ATT_USER");
								$arFilter = Array("IBLOCK_ID"=>32, "ACTIVE"=>"Y", "PROPERTY_ATT_USER_VALUE"=>$GLOBALS['USER']->GetID());
								$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
								//$arrp = array();
								if($ob = $res->GetNextElement())
								{
									$arFields = $ob->GetFields();
									if($arFields["PROPERTY_ATT_USER_VALUE"] == $GLOBALS['USER']->GetID()){
										//echo ($arFields["PROPERTY_ATT_BONUS_VALUE"]?$arFields["PROPERTY_ATT_BONUS_VALUE"]:'0');
										$numb = ($arFields["PROPERTY_ATT_BONUS_VALUE"]?$arFields["PROPERTY_ATT_BONUS_VALUE"]:0);
									}else{
										//echo '0';
									}
								}?>
			<span class="title_ball">��������� �����:</span>
			<br/>
			<input type="number" min="0" max="<?=$numb;?>" name="balls" id="balls" class="ball_inp" placeholder="������ � �������: <?=$numb;?>"  onkeyup="isright(this);"/>
</label>
	<?}*/?>
<?
}?>

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

			</div>

					<div class="order__next order__next_fin" style="display:none;">
						<a href="javascript:;" id="ORDER_CONFIRM_BUTTON" onclick="submitForm('Y'); return false;" class="checkout button big_btn clickd"><span><?=GetMessage("SOA_TEMPL_BUTTON")?></span>
						<div class="order__message order__message_next">
							<div class="pinkgirl">
								<img src="<?=SITE_TEMPLATE_PATH?>/pinkgirl/pinkgirl5.png" alt="">
							</div>
							<p>������ ����� ���� �������� ��� ������</p>
						</div>
						</a>
					</div>
<?
global $USER;
if ($USER->IsAuthorized()){
?>
<span class="clientType" style="display:none;">user</span>
<?}else{?>
<span class="clientType" style="display:none;">guest</span>
<?}?>

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
<?
if(!$USER->IsAuthorized() && !($arResult["ORDER_ID"]))
	{
	?>
	<div class="order__row">
	<?
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	?>
<div class="order__cell">
							<div class="order__block order__reg">
								<div class="order__message order__message_reg">
									<div class="pinkgirl">
										<img src="<?=SITE_TEMPLATE_PATH?>/pinkgirl/pinkgirl1.png" alt="">
									</div>
									<p>��� ������� ��� ������� �����, ����� �������� �����</p>
								</div>
								<label class="order__lbl">
									<span>���</span>
									<input id="name1" class="order__inp" type="text">
								</label>
								<label class="order__lbl">
									<span>E-Mail</span>
									<input id="mail1" class="order__inp required" type="text">
								</label>
								<label class="order__lbl">
									<span>�������</span>
									<input id="phone1" class="order__inp" type="text">
								</label>
								<label class="order__lbl">
									<span>����� ��������</span>
									<textarea id="text1" class="order__inp"></textarea>
								</label>
							</div>

</div>


</div>
<?}?>
<?if($USER->IsAuthorized() && !$_GET["ORDER_ID"])
	{
	?>
<h3 class="on_authord">�� ������� ������������</h3>
<?}?>
<?if(!$_GET["ORDER_ID"]):?>
<script>
	function isright(obj)
 {
 var value= +obj.value.replace(/\D/g,'')||0;
 var min = +obj.getAttribute('min');
 var max = +obj.getAttribute('max');
 obj.value = Math.min(max, Math.max(min, value));
 }
/********E-comerce basket********/
	var item = [], $elems = $('.tov_order'), item_id=[];
	$elems.each(function(i, elem) {
		//console.log(elem);
	/*	item.push({
      'name': $(elem).attr("data-nametov"),
      'id': $(elem).attr("data-idtov"),
      'price': $(elem).attr("data-price"),
      'brand': $(elem).attr("data-brand"),
      'category': 'cart'
    })*/
	item_id.push($(elem).attr("data-idtov"));
	});
	//item_id=item_id.substr(0, item_id.length-1);
	//console.log(item_id);
   dataLayer.push({
	"pageType": "cart",
    "clientType": $(".clientType").text(),
    "prodIds": item_id,
    "totalValue": $(".total_bas").text(),
    });
	/* 'ecommerce': {
        'currencyCode': 'RUB',
         'impressions': item
      },
      'event': 'gtm-ee-event',
      'gtm-ee-event-category': 'Enhanced Ecommerce',
      'gtm-ee-event-action': 'Basket',
      'gtm-ee-event-non-interaction': true,*/

/********�������� �������********/
var xhr;
	$('[name=coupon]').on('keyup', function(e) {
	var coup = e.target.value;
	 if(!!xhr)
            if(xhr!='0') xhr.abort();//��������� ������
        xhr=$.ajax({
				url: "/ajax/validate_order_coup.php",
					type: "post",
					dataType: "json",
					data: {
						"coup": coup
					},
				success: function(d) {
			xhr='0';
					if(d.result=='yes') {
						$('[name=coupon]').css("border-color","green");
						$('.name_coup').text(d.CoupName);
						/*if(!$good)
						$('.bx_ordercart_order_sum tbody').append('<tr class="sum_new_bas"><td class="custom_t1 fwb" colspan="6">����� ������:</td><td class="custom_t2 fwb"><div class="price_coup">'+d.Sum+' ���.</div></td></tr>');*/
						submitForm();
					}else{

						$('[name=coupon]').css("border-color","red");
					}
				}

	});


});


	/********�� �� ������, ������� �� �����������, �������� ���������� ������ ����� �������� ������ ������������ ����� ajax********/
var MY_LOGIN = $(document).find('[code=EMAIL]').val();
if(MY_LOGIN != ''){
   $.ajax({
				url: "/ajax/info_order.php",
					type: "post",
					dataType: "json",
					data: {
						"MY_LOGIN": MY_LOGIN
					},
				success: function(e) {
					if(e.result=='yes') {
						$(document).find('[code=EMAIL]').val(e.mail);
						$(document).find('[code=NAME]').val(e.name);
						$(document).find('[code=PHONE]').val(e.phone);
					}
				}
	});
	}

	/********������� ������� � �������� �����********/
	$("#ORDER_PROP_1").val($("#ORDER_PROP_1").val());
	var a,b,c, name1=$("#name1"), mail1=$("#mail1"), $phone1=$("#phone1"), text1=$("#text1"), $balls=$("#balls");
var $textareaOf = $("#text1"), $textareaIn = $("#ORDER_PROP_7");

$textareaOf.on('keyup', function(e) {
    $textareaIn.text(e.target.value);
});
$phone1.on('keyup', function(e) {
    $("#ORDER_PROP_3").val(e.target.value);
});
$balls.on('keyup', function(e) {
    $("#ORDER_PROP_46").val(e.target.value);
});

function epl3(){
    a = name1.val();
    $("#ORDER_PROP_1").val(a);
	b = mail1.val();
    $("#ORDER_PROP_2").val(b);
};
epl3();

$("#name1").click(function(){
    setTimeout('epl3()',100)
});
$("#mail1").click(function(){
    setTimeout('epl3()',100)
});

name1.bind('input',function(e){
    epl3();
});
mail1.bind('input',function(e){
    epl3();
});

jQuery(function($){

	$(".agreement-drop").click(function(e){
		e.preventDefault();
        var t = $(this).parents('.chek_politik').find('.agreement-info');
        t.stop().slideToggle(200);
	});
});



</script>
<div class="order__next order__next_fin">
						<a href=""  onclick="submitForm('Y'); return false;" class="checkout button big_btn"><span><?=GetMessage("SOA_TEMPL_BUTTON")?></span>
						<div class="order__message order__message_next">
							<div class="pinkgirl">
								<img src="<?=SITE_TEMPLATE_PATH?>/pinkgirl/pinkgirl5.png" alt="">
							</div>
							<p>������ ����� ���� �������� ��� ������</p>
						</div>
						</a>
</div>
<div class="order__block">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"mshop2",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "aspro_mshop_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"NEWS_COUNT" => "5",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"LINK",1=>"",),
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC"
	)
);?>
</div>
<?endif;?>
</div>
