<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true); // композит
?>

<?if($arResult["DYNAMIC_FRAME_SHADOW_URL"]!=""):?> 
	<style>
		div.slondesign-photoframe-dynamic-image-wrapper:after {
			background: url("<?=$arResult["DYNAMIC_FRAME_SHADOW_URL"]?>") no-repeat 50% 100% !important;
		}
	</style>
<?endif;?>
<?if($arResult["DYNAMIC_FRAME_BORDER_WIDTH"]!=""):?> 
	<style>
		div.slondesign-photoframe-dynamic-image-wrapper:after {
			bottom: <?=-13-$arResult["DYNAMIC_FRAME_BORDER_WIDTH"]?>px;
		}
	</style>
<?endif;?>

<?if($arResult["USE_3D"] == "Y"){
	$containerClass = " webslon-3d-image-container";
}else{
	$containerClass = "";
}
?>

<?if($arResult["USE_DYNAMIC_FRAME"]=="Y"):?> 
	<span class="slondesign-photoframe-dynamic-image-wrapper<?=$containerClass?>" style="<?=$arResult["FRAME_STYLE"]?>">
<?else:?>
	<span class="slondesign-photoframe-image-container" style="<?=$arResult["FRAME_STYLE"]?>"> 	 
		<div class="slondesign-photoframe-image-wrapper<?=$containerClass?>">
<?endif;?>
			
			<?if($arResult["USE_3D"]=="Y"):?><ul><?endif;?>
				<?if($arResult["USE_3D"]=="Y"):?><li><?endif;?>
					<?if($arResult["USE_FANCYBOX"]=="Y"):?>
						<a class="fancybox" href="<?=$arResult["IMAGE_URL"]?>" caption="<?=$arResult["FANCYBOX_TITLE"]?>">     
					<?endif;?>
						<img border="0" src="<?=$arResult["IMAGE_URL"]?>" width="<?=$arResult["IMAGE_WIDTH"]?>" height="<?=$arResult["IMAGE_HEIGHT"]?>" alt="<?=$arResult["IMAGE_ALT"]?>" title="<?=$arResult["IMAGE_TITLE"]?>"/>
					<?if($arParams["USE_FANCYBOX"]=="Y"):?>
						</a>
					<?endif;?>						
				<?if($arResult["USE_3D"]=="Y"):?></li><?endif;?>
				
				<?if($arResult["USE_3D"]=="Y"):?>
					<?for($i = 0; $i < $arResult["3D_IMAGES_COUNT"]; $i++):?>
						<li>
							<?if($arParams["USE_FANCYBOX"]=="Y"):?>
								<a class="fancybox" href="<?=$arResult["3D_IMAGES_URL_".$i]?>" caption="<?=$arResult["FANCYBOX_TITLE"]?>">     
							<?endif;?>
								<img border="0" src="<?=$arResult["3D_IMAGES_URL_".$i]?>" width="<?=$arResult["IMAGE_WIDTH"]?>" height="<?=$arResult["IMAGE_HEIGHT"]?>" alt="<?=$arResult["IMAGE_ALT"]?>" title="<?=$arResult["IMAGE_TITLE"]?>"/>
							<?if($arParams["USE_FANCYBOX"]=="Y"):?>
								</a>
							<?endif;?>
						</li>	
					<?endfor;?>	
				<?endif;?>
			<?if($arResult["USE_3D"]=="Y"):?></ul><?endif;?>
			
				
<?if($arResult["USE_DYNAMIC_FRAME"]=="Y"):?>					
	</span>
<?else:?>
		</div>
	</span>
<?endif;?>


<?if($arResult["CLEARFIX"]=="Y"):?> 
	<div class="webslon-clearfix"></div>
<?endif;?>