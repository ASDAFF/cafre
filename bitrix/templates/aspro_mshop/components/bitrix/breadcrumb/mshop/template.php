<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
if($arResult){
    CModule::IncludeModule("iblock");
    global $MShopSectionID;
    $cnt = count($arResult);
    $lastindex = $cnt - 1;
    //$bShowCatalogSubsections = COption::GetOptionString("aspro.mshop", "SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS", "Y", SITE_ID) == "Y";
    
    for($index = 0; $index < $cnt; ++$index){
        $arSubSections = array();
        $arItem = $arResult[$index];
        $title = htmlspecialcharsex($arItem["TITLE"]);
        $bLast = $index == $lastindex;
        if($MShopSectionID && $bShowCatalogSubsections){
            $arSubSections = CMShop::getChainNeighbors($MShopSectionID, $arItem['LINK']);
        }
        if($index){
            $strReturn .= '<span class="separator">-</span>';
        }
  
        //if($arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath() || $arSubSections && $index<(count($arResult)-1)){
          if($arItem["LINK"] <> "" && $index<(count($arResult)-1)){
            if($arSubSections){
                $strReturn .= '<span class="drop">';
                    $strReturn .= '<a class="number" href="'.$arItem["LINK"].'">'.($arSubSections ? '<span>'.$title.'</span><b class="space"></b><span class="separator'.($bLast ? ' cat_last' : '').'"></span>' : '<span itemprop="item">'.$title.'</span>').'</a>';
                    $strReturn .= '<div class="dropdown_wrapp"><div class="dropdown">';
                        foreach($arSubSections as $arSubSection){
                            $strReturn .= '<a href="'.$arSubSection["LINK"].'">'.$arSubSection["NAME"].'</a>';
                        }
                    $strReturn .= '</div></div>';
                $strReturn .= '</span>';
            }
            else{
                $strReturn .= '<a href="'.$arItem["LINK"].'" title="'.$title.'"><span>'.$title.'</span></a><div itemprop="itemListElement" itemscope
itemtype="http://schema.org/ListItem" style="display:none;"><a href="'.$arItem["LINK"].'" title="'.$title.'" itemprop="item"><span itemprop="name">'.$title.'</span></a></div>';
            }
        }
        else{
            $strReturn .= '<span>'.$title.'</span>
			<div itemprop="itemListElement" itemscope
itemtype="http://schema.org/ListItem" style="display:none;"><a href="'.$arItem["LINK"].'" title="'.$title.'" itemprop="item"><span itemprop="name">'.$title.'</span></a></div>
			';
        }
    }
    
    return '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'.$strReturn.'</div>';
}
else{
    return $strReturn;
}
?>