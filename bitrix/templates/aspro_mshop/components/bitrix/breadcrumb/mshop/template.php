<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<?
if($arResult){
		session_start();

/*		$cook = iconv('UTF-8', 'WINDOWS-1251', $_COOKIE["Catalog_new"]);
	$cook_elem = iconv('UTF-8', 'WINDOWS-1251', $_COOKIE["detail_elem"]);
	$exp1 = explode("-", $cook);

	foreach($exp1 as $val){
			$exp2 = explode("#", $val);
			$new_ar[]=array("TITLE"=>$exp2[0], "LINK"=>$exp2[1]);		
	}
	//print_r($new_ar);
	//print_r($cook_elem);
	if($cook_elem){
	$cook_elem_new = array("TITLE"=>$cook_elem, "LINK" => "");
		array_push($new_ar, $cook_elem_new);
		}*/
    CModule::IncludeModule("iblock");
    global $MShopSectionID;
	   $ex = explode('/',$_SERVER['REQUEST_URI']);
	   $array_b = array_values($ex);
		$last = count($array_b)-2;
if(strripos($_SERVER['REQUEST_URI'], "vse_brendy")){
$arFilter = array('IBLOCK_ID' => 26, '=CODE' => $array_b[$last]);
$rsSections = CIBlockSection::GetList(array(), $arFilter);
if($arSection = $rsSections->Fetch())
{
	if($arSection["CODE"] != "vse_brendy"){
	$ar_brands = array("TITLE"=>$arSection['NAME'], "LINK"=>"/catalog/vse_brendy/".$arSection["CODE"]."/");
	}else{
		$ar_brands = array("TITLE"=>$arSection['NAME'], "LINK"=>"/catalog/".$arSection["CODE"]."/");
	}
}
	   }
	 
$arFilter2 = array('IBLOCK_ID' => 26, '=CODE' => $array_b[$last]);
$rsSections2 = CIBlockSection::GetList(array(), $arFilter2);
if($arSection2 = $rsSections2->Fetch())
{
	$filt_br = array("TITLE"=>$arSection2['NAME'], "LINK"=>"");
}
	$arElm = CIBlockElement::GetList(array(), array('CODE' => $array_b[$last], 'IBLOCK_ID' => 26), false, false, array('ID', 'NAME'))->Fetch();
if ($arElm){
    $id_elem = (int) $arElm['ID'];
	$name_elem = array("TITLE"=>$arElm["NAME"], "LINK"=>"");
	}
if($id_elem){
	if(!array_search($name_elem, $_SESSION["CATALOG"])){
	array_push($_SESSION["CATALOG"], $name_elem);
	}
}elseif(strripos($_SERVER['REQUEST_URI'], "catalog")){
	$_SESSION["CATALOG"] = $arResult;
	foreach($_SESSION["CATALOG"] as $e){
		$new_mas_title[] = $e["TITLE"];
	}

if(strripos($_SERVER['REQUEST_URI'], "vse_brendy") && !array_search($ar_brands, $_SESSION["CATALOG"])){
	$alll_brands = array("TITLE"=>"Все бренды", "LINK"=>"/catalog/vse_brendy/");
	array_push($_SESSION["CATALOG"], $alll_brands);
	array_push($_SESSION["CATALOG"], $ar_brands);
	//print_r($_SESSION["CATALOG"]);
}elseif(!array_search($filt_br["TITLE"], $new_mas_title)){
	array_push($_SESSION["CATALOG"], $filt_br);
}
}

/*if($cook && $cook_elem){
    $cnt = count($new_ar);
	}else{}*/
//$_REQUEST[""]
	//
if($_SESSION["CATALOG"]){
		 $cnt = count($_SESSION["CATALOG"]);
}else{
	 $cnt = count($arResult);
}
	
    $lastindex = $cnt - 1;
    //$bShowCatalogSubsections = COption::GetOptionString("aspro.mshop", "SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS", "Y", SITE_ID) == "Y";
$cont = 0;
	
    for($index = 0; $index < $cnt; ++$index){
		$cont++;
        $arSubSections = array();
		/*if($cook && $cook_elem){
        $arItem = $new_ar[$index];
		}else{}*/
		if($_SESSION["CATALOG"]){
		$arItem = $_SESSION["CATALOG"][$index];
		}else{
			$arItem = $arResult[$index];
		}
		
		$title = htmlspecialcharsex($arItem["TITLE"]);
        $bLast = $index == $lastindex;
        if($MShopSectionID && $bShowCatalogSubsections){

				 $arSubSections = CMShop::getChainNeighbors($MShopSectionID, $arItem['LINK']);
			
        }
        if($index){
           // $strReturn .= '<span class="separator">-</span>';
        }
  
        //if($arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath() || $arSubSections && $index<(count($arResult)-1)){
			if($_SESSION["CATALOG"]){
				$arResult_it = $_SESSION["CATALOG"];
			}else{
				$arResult_it = $arResult;
			}
          if($arItem["LINK"] <> "" && $index<(count($arResult_it)-1)){
            if($arSubSections){
                $strReturn .= '<span class="drop">';
                    $strReturn .= '<a class="number" href="'.$arItem["LINK"].'">'.($arSubSections ? '<span data-url="'.$arItem["LINK"].'">'.$title.'</span><b class="space"></b><span class="separator'.($bLast ? ' cat_last' : '').'"></span>' : '<span data-url="'.$arItem["LINK"].'">'.$title.'</span>').'</a>';
                    $strReturn .= '<div class="dropdown_wrapp"><div class="dropdown">';
                        foreach($arSubSections as $arSubSection){
                            $strReturn .= '<a href="'.$arSubSection["LINK"].'">'.$arSubSection["NAME"].'</a>';
                        }
                    $strReturn .= '</div></div>';
                $strReturn .= '</span>';
            }
            else{
                $strReturn .= '<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a href="'.$arItem["LINK"].'" title="'.$title.'"><span data-url="'.$arItem["LINK"].'" itemprop="name">'.$title.'</span><meta itemprop="position" content="'.$cont.'" /></a></li>';
            }
        }
        else{

            $strReturn .= '<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a href="'.$arItem["LINK"].'" title="'.$title.'"><span class="last-elem detail" data-url="'.$arItem["LINK"].'" itemprop="name">'.$title.'</span><meta itemprop="position" content="'.$cont.'" /></a></li>
			';
			
        }
    }
    
    return '<div itemprop="breadcrumb" class="breadcrumb"><ul itemscope="" itemtype="http://schema.org/BreadcrumbList" class="bred_list">'.$strReturn.'</ul></div>';
}
else{
    return $strReturn;
}
?>