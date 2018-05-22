<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
Search(dirname(__FILE__));

function Search($path)
{

	if (is_dir($path)) // dir
	{
		$dir = opendir($path);
		while($item = readdir($dir))
		{
			if ($item == '.' || $item == '..'/*|| $item == 'install'|| $item == 'lang'|| $item == 'classes'|| $item == '..'*/)
				continue;

			Search($path.'/'.$item);
		}
		closedir($dir);
	}
	else // file
	{
		
			if ((substr($path,-3) == '.js' || substr($path,-4) == '.php' ) && $path != __FILE__)
				Process($path);
		
	}
}

function Process($file)
{


	
		if (!is_writable($file))
			echo('Файл не доступен на запись: '.$file);
	
		$content = file_get_contents($file);
echo GetStringCharset($content).'<br>';
		if (GetStringCharset($content) == 'utf8')
			return;

		if ($content === false)
			echo('Не удалось прочитать файл: '.$file);

		if (file_put_contents($file, mb_convert_encoding($content,  'cp1251')) === false)
			echo('Не удалось сохранить файл: '.$file);
		
	
}

function GetStringCharset($str)
{ 
	global $APPLICATION;
	if (preg_match("/[\xe0\xe1\xe3-\xff]/",$str))
		return 'cp1251';
	$str0 = $APPLICATION->ConvertCharset($str, 'utf8', 'cp1251');
	if (preg_match("/[\xe0\xe1\xe3-\xff]/",$str0,$regs))
		return 'utf8';
	return 'ascii';
}