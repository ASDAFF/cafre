<?
global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));

IncludeModuleLangFile(__FILE__);
if(class_exists("coderoid.css3animations")) return;

Class coderoid_css3animations extends CModule
{
    var $MODULE_ID = "coderoid.css3animations";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    function coderoid_css3animations()
    {
        $arModuleVersion = array();

        $this->MODULE_NAME = GetMessage("CODEROID_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("CODEROID_MODULE_DESCRIPTION");

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        } else {
            $this->MODULE_VERSION = '1.0.0';
            $this->MODULE_VERSION_DATE = '2015-05-30';
        }
        $this->PARTNER_NAME = "CODEROID";
        $this->PARTNER_URI = "http://www.coderoid.ru/";
    }

    function DoInstall()
    {
        CModule::IncludeModule("iblock");
        $arQuery = CSite::GetList($sort="sort", $order="desc", Array());
        while ($res = $arQuery->Fetch()) {
            $sids[] = $res["ID"];
        }

        if (IsModuleInstalled("coderoid.css3animations"))
        {
            $this->DoUninstall();
            return;
        }
        else
        {
            global $DB, $APPLICATION, $step;
            $RIGHT = $APPLICATION->GetGroupRight("coderoid.css3animations");
            if ($RIGHT>="W")
            {
                $step = IntVal($step);
                $this->InstallFiles();
                $this->InstallDB();
                $GLOBALS["errors"] = $this->errors;
                $APPLICATION->IncludeAdminFile(GetMessage("CODEROID_INSTALL_TITLE"),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/coderoid.css3animations/install/step1.php");
            }

        }
    }

    function DoUninstall()
    {
        global $DB, $APPLICATION, $step;
        $step = IntVal($step);
        $this->UnInstallDB();
        $this->UninstallFiles();
        $APPLICATION->IncludeAdminFile(GetMessage("CODEROID_UNINSTALL_TITLE"),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/coderoid.css3animations/install/unstep1.php");
    }

    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/coderoid.css3animations/install/components",$_SERVER["DOCUMENT_ROOT"]."/bitrix/components",true,true);
    }

    function UninstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/coderoid/css3animations");
    }

    function InstallDB()
    {
        global $APPLICATION;
        $this->errors = FALSE;

        RegisterModule("coderoid.css3animations");
    }

    function  UnInstallDB()
    {
        UnRegisterModule("coderoid.css3animations");
    }
}
?>
