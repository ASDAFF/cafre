<?
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
 CModule::IncludeModule("iblock");
 CModule::IncludeModule("catalog");
 CModule::IncludeModule("sale");
 /*
echo "<pre>";
print_r();
echo "</pre>";	
*/
 $fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/ya_market_mcm.xml';
 $xml = simplexml_load_file($fileurl);
 /*Перебор xml*/
    foreach($xml->shop->offers->offer as $key => $items)
    {
		$c1 = $items->attributes()->id;
		$str1c = (string)$c1;
		$text = $items->description;
		$textstr = (string)$text;
		$ar[] = array("ID"=>$str1c, "DESC"=>$textstr);
	}
	
	foreach($ar as $val){	
		$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$val["ID"]);
		$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID', 'NAME','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C', 'XML_ID', 'DETAIL_TEXT'));
		if($ob2 = $res->GetNextElement()){
	$arFields2 = $ob2->GetFields();
$el = new CIBlockElement;
$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(),
  "DETAIL_TEXT"    => iconv("UTF-8", "Windows-1251", $val["DESC"])
  );
$res2 = $el->Update($arFields2["ID"], $arLoadProductArray);
echo "<pre>";
		print_r($arFields2["ID"]);
echo "</pre>";
		}
	}
	/*$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>"00000019810");
		$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID', 'NAME','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C', 'XML_ID', 'DETAIL_TEXT'));
		if($ob2 = $res->GetNextElement()){
	$arFields2 = $ob2->GetFields();
echo "<pre>";
		print_r($arFields2);
		echo "</pre>";
		$el = new CIBlockElement;
$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(),
  "DETAIL_TEXT"    => iconv("UTF-8", "Windows-1251", $textstr)
  );
$res2 = $el->Update($arFields2["ID"], $arLoadProductArray);
		}*/

	
/* 
 include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php"); //Класс для открытия файа csv

$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/dewal.csv'; //Сам файл csv
try {
	

Функция записи в файл, делал чтобы посмотреть что выводит, так как вылазила ошибка 504, потому что очень много товаров, а по 1 не нужно выводить, так как связь работает
	function object2file($value, $filename)
{
    $str_value = serialize($value);
    
    $f = fopen($filename, 'w');
    fwrite($f, $str_value);
    fclose($f);
}

    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();
 $result = array();
 $result2 = array();
    foreach ($get_csv as $value2) {//получаем csv по столбцам, все уже настроенно, выбор идет по коду 1с
	$array1[]=$value2[1];
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID', 'NAME','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C', 'XML_ID'));

	if($ob2 = $res->GetNextElement()){
	$arFields2 = $ob2->GetFields();
	
	$array2[]=$arFields2["PROPERTY_CODE1C"];
	$prob = str_replace(' ', '', $value2[2]);
	$nomcen = str_replace(',', '.', $prob);
	$val2["P"] = $nomcen;
	//$offersExist = CCatalogSKU::getExistOffers($arFields2["ID"]);
/*$obElement = new CIBlockElement();

$SKUPropertyId = 230; 
 $arOfferProps = array(
  $SKUPropertyId => $arFields2["ID"],
 );
 $arOfferFields = array(
  'NAME' => $arFields2["NAME"],
  'IBLOCK_ID' => 27,
  'ACTIVE' => 'Y',
  'XML_ID' => $arFields2["XML_ID"],
  'PROPERTY_VALUES' => $arOfferProps
 );

 $offerId = $obElement->Add($arOfferFields); // ID торгового предложения
$arFields = Array(
    "PRODUCT_ID" => $offerId,
    "CATALOG_GROUP_ID" => 1,
    "PRICE" => $val2["P"],
    "CURRENCY" => "RUB"
);

$res = CPrice::GetList(
        array(),
        array(
                "PRODUCT_ID" => $offerId,
                "CATALOG_GROUP_ID" => 1
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
$arFields = array(
                  "ID" => $offerId, 
                  "QUANTITY " => 999
                  );
if(CCatalogProduct::Add($arFields))
    echo "Добавили параметры товара к элементу каталога ".$offerId.'<br>';
else
    echo 'Ошибка добавления параметров<br>';

$db_res = CCatalogProduct::GetByID($offerId);
CCatalogProduct::Update($offerId, Array("QUANTITY"=>"999"));
	
	
	
	
	

 
	}
/*	if($value2[3] == "SECTION_ID" || $value2[3] == "#Н/Д"){continue;} //исключал товары у которых нет id разделов
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$value2[0]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C'));

	if($ob2 = $res->GetNextElement()){
	$arFields2 = $ob2->GetFields();
		//echo $value2[3];
		//записываем в массив , данные об id товара и его связь со столбцом id разделов, в csv они уже такие как на сайте
		$result[$arFields2["ID"]][] = $value2[3];
		
	}
	
Старый вариант связи
	$arr[] = Array("ID"=>$value2[3]);
$arSelect2 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_CODE1C", "SECTION_ID");
$arFilter2 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_CODE1C"=>$value2[0]);
$res2 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize"=>100),$arSelect2);
if($ob2 = $res2->GetNextElement()){
		$arFields2 = $ob2->GetFields();
		$result[$arFields2["ID"]][] = $value2[3];
/*$itemsSection = GetIBlockSectionList(26,false,false,Array("sort"=>"asc"),Array($vv));
if($arItem = $itemsSection->GetNext()) { 
$result[$arFields2["ID"]][] = $arItem["ID"];
}

}
	}
/*Здесь обращение к функции для записи txt
object2file($result, 'array.txt');

echo '<pre>';
print_r($result);
echo '</pre>';


//Здесь связываем товары с разделами, даже если у товара много разделов, закоментировал, чтобы при открытии ничего сразу не стало привязыватся
	foreach($result as $key => $val){
//CIBlockElement::SetElementSection($key, $val);
	}
*/

	//$offersExist = CCatalogSKU::getExistOffers($result);

 
 //$res["price"] = $result2;


/*foreach($res33 as $val){
		$i++;
	//$val["PRICE"] = $result2[$i];
	foreach($val as $key => $val2){
	echo '<pre>';
print_r($val2["ID"]);
echo '</pre>';
CIBlockElement::Delete($val2["ID"]);
//CCatalogProduct::Delete($val2["ID"]);
	}
	
}

$obElement = new CIBlockElement();

$SKUPropertyId = 230; 
 $arOfferProps = array(
  $SKUPropertyId => 85151,
 );
 $arOfferFields = array(
  'NAME' => "тест",
  'IBLOCK_ID' => 27,
  'ACTIVE' => 'Y',
  'PROPERTY_VALUES' => $arOfferProps
 );

 $offerId = $obElement->Add($arOfferFields); // ID торгового предложения
$arFields = Array(
    "PRODUCT_ID" => $offerId,
    "CATALOG_GROUP_ID" => 1,
    "PRICE" => 222,
    "CURRENCY" => "RUB"
);

$res = CPrice::GetList(
        array(),
        array(
                "PRODUCT_ID" => $offerId,
                "CATALOG_GROUP_ID" => 1
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
$arFields = array(
                  "ID" => $offerId, 
                  "QUANTITY " => 999
                  );
if(CCatalogProduct::Add($arFields))
    echo "Добавили параметры товара к элементу каталога ".$PRODUCT_ID.'<br>';
else
    echo 'Ошибка добавления параметров<br>';

$db_res = CCatalogProduct::GetByID($offerId);
CCatalogProduct::Update($offerId, Array("QUANTITY"=>"999"));
$result = array_diff($array1, $array2);
echo '<pre>';
print_r($result);
echo '</pre>';
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}*/

?>