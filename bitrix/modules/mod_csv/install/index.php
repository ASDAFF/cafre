<?Class mod_csv extends CModule
{
    public $MODULE_ID = 'mod_csv';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    // �������� ������
    public function mod_csv() {
        $this->MODULE_NAME = '������ ������� CSV';
        $this->MODULE_DESCRIPTION = '������ ������� CSV';
        $this->MODULE_VERSION = '1.0';
        $this->MODULE_VERSION_DATE = '2017-03-04';
    }

    // ���������
    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
    }

    // ��������
    public function DoUninstall() {
        UnRegisterModule($this->MODULE_ID);
    }
}
?>