<?php
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$fileurl = $_SERVER["DOCUMENT_ROOT"].'/0.xml';
$xml = simplexml_load_file($fileurl);
print_r($xml);
?>