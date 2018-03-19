<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
CModule::includeModule('file');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_MORE_PHOTO'=>false);

$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'XML_ID','PROPERTY_MORE_PHOTO'));
$conunt2 = 0;
while($ar_fields = $res->GetNext())
{
echo "<pre>";
//print_r($ar_fields);
//echo count($ar_fields);
echo "</pre>";
$conunt2++;
}
echo $conunt2;
/*$array = array();
$res44 = CIBlockElement::GetList(array('XML_ID'=>'ASC', 'PREVIEW_PICTURE' => false), array('IBLOCK_ID'=>26), false, array("nPageSize"=>1));
while($ob44 = $res44->GetNextElement()){
  $arFields44 = $ob44->GetFields();
echo "<pre>";
	print_r($arFields44);
	echo "</pre>";
	//echo count($arFields44);
  $array[] = $arFields44["XML_ID"];
}
*/
?>

<div class="welcome-panel">
  <h2>Импорт фото с товарами из CSV-файла</h2>
  <p>Загрузите файл формата CSV, дождитесь обработки файла. Это может занять некоторое время.</p> 
  <a href="?GO=on">Погнали</a>
  <?php
if($_GET["GO"] == "on"){
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/MainPhoto.csv';
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    $get_csv = $csv->getCSV();	
 $result = array();
 $array_file = array();
 $conunt = 0;
 foreach ($get_csv as $key => $value) {
$arf=array('ACTIVE'=>'Y','IBLOCK_ID'=>26, 'PROPERTY_ON_PHOT'=>false, 'XML_ID'=>$value[1]);

$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arf, false, false, array('ID','IBLOCK_ID','ACTIVE', 'XML_ID','PROPERTY_ON_PHOT'));

if($ar_fields = $res->GetNext())
{
	
/*$str2 = explode('.', $value[4]); 
$a2 = $str2[0];
$b2 = $str2[1];*/
//$result[$value[1]][] = array('VALUE'=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/photos_big/'.$a2.'_big.'.$b2),'DESCRIPTION'=>$a2);

$str2 = explode('.', $value[4]); 
$a2 = $str2[0];
$b2 = $str2[1];
//$new = CIBlockElement::SetPropertyValuesEx($ar_fields["ID"], false, array("ON_PHOT" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/photos_big/'.$a2.'_big.'.$b2)));
//$result[$value[1]][] = array('VALUE'=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/photos_big/'.$a2.'_big.'.$b2),'DESCRIPTION'=>$a2);

echo "<pre>";
print_r($ar_fields);
echo "</pre>";
$conunt++;

}
	/*if(in_array($value[1], $array)){
	 $str = explode('.', $value[4]); 
$a = $str[0];
$b = $str[1];
$filename = $_SERVER["DOCUMENT_ROOT"]."/upload/photos_big/".$a."_big.".$b."";
if(file_exists($filename)) {
$arSelect44 = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID", 'PROPERTY_MORE_PHOTO', 'PROPERTY_ON_PHOT', "PREVIEW_PICTURE");
$arFilter44 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value[1]);
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>1), $arSelect44);
if($ob44 = $res44->GetNextElement()){
$arFields44 = $ob44->GetFields();
$str2 = explode('.', $value[3]); 
$a2 = $str[0];
$b2 = $str[1];
$el = new CIBlockElement;
$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "PREVIEW_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/photos_big/'.$a2.'_big.'.$b2),
  );
	//$res = $el->Update($arFields2["ID"], $arLoadProductArray);
	echo "<pre>";
	//print_r($arFields44["ID"]);
	echo "</pre>";
}*/

	//echo "The file $filename exists";
	/*$arSelect44 = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID", 'PROPERTY_MORE_PHOTO', 'PROPERTY_ON_PHOT', "PREVIEW_PICTURE");
$arFilter44 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value[1]);
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>99999), $arSelect44);
if($ob44 = $res44->GetNextElement()){
$arFields44 = $ob44->GetFields();
$fields = $ob44->GetProperties();
$str2 = explode('.', $value[4]); 
$a2 = $str2[0];
$b2 = $str2[1];
foreach($fields["MORE_PHOTO"]["VALUE"] as $prop){
$rsFile = CFile::GetByID($prop);
$arFile = $rsFile->Fetch();
$str3 = explode('_', $arFile["ORIGINAL_NAME"]); 
$a3 = $str3[0];
if($a3 == $a2){
$PROPERTY_CODE = "ON_PHOT";
$PROPERTY_VALUE = $arFile;
	//CIBlockElement::SetPropertyValues($arFields44["ID"], 26, $PROPERTY_VALUE, $PROPERTY_CODE);
echo "<pre>";
 print_r($arFields44["ID"]);
echo "</pre>";

}
}

	/*$rsFile3 = CFile::GetByID($prop);
$arFile3 = $rsFile->Fetch();

$PROPERTY_CODE = "ON_PHOT";
$PROPERTY_VALUE = $arFile3;
CIBlockElement::SetPropertyValues($arFields44["ID"], 26, $PROPERTY_VALUE, $PROPERTY_CODE);


	//$result[$value[1]][] = array('VALUE'=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/photos_big/'.$a2.'_big.'.$b2),'DESCRIPTION'=>$a2);
}*/
}
//echo $conunt;
/*foreach($result as $key => $val){
	$arSelect44 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID", 'PROPERTY_MORE_PHOTO');
$arFilter44 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$key);
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>9999), $arSelect44);
if($ob44 = $res44->GetNextElement()){
		$arFields44 = $ob44->GetFields();
//CIBlockElement::SetPropertyValuesEx($arFields44["ID"], 26, array("MORE_PHOTO" => $val));
echo '<pre>';
print_r($result);
echo '</pre>';
}
}*/
	/*
	 $str = explode('.', $value[4]); 
$a = $str[0];
$b = $str[1];
$filename = $_SERVER["DOCUMENT_ROOT"]."/upload/photos_big/".$a."_big.".$b."";
if(file_exists($filename)) {
	//echo "The file $filename exists";
$arSelect44 = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID", 'PROPERTY_MORE_PHOTO', "PREVIEW_PICTURE");
$arFilter44 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value[1]);
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>1), $arSelect44);
if($ob44 = $res44->GetNextElement()){

			$arFields44 = $ob44->GetFields();
$str2 = explode('.', $value[4]); 
$a2 = $str2[0];
$b2 = $str2[1];

 $rsFile = CFile::GetByID($arFields44["PROPERTY_MORE_PHOTO_VALUE"]);
        $arFile = $rsFile->Fetch();
$str3 = explode('_', $arFile["ORIGINAL_NAME"]); 
$a3 = $str2[0];
if($a3 == $a2){

echo "<pre>";
	echo "id:"; print_r($arFields44["ID"]);
	echo "<br />";
	echo "photo_name:"; print_r($arFile);
echo "</pre>";
	}
	//$result[$value[1]][] = array('VALUE'=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/photos_big/'.$a2.'_big.'.$b2),'DESCRIPTION'=>$a2);
}
}*/

/*


if($value[4] == 1){

$arSelect2 = Array("ID", "NAME", "PREVIEW_PICTURE", "DATE_ACTIVE_FROM", "XML_ID", "SECTION_ID");
$arFilter2 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value[1]);
$res2 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize"=>9999),$arSelect2);
if($ob2 = $res2->GetNextElement()){
		$arFields2 = $ob2->GetFields();

$str = explode('.', $value[3]); 
$a = $str[0];
$b = $str[1];
$el = new CIBlockElement;
$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "PREVIEW_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/photos_big/'.$a.'_big.'.$b),
  );
$res = $el->Update($arFields2["ID"], $arLoadProductArray);
echo '<pre>';
print_r($arFields2);
echo '</pre>';
}
	}

}
} else {
	 $array_file[] = array("XML_ID" => $value[2],"IMG" => $value[5]);
}


}
}
}
 }
//CIBlockElement::SetPropertyValuesEx($arFields44["ID"], 26, array("MORE_PHOTO" => $result));
/*foreach($result as $key => $val){
	$arSelect44 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID", 'PROPERTY_MORE_PHOTO');
$arFilter44 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$key);
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>9999), $arSelect44);
if($ob44 = $res44->GetNextElement()){
		$arFields44 = $ob44->GetFields();
CIBlockElement::SetPropertyValuesEx($arFields44["ID"], 26, array("MORE_PHOTO" => $val));
echo '<pre>';
print_r($result);
echo '</pre>';
}
}*/

/*echo '<pre>';
print_r($array_file);
echo 'Количество: ('.count($array_file).')</pre>';
 foreach ($get_csv as $key => $value) {
	 if($value[4] == 1){
$arSelect2 = Array("ID", "NAME", "PREVIEW_PICTURE", "DATE_ACTIVE_FROM", "XML_ID", "SECTION_ID");
$arFilter2 = Array("IBLOCK_ID"=>26, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "XML_ID"=>$value[1]);
$res2 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize"=>9999),$arSelect2);
if($ob2 = $res2->GetNextElement()){
		$arFields2 = $ob2->GetFields();

$str = explode('.', $value[3]); 
$a = $str[0];
$b = $str[1];
$el = new CIBlockElement;
$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
  "PREVIEW_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/phot/'.$a.'_original.'.$b),
  );
$res = $el->Update($arFields2["ID"], $arLoadProductArray);
echo '<pre>';
print_r($res);
echo '</pre>';
}
	}
if($value[4] == 0){
$str2 = explode('.', $value[3]); 
$a2 = $str2[0];
$b2 = $str2[1];
$result[$value[1]][] = array('VALUE'=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/phot/'.$a2.'_original.'.$b2),'DESCRIPTION'=>$a2);
}
	}
*/
foreach($result as $key => $val){
	$arSelect44 = Array("ID", "NAME", "DATE_ACTIVE_FROM", "XML_ID", 'PROPERTY_ON_PHOT');
$arFilter44 = Array("IBLOCK_ID"=>26, "ACTIVE"=>"Y", "XML_ID"=>$key);
$res44 = CIBlockElement::GetList(Array(), $arFilter44, false, Array("nPageSize"=>9999), $arSelect44);
if($ob44 = $res44->GetNextElement()){
		$arFields44 = $ob44->GetFields();
//CIBlockElement::SetPropertyValuesEx($arFields44["ID"], 26, array("ON_PHOT" => $val));

}
}
echo '<pre>';
print_r($result);
echo '</pre>';
echo $conunt;
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}

}
?>
  <p id="response"></p>
 </div>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");