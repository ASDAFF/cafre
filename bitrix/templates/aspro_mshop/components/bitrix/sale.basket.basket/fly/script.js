var basketTimeout;
var totalSum;
var timerBasketUpdate = false;

function setQuantity(basketId, ratio, direction){
	var curVal = parseFloat(BX("QUANTITY_INPUT_" + basketId).value), newVal;
	ratio = parseFloat(ratio);
	newVal = (direction == 'up') ? curVal + ratio : curVal - ratio;
	if (newVal < 0) newVal = 0;
	if (newVal > 0) {
		BX("QUANTITY_INPUT_" + basketId).value = newVal;
		BX("QUANTITY_INPUT_" + basketId).defaultValue = curVal;

		totalSum=0;
		$('#basket_line .basket_fly tr[data-id='+basketId+']').closest('table').find("tbody tr[data-id]").each(function(i, element) {
			id=$(element).attr("data-id");
			count=BX("QUANTITY_INPUT_" + id).value;
			price = $(document).find("#basket_form input[name=item_price_"+id+"]").val();
			sum = count*price;
			totalSum += sum;
			$(document).find("#basket_form [data-id="+id+"] .summ-cell .price").text(jsPriceFormat(sum));
		});

		$("#basket_form .itog span.price").text(jsPriceFormat(totalSum));
		$("#basket_form .itog div.discount").fadeTo( "slow" , 0.2);


		if(timerBasketUpdate){
			clearTimeout(timerBasketUpdate);
			timerBasketUpdate = false;
		}

		timerBasketUpdate = setTimeout(function(){
			updateQuantity('QUANTITY_INPUT_' + basketId, basketId, ratio);
			timerBasketUpdate=false;
		}, 700);
	}
}

function updateQuantity(controlId, basketId, ratio, animate) {

	var oldVal = BX(controlId).defaultValue, newVal = parseFloat(BX(controlId).value) || 0; bValidChange = false; // if quantity is correct for this ratio
	if (!newVal) {
		bValidChange = false;
		BX(controlId).value = oldVal;
	}

	newVal -= newVal % ratio;
	var is_int_ratio = (ratio % 1 == 0);
	newVal = is_int_ratio ? parseInt(newVal) : parseFloat(newVal).toFixed(2);

	if (isRealValue(BX("QUANTITY_SELECT_" + basketId))) { var option, options = BX("QUANTITY_SELECT_" + basketId).options, i = options.length; }
	while (i--) {
		option = options[i];
		if (parseFloat(option.value).toFixed(2) == parseFloat(newVal).toFixed(2)) option.selected = true;
	}

	BX("QUANTITY_" + basketId).value = newVal; // set hidden real quantity value (will be used in POST)
	BX("QUANTITY_INPUT_" + basketId).value = newVal; // set hidden real quantity value (will be used in POST)

	$('form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
	$.post( arMShopOptions['SITE_DIR']+'ajax/show_basket.php', $("form[name^=basket_form]").serialize(), $.proxy(function( data){
		if (timerBasketUpdate==false) {
			basketFly('open');
		}
		$('form[name^=basket_form] input[name=BasketRefresh]').remove();
	}));
}

function delete_all_items(type, item_section, correctSpeed){
	$.post( arMShopOptions['SITE_DIR']+"ajax/show_basket_fly.php", "PARAMS="+$("#basket_form").find("input#fly_basket_params").val()+"&TYPE="+type+"&CLEAR_ALL=Y", $.proxy(function( data ) {
		basketFly('open');
		$('.in-cart').hide();
		$('.in-cart').closest('.button_block').removeClass('wide');
		$('.to-cart').show();
		$('.counter_block').show();
		$('.wish_item').removeClass("added");
		$('.wish_item').find('.value').show();
		$('.wish_item').find('.value.added').hide();
		$.ajax({
														url: '/ajax/small_basket_update.php',
														type: 'post',
														success: function(data) {
															$('.new_bas_small').html(data);
														}
													});
	}));
}

function deleteProduct(basketId, itemSection, item){
	function _deleteProduct(basketId, itemSection){
		$.post( arMShopOptions['SITE_DIR']+'ajax/show_basket.php?action=delete&id='+basketId, $.proxy(function( data ){
			basketFly('open');
		}));
	}

	if(checkCounters()){
		delFromBasketCounter(item);
		setTimeout(function(){
			_deleteProduct(basketId, itemSection);
		}, 100);
	}
	else{
		_deleteProduct(basketId, itemSection);
	}
}

function delayProduct(basketId, itemSection){
	$.post( arMShopOptions['SITE_DIR']+'ajax/show_basket.php?action=delay&id='+basketId, $.proxy(function( data ){
		basketFly('open');
	}));
}

function addProduct(basketId, itemSection){
	$.post( arMShopOptions['SITE_DIR']+'ajax/show_basket.php?action=add&id='+basketId, $.proxy(function( data ) {
		basketFly('open');
	}));
}

function checkOut(event){
	event = event || window.event;
	var th=$(event.target).parent();
	if(checkCounters('google')){
		checkoutCounter(1, th.data('text'), th.data('href'));
	}else{
		location.href=th.data('href');
	}
	return true;
}

