<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление");
?>
<h2>Заголовок H2</h2>
<p>Интернет-магазин — сайт, торгующий товарами в интернете. Позволяет пользователям сформировать заказ на покупку, выбрать способ оплаты и доставки заказа в сети Интернет.&nbsp;</p>
<blockquote>Отслеживание ведется с помощью методов веб-аналитики. Часто при оформлении заказа предусматривается возможность сообщить некоторые дополнительные пожелания от покупателя продавцу. 	</blockquote> 
<h3>Заголовок H3</h3>
<p><i>Однако, в этом случае следует быть осторожным, поскольку доказать неполучение товара электронным способом существенно сложнее, чем в случае физической доставки.</i></p>
<h4>Маркированный список H4</h4>
<ul>
	<li>В интернет-магазинах, рассчитанных на повторные покупки, также ведется отслеживание возвратов песетителя и история покупок.</li>
	<li>Кроме того, существуют сайты, в которых заказ принимается по телефону, электронной почте, Jabber или ICQ.</li>
</ul>
<h5>Нумерованный список H5</h5>
<ol>
	<li>В интернет-магазинах, рассчитанных на повторные покупки, также ведется отслеживание возвратов песетителя и история покупок.</li>
	<li>Кроме того, существуют сайты, в которых заказ принимается по телефону, электронной почте, Jabber или ICQ.</li>
</ol>
<hr class="long"/>
<h5>Таблица</h5>
<table class="colored_table">
	<thead>
		<tr>
			<td>#</td>
			<td>First Name</td>
			<td>Last Name</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>Tim</td>
			<td>Tors</td>
		</tr>
		<tr>
			<td>2</td>
			<td>Denis</td>
			<td>Loner</td>
		</tr>
	</tbody>
</table>
<hr class="long"/>
<div class="sale_block">
	<div class="value">-10%</div>
	<div class="text">Экономия 100 р.</div>
	<div class="clearfix"></div>
</div>
<div class="view_sale_block">
	<div class="count_d_block">
		<span class="active_to_block hidden">30.10.2017</span>
		<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
		<span class="countdown countdown_block values"></span>
		<script>
			$(document).ready(function(){
				if( $('.countdown').size() ){
					var active_to = $('.active_to_block').text(),
					date_to = new Date(active_to.replace(/(\d+)\.(\d+)\.(\d+)/, '$3/$2/$1'));
					$('.countdown_block').countdown({until: date_to, format: 'dHMS', padZeroes: true, layout: '{d<}<span class="days item">{dnn}<div class="text">{dl}</div></span>{d>} <span class="hours item">{hnn}<div class="text">{hl}</div></span> <span class="minutes item">{mnn}<div class="text">{ml}</div></span> <span class="sec item">{snn}<div class="text">{sl}</div></span>'}, $.countdown.regionalOptions['ru']);
				}
			})
		</script>
	</div>
	<div class="quantity_block">
		<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
		<div class="values">
			<span class="item">
				<?=(int)$totalCount;?>
				<div class="text"><?=GetMessage("TITLE_QUANTITY");?></div>
			</span>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>