<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

$start_time = microtime(true);

$dbs=CIBlockSection::GetList(array('DEPTH_LEVEL'=>'ASC', 'SORT'=>'ASC'), array('IBLOCK_ID'=>26), false, array('ID', 'XML_ID', 'IBLOCK_SECTION_ID', 'NAME', 'ACTIVE', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DESCRIPTION', 'SORT'));
while($arS=$dbs->GetNext()):
	$arSectId2Xml[$arS['ID']]=$arS['XML_ID']; // соответствие id и xml_id
	$arSectXml2Id[$arS['XML_ID']]=$arS['ID']; // соответствие xml_id и id $arS['ID'] array($arS['ID'], $arS['NAME'], $arS['IBLOCK_SECTION_ID'])
endwhile;

$obTest=wsdl('GetAllProductsCategories');
//pr($obTest);
//exit;
$arProds2Cats=array();

foreach($obTest->GetAllProductsCategoriesResult->ExportedData->ProductLinkDTO as $key=>$obP2C):
	$arProds2Cats[trim($obP2C->ProductCodeOneC)][]=$obP2C->CategoryId;
	$arProds2CatsIds[trim($obP2C->ProductCodeOneC)][]=$arSectXml2Id[$obP2C->CategoryId];
endforeach;
unset($obTest);

echo 'time parse soap='.workTime2($start_time).'<br>';
echo 'num:'.sizeof($arProds2Cats);
//pr($arProds2CatsIds);

//exit;

$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26, '!PROPERTY_CODE1C'=>false), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'PROPERTY_CODE1C', 'IBLOCK_SECTION_ID', 'ACTIVE'));
$i=0;
	$arS2P=array();
while($arP=$dbb->GetNext()):
	/*$dbs=CIBlockElement::GetElementGroups($arP['ID'], false, array('ID', 'IBLOCK_ID', 'XML_ID'));
	while($arSect=$dbs->GetNext()):
		$arS2P[$arP['PROPERTY_CODE1C_VALUE']][]=$arSect['XML_ID'];
		$arIdS2P[$arP['PROPERTY_CODE1C_VALUE']][]=$arSect['ID'];
	endwhile;*/
	if(is_array($arProds2CatsIds[$arP['PROPERTY_CODE1C_VALUE']])):
		CIBlockElement::SetElementSection($arP['ID'], $arProds2CatsIds[$arP['PROPERTY_CODE1C_VALUE']]);
		//pr($arP);
		//pr($arProds2CatsIds[$arP['PROPERTY_CODE1C_VALUE']]);
		//break;
	else:
		$j++;
	endif;
	//pr($arP);
	$i++;
	//if($i>1000) break;
endwhile;
echo '<br>from site: '.$i.' '.$j.'<br>';
echo 'time set sections ='.workTime2($start_time).'<br>';

exit;
pr($arS2P);
pr($arIdS2P);
echo 'qq';
$dbs=CIBlockElement::GetElementGroups(85224, false, array('ID', 'IBLOCK_ID', 'XML_ID'));
while($arSect=$dbs->GetNext()):
	pr($arSect);
endwhile;
//4462 4470
//CIBlockElement::SetElementSection(85224, array(4462, 4470));

foreach($arS2P as $code1c=>$arSectOfProduct):
	$arDif=0;
endforeach;

?>
