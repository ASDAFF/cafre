<?if(!empty($arResult['SECTIONS'])):
$cache = new CPHPCache();
$cache_time = 3600000;
$cache_path = 'brand_Active';
$cache_id = 'top';
$active=array();
if($cache->InitCache($cache_time, $cache_id, $cache_path)){
	$res = $cache->GetVars();
	$active = $res["arResult"];
}
else{
	$res=CIblockElement::GetList(array(), array('IBLOCK_ID'=>26, 'ACTIVE'=>'Y'), array('PROPERTY_CATALOG_BREND'), false);
	while($ob=$res->GetNext()) {
		if($ob['PROPERTY_CATALOG_BREND_VALUE']) $active[]=$ob['PROPERTY_CATALOG_BREND_VALUE'];
	}	
	$cache->StartDataCache( $cache_time, $cache_id, $cache_path );
	$cache->EndDataCache( 
		array(
			"arResult" => $active,
		) 
	);
}
foreach($arResult['SECTIONS'] as $key => $arItem):
	$alpha=substr($arItem["NAME"], 0, 1);	
	if(in_array($arItem['ID'], $active) || $arItem['ELEMENT_CNT']>0) {
		$arResult['alpha'][(!preg_replace('|[^a-z]*|i', '',$alpha))?'а':$alpha][]=$arItem;
		$arResult['all_alpha'][$arItem['NAME']]=$arItem;
	}
endforeach;
ksort($arResult['alpha']);
ksort($arResult['all_alpha']);
$arResult['alpha']
?>
<span>Ѕренды:</span>
<div class="bbrands__alph">
	<ul class="bbrands__alph-list">
		<?foreach($arResult['alpha'] as $k => $mass) {?>
			<li>
				<a><?=$k=='а'?"#":$k?></a>
				<div class="bbrands__drop">
					<ul>
						<?foreach($mass as $arSec) {?>
							<li><a href="<?=$arSec['SECTION_PAGE_URL']?>"><?=$arSec['NAME']?></a></li>
						<?}?>
					</ul>
				</div>
			</li>
		<?}?>
		<li class="all-brand-menu-lr">
			<a href="/catalog/vse_brendy/" class="bbrands__alph-all">все бренды</a>
			<div class="bbrands__drop">
				<ul>	
				<?foreach($arResult['all_alpha'] as $arSec) {?>
							<li><a href="<?=$arSec['SECTION_PAGE_URL']?>"><?=$arSec['NAME']?></a></li>
						<?}?>
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
<?endif;?>