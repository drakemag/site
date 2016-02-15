var adjustMenu1 = function() {
	function detectmob() { 
		if( navigator.userAgent.match(/Android/i)
			|| navigator.userAgent.match(/webOS/i)
			/*|| navigator.userAgent.match(/iPhone/i)
			|| navigator.userAgent.match(/iPad/i)*/
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
	jQuery(".responsiveMenuTheme1").each(function(){
		$menu = jQuery(this);
		$toggle = $menu.prev("a.toggleMenu");
		var maxMobileWidth = $menu.data('maxmobilewidth');
		if (ww <= maxMobileWidth) {
			$toggle.css("display", "inline-block").removeClass("isDesktop").addClass("isMobile");
			if (!$toggle.hasClass("active")) {
				$menu.hide().removeClass("isDesktop").addClass("isMobile");
			} else {
				$menu.show().removeClass("isDesktop").addClass("isMobile");
			}
			$menu.find("li").unbind('mouseenter mouseleave').filter(".deeper").children("span.separator").addClass("parent");
			$menu.find("li.deeper>a.parent, li.deeper>span.separator").unbind('click').bind('click', function(e) {
				e.preventDefault();
				jQuery(this).parent("li").children("ul").animate({height: 'toggle'}, 300, "jswing");
			});
			$menu.find("li a.parent > span.linker").click(function(){
				if((typeof jQuery(this).parent().attr("href") != 'undefined') && jQuery(this).parent().attr("href") != "#"){
						jQuery(this).parent().unbind('click');
						myLink = jQuery(this).parent().attr("href");
						window.location.href = myLink;
				}
			});
			$toggle.unbind("click").click(function(e){
				e.preventDefault();
				$toggle = jQuery(this);
				$menu = jQuery(this).next("ul");
				if(!$toggle.hasClass("active")){
					$toggle.removeClass("active");
					$menu.hide();
				}
				$toggle.toggleClass("active");
				$menu.toggle();
			});
			$menu.find("a[href^='#']").click(function(){
				jQuery(this).parents("ul").hide();
				$toggle.removeClass("active");
				$menu.hide();
			});
		} else if (ww > maxMobileWidth) {
			$toggle.css("display", "none").removeClass("isMobile").addClass("isDesktop");
			$menu.show().removeClass("isMobile").addClass("isDesktop");
			$menu.find("li").removeClass("hover").find("a").unbind('click');
			$menu.children("li").unbind('mouseenter mouseleave').bind('mouseenter', function() {
				jQuery(this).children("ul").stop(1,1).animate({height: 'show'}, 300, "jswing");
			}).bind('mouseleave', function(){
			 	jQuery(this).children("ul").animate({height: 'hide'}, 300, "jswing");
			});
			$menu.children("li").find("li").unbind('mouseenter mouseleave').bind('mouseenter', function() {
				jQuery(this).children("ul").stop(1,1).animate({width: 'show'}, 300, "jswing");
			}).bind('mouseleave', function(){
			 	jQuery(this).children("ul").animate({width: 'hide'}, 300, "jswing");
			});
			if(detectmob()){
				$menu.find(".responsiveMenuTheme1 li.deeper > a").click(function(e){
					e.preventDefault();
				})
			}
		}
	});
}
var ww = jQuery(window).width();
// ww2 = document.body.clientWidth;
jQuery(document).ready(function() {
	jQuery(".responsiveMenuTheme1").each(function(){
		$menu = jQuery(this);
		$toggle = $menu.prev("a.toggleMenu");
		$menu.find("li.parent > a").addClass("parent");
		ww = jQuery(window).width();;
		adjustMenu1();
	});
})
jQuery(window).bind('resize orientationchange', function() {
	ww = jQuery(window).width();
	adjustMenu1();
});
