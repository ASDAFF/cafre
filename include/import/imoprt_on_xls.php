 <?
 require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
 require_once $_SERVER["DOCUMENT_ROOT"].'/include/import/phpexcel/Classes/PHPExcel.php'; // Ю??????? PHPExcel
 require_once $_SERVER["DOCUMENT_ROOT"].'/include/import/phpexcel/Classes/PHPExcel/Writer/Excel5.php'; // Ю??????? PHPExcel

 
CModule::IncludeModule("iblock");
 $rsParentSection = CIBlockSection::GetByID(6501);
if ($arParentSection = $rsParentSection->GetNext())
{
   $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); 
   // ???????? ???????
   $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
   $i = 0;
     $mass = array();
function shortName($fullName) {
    $res = explode(' ', $fullName);
    return $res[0] . " " . mb_substr($res[1], 0, 1) . '. ' . mb_substr($res[2], 0, 1) . '.';
}
   while ($arSect = $rsSect->GetNext())
   {
	   $section_id = $arSect["ID"];
	   $name_sec = iconv("Windows-1251", "UTF-8", $arSect["NAME"]);
	   $level = $arSect["DEPTH_LEVEL"];
	   if($level != 2){
	   $SVIAZ_SECTION_ID = $arSect["IBLOCK_SECTION_ID"];
	   }
if($level == "2"){

	$mass[] = array("ID" => $section_id, "name"=>$name_sec, "color"=>"4dbf62");
	echo 'true';
}elseif($level == "3"){
	$mass[] = array("ID" => $section_id, "name"=>$name_sec, "color"=>"d8bfd8");
}else{
	$mass[] = array("ID" => $section_id, "name"=>$name_sec, "color"=>"faf2f3");
}
	
	$i++;
   }

}
$catList = [
	['name' => 'Tom', 'color' => 'red'],
	['name' => 'Bars', 'color' => 'white'],
	['name' => 'Jane', 'color' => 'Yellow'],
];
echo "<pre>";
//print_r($catList);
echo "</pre>";
echo "<pre>";
//print_r($mass);
echo "</pre>";

$document = new \PHPExcel();

$sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе

$columnPosition = 0; // Начальная координата x
$startLine = 2; // Начальная координата y

// Вставляем заголовок в "A2" 
$sheet->setCellValueByColumnAndRow($columnPosition, $startLine, 'New Raz');

// Выравниваем по центру
$sheet->getStyleByColumnAndRow($columnPosition, $startLine)->getAlignment()->setHorizontal(
    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Объединяем ячейки "A2:C2"
$document->getActiveSheet()->mergeCellsByColumnAndRow($columnPosition, $startLine, $columnPosition+2, $startLine);

// Перекидываем указатель на следующую строку
$startLine++;

// Массив с названиями столбцов
$columns = ['Number', 'SECTION_ID', 'Name', 'Color'];

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
	
		$sheet->getStyleByColumnAndRow($currentColumn, $startLine)
        ->getFill()
        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB($catItem["color"]);
    }
}

$objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel5');
$objWriter->save("SecListNew.xls");