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
     * @param string $csv_file  - ���� �� csv-�����
     */
    public function __construct($csv_file) {
        if (file_exists($csv_file)) { //���� ���� ����������
            $this->_csv_file = $csv_file; //���������� ���� � ����� � ����������
        }
        else { //���� ���� �� ������ �� �������� ����������
            throw new Exception("���� \"$csv_file\" �� ������"); 
        }
    }
 
    public function setCSV(Array $csv) {
        //��������� csv ��� ��-������, 
        //���� ������� w, ��  ���������� ������� ���� � csv ����� �������
        $handle = fopen($this->_csv_file, "a"); 
 
        foreach ($csv as $value) { //�������� ������
            //����������, 3-�� �������� - ����������� ����
            fputcsv($handle, explode(";", $value), ";"); 
        }
        fclose($handle); //���������
    }
 
    /**
     * ����� ��� ������ �� csv-�����. ���������� ������ � ������� �� csv
     * @return array;
     */
    public function getCSV() {
        $handle = fopen($this->_csv_file, "r"); //��������� csv ��� ������
 
        $array_line_full = array(); //������ ����� ������� ������ �� csv
        //�������� ���� csv-����, � ������ ���������. 3-�� �������� ����������� ����
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) { 
            $array_line_full[] = $line; //���������� ������� � ������
        }
        fclose($handle); //��������� ����
        return $array_line_full; //���������� ���������� ������
    }
 
}
 
try {
    $csv = new CSV($fileurl); //��������� ��� csv
    /**
     * ������ �� CSV  (� ����� �� ����� � �������� ����)
     */
    echo "<h2>CSV �� ������:</h2>";
    $get_csv = $csv->getCSV();
	$arr = array();
    foreach ($get_csv as $value) { //�������� �� �������
        echo "ID ��������: " . $value[0] . "<br/>";
        echo "�������: " . $value[1] . "<br/>";
        echo "��������: " . $value[2] . "<br/>";
			 echo "������� ��������: <xml>" . $value[3] . "</xml><br/>";
			  echo "��������: <xml>" . $value[8] . "</xml><br/>";
			   echo "����������: " . $value[9] . "<br/>";
			   echo "������: " . $value[10] . "<br/>";
        echo "--------<br/>"; 
	}
}
catch (Exception $e) { //���� csv ���� �� ����������, ������� ���������
    echo "������: " . $e->getMessage();
}?
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>