<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
$arBasketItems = array();
$dbBasketItems = CSaleBasket::GetList(
    array("NAME" => "ASC","ID" => "ASC"),
    array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL","DELAY" => N,"CAN_BUY" => Y),
    false,
    false,
    array("ID", "CALLBACK_FUNC", "DETAIL_PAGE_URL", "NAME", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT")
);
while ($arItems = $dbBasketItems->Fetch()) {
    if (strlen($arItems["CALLBACK_FUNC"]) > 0) {
        CSaleBasket::UpdatePrice($arItems["ID"], $arItems["NAME"], $arItems["DETAIL_PAGE_URL"], $arItems["CALLBACK_FUNC"], $arItems["MODULE"], $arItems["PRODUCT_ID"], $arItems["QUANTITY"]);
        $arItems = CSaleBasket::GetByID($arItems["ID"]);
    }
    $arBasketItems[] = $arItems;
}
$total = count($_POST["arr"]);
$counter = 0;
foreach($arBasketItems as $r){
	$counter++;
	$intElementID = $r["PRODUCT_ID"]; // ID ????
	$mxResult = CCatalogSku::GetProductInfo($intElementID);
	$tovid = $mxResult['ID'];
	$db_props = CIBlockElement::GetProperty(26, $tovid, array("sort" => "asc"), Array("CODE"=>"BRAND"));
	if($ar_props = $db_props->Fetch()) 
		$res = CIBlockElement::GetByID($ar_props["VALUE"]);
	if($ar_res = $res->GetNext())
	    $res2 = CIBlockElement::GetByID($tovid);
	$search_raz = explode("/", $r["DETAIL_PAGE_URL"]);
	$nav = CIBlockSection::GetNavChain(false,$search_raz[2]);
	$i=1;
	$name = $r['NAME'];
	$brand = $ar_res['NAME'];
	$sect = "";
	while($arSectionPath = $nav->GetNext()){$sect.= $arSectionPath['NAME'].'/';$i++;}
		$arr_mas[] = array(
				"id" => $intElementID,
				"name" => mb_convert_encoding($name, 'utf-8', 'cp1251'),
				"list_name" => mb_convert_encoding($name, 'utf-8', 'cp1251'),
				"brand" => mb_convert_encoding($brand, 'utf-8', 'cp1251'),
				"category" => mb_convert_encoding($sect, 'utf-8', 'cp1251'),
				"list_position" => $i,
				"quantity" => $r["QUANTITY"],
				"price" => round($r["PRICE"], 0)
		);
		$r['SUM_Q'] = round($r["PRICE"], 0)*$r["QUANTITY"];	
		$sum[] = $r['SUM_Q'];
	}
$d = array(
	"transaction_id" => $intElementID,
	"affiliation" => "cafre.ru",
	"value" => array_sum($sum),
	"currency" => "RUB",
	"tax" =>  0,
	"shipping" => 0,
	"items" => $arr_mas
);
echo json_encode($d);
?>