<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");




function getAllCategories() {
	file_put_contents('log.txt', date('d.m.Y H:i:s').' GetAllCategories'."\n", FILE_APPEND);
	$start_time = microtime(true);
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");
	$obTest=wsdl('GetAllCategories');
	foreach($obTest->GetAllCategoriesResult->ExportedData->CategoryDTO as $key=>$obCat):
		$cat_id=$obCat->CategoryId;
		$arCatsParents[$obCat->ParentCategory][]=$cat_id;
		$arCat=array('NAME'=>iconv("utf-8", "windows-1251", trim($obCat->Name)), 'ENABLED'=>$obCat->Enabled, 'PARENT_ID'=>$obCat->ParentCategory, 'SORT'=>intval($obCat->SortOrder), 'CODE'=>trim($obCat->UrlPath));
		$arCats[$obCat->CategoryId]= $arCat;
		if($obCat->Enabled==1)
			$en1++;
		else
			$en0++;
	endforeach;
	unset($obTest);
	echo 'time get soap='.workTime2($start_time).'<br>';
	// по уровням
	$arCats2Levels=array();
	for($level=1;$level<10;$level++):
		if($level==1):
			foreach($arCatsParents[0] as $cat_id):
				$arLevels[$level][$cat_id]=0;
				$arCats[$cat_id]['LEVEL']=$level;
			endforeach;
			unset($arCatsParents[0]);
		else:
			if($level>20) break;
			foreach($arLevels[$level-1] as $parent_id=>$id):
				foreach ($arCatsParents[$parent_id] as $cat_id):
					$arLevels[$level][$cat_id]=$parent_id;
					$arCats[$cat_id]['LEVEL']=$level;
				endforeach;
				unset($arCatsParents[$parent_id]);
			endforeach;
			if(sizeof($arCatsParents)<1) break;
		endif;
	endfor;
	//с сайта
	$dbs=CIBlockSection::GetList(array('DEPTH_LEVEL'=>'ASC', 'SORT'=>'ASC'), array('IBLOCK_ID'=>26), false, array('ID', 'XML_ID', 'IBLOCK_SECTION_ID', 'NAME', 'ACTIVE', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DESCRIPTION', 'SORT'));
	while($arS=$dbs->GetNext()):
		$arId2Xml[$arS['ID']]=$arS['XML_ID']; // соответствие id и xml_id
		$arSectionsFromSite[$arS['XML_ID']]=$arS;
		$arLevelsFromSite[$arS['DEPTH_LEVEL']][$arS['XML_ID']]=array('parent_id'=>$arS['IBLOCK_SECTION_ID'], 'parent_xml'=>$arId2Xml[$arS['IBLOCK_SECTION_ID']]);
		if($arS['ACTIVE']=='Y')
			$arSectionsFromSiteAct[$arS['XML_ID']]=$arS;
		else
			$arSectionsFromSiteNoAct[$arS['XML_ID']]=$arS;
	endwhile;
	//деактивация разделов, которых нет в выгрузке
	$arOldSects=array_diff_key($arSectionsFromSiteAct, $arCats);
	$bs = new CIBlockSection;
	$arFieldsNoAct=array('ACTIVE'=>'N');
	$ideact=0;
	foreach ($arOldSects as $xml_id=>$arOldSect):
		if($bs->Update($arOldSect['ID'], $arFieldsNoAct)):
			$ideact++;
		else:
			echo $arOldSect['ID'].' '.$bs->LAST_ERROR.'<br>';
		endif;
	endforeach;/**/
	//eof деактивация разделов
	//добавление новых по уровням
	$arNewAddedSections=array();
	foreach ($arLevels as $level=>$arLevel):
		$arLevelNewSects=array_diff_key($arLevel, $arSectionsFromSite);
		if (is_array($arLevelNewSects)&&sizeof($arLevelNewSects)):
			foreach($arLevelNewSects as $newcode=>$parentcode):
				$arFields2Add=array('IBLOCK_ID'=>26, 'NAME'=>$arCats[$newcode]['NAME'], 'SORT'=>$arCats[$newcode]['SORT'], 'CODE'=>$arCats[$newcode]['CODE'], 'ACTIVE'=>'Y', 'XML_ID'=>$newcode);
				if(isset($arSectionsFromSite[$parentcode])):
					echo $newcode.' => '.$arSectionsFromSite[$parentcode]['NAME'].'<br>';
					$arFields2Add['IBLOCK_SECTION_ID']=$arSectionsFromSite[$parentcode]['ID'];
					$ID = $bs->Add($arFields2Add);
					if($ID>0):
						$arNewAddedSections[$newcode]=$ID;
					else:
						echo $bs->LAST_ERROR.'<br>';
					endif;
				elseif(isset($arNewAddedSections[$parentcode])&&$arNewAddedSections[$parentcode]>0):
					echo $newcode.' => '.$arSectionsFromSite[$parentcode]['NAME'].'<br>';
					$arFields2Add['IBLOCK_SECTION_ID']=$arNewAddedSections[$parentcode];
					$ID = $bs->Add($arFields2Add);
					if($ID>0):
						$arNewAddedSections[$newcode]=$ID;
					else:
						echo $bs->LAST_ERROR.'<br>';
					endif;
				else:
					echo 'NOT ADDED '.$newcode.' '.$parentcode.'<br>';
				endif;
			endforeach;
		endif;
	endforeach;
	//eof добавление новых по уровням
	//проверка старых по уровням
	$iold=0;
	$arOldSects=array_intersect_key($arSectionsFromSite, $arCats);
	echo 'oldsects='.sizeof($arOldSects).'<br>';
	foreach($arOldSects as $xml_id=>$arSect):
		$arFields2Upd=array();
		if($arSect['NAME']!=$arCats[$xml_id]['NAME']):
			$arFields2Upd['NAME']=$arCats[$xml_id]['NAME'];
		endif;
		if($arSect['SORT']!=$arCats[$xml_id]['SORT']):
			if($arCats[$xml_id]['SORT']>0):
				echo $xml_id.' => '.$arSect['SORT'].' | '.$arCats[$xml_id]['SORT'].' - sort<br>';
				$arFields2Upd['SORT']=$arCats[$xml_id]['SORT'];
			endif;
		endif;
		if($arId2Xml[$arSect['IBLOCK_SECTION_ID']]!=$arCats[$xml_id]['PARENT_ID']):
			$arFields2Upd['IBLOCK_SECTION_ID']=$arSectionsFromSite[$arCats[$xml_id]['PARENT_ID']]['ID'];
		endif;
		if(sizeof($arFields2Upd)>0):
			if($bs->Update($arSect['ID'], $arFields2Upd, false)):
				$iold++;
				echo 'upd: '.$arSect['ID'].' '.$xml_id.'<br>';
			else:
				echo $arSect['ID'].' '.$bs->LAST_ERROR.'<br>';
			endif;/**/
		endif;
	endforeach;
	//eof проверка старых по уровням
	CIBlockSection::ReSort(26);
	file_put_contents('log.txt', round(workTime2($start_time), 3)."\n-------------------------\n", FILE_APPEND);
	return 'getAllCategories();';
}

$q=getAllCategories();

//pr($obTest);
//echo sizeof($obTest->GetAllCategoriesResult->ExportedData->CategoryDTO).'<br>';

/*
echo 'time get soap='.workTime2($start_time).'<br>';
echo "en0=$en0, en1=$en1<br>";
//pr($arCats['8102']).'<br><br>';exit;
echo '<hr>';
echo '<br><br>';
//pr($arLevels);

//unset()
//pr($arLevelsFromSite);
//pr($arSectionsFromSite);
//exit;

echo sizeof($arSectionsFromSite).' '.sizeof($arOldSects);
echo '<br>';
//pr($arOldSects);
echo 'деактивировано '.$ideact.'<br><br>';

//exit;


*/

?>