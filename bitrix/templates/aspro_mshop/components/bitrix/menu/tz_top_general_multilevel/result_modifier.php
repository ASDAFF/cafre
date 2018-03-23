<?//$arResult = CMShop::getChilds($arResult);?>
<?
   /*foreach($arResult as $index => $arItem){
        if("Бренды" == $arItem["TEXT"]){
            foreach($arItem["CHILD"] as $key => $arChild){
                unset($arResult[$index]["CHILD"][$key]["CHILD"]);
            }
        }
    }*/

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
		
$arFilter = Array('IBLOCK_ID'=>26, "DEPTH_LEVEL"=>3, 'NAME'=>$va_z["TEXT"]);
 $db_list = CIBlockSection::GetList(Array(), $arFilter, false, array("UF_BRAND_ID"));
  if($ar_result = $db_list->GetNext())
  {
	   if(strripos($ar_result["SECTION_PAGE_URL"], "vse_brendy"))continue;
	$arResult[$ke]["SVIZ_BR"] = $ar_result["~UF_BRAND_ID"]; 
	
  }
			
	}
	//, "SECTION_PAGE_URL"=>$va_z["LINK"]
	
	
	

?>
