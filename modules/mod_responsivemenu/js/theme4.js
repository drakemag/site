var adjustMenu4 = function() {
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
	var maxMobileWidth = parseInt(jQuery(".responsiveMenuTheme4 input.maxMobileWidth").val());
	$menu = jQuery(".responsiveMenuTheme4");
	$toggle = $menu.prev("a.toggleMenu");
	if (ww <= maxMobileWidth) {
		jQuery(".responsiveMenu4.toggleMenu").css("display", "inline-block").removeClass("isDesktop").addClass("isMobile");
		if (!jQuery(".responsiveMenu4.toggleMenu").hasClass("active")) {
			jQuery(".responsiveMenuTheme4").hide().removeClass("isDesktop").addClass("isMobile");
		} else {
			jQuery(".responsiveMenuTheme4").show().removeClass("isDesktop").addClass("isMobile");
		}
		jQuery(".responsiveMenuTheme4 li").unbind('mouseenter mouseleave');
		jQuery(".responsiveMenuTheme4 li.deeper>span.separator").addClass("parent");
		jQuery(".responsiveMenuTheme4 li.deeper a.parent, .responsiveMenuTheme4 li.deeper span.separator").unbind('click').bind('click', function(e) {
			e.preventDefault();
			jQuery(this).parent("li").toggleClass("hover");
		});
		jQuery(".responsiveMenuTheme4 li a.parent > span").click(function(){
			if((typeof jQuery(this).parent().attr("href") != 'undefined') && jQuery(this).parent().attr("href") != "#"){
					jQuery(this).parent().unbind('click');
					myLink = jQuery(this).parent().attr("href");
					window.location.href = myLink;
			}
		});
		$menu.find("a[href^='#']").click(function(){
			jQuery("body").animate({left:"0"}, 400, function(){
			jQuery("ul.responsiveMenuTheme4").hide();
			jQuery(".responsiveMenu4.toggleMenu").removeClass("active");
			jQuery(".responsiveMenuTheme4").prependTo(jQuery("#responsiveMenuTheme4Cnt"));
			adjustMenu4();
			}).css("width", "auto").css("position", "relative");
		});
	} 
	else if (ww > maxMobileWidth) {
		jQuery("ul.responsiveMenuTheme4").hide();
		jQuery("body").css("left", "auto").css("width", "auto").css("position", "relative");
		jQuery(".responsiveMenu4.toggleMenu").removeClass("active");
		jQuery(".responsiveMenuTheme4").prependTo(jQuery("#responsiveMenuTheme4Cnt"));
		jQuery("body").css("left", "auto").css("width", "auto").css("position", "relative");
		jQuery(".responsiveMenu4.toggleMenu").css("display", "none").removeClass("isMobile").addClass("isDesktop");
		jQuery(".responsiveMenuTheme4").show().removeClass("isMobile").addClass("isDesktop");
		jQuery(".responsiveMenuTheme4 li").removeClass("hover");
		jQuery(".responsiveMenuTheme4 li a").unbind('click');
		jQuery(".responsiveMenuTheme4 li").unbind('mouseenter mouseleave').bind('mouseenter', function() {
		 	jQuery(this).addClass('hover');	
		}).bind('mouseleave', function(){
			jQuery(this).removeClass('hover');
		});
		if(detectmob()){
			jQuery(".responsiveMenuTheme4 li.deeper > a").click(function(e){
				e.preventDefault();
			})
		}
		jQuery(".responsiveMenuTheme4").prependTo(jQuery("#responsiveMenuTheme4Cnt")).show();
		jQuery(".responsiveMenu4.toggleMenu").hide();
	}
}
var ww = jQuery(window).width();
jQuery(document).ready(function() {
	jQuery(".responsiveMenu4.toggleMenu").prependTo(jQuery("body"));
	jQuery(".responsiveMenuTheme4 li a").each(function() {
		if (jQuery(this).next().length > 0) {
			jQuery(this).addClass("parent");
		};
	})
	jQuery(".responsiveMenu4.toggleMenu").click(function(e) {
		e.preventDefault();
		if(!jQuery(this).hasClass("active")){
			jQuery("ul.responsiveMenuTheme4").appendTo("body").show();
			bodyWidth = jQuery("body").width();
			jQuery("body").animate({left:"200px"}).css("position", "absolute");
			jQuery(this).toggleClass("active");
			adjustMenu4();
		}else{
			jQuery("body").animate({left:"0"}, 400, function(){
			jQuery("ul.responsiveMenuTheme4").hide();
			jQuery(".responsiveMenu4.toggleMenu").removeClass("active");
			jQuery(".responsiveMenuTheme4").prependTo(jQuery("#responsiveMenuTheme4Cnt"));
			adjustMenu4();
			}).css("width", "auto").css("position", "relative");
		}
	});
	ww = jQuery(window).width();
	adjustMenu4();
})
jQuery(window).bind('resize orientationchange', function() {
	//ww = document.body.clientWidth;
	ww = jQuery(window).width();
	adjustMenu4();
});
