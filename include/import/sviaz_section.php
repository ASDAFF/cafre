<?
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
 CModule::IncludeModule("iblock");
 CModule::IncludeModule("catalog");
 include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php"); //����� ��� �������� ���� csv

$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/SecListNew2.csv'; //��� ���� csv
try {
/*
������� ������ � ����, ����� ����� ���������� ��� �������, ��� ��� �������� ������ 504, ������ ��� ����� ����� �������, � �� 1 �� ����� ��������, ��� ��� ����� ��������
	function object2file($value, $filename)
{
    $str_value = serialize($value);
    
    $f = fopen($filename, 'w');
    fwrite($f, $str_value);
    fclose($f);
}
*/
    $csv = new CSV($fileurl); //��������� ��� csv
    $get_csv = $csv->getCSV();
 $result = array();
    foreach ($get_csv as $value2) {//�������� csv �� ��������, ��� ��� ����������, ����� ���� �� ���� 1�
	if($value2[3] == "SECTION_ID" || $value2[3] == "#�/�"){continue;} //�������� ������ � ������� ��� id ��������
	$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_CODE1C'=>$value2[0]);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'PROPERTY_CODE1C'));

	if($ob2 = $res->GetNextElement()){
	$arFields2 = $ob2->GetFields();
		//echo $value2[3];
		//���������� � ������ , ������ �� id ������ � ��� ����� �� �������� id ��������, � csv ��� ��� ����� ��� �� �����
		$result[$arFields2["ID"]][] = $value2[3];
		
	}
/*	
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

}*/
	}
/*����� ��������� � ������� ��� ������ txt
object2file($result, 'array.txt');

echo '<pre>';
print_r($result);
echo '</pre>';
*/

//����� ��������� ������ � ���������, ���� ���� � ������ ����� ��������, ��������������, ����� ��� �������� ������ ����� �� ����� ������������
	foreach($result as $key => $val){
//CIBlockElement::SetElementSection($key, $val);
	}

	
}
catch (Exception $e) { //���� csv ���� �� ����������, ������� ���������
    echo "������: " . $e->getMessage();
}

?>