<?
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
 CModule::IncludeModule("iblock");
 CModule::IncludeModule("catalog");
 CModule::IncludeModule("sale");
 /*
echo "<pre>";
print_r();
echo "</pre>";	

 $fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/cafre_new_sviaz.xls';
 $xml = simplexml_load_file($fileurl);
 /*������� xml*/
    /*foreach($xml->shop->offers->offer as $key => $items)
    {
		print_r($items);
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
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>"00000019810");
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

	

 include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php"); //����� ��� �������� ���� csv

$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/new_sviaz_raz.csv'; //��� ���� csv
try {
	

//������� ������ � ����, ����� ����� ���������� ��� �������, ��� ��� �������� ������ 504, ������ ��� ����� ����� �������, � �� 1 �� ����� ��������, ��� ��� ����� ��������
	function object2file($value, $filename)
{
    $str_value = serialize($value);
    
    $f = fopen($filename, 'w');
    fwrite($f, $str_value);
    fclose($f);
}

    $csv = new CSV($fileurl); //��������� ��� csv
    $get_csv = $csv->getCSV();
 $result = array();
 $result2 = array();
    foreach ($get_csv as $value2) {//�������� csv �� ��������, ��� ��� ����������, ����� ���� �� ���� 1�
	$arex = explode('/',$value2[3]);
	if($arex[9] != NULL){
	$code=$arex[9];
	}elseif($arex[8] != NULL){
	$code=$arex[8];
	}elseif($arex[7] != NULL){
	$code=$arex[7];
	}
	echo '<pre>';
	echo $code;
	echo '</pre>';
	//if($value2[3] == "SECTION_ID" || $value2[3] == "#�/�"){continue;} //�������� ������ � ������� ��� id ��������
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'CODE'=>$code);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C', 'CODE', 'IBLOCK_SECTION_ID'));

	if($ob2 = $res->GetNextElement()){
		
	$arFields2 = $ob2->GetFields(); 
	//$pos = strpos($arFields2["PROPERTY_CODE1C_VALUE"], $value2[0]);

	
	$db_old_groups = CIBlockElement::GetElementGroups($arFields2["ID"], true);
	while($ar_group = $db_old_groups->Fetch())
    $result[$arFields2["ID"]][] = $ar_group["ID"];
$result[$arFields2["ID"]][] = $value2[1];
	echo '<pre>';
	print_r($arFields2);
	echo '</pre>';
		//echo $value2[3];
		//���������� � ������ , ������ �� id ������ � ��� ����� �� �������� id ��������, � csv ��� ��� ����� ��� �� �����
		//$result[$arFields2["ID"]][] = $ar_new_groups;
		
	}
	}
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'CODE'=>"smyvaemyy_ukhod_rektifay_pro_fayber_pro_fiber_l_oreal_professionnel_200_ml");
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C', 'CODE', 'IBLOCK_SECTION_ID'));

	if($ob2 = $res->GetNextElement()){
		
	$arFields2 = $ob2->GetFields(); 
	//$pos = strpos($arFields2["PROPERTY_CODE1C_VALUE"], $value2[0]);

	
	$db_old_groups = CIBlockElement::GetElementGroups($arFields2["ID"], true);
	while($ar_group = $db_old_groups->Fetch())
    $result[$arFields2["ID"]][] = $ar_group["ID"];
$result[$arFields2["ID"]][] = 6526;
	echo '<pre>';
	print_r($arFields2);
	echo '</pre>';
		//echo $value2[3];
		//���������� � ������ , ������ �� id ������ � ��� ����� �� �������� id ��������, � csv ��� ��� ����� ��� �� �����
		//$result[$arFields2["ID"]][] = $ar_new_groups;
		
	}
	
	foreach($result as $key => $val){
		print_r($val);
//CIBlockElement::SetElementSection($key, $val);
	}
	/*$array1[]=$value2[1];
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

 $offerId = $obElement->Add($arOfferFields); // ID ��������� �����������
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
    echo "�������� ��������� ������ � �������� �������� ".$offerId.'<br>';
else
    echo '������ ���������� ����������<br>';

$db_res = CCatalogProduct::GetByID($offerId);
CCatalogProduct::Update($offerId, Array("QUANTITY"=>"999"));
	
	
	
	
	

 
	}
	if($value2[3] == "SECTION_ID" || $value2[3] == "#�/�"){continue;} //�������� ������ � ������� ��� id ��������
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$value2[0]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C'));

	if($ob2 = $res->GetNextElement()){
	$arFields2 = $ob2->GetFields();
		//echo $value2[3];
		//���������� � ������ , ������ �� id ������ � ��� ����� �� �������� id ��������, � csv ��� ��� ����� ��� �� �����
		$result[$arFields2["ID"]][] = $value2[3];
		
	}
	
������ ������� �����
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
/*����� ��������� � ������� ��� ������ txt
object2file($result, 'array.txt');

echo '<pre>';
print_r($result);
echo '</pre>';


//����� ��������� ������ � ���������, ���� ���� � ������ ����� ��������, ��������������, ����� ��� �������� ������ ����� �� ����� ������������
	foreach($result as $key => $val){
//CIBlockElement::SetElementSection($key, $val);
	}


	//$offersExist = CCatalogSKU::getExistOffers($result);

 
 //$res["price"] = $result2;


foreach($res33 as $val){
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
  'NAME' => "����",
  'IBLOCK_ID' => 27,
  'ACTIVE' => 'Y',
  'PROPERTY_VALUES' => $arOfferProps
 );

 $offerId = $obElement->Add($arOfferFields); // ID ��������� �����������
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
    echo "�������� ��������� ������ � �������� �������� ".$PRODUCT_ID.'<br>';
else
    echo '������ ���������� ����������<br>';

$db_res = CCatalogProduct::GetByID($offerId);
CCatalogProduct::Update($offerId, Array("QUANTITY"=>"999"));
$result = array_diff($array1, $array2);
echo '<pre>';
print_r($result);
echo '</pre>';*/
}
catch (Exception $e) { //���� csv ���� �� ����������, ������� ���������
    echo "������: " . $e->getMessage();
}

?>