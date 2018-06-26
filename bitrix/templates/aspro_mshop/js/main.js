var basketTimeoutSlide;
var resizeEventTimer;

var funcDefined = function(func){
	try {
		if (typeof func == 'function') {
			return true;
		} else {
			return typeof window[func] === "function";
		}
	} catch (e) {
		return false;
	}
}

if(!funcDefined('setLocationSKU')){
	function setLocationSKU(curLoc){
		try {
			history.pushState(null, null, curLoc);
			return;
		} catch(e) {}
			location.hash = '#' + curLoc.substr(1)
	}
}

if(!funcDefined('trimPrice')){
	var trimPrice = function trimPrice(s){
		s=s.split(" ").join("");
		s=s.split("&nbsp;").join("");
		return s;
	}
}

if(!funcDefined('markProductRemoveBasket')){
	var markProductRemoveBasket = function markProductRemoveBasket(id){
		$('.in-cart[data-item='+id+']').hide();
		$('.to-cart[data-item='+id+']').show();
		$('.to-cart[data-item='+id+']').closest('.button_block').removeClass('wide');
		$('.to-cart[data-item='+id+']').closest('.counter_wrapp').find('.counter_block').show();
		$('.counter_block[data-item='+id+']').show();
		$('.in-subscribe[data-item='+id+']').hide();
		$('.to-subscribe[data-item='+id+']').show();
		$('.wish_item[data-item='+id+']').removeClass("added");
		$('.wish_item[data-item='+id+'] .value:not(.added)').show();
		$('.wish_item[data-item='+id+'] .value.added').hide();
	}
}

if(!funcDefined('markProductAddBasket')){
	var markProductAddBasket = function markProductAddBasket(id){
		$('.to-cart[data-item='+id+']').hide();
		$('.to-cart[data-item='+id+']').closest('.counter_wrapp').find('.counter_block').hide();
		$('.to-cart[data-item='+id+']').closest('.button_block').addClass('wide');
		$('.in-cart[data-item='+id+']').show();
		$('.wish_item[data-item='+id+']').removeClass("added");
		$('.wish_item[data-item='+id+'] .value:not(.added)').show();
		$('.wish_item[data-item='+id+'] .value.added').hide();
	}
}

if(!funcDefined('markProductDelay')){
	var markProductDelay = function markProductDelay(id){
		$('.in-cart[data-item='+id+']').hide();
		$('.to-cart[data-item='+id+']').show();
		$('.to-cart[data-item='+id+']').closest('.counter_wrapp').find('.counter_block').show();
		$('.to-cart[data-item='+id+']').closest('.button_block').removeClass('wide');
		$('.wish_item[data-item='+id+']').addClass("added");
		$('.wish_item[data-item='+id+'] .value:not(.added)').hide();
		// $('.wish_item[data-item='+id+'] .value.added').show();
		$('.wish_item[data-item='+id+'] .value.added').css('display','inline-block');
	}
}

if(!funcDefined('basketFly')){
	var basketFly = function basketFly(action){
		$.post( arMShopOptions['SITE_DIR']+"ajax/reload_basket_fly.php", "PARAMS="+$("#basket_form").find("input#fly_basket_params").val(), $.proxy(function( data ){
			var small=$('.opener .basket_count').hasClass('small'),
				basket_count=$(data).find('.basket_count').find('.items div').text();
			$('#basket_line .basket_fly').html(data);
			if(parseInt(basket_count)){
				$('#basket_line .basket_fly').removeClass('basket_empty');
			}else{
				$('#basket_line .basket_fly').addClass('basket_empty');
			}

			if (action=='open') {
				if(small){
					if(arMShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
						$('.opener .basket_count').click();
					}
				}else{
					$('.opener .basket_count').removeClass('small')
					$('.tabs_content.basket li[item-section="AnDelCanBuy"]').addClass('cur');
					$('#basket_line ul.tabs li[item-section="AnDelCanBuy"]').addClass('cur');
				}
			} else if (action=='wish') {
				if(small){
					if(arMShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
						$('.opener .wish_count').click();
					}
				}else{
					$('.opener .wish_count').removeClass('small')
					$('.tabs_content.basket li[item-section="DelDelCanBuy"]').addClass('cur');
					$('#basket_line ul.tabs li[item-section="DelDelCanBuy"]').addClass('cur');
				}
			} else {
				if(arMShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
					$('.opener .basket_count').click();
				}
			}

		}));
	}
}



if(!funcDefined('initSelects')){
	function initSelects(target){
		var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );
		if ( iOS ) return;
		// SELECT STYLING
		$(target).find('.wrapper select').ikSelect({
			syntax: '<div class="ik_select_link"> \
						<span class="ik_select_link_text"></span> \
						<div class="trigger"></div> \
					</div> \
					<div class="ik_select_dropdown"> \
						<div class="ik_select_list"> \
						</div> \
					</div>',
			dynamicWidth: true,
			ddMaxHeight: 112,
			customClass: 'common_select',
			//equalWidths: true,
			onShow: function(inst){
				inst.$dropdown.css('top', (parseFloat(inst.$dropdown.css('top'))-5)+'px');
				if ( inst.$dropdown.outerWidth() < inst.$link.outerWidth() ){
					inst.$dropdown.css('width', inst.$link.outerWidth());
				}
				if ( inst.$dropdown.outerWidth() > inst.$link.outerWidth() ){
					inst.$dropdown.css('width', inst.$link.outerWidth());
				}
				var count=0,
					client_height=0;
				inst.$dropdown.css('left', inst.$link.offset().left);
				$(inst.$listInnerUl).find('li').each(function(){
					if(!$(this).hasClass('ik_select_option_disabled')){
						++count;
						client_height+=$(this).outerHeight();
					}
				})
				if(client_height<112){
					inst.$listInner.css('height', 'auto');
				}else{
					inst.$listInner.css('height', '112px');
				}
				inst.$link.addClass('opened');
				inst.$listInner.addClass('scroller');
			},
			onHide: function(inst){
				inst.$link.removeClass('opened');
			}
		});
		// END OF SELECT STYLING

		var timeout;
		$(window).on('resize', function(){
			clearTimeout(timeout);
			timeout = setTimeout(function(){
				//$('select:visible').ikSelect('redraw');
				var inst='';
				if(inst=$('.common_select-link.opened + select').ikSelect().data('plugin_ikSelect')){
					inst.$dropdown.css('left', inst.$link.offset().left+'px');
				}
			}, 20);
		});
	}
}

if(!funcDefined('initHoverBlock')){
	function initHoverBlock(target){
		$(target).find('.catalog_item.item_wrap').on('mouseenter', function(){
			$(this).addClass('hover');
		})
		$(target).find('.catalog_item.item_wrap').on('mouseleave', function(){
			$(this).removeClass('hover');
		})
	}
}

if(!funcDefined('setStatusButton')){
	function setStatusButton(){
		$.ajax({
			url: arMShopOptions["SITE_DIR"]+'ajax/get_basket_count.php',
			type: 'POST',
			success: function(data){
				if(data.ITEMS || data.DELAY_ITEMS || data.SUBSCRIBE_ITEMS) {
					if(data.ITEMS){
						for( var i in data.ITEMS ){
							$('.to-cart[data-item='+data.ITEMS[i].PRODUCT_ID+']').hide();
							$('.counter_block[data-item='+data.ITEMS[i].PRODUCT_ID+']').hide();
							$('.in-cart[data-item='+data.ITEMS[i].PRODUCT_ID+']').show();
							$('.in-cart[data-item='+data.ITEMS[i].PRODUCT_ID+']').closest('.button_block').addClass('wide');
						}
					}
					if(data.DELAY_ITEMS){
						for( var i in data.DELAY_ITEMS ){
							$('.wish_item.to[data-item='+data.DELAY_ITEMS[i].PRODUCT_ID+']').hide();
							$('.wish_item.in[data-item='+data.DELAY_ITEMS[i].PRODUCT_ID+']').show();
							if ($('.wish_item[data-item='+data.DELAY_ITEMS[i].PRODUCT_ID+']').find(".value.added").length) {
								$('.wish_item[data-item='+data.DELAY_ITEMS[i].PRODUCT_ID+']').addClass("added");
								$('.wish_item[data-item='+data.DELAY_ITEMS[i].PRODUCT_ID+']').find(".value").hide();
								$('.wish_item[data-item='+data.DELAY_ITEMS[i].PRODUCT_ID+']').find(".value.added").show();
							}
						}
					}
					if(data.SUBSCRIBE_ITEMS){
						for( var i in data.SUBSCRIBE_ITEMS ){
							$('.to-subscribe[data-item='+data.SUBSCRIBE_ITEMS[i].PRODUCT_ID+']').hide();
							$('.in-subscribe[data-item='+data.SUBSCRIBE_ITEMS[i].PRODUCT_ID+']').show();
						}
					}
				}
			}
		});
		$.ajax({
			url: arMShopOptions["SITE_DIR"]+'ajax/get_compare_count.php',
			type: 'POST',
			success: function(data){
				if(data.ITEMS) {
					if(data.ITEMS){
						for( var i in data.ITEMS ){
							$('.compare_item.to[data-item='+data.ITEMS[i]+']').hide();
							$('.compare_item.in[data-item='+data.ITEMS[i]+']').show();
							if ($('.compare_item[data-item='+data.ITEMS[i]+']').find(".value.added").length){
								$('.compare_item[data-item='+data.ITEMS[i]+']').find(".value").hide();
								$('.compare_item[data-item='+data.ITEMS[i]+']').find(".value.added").show();
							}
						}
					}
				}
			}
		})
	}
}


if(!funcDefined('onLoadjqm')){
	var onLoadjqm = function(name, hash, requestData, selector){
		hash.w.addClass('show').css({
			'margin-left': ($(window).width() > hash.w.outerWidth() ? '-' + hash.w.outerWidth() / 2 + 'px' : '-' + $(window).width() / 2 + 'px'),
			'top': $(document).scrollTop() + (($(window).height() > hash.w.outerHeight() ? ($(window).height() - hash.w.outerHeight()) / 2 : 10))   + 'px'
		});
		if(typeof(requestData) == 'undefined'){
			requestData = '';
		}
		if(typeof(selector) == 'undefined'){
			selector = false;
		}
		var width = $('.'+name+'_frame').width();
		$('.'+name+'_frame').css('margin-left', '-'+width/2+'px');

		if(name=='order-popup-call') {
		}
		else if(name=='order-button') {
			$(".order-button_frame").find("div[product_name]").find("input").val(hash.t.title).attr("readonly", "readonly").css({"overflow": "hidden", "text-overflow": "ellipsis"});
		}
		else if(name == "to-order" && selector){
			$(".to-order_frame").find('[data-sid="PRODUCT_NAME"]').val($(selector).data('name')).attr("readonly", "readonly").css({"overflow": "hidden", "text-overflow": "ellipsis"});
			$(".to-order_frame").find('[data-sid="PRODUCT_ID"]').val($(selector).attr('data-item'));
		}
		else if(name == "services" && selector) {
			$(".services_frame").find('[data-sid="SERVICE"]').val($(selector).attr('data-title'));
		}
		else if(name == "resume" && selector) {
			if($(selector).attr('data-jobs')){
				$(".resume_frame").find('[data-sid="POST"]').attr("readonly", "readonly").val($(selector).attr('data-jobs'));
			}
		}
		else if( name == 'one_click_buy') {
			$('#one_click_buy_form_button').on("click", function() {
				if(!$(this).hasClass("clicked")){
					$(this).addClass("clicked");
					$("#one_click_buy_form").submit();  //otherwise don't works
				}
			});

			$('#one_click_buy_form').submit( function(e) {
				if($('.'+name+'_frame form input.error').length || $('.'+name+'_frame form textarea.error').length) {
					return false
				}
				else{
					$this=$(this);
					$.ajax({
						url: $(this).attr('action'),
						data: $(this).serialize(),
						type: 'POST',
						dataType: 'json',
						error: function(data) {
							alert('Error connecting server');
						},
						success: function(data) {
							if(data.result == 'Y'){
								$('.one_click_buy_result').show();
								$('.one_click_buy_result_success').show();
								purchaseCounter(data.message, arMShopOptions["COUNTERS"]["TYPE"]["ONE_CLICK"]);
								$this.data('order', data.message);
								$this.trigger('action_ga');	
								
								gtag('event', 'send', {'event_category': 'zakaz'});
								
							}
							else{
								$('.one_click_buy_result').show();
								$('.one_click_buy_result_fail').show();
								$('.one_click_buy_result_text').text(data.message);
							}
							$('.one_click_buy_modules_button', self).removeClass('disabled');
							$('#one_click_buy_form').hide();
							$('#one_click_buy_form_result').show();
						}
					});
				}
				return false;
			});
		}
		else if( name == 'one_click_buy_basket') {
			$('#one_click_buy_form_button').on("click", function() {
				if(!$(this).hasClass("clicked")){
					$(this).addClass("clicked");
					$("#one_click_buy_form").submit();  //otherwise don't works
				}
			});

			$('#one_click_buy_form').on("submit", function(){
				if($('.'+name+'_frame form input.error').length || $('.'+name+'_frame form textarea.error').length) {
					return false
				}
				else{
					$this=$(this);
					$.ajax({
						url: "/ajax/action_flybas.php", 
						type: "post", 
						dataType: "json", 
						data:{"price": 0},
						success: function(dataB){
							$.ajax({
								url: $this.attr('action'),
								data: $this.serialize(),
								type: 'POST',
								dataType: 'json',
								error: function(data) {
									window.console&&console.log(data);
								},
								success: function(data) {
									if(data.result == 'Y') {										
										dataB.transaction_id=data.message;
										//gtag('event', 'purchase', dataB);
										
										window.dataLayer = window.dataLayer || [];
										dataLayer.push({
											'ecommerce': {	
												'currencyCode': 'RUB',
												'purchase': {
													'actionField': {	
														'id': dataB.transaction_id,
														'affiliation': 'cafre.ru',
														'revenue':  dataB.value,
														'shipping': 0
													},
													'products': dataB.items
												}
											},
											'event': 'gtm-ee-event',						
											'gtm-ee-event-category': 'Enhanced Ecommerce',
											'gtm-ee-event-action': 'Purchase',
											'gtm-ee-event-non-interaction': 'False',
										}); 
										console.log(dataB);
										console.log(dataLayer);
										 
										/*window.dataLayer = window.dataLayer || [];
										dataLayer.push({
											'transactionId': dataB.transaction_id,
											'transactionAffiliation': dataB.affiliation,
											'transactionTotal': dataB.value,
											'transactionShipping':  dataB.shipping,
											'transactionProducts': dataB.items
										}); */
										
										$('.one_click_buy_result').show();
										$('.one_click_buy_result_success').show();
										purchaseCounter(data.message, arMShopOptions["COUNTERS"]["TYPE"]["QUICK_ORDER"]);
										
										gtag('event', 'send', {'event_category': 'zakaz'});
										
										
									}
									else{
										$('.one_click_buy_result').show();
										$('.one_click_buy_result_fail').show();
										$('.one_click_buy_result_text').text(data.message);
									}
									$('.one_click_buy_modules_button', self).removeClass('disabled');
									$('#one_click_buy_form').hide();
									$('#one_click_buy_form_result').show();
								}
							});
							
						}
					});					
				}
				return false;
			});
		}

		$('.'+name+'_frame').show();
	}
}

if(!funcDefined('onHidejqm')){
	var onHidejqm = function(name, hash){
		if (hash.w.find('.one_click_buy_result_success').is(':visible') && name=="one_click_buy_basket") {
			$("#content").html("<p>Ваш заказ оформлен.</p><p>В ближайшее время наши менеджеры с Вами свяжутся для уточнения адреса и способа доставки заказа.</p>");
		}
		hash.w.css('opacity', 0).hide();
		hash.w.empty();
		hash.o.remove();
		hash.w.removeClass('show');
	}
}

if(!funcDefined("oneClickBuy")) {
	var oneClickBuy = function (elementID, iblockID, that) {
		var name = 'one_click_buy';
		var elementQuantity = 1;
		var offerProps = false;

		if(typeof(that) !== 'undefined'){
			elementQuantity = $(that).attr('data-quantity');
			offerProps = $(that).attr('data-props');
		}

		if(elementQuantity < 1){
			elementQuantity = 1;
		}

		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash) }, toTop: false, onLoad: function( hash ){ onLoadjqm(name, hash ); }, ajax: arMShopOptions["SITE_DIR"]+'ajax/one_click_buy.php?ELEMENT_ID='+elementID+'&IBLOCK_ID='+iblockID+'&ELEMENT_QUANTITY='+elementQuantity+(offerProps ? '&OFFER_PROPS='+offerProps : '')});
		$('.'+name+'_frame.popup').click();
	}
}

if(!funcDefined("oneClickBuyBasket")) {
	var oneClickBuyBasket = function () {
		name = 'one_click_buy_basket'
		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash) }, onLoad: function( hash ){ onLoadjqm( name, hash ); }, ajax: arMShopOptions["SITE_DIR"]+'ajax/one_click_buy_basket.php'});
		$('.'+name+'_frame.popup').click();
	}
}

if(!funcDefined("scroll_block")) {
	function scroll_block(block){
		var topPos = block.offset().top,
			headerH = $('header').outerHeight(true,true);
		if($(".stores_tab").length){
			$(".stores_tab").addClass("current").siblings().removeClass("current");
		}else{
			$(".prices_tab").addClass("current").siblings().removeClass("current");
			if($(".prices_tab .opener").length && !$(".prices_tab .opener .opened").length){
				var item = $(".prices_tab .opener").first();
				item.find(".opener_icon").addClass("opened");
				item.parents("tr").addClass("nb")
				item.parents("tr").next(".offer_stores").find(".stores_block_wrap").slideDown(200);
			}
		}
		$('html,body').animate({'scrollTop':topPos-30},150);
	}
}

if(!funcDefined("jqmEd")) {
	var jqmEd = function (name, form_id, open_trigger, requestData, selector){
		if(typeof(requestData) == "undefined"){
			requestData = '';
		}
		if(typeof(selector) == "undefined"){
			selector = false;
		}
		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		if(typeof open_trigger == "undefined" ){
			$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arMShopOptions["SITE_DIR"]+'ajax/form.php?form_id='+form_id+(requestData.length ? '&' + requestData : '')});
		}else{
			if(name == 'enter'){
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arMShopOptions["SITE_DIR"]+'ajax/auth.php'});
			}else{
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arMShopOptions["SITE_DIR"]+'ajax/form.php?form_id='+form_id+(requestData.length ? '&' + requestData : '')});
			}
			$(open_trigger).dblclick(function(){return false;})
		}
		return true;
	}
}

if (!funcDefined("replaceBasketPopup")){
	function replaceBasketPopup (hash){
		if(typeof hash != "undefined"){
			hash.w.hide();
			hash.o.hide();
		}
	}
}

if(!funcDefined("waitLayer")){
	function waitLayer(delay, callback){
		if((typeof dataLayer !== 'undefined') && (typeof callback === 'function')){
			callback();
		}
		else{
			setTimeout(function() {
				waitLayer(delay, callback);
			}, delay);
		}
	}
}

if(!funcDefined("checkCounters")){
	function checkCounters(name){
		if(typeof name !== "undefined"){
			if(name == "google" && (arMShopOptions["COUNTERS"]["GOOGLE_ECOMERCE"] == "Y" && arMShopOptions["COUNTERS"]["GOOGLE_COUNTER"] > 0)){
				return true;
			}
			else if(name == "yandex" && (arMShopOptions["COUNTERS"]["YANDEX_ECOMERCE"] == "Y" && arMShopOptions["COUNTERS"]["YANDEX_COUNTER"] > 0)){
				return true;
			}
			else{
				return false;
			}
		}
		else if((arMShopOptions["COUNTERS"]["YANDEX_ECOMERCE"] == "Y" && arMShopOptions["COUNTERS"]["YANDEX_COUNTER"] > 0) || (arMShopOptions["COUNTERS"]["GOOGLE_ECOMERCE"] == "Y" && arMShopOptions["COUNTERS"]["GOOGLE_COUNTER"] > 0)) {
			return true;
		}
		else{
			return false;
		}
	}
}

if(!funcDefined("addBasketCounter")){
	function addBasketCounter(id){
		if(checkCounters()){
			$.ajax({
				url:arMShopOptions['SITE_DIR'] + "ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: {"ID": id},
				success: function(item){
					if(!!item && !!item.ID){
						waitLayer(100, function() {
							dataLayer.push({
								"event": arMShopOptions["COUNTERS"]['GOOGLE_EVENTS']['ADD2BASKET'],
							    "ecommerce": {
							    	"currencyCode": item.CURRENCY,
							        "add": {
							            "products": [{
						                    "id": item.ID,
						                    "name": item.NAME,
						                    "price": item.PRICE,
						                    "brand": item.BRAND,
						                    "category": item.CATEGORY,
						                    "quantity": item.QUANTITY
						                }]
							        }
							    }
							});
						});
					}
				}
			});
		}
	}
}

if(!funcDefined("purchaseCounter")){
	function purchaseCounter(order_id, type){
		if(checkCounters()){
			$.ajax({
				url:arMShopOptions['SITE_DIR'] + "ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: {"ORDER_ID": order_id, "TYPE": type},
				success: function(order){
					var products = [];
					if(order.ITEMS){
						for(var i in order.ITEMS){
							products.push({
								"id": order.ITEMS[i].ID,
								"sku": order.ITEMS[i].ID,
			                    "name": order.ITEMS[i].NAME,
			                    "price": order.ITEMS[i].PRICE,
			                    "brand": order.ITEMS[i].BRAND,
			                    "category": order.ITEMS[i].CATEGORY,
			                    "quantity": order.ITEMS[i].QUANTITY
							});
						}
					}
					if(order.ID){
						waitLayer(100, function() {
							dataLayer.push({
							    "ecommerce": {
							    	"purchase": {
								    	"actionField":{
								    		"id": order.ACCOUNT_NUMBER,
								    		"shipping": order.PRICE_DELIVERY,
								    		"tax": order.TAX_VALUE,
								    		"list": type,
								    		"revenue": order.PRICE
								    	},
							            "products": products
							        }
							    }
							});
						});
					}
				}
			});
		}
	}
}

if(!funcDefined("viewItemCounter")){
	function viewItemCounter(id, price_id){
		if(checkCounters()){
			$.ajax({
				url:arMShopOptions['SITE_DIR'] + "ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: {"PRODUCT_ID": id, "PRICE_ID": price_id},
				success: function(item){
					if(item.ID){
						waitLayer(100, function() {
							dataLayer.push({
								//"event": "",
								"ecommerce": {
									"detail": {
										"products": [{
											"id": item.ID,
											"name": item.NAME,
											"price": item.PRICE,
											"brand": item.BRAND,
											"category": item.CATEGORY
										}]
									}
								}
							});
						});
					}
				}
			});
		}
	}
}

if(!funcDefined("checkoutCounter")){
	function checkoutCounter(step, option, callback){
		if(checkCounters('google')){
			$.ajax({
				url:arMShopOptions['SITE_DIR'] + "ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: {"BASKET": "Y"},
				success: function(basket){
					var products = [];
					if(basket.ITEMS){
						for(var i in basket.ITEMS){
							products.push({
								"id": basket.ITEMS[i].ID,
			                    "name": basket.ITEMS[i].NAME,
			                    "price": basket.ITEMS[i].PRICE,
			                    "brand": basket.ITEMS[i].BRAND,
			                    "category": basket.ITEMS[i].CATEGORY,
			                    "quantity": basket.ITEMS[i].QUANTITY
							});
						}
					}
					if(products){
						waitLayer(100, function() {
							dataLayer.push({
								"event": arMShopOptions["COUNTERS"]['GOOGLE_EVENTS']['CHECKOUT_ORDER'],
							    "ecommerce": {
							    	"actionField":{
							    		"step": step,
							    		"option": option
							    	},
							        "products": products
							    },
							    /*"eventCallback": function() {
							    	if((typeof callback !== 'undefined') && (typeof callback === 'function')){
							    		callback();
							    	}
							   }*/
							});
						});
					}
				}
			});
		}
	}
}

if(!funcDefined("delFromBasketCounter")){
	function delFromBasketCounter(id, callback){
		if(checkCounters()){
			$.ajax({
				url:arMShopOptions['SITE_DIR'] + "ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: {"ID": id},
				success: function(item){
					if(item.ID){
						waitLayer(100, function() {
							dataLayer.push({
								"event": arMShopOptions["COUNTERS"]['GOOGLE_EVENTS']['REMOVE_BASKET'],
							    "ecommerce": {
							        "remove": {
							            "products": [{
						                    "id": item.ID,
						                    "name": item.NAME,
						                    "category": item.CATEGORY
						                }]
							        }
							    }
							});
							if(typeof callback == 'function'){
								callback();
							}
						});
					}
				}
			});
		}
	}
}

if(!funcDefined("setHeightCompany")){
	function setHeightCompany(){
		$('.md-50.img').height($('.md-50.big').outerHeight()-35);
	}
}

if(!funcDefined("initSly")){
	function initSly(){
		var $frame  = $(document).find('.frame');
		var $slidee = $frame.children('ul').eq(0);
		var $wrap   = $frame.parent();

		$frame.sly({
			horizontal: 1,
			itemNav: 'basic',
			smart: 1,
			mouseDragging: 0,
			touchDragging: 0,
			releaseSwing: 0,
			startAt: 0,
			scrollBar: $wrap.find('.scrollbar'),
			scrollBy: 1,
			speed: 300,
			elasticBounds: 0,
			easing: 'swing',
			dragHandle: 1,
			dynamicHandle: 1,
			clickBar: 1,

			// Buttons
			forward: $wrap.find('.forward'),
			backward: $wrap.find('.backward'),
		});
		$frame.sly('reload');
	}
}

if(!funcDefined("createTableCompare")){
	function createTableCompare(originalTable, appendDiv, cloneTable){
		
		try{
			var clone = originalTable.clone().removeAttr('id').addClass('clone');
			if(cloneTable.length){
				cloneTable.remove();
				appendDiv.html('');
				appendDiv.html(clone);
			}else{
				appendDiv.append(clone);
			}
		}
		catch(e){}
		finally{
			
		}
	}
}

if(!funcDefined("isRealValue")){
	function isRealValue(obj){
		return obj && obj !== "null" && obj!== "undefined";
	}
}

if(!funcDefined("rightScroll")){
	function rightScroll(prop, id){
		var el = BX('prop_' + prop + '_' + id);
		if (el) {
			var curVal = parseInt(el.style.marginLeft);
			if (curVal >= 0) el.style.marginLeft = curVal - 20 + '%';
		}
	}
}

if(!funcDefined("leftScroll")){
	function leftScroll(prop, id){
		var el = BX('prop_' + prop + '_' + id);
		if (el) {
			var curVal = parseInt(el.style.marginLeft);
			if (curVal < 0) el.style.marginLeft = curVal + 20 + '%';
		}
	}
}

checkPopupWidth = function(){
	$('.popup.show').each(function() {
		var width_form = $(this).actual('width');
		$(this).css({
			'margin-left': ($(window).width() > width_form ? '-' + width_form / 2 + 'px' : '-' + $(window).width() / 2 + 'px'),
		});
	});
}

checkCaptchaWidth = function(){
	$('.captcha-row').each(function() {
		var width = $(this).actual('width');
		if($(this).hasClass('b')){
			if(width > 320){
				$(this).removeClass('b');
			}
		}
		else{
			if(width <= 320){
				$(this).addClass('b');
			}
		}
	});
}

checkFormWidth = function(){
	$('.form .form_left').each(function() {
		var form = $(this).parents('.form');
		var width = form.actual('width');
		if(form.hasClass('b')){
			if(width > 417){
				form.removeClass('b');
			}
		}
		else{
			if(width <= 417){
				form.addClass('b');
			}
		}
	});
}

checkFormControlWidth = function(){
	$('.form-control').each(function() {
		var width = $(this).actual('width');
		var labelWidth = $(this).find('label:not(.error) > span').actual('width');
		var errorWidth = $(this).find('label.error').actual('width');
		if(errorWidth > 0){
			if($(this).hasClass('h')){
				if(width > (labelWidth + errorWidth + 5)){
					$(this).removeClass('h');
				}
			}
			else{
				if(width <= (labelWidth + errorWidth + 5)){
					$(this).addClass('h');
				}
			}
		}
		else{
			$(this).removeClass('h');
		}
	});
}

scrollToTop = function(){
	if(arMShopOptions['THEME']['SCROLLTOTOP_TYPE'] !== 'NONE'){
		var _isScrolling = false;
		// Append Button
		$('body').append($('<a />').addClass('scroll-to-top ' + arMShopOptions['THEME']['SCROLLTOTOP_TYPE'] + ' ' + arMShopOptions['THEME']['SCROLLTOTOP_POSITION']).attr({'id': 'scrollToTop'}));
		$('#scrollToTop').click(function(e){
			e.preventDefault();
			$('body, html').animate({scrollTop : 0}, 500);
			return false;
		});
		// Show/Hide Button on Window Scroll event.
		$(window).scroll(function(){
			if(!_isScrolling) {
				_isScrolling = true;
				if($(window).scrollTop() > 150){
					$('#scrollToTop').stop(true, true).addClass('visible');
					_isScrolling = false;
				}
				else{
					$('#scrollToTop').stop(true, true).removeClass('visible');
					_isScrolling = false;
				}
				checkScrollToTop();
			}
		});
	}
}

checkScrollToTop = function(){
	var bottom = 55,
		scrollVal = $(window).scrollTop(),
		windowHeight = $(window).height(),
		footerOffset = $('footer').offset().top +70;

	if(arMShopOptions['THEME']['SCROLLTOTOP_POSITION'] == 'CONTENT'){
		warpperWidth = $('body > .wrapper > .wrapper_inner').width();
		$('#scrollToTop').css('margin-left', Math.ceil(warpperWidth / 2) + 23);
	}

	if(scrollVal + windowHeight > footerOffset){
		$('#scrollToTop').css('bottom', bottom  + scrollVal + windowHeight - footerOffset - 0);
	}
	else if(parseInt($('#scrollToTop').css('bottom')) > bottom){
		$('#scrollToTop').css('bottom', bottom);
	}
}

CheckObjectsSizes = function() {
	$('.container iframe,.container object,.container video').each(function() {
		var height_attr = $(this).attr('height');
		var width_attr = $(this).attr('width');
		if (height_attr && width_attr) {
			$(this).css('height', $(this).outerWidth() * height_attr / width_attr);
		}
	});
}

if(!funcDefined('reloadTopBasket')){
	var reloadTopBasket = function reloadTopBasket(action, basketWindow, speed, delay, slideDown, item){
		var obj={
				"PARAMS": $('#top_basket_params').val(),
				"ACTION": action
			};
		if(typeof item !== "undefined" ){
			obj.delete_top_item='Y';
			obj.delete_top_item_id=item.data('id');
		}
		$.post( arMShopOptions['SITE_DIR']+"ajax/show_basket_popup.php", obj, $.proxy(function( data ){
			$(basketWindow).html(data);
			if(arMShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
				if($(window).outerWidth() > 520){
					if(slideDown=="Y")
						$(basketWindow).find('.basket_popup_wrapp').stop(true,true).slideDown(speed);
					clearTimeout(basketTimeoutSlide);
					basketTimeoutSlide = setTimeout(function() {
						var _this = $('#basket_line').find('.basket_popup_wrapp');
						if (_this.is(':hover')) {
							_this.show();
						}else{
							$('#basket_line').find('.basket_popup_wrapp').slideUp(speed);
						}
					},delay);
				}
			}
		}))
	}
}

$(document).ready(function(){
	scrollToTop();
	$('body').on( 'click', '.captcha_reload', function(e){
		var captcha = $(this).parents('.captcha-row');
		e.preventDefault();
		$.ajax({
			url: arMShopOptions['SITE_DIR'] + 'ajax/captcha.php'
		}).done(function(text){
			captcha.find('input[name=captcha_sid]').val(text);
			captcha.find('img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + text);
			captcha.find('input[name=captcha_word]').val('').removeClass('error');
			captcha.find('.captcha_input').removeClass('error').find('.error').remove();
		});
	});

	$(window).resize(function(){
		checkScrollToTop();
		checkPopupWidth();
		checkCaptchaWidth();
		checkFormWidth();
		checkFormControlWidth();
		touchMenu('ul.menu:not(.opened) > li.menu_item_l1');
		touchBasket('.cart:not(.empty_cart) .basket_block .link');
		CheckObjectsSizes();

		initSly();

		if ($(window).width()<=751) {
			if($('.group_description_block.top').length){
				var top_pos=$('.adaptive_filter').position().top;
				$('.bx_filter.bx_filter_vertical').css({'top':top_pos+20});
			}
		}

		if(resizeEventTimer) {
			clearTimeout(resizeEventTimer);
		}

		resizeEventTimer = setTimeout(function(){
			if($(window).outerWidth()>600){
				$("#header ul.menu").removeClass("opened").css("display", "");

				if($(".authorization-cols").length){
					$('.authorization-cols').equalize({children: '.col .auth-title', reset: true});
					$('.authorization-cols').equalize({children: '.col .form-block', reset: true});
				}
			} else {
				$('.authorization-cols .auth-title').css("height", "");
				$('.authorization-cols .form-block').css("height", "");
			}


			if ($(window).width()>=400) {
				var textWrapper = $(".catalog_block .catalog_item .item-title").height();
				var textContent = $(".catalog_block .catalog_item .item-title a");
				/*$(textContent).each(function(){
					if($(this).outerHeight()>textWrapper) {
						$(this).text(function (index, text) { return text.replace(/\W*\s(\S)*$/, '...'); });
					}
				});*/
				$('.catalog_block').equalize({children: '.catalog_item .cost', reset: true});
				$('.catalog_block').equalize({children: '.catalog_item .item-title', reset: true});
				$('.catalog_block').equalize({children: '.catalog_item', reset: true});
			} else {
				$(".catalog_block .catalog_item").removeAttr("style");
				$(".catalog_block .catalog_item .item-title").removeAttr("style");
				$(".catalog_block .catalog_item .cost").removeAttr("style");
			}

			if($("#basket_form").length && $(window).outerWidth()<=600){
				$("#basket_form .tabs_content.basket > li.cur td").each(function() { $(this).css("width","");});
			}


			if($("#header .catalog_menu").length && $("#header .catalog_menu").css("display")!="none"){
				if($(window).outerWidth()>600){
					try {
						reCalculateMenu();
					} catch (e) {};
				}
			}

			if($(".front_slider_wrapp").length){
				$(".extended_pagination li i").each(function(){
					$(this).css({"borderBottomWidth": ($(this).parent("li").outerHeight()/2), "borderTopWidth": ($(this).parent("li").outerHeight()/2)});
				});
			}

			setHeightCompany();
			$(".bx_filter_section .bx_filter_select_container").each(function(){
				var prop_id=$(this).closest('.bx_filter_parameters_box').attr('property_id');
				if($('#smartFilterDropDown'+prop_id).length){
					$('#smartFilterDropDown'+prop_id).css("max-width", $(this).width());
				}
			})
			$('.specials_slider_wrapp .tabs_content > li.cur, .tab_slider_wrapp .tabs_content > li.cur, .wrapper_block .wr').equalize({children: '.item-title'});
			$('.specials_slider_wrapp .tabs_content > li.cur, .tab_slider_wrapp .tabs_content > li.cur, .wrapper_block .wr').equalize({children: '.item_info'});
			$('.specials_slider_wrapp .tabs_content > li.cur, .tab_slider_wrapp .tabs_content > li.cur, .wrapper_block .wr').equalize({children: '.catalog_item'});
		},
		50, 0);
	});

	setTimeout(function() {
		$(window).resize();
		$(window).scroll();
	}, 400);

	$(".show_props").live('click', function(){
		$(this).next(".props_list_wrapp").stop().slideToggle(333);
		$(this).find("a").toggleClass("opened");
	});

	$('.see_more').live('click', function(e) {
		e.preventDefault();
		var see_more = ($(this).is('.see_more') ? $(this) : $(this).parents('.see_more').first());
		var see_moreText = (see_more.find('> a').length ? see_more.find('> a') : see_more);
		var see_moreHiden = see_more.parent().find('> .d');
		if(see_more.hasClass('open')){
			see_moreText.text(BX.message('CATALOG_VIEW_MORE'));
			see_more.removeClass('open');
			see_moreHiden.hide();
		}
		else{
			see_moreText.text(BX.message('CATALOG_VIEW_LESS'));
			see_more.addClass('open');
			see_moreHiden.show();
		}
		return false;
	});

	$('.avtorization-call.enter').live('click', function(e){
		e.preventDefault();
		$("body").append("<span class='evb-enter' style='display:none;'></span>");
		jqmEd('enter', 'auth', '.evb-enter', '', this);
		$("body .evb-enter").click();
		$("body .evb-enter").remove();
	});

	$('.button.faq_button').click(function(e){
		e.preventDefault();
		$(this).toggleClass('opened');
		$('.faq_ask .form').slideToggle();
	});

	$('.staff.list .staff_section .staff_section_title a').click(function(e) {
		e.preventDefault();
		$(this).parents('.staff_section').toggleClass('opened');
		$(this).parents('.staff_section').find('.staff_section_items').stop().slideToggle(600);
		$(this).parents('.staff_section_title').find('.opener_icon').toggleClass('opened');
	});

	$('.jobs_wrapp .item .name tr').click(function(e) {
		$(this).closest('.item').toggleClass('opened');
		$(this).closest('.item').find('.description_wrapp').stop().slideToggle(600);
		$(this).closest('.item').find('.opener_icon').toggleClass('opened');
	});

	$('.faq.list .item .q a').live('click', function(e){
		e.preventDefault();
		$(this).parents('.item').toggleClass('opened');
		$(this).parents('.item').find('.a').stop().slideToggle();
		$(this).parents('.item').find('.q .opener_icon').toggleClass('opened');
	});

	$('.opener_icon').click(function(e) {
		e.preventDefault();
		$(this).parent().find('a').trigger('click');
	});

	$('.to-order').live('click', function(e){
		e.preventDefault();
		$("body").append("<span class='evb-toorder' style='display:none;'></span>");
		jqmEd('to-order', arMShopOptions['FORM']['TOORDER_FORM_ID'], '.evb-toorder', '', this);
		$("body .evb-toorder").click();
		$("body .evb-toorder").remove();
	});

	$(".counter_block:not(.basket) .plus").live("click", function(){
		
		if(!$(this).parents('.basket_wrapp').length){
			if($(this).parent().data("offers")!="Y"){
				var isDetailSKU2 = $(this).parents('.counter_block_wr').length;
					input = $(this).parents(".counter_block").find("input[type=text]")
					tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('ratio'),
					isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('float_ratio'),
					ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
					max_value='';
					currentValue = input.val();


				if(isDblQuantity)
					ratio = Math.round(ratio*arMShopOptions.JS_ITEM_CLICK.precisionFactor)/arMShopOptions.JS_ITEM_CLICK.precisionFactor;

				curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

				curValue += ratio;
				if (isDblQuantity){
					curValue = Math.round(curValue*arMShopOptions.JS_ITEM_CLICK.precisionFactor)/arMShopOptions.JS_ITEM_CLICK.precisionFactor;
				}
				if(parseFloat($(this).data('max'))>0){
					if(input.val() < $(this).data('max')){
						if(curValue>$(this).data('max')){
							console.log(1);
							input.val($(this).data('max'));
						}else{
							input.val(curValue);
						}
						input.change();
					} else {
						$(this).closest('.counter_block').addClass('maxcount');
						setTimeout(function() {
							$('.maxcount').removeClass('maxcount');
						}, 2500);
					}
				}else{
					input.val(curValue);
					input.change();
				}
			}
		}
	});

	$(".counter_block:not(.basket) .minus").live("click", function(){
		$(this).closest('.counter_block').removeClass('maxcount');
		if(!$(this).parents('.basket_wrapp').length){
			if($(this).parent().data("offers")!="Y"){
				var isDetailSKU2 = $(this).parents('.counter_block_wr').length;
					input = $(this).parents(".counter_block").find("input[type=text]")
					tmp_ratio = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('ratio'),
					isDblQuantity = !isDetailSKU2 ? $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio') : $(this).parents('tr').first().find("td.buy .to-cart").data('float_ratio'),
					ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
					max_value='';
					currentValue = input.val();

				if(isDblQuantity)
					ratio = Math.round(ratio*arMShopOptions.JS_ITEM_CLICK.precisionFactor)/arMShopOptions.JS_ITEM_CLICK.precisionFactor;

				curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

				curValue -= ratio;
				if (isDblQuantity){
					curValue = Math.round(curValue*arMShopOptions.JS_ITEM_CLICK.precisionFactor)/arMShopOptions.JS_ITEM_CLICK.precisionFactor;
				}

				if(parseFloat($(this).parents(".counter_block").find(".plus").data('max'))>0){
					if(currentValue > ratio){
						if(curValue<ratio){
							input.val(ratio);
						}else{
							input.val(curValue);
						}
						input.change();
					}
				}else{
					if(curValue > ratio){
						input.val(curValue);
					}else{
						if(ratio){
							input.val(ratio);
						}else if(currentValue > 1){
							input.val(curValue);
						}
					}
					input.change();
				}
			}
		}
	});

	$('.counter_block input[type=text]').numeric({allow:"."});

	$('.counter_block input[type=text]').live('change', function(e){
		if(!$(this).parents('.basket_wrapp').length){
			var val = $(this).val(),
				tmp_ratio = $(this).parents(".counter_wrapp").find(".to-cart").data('ratio'),
				isDblQuantity = $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio'),
				ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10));

			if(isDblQuantity)
				ratio = Math.round(ratio*arMShopOptions.JS_ITEM_CLICK.precisionFactor)/arMShopOptions.JS_ITEM_CLICK.precisionFactor;

			val -= (val % ratio);

			if(parseFloat($(this).parents(".counter_block").find(".plus").data('max'))>0){
				if(val>parseFloat($(this).parents(".counter_block").find(".plus").data('max'))){
					val=parseFloat($(this).parents(".counter_block").find(".plus").data('max'));
					val -= (val % ratio);
					$(this).closest('.counter_block').addClass('maxcount');
					setTimeout(function() {
						$('.maxcount').removeClass('maxcount');
					}, 2500);
				}
			}
			if(val<ratio){
				val=ratio;
			}else if(!parseFloat(val)){
				val=1;
			}

			$(this).parents('.counter_block').parent().parent().find('.to-cart').attr('data-quantity', val);
			$(this).parents('.counter_block').parent().parent().find('.one_click').attr('data-quantity', val);
			$(this).val(val);
		}
	});

	/*slide cart*/
	//if(arMShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
		$(document).on('mouseenter', '#basket_line .basket_normal:not(.empty_cart):not(.bcart) .basket_block ', function() {
			$(this).closest('.basket_normal').find('.popup').addClass('block');
			$(this).closest('.basket_normal').find('.basket_popup_wrapp').stop(true,true).slideDown(150);
		});
		$(document).on('mouseleave', '#basket_line .basket_normal .basket_block ', function() {
			var th=$(this);
			$(this).closest('.basket_normal').find('.basket_popup_wrapp').stop(true,true).slideUp(150, function(){
				th.closest('.basket_normal').find('.popup').removeClass('block');
			});
		});
	//}

	$(document).on( 'click', '.to-cart:not(.read_more)', function(e){
		e.preventDefault();
		var th=$(this);
		var val = $(this).attr('data-quantity'),
			offer = $(this).data('offers'),
			iblockid = $(this).data('iblockid'),
			tmp_props=$(this).data("props"),
			props='',
			rid='',
			item = $(this).attr('data-item');
		if(!val) $val = 1;
		if(offer!="Y") offer = "N";
		if(tmp_props){
			props=tmp_props.split(";");
		}
		$(this).hide();
		$(this).closest('.counter_wrapp').find('.counter_block').hide();
		$(this).parents('tr').find('.counter_block_wr .counter_block').hide();
		$(this).closest('.button_block').addClass('wide');
		$(this).parent().find('.in-cart').show();
		if($('.rid_item').length){
			rid=$('.rid_item').data('rid');
		}
		$.get( arMShopOptions['SITE_DIR']+"ajax/item.php?item="+item+"&quantity="+val+"&rid="+rid+"&add_item=Y"+"&offers="+offer+"&iblockID="+iblockid+"&props="+JSON.stringify(props),
			$.proxy
			(
				function(){
					addBasketCounter(item);
					$('.wish_item[data-item='+item+']').removeClass("added");
					$('.wish_item[data-item='+item+']').find(".value").show();
					$('.wish_item[data-item='+item+']').find(".value.added").hide();
												$.ajax({
														url: '/ajax/small_basket_update.php',
														type: 'post',
														success: function(data) {
															$('.new_bas_small').html(data);
														}
													});
					if($("#basket_line .basket_fly").length && $(window).outerWidth()>768){
						basketFly('open');
					}
					else if($("#basket_line .cart").length)
					{
						if($("#basket_line .cart").is(".empty_cart"))
						{
							$("#basket_line .cart").removeClass("empty_cart").find(".cart_wrapp a.basket_link").removeAttr("href").addClass("cart-call");
							$("#basket_line .cart").removeClass("ecart");
							touchBasket('.cart:not(.empty_cart) .basket_block .link');
						}

						reloadTopBasket('add', $('#basket_line'), 200, 5000, 'Y');

					}
				}
			)
		);
	})

	$(document).on('click', '.to-subscribe', function(e){
		e.preventDefault();
		if($(this).is('.auth')){
			$('.avtorization-call.enter').click();
		}
		else{
			var item = $(this).attr('data-item');
			$(this).hide();
			$(this).parent().find('.in-subscribe').show();
			$.get(arMShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&subscribe_item=Y',
				$.proxy(function(data){
					$('.wish_item[data-item=' + item + ']').removeClass('added');
					$.getJSON(arMShopOptions['SITE_DIR']+'ajax/get_basket_count.php', function(data){
					});
				})
			);
		}
	})

	$(document).on('click', '.in-subscribe', function(e){
		e.preventDefault();
		var item = $(this).attr('data-item');
		$(this).hide();
		$(this).parent().find('.to-subscribe').show();
		$.get(arMShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&subscribe_item=Y',
			$.proxy(function(data){
				$.getJSON(arMShopOptions['SITE_DIR'] + 'ajax/get_basket_count.php', function(data){
				});
			})
		);
	})

	$(document).on('click', '.wish_item', function(e){
		e.preventDefault();
		var val = $(this).attr('data-quantity'),
			offer = $(this).data('offers'),
			iblockid = $(this).data('iblock'),
			tmp_props=$(this).data("props"),
			props='',
			item = $(this).attr('data-item');
		if(!val) $val = 1;
		if(offer!="Y") offer = "N";
		if(tmp_props){
			props=tmp_props.split(";");
		}

		if(!$(this).hasClass('text')){
			if($(this).hasClass('added')){
				$(this).hide();
				$(this).closest('.wish_item_button').find('.to').show();

				$('.like_icons').each(function(){
					if($(this).find('.wish_item.text[data-item="'+item+'"]').length){
						$(this).find('.wish_item.text[data-item="'+item+'"]').removeClass('added');
						$(this).find('.wish_item.text[data-item="'+item+'"]').find('.value').show();
						$(this).find('.wish_item.text[data-item="'+item+'"]').find('.value.added').hide();
					}
					if($(this).find('.wish_item_button').length){
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').removeClass('added');
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value').show();
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value.added').hide();
					}
				})
			}
			else{
				$(this).hide();
				$(this).closest('.wish_item_button').find('.in').addClass('added').show();

				$('.like_icons').each(function(){
					if($(this).find('.wish_item.text[data-item="'+item+'"]').length){
						$(this).find('.wish_item.text[data-item="'+item+'"]').addClass('added');
						$(this).find('.wish_item.text[data-item="'+item+'"]').find('.value').hide();
						$(this).find('.wish_item.text[data-item="'+item+'"]').find('.value.added').css({"display":"inline-block"})
					}
					if($(this).find('.wish_item_button').length){
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').addClass('added');
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value').hide();
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value.added').show();
					}
				})
			}
		}else{
			if(!$(this).hasClass('added')){
				$('.wish_item[data-item=' + item + ']').addClass('added');
				$('.wish_item[data-item=' + item + ']').find('.value').hide();
				$('.wish_item[data-item=' + item + ']').find('.value.added').css('display','inline-block');

				$('.like_icons').each(function(){
					if($(this).find('.wish_item_button').length){
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').addClass('added');
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value').hide();
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value.added').show();
					}
				})
			}else{
				$('.wish_item[data-item=' + item + ']').removeClass('added');
				$('.wish_item[data-item=' + item + ']').find('.value').show();
				$('.wish_item[data-item=' + item + ']').find('.value.added').hide();

				$('.like_icons').each(function(){
					if($(this).find('.wish_item_button').length){
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').removeClass('added');
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value').show();
						$(this).find('.wish_item_button').find('.wish_item[data-item="'+item+'"]').find('.value.added').hide();
					}
				})
			}
		}

		$('.in-cart[data-item=' + item + ']').hide();
		$('.to-cart[data-item=' + item + ']').parent().removeClass('wide');
		$('.to-cart[data-item=' + item + ']').show();
		$('.counter_block[data-item=' + item + ']').show();
		if(!$(this).closest('.module-cart').size()){
			$.get( arMShopOptions['SITE_DIR']+"ajax/item.php?item="+item+"&quantity="+val+"&wish_item=Y"+"&offers="+offer+"&iblockID="+iblockid+"&props="+JSON.stringify(props),
				$.proxy(function(data){
					if($('.basket_fly').size()){
						basketFly('wish');
					}else{
						reloadTopBasket('wish', $('#basket_line'), 200, 5000, 'N');
					}
				})
			);
		}
	})

	// $('.compare_item').live('click', function(e){
	$(document).on('click', '.compare_item', function(e){
		e.preventDefault();
		var item = $(this).attr('data-item');
		var iblockID = $(this).attr('data-iblock');
		if(!$(this).hasClass('text')){
			if($(this).hasClass('added')){
				$(this).hide();
				$(this).closest('.compare_item_button').find('.to').show();

				/*sync other button*/
				$('.like_icons').each(function(){
					if($(this).find('.compare_item.text[data-item="'+item+'"]').length){
						$(this).find('.compare_item.text[data-item="'+item+'"]').removeClass('added');
						$(this).find('.compare_item.text[data-item="'+item+'"]').find('.value').show();
						$(this).find('.compare_item.text[data-item="'+item+'"]').find('.value.added').hide();
					}
					if($(this).find('.compare_item_button').length){
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').removeClass('added');
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value').show();
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value.added').hide();
					}
				})
			}
			else{
				$(this).hide();
				$(this).closest('.compare_item_button').find('.in').show();

				/*sync other button*/
				$('.like_icons').each(function(){
					if($(this).find('.compare_item.text[data-item="'+item+'"]').length){
						$(this).find('.compare_item.text[data-item="'+item+'"]').addClass('added');;
						$(this).find('.compare_item.text[data-item="'+item+'"]').find('.value').hide();
						$(this).find('.compare_item.text[data-item="'+item+'"]').find('.value.added').css({"display":"inline-block"});
					}
					if($(this).find('.compare_item_button').length){
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').addClass('added');
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value.added').show();
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value').hide();
					}
				})
			}
		}else{
			if(!$(this).hasClass('added')){
				$('.compare_item[data-item=' + item + ']').addClass('added');
				$('.compare_item[data-item=' + item + ']').find('.value').hide();
				$('.compare_item[data-item=' + item + ']').find('.value.added').css('display','inline-block');

				/*sync other button*/
				$('.like_icons').each(function(){
					if($(this).find('.compare_item_button').length){
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').addClass('added');
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value.added').show();
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value').hide();
					}
				})
			}else{
				$('.compare_item[data-item=' + item + ']').removeClass('added');
				$('.compare_item[data-item=' + item + ']').find('.value').show();
				$('.compare_item[data-item=' + item + ']').find('.value.added').hide();

				/*sync other button*/
				$('.like_icons').each(function(){
					if($(this).find('.compare_item_button').length){
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').removeClass('added');
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value').show();
						$(this).find('.compare_item_button').find('.compare_item[data-item="'+item+'"]').find('.value.added').hide();
					}
				})
			}
		}

		$.get(arMShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&compare_item=Y&iblock_id=' + iblockID,
			$.proxy(function(data){
				jsAjaxUtil.InsertDataToNode(arMShopOptions['SITE_DIR'] + 'ajax/show_compare_preview_top.php', 'compare_line', false);
			})
		);
	});

	$('.fancy').fancybox({
		openEffect  : 'fade',
		closeEffect : 'fade',
		nextEffect : 'fade',
		prevEffect : 'fade',
		tpl:{
			closeBtn : '<a title="'+BX.message('FANCY_CLOSE')+'" class="fancybox-item fancybox-close" href="javascript:;"></a>',
			next     : '<a title="'+BX.message('FANCY_NEXT')+'" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
			prev     : '<a title="'+BX.message('FANCY_PREV')+'" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
		},
	});

	/*search click*/
	$('.search_block .icon').on('click', function(){
		var th=$(this);
		if($(this).hasClass('open')){
			$(this).closest('.center_block').find('.search_middle_block').removeClass('active');
			$(this).removeClass('open');
			$(this).closest('.center_block').find('.search_middle_block').find('.noborder').hide();
		}else{
			setTimeout(function(){
				th.closest('.center_block').find('.search_middle_block').find('.noborder').show();
			},100);
			$(this).closest('.center_block').find('.search_middle_block').addClass('active');
			$(this).addClass('open');
		}
	})
	$(document).on('mouseenter', '.display_list .item_wrap', function(){
		$(this).prev().addClass('prev');
	});
	$(document).on('mouseleave', '.display_list .item_wrap', function(){
		$(this).prev().removeClass('prev');
	});
	$(document).on('mouseenter', '.catalog_block .item_wrap', function(){
		$(this).addClass('shadow_delay');
	});
	$(document).on('mouseleave', '.catalog_block .item_wrap', function(){
		$(this).removeClass('shadow_delay');
	});
	$(document).on('click', '.no_goods .button', function(){
		$('.bx_filter .smartfilter .bx_filter_search_reset').trigger('click');
	});

	$(document).on('click', '.more_text_ajax', function(){
		var url=$(this).closest('.right_block').find('.module-pagination .flex-direction-nav .flex-next').attr('href'),
			th=$(this);
		th.addClass('loading');		
		$.ajax({
			url: url,
			data: {"ajax_get": "Y"},
			success: function(html){
				var new_html=$.parseHTML(html);
				th.removeClass('loading');
				if($('.display_list').length){
					//$('.display_list').append($(new_html).find('.display_list').html());
					$('.display_list').append(html);
				}else if($('.catalog_block').length){
					$('.catalog_block').append(html);
					touchItemBlock('.catalog_item a');
					$('.catalog_block').ready(function()
					{
						$('.catalog_block').equalize({children: '.catalog_item .cost', reset: true});
						$('.catalog_block').equalize({children: '.catalog_item .item-title', reset: true});
						$('.catalog_block').equalize({children: '.catalog_item .counter_block', reset: true});
						$('.catalog_block').equalize({children: '.catalog_item_wrapp', reset: true});
					})
				}else if($('.module_products_list').length){
					$('.module_products_list tbody').append(html);
				}
				setStatusButton();
				BX.onCustomEvent('onAjaxSuccess');
				$('.bottom_nav').html($(html).find('.bottom_nav').html());
			}
		})
	})

	$(document).on('click', '.bx_compare .tabs-head li', function(){
		var url=$(this).find('.sortbutton').data('href');
		BX.showWait(BX("bx_catalog_compare_block"));
		$.ajax({
			url: url,
			data: {'ajax_action': 'Y'},
			success: function(html){
				history.pushState(null, null, url);
				$('#bx_catalog_compare_block').html(html);
				BX.closeWait();
			}
		})
	})
	var hoveredTrs;
	$(document).on({
		mouseover: function(e){
			var _ = $(this);
			var tbodyIndex = _.closest('tbody').index()+1; //+1 for nth-child
			var trIndex = _.index()+1; // +1 for nth-child
			hoveredTrs = $(e.delegateTarget).find('.data_table_props')
				.children(':nth-child('+tbodyIndex+')')
				.children(':nth-child('+trIndex+')').addClass('hovered');
		},
		mouseleave: function(e){
			if(hoveredTrs)
				hoveredTrs.removeClass('hovered');
		}
	}, '.bx_compare .data_table_props tbody>tr');
	$(document).on('click', '.fancy_offer', function(e){
		e.preventDefault();
		var arPict=[];
		$('.sliders[data-id='+$(this).data('id')+'] li').each(function(){
			if($(this).hasClass('current')){
				arPict.unshift($(this).data('big'));
			}else{
				arPict.push($(this).data('big'));
			}
		})
		$.fancybox(arPict, {
			openEffect  : 'fade',
			closeEffect : 'fade',
			nextEffect : 'fade',
			prevEffect : 'fade',
			//'type'              : 'image',
			tpl:{
				closeBtn : '<a title="'+BX.message('FANCY_CLOSE')+'" class="fancybox-item fancybox-close" href="javascript:;"></a>',
				next     : '<a title="'+BX.message('FANCY_NEXT')+'" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
				prev     : '<a title="'+BX.message('FANCY_PREV')+'" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
			},
		});
	})

	/*tabs*/
	$(".tabs_section .tabs-head li").live("click", function(){
		if(!$(this).is(".current")){
			$(".tabs_section .tabs-head li").removeClass("current");
			$(this).addClass("current");
			$(".tabs_section ul.tabs_content li").removeClass("current");
			if($(this).attr("id") == "product_reviews_tab"){
				$(".shadow.common").hide(); $("#reviews_content").show();
			}
			else{
				$(".shadow.common").show();
				$("#reviews_content").hide();
				$(".tabs_section ul.tabs_content > li:eq("+$(this).index()+")").addClass("current");
			}
		}
	});
	/*open first section slide*/
	setTimeout(function() {
		$('.jobs_wrapp .item:first .name tr').trigger('click');
	}, 300);

	$('.buy_block .slide_offer').on('click', function(){
		scroll_block($('.tabs_section'));
	});
	$('.share_wrapp .text').on('click', function(){
		$(this).parent().find('.shares').fadeToggle();
	})
	$('html, body').live('mousedown', function(e) {
		e.stopPropagation();
		$('.shares').fadeOut();
		$('.search_middle_block').removeClass('active_wide');
	})
	$('.share_wrapp').find('*').live('mousedown', function(e) {
		e.stopPropagation();
	});
	$(document).on('click', '.reviews-collapse-link', function(){
		$('.reviews-reply-form').slideToggle();
	})
	//$('.form_mobile_block .search_middle_block').html($('.main-nav .search_middle_block').html());

	/*touch event*/
	document.addEventListener('touchend', function(event) {
		if(!$(event.target).closest('.menu_item_l1').length){
			$('.child').css({'display':'none'});
			$('.menu_item_l1').removeClass('hover');
		}
		if(!$(event.target).closest('.basket_block').length){
			$('.basket_block .link').removeClass('hover');
			$('.basket_block .basket_popup_wrapp').slideUp();
		}
		if(!$(event.target).closest('.catalog_item').length){
			var tabsContentUnhoverHover = $('.tab:visible').attr('data-unhover') * 1;
			$('.tab:visible').stop().animate({'height': tabsContentUnhoverHover}, 100);
			$('.tab:visible').find('.catalog_item').removeClass('hover');
			$('.tab:visible').find('.catalog_item .buttons_block').stop().fadeOut(233);
			if($('.catalog_block').length){
				$('.catalog_block').find('.catalog_item').removeClass('hover');
				//$('.catalog_block').find('.catalog_item').blur();
			}
		}
	}, false);
	//touchItemBlock('.catalog_item a');
	$(document).on('keyup', '.coupon .input_coupon input', function(){
		if($(this).val().length){
			$(this).removeClass('error');
			$(this).closest('.input_coupon').find('.error').remove();
		}else{
			$(this).addClass('error');
			$("<label class='error'>"+BX.message("INPUT_COUPON")+"</label>").insertBefore($(this));
		}
	})
	BX.addCustomEvent(window, "onAjaxSuccess", function(){
		initSelects(document);
	});
	BX.addCustomEvent(window, "onFrameDataRequestFail", function(response){
		console.log(response);
	});
});
function touchMenu(selector){
	if($(window).outerWidth()>600){
		$(selector).each(function(){
			var th=$(this);
			th.on('touchend', function(e) {
				if (th.find('.child').length && !th.hasClass('hover')) {
					e.preventDefault();
					e.stopPropagation();
					th.siblings().removeClass('hover');
					th.addClass('hover');
					$('.child').css({'display':'none'});
					th.find('.child').css({'display':'block'});
				}
			})
		})
	}else{
		$(selector).off();
	}
}
function touchItemBlock(selector){
	$(selector).each(function(){
		var th=$(this),
			item=th.closest('.catalog_item');
		th.on('touchend', function(e) {
			if (!item.hasClass('hover')) {
				e.preventDefault();
				e.stopPropagation();
				item.siblings().removeClass('hover');
				item.siblings().blur();
				item.closest('.catalog_block').find('.catalog_item').removeClass('hover');
				item.addClass('hover');
				item.addClass('touch');

				var tabsContentHover = th.closest('.tab').attr('data-hover') * 1,
					tabsContentUnhoverHover = th.closest('.tab').attr('data-unhover') * 1;

				th.closest('.tab').stop().animate({'height': tabsContentUnhoverHover}, 100);
				th.closest('.catalog_item').siblings().find('.buttons_block').stop().fadeOut(233)

				th.closest('.tab').fadeTo(100, 1);
				th.closest('.tab').stop().css({'height': tabsContentHover});
				th.closest('.catalog_item').find('.buttons_block').fadeIn(450, 'easeOutCirc');
			}
		})
	})
}
function touchBasket(selector){
	if(arMShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
		if($(window).outerWidth()>600){
			$(document).find(selector).on('touchend', function(e) {
				if ($(this).parent().find('.basket_popup_wrapp').length && !$(this).hasClass('hover')) {
					e.preventDefault();
					e.stopPropagation();
					$(this).addClass('hover');
					$(this).parent().find('.basket_popup_wrapp').slideDown();
				}
			})
		}else{
			$(selector).off();
		}
	}
}
function initFull(){
	initSelects(document);
	initHoverBlock(document);
	$(window).resize();
	touchItemBlock('.catalog_item a');
}

var isFrameDataReceived = false;
if (typeof window.frameCacheVars !== "undefined")
{
	BX.addCustomEvent("onFrameDataReceived", function (json){
		initFull();
		isFrameDataReceived = true;
	});
} else {
	$( document ).ready(initFull);
}