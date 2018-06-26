<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("������, �������� � ������� ������");
?>
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

	.container .delivery p {
		font-size: 18px;
	}

	.delivery__steps {
		margin-bottom: 80px;
	}

	.delivery__girl {
		position: relative;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
	}

	.delivery__girl:nth-child(1)::before {
		position: absolute;
		left: 40%;
		top: 0;
		height: 80px;
		width: 400px;
		content: '';
		background: url('img/arrow-6.svg') no-repeat;
		background-size: contain;
	}

	.delivery__girl:nth-child(1)::after {
		position: absolute;
		left: 40%;
		bottom: -60px;
		height: 114px;
		width: 400px;
		content: '';
		background: url('img/arrow-7.svg') no-repeat;
		background-size: contain;
	}

	.delivery__girl:nth-child(2)::after {
		position: absolute;
		left: 40%;
		bottom: 0;
		height: 80px;
		width: 400px;
		content: '';
		background: url('img/arrow-6.svg') no-repeat;
		background-size: contain;
		transform: scaleY(-1);
	}

	.delivery__girl-message {
		position: relative;
		display: inline-block;
		vertical-align: top;
		width: 45%;
		text-align: right;
		height: 300px;
	}

	.delivery__girl-message img {
		position: absolute;
		left: -40px;
		top: 50%;
		transform: translateY(-50%) translate3d(0,0,0);
		width: 200px;
	}

	.delivery__block {
		width: 920px;
		max-width: 100%;
		margin: 50px 0;
	}

	.delivery__methods-logos {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin: 100px 0;
	}

	.delivery__methods-logos > div {
		position: relative;
		height: 75px;
		width: 30%;
	}

	.delivery__methods-logos > div img {
		position: absolute;
		top: 50%;
		left: 50%;
		max-width: 200px;
		max-height: 100%;
		transform: translate3d(0,0,0) translate(-50%,-50%);
	}

	.message {
		position: relative;
		top: 75px;
		/*top: 50%;*/
		/*transform: translateY(-50%);*/
		display: inline-block;
		pointer-events: none;
		background: #eaf3f5;
		border: 1px solid rgba(0,0,0,.1);
		padding: 15px;
		border-radius: 4px;
		width: 60%;
		text-align: left;
		min-height: 100px;
	}

	.message strong {
		display: block;
	}

	.container .message p {
		font-size: 16px;
		line-height: 1.4;
		margin: 0;
	}

	.message::before {
		z-index: 2;
		position: absolute;
		left: -20px;
		top: 50%;
		transform: translateY(-50%) scaleY(.75);
		content: '';
		border: 10px solid transparent;
		border-right-color: #eaf3f5;
	}

	.message::after {
		z-index: 1;
		position: absolute;
		left: -21px;
		top: 50%;
		transform: translateY(-50%) scale(1.1) scaleY(.75);
		content: '';
		border: 10px solid transparent;
		border-right-color: rgba(0,0,0,.2);
	}

	.delivery__block ul {
		margin: 20px 0 24px 10px;
	}

	.delivery__block li {
		margin-bottom: 20px;
	    font-family: 'Merriweather', sans-serif;
	    font-size: 14px;
	    color: black;
	    line-height: 24px;
	}

	.delivery__block li::before {
		padding: 0px;
	    margin-left: 20px;
	    width: 20px;
	    display: inline-block;
	    vertical-align: top;
	    zoom: 1;
	    font-size: 6px;
	    position: relative;
	    top: -1px;
	    margin-right: 15px;
	    color: black;
	}

	.delivery__block a {
		font-family: 'Merriweather', sans-serif;
	    font-size: 14px;
	    color: #4A90E2;
	    text-decoration: underline;
	}

	@media (max-width: 1000px) {
		.delivery__girl {
			justify-content: center;
		}

		.delivery__girl:nth-child(1n)::before,
		.delivery__girl:nth-child(1n)::after {
			display: none;
		}

		.delivery__girl-message {
			width: 90%;
			height: auto;
			padding: 20px 0;
			margin-bottom: 20px;
		}

		.message {
			top: 50%;
			transform: translateY(-50%);
		}

		.delivery__steps {
			margin-bottom: 50px;
		}

		.delivery__methods-logos {
			margin: 50px 0;
		}
	}

	@media (max-width: 768px) {
		.delivery__methods-logos {
			flex-wrap: wrap;
		}

		.delivery__methods-logos div {
			width: 100%;
			margin: 30px 0;
		}

		.delivery__girl {
			flex-wrap: wrap;
		}

		.delivery__girl-message {
			width: 100%;
		}

		.delivery__girl-message img {
			position: static;
			display: block;
			transform: none;
			margin: 0 auto 30px;
		}

		.message {
			display: block;
			width: 100%;
			position: relative;
			top: 0;
			left: 0;
			transform: none;
			box-sizing: border-box;
		}

		.message::before {
			top: -25px;
			left: 50%;
			border-color: transparent;
			border-bottom-color: #eaf3f5;
			transform: translateY(0) translateX(-50%) scaleY(1.5);
		}

		.message::after {
			top: -26px;
			left: 50%;
			transform: translateX(-50%) translateY(0) scaleY(1.5);
			border-color: transparent;
			border-bottom-color: rgba(0,0,0,.2);
		}
	}


	</style>
	<div class="delivery">
					<div class="delivery__steps">
						<div class="delivery__girl">
							<div class="delivery__girl-message">
								<img src="img/girl1.png" alt="">
								<div class="message">
									<p>
										<strong>���������� �������� ������ ������</strong>
										�������� ����� �� ����� �� 2000 �. � �������� ���������� ��������	
									</p>
								</div>
							</div>
							<div class="delivery__girl-message">
								<img src="img/girl2.png" alt="">
								<div class="message">
									<p>
										<strong>������� �������� �����, ����� �������</strong>
										������ ��� ��������� - ������� �������� �����, ���������, � ������ ����� �������
									</p>
								</div>
							</div>
						</div>
						<div class="delivery__girl">
							<div class="delivery__girl-message">
								<img src="img/girl3.png" alt="">
								<div class="message">
									<p>
										<strong>������� � ����������� ����������</strong>
										��������������� ������� � ������� ������ � ������ ����������
									</p>
								</div>
							</div>
							<div class="delivery__girl-message">
								<img src="img/girl4.png" alt="">
								<div class="message">
									<p>
										<strong>�������, ���� ���-�� �� �����������</strong>
										������� ��� ������ ��������, ���� � ������� ���-�� �� ���
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="delivery__methods">
						<div class="delivery__block">
							<h2>��������� ������� ��� ��� ������ ��������</h2>
							<p>�������, �� �����������, ��� ������� ������ ��������� ������� � ������������! ���� ������ - ������� ������� �������� ������ ����������� ���������� ��� ���!</p>

							<p>��������� ����� ������� �������:</p>

							<p>- �������� � ����� ������ ������, ������� ����� ������, �������������� �� ������������� ���� � ���������� ����� 300 ������ �� ���� ������. � ��� ������ �� ����� �� 3000 ������ �� � �������� ������� �� ���� ����� ����� �������� � ��������� ���������� �������� ������ ��������-��������� ���� � ��� �� ������ ������!</p>

							<p>-�������� �������� ����������� �������� ��� � ���� �������������� �� ������ ���� - 350 ������ �� ���� ������! � ��� ������ �� 5000 ������ ������ ���������� ��������� ������� ������� ����� � ������ ����. �������� ������ � �������!</p>
							<!--<p>������� ������ ��������� ������� � ������������, ��������� ����� ������� ��� ��� ������ ��������. ������ �������? � ����� ������� ���������� ��������. ������ ����������?</p>
							<p>��������������  ����������� ������������ � �� �������� ��� ����� ���������� ��������� � ����� ����� ������ (��� ����� ������ �� 2000 ������).</p>	-->
						</div>
						<div class="delivery__methods-logos">
							<div>
								<img src="img/russianpost.png" alt="����� ������">
							</div>
							<div>
								<img src="img/dpd.png" alt="DPD">
							</div>
							<div>
								<img src="img/cdek.png" alt="CDEK">
							</div>
						</div>
						<div class="delivery__block">
							<h3>������������ ���� ����� �������</h3>
							<p>� ����� ������ ������� �� ���������, ��� ������ ��������� ���� �������. ������������ ��������, ��� ����� �������� ���������� ����� ����� ������� � ������ ������������ � �������������� ����� �� ��������:</p>
							<ul>
								<li><a href="http://www.russianpost.ru/" target="_blank">����� ������</a></li>
								<li><a href="http://www.dpd.ru/" target="_blank">������������ �������� DPD</a></li>
								<li><a href="http://www.edostavka.ru/" target="_blank">������������ �������� ����</a></li>
							</ul>	
						</div>
					</div>
				</div>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>