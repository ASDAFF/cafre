<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("import");
ini_set('max_execution_time', '0');
@ignore_user_abort(true);
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/include/import/product_data.csv';
/*
$array = array_map('str_getcsv', file($_SERVER["DOCUMENT_ROOT"].'/include/import/product_data.csv'));
$header = array_shift($array);

array_walk($array, '_combine_array', $header);
function _combine_array(&$row, $key, $header) {
  $row = array_combine($header, $row);
}
foreach($array as $key => $value){
	$v = trim($value);
	
}
print_r($array);*/

class CSV {
 
    private $_csv_file = null;
 
    /**
     * @param string $csv_file  - путь до csv-файла
     */
    public function __construct($csv_file) {
        if (file_exists($csv_file)) { //Если файл существует
            $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную
        }
        else { //Если файл не найден то вызываем исключение
            throw new Exception("Файл \"$csv_file\" не найден"); 
        }
    }
 
    public function setCSV(Array $csv) {
        //Открываем csv для до-записи, 
        //если указать w, то  ифнормация которая была в csv будет затерта
        $handle = fopen($this->_csv_file, "a"); 
 
        foreach ($csv as $value) { //Проходим массив
            //Записываем, 3-ий параметр - разделитель поля
            fputcsv($handle, explode(";", $value), ";"); 
        }
        fclose($handle); //Закрываем
    }
 
    /**
     * Метод для чтения из csv-файла. Возвращает массив с данными из csv
     * @return array;
     */
    public function getCSV() {
        $handle = fopen($this->_csv_file, "r"); //Открываем csv для чтения
 
        $array_line_full = array(); //Массив будет хранить данные из csv
        //Проходим весь csv-файл, и читаем построчно. 3-ий параметр разделитель поля
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) { 
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle); //Закрываем файл
        return $array_line_full; //Возвращаем прочтенные данные
    }
 
}
 
try {
    $csv = new CSV($fileurl); //Открываем наш csv
    /**
     * Чтение из CSV  (и вывод на экран в красивом виде)
     */
    echo "<h2>CSV до записи:</h2>";
    $get_csv = $csv->getCSV();
	$arr = array();
    foreach ($get_csv as $value) { //Проходим по строкам
        echo "ID продукта: " . $value[0] . "<br/>";
        echo "Артикул: " . $value[1] . "<br/>";
        echo "Название: " . $value[2] . "<br/>";
			 echo "Краткое описание: <xml>" . $value[3] . "</xml><br/>";
			  echo "Описание: <xml>" . $value[8] . "</xml><br/>";
			   echo "Активность: " . $value[9] . "<br/>";
			   echo "Ссылка: " . $value[10] . "<br/>";
        echo "--------<br/>"; 
	}
}
catch (Exception $e) { //Если csv файл не существует, выводим сообщение
    echo "Ошибка: " . $e->getMessage();
}?
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>