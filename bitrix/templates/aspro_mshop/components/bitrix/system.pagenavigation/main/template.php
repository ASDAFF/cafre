<?$this->setFrameMode(true);?>
<?if($arResult["NavPageCount"] > 1):?>
	<?
	$count_item = 2;
	$arResult["nStartPage"] = $arResult["NavPageNomer"] - $count_item;
	$arResult["nStartPage"] = $arResult["nStartPage"] <= 0 ? 1 : $arResult["nStartPage"];
	$arResult["nEndPage"] = $arResult["NavPageNomer"] + $count_item;
	$arResult["nEndPage"] = $arResult["nEndPage"] > $arResult["NavPageCount"] ? $arResult["NavPageCount"] : $arResult["nEndPage"];
	$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
	$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
	if($arResult["NavPageNomer"] == 1){
		$bPrevDisabled = true;
	}
	elseif($arResult["NavPageNomer"] < $arResult["NavPageCount"]){
		$bPrevDisabled = false;
	}
	if($arResult["NavPageNomer"] == $arResult["NavPageCount"]){
		$bNextDisabled = true;
	}
	else{
		$bNextDisabled = false;
	}
	?>
	<?if(!$bNextDisabled){?>
		<div class="ajax_load_btn">
			<span class="more_text_ajax"><?=GetMessage('PAGER_SHOW_MORE')?></span>
		</div>
	<?}?>
	<div class="module-pagination">
		<ul class="flex-direction-nav" style="display:none;">
			<li class="flex-nav-prev <?if($bPrevDisabled){echo " disabled";}?>"><a data-href="<?=$arResult["sUrlPath"];?>?<?=$strNavQueryString;?>PAGEN_<?=$arResult["NavNum"];?>=<?=($arResult["NavPageNomer"]-1)?>" class="flex-prev"></a></li>
			<li class="flex-nav-next <?if($bNextDisabled){echo " disabled";}?>"><a data-href="<?=$arResult["sUrlPath"];?>?<?=$strNavQueryString;?>PAGEN_<?=$arResult["NavNum"];?>=<?=($arResult["NavPageNomer"]+1)?>" class="flex-next"></a></li>
		</ul>
		<span class="nums">
			<?if($arResult["nStartPage"] > 1):?>
				<a href="#" data-href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a>
				<span class='point_sep'></span>
			<?endif;?>
			<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
				<?if($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
					<span class="cur"><?=$arResult["nStartPage"]?></span>
				<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
					<a href="#" data-href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
				<?else:?>
					<a href="#" data-href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
				<?endif;?>
				<?$arResult["nStartPage"]++;?>
			<?endwhile;?>
			<?if($arResult["nEndPage"] < $arResult["NavPageCount"]):?>
				<span class='point_sep'></span>
				<a href="#" data-href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
			<?endif;?>
		</span>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".module-pagination span.nums a").live("click", function(e){
				e.preventDefault();
				var url=$(this).data('href');	
				BX.showWait();
				$.ajax({
					url: url,
					data: {"ajax_get": "Y"},
					success: function(html){
						var new_html=$.parseHTML(html);		
							$('.catalog_block').html(html);
							touchItemBlock('.catalog_item a');
							$('.catalog_block').ready(function() {
								$('.catalog_block').equalize({children: '.catalog_item .cost', reset: true});
								$('.catalog_block').equalize({children: '.catalog_item .item-title', reset: true});
								$('.catalog_block').equalize({children: '.catalog_item .counter_block', reset: true});
								$('.catalog_block').equalize({children: '.catalog_item_wrapp', reset: true});
							});						
						setStatusButton();
						BX.onCustomEvent('onAjaxSuccess');
						$('.bottom_nav').html($(html).find('.bottom_nav').html());
						var tops=$('.container>.breadcrumb+h1').offset();
						$('html, body').animate({scrollTop: tops.top}, 450);
						BX.closeWait();
					}
				});	
				if(!$(this).is(".cur")){
					$(".module-pagination span.nums a, .module-pagination span.nums span").removeClass("cur");
					$(this).addClass("cur");
				}		
				return false;
			});
			/*$(".module-pagination .next").live("click", function(){
				if(!$(this).is(".disabled")){
					element = $(".module-pagination span.nums a.cur");
					$(".module-pagination span.nums a").removeClass("cur");
					element.next("span.nums a").addClass("cur");
				}
			});
			$(".module-pagination .prev").live("click", function(){
				if(!$(this).is(".disabled")){
					element = $(".module-pagination span.nums a.cur");
					$(".module-pagination span.nums a").removeClass("cur");
					element.prev("span.nums a").addClass("cur");
				}
			});*/
		});
	</script>
<?endif;?>