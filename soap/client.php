<?
if(!isset($_GET['test'])) {
$username = "iitovci@gmail.com";
$password = "sK4ZMD29_%";     
$host_api = "https://sezonkoles.ru";
} else {
$username = "iitovci@gmail.com";
$password = "sK4ZMD29_%";     
$host_api = "https://www.koleso22.ru";
}


$curl = curl_init($host_api);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);       
curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);       
curl_setopt($curl, CURLOPT_URL, "$host_api/bitrix/admin/1c_exchange_koleso.php?type=catalog&mode=checkauth");       
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($curl);
print_r($result);
echo $host_api;


/*
$request=array('type'=>'sale', 'mode'=>'checkauth');
$c_url = "https://sezonkoles.ru/bitrix/admin/1c_exchange.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $c_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100); 
$data = curl_exec($ch); 
print_r($data);
*/
?>