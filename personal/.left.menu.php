<?
global $USER;
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

$aMenuLinks = Array(
	Array(
		"Персональные данные", 
		"/personal/personal-data/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"История заказов", 
		"/personal/history-of-orders/", 
		Array(), 
		Array(), 
		"" 
	)
);
	
if ($arUser["EXTERNAL_AUTH_ID"]!="socservices")
{
	$aMenuLinks[] = Array(
						"Сменить пароль", 
						"/personal/change-password/", 
						Array(), 
						Array(), 
						"" 
					);
}	
$aMenuLinks[] = Array(
	"Подписка на акции и новости", 
	"/personal/subscribe/", 
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