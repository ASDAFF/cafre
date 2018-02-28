<?

function wsdl($name, $array) {

    $client = new SoapClient("https://test.cafre.ru/soap/client.php", array('soap_version' => 'SOAP_1_2',
'exceptions' => 1));
    if($result->ErrorDesc!='') return 0;
    else return  $client->$name($array);
  
}


$result=wsdl('SetWholesalePriceBy1cCode', array("000998899", 10));         
print_r($result);
?>