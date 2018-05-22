<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class MCART extends PHPMailer{
    
    static public function log_write($str){
	if($str != ''){
	    $str = str_replace(array("\r","\r\n","\n"), "", $str);
	    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/log.txt", gmdate('Y-m-d H:i:s')."\t".$str."\n", FILE_APPEND);
	}
    }
}
