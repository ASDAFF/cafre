<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!is_array($arResult["arMap"]) || count($arResult["arMap"]) < 1)
    return;

    //echo "<pre>"; print_r($arResult["arMapStruct"]); echo "</pre>" ;
    foreach ($arResult["arMapStruct"] as $arNewResult):?>
    <div class="level-block">
        <div class="level0-block">
            <a href="<?=$arNewResult["FULL_PATH"]?>"> <?=$arNewResult["NAME"]?></a>
        </div>
        <?foreach ($arNewResult["CHILDREN"] as $arChild):?>
        <div class="level2-block">
            <a href="<?=$arChild["FULL_PATH"]?>"><?=$arChild["NAME"]?></a>
        </div>
        <?endforeach;?>
    </div>
    <?endforeach;
    