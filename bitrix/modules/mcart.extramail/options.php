<?
global $MESS;
IncludeModuleLangFile(__FILE__);
define("ADMIN_MODULE_NAME", "mcart.extramail");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");

$module_id = "mcart.extramail";
CModule::IncludeModule($module_id);

$MOD_RIGHT = $APPLICATION->GetGroupRight($module_id);				//Get right

//buttons for tab test
if($MOD_RIGHT=="W")								//Check right
{ 
    if(isset($_POST['send_t']) || isset($_POST['clear_log']) || isset($_POST['get_log'])){
	if(isset($_POST['send_t'])){
	    $test_mail = $_POST['test_adr'];		
	    $test_mess = $_POST['test_mess'];
	    $test_subject = $_POST['test_subject'];
	    $x = bxmail($test_mail, $test_subject, $test_mess);
	    if($x == 1){							//if message has been send
		echo GetMessage("MCART_MAIL_SEND_OK");				
	    }elseif($x == 2){							//if module not active
		echo GetMessage("MCART_MAIL_SEND_ERROR");							
	    }elseif($x == 3 || $x == 5){					//if module not active and message send mail()- php 
		echo GetMessage("MCART_MOD_N_ACT_MAIL_OK");				
	    }elseif($x == 4 || $x == 6){					//if module not active and error mail()- php
		echo GetMessage("MCART_MOD_N_ACT_MAIL_ERROR");				
	    }elseif($x == 7){							//if module not active and error mail()- php
		echo GetMessage("MCART_SMTP_SET_ERROR");			//Settings smtp error	
	    }
	    $outmsg = ob_get_contents();
	    ob_clean();
	    echo $outmsg;
        exit();
    }elseif(isset($_POST['get_log'])){
        $LogTXT = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/log.txt");
        if($LogTXT !== false ){
	    //if coding file win1251
	    if(LANG_CHARSET == 'windows-1251'){echo $APPLICATION->ConvertCharset($LogTXT, 'windows-1251', 'UTF-8');}
            else{ echo $LogTXT;}
        }else{
            echo GetMessage("MCART_ERROR_OPEN_LOG");}
        $outmsg2 = ob_get_contents();
        ob_clean();
        $outmsg2 = htmlspecialchars($outmsg2);
        echo $outmsg2 = str_replace("\n", "<br/>", $outmsg2); 
        exit();
    }elseif(isset($_POST['clear_log'])){
	if(fopen($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/mcart.extramail/log.txt", "w")!= false){
	    fclose($f);
		echo GetMessage("MCART_CLEAR_LOG_OK");
	    }else{
		echo GetMessage("MCART_CLEAR_LOG_ERROR");}
	$outmsg3 = ob_get_contents();
	ob_clean();
	echo $outmsg3;
	exit();
	}
    
    }
}   

if($MOD_RIGHT<"W")
{    
    CAdminMessage::ShowMessage(GetMessage("MCART_ACCESS_ERROR"));
    return;
}

if($MOD_RIGHT=="W")
{ 
  if($REQUEST_METHOD=="POST" && isset($_POST['save']) || isset($_POST['apply']))    //save params in db......
    {
        COption::SetOptionString($module_id, "smtp_use", $_REQUEST['smtp_use']);
        COption::SetOptionString($module_id, "smtp_host", $_REQUEST['smtp_host']);
        COption::SetOptionString($module_id, "smtp_port", $_REQUEST['smtp_port']);
        COption::SetOptionString($module_id, "smtp_login",$_REQUEST['smtp_login']);
        COption::SetOptionString($module_id, "smtp_password",$_REQUEST['smtp_password']);
        COption::SetOptionString($module_id, "smtp_secure",$_REQUEST['smtp_secure']);
        COption::SetOptionString($module_id, "smtp_auth",$_REQUEST['smtp_auth']);
	COption::SetOptionString($module_id, "mod_email_from",$_REQUEST['mod_email_from']);	
        COption::SetOptionString($module_id, "smtp_debug",$_REQUEST['smtp_debug']); //end save
    }
}

if($MOD_RIGHT=="W"):

    $arAllOptions[] = GetMessage("MCART_FIRST_PARAMS");
    
    $arAllOptions[] = array("smtp_use", GetMessage("opt_smtp_check"), "", array("checkbox", ""));
    $arAllOptions[] = array("mod_email_from", GetMessage("opt_smtp_email_from"), "", array("text",35));    
    $arAllOptions[] = array("smtp_host", GetMessage("opt_smtp_host"), "", array("text", 35));
    $arAllOptions[] = array("smtp_port", GetMessage("opt_smtp_host_port"), "", array("text", 5));
    $arAllOptions[] = array("smtp_secure", GetMessage("opt_smtp_host_secure"), "", array("selectbox", array(
                    'None' => 'None',
                    'ssl' => 'SSL',
                    'tls' => 'TLS'
                    )));
    $arAllOptions[] = array("smtp_auth", GetMessage("opt_smtp_user_auth"),"", array("checkbox", ""));
    $arAllOptions[] = array("smtp_login", GetMessage("opt_smtp_user_login"), "", array("text",35));
    $arAllOptions[] = array("smtp_password", GetMessage("opt_smtp_user_password"), "", array("password",35));
    $arAllOptions[] = array('note' => GetMessage("MCART_REQUIRED_FIELDS_1"));
   
    $arAllOptions2[] = array('note' => GetMessage("MCART_REQUIRED_FIELDS"));
    $arAllOptions2[] = GetMessage("MCART_OTHER_PARAMS");
    $arAllOptions2[] = array("smtp_debug", GetMessage("MCART_DEBUG"), "", array("checkbox", ""));    
    $arAllOptions2[] = array('note' => GetMessage("MCART_DEBUG_MESSAGE"));    
    
$aTabs = array();
$aTabs[] = array("DIV" => "edit0", "TAB" => GetMessage("MCART_TAB_EXTRAMAIL_SETTINGSOUTSMTP"), "ICON" => "extramail_settings", "TITLE" => GetMessage("MCART_TAB_TITLE_EXTRAMAIL_SETTINGSOUTSMTP"));   
$aTabs[] = array("DIV" => "edit1", "TAB" => GetMessage("MCART_TAB_TITLE_TEST"), "ICON" => "extramail_settings", "TITLE" => GetMessage("MCART_TAB_TEST_DEBUG"));
$aTabs[] = array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "main_settings", "TITLE" => GetMessage("MAIN_TAB_RIGHTS"));

$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>

<?$tabControl->Begin();?>
   <form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?lang=<? echo LANG;?>&mid=<? echo $module_id?>" ENCTYPE="multipart/form-data">
<?$tabControl->BeginNextTab();?>
        
    <? __AdmSettingsDrawList($module_id, $arAllOptions);?> 
        
<?$tabControl->BeginNextTab();?>
    <tr class="heading">
	<td colspan="2" style="height: 20px;"><label id="sys_msg" ><? echo GetMessage("MCART_SET_FIELD");?></label></td>
    </tr>
    <tr>
	<td colspan="2" style="height: 20px;"></td>
    </tr>
    <tr>
	<td width="40%"><? echo GetMessage("MCART_MAIL_TO");?></td>
	<td width="60%"><input type="text" id="smtp_mail_to" name="smtp_mail_to" value="" size="35" /></td>
    </tr>
    <tr>
	<td width="40%"><? echo GetMessage("MCART_MAIL_SUBJECT");?></td>
	<td width="60%"><input type="text" id="smtp_mail_subject" name="smtp_mail_subject" value="" size="35" /></td>
    </tr>
    <tr>
	<td width="40%"><? echo GetMessage("MCART_MAIL_MESSAGE");?></td>
	<td width="60%"><textarea id="smtp_mail_massege" name="smtp_mail_massege" cols="31" rows="10" ></textarea></td>
    </tr>    
    <tr>
	<td width="40%"></td>
	<td width="60%"><input type="button" value="<? echo GetMessage("MCART_MAIL_TO_BUTTON");?>" onclick="MsendOK();" /></td>
    </tr>

    <? __AdmSettingsDrawList($module_id, $arAllOptions2);?>
    
    <tr>
	<td width="40%"><input type="button" value="<? echo GetMessage("MCART_MAIL_GET_LOG");?>" onclick="GetClearLog('get');" /></td>
	<td width="60%"><input type="button" value="<? echo GetMessage("MCART_MAIL_CLEAR_LOG");?>" onclick="GetClearLog('clear');" /></td>
    </tr> 
    
       
<?$tabControl->BeginNextTab();?>     
        
    <?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>

<?$tabControl->Buttons(array('btnCancel' => false, 'btnSaveAndAdd' => false)); ?>   
        
<?$tabControl->End();?>
   </form>
<?endif;?> 
<script>
function MsendOK()
{   var adress_to = document.getElementsByName('smtp_mail_to')[0].value;
    var message = document.getElementsByName('smtp_mail_massege')[0].value;
    var subject = document.getElementsByName('smtp_mail_subject')[0].value;
    var outmsg = document.getElementById('sys_msg').value;
    var mass = new Array();
    mass["sessid"] = '<? echo bitrix_sessid();?>';
    mass["send_t"]=10;
    mass["test_mess"] = message;
    mass["test_adr"] = adress_to;
    mass["test_subject"] = subject;
    BX.ajax.post("<?echo $APPLICATION->GetCurPage()?>?lang=<? echo LANG;?>&mid=<? echo $module_id?>", mass, function(data){sys_msg.innerHTML = data; });
    window.setTimeout(function(){ sys_msg.innerHTML = '<? echo GetMessage("MCART_SET_FIELD");?>';}, 5000);
    //return; 
 }
function GetClearLog(params)
{ 
    if(params == 'get'){
        var outmsg = document.getElementById('sys_msg').value;
        var mass = new Array();
        mass["sessid"] = '<? echo bitrix_sessid();?>';
        mass["get_log"]=10;
        BX.ajax.post("<?echo $APPLICATION->GetCurPage()?>?lang=<? echo LANG;?>&mid=<? echo $module_id?>", mass, function(data){
        if(data==''){    
                sys_msg.innerHTML ='<? echo GetMessage('MCART_GET_LOG_EMPTY');?>';
                window.setTimeout(function(){ sys_msg.innerHTML = '<? echo GetMessage("MCART_SET_FIELD");?>';}, 5000);
		if(typeof(wLog)=== 'object'){ 
		    wLog.close();
		    wLog = undefined;
		    //console.log("Close Object");
		}
	    }
        else{
		if(typeof(wLog)=== 'undefined'){					//if window not open
		    wLog = window.open("about:blank", "hello", "width=500,height=200");
		    wLog.document.write(data);
		    //console.log("Object is not created. Create Object and push data");
		}else{								//reopen window
		    wLog.close();							//close
		    wLog = undefined ;						//clear object
		    wLog = window.open("about:blank", "hello", "width=500,height=200");
		    wLog.document.write(data);
		    //console.log("Recreate Object.");
		}
            }           
        });
 
    }
    if(params == 'clear'){
        var outmsg = document.getElementById('sys_msg').value;
        var mass = new Array();
        mass["sessid"] = '<? echo bitrix_sessid();?>';
        mass["clear_log"]=10;
        BX.ajax.post("<?echo $APPLICATION->GetCurPage()?>?lang=<? echo LANG;?>&mid=<? echo $module_id?>", mass, function(data){ sys_msg.innerHTML = data; });
        window.setTimeout(function(){ sys_msg.innerHTML = '<? echo GetMessage("MCART_SET_FIELD");?>';}, 5000); 
    }
 }
</script>