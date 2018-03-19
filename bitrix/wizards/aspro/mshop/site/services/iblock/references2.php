<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)	die();

if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";	

set_time_limit(0);

if (!CModule::IncludeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

$HL_ID = $_SESSION["MSHOP_HBLOCK_COLOR_ID"];
unset($_SESSION["MSHOP_HBLOCK_COLOR_ID"]);

//adding rows
WizardServices::IncludeServiceLang("references.php", LANGUAGE_ID);

use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;

if($HL_ID){
	$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
	$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

	$entity_data_class = $hlentity->getDataClass();
	$arColors = array(
		"PURPLE" => "references_files/iblock/7ba/7ba2a7b1acd6e79ca8def3a0596ff157.png",
		"BROWN" => "references_files/iblock/a58/a58485a354c23434e95e847d611edd50.png",
		"WHITE" => "references_files/iblock/a7a/a7a2d3ad7a0075541dcf54b9af53af20.png",
		"BLUE" => "references_files/iblock/90d/90d5ff168d93936c2af78c96955d2e35.png",
		"BLACK" => "references_files/iblock/9b8/9b8fcde0eea1a721e7fd1f7d8857647e.png",
		"GRAY" => "references_files/iblock/d48/d48a5e82db39fb92537ae3a72658dc98.png",
		"RED" => "references_files/iblock/1be/1bec2207cc076925cc07c68b079003a7.png",
	);
	$sort = 100;
	foreach($arColors as $colorName => $colorFile){
		$arData = array(
			'UF_NAME' => GetMessage("WZD_REF_COLOR_".$colorName),
			'UF_FILE' =>
				array (
					'name' => ToLower($colorName).".png",
					'type' => 'image/png',
					'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$colorFile
				),
			'UF_SORT' => $sort,
			'UF_DEF' => ($sort > 100) ? "0" : "1",
			'UF_XML_ID' => ToLower($colorName)
		);
		$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$HL_ID, $arData);
		$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$HL_ID, null, $arData);
		$result = $entity_data_class::add($arData);
		$sort += 100;
	}
}
?>