<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("����������");
?>
<h2>��������� H2</h2>
<p>��������-������� � ����, ��������� �������� � ���������. ��������� ������������� ������������ ����� �� �������, ������� ������ ������ � �������� ������ � ���� ��������.&nbsp;</p>
<blockquote>������������ ������� � ������� ������� ���-���������. ����� ��� ���������� ������ ����������������� ����������� �������� ��������� �������������� ��������� �� ���������� ��������. 	</blockquote> 
<h3>��������� H3</h3>
<p><i>������, � ���� ������ ������� ���� ����������, ��������� �������� ����������� ������ ����������� �������� ����������� �������, ��� � ������ ���������� ��������.</i></p>
<h4>������������� ������ H4</h4>
<ul>
	<li>� ��������-���������, ������������ �� ��������� �������, ����� ������� ������������ ��������� ���������� � ������� �������.</li>
	<li>����� ����, ���������� �����, � ������� ����� ����������� �� ��������, ����������� �����, Jabber ��� ICQ.</li>
</ul>
<h5>������������ ������ H5</h5>
<ol>
	<li>� ��������-���������, ������������ �� ��������� �������, ����� ������� ������������ ��������� ���������� � ������� �������.</li>
	<li>����� ����, ���������� �����, � ������� ����� ����������� �� ��������, ����������� �����, Jabber ��� ICQ.</li>
</ol>
<hr class="long"/>
<h5>�������</h5>
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
	<div class="text">�������� 100 �.</div>
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