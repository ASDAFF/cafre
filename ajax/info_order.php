<?
//файл с проверкой логина и отдачей на вывод в форму
require("../bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$UserLogin = $_REQUEST['MY_LOGIN'];
	$rsUser = CUser::GetByLogin($UserLogin);
    if($arUser = $rsUser->Fetch()) {

			//echo 'yes';//$arUser['PASSWORD'];
			echo json_encode(array(
        'result' => 'yes',
        'name' => $arUser["LAST_NAME"],
		'phone' => $arUser["PERSONAL_PHONE"],
		'all_pops' => $arUser
			)); 
    }
    else {
        echo json_encode(array(
        'result' => 'no'));
    }
	?>