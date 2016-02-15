var adjustMenu5 = function() {
	function detectmob() { 
		if( navigator.userAgent.match(/Android/i)
			|| navigator.userAgent.match(/webOS/i)
			|| navigator.userAgent.match(/iPhone/i)
			|| navigator.userAgent.match(/iPad/i)
			|| navigator.userAgent.match(/iPod/i)
			|| navigator.userAgent.match(/BlackBerry/i)
			|| navigator.userAgent.match(/Windows Phone/i)
		){
			return true;
		}
		else {
			return false;
		}
	}
	var maxMobileWidth = parseInt(jQuery(".responsiveMenuTheme5 input.maxMobileWidth").val());
	$menu = jQuery(".responsiveMenuTheme5");
	$toggle = $menu.prev("a.toggleMenu");
	if (ww <= maxMobileWidth) {
		jQuery(".responsiveMenu5.toggleMenu").css("display", "inline-block").removeClass("isDesktop").addClass("isMobile");
		if (!jQuery(".responsiveMenu5.toggleMenu").hasClass("active")) {
			jQuery(".responsiveMenuTheme5").hide().removeClass("isDesktop").addClass("isMobile");
		} else {
			jQuery(".responsiveMenuTheme5").show().removeClass("isDesktop").addClass("isMobile");
		}
		jQuery(".responsiveMenuTheme5 li").unbind('mouseenter mouseleave');
		jQuery(".responsiveMenuTheme5 li.deeper>span.separator").addClass("parent");
		jQuery(".responsiveMenuTheme5 li.deeper a.parent, .responsiveMenuTheme5 li.deeper span.separator").unbind('click').bind('click', function(e) {
			e.preventDefault();
			jQuery(this).parent("li").toggleClass("hover");
		});
		jQuery(".responsiveMenuTheme5 li a.parent > span").click(function(){
			if((typeof jQuery(this).parent().attr("href") != 'undefined') && jQuery(this).parent().attr("href") != "#"){
					jQuery(this).parent().unbind('click');
					myLink = jQuery(this).parent().attr("href");
					window.location.href = myLink;
			}
		});
		$menu.find("a[href^='#']").click(function(){
			menuULheight = jQuery("ul.responsiveMenuTheme5").outerHeight();
			jQuery("ul.responsiveMenuTheme5").animate({marginTop:menuULheight*-1}, 450, function(){
				jQuery(".responsiveMenu5.toggleMenu").removeClass("active");
				jQuery(".responsiveMenuTheme5").prependTo(jQuery("#responsiveMenuTheme5Cnt"));
				adjustMenu5();
			});
		});
	} 
	else if (ww > maxMobileWidth) {
		jQuery("ul.responsiveMenuTheme5").hide();
		jQuery("body").css("left", "auto").css("width", "auto").css("position", "relative");
		jQuery(".responsiveMenu5.toggleMenu").removeClass("active");
		jQuery(".responsiveMenuTheme5").prependTo(jQuery("#responsiveMenuTheme5Cnt"));
		jQuery("body").css("left", "auto").css("width", "auto").css("position", "relative");
		jQuery(".responsiveMenu5.toggleMenu").css("display", "none").removeClass("isMobile").addClass("isDesktop");
		jQuery(".responsiveMenuTheme5").show().removeClass("isMobile").addClass("isDesktop");
		jQuery(".responsiveMenuTheme5 li").removeClass("hover");
		jQuery(".responsiveMenuTheme5 li a").unbind('click');
		jQuery(".responsiveMenuTheme5 li").unbind('mouseenter mouseleave').bind('mouseenter', function() {
		 	jQuery(this).addClass('hover');	
		}).bind('mouseleave', function(){
			jQuery(this).removeClass('hover');
		});
		if(detectmob()){
			jQuery(".responsiveMenuTheme5 li.deeper > a").click(function(e){
				e.preventDefault();
			})
		}
		jQuery(".responsiveMenuTheme5").prependTo(jQuery("#responsiveMenuTheme5Cnt")).show().css("marginTop",0);
		jQuery(".responsiveMenu5.toggleMenu").hide();
	}
}
var ww = jQuery(window).width();
jQuery(document).ready(function() {
	jQuery(".responsiveMenu5.toggleMenu").prependTo(jQuery("body"));
	jQuery(".responsiveMenuTheme5 li a").each(function() {
		if (jQuery(this).next().length > 0) {
			jQuery(this).addClass("parent");
		};
	})
	jQuery(".responsiveMenu5.toggleMenu").click(function(e) {
		e.preventDefault();
		menuULheight = jQuery("ul.responsiveMenuTheme5").outerHeight();
		if(!jQuery(this).hasClass("active")){
			jQuery("ul.responsiveMenuTheme5").prependTo("body").css("marginTop",menuULheight*-1).animate({marginTop:0}, 450);
			bodyWidth = jQuery("body").width();
			// jQuery("body").animate({top:"0"}, 300);
			jQuery(this).toggleClass("active");
			adjustMenu5();
		}else{
			jQuery("ul.responsiveMenuTheme5").animate({marginTop:menuULheight*-1}, 450, function(){
				jQuery(".responsiveMenu5.toggleMenu").removeClass("active");
				jQuery(".responsiveMenuTheme5").prependTo(jQuery("#responsiveMenuTheme5Cnt"));
				adjustMenu5();
			});
		}
	});
	ww = jQuery(window).width();
	adjustMenu5();
})
jQuery(window).bind('resize orientationchange', function() {
	//ww = document.body.clientWidth;
	ww = jQuery(window).width();
	adjustMenu5();
});
