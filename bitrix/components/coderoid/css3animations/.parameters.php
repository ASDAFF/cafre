<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER_FIELD_MANAGER;

if (!CModule::IncludeModule("iblock"))
    return;

IncludeTemplateLangFile(__FILE__);

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$effect_type = array("Flip" => "Flip", "Rotation" => "Rotation", "Multi-flip" => "Multi-flip", "Cube" => "Cube", "Unfold" => "Unfold", "Others" => "Others");
$effect_type_flip = array("te-flip1" => "Flip1", "te-flip2" => "Flip2", "te-flip3" => "Flip3", "te-flip4" => "Flip4");
$effect_type_rotation = array("te-rotation1" => "Rotation1", "te-rotation2" => "Rotation2", "te-rotation3" => "Rotation3", "te-rotation4" => "Rotation4", "te-rotation5" => "Rotation5");
$effect_type_multi_flip = array("te-multiflip1" => "Multi-flip1", "te-multiflip2" => "Multi-flip2", "te-multiflip3" => "Multi-flip3");
$effect_type_cube = array("te-cube1" => "Cube1", "te-cube2" => "Cube2", "te-cube3" => "Cube3", "te-cube4" => "Cube4");
$effect_type_unfold = array("te-unfold1" => "Unfold1", "te-unfold2" => "Unfold2");
$effect_type_others = array("te-example1" => "Example1", "te-example2" => "Example2", "te-example3" => "Example3", "te-example4" => "Example4", "te-example5" => "Example5", "te-example6" => "Example6", "te-example7" => "Example7");

$arIBlock = array();
$rsIBlock = CIBlock::GetList(array("sort" => "asc"), array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE" => "Y"));
while ($arr = $rsIBlock->Fetch())
    $arIBlock[$arr["ID"]] = "[" . $arr["ID"] . "] " . $arr["NAME"];

$selectFrom = array();
$selectFrom["PREVIEW_PICTURE"] = GetMessage("CODEROID_css3animations_PREVIEW_PICTURE");
$selectFrom["DETAIL_PICTURE"] = GetMessage("CODEROID_css3animations_DETAIL_PICTURE");

$properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array(
    "ACTIVE" => "Y", "IBLOCK_ID" => $arCurrentValues["IBLOCK_ID"], "PROPERTY_TYPE" => "F"));
while ($prop_fields = $properties->GetNext()) {
    $selectFrom[$prop_fields["CODE"]] = $prop_fields["NAME"];
}

$urlFrom = array();
$urlFrom["FROM_SETTINGS"] = GetMessage("CODEROID_css3animations_FROM_SETTINGS");
$urlFromProperties = CIBlockProperty::GetList(array("sort" => "asc", "name" => "asc"), array(
    "ACTIVE" => "Y", "IBLOCK_ID" => $arCurrentValues["IBLOCK_ID"], "PROPERTY_TYPE" => "S"
));
while ($urlFromFields = $urlFromProperties->GetNext()) {
    $urlFrom[$urlFromFields["CODE"]] = $urlFromFields["NAME"];
}

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_css3animations_IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_css3animations_IBLOCK_IBLOCK"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "N",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
        ),
        "SELECT_FROM" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_css3animations_SELECT_FROM"),
            "TYPE" => "LIST",
            "VALUES" => $selectFrom,
            "REFRESH" => "Y",
        ),
        "EFFECT_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_css3animations_EFFECT_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $effect_type,
            "REFRESH" => "Y",			
        )
    ));

if($arCurrentValues["EFFECT_TYPE"] == "Flip")
{
    $arComponentParameters["PARAMETERS"]["EFFECT"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_EFFECT"),
        "TYPE" => "LIST",
        "VALUES" => $effect_type_flip,
    );
}
if($arCurrentValues["EFFECT_TYPE"] == "Rotation")
{
    $arComponentParameters["PARAMETERS"]["EFFECT"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_EFFECT"),
        "TYPE" => "LIST",
        "VALUES" => $effect_type_rotation,
    );
}
if($arCurrentValues["EFFECT_TYPE"] == "Multi-flip")
{
    $arComponentParameters["PARAMETERS"]["EFFECT"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_EFFECT"),
        "TYPE" => "LIST",
        "VALUES" => $effect_type_multi_flip,
    );
}
if($arCurrentValues["EFFECT_TYPE"] == "Cube")
{
    $arComponentParameters["PARAMETERS"]["EFFECT"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_EFFECT"),
        "TYPE" => "LIST",
        "VALUES" => $effect_type_cube,
    );
}
if($arCurrentValues["EFFECT_TYPE"] == "Unfold")
{
    $arComponentParameters["PARAMETERS"]["EFFECT"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_EFFECT"),
        "TYPE" => "LIST",
        "VALUES" => $effect_type_unfold,
    );
}
if($arCurrentValues["EFFECT_TYPE"] == "Others")
{
    $arComponentParameters["PARAMETERS"]["EFFECT"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_EFFECT"),
        "TYPE" => "LIST",
        "VALUES" => $effect_type_others,
    );
}

$arComponentParameters["PARAMETERS"]["USE_RESIZE"] = array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CODEROID_css3animations_USE_RESIZE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
            "REFRESH" => "Y"
	);

if($arCurrentValues["USE_RESIZE"] != "N")
{
    $arComponentParameters["PARAMETERS"]["PREVIEW_WIDTH"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_PREVIEW_WIDTH"),
        "TYPE" => "STRING",
        "DEFAULT" => 200,
        "PARENT" => "BASE",
    );
    $arComponentParameters["PARAMETERS"]["PREVIEW_HEIGHT"] = array(
        "NAME" => GetMessage("CODEROID_css3animations_PREVIEW_HEIGHT"),
        "TYPE" => "STRING",
        "DEFAULT" => 200,
        "PARENT" => "BASE",
    );
}

$arComponentParameters["PARAMETERS"]["COMPOSITE"] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage("CODEROID_css3animations_COMPOSITE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "N",
);