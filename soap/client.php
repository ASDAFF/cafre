<?
$request  = '<?xml version="1.0" encoding="UTF-8"?>
<SERVICE>
<Request>
	<Operation>SetWholesalePriceBy1cCode</Operation>
	<Code>998899</Code>
	<Currency>105</Currency>
</Request>
</SERVICE>';
$request  = '<?xml version="1.0" encoding="UTF-8"?>
<SERVICE>
<Request>
	<Operation>UpdateAmountByCodeFrom1c</Operation>
	<Code>998899</Code>
	<Count>105</Count>
</Request>
</SERVICE>';
$request  ='<?xml version="1.0" encoding="UTF-8"?>
<SERVICE>
	<Request>
		<Operation>AddImage</Operation>
		<Code>998899</Code>
		<IsMainImage>false</IsMainImage>
		<OriginName>/var/www/www-root/data/www/test.cafre.ru/upload/4.png</OriginName>
		<SortOrder>4</SortOrder>
	</Request>
</SERVICE>';

$request  ='<?xml version="1.0" encoding="UTF-8"?>
<SERVICE>
	<Request>
	<Operation>AddImage</Operation>
  <Code>998899</Code>
  <IsMainImage>true</IsMainImage>
  <OriginName>/tmp/00000003343.jpg</OriginName>
  <SortOrder>1</SortOrder></Request>
</SERVICE>';

$request  = '<?xml version="1.0" encoding="UTF-8"?>
<SERVICE>
<Request>
	<Operation>SetWholesalePriceBy1cCode</Operation>
	<Code>998899</Code>
	<Currency>
  		<Price>
  			<PriceType>Base</PriceType>
  			<Value>115</Value>
  		</Price>  			
  	</Currency>
</Request>
</SERVICE>';
/*
		<IsHit>true</IsHit>
  		<IsRecommend>false</IsRecommend>
  		<IsSale>false</IsSale>
  		<IsNew>true</IsNew>
		*/


$request  ='<?xml version="1.0" encoding="UTF-8"?><SERVICE>
	<Request>
		<Operation>UpdateOrder</Operation>		
		<OrderNumber>597</OrderNumber>
		<DeliveryCost>100</DeliveryCost>
		<DeliveryTrackNumber>157</DeliveryTrackNumber>
		<DeliveryMethodName>SPSR</DeliveryMethodName>
		<OrderStateId>PG</OrderStateId>
		<OrderTotalSum>2351</OrderTotalSum>
		<Cart>
			<Item>
				<Amount>1</Amount>
				<Price>476</Price>
				<Code>89292</Code>
			</Item>
			<Item>
				<Amount>2</Amount>
				<Price>959</Price>
				<Code>85959</Code>
			</Item>
		</Cart>		
	</Request>
</SERVICE>';




$request1  ='<?xml version="1.0" encoding="UTF-8"?><SERVICE>
	<Request>
		<Operation>AddProduct</Operation>		
		<Code>998898</Code>
  		<Name>Название</Name>
  		<Brand>Nexxt</Brand>
  		<Anons>Текст</Anons>
  		<Description>Текст</Description>
  		<ArtNo>123456</ArtNo>
		<Count>105</Count>
  		<Currency>
  			<Price>
	  			<PriceType>Base</PriceType>
  				<Value>115</Value>
  			</Price>  			
  		</Currency>
  		<Pictures>
  			<Image>
				<IsMainImage>false</IsMainImage>
  				<OriginName>/tmp/76112.jpg</OriginName>
  				<SortOrder>1</SortOrder>
  			</Image>
  			<Image>
				<IsMainImage>true</IsMainImage>
  				<OriginName>/tmp/7611.jpg</OriginName>
  				<SortOrder>1</SortOrder>
  			</Image>
  			<Image>
				<IsMainImage>false</IsMainImage>
  				<OriginName>/tmp/01.png</OriginName>
  				<SortOrder>2</SortOrder>
  			</Image>
  			
  			<Image>
				<IsMainImage>false</IsMainImage>
  				<OriginName>/tmp/76113.jpg</OriginName>
  				<SortOrder>3</SortOrder>
  			</Image>
  		</Pictures>
  </Request>
</SERVICE>';


$request0='<?xml version="1.0" encoding="UTF-8"?>
<SERVICE>
	<Request>
		<Operation>AddProduct</Operation>
		<Code>3643</Code>
		<Name>S-OS/165 Essex Коралловый, крем-краска, 60 мл.</Name>
		<Brand>Estel</Brand>
		<Anons>&lt;p&gt;ESSEX S-OS/165 Коралловый от ESTEL Professional является отличным решением в случае, когда необходимо осветлить волосы на 4 тона, либо подкорректировать существующий цвет.&lt;/p&gt;</Anons>
		<Description>&lt;p&gt;ESSEX S-OS/165 Коралловый от компании ESTEL Professional позволит подкорректировать имеющийся цвет, или осветлить волосы на 4 тона.&lt;/p&gt;&lt;p&gt;Смешайте краску с оксигентом ESSEX 3%, 6%, 9%, 12% в пропорции 1:2.&lt;/p&gt;&lt;p&gt;Краска наносится на подготовленные волосы на 50 минут. Не рекомендуется использовать для тонирования.&lt;/p&gt;</Description>
		<ArtNo>S/165</ArtNo>
		<Count>180</Count>
		<Currency>
			<Price>
				<PriceType>Base</PriceType>
				<Value>150</Value>
			</Price>
		</Currency>
		<Pictures>
			<Image>
				<IsMainImage>true</IsMainImage>
				<OriginName>/tmp/00000003643_1_.jpg</OriginName>
				<SortOrder>1</SortOrder>
			</Image>
			<Image>
				<IsMainImage>false</IsMainImage>
  				<OriginName>/tmp/7611.jpg</OriginName>
  				<SortOrder>1</SortOrder>
  			</Image>
		</Pictures>
	</Request>
</SERVICE>';



$c_url = "https://test.cafre.ru/soap/client2.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $c_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100); 
$data = curl_exec($ch); 
print_r($data);