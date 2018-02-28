<?
/*
//file_put_contents('0.txt', print_r($REQUEST, true), FILE_APPEND);
class MyServer
{
	
    public function SetWholesalePriceBy1cCode($array) {
		file_put_contents('0.txt', print_r($_REQUEST, true), FILE_APPEND);
		$c_url = "https://test.cafre.ru/soap/client3.php";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $c_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('id'=>$array[0], 'price'=>$array[1]));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100); 
		$data = curl_exec($ch); 
		return $data;
	}
	
}
// Create and run the server.
$soapServer = new SoapServer(null, array('uri' => 'urn:myschema'));
$soapServer->setObject(new MyServer());
$soapServer->handle();?>
*/
/*
file_put_contents('0.txt', print_r($_REQUEST, true), FILE_APPEND);
require_once "soap.php";
$client = new Dklab_SoapClient(null, array(
    'location' => "https://test.cafre.ru/soap/client.php",
    'uri' => 'urn:myschema',
    'timeout' => 3,
));
//$data = $client->SetWholesalePriceBy1cCode(array("000998899", 10)); 
$data = $client->SetWholesalePriceBy1cCode($_REQUEST); 
echo "<pre>";
print_r($data);
echo "</pre>";
*/
if(isset($_POST['id'])) {
	$_POST['id']="000998899";
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	CModule::IncludeModule('catalog');
	CModule::IncludeModule('sale');
	CModule::IncludeModule('iblock');
	$_POST['id']=(string)$_POST['id'];
	for ($i=0; $i < strlen($_POST['id']); $i++) { 		
		if($_POST['id'][$i]=='0') {						
		}
		else break;
	}	
	$_POST['id']= substr($_POST['id'], $i);
	$arSelect = Array("ID");
	$arFilter = Array("IBLOCK_ID"=>26, "PROPERTY_CODE1C"=>array(str_repeat("0", 11-strlen($_POST['id'])).$_POST['id'], $_POST['id']));
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
	
	if($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arSelect = Array("ID");
		$arFilter = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arFields['ID']) ;
		$resTP = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);		
		if($obTP = $resTP->GetNextElement()) {
			$arFieldsTP = $obTP->GetFields();
			
			$PRODUCT_ID = $arFieldsTP['ID'];
			$PRICE_TYPE_ID = 1;
			$arFields = Array(
				"PRODUCT_ID" => $PRODUCT_ID,
				"CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
				"PRICE" => $_POST['price'],
				"CURRENCY" => "RUB"
			);
			$res = CPrice::GetList(
				array(),
				array(
					"PRODUCT_ID" => $PRODUCT_ID,
					"CATALOG_GROUP_ID" => $PRICE_TYPE_ID
				)
			);
			if ($arr = $res->Fetch())
			{
				CPrice::Update($arr["ID"], $arFields);
			}
			else
			{
				CPrice::Add($arFields);
			}
		}
	}
	

}
?>