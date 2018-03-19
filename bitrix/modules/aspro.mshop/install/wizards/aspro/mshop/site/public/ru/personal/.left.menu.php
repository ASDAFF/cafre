<?
global $USER;
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$aMenuLinks = Array(
	Array(
		"Персональные данные", 
		"#SITE_DIR#personal/personal-data/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"История заказов", 
		"#SITE_DIR#personal/history-of-orders/", 
		Array(), 
		Array(), 
		"" 
	)
);
	
if ($arUser["EXTERNAL_AUTH_ID"]!="socservices")
{
	$aMenuLinks[] = Array(
						"Сменить пароль", 
						"#SITE_DIR#personal/change-password/", 
						Array(), 
						Array(), 
						"" 
					);
}	
$aMenuLinks[] = Array(
	"Подписка на акции и новости", 
	"#SITE_DIR#personal/subscribe/", 
	Array(), 
	Array(), 
	"" 
);
if($USER->isAuthorized()){
$aMenuLinks[] = Array(
	"Выйти", 
	"?logout=yes&login=yes", 
	Array(), 
	Array("class"=>"exit"), 
	"" 
);
}

?>