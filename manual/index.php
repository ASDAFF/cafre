<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
	<style>

	@font-face {
		font-family: 'Futura';
		src: url('fonts/FuturaPT-Bold.eot');
		src: url('fonts/FuturaPT-Bold.eot?#iefix') format('embedded-opentype'),
			url('fonts/FuturaPT-Bold.woff') format('woff'),
			url('fonts/FuturaPT-Bold.ttf') format('truetype');
		font-weight: bold;
		font-style: normal;
	}

	@font-face {
		font-family: 'Futura';
		src: url('fonts/FuturaPT-Demi.eot');
		src: url('fonts/FuturaPT-Demi.eot?#iefix') format('embedded-opentype'),
			url('fonts/FuturaPT-Demi.woff') format('woff'),
			url('fonts/FuturaPT-Demi.ttf') format('truetype');
		font-weight: 600;
		font-style: normal;
	}

	@font-face {
		font-family: 'Futura';
		src: url('fonts/FuturaPT-Book.eot');
		src: url('fonts/FuturaPT-Book.eot?#iefix') format('embedded-opentype'),
			url('fonts/FuturaPT-Book.woff') format('woff'),
			url('fonts/FuturaPT-Book.ttf') format('truetype');
		font-weight: normal;
		font-style: normal;
	}

	body {
		font-family: 'Futura', sans-serif;
	}

	.manual {
		display: inline-block;
		vertical-align: top;
		padding: 50px 0;
		margin: 0;
		max-width: 902px;
		width: 75%;
	}

	.manual__title {
		display: block;
		padding: 10px 0;
		border-bottom: 2px solid currentColor;
		text-align: center;
		font-size: 32px;
		margin-bottom: 30px;
	}

	.manual__caption {
		display: block;
		text-decoration: underline;
		margin-bottom: 14px;
		font-size: 24px;
	}

	.manual__block {
		margin-bottom: 50px;
	}

	.manual__line {
		padding: 20px;
		margin-bottom: 30px;
		box-shadow: 0 3px 10px -1px rgba(0,0,0,.1);
	}
	
	.manual pre {
		display: block;
		width: 500px;
		margin: 20px 0;
		background: rgba(0,0,0,.01);
		padding: 10px;
		border: 1px dashed black;
	}

	.container strong {
		font-family: inherit;
    	font-weight: inherit;
		color: #EB5470;
	}

	.manual ul {
		margin: 20px 0 24px 10px;
	}

	.manual ul > li {
	    font-family: 'Merriweather', sans-serif;
	    font-size: 14px;
	    color: black;
	    line-height: 24px;
	    margin-bottom: 14px;
	}

	.manual ul > li::before {
	    font-size: 6px;
	    position: relative;
	    top: -3px;
	    margin-right: 15px;
	    color: black;
	}

	ol {
		margin: 20px 0 24px 10px;
	}

	ol > li {
	    font-family: 'Merriweather', sans-serif;
	    font-size: 14px;
	    color: black;
	    list-style: none;
	    line-height: 24px;
	    margin-bottom: 20px;
	    counter-reset: item;
	    counter-increment: item;
	}

	ol > li::before {
		content: counter(item);
		margin-right: 15px;
	}

	.manual a {
		font-family: 'Merriweather', sans-serif;
		font-size: 14px;
		color: #4A90E2;
		text-decoration: underline;
	}

	.manual-content {
		display: inline-block;
		vertical-align: top;
	    margin-top: 63px;
	    display: inline-block;
	    width: 205px;
	    padding: 30px 10px 50px 20px;
	    box-sizing: border-box;
	    background: #ecf5ff;
	    border-radius: 4px;
	    counter-reset: span;
	    margin-left: 6%;
	}

	.manual-content ul {
		margin: 0;
		padding: 0;
		list-style: none;
	}

	.manual-content ul > li::before {
		position: absolute;
	    left: 0;
	    top: 0;
	    content: counter(span)'.';
	    counter-increment: span;
	}

	.manual-content ul > li {
		position: relative;
	    display: block;
	    padding-left: 20px;
	    font-family: "Merriweather", sans-serif;
	    font-size: 16px;
	    line-height: 1.71;
	}

	.manual-content ul > li span {
	    color: black;
	    text-decoration: underline;
	    cursor: pointer;
	}

	.manual-content ul > li span:hover {
		text-decoration: none;
	}
	
	img {
		max-width: 100%;
	}
	
	dl dd {
		margin-left: 20px;
		margin-bottom: 20px;
		margin-top: 7px;
		color: #0a0a0a;
		background: #ecf5ff;
		line-height: 1.1;
		padding: 15px;
		border-radius: 5px;
	}
	
	dl dt {
		font-weight: bold;
		color: #000;
	}
	
	blokquote {
		border-radius: 0px 10px 10px 10px;
		background: #ecf5ff;
		display: block;
		padding: 15px;
		margin-bottom: 19px;
		color: #EB5470;
		font-style: italic;
	}

</style>
	

<div class="wrapper_inner">
	<div class="container">
		<div id="content">
			<div class="manual">
				<div class="manual__block">
					<span class="manual__title">Заголовки</span>
					<div class="manual__line">
						<h1>H1 Заголовок</h1>
<pre>
<h1>H1 Заголовок</h1>	
</pre>
					</div>
					<div class="manual__line">
						<h2>H2 Заголовок</h2>
<pre>
<h2>H2 Заголовок</h2>	
</pre>
					</div>
					<div class="manual__line">
						<h3>H3 Заголовок</h3>
<pre>
<h3>H3 Заголовок</h3>	
</pre>
					</div>
					<div class="manual__line">
						<h4>H4 Заголовок</h4>
<pre>
<h4>H4 Заголовок</h4>	
</pre>
					</div>
					<div class="manual__line">
						<h5>H5 Заголовок</h5>
<pre>
<h5>H5 Заголовок</h5>	
</pre>
					</div>
				</div>

				<div class="manual__block">
					<span class="manual__title">Текст</span>
					<div class="manual__line">
						<span class="manual__caption">Обычный текст</span>
						<p>
						Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, praesentium iusto obcaecati perspiciatis alias ea aut esse eius aliquam.
						</p>
<pre>
<p>
	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum facere cum, iure 
	omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, praesentium iusto 
	obcaecati perspiciatis alias ea aut esse eius aliquam.
</p>
</pre>
					</div>
					<div class="manual__line">
						<span class="manual__caption">Текст с выделениями</span>

						<p>
						Lorem ipsum dolor sit amet, <strong>consectetur adipisicing</strong> elit. Illum facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, <b>praesentium</b> iusto obcaecati <b>perspiciatis</b> alias ea aut esse eius aliquam.
						</p>
					
<pre>
<p>
	Lorem ipsum dolor sit amet, <strong>consectetur adipisicing</strong> elit. Illum 
	facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, 
	<b>praesentium</b> iusto obcaecati <b>perspiciatis</b> 
	alias ea aut esse eius aliquam.
</p>
</pre>
<strong>Выделение 1</strong>
<br>
<b>Выделение 2</b>
<pre>
<strong>Выделение 1</strong>
<b>Выделение 2</b>
</pre>
					</div>
					<div class="manual__line">
						<span class="manual__caption">Текст с ссылками</span>

						<p>
						Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing</a> elit. Illum facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, <a href="#">praesentium</a> iusto obcaecati <a href="#">perspiciatis</a> alias ea aut esse eius aliquam.
						</p>
					
<pre>
<p>
	Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing</a> elit. 
	Illum facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, 
	dolores, <a href="#">praesentium</a> iusto obcaecati 
	<a href="#">perspiciatis</a> alias ea aut esse eius aliquam.
</p>
</pre>
<a href="#">Ссылка</a>
<pre>
<a href="#">Ссылка</a>
</pre>
					</div>
					<div class="manual__line">
						<span class="manual__caption">Цитата</span>

						<blockquote>
							Lorem ipsum dolor sit amet, elit. Illum facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, iusto obcaecati alias ea aut esse eius aliquam.
						</blockquote>
					
<pre>
<blockquote>
	Lorem ipsum dolor sit amet, elit...
</blockquote>
</pre>
					</div>
				</div>

<div class="manual__block">
	<span class="manual__title">Списки</span>
	<div class="manual__line">
		<span class="manual__caption">Текстовой список</span>

		<dl>
			<dt>Первый</dt>
			<dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, praesentium iusto obcaecati perspiciatis alias ea aut esse eius aliquam.</dd>
			<dt>Второй</dt>
			<dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum facere cum, iure omnis optio veniam ipsam voluptates. Hic reiciendis, dolores, praesentium iusto obcaecati perspiciatis alias ea aut esse eius aliquam.</dd>
		</dl>
		
<pre>
<dl>
	<dt>Первый</dt>
	<dd>Lorem ipsum dolor sit amet...</dd>
	<dt>Второй</dt>
	<dd>Lorem ipsum dolor sit amet...</dd>
</dl>
</pre>
</div>
	<div class="manual__line">
		<span class="manual__caption">Ненумерованный список</span>

		<ul>
			<li>  Научно-техническая база - дело серьезное. Вы знаете, как много брендов имеют такую при себе для разработки косметики? Вот теперь можете назвать как минимум один - конечно, мы говорим об Эстель. Научные работники создают рецепты косметики для волос совместно с филиалом Академии парикмахерского искусства.</li>
			<li>  Самое лучшее, что могли сделать производители марки Estel, это сосредоточиться на высоком качестве продукции. А значит используется только передовое оборудование и хорошее сырье. Но ведь все это есть и у других марок, так почему же мне нужно выбрать именно Estel спросите вы?</li>
			<li>  Цена! И она низкая не потому, что кто-то тут пытается демпинговать конкурентов. Секрет прост - производство всей косметики идет в России, а значит компании не приходится тратить деньги на перевозку и растаможивание товара.</li>
			<li>  А значит, что мы получаем такое же высокое качество, как и у мировых брендов, но по более низкой стоимости.</li>
		</ul>
		
<pre>
<ul>
	<li>  Научно-техническая база - дело серьезное...</li>
	<li>  Самое лучшее, что могли сделать...</li>
	<li>  Цена! И она низкая не потому, что кто-то...</li>
	<li>  А значит, что мы получаем такое же высокое качество...</li>
</ul>
</pre>
</div>
	<div class="manual__line">
<span class="manual__caption">Нумерованный список</span>

		<ol>
			<li>  Научно-техническая база - дело серьезное. Вы знаете, как много брендов имеют такую при себе для разработки косметики? Вот теперь можете назвать как минимум один - конечно, мы говорим об Эстель. Научные работники создают рецепты косметики для волос совместно с филиалом Академии парикмахерского искусства.</li>
			<li>  Самое лучшее, что могли сделать производители марки Estel, это сосредоточиться на высоком качестве продукции. А значит используется только передовое оборудование и хорошее сырье. Но ведь все это есть и у других марок, так почему же мне нужно выбрать именно Estel спросите вы?</li>
			<li>  Цена! И она низкая не потому, что кто-то тут пытается демпинговать конкурентов. Секрет прост - производство всей косметики идет в России, а значит компании не приходится тратить деньги на перевозку и растаможивание товара.</li>
			<li>  А значит, что мы получаем такое же высокое качество, как и у мировых брендов, но по более низкой стоимости.</li>
		</ol>
		
<pre>
<ol>
	<li>  Научно-техническая база - дело серьезное...</li>
	<li>  Самое лучшее, что могли сделать...</li>
	<li>  Цена! И она низкая не потому, что кто-то...</li>
	<li>  А значит, что мы получаем такое же высокое качество...</li>
</ol>
</pre>
		
	</div>
</div>

<div class="manual__block">
	<span class="manual__title">Изображения</span>
	<div class="manual__line">
		<span class="manual__caption">Обычное изображение</span>
		
		<img src="http://caffret.com/images/gallery/fulls/4.jpg" alt="Cafre" />
		
<pre>
<img src="http://caffret.com/images/gallery/fulls/4.jpg" alt="Cafre" />
</pre>
	</div>
	<div class="manual__line">
		<span class="manual__caption">Обтекание контента слева</span>

		<div class="brand__img-l">
			<img src="http://cafre.ru/bitrix/templates/aspro_mshop/images/estel-img-l.jpg"/>
			</div>

			<h4>Поговорим о преимуществах?</h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque rem ad consectetur dignissimos reprehenderit, deleniti totam tempora, odit voluptatem a exercitationem ea tenetur dolor amet ab ratione, esse ullam quas?</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque quas iusto illo assumenda quasi fuga repellat, voluptatibus minus at quo reprehenderit voluptate unde impedit deserunt. Fugit debitis ipsa illum, consequatur.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error sed, cupiditate odio at adipisci optio nobis minima molestiae. Odio maiores iusto assumenda obcaecati ea fugiat exercitationem modi dolore, odit enim!Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, obcaecati labore iste illum beatae eveniet id? Ratione harum temporibus sapiente neque reiciendis perferendis, libero suscipit ipsum sint, consectetur consequuntur. Explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto provident, explicabo. Nihil similique illum vitae commodi nisi repellendus alias, odit qui delectus vel adipisci quia eum modi. Minus, delectus, molestiae.</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos eligendi tenetur modi assumenda est dolores quo delectus repellendus, mollitia repudiandae nostrum veritatis recusandae, ullam omnis repellat officiis, maxime perspiciatis natus!</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni, nam ea voluptates quam quidem eius fugit repellendus earum unde. Consequatur harum saepe, veritatis neque optio obcaecati suscipit fugit quae doloribus.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita consequuntur facere voluptatum inventore neque dicta tenetur veritatis unde natus eaque, architecto voluptatem dignissimos, adipisci nulla cum vero harum officiis minima.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias iure rerum tempora possimus incidunt sit labore nihil, modi aut eligendi cum blanditiis cupiditate quaerat repellat cumque asperiores rem magni nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>

<pre>
<div class="brand__img-l">
	<img src="адрес_изображения.jpg"/>
</div>
<h4>Поговорим о преимуществах?</h4>
<p>Текст</p>
</pre>
	</div>

	<div class="manual__line">
		<span class="manual__caption">Обтекание контента справа</span>


		<div class="brand__img-r">
			<img src="http://cafre.ru/bitrix/templates/aspro_mshop/images/estel-img-r.jpg"/>
		</div>

		<h4>Широкий ассортимент - это правда? </h4>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque rem ad consectetur dignissimos reprehenderit, deleniti totam tempora, odit voluptatem a exercitationem ea tenetur dolor amet ab ratione, esse ullam quas?</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Itaque quas iusto illo assumenda quasi fuga repellat, voluptatibus minus at quo reprehenderit voluptate unde impedit deserunt. Fugit debitis ipsa illum, consequatur.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error sed, cupiditate odio at adipisci optio nobis minima molestiae. Odio maiores iusto assumenda obcaecati ea fugiat exercitationem modi dolore, odit enim!Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, obcaecati labore iste illum beatae eveniet id? Ratione harum temporibus sapiente neque reiciendis perferendis, libero suscipit ipsum sint, consectetur consequuntur. Explicabo. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto provident, explicabo. Nihil similique illum vitae commodi nisi repellendus alias, odit qui delectus vel adipisci quia eum modi. Minus, delectus, molestiae.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos eligendi tenetur modi assumenda est dolores quo delectus repellendus, mollitia repudiandae nostrum veritatis recusandae, ullam omnis repellat officiis, maxime perspiciatis natus!</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni, nam ea voluptates quam quidem eius fugit repellendus earum unde. Consequatur harum saepe, veritatis neque optio obcaecati suscipit fugit quae doloribus.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita consequuntur facere voluptatum inventore neque dicta tenetur veritatis unde natus eaque, architecto voluptatem dignissimos, adipisci nulla cum vero harum officiis minima.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias iure rerum tempora possimus incidunt sit labore nihil, modi aut eligendi cum blanditiis cupiditate quaerat repellat cumque asperiores rem magni nobis.Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>

<pre>
<div class="brand__img-r">
	<img src="адрес_изображения.jpg"/>
</div>
<h4>Поговорим о преимуществах?</h4>
<p>Текст</p>
</pre>
	</div>
</div>	

				<div class="manual__block">
					<span class="manual__title">Таблица</span>
					<div class="brand__items">
					    <div class="brand__items-head">
					        <h5>Средства для ухода за волосами</h5>
					    </div>
					    <div class="brand__items-table">
					        <table>
					            <tr>
					                <th>Название</th>
					                <th>Объём</th>
					                <th>Артикул</th>
					                <th>Результат</th>
					            </tr>
					            <tr>
					                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
					                <td><strong>1000 мл</strong></td>
					                <td>CUS1000/S13</td>
					                <td>Уплотняет и дарит визуальное совершенство</td>
					            </tr>
					            <tr>
					                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
					                <td><strong>1000 мл</strong></td>
					                <td>CUS1000/S13</td>
					                <td>Уплотняет и дарит визуальное совершенство</td>
					            </tr>
					            <tr>
					                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
					                <td><strong>1000 мл</strong></td>
					                <td>CUS1000/S13</td>
					                <td>Уплотняет и дарит визуальное совершенство</td>
					            </tr>
					            <tr>
					                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
					                <td><strong>1000 мл</strong></td>
					                <td>CUS1000/S13</td>
					                <td>Уплотняет и дарит визуальное совершенство</td>
					            </tr>
					        </table>
					    </div>
					</div>

<pre>
<div class="brand__items">
    <div class="brand__items-head">
        <h5>Средства для ухода за волосами</h5>
    </div>
    <div class="brand__items-table">
        <table>
            <tr>
                <th>Название</th>
                <th>Объём</th>
                <th>Артикул</th>
                <th>Результат</th>
            </tr>
            <tr>
                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
                <td><strong>1000 мл</strong></td>
                <td>CUS1000/S13</td>
                <td>Уплотняет и дарит визуальное совершенство</td>
            </tr>
            <tr>
                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
                <td><strong>1000 мл</strong></td>
                <td>CUS1000/S13</td>
                <td>Уплотняет и дарит визуальное совершенство</td>
            </tr>
            <tr>
                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
                <td><strong>1000 мл</strong></td>
                <td>CUS1000/S13</td>
                <td>Уплотняет и дарит визуальное совершенство</td>
            </tr>
            <tr>
                <td><a href="#">Шампунь «Увлажнение и питание» </a></td>
                <td><strong>1000 мл</strong></td>
                <td>CUS1000/S13</td>
                <td>Уплотняет и дарит визуальное совершенство</td>
            </tr>
        </table>
    </div>
</div>						
</pre>
</div>
					
				
			<div class="manual__block">
				<span class="manual__title">Содержание</span>
				<div class="manual__line">
					<p>Содержание формируется автоматически по найденным в тексте тегам - h2,h3,h4</p>
				</div>
			</div>
			</div>
			<div class="manual-content">
				<span class="manual__caption">Навигация</span>
				<ul class="manual-content__list"></ul>
			</div>
			</div>
		</div>
	</div>	
</div>


<script src="fixto.min.js"></script>
<script>

	$(function() {
		$('.manual pre').each(function(i, code) {
			var str = $(code).html().replace(/</g, '&lt;').replace(/>/g, '&gt;');
			$(code).html(str);
		});

		function navigation() {
			var navList = $('.manual-content__list');

			$('.manual__title').each(function(i, t) {
				$(t).attr('id', 'nav'+i);
				$(navList).append('<li><span>'+$(t).text()+'</span></li>');
			})

			$('.manual-content__list span').on('click', function() {
				var i = $(this).parent().index();
				$("html, body").stop().animate({ scrollTop: $('#nav'+i).offset().top }, 1000);
			})
		}

		navigation();

		$('.manual-content').fixTo('.content', {
			top: 50
		});
	});

</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
 
<script>
 

</script>

 