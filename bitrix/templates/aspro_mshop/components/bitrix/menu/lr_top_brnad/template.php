<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetAdditionalCSS("/include/new_menu.css");
$this->setFrameMode(true);
$numSubLevel = -1;
$numSubLevel3 = -1;
$prevLevel = 0;
$subSubArray = array();
$subSubArray2 = array();
$brend=false;
$brend_list=array();
?>
<span>Бренды:</span>
	<div class="bbrands__alph">
			<ul class="bbrands__alph-list">
		<?
		foreach($arResult as $key => $arItem):
		if(!strripos($arItem["LINK"], "vse_brendy"))continue;
		
		?> 
				<?if($arItem["DEPTH_LEVEL"]==2){
				$alf_brand[]=substr($arItem["TEXT"], 0, 1);
				}?>
						
			<?if($arItem["IS_PARENT"]):?>
					<?if(strpos($arItem["LINK"], 'vse_bren')===false) {?>
							<?} else {
								$brend=true;
								$db_sec = CIBlockSection::GetList(Array(), Array('NAME'=>$arItem['TEXT'], 'GLOBAL_ACTIVE'=>'Y'), false, array('ID', 'IBLOCK_ID'));																
								if($sec_result = $db_sec->GetNext())
								{
									$arFilter = Array('IBLOCK_ID'=>$sec_result['IBLOCK_ID'], 'SECTION_ID'=>$sec_result['ID'], 'GLOBAL_ACTIVE'=>'Y');
									$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, false, array('ID', 'NAME', 'IBLOCK_ID', 'UF_IMG_BRAND'));																
									while($ar_result = $db_list->GetNext())
									{
										$brend_pic[$ar_result['NAME']]=$ar_result['UF_IMG_BRAND'];
									}
								} 
							} ?>
				<?endif;?>
	
		<?if($arItem["DEPTH_LEVEL"]==2&&$brend):
					$brend_list[substr($arItem["TEXT"], 0, 1)][]='<li><a href="'.$arItem["LINK"].'">'.$arItem["TEXT"].'</a></li>';
			endif;?>
		
			<?if(is_integer($key)) $prevLevel = $arItem["DEPTH_LEVEL"];?>	
			
		<?endforeach;?>

		<?$result_alf = array_unique($alf_brand);?>
				<?sort($result_alf);?>
				<?foreach($result_alf as $alfen):?>
				<?if(!preg_replace('|[^a-z]*|i', '',$alfen))continue;?>
								<li><a><?=$alfen?></a>
						<div class="bbrands__drop">
								<ul>
									<?foreach($brend_list as $first_b => $fulbr){?>
										<?if($first_b == $alfen){?>
										<?foreach($fulbr as $v):?>
										<?echo $v;?>
										<?endforeach;?>
									<?}}?>
								</ul>
								</div>
						</li>	
<?endforeach;?>	
<li>
					<a>#</a>
					<div class="bbrands__drop">
						<ul>
						<?foreach($result_alf as $alfen):?>
						<?if(preg_replace('|[^a-z]*|i', '',$alfen))continue;?>
						<?foreach($brend_list as $first_b => $fulbr){?>
						<?if($first_b == $alfen){?>
							<?foreach($fulbr as $v):?>
										<?echo $v;?>
										<?endforeach;?>
							<?}}?>
						<?endforeach;?>		
						</ul>
					</div>
</li>
<li class="all-brand-menu-lr">
					<a href="/catalog/vse_brendy/" class="bbrands__alph-all">Все бренды</a>
					<div class="bbrands__drop">
						<ul>
						<?foreach($result_alf as $alfen):?>
						<?foreach($brend_list as $first_b => $fulbr){?>
						<?if($first_b == $alfen){?>
							<?foreach($fulbr as $v):?>
										<?echo $v;?>
										<?endforeach;?>
							<?}}?>
						<?endforeach;?>		
						</ul>
					</div>
</li>
	</ul>	
	
	</div>
<script>

	$(function() {
		function scrollMenu(menu) {
			var flag = $(menu).hasClass('scroll');
			
			if (flag) return;
			
			var children = $(menu).children().length;
			
			if (children > 5) {
				$(menu).addClass('scroll').jScrollPane();
			} else {
				return
			}
		}
		
		$('.menu__item').hover(function() {
			$(this).find('.active').removeClass('active');
			$(this).find('.menu__item-lvl_1 li[data-lvl]').first().addClass('active');
			$(this).find('.menu__item-lvl_2 ul').first().addClass('active');
			
			scrollMenu($(this).find('.menu__item-lvl_1 > ul'));
			scrollMenu($(this).find('.menu__item-lvl_2 ul').first());
			//$(this).find('.menu__item-lvl_1 > ul').jScrollPane();
		});

		$('.menu__item-lvl li[data-lvl] > a').hover(function() {
			var drop = $(this).closest('.menu__drop'),
				lvl = $(this).parent().data('lvl'),
				item = $(this).closest('.menu__item-lvl').attr('class'),
				itemLvl = Number(item[item.length-1]),
				menu = $(drop).find('.menu__item-lvl_'+(itemLvl+1)+' ul[data-lvl="'+lvl+'"]');

			if (itemLvl === 1) {
				$(drop).find('.active').removeClass('active');
				$('.menu__item-lvl_3').hide();
			} else {
				$(drop).find('.menu__item-lvl_'+(itemLvl+1)+' ul.active').removeClass('active')
				$(drop).find('.menu__item-lvl_'+(itemLvl)+' li.active').removeClass('active')
				$('.menu__item-lvl_3').show();
			}
			
			$(this).parent().addClass('active');
			$(menu).addClass('active');
			
			scrollMenu(menu);
		});

		function brands () {

			var active = $('.brnds__alph a.cur').text();

			$('.brnds__caption').empty().append('<span>'+active+'</span>');

			$('.brnds__alph a').on('click', function(e) {
				e.preventDefault();

				$('.brnds__list').removeClass('brnds__list_big');

				var letter = $(this).text().toLowerCase(),
					counter = 0;

				$('.brnds__caption').empty().append('<span>'+letter+'</span>');

				$('.brnds__top a.cur').removeClass('cur');
				$(this).addClass('cur');

				$('.brnds__list .brnd').addClass('hide');
				$('.brnds__list .brnd span').each(function(i, title) {
					if ($(title).text()[0].toLowerCase() === letter) {
						$(title).closest('.brnd').removeClass('hide');
						counter++;
					}
				});

				if (counter <= 6) {
					$('.brnds__list').addClass('brnds__list_big');
				}

				// $('.brnds__list').append($('.brnd.hide'));
			})

			$('.brnds__all').on('click', function(e) {
				e.preventDefault();

				$('.brnds__alph a.cur').removeClass('cur');
				$(this).addClass('cur');
				$('.brnds__caption').empty().append('<span>'+$(this).text()+'</span>')

				$('.brnds__list').removeClass('brnds__list_big');
				$('.brnds__list .brnd').removeClass('hide');
			});

			if ($('.brnds__list .brnd').length <= 6) {
				$('.brnds__list').addClass('brnds__list_big');
			}

			$('.brnds__all').trigger('click');
		};

		brands();


	});

</script>