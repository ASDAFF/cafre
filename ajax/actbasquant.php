 <?
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
 CModule::IncludeModule("iblock");
 CModule::IncludeModule("catalog");
 CModule::IncludeModule("sale");

if($_POST["id_tov"]){
							$arItems = CSaleBasket::GetByID($_POST["id_tov"]);
							if($_POST["but"] == 1){
							$arFields = array(
							   "QUANTITY" => $arItems["QUANTITY"]+1
							);
							}else{
								$arFields = array(
							   "QUANTITY" => $arItems["QUANTITY"]-1
							);
							}
							CSaleBasket::Update($_POST["id_tov"], $arFields);
							
$msg_box = "Операция выполнена!";

echo json_encode(array(
        'result' => $msg_box
    ));
}

?>