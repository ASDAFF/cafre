<?
//файл с проверкой логина и отдачей на вывод в форму
require("../bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$UserLogin = $USER->GetLogin(); //$_REQUEST['MY_LOGIN'];

	$rsUser = CUser::GetByLogin($UserLogin);
    if($arUser = $rsUser->Fetch()) {

			//echo 'yes';//$arUser['PASSWORD'];
			echo json_encode(array(
        'result' => 'yes',
        'name' => iconv('WINDOWS-1251','utf-8', $arUser["LAST_NAME"]),
		'phone' => $arUser["PERSONAL_PHONE"],
		'mail' => $arUser["EMAIL"]
			)); 
    }
    else {
        echo json_encode(array(
        'result' => 'no'));
    }
	?>