<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $GLOBAL;
foreach($arResult['ITEMS'] as $key => $arItems) {
	if($key == 1 || $key == 3 || $key == 'OPT' || $key == 'BASE' || $key == 'Интернет Розница') continue;
	foreach($arItems['VALUES'] as $value) {	
		if($key==250) {
			$resBrand = CIBlockSection::GetByID($value['URL_ID']);
			$resBrand = $resBrand->GetNext();						
			$GLOBAL.= $arParams['TEK_URL'].$resBrand['CODE'].'/'.PHP_EOL;
		}
		else
			$GLOBAL.= $arParams['TEK_URL'].'f-'.strtolower($arItems['CODE']).'-is-'.$value['URL_ID'].'/'.PHP_EOL;
	}
}
?>