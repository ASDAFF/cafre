<script type="text/javascript">
$(document).ready(function(){
	if($('.top_slider_wrapp .flexslider').length){
		var config = {"controlNav": true, "animationLoop": true, "pauseOnHover" : true};
		if(typeof(arMShopOptions['THEME']) != 'undefined'){
			var slideshowSpeed = Math.abs(parseInt(arMShopOptions['THEME']['BANNER_SLIDESSHOWSPEED']));
			var animationSpeed = Math.abs(parseInt(arMShopOptions['THEME']['BANNER_ANIMATIONSPEED']));
			config["directionNav"] = (arMShopOptions['THEME']['BANNER_WIDTH'] == 'narrow' ? false : true);
			config["slideshow"] = (slideshowSpeed && arMShopOptions['THEME']['BANNER_ANIMATIONTYPE'].length ? true : false);
			config["animation"] = (arMShopOptions['THEME']['BANNER_ANIMATIONTYPE'] === 'FADE' ? 'fade' : 'slide');
			if(animationSpeed >= 0){
				config["animationSpeed"] = animationSpeed;
			}
			if(slideshowSpeed >= 0){
				config["slideshowSpeed"] = slideshowSpeed;
			}
			if(arMShopOptions['THEME']['BANNER_ANIMATIONTYPE'] !== 'FADE'){
				config["direction"] = (arMShopOptions['THEME']['BANNER_ANIMATIONTYPE'] === 'SLIDE_VERTICAL' ? 'vertical' : 'horizontal');
			}
			config.start = function(slider){
				if(slider.count <= 1){
					slider.find('.flex-direction-nav li').addClass('flex-disabled');
				}
			}
		}

		$(".top_slider_wrapp .flexslider").flexslider(config);
	}
});
</script>