<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if( !empty( $arParams["VK"] ) ){?>
	<a href="<?=$arParams["VK"]?>" target="_blank" >
		<img border="0" src="/bitrix/components/aspro/social.info.mshop/images/vk.png" alt="<?=GetMessage("VKONTAKTE")?>" title="<?=GetMessage("VKONTAKTE")?>" />
	</a>
<?}?>
<?if( !empty( $arParams["ODN"] ) ){?>
	<a href="<?=$arParams["ODN"]?>" target="_blank" >
		<img border="0" src="/bitrix/components/aspro/social.info.mshop/images/odn.png" alt="<?=GetMessage("ODN")?>" title="<?=GetMessage("ODN")?>" />
	</a>
<?}?>
<?if( !empty( $arParams["FACE"] ) ){?>
	<a href="<?=$arParams["FACE"]?>" target="_blank">
		<img border="0" src="/bitrix/components/aspro/social.info.mshop/images/facebook.png" alt="<?=GetMessage("FACEBOOK")?>" title="<?=GetMessage("FACEBOOK")?>" />
	</a>
<?}?>
<?if( !empty( $arParams["TWIT"] ) ){?>
	<a href="<?=$arParams["TWIT"]?>" target="_blank">
		<img border="0" src="/bitrix/components/aspro/social.info.mshop/images/twitter.png" alt="<?=GetMessage("TWITTER")?>" title="<?=GetMessage("TWITTER")?>" /> 
	</a>
<?}?>
<?if( !empty( $arParams["INST"] ) ){?>
	<a href="<?=$arParams["INST"]?>" target="_blank" >
		<img border="0" src="/bitrix/components/aspro/social.info.mshop/images/inst.png" alt="<?=GetMessage("INST")?>" title="<?=GetMessage("INST")?>" />
	</a>
<?}?>
<?if( !empty( $arParams["MAIL"] ) ){?>
	<a href="<?=$arParams["MAIL"]?>" target="_blank" >
		<img border="0" src="/bitrix/components/aspro/social.info.mshop/images/mail.png" alt="<?=GetMessage("MAIL")?>" title="<?=GetMessage("MAIL")?>" />
	</a>
<?}?>