﻿<? 
ini_set('max_execution_time', '0');
@ignore_user_abort(true);
//<title>Google_me</title>
/** @global CUser $USER */
/** @global CMain $APPLICATION */
use Bitrix\Currency,
	Bitrix\Iblock;

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/catalog/export_yandex.php');
set_time_limit(0);

global $USER, $APPLICATION;
$bTmpUserCreated = false;
if (!CCatalog::IsUserExists())
{
	$bTmpUserCreated = true;
	if (isset($USER))
	{
		$USER_TMP = $USER;
		unset($USER);
	}

	$USER = new CUser();
}

CCatalogDiscountSave::Disable();
CCatalogDiscountCoupon::ClearCoupon();
if ($USER->IsAuthorized())
	CCatalogDiscountCoupon::ClearCouponsByManage($USER->GetID());

$arYandexFields = array('vendor', 'vendorCode', 'model', 'author', 'name', 'publisher', 'series', 'year', 'ISBN', 'volume', 'part', 'language', 'binding', 'page_extent', 'table_of_contents', 'performed_by', 'performance_type', 'storage', 'format', 'recording_length', 'artist', 'title', 'year', 'media', 'starring', 'director', 'originalName', 'country', 'aliases', 'description', 'sales_notes', 'promo', 'provider', 'tarifplan', 'xCategory', 'additional', 'worldRegion', 'region', 'days', 'dataTour', 'hotel_stars', 'room', 'meal', 'included', 'transport', 'price_min', 'price_max', 'options', 'manufacturer_warranty', 'country_of_origin', 'downloadable', 'param', 'place', 'hall', 'hall_part', 'is_premiere', 'is_kids', 'date',);

if (!function_exists("yandex_replace_special"))
{
	function yandex_replace_special($arg)
	{
		if (in_array($arg[0], array("&quot;", "&amp;", "&lt;", "&gt;")))
			return $arg[0];
		else
			return " ";
	}
}

if (!function_exists("yandex_text2xml"))
{
	function yandex_text2xml($text, $bHSC = false, $bDblQuote = false)
	{
		global $APPLICATION;

		$bHSC = (true == $bHSC ? true : false);
		$bDblQuote = (true == $bDblQuote ? true: false);

		if ($bHSC)
		{
			$text = htmlspecialcharsbx($text);
			if ($bDblQuote)
				$text = str_replace('&quot;', '"', $text);
		}
		$text = preg_replace("/[\x1-\x8\xB-\xC\xE-\x1F]/", "", $text);
		$text = str_replace("'", "&apos;", $text);
		$text = $APPLICATION->ConvertCharset($text, LANG_CHARSET, 'windows-1251');
		return $text;
	}
}

if (!function_exists('yandex_get_value'))
{
function yandex_get_value($arOffer, $param, $PROPERTY, &$arProperties, &$arUserTypeFormat, $usedProtocol)
{
	global $iblockServerName;

	$strProperty = '';
	$bParam = (strncmp($param, 'PARAM_', 6) == 0);
	if (isset($arProperties[$PROPERTY]) && !empty($arProperties[$PROPERTY]))
	{
		$PROPERTY_CODE = $arProperties[$PROPERTY]['CODE'];
		$arProperty = (
			isset($arOffer['PROPERTIES'][$PROPERTY_CODE])
			? $arOffer['PROPERTIES'][$PROPERTY_CODE]
			: $arOffer['PROPERTIES'][$PROPERTY]
		);

		$value = '';
		$description = '';
		switch ($arProperties[$PROPERTY]['PROPERTY_TYPE'])
		{
			case 'USER_TYPE':
				if ($arProperty['MULTIPLE'] == 'Y')
				{
					if (!empty($arProperty['~VALUE']))
					{
						$arValues = array();
						foreach($arProperty["~VALUE"] as $oneValue)
						{
							$isArray = is_array($oneValue);
							if (
								($isArray && !empty($oneValue))
								|| (!$isArray && $oneValue != '')
							)
							{
								$arValues[] = call_user_func_array($arUserTypeFormat[$PROPERTY],
									array(
										$arProperty,
										array("VALUE" => $oneValue),
										array('MODE' => 'SIMPLE_TEXT'),
									)
								);
							}
						}
						$value = implode(', ', $arValues);
					}
				}
				else
				{
					$isArray = is_array($arProperty['~VALUE']);
					if (
						($isArray && !empty($arProperty['~VALUE']))
						|| (!$isArray && $arProperty['~VALUE'] != '')
					)
					{
						$value = call_user_func_array($arUserTypeFormat[$PROPERTY],
							array(
								$arProperty,
								array("VALUE" => $arProperty["~VALUE"]),
								array('MODE' => 'SIMPLE_TEXT'),
							)
						);
					}
				}
				break;
			case 'E':
				if (!empty($arProperty['VALUE']))
				{
					$arCheckValue = array();
					if (!is_array($arProperty['VALUE']))
					{
						$arProperty['VALUE'] = (int)$arProperty['VALUE'];
						if (0 < $arProperty['VALUE'])
							$arCheckValue[] = $arProperty['VALUE'];
					}
					else
					{
						foreach ($arProperty['VALUE'] as &$intValue)
						{
							$intValue = (int)$intValue;
							if (0 < $intValue)
								$arCheckValue[] = $intValue;
						}
						if (isset($intValue))
							unset($intValue);
					}
					if (!empty($arCheckValue))
					{
						$dbRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arProperties[$PROPERTY]['LINK_IBLOCK_ID'], 'ID' => $arCheckValue), false, false, array('NAME'));
						while ($arRes = $dbRes->Fetch())
						{
							$value .= ($value ? ', ' : '').$arRes['NAME'];
						}
					}
				}
				break;
			case 'G':
				if (!empty($arProperty['VALUE']))
				{
					$arCheckValue = array();
					if (!is_array($arProperty['VALUE']))
					{
						$arProperty['VALUE'] = (int)$arProperty['VALUE'];
						if (0 < $arProperty['VALUE'])
							$arCheckValue[] = $arProperty['VALUE'];
					}
					else
					{
						foreach ($arProperty['VALUE'] as &$intValue)
						{
							$intValue = (int)$intValue;
							if (0 < $intValue)
								$arCheckValue[] = $intValue;
						}
						if (isset($intValue))
							unset($intValue);
					}
					if (!empty($arCheckValue))
					{
						$dbRes = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $arProperty['LINK_IBLOCK_ID'], 'ID' => $arCheckValue), false, array('NAME'));
						while ($arRes = $dbRes->Fetch())
						{
							$value .= ($value ? ', ' : '').$arRes['NAME'];
						}
					}
				}
				break;
			case 'L':
				if (!empty($arProperty['VALUE']))
				{
					if (is_array($arProperty['VALUE']))
						$value .= implode(', ', $arProperty['VALUE']);
					else
						$value .= $arProperty['VALUE'];
				}
				break;
			case 'F':
				if (!empty($arProperty['VALUE']))
				{
					if (is_array($arProperty['VALUE']))
					{
						foreach ($arProperty['VALUE'] as &$intValue)
						{
							$intValue = (int)$intValue;
							if ($intValue > 0)
							{
								if ($ar_file = CFile::GetFileArray($intValue))
								{
									if(substr($ar_file["SRC"], 0, 1) == "/")
										$strFile = $usedProtocol.$iblockServerName.CHTTP::urnEncode($ar_file['SRC'], 'utf-8');
									else
										$strFile = $ar_file["SRC"];
									$value .= ($value ? ', ' : '').$strFile;
								}
							}
						}
						if (isset($intValue))
							unset($intValue);
					}
					else
					{
						$arProperty['VALUE'] = (int)$arProperty['VALUE'];
						if ($arProperty['VALUE'] > 0)
						{
							if ($ar_file = CFile::GetFileArray($arProperty['VALUE']))
							{
								if(substr($ar_file["SRC"], 0, 1) == "/")
									$strFile = $usedProtocol.$iblockServerName.CHTTP::urnEncode($ar_file['SRC'], 'utf-8');
								else
									$strFile = $ar_file["SRC"];
								$value = $strFile;
							}
						}
					}
				}
				break;
			default:
				if ($bParam && $arProperty['WITH_DESCRIPTION'] == 'Y')
				{
					$description = $arProperty['DESCRIPTION'];
					$value = $arProperty['VALUE'];
				}
				else
				{
					$value = is_array($arProperty['VALUE']) ? implode(', ', $arProperty['VALUE']) : $arProperty['VALUE'];
				}
		}

		// !!!! check multiple properties and properties like CML2_ATTRIBUTES

		if ($bParam)
		{
			if (is_array($description))
			{
				foreach ($value as $key => $val)
				{
					$strProperty .= $strProperty ? "\n" : "";
					$namep = 'Фотогалерея';
					$namep2 = 'Название первой фотографии';
					if(yandex_text2xml($arProperties[$PROPERTY]['NAME'], true) != iconv("UTF-8", "WINDOWS-1251", $namep) && yandex_text2xml($arProperties[$PROPERTY]['NAME'], true) != iconv("UTF-8", "WINDOWS-1251", $namep2))
					$strProperty .= '<param name="'.iconv("WINDOWS-1251", "UTF-8", yandex_text2xml($description[$key], true)).'">'.iconv("WINDOWS-1251", "UTF-8", yandex_text2xml($val, true)).'</param>';
				}
			}
			else
			{
				$namep = 'Фотогалерея';
				$namep2 = 'Название первой фотографии';
				if(yandex_text2xml($arProperties[$PROPERTY]['NAME'], true) != iconv("UTF-8", "WINDOWS-1251", $namep) && yandex_text2xml($arProperties[$PROPERTY]['NAME'], true) != iconv("UTF-8", "WINDOWS-1251", $namep2))
				$strProperty .= '<param name="'.iconv("WINDOWS-1251", "UTF-8", yandex_text2xml($arProperties[$PROPERTY]['NAME'], true)).'">'.iconv("WINDOWS-1251", "UTF-8", yandex_text2xml($value, true)).'</param>';
			}
		}
		else
		{
			$param_h = iconv("WINDOWS-1251", "UTF-8", yandex_text2xml($param, true));
			$strProperty .= '<'.$param_h.'>'.iconv("WINDOWS-1251", "UTF-8", yandex_text2xml($value, true)).'</'.$param_h.'>';
		}
	}

	return $strProperty;
}
}
if (!function_exists('yandex_get_value2'))
{
function yandex_get_value2($arOffer, $param, $PROPERTY, &$arProperties, &$arUserTypeFormat, $usedProtocol)
{
	global $iblockServerName;

	$strProperty = '';
	$bParam = (strncmp($param, 'PARAM_', 6) == 0);
	if (isset($arProperties[$PROPERTY]) && !empty($arProperties[$PROPERTY]))
	{
		$PROPERTY_CODE = $arProperties[$PROPERTY]['CODE'];
		$arProperty = (
			isset($arOffer['PROPERTIES'][$PROPERTY_CODE])
			? $arOffer['PROPERTIES'][$PROPERTY_CODE]
			: $arOffer['PROPERTIES'][$PROPERTY]
		);

		$value = '';
		$description = '';
		switch ($arProperties[$PROPERTY]['PROPERTY_TYPE'])
		{
			case 'USER_TYPE':
				if ($arProperty['MULTIPLE'] == 'Y')
				{
					if (!empty($arProperty['~VALUE']))
					{
						$arValues = array();
						foreach($arProperty["~VALUE"] as $oneValue)
						{
							$isArray = is_array($oneValue);
							if (
								($isArray && !empty($oneValue))
								|| (!$isArray && $oneValue != '')
							)
							{
								$arValues[] = call_user_func_array($arUserTypeFormat[$PROPERTY],
									array(
										$arProperty,
										array("VALUE" => $oneValue),
										array('MODE' => 'SIMPLE_TEXT'),
									)
								);
							}
						}
						$value = implode(', ', $arValues);
					}
				}
				else
				{
					$isArray = is_array($arProperty['~VALUE']);
					if (
						($isArray && !empty($arProperty['~VALUE']))
						|| (!$isArray && $arProperty['~VALUE'] != '')
					)
					{
						$value = call_user_func_array($arUserTypeFormat[$PROPERTY],
							array(
								$arProperty,
								array("VALUE" => $arProperty["~VALUE"]),
								array('MODE' => 'SIMPLE_TEXT'),
							)
						);
					}
				}
				break;
			case 'E':
				if (!empty($arProperty['VALUE']))
				{
					$arCheckValue = array();
					if (!is_array($arProperty['VALUE']))
					{
						$arProperty['VALUE'] = (int)$arProperty['VALUE'];
						if (0 < $arProperty['VALUE'])
							$arCheckValue[] = $arProperty['VALUE'];
					}
					else
					{
						foreach ($arProperty['VALUE'] as &$intValue)
						{
							$intValue = (int)$intValue;
							if (0 < $intValue)
								$arCheckValue[] = $intValue;
						}
						if (isset($intValue))
							unset($intValue);
					}
					if (!empty($arCheckValue))
					{
						$dbRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arProperties[$PROPERTY]['LINK_IBLOCK_ID'], 'ID' => $arCheckValue), false, false, array('NAME'));
						while ($arRes = $dbRes->Fetch())
						{
							$value .= ($value ? ', ' : '').$arRes['NAME'];
						}
					}
				}
				break;
			case 'G':
				if (!empty($arProperty['VALUE']))
				{
					$arCheckValue = array();
					if (!is_array($arProperty['VALUE']))
					{
						$arProperty['VALUE'] = (int)$arProperty['VALUE'];
						if (0 < $arProperty['VALUE'])
							$arCheckValue[] = $arProperty['VALUE'];
					}
					else
					{
						foreach ($arProperty['VALUE'] as &$intValue)
						{
							$intValue = (int)$intValue;
							if (0 < $intValue)
								$arCheckValue[] = $intValue;
						}
						if (isset($intValue))
							unset($intValue);
					}
					if (!empty($arCheckValue))
					{
						$dbRes = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $arProperty['LINK_IBLOCK_ID'], 'ID' => $arCheckValue), false, array('NAME'));
						while ($arRes = $dbRes->Fetch())
						{
							$value .= ($value ? ', ' : '').$arRes['NAME'];
						}
					}
				}
				break;
			case 'L':
				if (!empty($arProperty['VALUE']))
				{
					if (is_array($arProperty['VALUE']))
						$value .= implode(', ', $arProperty['VALUE']);
					else
						$value .= $arProperty['VALUE'];
				}
				break;
			case 'F':
				if (!empty($arProperty['VALUE']))
				{
					if (is_array($arProperty['VALUE']))
					{
						foreach ($arProperty['VALUE'] as &$intValue)
						{
							$intValue = (int)$intValue;
							if ($intValue > 0)
							{
								if ($ar_file = CFile::GetFileArray($intValue))
								{
									if(substr($ar_file["SRC"], 0, 1) == "/")
										$strFile = $usedProtocol.$iblockServerName.CHTTP::urnEncode($ar_file['SRC'], 'utf-8');
									else
										$strFile = $ar_file["SRC"];
									$value .= ($value ? ', ' : '').$strFile;
								}
							}
						}
						if (isset($intValue))
							unset($intValue);
					}
					else
					{
						$arProperty['VALUE'] = (int)$arProperty['VALUE'];
						if ($arProperty['VALUE'] > 0)
						{
							if ($ar_file = CFile::GetFileArray($arProperty['VALUE']))
							{
								if(substr($ar_file["SRC"], 0, 1) == "/")
									$strFile = $usedProtocol.$iblockServerName.CHTTP::urnEncode($ar_file['SRC'], 'utf-8');
								else
									$strFile = $ar_file["SRC"];
								$value = $strFile;
							}
						}
					}
				}
				break;
			default:
				if ($bParam && $arProperty['WITH_DESCRIPTION'] == 'Y')
				{
					$description = $arProperty['DESCRIPTION'];
					$value = $arProperty['VALUE'];
				}
				else
				{
					$value = is_array($arProperty['VALUE']) ? implode(', ', $arProperty['VALUE']) : $arProperty['VALUE'];
				}
		}

		// !!!! check multiple properties and properties like CML2_ATTRIBUTES

		if ($bParam)
		{
			if (is_array($description))
			{
				/*foreach ($value as $key => $val)
				{
					$strProperty .= $strProperty ? "\n" : "";
					$strProperty .= '<param name="'.yandex_text2xml($description[$key], true).'">'.yandex_text2xml($val, true).'</param>';
				}*/
			}
			else
			{
			if(yandex_text2xml($arProperties[$PROPERTY]['NAME'], true) == iconv("UTF-8", "WINDOWS-1251", "Фотогалерея") && yandex_text2xml($arProperties[$PROPERTY]['NAME'], true) == iconv("UTF-8", "WINDOWS-1251", "Название первой фотографии"))
				$strProperty .= ''.yandex_text2xml($value, true).'';
			}
		}
		else
		{
			$param_h = yandex_text2xml($param, true);
			$strProperty .= '<'.$param_h.'>'.yandex_text2xml($value, true).'</'.$param_h.'>';
		}
	}

	return $strProperty;
}
}

$arRunErrors = array();

if ($XML_DATA && CheckSerializedData($XML_DATA))
{
	$XML_DATA = unserialize(stripslashes($XML_DATA));
	if (!is_array($XML_DATA)) $XML_DATA = array();
}

$IBLOCK_ID = (int)$IBLOCK_ID;
$db_iblock = CIBlock::GetByID($IBLOCK_ID);
if (!($ar_iblock = $db_iblock->Fetch()))
{
	$arRunErrors[] = str_replace('#ID#', $IBLOCK_ID, GetMessage('YANDEX_ERR_NO_IBLOCK_FOUND_EXT'));
}
/*elseif (!CIBlockRights::UserHasRightTo($IBLOCK_ID, $IBLOCK_ID, 'iblock_admin_display'))
{
	$arRunErrors[] = str_replace('#IBLOCK_ID#',$IBLOCK_ID,GetMessage('CET_ERROR_IBLOCK_PERM'));
} */
else
{
	$SETUP_SERVER_NAME = trim($SETUP_SERVER_NAME);

	if (strlen($SETUP_SERVER_NAME) <= 0)
	{
		if (strlen($ar_iblock['SERVER_NAME']) <= 0)
		{
			$b = "sort";
			$o = "asc";
			$rsSite = CSite::GetList($b, $o, array("LID" => $ar_iblock["LID"]));
			if($arSite = $rsSite->Fetch())
				$ar_iblock["SERVER_NAME"] = $arSite["SERVER_NAME"];
			if(strlen($ar_iblock["SERVER_NAME"])<=0 && defined("SITE_SERVER_NAME"))
				$ar_iblock["SERVER_NAME"] = SITE_SERVER_NAME;
			if(strlen($ar_iblock["SERVER_NAME"])<=0)
				$ar_iblock["SERVER_NAME"] = COption::GetOptionString("main", "server_name", "");
		}
	}
	else
	{
		$ar_iblock['SERVER_NAME'] = $SETUP_SERVER_NAME;
	}
	$ar_iblock['PROPERTY'] = array();
	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'NAME' => 'ASC'),
		array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'N')
	);
	while ($arProp = $rsProps->Fetch())
	{
		$arProp['ID'] = (int)$arProp['ID'];
		$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
		$arProp['CODE'] = (string)$arProp['CODE'];
		$ar_iblock['PROPERTY'][$arProp['ID']] = $arProp;
	}
}

global $iblockServerName;
$iblockServerName = $ar_iblock["SERVER_NAME"];

$arProperties = array();
if (isset($ar_iblock['PROPERTY']))
	$arProperties = $ar_iblock['PROPERTY'];

$boolOffers = false;
$arOffers = false;
$arOfferIBlock = false;
$intOfferIBlockID = 0;
$arSelectOfferProps = array();
$arSelectedPropTypes = array('S','N','L','E','G');
$arOffersSelectKeys = array(
	YANDEX_SKU_EXPORT_ALL,
	YANDEX_SKU_EXPORT_MIN_PRICE,
	YANDEX_SKU_EXPORT_PROP,
);
$arCondSelectProp = array(
	'ZERO',
	'NONZERO',
	'EQUAL',
	'NONEQUAL',
);
$arPropertyMap = array();
$arSKUExport = array();

$arCatalog = CCatalog::GetByIDExt($IBLOCK_ID);
if (empty($arCatalog))
{
	$arRunErrors[] = str_replace('#ID#', $IBLOCK_ID, GetMessage('YANDEX_ERR_NO_IBLOCK_IS_CATALOG'));
}
else
{
	$arOffers = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
	if (!empty($arOffers['IBLOCK_ID']))
	{
		$intOfferIBlockID = $arOffers['IBLOCK_ID'];
		$rsOfferIBlocks = CIBlock::GetByID($intOfferIBlockID);
		if (($arOfferIBlock = $rsOfferIBlocks->Fetch()))
		{
			$boolOffers = true;
			$rsProps = CIBlockProperty::GetList(
				array('SORT' => 'ASC', 'NAME' => 'ASC'),
				array('IBLOCK_ID' => $intOfferIBlockID, 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'N')
			);
			while ($arProp = $rsProps->Fetch())
			{
				$arProp['ID'] = (int)$arProp['ID'];
				if ($arOffers['SKU_PROPERTY_ID'] != $arProp['ID'])
				{
					$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
					$arProp['CODE'] = (string)$arProp['CODE'];
					$ar_iblock['OFFERS_PROPERTY'][$arProp['ID']] = $arProp;
					$arProperties[$arProp['ID']] = $arProp;
					if (in_array($arProp['PROPERTY_TYPE'], $arSelectedPropTypes))
						$arSelectOfferProps[] = $arProp['ID'];
					if ($arProp['CODE'] !== '')
					{
						foreach ($ar_iblock['PROPERTY'] as &$arMainProp)
						{
							if ($arMainProp['CODE'] == $arProp['CODE'])
							{
								$arPropertyMap[$arProp['ID']] = $arMainProp['CODE'];
								break;
							}
						}
						if (isset($arMainProp))
							unset($arMainProp);
					}
				}
			}
			$arOfferIBlock['LID'] = $ar_iblock['LID'];
		}
		else
		{
			$arRunErrors[] = GetMessage('YANDEX_ERR_BAD_OFFERS_IBLOCK_ID');
		}
	}
	if ($boolOffers)
	{
		if (empty($XML_DATA['SKU_EXPORT']))
		{
			$arRunErrors[] = GetMessage('YANDEX_ERR_SKU_SETTINGS_ABSENT');
		}
		else
		{
			$arSKUExport = $XML_DATA['SKU_EXPORT'];;
			if (empty($arSKUExport['SKU_EXPORT_COND']) || !in_array($arSKUExport['SKU_EXPORT_COND'],$arOffersSelectKeys))
			{
				$arRunErrors[] = GetMessage('YANDEX_SKU_EXPORT_ERR_CONDITION_ABSENT');
			}
			if (YANDEX_SKU_EXPORT_PROP == $arSKUExport['SKU_EXPORT_COND'])
			{
				if (empty($arSKUExport['SKU_PROP_COND']) || !is_array($arSKUExport['SKU_PROP_COND']))
				{
					$arRunErrors[] = GetMessage('YANDEX_SKU_EXPORT_ERR_PROPERTY_ABSENT');
				}
				else
				{
					if (empty($arSKUExport['SKU_PROP_COND']['PROP_ID']) || !in_array($arSKUExport['SKU_PROP_COND']['PROP_ID'],$arSelectOfferProps))
					{
						$arRunErrors[] = GetMessage('YANDEX_SKU_EXPORT_ERR_PROPERTY_ABSENT');
					}
					if (empty($arSKUExport['SKU_PROP_COND']['COND']) || !in_array($arSKUExport['SKU_PROP_COND']['COND'],$arCondSelectProp))
					{
						$arRunErrors[] = GetMessage('YANDEX_SKU_EXPORT_ERR_PROPERTY_COND_ABSENT');
					}
					else
					{
						if ($arSKUExport['SKU_PROP_COND']['COND'] == 'EQUAL' || $arSKUExport['SKU_PROP_COND']['COND'] == 'NONEQUAL')
						{
							if (empty($arSKUExport['SKU_PROP_COND']['VALUES']))
							{
								$arRunErrors[] = GetMessage('YANDEX_SKU_EXPORT_ERR_PROPERTY_VALUES_ABSENT');
							}
						}
					}
				}
			}
		}
	}
}

$arUserTypeFormat = array();
foreach($arProperties as $key => $arProperty)
{
	$arProperty["USER_TYPE"] = (string)$arProperty["USER_TYPE"];
	$arUserTypeFormat[$arProperty["ID"]] = false;
	if ($arProperty["USER_TYPE"] !== '')
	{
		$arUserType = CIBlockProperty::GetUserType($arProperty["USER_TYPE"]);
		if (isset($arUserType["GetPublicViewHTML"]))
		{
			$arUserTypeFormat[$arProperty["ID"]] = $arUserType["GetPublicViewHTML"];
			$arProperties[$key]['PROPERTY_TYPE'] = 'USER_TYPE';
		}
	}
}

if (empty($arRunErrors))
{
	$bAllSections = false;
	$arSections = array();
	if (is_array($V))
	{
		foreach ($V as $key => $value)
		{
			if (trim($value)=="0")
			{
				$bAllSections = true;
				break;
			}
			$value = (int)$value;
			if ($value > 0)
			{
				$arSections[] = $value;
			}
		}
	}

	if (!$bAllSections && empty($arSections))
	{
		$arRunErrors[] = GetMessage('YANDEX_ERR_NO_SECTION_LIST');
	}
}

if (!empty($XML_DATA['PRICE']))
{
	if ((int)$XML_DATA['PRICE'] > 0)
	{
		$rsCatalogGroups = CCatalogGroup::GetGroupsList(array('CATALOG_GROUP_ID' => $XML_DATA['PRICE'],'GROUP_ID' => 2));
		if (!($arCatalogGroup = $rsCatalogGroups->Fetch()))
		{
			$arRunErrors[] = GetMessage('YANDEX_ERR_BAD_PRICE_TYPE');
		}
	}
	else
	{
		$arRunErrors[] = GetMessage('YANDEX_ERR_BAD_PRICE_TYPE');
	}
}

$usedProtocol = (isset($USE_HTTPS) && $USE_HTTPS == 'Y' ? 'https://' : 'http://');

if (strlen($SETUP_FILE_NAME) <= 0)
{
	$arRunErrors[] = GetMessage("CATI_NO_SAVE_FILE");
}
elseif (preg_match(BX_CATALOG_FILENAME_REG,$SETUP_FILE_NAME))
{
	$arRunErrors[] = GetMessage("CES_ERROR_BAD_EXPORT_FILENAME");
}
else
{
	$SETUP_FILE_NAME = Rel2Abs("/", $SETUP_FILE_NAME);
}
if (empty($arRunErrors))
{
/*	if ($GLOBALS["APPLICATION"]->GetFileAccessPermission($SETUP_FILE_NAME) < "W")
	{
		$arRunErrors[] = str_replace('#FILE#', $SETUP_FILE_NAME,GetMessage('YANDEX_ERR_FILE_ACCESS_DENIED'));
	} */
}

if (empty($arRunErrors))
{
	CheckDirPath($_SERVER["DOCUMENT_ROOT"].$SETUP_FILE_NAME);

	if (!$fp = @fopen($_SERVER["DOCUMENT_ROOT"].$SETUP_FILE_NAME, "wb"))
	{
		$arRunErrors[] = str_replace('#FILE#', $_SERVER["DOCUMENT_ROOT"].$SETUP_FILE_NAME, GetMessage('YANDEX_ERR_FILE_OPEN_WRITING'));
	}
	else
	{

		/*if (!@fwrite($fp, '<?if (!isset($_GET["referer1"]) || strlen($_GET["referer1"])<=0) $_GET["referer1"] = "yandext";?>'))
		{
			$arRunErrors[] = str_replace('#FILE#', $_SERVER["DOCUMENT_ROOT"].$SETUP_FILE_NAME, GetMessage('YANDEX_ERR_SETUP_FILE_WRITE'));
			@fclose($fp);
		}
		else
		{
			fwrite($fp, '<? $strReferer1 = htmlspecialchars($_GET["referer1"]); ?>');
			fwrite($fp, '<?if (!isset($_GET["referer2"]) || strlen($_GET["referer2"]) <= 0) $_GET["referer2"] = "";?>');
			fwrite($fp, '<? $strReferer2 = htmlspecialchars($_GET["referer2"]); ?>');
		}*/
	}
}

if (empty($arRunErrors))
{
	fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>');
	fwrite($fp, '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">');
	fwrite($fp, '<channel>'."\n");

	fwrite($fp, '<title>Cafre.ru'."</title>\n");

	fwrite($fp, '<link>'.$usedProtocol.htmlspecialcharsbx($ar_iblock['SERVER_NAME'])."</link>\n");
	fwrite($fp, '<description>Универсальный интернет-магазин'."</description>\n");

	fwrite($fp, $strTmp);
	unset($strTmp);

	//*****************************************//


	//*****************************************//
	$intMaxSectionID = 0;

	$strTmpCat = '';
	$strTmpOff = '';

	$arSectionIDs = array();
	$arAvailGroups = array();
	if (!$bAllSections)
	{
		for ($i = 0, $intSectionsCount = count($arSections); $i < $intSectionsCount; $i++)
		{
			$filter_tmp = $filter;
			$sectionIterator = CIBlockSection::GetNavChain($IBLOCK_ID, $arSections[$i], array('ID', 'IBLOCK_SECTION_ID', 'NAME', 'LEFT_MARGIN', 'RIGHT_MARGIN'));
			$curLEFT_MARGIN = 0;
			$curRIGHT_MARGIN = 0;
			while ($section = $sectionIterator->Fetch())
			{
				$section['ID'] = (int)$section['ID'];
				$section['IBLOCK_SECTION_ID'] = (int)$section['IBLOCK_SECTION_ID'];
				if ($arSections[$i] == $section['ID'])
				{
					$curLEFT_MARGIN = (int)$section['LEFT_MARGIN'];
					$curRIGHT_MARGIN = (int)$section['RIGHT_MARGIN'];
					$arSectionIDs[] = $section['ID'];
				}
				$arAvailGroups[$section['ID']] = array(
					'ID' => $section['ID'],
					'IBLOCK_SECTION_ID' => $section['IBLOCK_SECTION_ID'],
					'NAME' => $section['NAME']
				);
				
				if ($intMaxSectionID < $section['ID'])
					$intMaxSectionID = $section['ID'];
			}
			unset($section, $sectionIterator);

			$filter = array("IBLOCK_ID"=>$IBLOCK_ID, ">LEFT_MARGIN"=>$curLEFT_MARGIN, "<RIGHT_MARGIN"=>$curRIGHT_MARGIN, "ACTIVE"=>"Y", "IBLOCK_ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y");
			$sectionIterator = CIBlockSection::GetList(array("LEFT_MARGIN"=>"ASC"), $filter, false, array('ID', 'IBLOCK_SECTION_ID', 'NAME'));
			while ($section = $sectionIterator->Fetch())
			{
				$section["ID"] = (int)$section["ID"];
				$section["IBLOCK_SECTION_ID"] = (int)$section["IBLOCK_SECTION_ID"];
				$arSectionIDs[] = $section["ID"];
				$arAvailGroups[$section["ID"]] = $section;
				if ($intMaxSectionID < $section["ID"])
					$intMaxSectionID = $section["ID"];
			}
			unset($section, $sectionIterator);
		}
		if (!empty($arSectionIDs))
			$arSectionIDs = array_unique($arSectionIDs);
	}
	else
	{
		$filter = array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE"=>"Y", "IBLOCK_ACTIVE"=>"Y", "GLOBAL_ACTIVE"=>"Y");
		$sectionIterator = CIBlockSection::GetList(array("LEFT_MARGIN"=>"ASC"), $filter, false, array('ID', 'IBLOCK_SECTION_ID', 'NAME'));
		while ($section = $sectionIterator->Fetch())
		{
			$section["ID"] = (int)$section["ID"];
			$section["IBLOCK_SECTION_ID"] = (int)$section["IBLOCK_SECTION_ID"];
			$arAvailGroups[$section["ID"]] = $section;
			if ($intMaxSectionID < $section["ID"])
				$intMaxSectionID = $section["ID"];
		}
		unset($section, $sectionIterator);

		if (!empty($arAvailGroups))
			$arSectionIDs = array_keys($arAvailGroups);
	}

	foreach ($arAvailGroups as &$value)
	{
		$strTmpCat.= '<category id="'.$value['ID'].'"'.($value['IBLOCK_SECTION_ID'] > 0 ? ' parentId="'.$value['IBLOCK_SECTION_ID'].'"' : '').'>'.yandex_text2xml($value['NAME'], true).'</category>'."\n";
	}
	if (isset($value))
		unset($value);

	$intMaxSectionID += 100000000;

	//*****************************************//
	$boolNeedRootSection = false;

	CCatalogProduct::setPriceVatIncludeMode(true);
	CCatalogProduct::setUsedCurrency($BASE_CURRENCY);
	CCatalogProduct::setUseDiscount(true);

	if ($arCatalog['CATALOG_TYPE'] == CCatalogSKU::TYPE_CATALOG || $arCatalog['CATALOG_TYPE'] == CCatalogSKU::TYPE_OFFERS)
	{
		$arSelect = array(
			"ID", "LID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME",
			"PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_TEXT", "DETAIL_TEXT_TYPE", "PREVIEW_TEXT_TYPE", "DETAIL_PICTURE", "LANG_DIR", "DETAIL_PAGE_URL",
			"CATALOG_AVAILABLE"
		);

		$filter = array("IBLOCK_ID" => $IBLOCK_ID, "!IBLOCK_SECTION_ID" => array(4488, 4830, 4860, 4917, 4918, 4919, 5033, 5102, 5104, 5205, 5206, 5218, 5224, 5355, 5361, 5395, 5403, 5411, 5466, 6112, 6224), ">IBLOCK_SECTION_ID" => 0);
		if (!$bAllSections && !empty($arSectionIDs))
		{
			$filter["INCLUDE_SUBSECTIONS"] = "Y";
			$filter["SECTION_ID"] = $arSectionIDs;
		}
		$filter["ACTIVE"] = "Y";
		$filter["ACTIVE_DATE"] = "Y";
		$res = CIBlockElement::GetList(array('ID' => 'ASC'), $filter, false, false, $arSelect);

		$total_sum = 0;
		$is_exists = false;
		$cnt = 0;
		while ($obElement = $res->GetNextElement())
		{
			$cnt++;
			$arAcc = $obElement->GetFields();
$arSelect3 = Array("ID", "PROPERTY_CML2_LINK");
$arFilter3 = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arAcc['ID']);
$res3 = CIBlockElement::GetList(Array(), $arFilter3, false, Array(), $arSelect3);
if($ob3 = $res3->GetNextElement())
{
 $arFields3 = $ob3->GetFields();
 $quant = CCatalogProduct::GetByID($arFields3["ID"]);
  if($quant["QUANTITY"] == 0)continue;
}
			if (is_array($XML_DATA['XML_DATA']))
			{
				$arAcc["PROPERTIES"] = $obElement->GetProperties();
			}
			$str_AVAILABLE = ' available="'.($arAcc['CATALOG_AVAILABLE'] == 'Y' ? 'true' : 'false').'"';

			$fullPrice = 0;
			$minPrice = 0;
			$minPriceRUR = 0;
			$minPriceGroup = 0;
			$minPriceCurrency = "";

			if ($XML_DATA['PRICE'] > 0)
			{
				$rsPrices = CPrice::GetListEx(array(),array(
					'PRODUCT_ID' => $arAcc['ID'],
					'CATALOG_GROUP_ID' => $XML_DATA['PRICE'],
					'CAN_BUY' => 'Y',
					'GROUP_GROUP_ID' => array(2),
					'>=QUANTITY_FROM' => 1,
					'>=QUANTITY_TO' => 1,
					)
				);
				if ($arPrice = $rsPrices->Fetch())
				{
					if ($arOptimalPrice = CCatalogProduct::GetOptimalPrice(
						$arAcc['ID'],
						1,
						array(2), // anonymous
						'N',
						array($arPrice),
						$ar_iblock['LID'],
						array()
					))
					{
/*						$minPrice = $arOptimalPrice['DISCOUNT_PRICE'];
						$minPriceCurrency = $BASE_CURRENCY;
						$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
						$minPrice = $arOptimalPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
						$fullPrice = $arOptimalPrice['RESULT_PRICE']['BASE_PRICE'];
						$minPriceCurrency = $arOptimalPrice['RESULT_PRICE']['CURRENCY'];
						if ($minPriceCurrency == $RUR)
							$minPriceRUR = $minPrice;
						else
							$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
						$minPriceGroup = $arOptimalPrice['PRICE']['CATALOG_GROUP_ID'];
					}
				}
			}
			else
			{
				if ($arPrice = CCatalogProduct::GetOptimalPrice(
					$arAcc['ID'],
					1,
					array(2), // anonymous
					'N',
					array(),
					$ar_iblock['LID'],
					array()
				))
				{
/*					$minPrice = $arPrice['DISCOUNT_PRICE'];
					$minPriceCurrency = $BASE_CURRENCY;
					$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
					$minPrice = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
					$fullPrice = $arPrice['RESULT_PRICE']['BASE_PRICE'];
					$minPriceCurrency = $arPrice['RESULT_PRICE']['CURRENCY'];
					if ($minPriceCurrency == $RUR)
						$minPriceRUR = $minPrice;
					else
						$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
					$minPriceGroup = $arPrice['PRICE']['CATALOG_GROUP_ID'];
				}
			}

			if ($minPrice <= 0)
				continue;

			$boolCurrentSections = false;
			$bNoActiveGroup = true;
			$strTmpOff_tmp = "";
			$db_res1 = CIBlockElement::GetElementGroups($arAcc["ID"], false, array('ID', 'ADDITIONAL_PROPERTY_ID'));
			while ($ar_res1 = $db_res1->Fetch())
			{
				if (0 < (int)$ar_res1['ADDITIONAL_PROPERTY_ID'])
					continue;
				$boolCurrentSections = true;
				if (in_array((int)$ar_res1["ID"], $arSectionIDs))
				{
					
					$bNoActiveGroup = false;

				}
			}
			if (!$boolCurrentSections)
			{
				$boolNeedRootSection = true;
				
			}
			else
			{
				if ($bNoActiveGroup)
					continue;
			}

			if (strlen($arAcc['DETAIL_PAGE_URL']) <= 0)
				$arAcc['DETAIL_PAGE_URL'] = '/';
			else
				$arAcc['DETAIL_PAGE_URL'] = str_replace(' ', '%20', $arAcc['DETAIL_PAGE_URL']);

			if (is_array($XML_DATA) && $XML_DATA['TYPE'] && $XML_DATA['TYPE'] != 'none')
				$str_TYPE = ' type="'.htmlspecialcharsbx($XML_DATA['TYPE']).'"';
			else
				$str_TYPE = '';

			$strTmpOff.= '<item>\n';
			$strTmpOff.= "<link>".$usedProtocol.$ar_iblock['SERVER_NAME'].htmlspecialcharsbx($arAcc["~DETAIL_PAGE_URL"])."</link>\n";
			$strTmpOff .= "<g:id>".$arAcc["ID"]."</g:id>\n";

			$strTmpOff.= "<g:price>".$minPrice." ".$minPriceCurrency."</g:price>\n";
	$mxResult = CCatalogSku::GetProductInfo($arAcc["ID"]);
					$db_props2 = CIBlockElement::GetProperty(26, $mxResult["ID"], array(), Array("CODE"=>"CATALOG_BREND"));
if($ar_props2 = $db_props2->Fetch()){
$res2 = CIBlockSection::GetByID($ar_props2["VALUE"]);
if($ar_res2 = $res2->GetNext())
	$strTmpOff .= "<g:brand>".iconv("WINDOWS-1251", "UTF-8", $ar_res2["NAME"])."</g:brand>\n";
}
			$strOfferYandex.= "<g:condition>new</g:condition>\n";
			$strOfferYandex.= "<g:availability>in stock</g:availability>\n";
			if ($minPrice < $fullPrice)

			$strTmpOff.= $strTmpOff_tmp;

			$arAcc["DETAIL_PICTURE"] = (int)$arAcc["DETAIL_PICTURE"];
			$arAcc["PREVIEW_PICTURE"] = (int)$arAcc["PREVIEW_PICTURE"];
			if ($arAcc["DETAIL_PICTURE"] > 0 || $arAcc["PREVIEW_PICTURE"] > 0)
			{
				$pictNo = ($arAcc["DETAIL_PICTURE"] > 0 ? $arAcc["DETAIL_PICTURE"] : $arAcc["PREVIEW_PICTURE"]);

				if ($ar_file = CFile::GetFileArray($pictNo))
				{
					if(substr($ar_file["SRC"], 0, 1) == "/")
						$strFile = $usedProtocol.$ar_iblock['SERVER_NAME'].CHTTP::urnEncode($ar_file["SRC"], 'utf-8');
					else
						$strFile = $ar_file["SRC"];
						$newarFile = explode(",", $strFile);
					$strTmpOff.="<g:image_link>".$newarFile[0]."</g:image_link>\n";
				}
			}
			$y = 0;
			foreach ($arYandexFields as $key)
			{
				switch ($key)
				{
				case 'name':
					if (is_array($XML_DATA) && ($XML_DATA['TYPE'] == 'vendor.model' || $XML_DATA['TYPE'] == 'artist.title'))
						continue;

					$strTmpOff .= "<title>".iconv("WINDOWS-1251", "UTF-8", substr(yandex_text2xml(str_replace('  ',' ', $arAcc["~NAME"]), true),0,140))."</title>\n";
					break;
				case 'description':
			if($arAcc["~DETAIL_TEXT"] != "NULL" && $arAcc["~DETAIL_TEXT"] != NULL){
				if(preg_match('/[а-яА-Я]+/',strip_tags($arAcc["~DETAIL_TEXT"]))){
					$strTmpOff .=
						"<description>".
					trim(iconv("WINDOWS-1251", "UTF-8", yandex_text2xml(TruncateText(
							($arAcc["DETAIL_TEXT_TYPE"]=="html"?
							str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u", "yandex_replace_special", mb_substr(strip_tags($arAcc["~DETAIL_TEXT"]), 0, 2000))) : str_replace('  ',' ', preg_replace_callback("'&[^;]*;'", "yandex_replace_special", mb_substr(strip_tags($arAcc["~DETAIL_TEXT"]), 0, 2000)))),
							255), true))).
						"</description>\n";
$masurl = explode("/",$arAcc["DETAIL_PAGE_URL"]);
unset($masurl[0]);unset($masurl[1]);$fruit = array_pop($masurl);$fruit2 = array_pop($masurl);
$category="";
foreach($masurl as $ket => $val_sec){				
						$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, '=CODE' => $val_sec));
if ($arSection = $rsSections->Fetch())
{
	if(!next($masurl)){
$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']);
}else{
	$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']).' > ';
}
}					}
						$strTmpOff .= "<g:product_type>".$category."</g:product_type>\n";
				}
					}else{
						if(preg_match('/[а-яА-Я]+/',strip_tags($arAcc["~PREVIEW_TEXT"]))){
						$strTmpOff .=
						"<description>".
							trim(iconv("WINDOWS-1251", "UTF-8", yandex_text2xml(TruncateText(
							($arAcc["DETAIL_TEXT_TYPE"]=="html"?
							str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u", "yandex_replace_special", mb_substr(strip_tags($arAcc["~PREVIEW_TEXT"]), 0, 2000))) : str_replace('  ',' ', preg_replace_callback("'&[^;]*;'", "yandex_replace_special", mb_substr(strip_tags($arAcc["~PREVIEW_TEXT"]), 0, 2000)))),
							255), true))).
						"</description>\n";
						$masurl = explode("/",$arAcc["DETAIL_PAGE_URL"]);
unset($masurl[0]);unset($masurl[1]);$fruit = array_pop($masurl);$fruit2 = array_pop($masurl);
$category="";
foreach($masurl as $ket => $val_sec){				
						$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, '=CODE' => $val_sec));
if ($arSection = $rsSections->Fetch())
{
	if(!next($masurl)){
$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']);
}else{
	$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']).' > ';
}
}					}
						$strTmpOff .= "<g:product_type>".$category."</g:product_type>\n";
						}
					}
					break;
				case 'param':
					if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && is_array($XML_DATA['XML_DATA']['PARAMS']))
					{
						foreach ($XML_DATA['XML_DATA']['PARAMS'] as $key => $prop_id)
						{
							$strParamValue = '';
							if ($prop_id)
							{
								$strParamValue = yandex_get_value($arAcc, 'PARAM_'.$key, $prop_id, $arProperties, $arUserTypeFormat, $usedProtocol);
							}
							if ('' != $strParamValue)
								$strTmpOff .= $strParamValue."\n";
						}
					}
					break;
				case 'model':
				case 'title':
					if (!is_array($XML_DATA) || !is_array($XML_DATA['XML_DATA']) || !$XML_DATA['XML_DATA'][$key])
					{
						if (
							$key == 'model' && $XML_DATA['TYPE'] == 'vendor.model'
							||
							$key == 'title' && $XML_DATA['TYPE'] == 'artist.title'
						)

						$strTmpOff.= "<".$key.">".yandex_text2xml($arAcc["~NAME"], true)."</".$key.">\n";
					}
					else
					{
						$strValue = '';
						$strValue = yandex_get_value($arAcc, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
						if ('' != $strValue)
							$strTmpOff .= $strValue."\n";
					}
					break;
				case 'year':
					$y++;
					if ($XML_DATA['TYPE'] == 'artist.title')
					{
						if ($y == 1) continue;
					}
					else
					{
						if ($y > 1) continue;
					}

					// no break here

				default:
					if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && $XML_DATA['XML_DATA'][$key])
					{
						$strValue = '';
						$strValue = yandex_get_value($arAcc, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
						if ('' != $strValue)
							$strTmpOff .= $strValue."\n";
					}
				}
			}

			$strTmpOff.= "</item>\n";
			if (100 <= $cnt)
			{
				$cnt = 0;
				CCatalogDiscount::ClearDiscountCache(array(
					'PRODUCT' => true,
					'SECTIONS' => true,
					'PROPERTIES' => true
				));
			}
		}
	}
	elseif ($arCatalog['CATALOG_TYPE'] == CCatalogSKU::TYPE_PRODUCT || $arCatalog['CATALOG_TYPE'] == CCatalogSKU::TYPE_FULL)
	{
		$arOfferSelect = array(
			"ID", "LID", "IBLOCK_ID", "NAME",
			"PREVIEW_PICTURE", "PREVIEW_TEXT", "DETAIL_TEXT", "PREVIEW_TEXT_TYPE", "DETAIL_PICTURE", "DETAIL_PAGE_URL",
			"CATALOG_AVAILABLE"
		);
		$arOfferFilter = array('IBLOCK_ID' => $intOfferIBlockID, '=PROPERTY_'.$arOffers['SKU_PROPERTY_ID'] => 0, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y");
		if (YANDEX_SKU_EXPORT_PROP == $arSKUExport['SKU_EXPORT_COND'])
		{
			$strExportKey = '';
			$mxValues = false;
			if ($arSKUExport['SKU_PROP_COND']['COND'] == 'NONZERO' || $arSKUExport['SKU_PROP_COND']['COND'] == 'NONEQUAL')
				$strExportKey = '!';
			$strExportKey .= 'PROPERTY_'.$arSKUExport['SKU_PROP_COND']['PROP_ID'];
			if ($arSKUExport['SKU_PROP_COND']['COND'] == 'EQUAL' || $arSKUExport['SKU_PROP_COND']['COND'] == 'NONEQUAL')
				$mxValues = $arSKUExport['SKU_PROP_COND']['VALUES'];
			$arOfferFilter[$strExportKey] = $mxValues;
		}

		$arSelect = array(
			"ID", "LID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME",
			"PREVIEW_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "DETAIL_PICTURE", "DETAIL_TEXT", "DETAIL_PAGE_URL"
		);
		if ($arCatalog['CATALOG_TYPE'] == CCatalogSKU::TYPE_FULL)
			$arSelect[] = "CATALOG_AVAILABLE";

		$arFilter = array("IBLOCK_ID" => $IBLOCK_ID, "!IBLOCK_SECTION_ID" => array(4488, 4830, 4860, 4917, 4918, 4919, 5033, 5102, 5104, 5205, 5206, 5218, 5224, 5355, 5361, 5395, 5403, 5411, 5466, 6112, 6224), ">IBLOCK_SECTION_ID" => 0);
		if (!$bAllSections && !empty($arSectionIDs))
		{
			$arFilter["INCLUDE_SUBSECTIONS"] = "Y";
			$arFilter["SECTION_ID"] = $arSectionIDs;
		}
		$arFilter["ACTIVE"] = "Y";
		$arFilter["ACTIVE_DATE"] = "Y";

		$strOfferTemplateURL = '';
		if (!empty($arSKUExport['SKU_URL_TEMPLATE_TYPE']))
		{
			switch($arSKUExport['SKU_URL_TEMPLATE_TYPE'])
			{
				case YANDEX_SKU_TEMPLATE_PRODUCT:
					$strOfferTemplateURL = '#PRODUCT_URL#';
					break;
				case YANDEX_SKU_TEMPLATE_CUSTOM:
					if (!empty($arSKUExport['SKU_URL_TEMPLATE']))
					$strOfferTemplateURL = $arSKUExport['SKU_URL_TEMPLATE'];
					break;
				case YANDEX_SKU_TEMPLATE_OFFERS:
				default:
					$strOfferTemplateURL = '';
					break;
			}
		}

		$cnt = 0;
		$rsItems = CIBlockElement::GetList(array('ID' => 'ASC'), $arFilter, false, false, $arSelect);
		while ($obItem = $rsItems->GetNextElement())
		{
			if(empty($obItem))continue;
			$cnt++;
			$arCross = array();
			$arItem = $obItem->GetFields();
			$arSelect3 = Array("ID", "PROPERTY_CML2_LINK");
$arFilter3 = Array("IBLOCK_ID"=>27, "PROPERTY_CML2_LINK"=>$arItem['ID']);
$res3 = CIBlockElement::GetList(Array(), $arFilter3, false, Array(), $arSelect3);
if($ob3 = $res3->GetNextElement())
{
 $arFields3 = $ob3->GetFields();
 $quant = CCatalogProduct::GetByID($arFields3["ID"]);
  if($quant["QUANTITY"] == 0)continue;
}
			$arItem['PROPERTIES'] = $obItem->GetProperties();
			if (!empty($arItem['PROPERTIES']))
			{
				foreach ($arItem['PROPERTIES'] as &$arProp)
				{
					$arCross[$arProp['ID']] = $arProp;
				}
				if (isset($arProp))
					unset($arProp);
				$arItem['PROPERTIES'] = $arCross;
			}
			$boolItemExport = false;
			$boolItemOffers = false;
			$arItem['OFFERS'] = array();

			$boolCurrentSections = false;
			$boolNoActiveSections = true;
			$strSections = '';
			$rsSections = CIBlockElement::GetElementGroups($arItem["ID"], false, array('ID', 'ADDITIONAL_PROPERTY_ID'));
			while ($arSection = $rsSections->Fetch())
			{
				if (0 < (int)$arSection['ADDITIONAL_PROPERTY_ID'])
					continue;
				$arSection['ID'] = (int)$arSection['ID'];
				$boolCurrentSections = true;
				if (in_array($arSection['ID'], $arSectionIDs))
				{
					
					$boolNoActiveSections = false;
				}
			}
			if (!$boolCurrentSections)
			{
				$boolNeedRootSection = true;
				
			}
			else
			{
				if ($boolNoActiveSections)
					continue;
			}

			$arItem['YANDEX_CATEGORY'] = $strSections;

			$strFile = '';
			$arItem["DETAIL_PICTURE"] = (int)$arItem["DETAIL_PICTURE"];
			$arItem["PREVIEW_PICTURE"] = (int)$arItem["PREVIEW_PICTURE"];
			if ($arItem["DETAIL_PICTURE"] > 0 || $arItem["PREVIEW_PICTURE"] > 0)
			{
				$pictNo = ($arItem["DETAIL_PICTURE"] > 0 ? $arItem["DETAIL_PICTURE"] : $arItem["PREVIEW_PICTURE"]);

				if ($ar_file = CFile::GetFileArray($pictNo))
				{
					if(substr($ar_file["SRC"], 0, 1) == "/")
						$strFile = $usedProtocol.$ar_iblock['SERVER_NAME'].CHTTP::urnEncode($ar_file['SRC'], 'utf-8');
					else
						$strFile = $ar_file["SRC"];
				}
			}
			$arItem['YANDEX_PICT'] = $strFile;
			if($arItem["~PREVIEW_TEXT"] != "NULL" && $arItem["~PREVIEW_TEXT"] != NULL && $arItem["~PREVIEW_TEXT"] != "<p>&nbsp;</p>"){
			$arItem['YANDEX_DESCR'] = yandex_text2xml(strip_tags($arItem["~PREVIEW_TEXT"]), true);
			}elseif($arItem["~DETAIL_TEXT"] != "NULL"){
				$arItem['YANDEX_DESCR'] = yandex_text2xml(strip_tags($arItem["~DETAIL_TEXT"]), true);
			}
			
			
			
			/*$arItem['YANDEX_DESCR'] = yandex_text2xml(TruncateText(
							($arItem["PREVIEW_TEXT_TYPE"]=="html"?
							strip_tags(preg_replace_callback("'&[^;]*;'", "yandex_replace_special", $arItem["~PREVIEW_TEXT"])) : preg_replace_callback("'&[^;]*;'", "yandex_replace_special", $arItem["~PREVIEW_TEXT"])),
							255), true);*/
			

			$arOfferFilter['=PROPERTY_'.$arOffers['SKU_PROPERTY_ID']] = $arItem['ID'];
			$rsOfferItems = CIBlockElement::GetList(array('ID' => 'ASC'), $arOfferFilter, false, false, $arOfferSelect);

			if (!empty($strOfferTemplateURL))
				$rsOfferItems->SetUrlTemplates($strOfferTemplateURL);
			if (YANDEX_SKU_EXPORT_MIN_PRICE == $arSKUExport['SKU_EXPORT_COND'])
			{
				$arCurrentOffer = false;
				$arCurrentPrice = false;
				$dblAllMinPrice = 0;
				$boolFirst = true;

				while ($obOfferItem = $rsOfferItems->GetNextElement())
				{
					$arOfferItem = $obOfferItem->GetFields();
					$fullPrice = 0;
					$minPrice = 0;
					if ($XML_DATA['PRICE'] > 0)
					{
						$rsPrices = CPrice::GetListEx(array(),array(
							'PRODUCT_ID' => $arOfferItem['ID'],
							'CATALOG_GROUP_ID' => $XML_DATA['PRICE'],
							'CAN_BUY' => 'Y',
							'GROUP_GROUP_ID' => array(2),
							'>=QUANTITY_FROM' => 1,
							'>=QUANTITY_TO' => 1,
							)
						);
						if ($arPrice = $rsPrices->Fetch())
						{
							if ($arOptimalPrice = CCatalogProduct::GetOptimalPrice(
								$arOfferItem['ID'],
								1,
								array(2),
								'N',
								array($arPrice),
								$arOfferIBlock['LID'],
								array()
							))
							{
/*								$minPrice = $arOptimalPrice['DISCOUNT_PRICE'];
								$minPriceCurrency = $BASE_CURRENCY;
								$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
								$minPrice = $arOptimalPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
								$fullPrice = $arOptimalPrice['RESULT_PRICE']['BASE_PRICE'];
								$minPriceCurrency = $arOptimalPrice['RESULT_PRICE']['CURRENCY'];
								if ($minPriceCurrency == $RUR)
									$minPriceRUR = $minPrice;
								else
									$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
								$minPriceGroup = $arOptimalPrice['PRICE']['CATALOG_GROUP_ID'];
							}
						}
					}
					else
					{
						if ($arPrice = CCatalogProduct::GetOptimalPrice(
							$arOfferItem['ID'],
							1,
							array(2), // anonymous
							'N',
							array(),
							$arOfferIBlock['LID'],
							array()
						))
						{
/*							$minPrice = $arPrice['DISCOUNT_PRICE'];
							$minPriceCurrency = $BASE_CURRENCY;
							$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
							$minPrice = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
							$fullPrice = $arPrice['RESULT_PRICE']['BASE_PRICE'];
							$minPriceCurrency = $arPrice['RESULT_PRICE']['CURRENCY'];
							if ($minPriceCurrency == $RUR)
								$minPriceRUR = $minPrice;
							else
								$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
							$minPriceGroup = $arPrice['PRICE']['CATALOG_GROUP_ID'];
						}
					}
					if ($minPrice <= 0)
						continue;
					if ($boolFirst)
					{
						$dblAllMinPrice = $minPriceRUR;
						$arCross = (!empty($arItem['PROPERTIES']) ? $arItem['PROPERTIES'] : array());
						$arOfferItem['PROPERTIES'] = $obOfferItem->GetProperties();
						if (!empty($arOfferItem['PROPERTIES']))
						{
							foreach ($arOfferItem['PROPERTIES'] as $arProp)
							{
								$arCross[$arProp['ID']] = $arProp;
							}
						}
						$arOfferItem['PROPERTIES'] = $arCross;

						$arCurrentOffer = $arOfferItem;
						$arCurrentPrice = array(
							'FULL_PRICE' => $fullPrice,
							'MIN_PRICE' => $minPrice,
							'MIN_PRICE_CURRENCY' => $minPriceCurrency,
							'MIN_PRICE_RUR' => $minPriceRUR,
							'MIN_PRICE_GROUP' => $minPriceGroup,
						);
						$boolFirst = false;
					}
					else
					{
						if ($dblAllMinPrice > $minPriceRUR)
						{
							$dblAllMinPrice = $minPriceRUR;
							$arCross = (!empty($arItem['PROPERTIES']) ? $arItem['PROPERTIES'] : array());
							$arOfferItem['PROPERTIES'] = $obOfferItem->GetProperties();
							if (!empty($arOfferItem['PROPERTIES']))
							{
								foreach ($arOfferItem['PROPERTIES'] as $arProp)
								{
									$arCross[$arProp['ID']] = $arProp;
								}
							}
							$arOfferItem['PROPERTIES'] = $arCross;

							$arCurrentOffer = $arOfferItem;
							$arCurrentPrice = array(
								'FULL_PRICE' => $fullPrice,
								'MIN_PRICE' => $minPrice,
								'MIN_PRICE_CURRENCY' => $minPriceCurrency,
								'MIN_PRICE_RUR' => $minPriceRUR,
								'MIN_PRICE_GROUP' => $minPriceGroup,
							);
						}
					}
				}
				if (!empty($arCurrentOffer) && !empty($arCurrentPrice))
				{
					$arOfferItem = $arCurrentOffer;
					$fullPrice = $arCurrentPrice['FULL_PRICE'];
					$minPrice = $arCurrentPrice['MIN_PRICE'];
					$minPriceCurrency = $arCurrentPrice['MIN_PRICE_CURRENCY'];
					$minPriceRUR = $arCurrentPrice['MIN_PRICE_RUR'];
					$minPriceGroup = $arCurrentPrice['MIN_PRICE_GROUP'];

					$arOfferItem['YANDEX_AVAILABLE'] = ($arOfferItem['CATALOG_AVAILABLE'] == 'Y' ? 'true' : 'false');

					if (strlen($arOfferItem['DETAIL_PAGE_URL']) <= 0)
						$arOfferItem['DETAIL_PAGE_URL'] = '/';
					else
						$arOfferItem['DETAIL_PAGE_URL'] = str_replace(' ', '%20', $arOfferItem['DETAIL_PAGE_URL']);

					if (is_array($XML_DATA) && $XML_DATA['TYPE'] && $XML_DATA['TYPE'] != 'none')
						$str_TYPE = ' type="'.htmlspecialcharsbx($XML_DATA['TYPE']).'"';
					else
						$str_TYPE = '';

					$arOfferItem['YANDEX_TYPE'] = $str_TYPE;

					$strOfferYandex = '';
					$strOfferYandex .= '<item>'."\n";
					$strOfferYandex .= "<link>".$usedProtocol.$ar_iblock['SERVER_NAME'].htmlspecialcharsbx($arOfferItem["~DETAIL_PAGE_URL"])."</link>\n";
					$strOfferYandex .= "<g:id>".$arOfferItem["ID"]."</g:id>\n";

					$strOfferYandex .= "<g:price>".$minPrice." ".$minPriceCurrency."</g:price>\n";
						$mxResult = CCatalogSku::GetProductInfo($arOfferItem["ID"]);
					$db_props2 = CIBlockElement::GetProperty(26, $mxResult["ID"], array(), Array("CODE"=>"CATALOG_BREND"));
if($ar_props2 = $db_props2->Fetch()){
$res2 = CIBlockSection::GetByID($ar_props2["VALUE"]);
if($ar_res2 = $res2->GetNext())
	$strOfferYandex .= "<g:brand>".iconv("WINDOWS-1251", "UTF-8", $ar_res2["NAME"])."</g:brand>\n";
}
					$strOfferYandex.= "<g:condition>new</g:condition>\n";
					$strOfferYandex.= "<g:availability>in stock</g:availability>\n";
					if ($minPrice < $fullPrice)

					$strOfferYandex .= $arItem['YANDEX_CATEGORY'];

					$strFile = '';
					$arOfferItem["DETAIL_PICTURE"] = (int)$arOfferItem["DETAIL_PICTURE"];
					$arOfferItem["PREVIEW_PICTURE"] = (int)$arOfferItem["PREVIEW_PICTURE"];
					if ($arOfferItem["DETAIL_PICTURE"] > 0 || $arOfferItem["PREVIEW_PICTURE"] > 0)
					{
						$pictNo = ($arOfferItem["DETAIL_PICTURE"] > 0 ? $arOfferItem["DETAIL_PICTURE"] : $arOfferItem["PREVIEW_PICTURE"]);

						if ($ar_file = CFile::GetFileArray($pictNo))
						{
							if(substr($ar_file["SRC"], 0, 1) == "/")
								$strFile = $usedProtocol.$ar_iblock['SERVER_NAME'].CHTTP::urnEncode($ar_file['SRC'], 'utf-8');
							else
								$strFile = $ar_file["SRC"];
						}
					}
					if (!empty($strFile) || !empty($arItem['YANDEX_PICT']))
					{
						$exp1 = explode(",", $strFile);
						$exp2 = explode(",", $arItem['YANDEX_PICT']);
						$strOfferYandex .= "<g:image_link>".(!empty($strFile) ? $exp1[0] : $exp2[0])."</g:image_link>\n";
					}

					$y = 0;
					foreach ($arYandexFields as $key)
					{
						switch ($key)
						{
						case 'name':
							if (is_array($XML_DATA) && ($XML_DATA['TYPE'] == 'vendor.model' || $XML_DATA['TYPE'] == 'artist.title'))
								continue;

							$strOfferYandex .= "<title>".iconv("WINDOWS-1251", "UTF-8", substr(yandex_text2xml(str_replace('  ',' ', $arOfferItem["~NAME"]), true),0,140))."</title>\n";
							break;
						case 'description':
							$strOfferYandex .= "<description>";
							if (strlen($arOfferItem['~PREVIEW_TEXT']) <= 0)
							{
								if(preg_match('/[а-яА-Я]+/',strip_tags($arItem['YANDEX_DESCR']))){
								$strOfferYandex .= trim(iconv("WINDOWS-1251", "UTF-8", mb_substr(strip_tags($arItem['YANDEX_DESCR']),0,2000)));
								}
							}
							else
							{
								if(preg_match('/[а-яА-Я]+/',strip_tags($arOfferItem["~PREVIEW_TEXT"]))){
								$strOfferYandex .= trim(iconv("WINDOWS-1251", "UTF-8", yandex_text2xml(TruncateText(
									($arOfferItem["PREVIEW_TEXT_TYPE"]=="html"?
										str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/", "yandex_replace_special", mb_substr(strip_tags($arOfferItem["~PREVIEW_TEXT"]),0,2000))) : str_replace('  ',' ', mb_substr(strip_tags($arOfferItem["~PREVIEW_TEXT"]),0,2000))),
										255),
									true)));
								}
							}
							$strOfferYandex .= "</description>\n";
							$masurl = explode("/",$arOfferItem["DETAIL_PAGE_URL"]);
unset($masurl[0]);unset($masurl[1]);$fruit = array_pop($masurl);$fruit2 = array_pop($masurl);
$category="";
foreach($masurl as $ket => $val_sec){				
						$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, '=CODE' => $val_sec));
if ($arSection = $rsSections->Fetch())
{
	if(!next($masurl)){
$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']);
}else{
	$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']).' > ';
}
}					}
						$strOfferYandex .= "<g:product_type>".$category."</g:product_type>\n";
							break;
						case 'param':
							if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && is_array($XML_DATA['XML_DATA']['PARAMS']))
							{
								foreach ($XML_DATA['XML_DATA']['PARAMS'] as $key => $prop_id)
								{
									$strParamValue = '';
									if ($prop_id)
									{
										$strParamValue = yandex_get_value($arOfferItem, 'PARAM_'.$key, $prop_id, $arProperties, $arUserTypeFormat, $usedProtocol);
									}
									if ('' != $strParamValue)
										$strOfferYandex .= $strParamValue."\n";
								}
							}
							break;
						case 'model':
						case 'title':
							if (!is_array($XML_DATA) || !is_array($XML_DATA['XML_DATA']) || !$XML_DATA['XML_DATA'][$key])
							{
								if (
									$key == 'model' && $XML_DATA['TYPE'] == 'vendor.model'
									||
									$key == 'title' && $XML_DATA['TYPE'] == 'artist.title'
								)
								$strOfferYandex .= "<".$key.">".yandex_text2xml($arOfferItem["~NAME"], true)."</".$key.">\n";
							}
							else
							{
								$strValue = '';
								$strValue = yandex_get_value($arOfferItem, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
								if ('' != $strValue)
									$strOfferYandex .= $strValue."\n";
							}
							break;
						case 'year':
							$y++;
							if ($XML_DATA['TYPE'] == 'artist.title')
							{
								if ($y == 1) continue;
							}
							else
							{
								if ($y > 1) continue;
							}
					// no break here
						default:
							if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && $XML_DATA['XML_DATA'][$key])
							{
								$strValue = '';
								$strValue = yandex_get_value($arOfferItem, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
								if ('' != $strValue)
									$strOfferYandex .= $strValue."\n";
							}
						}
					}

					$strOfferYandex .= "</item>\n";
					$arItem['OFFERS'][] = $strOfferYandex;
					$boolItemOffers = true;
					$boolItemExport = true;
				}
			}
			else
			{
				while ($obOfferItem = $rsOfferItems->GetNextElement())
				{
					$arOfferItem = $obOfferItem->GetFields();
					$arCross = (!empty($arItem['PROPERTIES']) ? $arItem['PROPERTIES'] : array());
					$arOfferItem['PROPERTIES'] = $obOfferItem->GetProperties();
					if (!empty($arOfferItem['PROPERTIES']))
					{
						foreach ($arOfferItem['PROPERTIES'] as $arProp)
						{
							$arCross[$arProp['ID']] = $arProp;
						}
					}
					$arOfferItem['PROPERTIES'] = $arCross;

					$arOfferItem['YANDEX_AVAILABLE'] = ($arOfferItem['CATALOG_AVAILABLE'] == 'Y' ? 'true' : 'false');

					$fullPrice = 0;
					$minPrice = 0;
					if ($XML_DATA['PRICE'] > 0)
					{
						$rsPrices = CPrice::GetListEx(array(),array(
							'PRODUCT_ID' => $arOfferItem['ID'],
							'CATALOG_GROUP_ID' => $XML_DATA['PRICE'],
							'CAN_BUY' => 'Y',
							'GROUP_GROUP_ID' => array(2),
							'>=QUANTITY_FROM' => 1,
							'>=QUANTITY_TO' => 1,
							)
						);
						if ($arPrice = $rsPrices->Fetch())
						{
							if ($arOptimalPrice = CCatalogProduct::GetOptimalPrice(
								$arOfferItem['ID'],
								1,
								array(2),
								'N',
								array($arPrice),
								$arOfferIBlock['LID'],
								array()
							))
							{
/*								$minPrice = $arOptimalPrice['DISCOUNT_PRICE'];
								$minPriceCurrency = $BASE_CURRENCY;
								$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
								$minPrice = $arOptimalPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
								$fullPrice = $arOptimalPrice['RESULT_PRICE']['BASE_PRICE'];
								$minPriceCurrency = $arOptimalPrice['RESULT_PRICE']['CURRENCY'];
								if ($minPriceCurrency == $RUR)
									$minPriceRUR = $minPrice;
								else
									$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
								$minPriceGroup = $arOptimalPrice['PRICE']['CATALOG_GROUP_ID'];
							}

						}
					}
					else
					{
						if ($arPrice = CCatalogProduct::GetOptimalPrice(
							$arOfferItem['ID'],
							1,
							array(2), // anonymous
							'N',
							array(),
							$arOfferIBlock['LID'],
							array()
						))
						{
/*							$minPrice = $arPrice['DISCOUNT_PRICE'];
							$minPriceCurrency = $BASE_CURRENCY;
							$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
							$minPrice = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
							$fullPrice = $arPrice['RESULT_PRICE']['BASE_PRICE'];
							$minPriceCurrency = $arPrice['RESULT_PRICE']['CURRENCY'];
							if ($minPriceCurrency == $RUR)
								$minPriceRUR = $minPrice;
							else
								$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
							$minPriceGroup = $arPrice['PRICE']['CATALOG_GROUP_ID'];
						}
					}
					if ($minPrice <= 0)
						continue;

					if (strlen($arOfferItem['DETAIL_PAGE_URL']) <= 0)
						$arOfferItem['DETAIL_PAGE_URL'] = '/';
					else
						$arOfferItem['DETAIL_PAGE_URL'] = str_replace(' ', '%20', $arOfferItem['DETAIL_PAGE_URL']);

					if (is_array($XML_DATA) && $XML_DATA['TYPE'] && $XML_DATA['TYPE'] != 'none')
						$str_TYPE = ' type="'.htmlspecialcharsbx($XML_DATA['TYPE']).'"';
					else
						$str_TYPE = '';

					$arOfferItem['YANDEX_TYPE'] = $str_TYPE;

					$strOfferYandex = '';
					$strOfferYandex .= '<item>'."\n";
					$strOfferYandex .= "<link>".$usedProtocol.$ar_iblock['SERVER_NAME'].htmlspecialcharsbx($arOfferItem["~DETAIL_PAGE_URL"])."</link>\n";
					$strOfferYandex .= "<g:id>".$arOfferItem["ID"]."</g:id>\n";

					$strOfferYandex .= "<g:price>".$minPrice." ".$minPriceCurrency."</g:price>\n";
					$mxResult = CCatalogSku::GetProductInfo($arOfferItem["ID"]);
					$db_props2 = CIBlockElement::GetProperty(26, $mxResult["ID"], array(), Array("CODE"=>"CATALOG_BREND"));
if($ar_props2 = $db_props2->Fetch()){
$res2 = CIBlockSection::GetByID($ar_props2["VALUE"]);
if($ar_res2 = $res2->GetNext())
	$strOfferYandex .= "<g:brand>".iconv("WINDOWS-1251", "UTF-8", $ar_res2["NAME"])."</g:brand>\n";
}
					$strOfferYandex.= "<g:condition>new</g:condition>\n";
					$strOfferYandex.= "<g:availability>in stock</g:availability>\n";
					if ($minPrice < $fullPrice)

					$strOfferYandex .= $arItem['YANDEX_CATEGORY'];

					$strFile = '';
					$arOfferItem["DETAIL_PICTURE"] = (int)$arOfferItem["DETAIL_PICTURE"];
					$arOfferItem["PREVIEW_PICTURE"] = (int)$arOfferItem["PREVIEW_PICTURE"];
					if ($arOfferItem["DETAIL_PICTURE"] > 0 || $arOfferItem["PREVIEW_PICTURE"] > 0)
					{
						$pictNo = ($arOfferItem["DETAIL_PICTURE"] > 0 ? $arOfferItem["DETAIL_PICTURE"] : $arOfferItem["PREVIEW_PICTURE"]);

						if ($ar_file = CFile::GetFileArray($pictNo))
						{
							if(substr($ar_file["SRC"], 0, 1) == "/")
								$strFile = $usedProtocol.$ar_iblock['SERVER_NAME'].CHTTP::urnEncode($ar_file['SRC'], 'utf-8');
							else
								$strFile = $ar_file["SRC"];
						}
					}
					
					if (!empty($strFile) || !empty($arItem['YANDEX_PICT']))
					{	
						$i = 0;
						foreach ($XML_DATA['XML_DATA']['PARAMS'] as $key => $prop_id)
								{
									$i++;
									$strParamValue = '';
									if ($prop_id)
									{
										if(yandex_get_value2($arOfferItem, 'PARAM_'.$key, 246, $arProperties, $arUserTypeFormat, $usedProtocol)){
										$strParamValue = yandex_get_value2($arOfferItem, 'PARAM_'.$key, 246, $arProperties, $arUserTypeFormat, $usedProtocol);
										}elseif(yandex_get_value2($arOfferItem, 'PARAM_'.$key, 242, $arProperties, $arUserTypeFormat, $usedProtocol)){
											$strParamValue = yandex_get_value2($arOfferItem, 'PARAM_'.$key, 242, $arProperties, $arUserTypeFormat, $usedProtocol);
										}
									}
									if ('' != $strParamValue){
										$exp3 = explode(",", $strParamValue);
										$strOfferYandex .= "<g:image_link>".$exp3[0]."</g:image_link>\n";
										
										}elseif($i == 1){
											$exp4 = explode(",", $strFile);
											$exp5 = explode(",", $arItem['YANDEX_PICT']);
										$strOfferYandex .= "<g:image_link>".(!empty($strFile) ? $exp4[0] : $exp5[0])."</g:image_link>\n";
										}
										
									
								}

						//$strOfferYandex .= "<picture>".(!empty($strFile) ? $strFile : $arItem['YANDEX_PICT'])."</picture>\n";
					}else{
						$i=0;
							foreach ($XML_DATA['XML_DATA']['PARAMS'] as $key => $prop_id)
								{
									$i++;
									$strParamValue = '';
									if ($prop_id)
									{
										if(yandex_get_value2($arOfferItem, 'PARAM_'.$key, 246, $arProperties, $arUserTypeFormat, $usedProtocol)){
										$strParamValue = yandex_get_value2($arOfferItem, 'PARAM_'.$key, 246, $arProperties, $arUserTypeFormat, $usedProtocol);
										}elseif(yandex_get_value2($arOfferItem, 'PARAM_'.$key, 242, $arProperties, $arUserTypeFormat, $usedProtocol)){
											$strParamValue = yandex_get_value2($arOfferItem, 'PARAM_'.$key, 242, $arProperties, $arUserTypeFormat, $usedProtocol);
										}
									}
									if ('' != $strParamValue){
										$exp6 = explode(",", $strParamValue);
										$strOfferYandex .= "<g:image_link>".$exp6[0]."</g:image_link>\n";
										
									}elseif($i == 1){
									$exp7 = explode(",", $strFile);
									$exp8 = explode(",", $arItem['YANDEX_PICT']);
									$strOfferYandex .= "<g:image_link>".(!empty($strFile) ? $exp7 : $exp8)."</g:image_link>\n";
									}
								}
						
			//$strOfferYandex.="<picture1>".print_r($arOfferItem)."</picture1>\n";
						}
						/*if($params_title){
							$strOfferYandex .= "<picture>".$params_title."</picture>\n";
						}else{
							$strOfferYandex .= "<picture>".(!empty($strFile) ? $strFile : $arItem['YANDEX_PICT'])."</picture>\n";
						}*/
			

					$y = 0;
					foreach ($arYandexFields as $key)
					{
						switch ($key)
						{
						case 'name':
							if (is_array($XML_DATA) && ($XML_DATA['TYPE'] == 'vendor.model' || $XML_DATA['TYPE'] == 'artist.title'))
								continue;

							$strOfferYandex .= "<title>".iconv("WINDOWS-1251", "UTF-8", substr(yandex_text2xml(str_replace('  ',' ', $arOfferItem["~NAME"]), true),0,140))."</title>\n";
							break;
						case 'description':
							$strOfferYandex .= "<description>";
							if (strlen($arOfferItem['~PREVIEW_TEXT']) <= 0 || strlen($arOfferItem['~DETAIL_TEXT']) <= 0)
							{
								if(preg_match('/[а-яА-Я]+/',strip_tags($arItem['YANDEX_DESCR']))){
								$strOfferYandex .= trim(iconv("WINDOWS-1251", "UTF-8", mb_substr(strip_tags($arItem['YANDEX_DESCR']),0,2000)));
								}
								
							}
							else
							{
								if(preg_match('/[а-яА-Я]+/',strip_tags($arOfferItem["~PREVIEW_TEXT"]))){
								$strOfferYandex .= trim(iconv("WINDOWS-1251", "UTF-8", yandex_text2xml(TruncateText(
									($arOfferItem["PREVIEW_TEXT_TYPE"]=="html"?
										str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/", "yandex_replace_special", mb_substr(strip_tags($arOfferItem["~PREVIEW_TEXT"]),0,2000))) : str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/", "yandex_replace_special", strip_tags(mb_substr($arOfferItem["~PREVIEW_TEXT"],0,2000))))),
										255),
									true)));
								}
							}
							$strOfferYandex .= "</description>\n";
							$masurl = explode("/",$arOfferItem["DETAIL_PAGE_URL"]);
unset($masurl[0]);unset($masurl[1]);$fruit = array_pop($masurl);$fruit2 = array_pop($masurl);
$category="";
foreach($masurl as $ket => $val_sec){				
						$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, '=CODE' => $val_sec));
if ($arSection = $rsSections->Fetch())
{
	if(!next($masurl)){
$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']);
}else{
	$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']).' > ';
}
}					}
						$strOfferYandex .= "<g:product_type>".$category."</g:product_type>\n";
							break;
						case 'param':
							if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && is_array($XML_DATA['XML_DATA']['PARAMS']))
							{
								foreach ($XML_DATA['XML_DATA']['PARAMS'] as $key => $prop_id)
								{
									$strParamValue = '';
									if ($prop_id)
									{
										$strParamValue = yandex_get_value($arOfferItem, 'PARAM_'.$key, $prop_id, $arProperties, $arUserTypeFormat, $usedProtocol);
									}
									if ('' != $strParamValue)
										$strOfferYandex .= $strParamValue."\n";
								}
							}
							break;
						case 'model':
						case 'title':
							if (!is_array($XML_DATA) || !is_array($XML_DATA['XML_DATA']) || !$XML_DATA['XML_DATA'][$key])
							{
								if (
									$key == 'model' && $XML_DATA['TYPE'] == 'vendor.model'
									||
									$key == 'title' && $XML_DATA['TYPE'] == 'artist.title'
								)
								$strOfferYandex .= "<".$key.">".yandex_text2xml($arOfferItem["~NAME"], true)."</".$key.">\n";
							}
							else
							{
								$strValue = '';
								$strValue = yandex_get_value($arOfferItem, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
								if ('' != $strValue)
									$strOfferYandex .= $strValue."\n";
							}
							break;
						case 'year':
							$y++;
							if ($XML_DATA['TYPE'] == 'artist.title')
							{
								if ($y == 1) continue;
							}
							else
							{
								if ($y > 1) continue;
							}
					// no break here
						default:
							if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && $XML_DATA['XML_DATA'][$key])
							{
								$strValue = '';
								$strValue = yandex_get_value($arOfferItem, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
								if ('' != $strValue)
									$strOfferYandex .= $strValue."\n";
							}
						}
					}

					$strOfferYandex .= "</item>\n";
					$arItem['OFFERS'][] = $strOfferYandex;
					$boolItemOffers = true;
					$boolItemExport = true;
				}
			}
			if ($arCatalog['CATALOG_TYPE'] == CCatalogSKU::TYPE_FULL && !$boolItemOffers)
			{
				$str_AVAILABLE = ' available="'.($arItem['CATALOG_AVAILABLE'] == 'Y' ? 'true' : 'false').'"';

				$fullPrice = 0;
				$minPrice = 0;
				$minPriceRUR = 0;
				$minPriceGroup = 0;
				$minPriceCurrency = "";

				if ($XML_DATA['PRICE'] > 0)
				{
					$rsPrices = CPrice::GetListEx(array(),array(
						'PRODUCT_ID' => $arItem['ID'],
						'CATALOG_GROUP_ID' => $XML_DATA['PRICE'],
						'CAN_BUY' => 'Y',
						'GROUP_GROUP_ID' => array(2),
						'>=QUANTITY_FROM' => 1,
						'>=QUANTITY_TO' => 1,
						)
					);
					if ($arPrice = $rsPrices->Fetch())
					{
						if ($arOptimalPrice = CCatalogProduct::GetOptimalPrice(
							$arItem['ID'],
							1,
							array(2),
							'N',
							array($arPrice),
							$ar_iblock['LID'],
							array()
						))
						{
/*							$minPrice = $arOptimalPrice['DISCOUNT_PRICE'];
							$minPriceCurrency = $BASE_CURRENCY;
							$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
							$minPrice = $arOptimalPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
							$fullPrice = $arOptimalPrice['RESULT_PRICE']['BASE_PRICE'];
							$minPriceCurrency = $arOptimalPrice['RESULT_PRICE']['CURRENCY'];
							if ($minPriceCurrency == $RUR)
								$minPriceRUR = $minPrice;
							else
								$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
							$minPriceGroup = $arOptimalPrice['PRICE']['CATALOG_GROUP_ID'];
						}
					}
				}
				else
				{
					if ($arPrice = CCatalogProduct::GetOptimalPrice(
						$arItem['ID'],
						1,
						array(2), // anonymous
						'N',
						array(),
						$ar_iblock['LID'],
						array()
					))
					{
/*						$minPrice = $arPrice['DISCOUNT_PRICE'];
						$minPriceCurrency = $BASE_CURRENCY;
						$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $BASE_CURRENCY, $RUR); */
						$minPrice = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
						$fullPrice = $arPrice['RESULT_PRICE']['BASE_PRICE'];
						$minPriceCurrency = $arPrice['RESULT_PRICE']['CURRENCY'];
						if ($minPriceCurrency == $RUR)
							$minPriceRUR = $minPrice;
						else
							$minPriceRUR = CCurrencyRates::ConvertCurrency($minPrice, $minPriceCurrency, $RUR);
						$minPriceGroup = $arPrice['PRICE']['CATALOG_GROUP_ID'];
					}
				}

				if ($minPrice <= 0) continue;

				if ('' == $arItem['DETAIL_PAGE_URL'])
				{
					$arItem['DETAIL_PAGE_URL'] = '/';
				}
				else
				{
					$arItem['DETAIL_PAGE_URL'] = str_replace(' ', '%20', $arItem['DETAIL_PAGE_URL']);
				}
				if ('' == $arItem['~DETAIL_PAGE_URL'])
				{
					$arItem['~DETAIL_PAGE_URL'] = '/';
				}
				else
				{
					$arItem['~DETAIL_PAGE_URL'] = str_replace(' ', '%20', $arItem['~DETAIL_PAGE_URL']);
				}

				if (is_array($XML_DATA) && $XML_DATA['TYPE'] && $XML_DATA['TYPE'] != 'none')
					$str_TYPE = ' type="'.htmlspecialcharsbx($XML_DATA['TYPE']).'"';
				else
					$str_TYPE = '';

				$strOfferYandex = '';
				$strOfferYandex.= "<item>\n";
				$strOfferYandex.= "<link>".$usedProtocol.$ar_iblock['SERVER_NAME'].htmlspecialcharsbx($arItem["~DETAIL_PAGE_URL"])."</link>\n";
				$strOfferYandex .= "<g:id>".$arItem["ID"]."</g:id>\n";

				$strOfferYandex.= "<g:price>".$minPrice." ".$minPriceCurrency."</g:price>\n";
				$mxResult = CCatalogSku::GetProductInfo($arItem["ID"]);
					$db_props2 = CIBlockElement::GetProperty(26, $mxResult["ID"], array(), Array("CODE"=>"CATALOG_BREND"));
if($ar_props2 = $db_props2->Fetch()){
$res2 = CIBlockSection::GetByID($ar_props2["VALUE"]);
if($ar_res2 = $res2->GetNext())
	$strOfferYandex .= "<g:brand>".iconv("WINDOWS-1251", "UTF-8", $ar_res2["NAME"])."</g:brand>\n";
}
				$strOfferYandex.= "<g:condition>new</g:condition>\n";
				$strOfferYandex.= "<g:availability>in stock</g:availability>\n";
				if ($minPrice < $fullPrice)

				$strOfferYandex.= $arItem['YANDEX_CATEGORY'];

				if (!empty($arItem['YANDEX_PICT']))
				{
					$exp9 = explode(",", $arItem['YANDEX_PICT']);
					$strOfferYandex .= "<g:image_link>".$exp9."</g:image_link>\n";
				}

				$y = 0;
				foreach ($arYandexFields as $key)
				{
					$strValue = '';
					switch ($key)
					{
					case 'name':
						if (is_array($XML_DATA) && ($XML_DATA['TYPE'] == 'vendor.model' || $XML_DATA['TYPE'] == 'artist.title'))
							continue;

						$strValue = "<title>".iconv("WINDOWS-1251", "UTF-8", substr(yandex_text2xml(str_replace('  ',' ', $arItem["~NAME"]), true),140))."</title>\n";
						break;
					case 'description':
					if($arItem["~DETAIL_TEXT"] != "NULL" && $arItem["~DETAIL_TEXT"] != NULL){
						if(preg_match('/[а-яА-Я]+/',strip_tags($arItem["~DETAIL_TEXT"]))){
						$strValue =
							"<description>".
							trim(iconv("WINDOWS-1251", "UTF-8", yandex_text2xml(TruncateText(
								($arItem["PREVIEW_TEXT_TYPE"]=="html"?
								str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u", "yandex_replace_special", mb_substr(strip_tags($arItem["~DETAIL_TEXT"]),0,2000))) : str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u", "yandex_replace_special", mb_substr(strip_tags($arItem["~DETAIL_TEXT"]),0,2000)))),
								255), true))).
							"</description>\n";
							$masurl = explode("/",$arItem["DETAIL_PAGE_URL"]);
unset($masurl[0]);unset($masurl[1]);$fruit = array_pop($masurl);$fruit2 = array_pop($masurl);
$category="";
foreach($masurl as $ket => $val_sec){				
						$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, '=CODE' => $val_sec));
if ($arSection = $rsSections->Fetch())
{
	if(!next($masurl)){
$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']);
}else{
	$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']).' > ';
}
}					}
						$strValue .= "<g:product_type>".$category."</g:product_type>\n";
						}
						}else{
							if(preg_match('/[а-яА-Я]+/',strip_tags($arItem["~PREVIEW_TEXT"]))){
							$strValue =
							"<description>".
								trim(iconv("WINDOWS-1251", "UTF-8", yandex_text2xml(TruncateText(
								($arItem["PREVIEW_TEXT_TYPE"]=="html"?
								str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u", "yandex_replace_special", mb_substr(strip_tags($arItem["~PREVIEW_TEXT"]),0,2000))) : str_replace('  ',' ', preg_replace_callback("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u", "yandex_replace_special", mb_substr(strip_tags($arItem["~PREVIEW_TEXT"]),0,2000)))),
								255), true))).
							"</description>\n";
							$masurl = explode("/",$arItem["DETAIL_PAGE_URL"]);
unset($masurl[0]);unset($masurl[1]);$fruit = array_pop($masurl);$fruit2 = array_pop($masurl);
$category="";
foreach($masurl as $ket => $val_sec){				
						$rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => 26, '=CODE' => $val_sec));
if ($arSection = $rsSections->Fetch())
{
	if(!next($masurl)){
$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']);
}else{
	$category.= iconv("WINDOWS-1251", "UTF-8", $arSection['NAME']).' > ';
}
}					}
						$strValue .= "<g:product_type>".$category."</g:product_type>\n";
							}
						}
						break;
					case 'param':
						if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && is_array($XML_DATA['XML_DATA']['PARAMS']))
						{
							foreach ($XML_DATA['XML_DATA']['PARAMS'] as $key => $prop_id)
							{
								$strParamValue = '';
								if ($prop_id)
								{
									$strParamValue = yandex_get_value($arItem, 'PARAM_'.$key, $prop_id, $arProperties, $arUserTypeFormat, $usedProtocol);
								}
								if ('' != $strParamValue)
									$strValue .= $strParamValue."\n";
							}
						}
						break;
					case 'model':
					case 'title':
						if (!is_array($XML_DATA) || !is_array($XML_DATA['XML_DATA']) || !$XML_DATA['XML_DATA'][$key])
						{
							if (
								$key == 'model' && $XML_DATA['TYPE'] == 'vendor.model'
								||
								$key == 'title' && $XML_DATA['TYPE'] == 'artist.title'
							)

							$strValue = "<".$key.">".yandex_text2xml($arItem["~NAME"], true)."</".$key.">\n";
						}
						else
						{
							$strValue = yandex_get_value($arItem, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
							if ('' != $strValue)
								$strValue .= "\n";
						}
						break;
					case 'year':
						$y++;
						if ($XML_DATA['TYPE'] == 'artist.title')
						{
							if ($y == 1) continue;
						}
						else
						{
							if ($y > 1) continue;
						}

					// no break here

					default:
						//if (is_array($XML_DATA) && is_array($XML_DATA['XML_DATA']) && $XML_DATA['XML_DATA'][$key])
						if (isset($XML_DATA['XML_DATA'][$key]))
						{
							$strValue = yandex_get_value($arItem, $key, $XML_DATA['XML_DATA'][$key], $arProperties, $arUserTypeFormat, $usedProtocol);
							if ('' != $strValue)
								$strValue .= "\n";
						}
					}
					if ('' != $strValue)
						$strOfferYandex .= $strValue;
				}

				$strOfferYandex .= "</item>\n";

				if ('' != $strOfferYandex)
				{
					$arItem['OFFERS'][] = $strOfferYandex;
					$boolItemOffers = true;
					$boolItemExport = true;
				}
			}
			if (100 <= $cnt)
			{
				$cnt = 0;
				CCatalogDiscount::ClearDiscountCache(array(
					'PRODUCT' => true,
					'SECTIONS' => true,
					'PROPERTIES' => true
				));
			}
			if (!$boolItemExport)
				continue;
			foreach ($arItem['OFFERS'] as $strOfferItem)
			{
				$strTmpOff .= $strOfferItem;
			}
		}
	}


	
	fwrite($fp, $strTmpOff);
	

	fwrite($fp, "</channel>\n");
	fwrite($fp, "</rss>\n");

	fclose($fp);
}

CCatalogDiscountSave::Enable();

if (!empty($arRunErrors))
	$strExportErrorMessage = implode('<br />',$arRunErrors);

if ($bTmpUserCreated)
{
	unset($USER);
	if (isset($USER_TMP))
	{
		$USER = $USER_TMP;
		unset($USER_TMP);
	}
}
?>