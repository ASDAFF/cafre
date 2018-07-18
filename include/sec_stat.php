<ul class="left_menu">

					<?
					$arFilter = array('IBLOCK_ID' => 9); // выберет потомков без учета активности
				   $rsSect = CIBlockSection::GetList(array('sort' => 'asc'),$arFilter);
				   while ($arSect = $rsSect->GetNext())
				   {
					?>
					<li class="item <?if($APPLICATION->GetCurPage() == $arSect["SECTION_PAGE_URL"]):?>active-sec<?endif;?>">
					<a href="<?=$arSect["SECTION_PAGE_URL"];?>">
					<span><?=$arSect["NAME"];?></span>
					</a>
					</li>
					<?
					}
					?>
</ul>