<?
	$arMenu = array();
	$inx = 0;
	$count = count($arResult);
	for( $i = 0; $i < $count; $i++ ){
		if( $arResult[$i]["DEPTH_LEVEL"] == 1 ):
			$arMenu[$inx] = $arResult[$i];
			if( $arResult[$i]["IS_PARENT"] == 1 ):
				while(1){
					$i++;
					$arMenu[$inx]["ITEMS"][] = $arResult[$i];
					if( $i+1 >= $count || $arResult[$i+1]["DEPTH_LEVEL"] == 1 ):
						break;
					endif;
				}
			endif;
			$inx++;
		endif;
	}
	$arResult = $arMenu;
?>