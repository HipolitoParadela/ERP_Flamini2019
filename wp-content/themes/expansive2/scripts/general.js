jQuery(document).ready(function(){
	/* OPAQUE HEADER & PARALLAX */
	cpotheme_update_sticky_header();
	var slider_height = jQuery('.slider-slides').height();
	var fadeUntil = slider_height, paddingFactor = slider_height / 3;
	jQuery(window).bind('scroll', function(){
		cpotheme_update_sticky_header();
		var offset = jQuery(document).scrollTop(), padding = 0;
		if(offset <= fadeUntil){
			padding = paddingFactor * offset / fadeUntil;
			jQuery('.slide').css('background-position', 'center ' + padding + 'px');
		}
	});
});

function cpotheme_update_sticky_header(){
    if(jQuery(document).scrollTop() > 0)
		jQuery('.header').addClass('header-sticky-active');
	else
		jQuery('.header').removeClass('header-sticky-active');
}
