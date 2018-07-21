<?
    $arResult["TZ_SEC_COUNT"] = $arResult["TZ_SUBSEC_COUNT"] = array();
    foreach($arResult as $key => $arItem){
        $arResult["TZ_SEC_COUNT"][$arItem["TEXT"]]++;
        if($arItem["IS_PARENT"]){
            foreach($arItem["CHILD"] as $arChild){
                $arResult["TZ_SEC_COUNT"][$arItem["TEXT"]]++;
                $arResult["TZ_SUBSEC_COUNT"][$arItem["TEXT"]][$arChild["TEXT"]]++; 
                if($arChild["IS_PARENT"]){
                    foreach($arChild["CHILD"] as $arChilds){
                        $arResult["TZ_SEC_COUNT"][$arItem["TEXT"]]++; 
                        $arResult["TZ_SUBSEC_COUNT"][$arItem["TEXT"]][$arChild["TEXT"]]++;
                    }
                }
            }
        }
    }

    $arResult["GROUP"] = array();
    foreach($arResult["TZ_SUBSEC_COUNT"] as $key=>$arTzSec){
        $arNum = (int)($arResult["TZ_SEC_COUNT"][$key]/3);
        
        if($arNum > 3){
            $arQuant = 0;
            $group = 0;
            foreach($arTzSec as $index=>$arVals){
                if($arNum > $arQuant){                  
                    $arResult["GROUP"][$key][$group][] = $index;
                    $arQuant = $arQuant + $arVals;
                }
                else{
                    $group++;
                    $arResult["GROUP"][$key][$group][] = $index;
                    $arQuant =  $arVals;
                }
            }
        }
    }
    foreach($arResult["GROUP"] as $key=>$arGroup){
        foreach($arGroup as $index=>$arGroups){
            $arLastInGroup[$key][] = end($arGroups);
        }
    }
	
    $arResult["TZ_LAST_IN_GROUP"] = $arLastInGroup;

	foreach($arResult as $ke => $va_z){
				$masurl = explode("/",$va_z["LINK"]);
		unset($masurl[0]);unset($masurl[1]);unset($masurl[2]);
					$fruit2 = array_pop($masurl);
$arFilter = Array('IBLOCK_ID'=>26, "DEPTH_LEVEL"=>2, "CODE"=>$masurl[3]);
 $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array("UF_BRAND_ID"));
  if($ar_result = $db_list->GetNext())
  {
	  $arFilter2 = Array('IBLOCK_ID'=>26, "DEPTH_LEVEL"=>3, "SECTION_ID"=>$ar_result["ID"], "CODE"=>$masurl[4]);
	  $db_list2 = CIBlockSection::GetList(Array(), $arFilter2, false, array("UF_BRAND_ID"));
	  if($ar_result2 = $db_list2->GetNext())
	  {
		   if(strripos($ar_result2["SECTION_PAGE_URL"], "vse_brendy"))continue;
		$arResult[$ke]["SVIZ_BR"] = $ar_result2["~UF_BRAND_ID"]; 
	  }	
  }
			
	}
	
	
	

?>
