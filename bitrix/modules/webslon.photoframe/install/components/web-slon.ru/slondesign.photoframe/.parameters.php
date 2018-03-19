<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$curSize = getimagesize($_SERVER["DOCUMENT_ROOT"].$arCurrentValues["IMAGE_URL"]);
$imageWidthText = "";
$imageHeightText = "";
if(is_array($curSize)){
	$imageWidthText = " (".GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_ORIGINAL")." ".$curSize[0]."px)";
	$imageHeightText = " (".GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_ORIGINAL")." ".$curSize[1]."px)";
}

//print_r($arCurrentValues);

$arGroups = array(
	"IMAGE_SETTINGS" => array(
	 "NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_SETTINGS_GRP"),
	 "SORT" => 100,
	),	  
	"FRAME_SETTINGS" => array(
	 "NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_FRAME_SETTINGS_GRP"),
	 "SORT" => 200,
	), 
	"FANCYBOX_SETTINGS" => array(
	 "NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_FANCYBOX_SETTINGS_GRP"),
	 "SORT" => 300,
	),		   
	"3D_SETTINGS" => array(
	 "NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_3D_SETTINGS_GRP"),
	 "SORT" => 400,
	),		   

);

$arComponentParameters = array(
	"GROUPS" => $arGroups,
    "PARAMETERS" => array(
    	"IMAGE_URL" => Array(
    		"PARENT" => "IMAGE_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_URL"),
			"TYPE" => "FILE", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"FD_USE_MEDIALIB" => true, // ���������� ���������������
			"FD_TARGET" => "F",
			"FD_EXT" => "",
			"FD_UPLOAD" => true,
			"FD_USE_MEDIALIB" => true,
			"FD_MEDIALIB_TYPES" => Array("image"),
			"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
			"REFRESH" => "Y",
		),
    	"IMAGE_WIDTH" => Array(
    		"PARENT" => "IMAGE_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_WIDTH").$imageWidthText,
			"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
			"COLS" => 5,
		),
    	"IMAGE_HEIGHT" => Array(
    		"PARENT" => "IMAGE_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_HEIGHT").$imageHeightText,
			"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
			"COLS" => 5,	
		),
    	"IMAGE_ALT" => Array(
    		"PARENT" => "IMAGE_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_ALT"),
			"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N		
		),
    	"IMAGE_TITLE" => Array(
    		"PARENT" => "IMAGE_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_TITLE"),
			"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N		
		),
    	"CLEARFIX" => Array(
    		"PARENT" => "IMAGE_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_CLEARFIX"),
			"TYPE" => "CHECKBOX", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "Y", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N	
			"REFRESH" => "Y",
		),		
    	"USE_DYNAMIC_FRAME" => Array(
    		"PARENT" => "FRAME_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_USE_DYNAMIC_FRAME"),
			"TYPE" => "CHECKBOX", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "Y", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N	
			"REFRESH" => "Y",	
		),										
    	"USE_FANCYBOX" => Array(
    		"PARENT" => "FANCYBOX_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_USE_FANCYBOX"),
			"TYPE" => "CHECKBOX", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "N", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N	
			"REFRESH" => "Y",		
		),							
    	"USE_3D" => Array(
    		"PARENT" => "3D_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_USE_3D"),
			"TYPE" => "CHECKBOX", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"DEFAULT" => "N", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N	
			"REFRESH" => "Y",		
		),									
        "CACHE_TIME"  =>  array("DEFAULT"=>3600),
    ),
);

if($arCurrentValues["USE_FANCYBOX"] == "Y"){
	$arComponentParameters["PARAMETERS"]["FANCYBOX_TITLE"] =  Array(
		"PARENT" => "FANCYBOX_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_FANCYBOX_TITLE"),
		"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N	
		"COLS" => 50,
		"ROWS" => 2,		
	);	
	$arComponentParameters["PARAMETERS"]["LOAD_FANCYBOX"] =  Array(
		"PARENT" => "FANCYBOX_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_LOAD_FANCYBOX"),
		"TYPE" => "CHECKBOX", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "N", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N			
	);
	$arComponentParameters["PARAMETERS"]["LOAD_JQUERY"] =  Array(
		"PARENT" => "FANCYBOX_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_LOAD_JQUERY"),
		"TYPE" => "CHECKBOX", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "N", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N			
	);	
	
}

if($arCurrentValues["USE_3D"] == "Y"){
	$defaultImagesCount = "20";
	if(isset($arCurrentValues["3D_IMAGES_COUNT"])){
		$arCurrentValues["3D_IMAGES_COUNT"] = intval($arCurrentValues["3D_IMAGES_COUNT"]);
		$curImagesCount = $arCurrentValues["3D_IMAGES_COUNT"];
	}else{
		$curImagesCount = $defaultImagesCount;
	}
	$arComponentParameters["PARAMETERS"]["3D_IMAGES_COUNT"] =  Array(
		"PARENT" => "3D_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_3D_IMAGES_COUNT"),
		"TYPE" => "TEXT", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => $defaultImagesCount, // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
		"COLS" => 10,
		"ROWS" => 1,		
		"REFRESH" => "Y",
	);	
	
	for($i = 0; $i < $curImagesCount; $i++){
		$arComponentParameters["PARAMETERS"]["3D_IMAGES_URL_".$i] =  Array(
			"PARENT" => "3D_SETTINGS",
			"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_URL").' #'.($i+1),
			"TYPE" => "FILE", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
			"FD_USE_MEDIALIB" => true, // ���������� ���������������
			"FD_TARGET" => "F",
			"FD_EXT" => "",
			"FD_UPLOAD" => true,
			"FD_USE_MEDIALIB" => true,
			"FD_MEDIALIB_TYPES" => Array("image"),
			"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
		);	
	};
}

if($arCurrentValues["USE_DYNAMIC_FRAME"] != "Y"){
	$arComponentParameters["PARAMETERS"]["FRAME_URL"] = Array(
		"PARENT" => "FRAME_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_URL"),
		"TYPE" => "FILE", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"FD_USE_MEDIALIB" => true, // ���������� ���������������
		"FD_TARGET" => "F",
		"FD_EXT" => "",
		"FD_UPLOAD" => true,
		"FD_USE_MEDIALIB" => true,
		"FD_MEDIALIB_TYPES" => Array("image"),
		"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
	);
    $arComponentParameters["PARAMETERS"]["FRAME_WIDTH"] = Array(
		"PARENT" => "FRAME_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_WIDTH"),
		"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "406", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
		"COLS" => 5,
	);
    $arComponentParameters["PARAMETERS"]["FRAME_HEIGHT"] = Array(
		"PARENT" => "FRAME_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_IMAGE_HEIGHT"),
		"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "260", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
		"COLS" => 5,			
	);
}else{
	$arComponentParameters["PARAMETERS"]["DYNAMIC_FRAME_SHADOW_URL"] = Array(
		"PARENT" => "FRAME_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_DYNAMIC_FRAME_SHADOW_URL"),
		"TYPE" => "FILE", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"FD_USE_MEDIALIB" => true, // ���������� ���������������
		"FD_TARGET" => "F",
		"FD_EXT" => "",
		"FD_UPLOAD" => true,
		"FD_USE_MEDIALIB" => true,
		"FD_MEDIALIB_TYPES" => Array("image"),
		"DEFAULT" => "", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
	);	
	$arComponentParameters["PARAMETERS"]["DYNAMIC_FRAME_BORDER_WIDTH"] = Array(
		"PARENT" => "FRAME_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_DYNAMIC_FRAME_BORDER_WIDTH"),
		"TYPE" => "STRING", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "1", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
		"COLS" => 5,
	);
	$arComponentParameters["PARAMETERS"]["DYNAMIC_FRAME_BORDER_COLOR"] = Array(
		"PARENT" => "FRAME_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_DYNAMIC_FRAME_BORDER_COLOR"),
		"TYPE" => "COLORPICKER", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "#A6A5A5", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
		"COLS" => 7,
	);	
	$arComponentParameters["PARAMETERS"]["DYNAMIC_FRAME_BORDER_STYLE"] = Array(
		"PARENT" => "FRAME_SETTINGS",
		"NAME" => GetMessage("SLONDESIGN_PHOTOFRAME_PARAMS_DYNAMIC_FRAME_BORDER_STYLE"),
		"TYPE" => "LIST", // ����� ������� ����������� ��� �������� ��� ���������, �������-�� ��� ��� ������� (STRING, CHECKBOX � etc.)
		"DEFAULT" => "solid", // �������� �� ���������, ���� �� ��� CHECKBOX �������� Y ��� N
		"VALUES" => array(
			"none"=>"none",
			"hidden"=>"hidden",
			"dotted"=>"dotted",
			"dashed"=>"dashed",
			"solid"=>"solid",
			"double"=>"double",
			"groove"=>"groove",
			"ridge"=>"ridge",
			"inset"=>"inset",
			"outset"=>"outset",
		),
	);		
	
}
?>