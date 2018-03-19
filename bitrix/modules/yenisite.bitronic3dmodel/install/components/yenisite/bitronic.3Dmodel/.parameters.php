<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	
$arComponentParameters = array(

	"GROUPS" => array(
		"PLAYER_PARAM" => array(
			"NAME" => GetMessage("PLAYER_PARAM")
		),
		
		"BUTTON_PARAM" => array(
			"NAME" => GetMessage("BUTTON_PARAM")
		),
	),

	"PARAMETERS" => array(
	
	"BUT_OR_PLAY" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("BUT_OR_PLAY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
			"PLAYER" => GetMessage("PLAYER"),
			"BUTTON" => GetMessage("BUTTON"),
			),
			"DEFAULT" => "PLAYER",
		),
	
	"ID" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "5fb1fa73-4ad6-4a8b-b4a6-fff1dd69f463",
		),
	
		"FULLSCREEN" =>array(
			"PARENT" => "PLAYER_PARAM",
			"NAME" => GetMessage("FULLSCREEN"),
			"TYPE" => "CHECKBOX",
		),
		
		"ZOOM" =>array(
			"PARENT" => "PLAYER_PARAM",
			"NAME" => GetMessage("ZOOM"),
			"TYPE" => "CHECKBOX",
		),
		
		"ANAGLYPH" =>array(
			"PARENT" => "PLAYER_PARAM",
			"NAME" => GetMessage("ANAGLYPH"),
			"TYPE" => "CHECKBOX",
		),
		
		"AUTOPLAY" =>array(
			"PARENT" => "PLAYER_PARAM",
			"NAME" => GetMessage("AUTOPLAY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		
		"SIZE" =>array(
			"PARENT" => "PLAYER_PARAM",
			"NAME" => GetMessage("SIZE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
			"SMALL" => GetMessage("SMALL"),
			"MIDDLE" => GetMessage("MIDDLE"),
			"BIG" => GetMessage("BIG"),
			),
			"DEFAULT" => "MIDDLE",
		),
		
		"HEIGHT" =>array(
			"PARENT" => "PLAYER_PARAM",
			"NAME" => GetMessage("HEIGHT"),
			"TYPE" => "STRING",
		),
		
		"WIDTH" =>array(
			"PARENT" => "PLAYER_PARAM",
			"NAME" => GetMessage("WIDTH"),
			"TYPE" => "STRING",
		),
		
		"DESIGN" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("DESIGN"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
			"1" => GetMessage("GREEN"),
			"2" => GetMessage("ORANGE"),
			"3" => GetMessage("GRAY"),
			"4" => GetMessage("BLUE"),
			),
			"DEFAULT" => "MIDDLE",
		),
		
		"BUTTON_TEXT" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("BUTTON_TEXT"),
			"TYPE" => "STRING",
		),
		
		"FULLSCREEN2" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("FULLSCREEN"),
			"TYPE" => "CHECKBOX",
		),
		
		"ZOOM2" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("ZOOM"),
			"TYPE" => "CHECKBOX",
		),
		
		"ANAGLYPH2" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("ANAGLYPH"),
			"TYPE" => "CHECKBOX",
		),
		
		"SIZE" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("SIZE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
			"OTHER" => GetMessage("OTHER"),
			"SMALL" => GetMessage("SMALL"),
			"MIDDLE" => GetMessage("MIDDLE"),
			"BIG" => GetMessage("BIG"),
			),
			"DEFAULT" => "MIDDLE",
		),
		
		"HEIGHT" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("HEIGHT"),
			"TYPE" => "STRING",
		),
		
		"WIDTH" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("WIDTH"),
			"TYPE" => "STRING",
		),
		
		"BORDER" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("BORDER"),
			"TYPE" => "STRING",
		),
		
		"BORDER_COLOR" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("BORDER_COLOR"),
			"TYPE" => "COLORPICKER",
		),
		
		"BACKGROUND_COLOR" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("BACKGROUND_COLOR"),
			"TYPE" => "COLORPICKER",
		),

		"SHADOW_COLOR" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("SHADOW_COLOR"),
			"TYPE" => "COLORPICKER",
		),		
		
		"OPACITY_BORDER" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("OPACITY_BORDER"),
			"TYPE" => "STRING",
		),
		
		"OPACITY_BACKGROUND" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("OPACITY_BACKGROUND"),
			"TYPE" => "STRING",
		),

		"OPACITY_SHADOW" =>array(
			"PARENT" => "BUTTON_PARAM",
			"NAME" => GetMessage("OPACITY_SHADOW"),
			"TYPE" => "STRING",
		),			
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>360000),
	),

);
?>