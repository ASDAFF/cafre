<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID;
if(!CModule::IncludeModule("form")) return;
if(!CModule::IncludeModule("main")) return;

$FORM_SID = "RESUME";

$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch()) $lang = $arSite["LANGUAGE_ID"];
if(strlen($lang) <= 0) $lang = "ru";
	
WizardServices::IncludeServiceLang("forms.php", $lang);

/*��������� �������� �������*/
if($db_res = CEventType::GetList(array("TYPE_ID" => "FORM_FILLING_RESUME"))){ 
	$count = $db_res->SelectedRowsCount(); 
	if(!$count){
		$oEventType = new CEventType();
		$arFields = array("LID" => $lang, "EVENT_NAME" => "FORM_FILLING_RESUME", "NAME" => GetMessage("EVENT_NEW_RESUME_NAME"), "DESCRIPTION" => GetMessage("EVENT_NEW_RESUME_DESCRIPTION"));
		$oEventTypeSrcID = $oEventType->Add($arFields);
	}
}

/*��������� �������� ������ ��� ������� �����*/
$oEventMessage = new CEventMessage();
$by = "id"; $order = "asc";
$arFields = array("ACTIVE" => "Y", "EVENT_NAME" => "FORM_FILLING_RESUME", "LID" => WIZARD_SITE_ID, "EMAIL_FROM" => $wizard->GetVar("shopEmail"), "EMAIL_TO" => $wizard->GetVar("shopEmail"), "SUBJECT" => GetMessage("NEW_RESUME_EMAIL_SUBJECT"), "MESSAGE" => GetMessage("NEW_RESUME_EMAIL_TEXT"), "BODY_TYPE" => "html");
if($db_res = CEventMessage::GetList($by, $order, array("TYPE_ID" => "FORM_FILLING_RESUME", "SITE_ID" => array(WIZARD_SITE_ID)))){ 
	$count = $db_res->SelectedRowsCount(); 
	if($count > 0){
		while($res = $db_res->GetNext()){
			$oEventMessage->Update($res["ID"], $arFields);
		}
	}
	else{
		$oEventMessage->Add($arFields);
	}
}

/*�������� ������ �������� ����� �������*/
$arEventMessageIDs = array();
if($db_res = CEventMessage::GetList($by, $order, array ("TYPE_ID" => "FORM_FILLING_RESUME"))){ 
	while($res = $db_res->GetNext()){
		$arEventMessageIDs[] = $res["ID"];
	}
}

/*�������� ����� � � �����*/
$form_id = false;
$arFormSiteIDs = array();
if($arForm = CForm::GetBySID($FORM_SID)->Fetch()){
	if(($form_id = $arForm["ID"]) > 0){
		/*����� ����*/
		$arFormSiteIDs = CForm::GetSiteArray($arForm['ID']);
	}
}
$arFormSiteIDs[] = WIZARD_SITE_ID;
$arFormSiteIDs = array_unique($arFormSiteIDs);

/*��������� ����� ��� �������*/
if($form_id){
	$arFields = array(
		"arSITE"			=> $arFormSiteIDs,
		"arMAIL_TEMPLATE"	=> $arEventMessageIDs,
	);
	$form_id = CForm::Set($arFields, $form_id, "N");
	if($form_id < 0){
		return;
	}
}
else{
	$arFields = array(
		"NAME"				=> GetMessage("RESUME_FORM_NAME"),
		"SID"				=> $FORM_SID,
		"C_SORT"			=> 300,
		"BUTTON"			=> GetMessage("RESUME_BUTTON_NAME"),
		"DESCRIPTION"		=> GetMessage("RESUME_FORM_DESCRIPTION"),
		"DESCRIPTION_TYPE"	=> "text",
		"STAT_EVENT1"		=> "form",
		"STAT_EVENT2"		=> "",
		"arSITE"			=> $arFormSiteIDs,
		"arMENU"			=> array( "ru" => GetMessage("RESUME_FORM_NAME") ),
		"arGROUP"			=> array( "2" => "10" ),
		"arMAIL_TEMPLATE"	=> $arEventMessageIDs
	);	
	$form_id = CForm::Set($arFields);
	if($form_id < 0){
		return;
	}
	
	/* ��������� ������� */
	$arANSWER = array();
	$arANSWER[] = array( "MESSAGE" => " ", "C_SORT" => 100, "ACTIVE" => "Y", "FIELD_TYPE" => "text", "FIELD_PARAM" => "" );
	$arFields = array( "FORM_ID" => $form_id, "ACTIVE" => "Y", "TITLE" => GetMessage("RESUME_FORM_QUESTION_1"), "TITLE_TYPE" => "text", "SID" => "CLIENT_NAME", "C_SORT" => 100, "ADDITIONAL" => "N", "REQUIRED" => "Y", "arANSWER" => $arANSWER );
	CFormField::Set($arFields);

	$arANSWER = array();
	$arANSWER[] = array( "MESSAGE" => " ", "C_SORT" => 100, "ACTIVE" => "Y", "FIELD_TYPE" => "text", "FIELD_PARAM" => "class=\"phone\"" );
	$arFields = array( "FORM_ID" => $form_id, "ACTIVE" => "Y", "TITLE" => GetMessage("RESUME_FORM_QUESTION_2"), "TITLE_TYPE" => "text", "SID" => "PHONE", "C_SORT" => 200, "ADDITIONAL" => "N", "REQUIRED" => "Y", "arANSWER" => $arANSWER );
	CFormField::Set($arFields);

	$arANSWER = array();
	$arANSWER[] = array( "MESSAGE" => " ", "C_SORT" => 100, "ACTIVE" => "Y", "FIELD_TYPE" => "email", "FIELD_PARAM" => "" );
	$arFields = array( "FORM_ID" => $form_id, "ACTIVE" => "Y", "TITLE" => GetMessage("RESUME_FORM_QUESTION_3"), "TITLE_TYPE" => "text", "SID" => "EMAIL", "C_SORT" => 300, "ADDITIONAL" => "N", "REQUIRED" => "N", "arANSWER" => $arANSWER );
	CFormField::Set($arFields);

	$arANSWER = array();
	$arANSWER[] = array( "MESSAGE" => " ", "C_SORT" => 100, "ACTIVE" => "Y", "FIELD_TYPE" => "text", "FIELD_PARAM" => "left" );
	$arFields = array( "FORM_ID" => $form_id, "ACTIVE" => "Y", "TITLE" => GetMessage("RESUME_FORM_QUESTION_4"), "TITLE_TYPE" => "text", "SID" => "POST", "C_SORT" => 400, "ADDITIONAL" => "N", "REQUIRED" => "Y", "arANSWER" => $arANSWER );
	CFormField::Set($arFields);

	$arANSWER = array();
	$arANSWER[] = array( "MESSAGE" => " ", "C_SORT" => 100, "ACTIVE" => "Y", "FIELD_TYPE" => "file", "FIELD_PARAM" => "left" );
	$arFields = array( "FORM_ID" => $form_id, "ACTIVE" => "Y", "TITLE" => GetMessage("RESUME_FORM_QUESTION_5"), "TITLE_TYPE" => "text", "SID" => "FILE", "C_SORT" => 500, "ADDITIONAL" => "N", "REQUIRED" => "Y", "arANSWER" => $arANSWER );
	CFormField::Set($arFields);

	$arANSWER = array();
	$arANSWER[] = array( "MESSAGE" => " ", "C_SORT" => 100, "ACTIVE" => "Y", "FIELD_TYPE" => "textarea", "FIELD_PARAM" => "left" );
	$arFields = array( "FORM_ID" => $form_id, "ACTIVE" => "Y", "TITLE" => GetMessage("RESUME_FORM_QUESTION_6"), "TITLE_TYPE" => "text", "SID" => "RESUME_TEXT", "C_SORT" => 600, "ADDITIONAL" => "N", "REQUIRED" => "Y", "arANSWER" => $arANSWER );
	CFormField::Set($arFields);

	/* ��������� ������ */
	$arFields = array( "FORM_ID" => $form_id, "C_SORT" => 100, "ACTIVE" => "Y", "TITLE" => "DEFAULT", "DEFAULT_VALUE" => "Y", "arPERMISSION_VIEW" => array(2), "arPERMISSION_MOVE" => array(2), "arPERMISSION_EDIT" => array(2), "arPERMISSION_DELETE" => array(2) );
	CFormStatus::Set($arFields);
}
	
/*�������� �������*/
CWizardUtil::ReplaceMacros($bitrixTemplateDir."/header.php", array("RESUME_FORM_ID" => $form_id));
?>