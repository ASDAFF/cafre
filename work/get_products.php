<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");



$start_time = microtime(true);
//echo $start_time.'<br>';
/*$el = new CIBlockElement;
$arFields=array('ACTIVE'=>'N');
$arIdeact=array( 89271, 89272, 89273, 89274); //, 89271, 89272, 89273
foreach($arIdeact as $id):
	$el->Update($id, $arFields);
	echo $id.' ';
endforeach;
echo '<br>'.workTime2($start_time).'<br>';
exit;*/


$obTest=wsdl('GetAllProducts');
echo sizeof($obTest->GetAllProductsResult->ExportedData->ProductDTO);
echo '<br>'.workTime2($start_time).'<br>';

//pr($obTest);
//exit;

$arProducts=array();
$en0=0;
$en1=0;
foreach($obTest->GetAllProductsResult->ExportedData->ProductDTO as $key=>$obProd):
	//echo $key.'!';
	$brand_id=$obProd->Brand->BrandId;
	$arProducts[$obProd->ID]=array('NAME'=>iconv("utf-8", "windows-1251", $obProd->Name), 'PREVIEW_TEXT'=>iconv("utf-8", "windows-1251", $obProd->BriefDescription), 'DETAIL_TEXT'=>iconv("utf-8", "windows-1251", $obProd->Description), 'ENABLED'=>$obProd->Enabled, 'BRAND_ID'=>$brand_id, 'CODE'=>$obProd->UrlPath, 'RECOMMENDED'=>$obProd->Recomended, 'ISWEEKGOODS'=>$obProd->IsWeekGoods, 'ONSALE'=>$obProd->OnSale); 
	if($obProd->Enabled==1)
		$en1++;
	else
		$en0++;
	//pr($arProducts[$obProd->ID]);
	//if($key>2000) break;
endforeach;
echo "en0=$en0, en1=$en1<br>";
echo '<br>timesoap='.workTime2($start_time).'<br>';
//exit;

$dbb=CIBlockElement::GetList(array('XML_ID'=>'ASC'), array('IBLOCK_ID'=>26), false, false, array('ID', 'IBLOCK_ID', 'XML_ID', 'NAME', 'ACTIVE'));
while($arP=$dbb->GetNext()):
	$arProductsFromSite[$arP['XML_ID']]=array('ID'=>$arP['ID'], 'NAME'=>$arP['NAME'], 'ACTIVE'=>$arP['ACTIVE']);
endwhile;
echo '<br>timeFromSite='.workTime2($start_time).'<br>';
//exit;


$arNewProducts=array_diff_key($arProducts, $arProductsFromSite);
$arOldProducts=array_diff_key($arProductsFromSite, $arProducts);
echo 'from 1c ='.sizeof($arNewProducts).'<br>';
//pr($arNewProducts);
//pr($arNewProducts['81420982']);
echo 'from site ='.sizeof($arOldProducts);
//pr($arOldProducts);
echo '<hr>';
$ideact=0;
$el = new CIBlockElement;
//деактивация отсутствующих
	$arFields=array('ACTIVE'=>'N');
foreach($arOldProducts as $xml_id=>$arOldProd):
	if($arOldProd['ACTIVE']=='Y'):
		//$el->Update(intval($arOldProd['ID']), $arFields);
		echo intval($arOldProd['ID']).' '.$xml_id;
		pr($arOldProd);
		$ideact++;
		//exit;
		/*if($el->Update($arOldProd['ID'], $arFields)):
			break;
		else:
			echo 'Error: '.$el->LAST_ERROR;
		endif;*/
	endif;
	echo $ideact.'=ideact<br>';
	if($ideact>5) break;
endforeach;
//eof деактивация отсутствующих
echo $ideact.' деактивировано, время='.workTime2($start_time);

?>