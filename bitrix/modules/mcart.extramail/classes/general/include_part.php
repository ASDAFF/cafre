<?
function custom_mail($to, $subject, $message, $additional_headers='', $additional_params='')
{
    global $APPLICATION;
    
    $sInspect = (COption::GetOptionString("mcart.extramail","smtp_use") =="Y"? true : false);
    
    if(CModule::IncludeModule('mcart.extramail') && $sInspect)
	    {
			$marker = (COption::GetOptionString("mcart.extramail","smtp_debug")=="Y"? true : false); //get check debug enable/disable
			$mail = new MCART;
			$getMass = array();
                        $mail->Debugoutput ='MCART::log_write';    //function replacement for errors debug(hook)
			//$mail->setLanguage(LANG);				    //language for debug message
                        $mail->isSMTP();                                        // Set use SMTP mode
			$mail->Timeout = 5;
			
                        if($marker){						    //check debug enable/disable
                            $mail->SMTPDebug = 2;}                                               
                        else{
                            $mail->SMTPDebug = 0;}			
			//The parameters passed to the function for debug(begin)
			    if($marker){
				if($additional_headers !=''){
				    MCART::log_write("***********IN_HEADERS BEGIN*******************");
				    $tmpHeaders = $additional_headers;
				    foreach(explode("\n", $additional_headers) as $val){
					MCART::log_write($val);
				    }
				    MCART::log_write("***********IN_HEADERS END*********************");
				}    
				if($additional_params !=''){
				    MCART::log_write("***********IN_PARAMS BEGIN********************");
				    $tmpHeaders = $additional_params;
				    foreach(explode("\n", $additional_params) as $val){
					MCART::log_write($val);
				    }
				    MCART::log_write("***********IN_PARAMS END**********************");
				}    
			    }    
			// The parameters passed to the function for debug(end) 
			
			$mail->Host       = COption::GetOptionString("mcart.extramail","smtp_host");  // Specify main and backup SMTP servers
			$mail->Port       = (int)COption::GetOptionString("mcart.extramail","smtp_port");        // TCP port to connect to
			$mail->Username   = COption::GetOptionString("mcart.extramail","smtp_login");
			$mail->Password   = COption::GetOptionString("mcart.extramail","smtp_password");
			$mail->SMTPSecure = COption::GetOptionString("mcart.extramail","smtp_secure");  // Enable TLS encryption, `ssl` also accepted
			$mail->SMTPAuth   = (COption::GetOptionString("mcart.extramail","smtp_auth")=="Y"?'true':'');    // Enable SMTP authentication
			$mail->CharSet    = LANG_CHARSET; //'UTF-8';                                   //utf-8 CharSet for headers
        		$mail->addAddress($to);     // Add a recipient
			//coding site name
			if(LANG_CHARSET == "windows-1251"){
			    $mail->setFrom(COption::GetOptionString("mcart.extramail","mod_email_from")!=''? COption::GetOptionString("mcart.extramail","mod_email_from") : COption::GetOptionString("main","email_from"), $APPLICATION->ConvertCharset(COption::GetOptionString("main","site_name",''),'windows-1251', 'UTF-8'));}
			else{
			    $mail->setFrom(COption::GetOptionString("mcart.extramail","mod_email_from")!=''? COption::GetOptionString("mcart.extramail","mod_email_from") : COption::GetOptionString("main","email_from"), COption::GetOptionString("main","site_name",''));}
			
			if($mail->Host == '' || $mail->Port ==''){ return 7;}	//report - SMTP settings error
			
                        $getMass = explode("\n", $additional_headers);                  //get array
                        
                        //parse headers -------------------------------------------------------
                        foreach ($getMass as $value)
                        {
			    $markFrom ='';
                            //manipulation for CC:
                            if(preg_match("/^CC:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value); 
                                if(strpos($mV[1], ',')!==false){
                                    foreach(explode(",", $mV[1]) as $valCC){
                                        $mail->addCC(trim($valCC, "\x20"));
                                    }
                                }
                                else{ 
                                        $mail->addCC(trim($mV[1], "\x20")); 
                                    }
                            }
                           //manipulation for BCC:
                           if(preg_match("/^BCC:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value); 
                                if(strpos($mV[1], ',')!==false){
                                    foreach(explode(",", $mV[1]) as $valBCC){
                                      $mail->addBCC(trim($valBCC, "\x20"));
                                    }
                                }
                                else { 
                                      $mail->addBCC(trim($mV[1], "\x20")); 
                                }
                           }                                
                           //manipulation for Reply-To:
                           if(preg_match("/^Reply-To:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value); 
                                $mail->addReplyTo(trim($mV[1], "\x20"));
                            }
                           //manipulation for In-Reply-To:
                           if(preg_match("/^In-Reply-To:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value); 
                                $mail->addReplyTo(trim($mV[1], "\x20"));
                            }                            
                           //manipulation for X-EVENT_NAME: 
                           if(preg_match("/^X-EVENT_NAME:/", $value)){
                               $mV = array();
                               $mV = explode(":", $value); 
                               $mail->addCustomHeader($mV[0],trim($mV[1], "\x20"));
                            } 
                            //manipulation for X-CONTENT_TYPE:
                            if(preg_match("/^Content-Type:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value);
				$mail->addCustomHeader($mV[0],trim($mV[1], "\x20"));
                            }
                            //manipulation for $IS_CONTENT_TRANSFER_ENCODING:
                            if(preg_match("/^Content-Transfer-Encoding:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value);
                                $mail->addCustomHeader($mV[0],trim($mV[1], "\x20"));
                            }                            
                            //manipulation for X_PRIORITY:
                            if(preg_match("/^X-Priority:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value);  
                                $mail->addCustomHeader($mV[0], trim($mV[1], "\x20"));
                             } 
                             //manipulation for X_MID:
                            if(preg_match("/^X-MID:/", $value)){
                                $mV = array();
                                $mV[] = trim($value, "\x20");
                                $mV[] = substr($mV[0], 6); 
                                $mail->addCustomHeader("X-MID", trim($mV[1], "\x20"));
                            }
			    //manipulation for Disposition-Notification-To:
                            if(preg_match("/^Disposition-Notification-To:/", $value)){
                                $mV = array();
                                $mV = explode(":", $value);
				$mail->addCustomHeader($mV[0],trim($mV[1], "\x20"));
                            }
                             //manipulation for List-Unsubscribe:
                            if(preg_match("/^List-Unsubscribe:/", $value)){
                                $mV = array();
                                $mV[] = trim($value, "\x20");
                                $mV[] = substr($mV[0], 17); 
                                $mail->addCustomHeader("List-Unsubscribe:", trim($mV[1], "\x20"));
                            }
                             //manipulation for List-Subscribe:
                            if(preg_match("/^List-Subscribe:/", $value)){
                                $mV = array();
                                $mV[] = trim($value, "\x20");
                                $mV[] = substr($mV[0], 15); 
                                $mail->addCustomHeader("List-Subscribe:", trim($mV[1], "\x20"));
                            }			    
                        }
			$mail->Subject = $subject;
			$mail->Body    = $message;
			$mail->isHTML(true);                                  // Set email format to HTML
                          if(!$mail->Send()){
				if($marker){ 
				    MCART::log_write($mail->ErrorInfo);
				}
			    return 2;
			    }
                           else {
				if($marker){ 
				    MCART::log_write('MESSAGE HAS BEEN SENT');
				}
                            return 1;   
                           }
                        $mail->clearAddresses();
			$mail->clearAllRecipients();
			$mail->clearBCCs();
			$mail->clearCCs();
			$mail->clearCustomHeaders();
			$mail->clearReplyTos();
	    }else{ 
		    if($additional_params!=''){
			if(mail($to, $subject, $message, $additional_headers, $additional_params)){
			    return 3;
			}else{ return 4;}
		    }
		    else{
			if(mail($to, $subject, $message, $additional_headers)){
			    return 5;
			}else{ return 6;}
		    }
	    }		
}
?>