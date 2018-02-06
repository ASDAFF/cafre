<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");



$obTest=wsdl('GetAllBrands');
//pr($obTest);
//exit;
$arBrands=array();
foreach($obTest->GetAllBrandsResult->ExportedData->BrandDTO as $key=>$obBrand):
	//echo $obBrand->Name;
	$arBrands[$obBrand->BrandId]=array('NAME'=>iconv("utf-8", "windows-1251", $obBrand->Name), 'PREVIEW_TEXT'=>iconv("utf-8", "windows-1251", $obBrand->BriefDescription), 'DETAIL_TEXT'=>iconv("utf-8", "windows-1251", $obBrand->Description), 'ENABLED'=>$obBrand->Enabled); 
endforeach;

echo sizeof($arBrands);
pr($arBrands);
//exit;

$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>8, 'SECTION_ID'=>0), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'NAME'));
while($arB=$dbb->GetNext()):
	$arBrandsFromSite[$arB['XML_ID']]=array('ID'=>$arB['ID'], 'NAME'=>$arB['NAME']);
endwhile;

//pr($arBrandsFromSite);

$arNewBrands=array_diff_key($arBrands, $arBrandsFromSite);
$arOldBrands=array_diff_key($arBrandsFromSite, $arBrands);
echo sizeof($arNewBrands);

pr($arNewBrands);
echo 'from site ='.sizeof($arOldBrands);
pr($arOldBrands);
echo '<hr>';

?>