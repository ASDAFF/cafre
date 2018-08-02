<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Условия оплаты");
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



</style>
  <div class="text-page tpay">

						  <div id="content">

                <div class="alrt">
                  <h3>Оформили заказ и уже считаете часы до его прибытия?</h3>
                </div>

                <p>
                  Не забудьте про оплату! Как вы хотите осуществить? Выбирайте самый удобный вариант!
                </p>

                <h3 style="color: #ff9cb0;">Варианты оплаты. <span>Итак,</span> есть <strong>2</strong> способа <span>это сделать:</span></h3>

                <div class="ab-tabs tpay__tabs">
                  <ul class="ab-tabs__list">
                    <li class="tab">Наличными</li>
                    <li class="tab">Банковский перевод</li>
                  </ul>
                  <div class="ab-tabs__content-wrap">
                    <div class="ab-tabs__content">
                      <img src="/img/wallet.png" class="ab-tabs__image">
                      <p>
                        Больше нравится сначала получить товар, а уже после расплатиться?
                        И мы снова не против! Оформляя заказ, не забудьте указать в пункте "оплата",
                        что вам удобнее заплатить наличными. В этом случае расчёт осущемтвляется
                        в отделении Почты России, либо с курьером, который доставит ваш заказ
                        прямо к дому.<br>
                        Всё зависит от того, какой вариант доставки вы предпочли!
                      </p>
                    </div>
                    <div class="ab-tabs__content">
                      <img src="/img/bank.png" class="ab-tabs__image">
                      <p>
                        Вам по душе оплата банковским переводом?<br>
                        Пожалуйста, мы только рады предоставить вам эту возможность!
                      </p>
                    </div>
                  </div>
                </div>

                <h4 style="color: #7f7f7f">И ПОМНИТЕ, что ваши УДОБСТВО И КОМФОРТ - НАША ГЛАВНАЯ ЗАДАЧА!</h4>

              </div>
          </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>