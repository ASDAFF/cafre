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

$request  ='<?xml version="1.0" encoding="UTF-8"?><SERVICE>
	<Request>
		<Operation>AddProduct</Operation>		
		<Code>998898</Code>
  		<Name>Название</Name>
  		<Brand>Nexxt</Brand>
  		<Anons>Текст</Anons>
  		<Description>Текст</Description>
		<IsHit>true</IsHit>
  		<IsRecommend>false</IsRecommend>
  		<IsSale>false</IsSale>
  		<IsNew>true</IsNew>
  		<ArtNo>123456</ArtNo>
  		<Category>Волосы/Уход/Шампуни</Category>
		<Count>105</Count>
  		<Currency>
  			<Price>
	  			<PriceType>Base</PriceType>
  				<Value>115</Value>
  			</Price>  			
  		</Currency>
  		<Pictures>
  			<Image>
				<IsMainImage>true</IsMainImage>
  				<OriginName>/tmp/00.png</OriginName>
  				<SortOrder>1</SortOrder>
  			</Image>
  			<Image>
				<IsMainImage>false</IsMainImage>
  				<OriginName>/tmp/01.png</OriginName>
  				<SortOrder>1</SortOrder>
  			</Image>
  			<Image>
				<IsMainImage>false</IsMainImage>
  				<OriginName>/tmp/02.png</OriginName>
  				<SortOrder>2</SortOrder>
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