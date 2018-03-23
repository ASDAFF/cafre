<?
header('Content-Type: text/html; charset= utf-8');
set_time_limit(0);
if(isset($_REQUEST['getcommit'])&&$_REQUEST['getcommit']=='true') {
	$all=array();
	$files=array();
	$tmp=file('0.txt'); 
	$newtmp=array_reverse($tmp);
	exec('git show '.$_REQUEST['commit'], $all);
	foreach ($all as $k => $v) {
		if(strpos($v, 'iff --git ')===1) {
			$date=array();
			//$filename=substr(str_replace('diff --git ', '', $v), 0, strpos(str_replace('diff --git ', '', $v), ' '));
			$filename=trim(substr($v, strrpos($v, ' ')));
			foreach ($newtmp as $stroka) { 	
				if(!strpos($stroka, substr($filename, 1))===false) {
					//$stroka=substr($stroka, strpos($stroka, '(')+1);
					$date=explode(' ', $stroka);
					break;
				}
			}	
			$files[]= "<input type='checkbox' name='".$filename."' value='Y'>&nbsp;&nbsp;&nbsp;<a class='more_commit_file' title='Смотреть изменения' href='?commit=".$_REQUEST['commit']."&filename=".$filename."'>".$filename.(strpos($all[$k+1], 'eleted file mode')===false?'':' (удален файл)').(!empty($date)?' (<span class="i" '.($date[0]==date("d.m.Y")?'style="color:red"':'').'>выгружен '.$date[0].' '.$date[1].'</span>)':'')."</a>";
		}
	}
	echo implode(',<br>', $files)."<br>";
}
elseif(isset($_REQUEST['clean'])&&$_REQUEST['clean']=='true') {
	$f = fopen('0.txt', 'w');
	fclose($f);
	echo "Готово";
}
else {
	$echo='';

	$ftp_server="77.244.210.154";
	$ftp_user_name="git";
	$ftp_user_pass="5U3h1D6x";	
	$conn_id = ftp_connect($ftp_server);	
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	ftp_pasv($conn_id, true);
	ftp_chdir($conn_id, '../');

	$connection = ssh2_connect('77.244.210.154', 22);
	ssh2_auth_password($connection, 'root', 'SW0LS9bM3QL7');
	$sftp = ssh2_sftp($connection);
	$commit=$_REQUEST['commit'];
	$name_commit=$_REQUEST['name_commit'];
	foreach ($_REQUEST as $key => $value) {
		if(intval($key)>0) {
			$file = substr($value, 1);
			$dir=explode('/', $file);
			$dir[count($dir)-1]='';
			$str='';
			foreach ($dir as $k => $value2) {
				if($value2=='') continue;
				if(!in_array($str==''?$value2:$str.$value2, (array)ftp_nlist($conn_id,$str))) {
					ssh2_sftp_mkdir($sftp, '/var/www/www-root/data/www/cafre.ru/'.$str.$value2.'/');
				}
				$str.=$value2.'/';	
			}
			if(ssh2_scp_send($connection,  $_SERVER['DOCUMENT_ROOT'].$file, '/var/www/www-root/data/www/cafre.ru'.$file,0644)) {
	 			$echo.= "$file загружен на сервер\n";
	 			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/git/0.txt', PHP_EOL.date('d.m.Y h:i').' '.$file.' ('.$name_commit.')', FILE_APPEND);
			} else {
	 			$echo.= "Не удалось загрузить $file на сервер\n";
			}
		}
	}
	echo $echo;
} ?>