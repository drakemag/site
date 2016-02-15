<!-- HELPFUL LINKS - 20141120 - RGS 
http://owlgraphic.com/owlcarousel/demos/transitions.html
http://www.owlgraphic.com/owlcarousel/

-->

    <!--link href="/templates/baseline/css/owl/bootstrapTheme.css" rel="stylesheet"-->
    <!--link href="/templates/baseline/css/owl/custom.css" rel="stylesheet"-->

    <!-- Owl Carousel Assets -->
    <link href="/templates/baseline/css/owl/owl.carousel.css" rel="stylesheet">
    <link href="/templates/baseline/css/owl/owl.theme.css" rel="stylesheet">

    <link href="/templates/baseline/css/owl/google-code-prettify/prettify.css" rel="stylesheet">



<?php
$issue_promo_text = '
    						<div class="promo_text_container">
    							<div class="promo_text">
    								<div class="promo_issue_cover">
    									<a href="http://shop.drakemag.com/2015-Winter-Issue_p_97.html"><img src="/images/back_issue/2015/2015_winter_issue-promo.png" alt="Winter 2015 issue cover" /></a>
    								</div>
    								<h2><a href="/back-issues/2015/winter.html?limitstart=0">Storm Into the Season</a></h2>
									<p>The Drake, Winter 2015, on newsstands and in fly shops today, featuring… B.C.’s Pipeline Promises, plus: forecasting the West’s best winter, dorado below dams, Atlantic salmon tribes, Florida barracuda, born again as a bass pro, SoCal steelhead, Venice reds, North Umpqua redemption, Northwest bar fights, Montana’s unsung banjo-scene, musky muscle, shore albies, and a surefire way to gain two weeks of summer—in January.</p>
									<img src="/templates/baseline/images/promo/btn_buy_subscribe.png" alt="buy subscribe button" usemap="#buy_sub"/>
    							</div>
    						</div> 
 							<div id="promo_text_bkgd"></div>
';
$big_year_contest_2015 = '
    						<div class="promo_text_container">
    							<div class="promo_text">
    								<h2><a href="/featured-content/the-big-year-contest.html">THE BIG YEAR CONTEST<br />
    									#DrakemagBigYear</a></h2>
									<p>Since the 1930s, the world of birding has held an informal contest to find out who can see or hear the largest number of species of birds within a calendar year. They call this contest The Big Year, and we thought it was time flyfishing had its own Big Year. Our contest will be run through Facebook and other social-media outlets like Twitter and Instagram. It is open to anyone, and the rules are simple.</p>
									<a href="/featured-content/the-big-year-contest.html"><img src="/templates/baseline/images/promo/2014/learn_more.png" alt="learn more button" /></a>
    							</div>
    						</div> 
 							<div id="promo_text_bkgd"></div>
							
							<map name="buy_sub">
							  <area shape="rect" coords="0,0,158,32" href="http://shop.drakemag.com/Subscriptions-Renewals_c_1.html" alt="Sun">
							  <area shape="rect" coords="159,0,316,32" href="http://shop.drakemag.com/2015-Winter-Issue_p_97.html" alt="Sun">
							</map>
';
?>

    <div id="demo">
    	<div class="container">
    		<div class="row">
    			<div class="span12">
    				<div id="owl-demo" class="owl-carousel">
    					<div class="item">
	    						<?php echo $issue_promo_text; ?>
   							<a href="http://www.drakemag.com/back-issues/2015/winter.html"> 
								<img src="/templates/baseline/images/promo/2015/2015_winter_albie.jpg" alt="Drake Magazine 2015 Winter Issue" border="0" usemap="#map1" />
   							</a> 
   						</div>

    					<div class="item">
	    						<?php echo $issue_promo_text; ?>
   							<a href="http://www.drakemag.com/back-issues/2015/winter.html"> 
								<img src="/templates/baseline/images/promo/2015/2015_winter_move.jpg" alt="Drake Magazine 2015 Winter Issue" border="0" usemap="#map1" />
   							</a> 
   						</div>

    					<div class="item">
	    						<?php echo $issue_promo_text; ?>
   							<a href="http://www.drakemag.com/back-issues/2015/winter.html"> 
								<img src="/templates/baseline/images/promo/2015/2015_winter_derailed.jpg" alt="Drake Magazine 2015 Winter Issue" border="0" usemap="#map1" />
   							</a> 
   						</div>

    					<div class="item">
	    						<?php echo $issue_promo_text; ?>
   							<a href="http://www.drakemag.com/back-issues/2015/winter.html"> 
								<img src="/templates/baseline/images/promo/2015/2015_winter_clyde.jpg" alt="Drake Magazine 2015 Winter Issue" border="0" usemap="#map1" />
   							</a> 
   						</div>

    					<div class="item">
	    						<?php echo $issue_promo_text; ?>
   							<a href="http://www.drakemag.com/back-issues/2015/winter.html"> 
								<img src="/templates/baseline/images/promo/2015/2015_winter_everyday.jpg" alt="Drake Magazine 2015 Winter Issue" border="0" usemap="#map1" />
   							</a> 
   						</div>

    					
					</div>
				</div>
			</div>
		</div>
	</div>



    	<script src="/templates/baseline/js/owl/jquery-1.9.1.min.js"></script> 
    	<script src="/templates/baseline/js/owl/owl.carousel.js"></script>

    	<script type="text/javascript">
    		$(document).ready(function() {
    			$("#owl-demo").owlCarousel({

    				navigation : true,
    				slideSpeed : 300,
    				paginationSpeed : 400,
    				singleItem : true,

	// Most important owl features
	/*
	items : 5,
	itemsCustom : false,
	itemsDesktop : [1199,4],
	itemsDesktopSmall : [980,3],
	itemsTablet: [768,2],
	itemsTabletSmall: false,
	itemsMobile : [479,1],
	singleItem : false,
	itemsScaleUp : false,
	*/
	//Basic Speeds
	slideSpeed : 200,
	paginationSpeed : 800,
	rewindSpeed : 1000,

	//Autoplay
	autoPlay : true,
	stopOnHover : true,

	// Navigation
	navigation : true,
	navigationText : ["&#9664;","&#9654;"],
	rewindNav : true,
	scrollPerPage : false,

	//Pagination
	pagination : true,
	paginationNumbers: false,

	// Responsive
	responsive: true,
	responsiveRefreshRate : 200,
	responsiveBaseWidth: window,

	// CSS Styles
	baseClass : "owl-carousel",
	theme : "owl-theme",

	//Lazy load
	lazyLoad : false,
	lazyFollow : true,
	lazyEffect : "fade",

	//Auto height
	autoHeight : true,

	//JSON
	jsonPath : false,
	jsonSuccess : false,

	//Mouse Events
	dragBeforeAnimFinish : true,
	mouseDrag : true,
	touchDrag : true,

	//Transitions
	transitionStyle : false,

	// Other
	addClassActive : false,

	//Callbacks
	beforeUpdate : false,
	afterUpdate : false,
	beforeInit: false,
	afterInit: false,
	beforeMove: false,
	afterMove: false,
	afterAction: false,
	startDragging : false,
	afterLazyLoad : false


	// "singleItem:true" is a shortcut for:
	// items : 1, 
	// itemsDesktop : false,
	// itemsDesktopSmall : false,
	// itemsTablet: false,
	// itemsMobile : false

});
});
</script>

<script src="/templates/baseline/js/owl/bootstrap-collapse.js"></script>
<script src="/templates/baseline/js/owl/bootstrap-transition.js"></script>
<script src="/templates/baseline/js/owl/bootstrap-tab.js"></script>

<script src="/templates/baseline/js/owl/google-code-prettify/prettify.js"></script>
<script src="/templates/baseline/js/owl/application.js"></script>

