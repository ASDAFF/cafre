<?
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
