<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>

<?if(!empty($arParams["ID"])):?>
<div>
<?
if($arParams["SIZE"]=='SMALL'):
	$WIDTH = '512';
	$HEIGHT = '384';
endif;

if($arParams["SIZE"]=='MIDDLE'):
	$WIDTH = '640';
	$HEIGHT = '480';
endif;

if($arParams["SIZE"]=='BIG'):
	$WIDTH = '800';
	$HEIGHT = '600';
endif;

if($arParams["SIZE"]=='OTHER'):
	$WIDTH = $arParams["WIDTH"];
	$HEIGHT = $arParams["HEIGHT"];
endif;
?>


<? if($arParams["BUT_OR_PLAY"]=='PLAYER'): ?>

		<?if($arParams["FULLSCREEN"]=='N'):
				$fullscreen = 'allowFulscreen=false';
			endif;
			
			if($arParams["ZOOM"]=='N'):
				$zoom = 'allowZoom=false';
			endif;
			
			if($arParams["ANAGLYPH"]=='N'):
				$anaglyph = 'allowAnaglyph=false';
			 endif;
			 
			if($arParams["AUTOPLAY"]=='Y'):
				$autoplay = 'autoPlay=true" ';
			endif;	
		?>
		
		<iframe src="http://media.megavisor.com/player/embed?<?=$arParams["ID"]?>#<?=$fullscreen?>&<?=$zoom?>&<?=$anaglyph?>&<?=$autoplay?>" 
		width="<?=$WIDTH?>"
		height="<?=$HEIGHT?>" 
		<?=$fullscreen?> 
		frameborder="0" 
		webkitAllowFullscreen allowFullscreen></iframe>
<? else: ?>

		<?if($arParams["FULLSCREEN2"]=='N'):
				$fullscreen = 'data-player-allow-fullscreen="false"';
			endif;
			
			if($arParams["ZOOM2"]=='N'):
				$zoom = 'data-player-allow-zoom="false"';
			endif;
			
			if($arParams["ANAGLYPH2"]=='N'):
				$anaglyph = 'data-player-allow-anaglyph="false"';
			 endif;
		?>

		<span class="megavisor-button" 
		data-uuid="<?=$arParams["ID"]?>" 
		data-type="1" 
		data-sub-type="1" 
		data-style="<?=$arParams["DESIGN"]?>" 
		data-player-width="<?=$WIDTH?>" 
		data-player-height="<?=$HEIGHT?>" 
		data-text="<?=$arParams["BUTTON_TEXT"]?>" 
		<?=$anaglyph?>
		<?=$fullscreen?>
		<?=$zoom?> 
		data-popup-background-color="<?=$arParams["BACKGROUND_COLOR"]?>" 
		data-popup-background-opacity="<?=$arParams["OPACITY_BACKGROUND"]?>" 
		data-popup-shadow-color="<?=$arParams["SHADOW_COLOR"]?>" 
		data-popup-shadow-opacity="<?=$arParams["OPACITY_SHADOW"]?>" 
		data-popup-border-color="<?=$arParams["BORDER_COLOR"]?>" 
		data-popup-border-opacity="<?=$arParams["OPACITY_BORDER"]?>" 
		data-popup-border-weight="<?=$arParams["BORDER"]?>"></span><script type="text/javascript">if(!window.Megavisor){window.document.write('<scr'+'ipt type="text/javascript" src="http://media.megavisor.com/player/api.js"></sc'+'ript>');}</script>
<?endif;?>
</div>
<?endif;?>