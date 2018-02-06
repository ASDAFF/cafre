<?
AddEventHandler('catalog', 'OnSuccessCatalogImport1C', array("AliasImport","CustomCatalogImportStep"));
class AliasImport
{
    public static function CustomCatalogImportStep()
    {

        $stepInterval = (int) COption::GetOptionString("catalog", "1C_INTERVAL", "-");
        $startTime = time();
        // ���� ������� ����� �������� �����������
        $isImport = strpos($_REQUEST['filename'], 'import') !== false;

        global $DB;
        global $USER;
        global $APPLICATION;
        $file = $_SERVER['DOCUMENT_ROOT'].'/1clog.txt';


        if($isImport)
        {
            CModule::IncludeModule("iblock");
            CModule::IncludeModule("catalog");
           /* file_put_contents($file, '===================================================
            ');*/
            $oSec = new CIBlockSection();
            $oEl = new CIBlockElement;
            $xmlFile = new CIBlockXMLFile();

            $catalogNodeDB = $xmlFile->GetList(array(),array("NAME"=>"�������"));

            $catalogNode = $catalogNodeDB->fetch();

            if($catalogNode)
            {
                $idNodeDB = $xmlFile->GetList(array(),array("PARENT_ID"=>$catalogNode["ID"],"NAME"=>"��"));
                $idNode = $idNodeDB->fetch();
                $catalogXmlID = $idNode["VALUE"];
                if(!empty($catalogXmlID))
                {
                    $ibDB = CIBlock::GetList(false,array("XML_ID"=>$catalogXmlID));
                    if($ib = $ibDB -> fetch())
                        $IBLOCK_ID = $ib["ID"];
                }
            }
            /*file_put_contents($file, 'IBLOCK_ID '.$IBLOCK_ID.'
            ', FILE_APPEND);*/
            if($IBLOCK_ID > 0 )
            {
                $rs = $xmlFile->GetList(array(),array("NAME"=>"�����"));
                while($e = $rs->fetch())
                {
                    $arr = $xmlFile->GetAllChildrenArray($e["ID"]);
                    $elementXmlID = $arr["��"];
                    $resEl = CIBlockElement::getList(array(),array('XML_ID'=>$elementXmlID, 'IBLOCK_ID' => $IBLOCK_ID),false, array('nTopCount' => 1));
                    $arEl=$resEl->fetch();
                    if(!empty($arEl) )
                    {
                        $rekvizit_block = $xmlFile->GetList(array(),array("PARENT_ID"=>$e["ID"],"NAME"=>"������������������"))->fetch();
                        $rekvizits = $xmlFile->GetList(array(),array("PARENT_ID"=>$rekvizit_block["ID"],"NAME"=>"�����������������"));
                        $arSects = array();
                        $ChangeProps = array();
                        $ChangeFields = array();
                        $ChangeProductFields = array();
                        while($arRekvizit  = $rekvizits->fetch())
                        {
                           $arRekvs = $xmlFile->GetAllChildrenArray($arRekvizit["ID"]);
                            if($arRekvs['������������'] == '�������������')
                            {
                                $resSect = CIBlockSection::GetList(array(),array('IBLOCK_ID' => $IBLOCK_ID, 'XML_ID' => $arRekvs['��������']));
                                if($section = $resSect -> fetch()){
                                    $arSects[] = $section['ID'];

                                }
                            }
                            if($arRekvs['������������'] == '�����')
                            {
                                $ChangeProps['BRAND'] = $arRekvs['��������'];
                            }
                            if($arRekvs['������������'] == '�������� ������')
                            {
                                $ChangeFields['DETAIL_TEXT'] = html_entity_decode($arRekvs['��������']);
                                $ChangeFields["DETAIL_TEXT_TYPE"] = "html";
                            }
                            if($arRekvs['������������'] == '�������� �������')
                            {
                                $ChangeFields['PREVIEW_TEXT'] = html_entity_decode($arRekvs['��������']);
                                $ChangeFields["PREVIEW_TEXT_TYPE"] = "html";
                            }
                            if($arRekvs['������������'] == '���������')
                            {
                                $ChangeProductFields['WEIGHT'] = trim($arRekvs['��������']);

                            }



                        }

                       if(!empty($arSects)) CIBlockElement::SetElementSection($arEl['ID'], $arSects);
                       if(!empty($ChangeProps)) CIBlockElement::SetPropertyValuesEx($arEl['ID'], $IBLOCK_ID, $ChangeProps);
                       if(!empty($ChangeFields)) $oEl->Update($arEl['ID'],  $ChangeFields);
                       if(!empty($ChangeProductFields)) CCatalogProduct::Update($arEl['ID'], $ChangeProductFields);
                       
                    }
                }
            }


        }

      }
}
?>