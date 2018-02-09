<?
require("../bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($_REQUEST['label']=='findEmail') {

    $UserLogin = $_REQUEST['email'];
    $rsUser = CUser::GetByLogin($UserLogin);
    if($arUser = $rsUser->Fetch()) {
        
        echo $arUser['ID'];
    }
	else echo "no";
}

if($_REQUEST['label']=='auth_basket') {

    $UserLogin = $_REQUEST['MY_LOGIN'];
	$rsUser = CUser::GetByLogin($UserLogin);
    if($arUser = $rsUser->Fetch()) {
		$salt = substr($arUser['PASSWORD'], 0, (strlen($arUser['PASSWORD']) - 32));
        $realPassword = substr($arUser['PASSWORD'], -32);
        $password = md5($salt.$_REQUEST['MY_PASS']);

        if($password == $realPassword) {
			echo 'yes';//$arUser['PASSWORD'];
            global $USER;
            $USER->Authorize($arUser['ID']);
        }
        else echo 'no';
    }
    else {
        echo 'no';
    }
}
?>