<?
define('WSDL', "http://37.18.74.47:8002/WebServices/Granite.Gateway.ExportService.asmx?WSDL");
//define('WCLIENT', array('soap_version' => 'SOAP_1_2','trace' => true,'login' => '','password' => '','exceptions' => 1,'cache_wsdl'=>WSDL_CACHE_NONE));

function pr($arX, $disp='block') {
	echo '<pre style="display:'.$disp.'">';
	print_r($arX);
	echo '</pre>';
}


function wsdl($name, $array) {
  $server = '37.18.74.47:8002/WebServices/Granite.Gateway.ExportService.asmx';
  $wsdl_status = 0;
  $port = 80;
  $timeout = 10;
  $fp = @fsockopen ($server, $port, $errno, $errstr, $timeout);
  if ($fp) {//сначала проверяем доступен ли сейчас сервер
    $wsdl_status = 0;
    @fwrite ($fp, "HEAD / HTTP/1.0\r\nHost: $server:$port\r\n\r\n");
    if (strlen(@fread($fp,1024))>0) $wsdl_status = 1;
    fclose ($fp);
  }
  if($wsdl_status>0) {//если да - то запрос делаем
	//$client = new SoapClient(WSDL, array('soap_version' => 'SOAP_1_2','trace' => true,'login' => '','password' => '','exceptions' => 1,'cache_wsdl'=>WSDL_CACHE_NONE));
	$client = new SoapClient(WSDL);
    return  $client->$name();
  }
  else return 0;//или получаем 0
}


$obTest=wsdl('GetAllBrands', array());
//$obTest1=$obTest['GetAllBrands'];
//foreach($obTest as $key=>$X):
foreach($obTest->GetAllBrandsResult->ExportedData->BrandDTO as $key=>$X):
	//echo 'key='.$key;
	//$obTest1=$obTest->$key;
	echo $X->Name;
	pr($X);
	//echo 'QQ<hr>';
endforeach;

//pr($obTest->GetAllBrandsResult->ExportedData->BrandDTO);
echo '<hr>';

?>