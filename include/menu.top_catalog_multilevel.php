<?
$APPLICATION->SetAdditionalCSS("/include/new_menu.css");
$APPLICATION->IncludeComponent("lets:catalog.section.list","top_main",
Array(
				        "ACTIVE_SUBSECTION" => "N",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "CACHE_GROUPS" => "N",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "COUNT_ELEMENTS" => "N",
                        "IBLOCK_ID" => "26",
                        "IBLOCK_TYPE" => "new_cat",
                        "SECTION" => "#SECTION_CODE_PATH#/",
                        "SECTION_CODE" => "",
                        "SECTION_FIELDS" => array(
                            0 => "UF_BRAND_ID",
                            1 => "",
                        ),
                        "SECTION_ID_EXCEP" => "5338",
                        "SECTION_URL" => "/catalog/#SECTION_CODE_PATH#/",
                        "SECTION_USER_FIELDS" => array(
                            0 => "",
                            1 => "",
                        ),
                        "SEF_FOLDER" => "/catalog/",
                        "SEF_MODE" => "N",
                        "SELF_FOLDER" => "/catalog/",
                        "TOP_DEPTH" => "3"
    )		
);
?>
