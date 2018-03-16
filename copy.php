<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$razbiv=500;

if(($handle = fopen('0.csv', "r")) !== FALSE) {
	if(isset($_GET['ftell']) && $_GET['ftell']>0){
		fseek($handle,$_GET['ftell']);
	}  
	$row=1;	
	while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
		$row++;	
		$arFilter = Array("IBLOCK_ID"=>26, "ID"=>$data[0]);		
		$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, array('ID','IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID' ));
		
		while($ar = $res->GetNextElement())
		{
			$arProp=$ar->GetProperties();
			$ar_fields=$ar->GetFields();
			echo $data[0].': '.$arProp["CATALOG_BREND"]['VALUE'].' '.$ar_fields["NAME"].' '.$ar_fields["IBLOCK_SECTION_ID"]."<br>";
			$el = new CIBlockElement;

$arLoadProductArray = Array(
  "IBLOCK_SECTION" => $data[1],
  );

$PRODUCT_ID = $data[0]; 
//$el->Update($PRODUCT_ID, $arLoadProductArray);
		}
		if(feof($handle)) {
			break;
		}
		elseif($razbiv==$row  ) {
			echo '<br>end '.ftell($handle);
			break;
			
			$ftell=ftell($handle);
			sleep(5);
			fclose($handle);
			header('HTTP/1.1 200 OK');
			
			header('Location: https://'.$_SERVER['SERVER_NAME'].'/ira.php?ftell='.$ftell);
			exit;
		}   
	} 
	fclose($handle);
}
?>