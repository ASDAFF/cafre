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
								$(document).find("form[name=order_auth_form]").after('<p id="error">Авторизуйтесь, такой email уже существует</p>');
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
									$('#order_form_content').before('<p class="errors"><font class="errortext">Имя обязательно для заполнения</font></p><p class="errors"><font class="errortext">E-Mail обязательно для заполнения</font></p><p class="errors"><font class="errortext">Телефон обязательно для заполнения</font></p>');
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
							<p>А теперь скорее жми на эту кнопку</p>
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
	<span class="title_coup">Выберите способ доставки:</span>
	<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery_new.php");			?>
	</div>


	<label class="block_coup">
				<span class="title_coup">У меня есть промокод:</span>
				<div class="block_coup-wrap">
					<input type="text" name="coupon" class="coup_inp" value="<?=key($prc_s["COUPON_LIST"]);?>"/>
					<button class="button medium">применить</button>
				</div>
				<span class="name_coup"></span>
	</label>
</div>




<?
/*if($USER->IsAuthorized())
	{
	?>
<label class="block_ball">
			<!--<span class="title_ball">Баллов в наличии:-->
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
			<span class="title_ball">Применить баллы:</span>
			<br/>
			<input type="number" min="0" max="<?=$numb;?>" name="balls" id="balls" class="ball_inp" placeholder="Баллов в наличии: <?=$numb;?>"  onkeyup="isright(this);"/>
</label>
	<?}*/?>
<?
}?>

<div class="chek_politik">
			<input type="checkbox" checked/>
<div class="tex_check_politik2">
	Принимаю условия <a href="/politik/" target="_blank">Согласия на обработку персональных данных</a>
	</div>
	<br/><br/>
<input type="checkbox" checked/>
<div class="tex_check_politik2">
	С условиями <a href="#" class="agreement-drop">оферты ознакомлен и согласен</a>
	</div>
	<div class="agreement-info">
	<h2>ПУБЛИЧНАЯ ОФЕРТА</h2>
	<p>
	1. Термины и определения, используемые в настоящей Оферте.<br />
В настоящей оферте, если из контекста не следует иное, нижеприведенные термины имеют следующие значения и являются её составной неотъемлемой частью:<br />
«Продавец» — общество с ограниченной ответственностью «КАФРЕ» (ИНН 7325153965), осуществляющий предпринимательскую деятельность по продаже Товара дистанционным способом под товарным знаком «CAFRE».<br />
«Оферта» — настоящий документ, являющийся публичным предложением Продавца, адресованным любому физическому лицу, о заключении договора розничной купли-продажи Товара (далее - «Договор») на условиях, содержащихся в Оферте, включая все её приложения.<br />
«Покупатель» — любое физическое лицо, принявшее (акцептировавшее) настоящую оферту на нижеуказанных условиях.<br />
«Зарегистрированный покупатель» — Покупатель, предоставивший Продавцу свои персональные данные посредством регистрации на Сайте, которые могут быть использованы Продавцом для оформления Заказа Покупателя.<br />
«Сайт» — совокупность электронных документов, доступных для просмотра Покупателями в сети Интернет в домене www.cafre.ru.<br />
«Интернет-магазин» — интернет-магазин по продаже парфюмерно-косметических товаров, размещенных на Сайте.<br />
«Товар» — объект купли-продажи, не изъятый и не ограниченный в гражданском обороте и предложенный к продаже посредством размещения в соответствующем разделе Интернет-магазина.<br />
«Заказ» — оформленный Покупателем в Интернет-магазине запрос на покупку и доставку Товаров, выбранных Покупателем в Интернет-магазине, и предоставленный Продавцу посредством сети Интернет (электронная форма, размещенная на Сайте) и/или оформленный Покупателем по телефону.<br />
«Курьерская служба» — третье лицо, определяемое Продавцом, осуществляющее Доставку Товара Покупателю, либо уполномоченное от имени и за счёт Продавца заключать Договор и производить его исполнение.<br />
«Акцепт»— полное и безоговорочное принятие Покупателем условий оферты.
	<br /><br />
	2. Общие положения<br />
В соответствии со статьей 437 Гражданского Кодекса Российской Федерации данный документ является публичной офертой, адресованной физическим лицам, и в случае принятия изложенных ниже условий, физическое лицо обязуется принять и произвести оплату Товара и его доставки на условиях, изложенных в настоящей оферте. В соответствии с пунктом 3 статьи 438 ГК РФ, факт оформления заказа Покупателем является акцептом оферты Продавца, что является равносильным заключению Договора купли-продажи Товара на условиях, установленных в настоящей оферте и на Сайте.
Продавец и Покупатель гарантируют, что обладают необходимой право- и дееспособностью, а также всеми правами и полномочиями, необходимыми и достаточными для заключения и исполнения Договора розничной купли-продажи Товара.
Заказывая Товары через Интернет-магазин, Покупатель безоговорочно принимает условия настоящей оферты, а также условия, указанные на Сайте.
К отношениям между Покупателем и Продавцом применяются положения Гражданского кодекса РФ (розничной купле-продаже, Закон РФ «О защите прав потребителей», Постановление Правительства РФ от 27.09.2007 г. №612 «Об утверждении Правил продажи товаров дистанционным способом» и иные положения действующего законодательства РФ.
Продавец оставляет за собой право вносить изменения в настоящую оферту, в связи с чем Покупатель обязуется самостоятельно контролировать наличие изменений в оферте, размещенной на Сайте.
<br /><br />
3. Предмет договора<br />
Продавец обязуется передать в собственность Покупателя для личного использования косметические средства (далее по тексту договора - Товар) из ассортиментного перечня, имеющегося у Продавца, а Покупатель обязуется принять этот Товар и оплатить на условиях, предусмотренных настоящим договором.
<br /><br />
	4. Права и обязанности Сторон<br />
Продавец обязуется:<br />
С момента заключения настоящего Договора обеспечить исполнение своих обязательств перед Покупателем на условиях, установленных настоящей офертой и в соответствии с требованиями действующего законодательства РФ. <br />
Принимая (акцептируя) настоящую оферту, Покупатель подтверждает свое согласие и разрешает обществу с ограниченной ответственностью «КАФРЕ» (далее - Оператор) обрабатывать свои персональные данные, в том числе: фамилию, имя, отчество; адрес доставки, телефон для связи с Покупателем; адрес электронной почты; адрес проживания.<br />
Под обработкой персональных данных в настоящей оферте понимается: сбор вышеуказанных данных; их систематизация; накопление; хранение; уточнение (обновление, изменение); использование, распространение (в том числе передача на территории Российской Федерации и трансграничная передача); обезличивание; блокирование; уничтожение.<br />
Оператор имеет право на передачу персональных данных Покупателя контрагентам Оператора (Курьерским службам) с целью доставки Покупателю заказанных последним Товаров.<br />
Покупатель выражает согласие и разрешает Оператору и его контрагентам обрабатывать персональные данные Покупателя с помощью автоматизированных систем управления базами данных, а также иных программных и технических средств.
Оператор вправе самостоятельно определять используемые способы обработки персональных данных Покупателя.<br />
Покупатель соглашается с тем, что его персональные данные, полученные Оператором, могут быть переданы третьим лицам в целях, указанных в настоящей оферте, или для исполнения обязательств Продавца по заключенной с Покупателем сделке в отношении Товара.
Покупатель также предоставляет Оператору и третьим лицам право обрабатывать и использовать свои персональные данные с целью проведения исследований, направленных на улучшение качества предоставляемых услуг и Товаров, в том числе для проведения маркетинговых программ и исследований, статистических исследований, рассылку сообщений рекламного характера.<br />
Покупатель подтверждает свое согласие с тем, что Продавец или уполномоченные им лица вправе взаимодействовать с Покупателем путем осуществления прямых контактов с Покупателем с помощью различных средств связи, включая, но, не ограничиваясь: почтовая рассылка, sms-рассылка, электронная почта, телефон, сеть Интернет и др., при условии соблюдения такими третьими лицами действующего законодательства РФ в сфере защиты персональных данных.<br />
При передаче персональных данных Покупателя третьим лицам Оператор предупреждает лиц, получающих персональные данные Покупателя, о том, что эти данные являются конфиденциальными и могут быть использованы лишь в целях, для которых они сообщены, и требует от таких третьих лиц соблюдения этого условия.<br />
Оператор обеспечивает конфиденциальность предоставленных Покупателем персональных данных, их защиту от несанкционированного доступа, копирования, распространения. В любой момент Покупатель вправе запросить перечень своих персональных данных и/или потребовать изменить, уничтожить свои персональные данные, позвонив Оператору по телефону или отправив сообщение по электронной почте, указав имя, фамилию и адрес доставки.
<br /><br />
Продавец имеет право:<br />
Изменять условия настоящей оферты; цены на Товар, указанные в Интернет-магазине; условия оплаты Товара; способы и сроки доставки Товара; а также иные условия, указанные в настоящей оферте или в Интернет-магазине.<br />
Без согласования с Покупателем передавать свои права и обязанности по заключенной с Покупателем сделке (Договора) третьим лицам.
<br /><br />
Покупатель обязуется:<br />
До момента оформления Заказа на Сайте — ознакомиться с содержанием и условиями, установленными в настоящей оферте, а также с иными условиями, указанными на Сайте, в том числе с ценами на Товар, установленными в Интернет-магазине.<br />
Во исполнение Продавцом своих обязательств перед Покупателем последний должен сообщить свои персональные данные, необходимые для идентификации Покупателя и достаточные для совершения сделки с Продавцом и доставки Покупателю заказанного им Товара.<br />
Оплатить заказанный Товар и его доставку на условиях настоящей оферты.<br />
Соблюдать условия, установленные в настоящей оферте, а также иные условия, указанные на Сайте.
<br /><br />
5. Порядок оформления заказа<br />
Заказ Покупателя может быть оформлен посредством заполнения электронной формы Заказа на Сайте. При оформлении Заказа посредством электронной формы на Сайте, Покупатель тем самым подтверждает, что он ознакомлен с правилами продажи Товаров через Интернет-магазин, указанными на Сайте и в настоящей оферте, и обязуется предоставить Продавцу всю информацию, необходимую для надлежащего оформления и исполнения Заказа.<br />
Если на складе Продавца отсутствует необходимое количество или ассортимент заказанного Покупателем Товара, Продавец информирует об этом Покупателя по телефону или путем отправки сообщения на указанный Покупателем адрес электронной почты, в течение 24 (двадцати четырех) часов после получения Заказа от Покупателя. Покупатель вправе согласиться принять Товар в ином количестве или ассортименте, либо аннулировать свой Заказ. В случае неполучения ответа Покупателя в течение 24 (двадцати четырех) часов с момента уведомления Покупателя Продавцом, Продавец вправе аннулировать Заказ Покупателя в полном объеме.<br />
В случае возникновения у Покупателя вопросов, касающихся свойств и характеристик Товара, перед оформлением Заказа, Покупатель должен обратиться к Продавцу по телефону или электронной почте для получения необходимой информации.
<br /><br />
6. Порядок доставки<br />
Доставка товара до порога клиента осуществляется Курьерской службой или Почтой России. С порядком доставки и стоимостью ее оплаты Покупатель может ознакомиться в соответствующем разделе Сайта «Доставка и оплата» (https://estel.m-cosmetica.ru/pages/shipping).
Покупатель вправе в любой момент времени отказаться от Заказа. В случае если Покупателем, отказавшимся от своего Заказа, предварительно была оплачена стоимость доставки Товара, такие расходы Покупателя в таком случае Продавцом не возмещаются.<br />
В случае если Товар не был передан Покупателю по вине последнего, повторная доставка может быть произведена при условии согласования Продавцом и Покупателем новых сроков доставки и при условии оплаты Покупателем стоимости вторичной доставки Товара.
При получении Товара Покупатель проверяет соответствие доставленного Товара Заказу, комплектность и отсутствие претензий к внешнему виду доставленного Товара. Приемка Товара подтверждается подписью Покупателя на бланке накладной. Приемка Товара без замечаний лишает Покупателя права ссылаться на некомплектность Товара, наличие явных внешних повреждений Товара (явных производственных дефектов), несоответствие фактически поставленного Товара Заказу или сопроводительному документу.
<br /><br />
7. Порядок оплаты<br />
Цены на Товар определяются Продавцом в одностороннем порядке и указываются на Сайте в российских рублях. Цена на товар, указанная на сайте может отличаться от цен на товар имеющихся в свободной продаже в розничных магазинах сети «CAFRE». Цена Товара может быть изменена Продавцом в одностороннем порядке. При этом цена на заказанный Покупателем Товар изменению не подлежит.<br />
Покупатель может заказать только тот Товар, который есть в наличии на складе в момент оформления Заказа.<br />
 Оплата Товара Покупателем производится в рублях одним из нижеперечисленных способов:<br />
•	перечислением на расчётный счет Продавца;<br />
•	наличными курьеру при доставке до двери получателя.
<br /><br />
8. Возврат товара<br />
Покупатель вправе отказаться от Товара надлежащего качества в любое время до его передачи, а после передачи Товара — в течение 7 (семи) календарных дней с момента приемки Товара Покупателем при соблюдении следующих условий:<br />
•	сохранен товарный вид (оригинальная упаковка, фабричные ярлыки, пломбы);<br />
•	сохранены потребительские свойства Товара;<br />
•	товар не имеет следов эксплуатации;<br />
•	наличие документов на Товар, подтверждающих факт покупки возвращаемого Товара (кассовый или товарный чек).<br />
При возврате Товара надлежащего качества Покупателю возвращается стоимость Товара, за исключением стоимости доставки. Расходы Покупателя по возврату Продавцу Товара надлежащего качества Продавцом не возмещаются. Срок возврата денежных средств — 10 (десять) рабочих дней со дня передачи Продавцу возвращаемого Товара и предоставления Покупателем соответствующего письменного требования. Денежные средства возвращаются клиенту на его расчетный счет, указанный в заявлении на оплату.
Возврат Товара Продавцу осуществляется путем передачи Товара по адресу: 432071, г.Ульяновск, ул.Радищева, 39, офис 95.<br />
Возврата Товара ненадлежащего качества и его приемка осуществляется в соответствии с положениями действующего законодательства РФ.<br />
Перечень документов, представляемых Клиентом вместе с возвращаемым Товаром:<br />
1.	Заявление на возврат Товара.<br />
2.	Документ на Товар, подтверждающий факт покупки возвращаемого Товара у Продавца (кассовый или товарный чек).<br />
3.	Копию документа, удостоверяющего личность (паспорт гражданина РФ/загранпаспорт, временное удостоверение личности гражданина РФ, выдаваемое на период оформления паспорта, военный билет, водительские права).<br />
<br /><br />
9. Гарантийные обязательства Сторон<br />
Гарантийный срок на Товар, устанавливается в соответствии с действующим законодательством РФ.
<br /><br />
10. Ответственность Сторон<br />
Любая из Сторон освобождается от ответственности за полное или частичное неисполнение своих обязательств по настоящему Договору, если это неисполнение было вызвано обстоятельствами непреодолимой силы. <br />
За неисполнение или ненадлежащее исполнение условий настоящего Договора (акцептованной Покупателем оферты Продавца) Стороны несут ответственность в соответствии с законодательством Российской Федерации.<br />
 Все споры, связанные с неисполнением или ненадлежащим исполнением своих обязательств по настоящему Договору, Стороны будут решать путем переговоров. Претензионный порядок обязателен для сторон составляет 30 календарных дней.
В случае не достижения согласия в ходе переговоров, споры будут разрешаться в судебном порядке в соответствии с действующим законодательством Российской Федерации.
	</p>
	</div>

			</div>

					<div class="order__next order__next_fin" style="display:none;">
						<a href="javascript:;" id="ORDER_CONFIRM_BUTTON" onclick="submitForm('Y'); return false;" class="checkout button big_btn clickd"><span><?=GetMessage("SOA_TEMPL_BUTTON")?></span>
						<div class="order__message order__message_next">
							<div class="pinkgirl">
								<img src="<?=SITE_TEMPLATE_PATH?>/pinkgirl/pinkgirl5.png" alt="">
							</div>
							<p>Теперь точно пора нажимать эту кнопку</p>
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
									<p>Или заполни эту простую форму, чтобы оформить заказ</p>
								</div>
								<label class="order__lbl">
									<span>Имя</span>
									<input id="name1" class="order__inp" type="text">
								</label>
								<label class="order__lbl">
									<span>E-Mail</span>
									<input id="mail1" class="order__inp required" type="text">
								</label>
								<label class="order__lbl">
									<span>Телефон</span>
									<input id="phone1" class="order__inp" type="text">
								</label>
								<label class="order__lbl">
									<span>Адрес доставки</span>
									<textarea id="text1" class="order__inp"></textarea>
								</label>
							</div>

</div>


</div>
<?}?>
<?if($USER->IsAuthorized() && !$_GET["ORDER_ID"])
	{
	?>
<h3 class="on_authord">Вы успешно авторизованы</h3>
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

/********Проверка купонов********/
var xhr;
	$('[name=coupon]').on('keyup', function(e) {
	var coup = e.target.value;
	 if(!!xhr)
            if(xhr!='0') xhr.abort();//прерываем запрос
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
						$('.bx_ordercart_order_sum tbody').append('<tr class="sum_new_bas"><td class="custom_t1 fwb" colspan="6">Сумма скидки:</td><td class="custom_t2 fwb"><div class="price_coup">'+d.Sum+' руб.</div></td></tr>');*/
						submitForm();
					}else{

						$('[name=coupon]').css("border-color","red");
					}
				}

	});


});


	/********Из за данных, которые не добавлялись, пришлось отправлять запрос чтобы получить данные пользователя через ajax********/
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

	/********поменял скрипты и исправил маску********/
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
							<p>Теперь точно пора нажимать эту кнопку</p>
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
