<?php

global $APPLICATION;

IncludeModuleLangFile(__FILE__);

$arClassesList = array();

if(method_exists(CModule, "AddAutoloadClasses"))
{
    CModule::AddAutoloadClasses($arClassesList);
}
else
{
    foreach ($arClassesList as $sClassName => $sClassFile) {
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/coderoid.css3animations/".$sClassFile);
    }
}
