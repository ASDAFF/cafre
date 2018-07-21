<?
function RDir( $path ) {
 if ( file_exists( $path ) AND is_dir( $path ) ) {
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
      if ( $element != '.' AND $element != '..' )  {
        $tmp = $path . '/' . $element;
        chmod( $tmp, 0777 );
        if ( is_dir( $tmp ) ) {
         RDir( $tmp );
        } else {
          unlink( $tmp );
       }
     }
   }
   // закрываем папку
    closedir($dir);
    // удаляем саму папку
   if ( file_exists( $path ) ) {
     rmdir( $path );
   }
 }
}

$path = '/var/www/www-root/data/www/test.cafre.ru/bitrix/managed_cache';
if ( file_exists( $path ) AND is_dir( $path ) ) {
   	// открываем папку
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
    	// удаляем только содержимое папки
      	if ( $element != '.' AND $element != '..' )  {
	        $tmp = $path . '/' . $element;
        	chmod( $tmp, 0777 );
        	if ( is_dir( $tmp ) ) {
				
         		RDir( $tmp );
        	} else {
          		unlink( $tmp );
       		}
     	}
   	}
   	// закрываем папку
    closedir($dir);    
}
$path = '/var/www/www-root/data/www/test.cafre.ru/bitrix/cache';
if ( file_exists( $path ) AND is_dir( $path ) ) {
   	// открываем папку
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
    	// удаляем только содержимое папки
      	if ( $element != '.' AND $element != '..' )  {
	        $tmp = $path . '/' . $element;
        	chmod( $tmp, 0777 );
        	if ( is_dir( $tmp ) ) {
				
         		RDir( $tmp );
        	} else {
          		unlink( $tmp );
       		}
     	}
   	}
   	// закрываем папку
    closedir($dir);    
}

$path = '/var/www/www-root/data/www/cafre.ru/bitrix/managed_cache';
if ( file_exists( $path ) AND is_dir( $path ) ) {
   	// открываем папку
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
    	// удаляем только содержимое папки
      	if ( $element != '.' AND $element != '..' )  {
	        $tmp = $path . '/' . $element;
        	chmod( $tmp, 0777 );
        	if ( is_dir( $tmp ) ) {
				
         		RDir( $tmp );
        	} else {
          		unlink( $tmp );
       		}
     	}
   	}
   	// закрываем папку
    closedir($dir);    
}
$path = '/var/www/www-root/data/www/cafre.ru/bitrix/cache';
if ( file_exists( $path ) AND is_dir( $path ) ) {
   	// открываем папку
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
    	// удаляем только содержимое папки
      	if ( $element != '.' AND $element != '..' )  {
	        $tmp = $path . '/' . $element;
        	chmod( $tmp, 0777 );
        	if ( is_dir( $tmp ) ) {
				
         		RDir( $tmp );
        	} else {
          		unlink( $tmp );
       		}
     	}
   	}
   	// закрываем папку
    closedir($dir);    
}
$path = '/var/www/www-root/data/www/cafre.ru/upload/resize_cache';
if ( file_exists( $path ) AND is_dir( $path ) ) {
   	// открываем папку
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
    	// удаляем только содержимое папки
      	if ( $element != '.' AND $element != '..' )  {
	        $tmp = $path . '/' . $element;
        	chmod( $tmp, 0777 );
        	if ( is_dir( $tmp ) ) {
				
         		RDir( $tmp );
        	} else {
          		unlink( $tmp );
       		}
     	}
   	}
   	// закрываем папку
    closedir($dir);    
}

//RDir( $dir );