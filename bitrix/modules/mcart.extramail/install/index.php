<?
global $MESS;
$PathInstall = str_replace('\\', '/', __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
IncludeModuleLangFile($PathInstall.'/install.php');
include($PathInstall.'/version.php');

if (class_exists('mcart_extramail')) return;

class mcart_extramail extends CModule
{
	var $MODULE_ID = "mcart.extramail";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $MODULE_GROUP_RIGHTS = 'N';

	public function __construct()
	{
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$path = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($path.'/version.php');

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->PARTNER_NAME = GetMessage('MCART_PARTNER_NAME');
		$this->PARTNER_URI = 'MCART_PARTNER_URI';

		$this->MODULE_NAME = GetMessage('MCART_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('MCART_MODULE_DESCRIPTION');
	}

	
	
	function DoInstall()
	{
		global $APPLICATION;

		if (!IsModuleInstalled("mcart.extramail"))
		{
			if(function_exists("custom_mail")){
			    $APPLICATION->throwException(GetMessage('MCART_ERROR_INSTALL'));			
			    return false;
			}		    
		    
			$this->InstallDB();
			$this->InstallEvents();
			$this->InstallFiles();
			
		}
		return true;
	}

	function DoUninstall()
	{
		$this->UnInstallFiles();
		$this->UnInstallDB();
		$this->UnInstallEvents();
		
		
		return true;
	}
	
	
	function InstallDB() {

		
		RegisterModule("mcart.extramail");	
		return true;
	
			
	}
	
	function UnInstallDB()
	{
		COption::RemoveOption("mcart.extramail");		//clear db
		UnRegisterModule("mcart.extramail");
		return true;
	}
	
	
	
	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
	
	    if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface'))
		{
			$filename = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php';
		if (file_exists($filename))
		    {
		        $heandle = fopen($filename,'rb');
		        $buffer = fread($heandle,filesize($filename));
		        $openTag = $openTag + substr_count($buffer,'<?');
		        $closeTag = $closeTag + substr_count($buffer,'?>');
		        fclose($heandle);
		        if($openTag !== $closeTag)
		        {
			    file_put_contents($filename, ' require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/classes/general/include_part.php");?'.'>', FILE_APPEND);
			}				
			else
			{    
			    file_put_contents($filename, '<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/classes/general/include_part.php");?'.'>', FILE_APPEND);
			}
		    }
		    else
		    {    
		        file_put_contents($filename, '<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/classes/general/include_part.php");?'.'>');
		    }
		    
		}
	return true;
	}
	
	function UnInstallFiles()
	{	
		    $filename = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php';
	    if (file_exists($filename))
		{
		    $stroka = '<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/classes/general/include_part.php");?>';
		    $stroka2 = 'require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/classes/general/include_part.php");';
                    $file = fopen($filename, 'rb');
		    $text = fread($file, filesize($filename));
		    fclose($file);
		    $file = fopen($filename, 'w');
		    if(strpos($text, $stroka) !== false)
		    {
		        $tmpStr = trim(str_replace($stroka, '', $text));
		    }
		    else
		    {
		        $tmpStr = str_replace($stroka2, '', $text);
		    }
		    fwrite($file, $tmpStr);
		    fclose($file);
		}
		return true;
	}
	
	

}
?>