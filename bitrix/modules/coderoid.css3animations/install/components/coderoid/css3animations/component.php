<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

IncludeTemplateLangFile(__FILE__);

CModule::IncludeModule("iblock");
$arSelect = array(
    "ID",
    "IBLOCK_ID",
    "CODE",
    "XML_ID",
    "NAME",
    "ACTIVE",
    "DATE_ACTIVE_FROM",
    "DATE_ACTIVE_TO",
    "SORT",
    "PREVIEW_TEXT",
    "PREVIEW_TEXT_TYPE",
    "DETAIL_TEXT",
    "DETAIL_TEXT_TYPE",
    "DATE_CREATE",
    "CREATED_BY",
    "TIMESTAMP_X",
    "MODIFIED_BY",
    "TAGS",
    "IBLOCK_SECTION_ID",
    "DETAIL_PICTURE",
    "PREVIEW_PICTURE",
    "DETAIL_PAGE_URL",
	"PROPERTY_".$arParams["URL_FROM"],		
    "PROPERTY_" . $arParams["SELECT_FROM"],
);

$arFilter    = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], 'GLOBAL_ACTIVE'=>'Y');

$elements = CIBlockElement::GetList(array(), array("IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"], "ACTIVE" => "Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, false, $arSelect);
$arResult["ITEMS"] = array();
while ($arItem = $elements->GetNext()) {
    if ($arParams["URL_FROM"] != "FROM_SETTINGS") {
        $arItem["DETAIL_PAGE_URL"] = $arItem["PROPERTY_" . $arParams["URL_FROM"] . "_VALUE"];
    }
    $arButtons = CIBlock::GetPanelButtons(
        $arItem["IBLOCK_ID"],
        $arItem["ID"],
        $arResult["ID"],
        array("SECTION_BUTTONS" => false, "SESSID" => false, "CATALOG" => true)
    );
    $arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
    $arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
    if ($arParams["SELECT_FROM"] != "PREVIEW_PICTURE" && $arParams["SELECT_FROM"] != "DETAIL_PICTURE") {
        $selectFrom = $arItem["PROPERTY_" . $arParams["SELECT_FROM"] . "_VALUE"];
        if (!empty($arParams["PREVIEW_WIDTH"]) && !empty($arParams["PREVIEW_HEIGHT"])) {
            $pic = CFile::ResizeImageGet($selectFrom,
                array("width" => $arParams["PREVIEW_WIDTH"], "height" => $arParams["PREVIEW_HEIGHT"]), BX_RESIZE_IMAGE_PROPORTIONAL, false);
            $arItem["SELECT_FROM"] = array();
            $arItem["SELECT_FROM"]["SRC"] = $pic["src"];
        } else {
            $pic = CFile::GetPath($selectFrom);
            $arItem["SELECT_FROM"] = array();
            $arItem["SELECT_FROM"]["SRC"] = $pic;
        }
    } else {
        $selectFrom = $arItem[$arParams["SELECT_FROM"]];
        if (!empty($arParams["PREVIEW_WIDTH"]) && !empty($arParams["PREVIEW_HEIGHT"])) {
            $pic = CFile::ResizeImageGet($selectFrom,
                array("width" => $arParams["PREVIEW_WIDTH"], "height" => $arParams["PREVIEW_HEIGHT"]), BX_RESIZE_IMAGE_PROPORTIONAL, false);
            $arItem["SELECT_FROM"] = array();
            $arItem["SELECT_FROM"]["SRC"] = $pic["src"];
        } else {
            $pic = CFile::GetPath($selectFrom);
            $arItem["SELECT_FROM"] = array();
            $arItem["SELECT_FROM"]["SRC"] = $pic;
        }
    }

    $arResult["ITEMS"][] = $arItem;
}
$this->IncludeComponentTemplate();