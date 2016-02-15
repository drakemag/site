jQuery(document).ready(function(){

	jQuery("ul.responsiveMenuTheme3 li.parent a").click(function(e){
		//e.preventDefault();
	}).children("span.linker").click(function(){
		//window.location.href = jQuery(this).parent("a").attr("href");
	}).siblings("span.opener").click(function(e){
		e.preventDefault();
		jQuery(this).parent("a").parent("li").siblings().find(".open").removeClass("open").siblings("ul").slideUp("fast");
		jQuery(this).parent("a").siblings("ul").slideToggle("fast").end().toggleClass("open");
	});

});