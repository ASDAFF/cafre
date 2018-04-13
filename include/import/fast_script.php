<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
 require_once $_SERVER["DOCUMENT_ROOT"].'/include/import/phpexcel/Classes/PHPExcel.php'; // Ю??????? PHPExcel
 require_once $_SERVER["DOCUMENT_ROOT"].'/include/import/phpexcel/Classes/PHPExcel/Writer/Excel5.php'; // Ю??????? PHPExcel
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');



$arSelect2 = Array("ID", "NAME", "XML_ID", "PREWIEV_TEXT", "DETAIL_TEXT");
$arFilter2 = Array("IBLOCK_ID"=>26); //,"XML_ID"=>$value2[0]
$res1 = CIBlockElement::GetList(Array(), $arFilter2, false, Array(), $arSelect2);
	while($ar_fields = $res1->GetNextElement())
	{
		$arFields1 = $ar_fields->GetFields();
		$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_CML2_LINK", "QUANTYTY");
$arFilter = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arFields1["ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
if($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 $quant = CCatalogProduct::GetByID($arFields["ID"]);
 if((int)$quant["QUANTITY"] <= 0){
  echo "<pre>";
  print_r($quant["QUANTITY"]);
		print_r($arFields1);
		echo "</pre>";
	//	CIBlockElement::SetPropertyValueCode($arFields1["ID"], "KOL_SORT_CAT", 0);
 }else{
	 echo "<pre>";
  print_r($quant["QUANTITY"]);
		print_r($arFields1);
		echo "</pre>";
	// CIBlockElement::SetPropertyValueCode($arFields1["ID"], "KOL_SORT_CAT", 1);
 }
 
}
		
	}
	
/*	 //CIBlockElement::SetPropertyValueCode($ar_fields["ID"], "KOL_SORT_CAT", 6058);
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_CML2_LINK", "QUANTYTY");
$arFilter = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>83768);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
if($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 $quant = CCatalogProduct::GetByID($arFields["ID"]);
  print_r($quant["QUANTITY"]);
}

$arFields1 = $ar_fields->GetFields();
		$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_CML2_LINK", "QUANTYTY");
$arFilter = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arFields1["ID"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
if($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 $quant = CCatalogProduct::GetByID($arFields["ID"]);
 if((int)$quant["QUANTITY"] <= 0){
 /* echo "<pre>";
  print_r($quant["QUANTITY"]);
		print_r($arFields1);
		echo "</pre>";
		CIBlockElement::SetPropertyValueCode($arFields1["ID"], "KOL_SORT_CAT", 0);
 }else{
	 echo "<pre>";
  print_r($quant["QUANTITY"]);
		print_r($arFields1);
		echo "</pre>";
	 //CIBlockElement::SetPropertyValueCode($arFields1["ID"], "KOL_SORT_CAT", 1);
 }
 
}


$arFields = $ar_fields->GetFields();
		if(strpos($arFields["PREWIEV_TEXT"], "/products/") || strpos($arFields["DETAIL_TEXT"], "/products/")){
			$mass[] = array("ID" => $arFields["ID"], "name"=>iconv("Windows-1251", "UTF-8", $arFields["NAME"]));
		
		}elseif(strpos($arFields["PREWIEV_TEXT"], "/categories/") || strpos($arFields["DETAIL_TEXT"], "/categories/")){
			$mass[] = array("ID" => $arFields["ID"], "name"=>iconv("Windows-1251", "UTF-8", $arFields["NAME"]));
		}

$document = new \PHPExcel();

$sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе

$columnPosition = 0; // Начальная координата x
$startLine = 2; // Начальная координата y

// Вставляем заголовок в "A2" 
$sheet->setCellValueByColumnAndRow($columnPosition, $startLine, 'Bad url');

// Выравниваем по центру
$sheet->getStyleByColumnAndRow($columnPosition, $startLine)->getAlignment()->setHorizontal(
    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Объединяем ячейки "A2:C2"
$document->getActiveSheet()->mergeCellsByColumnAndRow($columnPosition, $startLine, $columnPosition+2, $startLine);

// Перекидываем указатель на следующую строку
$startLine++;

// Массив с названиями столбцов
$columns = ['Number', 'ID', 'Name'];

// Указатель на первый столбец
$currentColumn = $columnPosition;

// Формируем шапку
foreach ($columns as $column) {
    // Красим ячейку
    $sheet->getStyleByColumnAndRow($currentColumn, $startLine)
        ->getFill()
        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('4dbf62');

    $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $column);

    // Смещаемся вправо
    $currentColumn++;
}

// Формируем список
foreach ($mass as $key=>$catItem) {
	// Перекидываем указатель на следующую строку
    $startLine++;
    // Указатель на первый столбец
    $currentColumn = $columnPosition;
    // Вставляем порядковый номер
    $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $key+1);

    // Ставляем информацию об имени и цвете
    foreach ($catItem as $value) {
        $currentColumn++;
    	$sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $value);
	
		/*$sheet->getStyleByColumnAndRow($currentColumn, $startLine)
        ->getFill()
        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB($catItem["color"]);
    }
}

$objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel5');
$objWriter->save("BadUrl.xls");
*/

?>