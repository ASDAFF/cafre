<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/import/class.php");
CModule::IncludeModule('iblock'); 
CModule::includeModule('catalog');
?>

<div class="welcome-panel">
  <h2>Удаление элементов</h2>
  <p>Загрузите файл формата CSV, дождитесь обработки файла. Это может занять некоторое время.</p> 
  <a href="?GO=on">Погнали</a>
  <?php
if($_GET["GO"] == "on"){
$IBLOCK_ID = 24;
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>IntVal($IBLOCK_ID), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>28833), $arSelect);
while($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 if(CIBlock::GetPermission($IBLOCK_ID)>='W')
{
    $DB->StartTransaction();
    if(!CIBlockElement::Delete($arFields["ID"]))
    {
        $strWarning .= 'Error!';
        $DB->Rollback();
    }
    else{
        $DB->Commit();
	echo "Элементы удалены";
	}
}
}
}
?>
  <p id="response"></p>
 </div>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");