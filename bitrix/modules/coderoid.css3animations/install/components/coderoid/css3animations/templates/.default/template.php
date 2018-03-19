<? if($arParams["COMPOSITE"] == "Y"): $this->setFrameMode(true); endif; ?>
		
<link rel="stylesheet" type="text/css" href="/bitrix/components/coderoid/css3animations/include/css/style.css" />
<link rel="stylesheet" type="text/css" href="/bitrix/components/coderoid/css3animations/include/css/<?=$arParams["EFFECT_TYPE"];?>.css" />
<script type="text/javascript" src="/bitrix/components/coderoid/css3animations/include/js/modernizr.custom.js"></script>


			<div class="te-container">
				<div class="te-controls">
					<select id="type" style="display:none;">
					<option value="<?=$arParams["EFFECT"];?>"></option>
					</select>
					<a id="te-next" href="#" class="te-next">next</a>
					<div class="te-shadow"></div>
				</div>
				<div id="te-wrapper" class="te-wrapper">
					<div class="te-images">
						<? foreach($arResult["ITEMS"] as $arItem): ?>
							<?
							$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
							$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
							$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
							?>
                        <img src="<?=$arItem["SELECT_FROM"]["SRC"];?>" alt="<?=$arItem["NAME"];?>">
						<? endforeach; ?>
						
					</div>
					<div class="te-cover">
						<img src="<?=$arResult["ITEMS"][0]["SELECT_FROM"]["SRC"];?>"/>
					</div>
					<? if($arParams["EFFECT_TYPE"] == "Flip")  { 
					?>
						<div class="te-transition">
							<div class="te-card">
								<div class="te-front"></div>
								<div class="te-back"></div>
							</div>
						</div>
					<?
															} ?>
					<? if($arParams["EFFECT_TYPE"] == "Rotation")  { 
					?>
						<div class="te-transition">
								<div class="te-front"><img src="<?=$arResult["ITEMS"][1]["SELECT_FROM"]["SRC"];?>"/></div>
								<div class="te-back"><img src="<?=$arResult["ITEMS"][0]["SELECT_FROM"]["SRC"];?>"/></div>
						</div>
					<?
															} ?>
					<? if($arParams["EFFECT_TYPE"] == "Cube")  { 
					?>
						<div class="te-transition <?=$arParams["EFFECT"];?>">
							<div class="te-cube-front te-cube-face te-front"></div>
							<div class="te-cube-top te-cube-face te-back"></div>
							<div class="te-cube-bottom te-cube-face te-back"></div>
							<div class="te-cube-right te-cube-face te-back"></div>
							<div class="te-cube-left te-cube-face te-back"></div>
						</div>
					<?
															} ?>
					<? if($arParams["EFFECT_TYPE"] == "Unfold")  { 
					?>
						<div class="te-transition <?=$arParams["EFFECT"];?>">
							<div class="te-front te-front1"></div>
							<div class="te-front te-front2"></div>
							<div class="te-front te-front3"></div>
							<div class="te-back te-back1"></div>
							<div class="te-back te-back2"></div>
							<div class="te-back te-back3"></div>
						</div>
					<?
															} ?>
					<? if($arParams["EFFECT_TYPE"] == "Others")  { 
					?>
						<div class="te-transition <?=$arParams["EFFECT"];?>">
								<div class="te-front"></div>
								<div class="te-back"></div>
						</div>
					<?
															} ?>
					<? if($arParams["EFFECT_TYPE"] == "Multi-flip")  { 
					?>
						<div class="te-transition <?=$arParams["EFFECT"];?>">
						
						<? 
						$dat = 0;
							foreach($arResult["ITEMS"] as $arItem): ?>
							<div class="te-card te-flip<? echo strval(++$dat);?>">
								<div class="te-front"></div>
								<div class="te-back"></div>
							</div>
						<? endforeach; ?>
						</div>
					<?
															} ?>
				</div>
			</div>	

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="/bitrix/components/coderoid/css3animations/include/js/jquery.transitions.js"></script>
		<script type="text/javascript" src="/bitrix/components/coderoid/css3animations/include/js/data.js"></script>
