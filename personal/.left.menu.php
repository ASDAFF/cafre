<?
global $USER;
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$aMenuLinks = Array(
	Array(
		"������������ ������", 
		"/personal/personal-data/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"������� �������", 
		"/personal/history-of-orders/", 
		Array(), 
		Array(), 
		"" 
	)
);
	
if ($arUser["EXTERNAL_AUTH_ID"]!="socservices")
{
	$aMenuLinks[] = Array(
						"������� ������", 
						"/personal/change-password/", 
						Array(), 
						Array(), 
						"" 
					);
}	
$aMenuLinks[] = Array(
	"�������� �� ����� � �������", 
	"/personal/subscribe/", 
	Array(), 
	Array(), 
	"" 
);
if($USER->isAuthorized()){
$aMenuLinks[] = Array(
	"�����", 
	"?logout=yes&login=yes", 
	Array(), 
	Array("class"=>"exit"), 
	"" 
);
}

?>