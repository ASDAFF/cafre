<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
?>

<div class="welcome-panel">
  <h2>������ �������� ����������� �� CSV-�����</h2>
  <p>��������� ���� ������� CSV, ��������� ��������� �����. ��� ����� ������ ��������� �����.</p> 
  <a href="?GO=on">�������</a>
  <?php
if($_GET["GO"] == "on"){
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/offer_data.csv';
 
try {
    $csv = new CSV($fileurl); //��������� ��� csv
    $get_csv = $csv->getCSV();

    foreach ($get_csv as $value2) {
//	print_r($value2);
		$arSelect2 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID");
$arFilter2 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value2[1]);
$res2 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize"=>99999), $arSelect2);

$IBlockOffersCatalogId = 27; // ID ��������� ����������� (������ ���� �������� ���������)
$offerName = $value2[0]; // ������������ ��������� �����������
$offerPrice = $value2[3]; // ���� ��������� �����������

	$obElement = new CIBlockElement();
if($ob2 = $res2->GetNextElement()){
		$arFields2 = $ob2->GetFields();
$SKUPropertyId = 230; // ID �������� � ��������� ����������� ���� "�������� � ������� (SKU)"
	// �������� ��������� �����������
	$arOfferProps = array(
		$SKUPropertyId => $arFields2["ID"],
	);
	$arOfferFields = array(
		'NAME' => $arFields2["NAME"],
		'IBLOCK_ID' => $IBlockOffersCatalogId,
		'ACTIVE' => 'Y',
		'XML_ID' => $value2[0],
		'PROPERTY_VALUES' => $arOfferProps
	);
	
 	$arSelect3 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID");
$arFilter3 = Array("IBLOCK_ID"=>27, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value2[0]);
$res3 = CIBlockElement::GetList(Array(), $arFilter3, false, Array("nPageSize"=>99999), $arSelect3);
if($ob3 = $res3->GetNextElement()){
	$arFields3 = $ob3->GetFields();
	 $res33 = $obElement->Update($arFields3["ID"], $arOfferFields);
	 echo '<br/>';
 echo 'update_torgov:'.$arFields3["ID"];
}else{
	$offerId = $obElement->Add($arOfferFields); // ID ��������� �����������
	echo '<br/>';
	echo 'add_torgov:'.$offerId; 
}
 }	
 }


}
catch (Exception $e) { //���� csv ���� �� ����������, ������� ���������
    echo "������: " . $e->getMessage();
}
}
?>
  <p id="response"></p>
 </div>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");