<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult["ITEMS"]){?>
	<div class="news_akc_block news">
		<div class="top_block">
			<?
			$title_block=($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('AKC_TITLE'));
			$url=($arParams["ALL_URL"] ? $arParams["ALL_URL"] : "sale/");
			$count=ceil(count($arResult["ITEMS"])/4);
			?>
			<div class="title_block"><?=$title_block;?></div>
			<a href="<?=SITE_DIR.$url;?>"><?=GetMessage('ALL_AKC')?></a>
		</div>
		<div class="news_slider_navigation slider_navigation top"></div>
		<div class="news_slider_wrapp">
			<ul class="news_slider wr">
				<?foreach($arResult["ITEMS"] as $arItem){
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					$img_source='';
					?>
					<li id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="item">
						<?if($arItem["DETAIL_PICTURE"]){
							$img_source=$arItem["DETAIL_PICTURE"];
						}elseif($arItem["PREVIEW_PICTURE"]){
							$img_source=$arItem["PREVIEW_PICTURE"];
						}?>
						<?if($img_source){?>
							<div class="img">
								<?$img = CFile::ResizeImageGet($img_source, array("width" => 268, "height" => 166), BX_RESIZE_IMAGE_EXACT, true, false, false, 80 );?>
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
									<img border="0" src="<?=$img["src"]?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" />
								</a>
							</div>
						<?}?>
						<div class="info">
							<div class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
							<a class="name" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
							<div class="preview"><?=$arItem['PREVIEW_TEXT'];?></div>
						</div>
					</li>
				<?}?>
			</ul>
		</div>
	</div>
	<script>
		var timeoutSlide;
		InitFlexSliderNews = function() {
			var flexsliderItemWidth = 268,
				flexsliderItemMargin = 20;
			$(".news_slider_wrapp").flexslider({
				animation: "slide",
				selector: ".news_slider > li",
				slideshow: false,
				slideshowSpeed: 6000,
				animationSpeed: 600,
				directionNav: true,
				//controlNav: false,
				pauseOnHover: true,
				animationLoop: true, 
				controlsContainer: ".news_slider_navigation",
				itemWidth: flexsliderItemWidth,
				itemMargin: flexsliderItemMargin, 
				// manualControls: ".block_wr .flex-control-nav.flex-control-paging li a"
				start:function(){
					$('.news_slider_wrapp .flex-viewport .news_slider').equalize({children: '.item .info'});
					$('.flex-control-nav li a').on('touchend', function(){
						$(this).addClass('touch');
					});
					$('.news_slider_wrapp li').css('opacity', 1);
				}
			});
			$('.stores').equalize({children: '.wrapp_block', reset: true});
		}
		$(document).ready(function(){
			$(window).resize(function(){
				clearTimeout(timeoutSlide);
				timeoutSlide = setTimeout(InitFlexSliderNews(),50);
			})
		});
		/*$(document).ready(function(){
			var flexsliderItemWidth = 268,
				flexsliderItemMargin = 20;
			$(".news_slider_wrapp").flexslider({
				animation: "slide",
				selector: ".news_slider > li",
				slideshow: false,
				slideshowSpeed: 6000,
				animationSpeed: 600,
				directionNav: true,
				//controlNav: true,
				pauseOnHover: true,
				animationLoop: true, 
				controlsContainer: ".news_slider_navigation",
				itemWidth: flexsliderItemWidth,
				itemMargin: flexsliderItemMargin, 
				manualControls: ".news_akc_block .flex-control-nav.flex-control-paging li a",
				start: function(){
					$('.news_slider_wrapp .flex-viewport .news_slider').equalize({children: '.item .info'});
				}
			});
			$(window).resize(function(){
				//$('.news_slider_wrapp .flex-viewport .news_slider').equalize({children: '.item'});
			})
		})*/
	</script>
<?}?>