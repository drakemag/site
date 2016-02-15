<?php 
	//$link_slug = '<a href="/index.php?option=com_content&view=category&layout=blog&id=';
	//$link_slug_end = '" class="nb"><span class="green">BACK ISSUE:';
	//$bi_link= '<a href="/index.php?option=com_content&view=category&id=159&Itemid=142" class="nb"><span class="green">BACK ISSUE: </a>';
	$bi_link= '';
	$link_slug = '<a href="/index.php?option=com_content&view=category&layout=blog&id=';
	$link_slug_end = '" class="nb">';

	if($remove_contents == 1){
		echo '
		<style>
		div.bi_desc,
		span.contents{display:none;}
		</style>
		';
	}
// catid = 350  - Winter 2015
	$winter_2015_slug = $link_slug . "350" . $link_slug_end;
	$winter_2015 = <<<EOT
		<h3>$bi_link $winter_2014_slug Winter 2015 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2015-Winter-Issue_p_97.html"><img src="/images/back_issue/2015/2015_winter_issue.jpg" alt="Drake 2015 winter Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>			

						<li>On the Mend<br />
						<div class="bi_article_desc">The Kootenai River is hidden away in the boondocks of northwest Montana. But look closer at the trout and the scenery and Dave Blackburn&#146;s lodge, and it doesn&#146;t seem so far.</div>
						<div class="bi_biline">By Myers Reece</div></li>
						
						<li><a href="http://www.drakemag.com/back-issues/2015/winter/1534-derailed.html">Derailed</a><br />
						<div class="bi_article_desc">Canadian Prime Minister Justin Trudeau banned oil-tanker traffic on B.C.&#146;s north coast, lessening the threat of oil pipelines running along steelhead rivers. But plenty of other threats remain.</div>
						<div class="bi_biline">By Leslie Anthony, Steven Hawley, and Tom Bie</div></li>
						
						<li>Minnesota Musky<br />
						<div class="bi_article_desc">There&#146;s no toothy critters like &#146;Sota toothy critters. Our photographers in the field bring back a report to help you survive the winter.</div>
						<div class="bi_biline">By Lee Church and Corey Kruitbosch</div></li>
						
						<li>Pancora Holiday<br />
						<div class="bi_article_desc">Catching a Patagonian brown or rainbow is like catching one in Montana or Colorado. Only it&#146;s those two states circa 1915, not 2015. You&#146;ll also lose a week of January or February, while gaining a week of Southern Hemisphere sunshine&mdash;a trade we&#146;ll take.</div>
						<div class="bi_biline">By Tom Bie</div></li>
										
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<h2>Departments</h2>
						<li>Page Six Chix<br />
							<div class="bi_article_desc">Wintertime hat-trick: Musky, steelhead, and a Mongolian taimen.</div> 
						</li>
	
						<li>Put-in<br />
							<div class="bi_article_desc">Arctic weather options for sourcing a level of quiet calm under falling snow.</div> 
						</li>
						
						<li>Rises <br />
							<div class="bi_article_desc">Bushkill weekends, community connectedness, and a Deschutes River reply.</div> 
						</li>
						
						<li>Scuddlebutt <br />
							<div class="bi_article_desc">Waiting on El Niño, <a href="http://www.drakemag.com/back-issues/2015/winter/1533-ride-with-clyde-just-say-yes.html">Clyde tours the Front Strange</a>, an OP steelheader&#146;s best friend, playing Hooké, pro bassin&#146; on the fly, school spirit, baked trout, and a <a href="http://www.drakemag.com/back-issues/2015/winter/1537-move-or-die.html">scientific study on how to cheat death</a>.</div></li>
						
						<li>Tailwater Weekend<br />
						<div class="bi_article_desc">Getting down in the Gunnison Gorge.</div>
						<div class="bi_biline">By Gus Jarvis</div></li>
		
						<li>Tippets <br />
							<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2015/winter/1538-shore-albies.html">Addicted to shore albies</a>, North Umpqua redemption, a <a href="http://www.drakemag.com/back-issues/2015/winter/1536-everyday-people.html">few flyfishers we know</a>, reflections from the backseat, a <a href="http://www.drakemag.com/back-issues/2015/winter/1535-bar-fight.html">Northwest bar brawl</a>, from Steamboat to Striperville, and the real life of a hoarder with a fishing hobby.</div></li>
						
						<li>Redspread<br />
						<div class="bi_article_desc">Venice Reds: A homecoming in southern Louisiana.</div>
						<div class="bi_biline">By Colles Stowell</div></li>
						
						<li>Passport<br />
						<div class="bi_article_desc">Unguided epiphanies in the Bahamas.</div>
						<div class="bi_biline">By Jason Houston</div></li>
						
						<li>Bugs<br />
						<div class="bi_article_desc">A tailwater shrimp cocktail.</div>
						<div class="bi_biline">By Geoff Mueller</div></li>
						
						<li>City Limits<br />
						<div class="bi_article_desc">Hobo hatches and Grand Junction heroics.</div>
						<div class="bi_biline">By Justin Edge</div></li>
						
						<li>Rodholders<br />
						<div class="bi_article_desc">Engraving rods with Bill Oyster.</div>
						<div class="bi_biline">By Zach Matthews</div></li>					
	
						<li>Backcountry<br />
						<div class="bi_article_desc">Into the North for busted ankles, brotherly bonds, and backwoods pike.</div>
						<div class="bi_biline">By Dave Karczynski</div></li>
						
						<li>Permit Page<br />
						<div class="bi_article_desc">Wading for the one that didn&#146;t get away.</div>
						<div class="bi_biline">By Luke Williams</div></li>
					</ul>
				</div>
			</div>
		</div>
EOT;

// catid = 594  - Fall 2015
	$fall_2015_slug = $link_slug . "349" . $link_slug_end;
	$fall_2015 = <<<EOT
		<h3>$bi_link $winter_2014_slug Fall 2015 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2015-Fall-Issue_p_94.html"><img src="/images/back_issue/2015/2015_fall_issue.jpg" alt="Drake 2015 Fall Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>			
						<li>Western Remembrance<br />
						<div class="bi_article_desc">Waitressing West Yellowstone in the 1980s, our writer met a fishing guide. In fact, she met a few.</div>
						<div class="bi_biline">By Darcy Lohmiller</div></li>
						
						<li><br />
						<li><a href="/back-issues/2015/fall/1492-ahead-of-the-flood.html">Ahead of the Flood</a><br />
						<div class="bi_article_desc">Floating Arkansas’ beloved Buffalo for smallies and sunfish, days are filled with tossing poppers, stripping streamers, and lobbing bacon grease.</div>
						<div class="bi_biline">By Miles Nolte</div></li>
						
						<li>The Wind at Montauk<br />
						<div class="bi_article_desc">Striper fishing the rocks of Long Island is not for the faint of heart. It’s also not for trout fishermen.</div>
						<div class="bi_biline">By James Wu </div></li>
						
						<li>Return of Wild Abundance<br />
						<div class="bi_article_desc">Osoyoos Lake sits on the border of Washington and British Columbia. In the past 20 years, its wild sockeye run has increased nearly <strong>30,000%</strong>. Why?</div>
						<div class="bi_biline">By Bill McMillan </div></li>
						
											
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<h2>Departments</h2>
						<li>Page Six Chix<br />
							<div class="bi_article_desc">Two browns, a bass, and a bonefish. With love.</div> 
						</li>
	
						<li>Put-in<br />
							<div class="bi_article_desc">Be the bucket. And other joys of water-reading.</div> 
						</li>
						
						<li>Rises <br />
							<div class="bi_article_desc">Lab love, scolding Scotland, and Casa de Caddis.</div> 
						</li>
						
						<li>Scuddlebutt <br />
							<div class="bi_article_desc">Deschutes dilemma, striper movie, <a href="/back-issues/2015/fall/1493-the-compromise.html">a new take on public lands</a>, fishing wildlife refuges, Kanton Atoll, steelhead martini, and <a href="/back-issues/2015/fall/1495-gotcha-covered.html">solutions for the Bahamas!</a></div> 
						</li>
						
						<li>Tailwater Weekend<br />
						<div class="bi_article_desc">Western Connecticut’s Deerfield River delivers.</div>
						<div class="bi_biline">By Stephen Zakur</div></li>
		
						<li>Tippets <br />
							<div class="bi_article_desc">Fishing as religion, <a href="/back-issues/2015/fall/1496-err-qatar.html">Qatar justice</a>, <a href="/back-issues/2015/fall/1494-deformed-loops.html">in defense of shitty casting</a>, Driftless bar, aggressive taimen, fishing and Letterman, Shakespeare steelhead</div> 
						</li>
						
						<li>Redspread<br />
						<div class="bi_article_desc">The heartbreaking story of Randy Charba.</div>
						<div class="bi_biline">By Tosh Brown</div></li>
						
						<li>Passport<br />
						<div class="bi_article_desc">Everyone loves Atlantic salmon on the Gaspé. But what about all those stripers?</div>
						<div class="bi_biline">By Ben Carmichael</div></li>
						
						<li>Bugs<br />
						<div class="bi_article_desc">Steelheading with the General Practitioner.</div>
						<div class="bi_biline">By Geoff Mueller</div></li>
						
						<li>City Limits<br />
						<div class="bi_article_desc">Columbia, South Carolina’s schoolie stripers.</div>
						<div class="bi_biline">By Tommy Cody</div></li>
						
						<li>Rodholders<br />
						<div class="bi_article_desc">Tommy Lynch, Batman of the Pere Marquette.</div>
						<div class="bi_biline">By Preston Marson</div></li>					
	
						<li>Backcountry<br />
						<div class="bi_article_desc">Bonefishing the Bahamian hinterlands.</div>
						<div class="bi_biline">By Mike Benson</div></li>
						
						<li>Permit Page<br />
						<div class="bi_article_desc">The life and flies of Craig Mathews.</div>
						<div class="bi_biline">By Geoff Mueller</div></li>
					</ul>
				</div>
			</div>
		</div>
EOT;

// catid = 346  - Summer 2015
	$summer_2015_slug = $link_slug . "346" . $link_slug_end;
	$summer_2015 = <<<EOT
		<h3>$bi_link $winter_2014_slug Summer 2015 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2015-Summer-Issue_p_90.html"><img src="/images/back_issue/2015/2015_summer_issue.png" alt="Drake 2015 Summer Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>			
						<li><a href="http://www.drakemag.com/back-issues/2015/summer/1438-i-love-laxa.html">I Love Laxa</a><br />
						<div class="bi_article_desc">Big Icelandic salmon, secret American flies, and the determination of Orri Vigfusson.</div>
						<div class="bi_biline">By Tom Bie</div></li>
						
						<li><a href="http://www.drakemag.com/back-issues/2015/summer/1437-chasing-natives-raging-bull.html">Chasing Natives</a><br />
						<div class="bi_article_desc">From backcountry brookies to spirited pickerel, this is our quest for encounters of the indigenous kind.</div>
						<div class="bi_biline">By Zach Matthews, John Larison, Jimmy Fee, Kevin Luby, Brian Boomer, and Will Jordan</div></li>

						<li>Patches<br />
						<div class="bi_article_desc">When a shiny she-boat morphs into a he-boat, and becomes a confidant.</div>
						<div class="bi_biline">By Monty Orrick</div></li>
						
						<li>Post-Inferno Flyfishing<br />
						<div class="bi_article_desc">In August 2013, two lightning-caused blazes burned 435 square miles of Idaho’s South Fork Boise drainage, showing just how much impact fires can have on a river.</div>
						<div class="bi_biline">By Mark Menlov</div></li>
											
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<h2>Departments</h2>
						<li>Page Six Chix<br />
							<div class="bi_article_desc">The Flathead, the Yellowstone, the Menominee.</div> 
						</li>
	
						<li>Put-in<br />
							<div class="bi_article_desc">Summer Camp, America’s best idea, and its worst.</div> 
						</li>
						
						<li>Rises <br />
							<div class="bi_article_desc">Husband exchange, political delusions, girlfriend dilema, and using a puppy to our advantage.</div> 
						</li>
						
						<li>Scuddlebutt <br />
							<div class="bi_article_desc">Nicaragua tarpon, coho comeback, Clyde takes a slyde, <a href="http://www.drakemag.com/back-issues/2015/summer/1439-top-ten-bikini-hatches.html">summer bikini hatches</a>, oversized art, PNW smallies, striper uncertainty, and one feared Beard.</div> 
						</li>
						
						<li>Tailwater Weekend<br />
						<div class="bi_article_desc">Trout-filled tailwaters in Oklahoma and Texas.</div>
						<div class="bi_biline">By Stephen Schwartz</div></li>
		
						<li>Tippets <br />
							<div class="bi_article_desc">Sounds of bluefish, steelheading the ‘V,’ inglorious bassers, a crowded roadtrip, <a href="http://www.drakemag.com/back-issues/2015/summer/1436-for-the-love-of-hats.html">good hats</a>, stripers in plain sight, and catching brown trout in Scotland.</div> 
						</li>
						
						<li>Redspread<br />
						<div class="bi_article_desc">The Islamorada backcountry delivers, eventually.</div>
						<div class="bi_biline">By Matt Smythe</div></li>
						
						<li>Passport<br />
						<div class="bi_article_desc">A purist’s exile to Saskatchewan’s Pikelandia.</div>
						<div class="bi_biline">By Toby Gilbert</div></li>
						
						<li>Bugs<br />
						<div class="bi_article_desc">Puget Sound termites.</div>
						<div class="bi_biline">By Jesse Robbins</div></li>
						
						<li>City Limits<br />
						<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2015/summer/1435-the-trash-men.html">Flats fishing the Dirty South.</a></div>
						<div class="bi_biline">By Zach Matthews</div></li>
						
						<li>Rodholders<br />
						<div class="bi_article_desc">How Jon Yousko pulls off the endless season.</div>
						<div class="bi_biline">By Geoff Mueller</div></li>					
	
						<li>Backcountry<br />
						<div class="bi_article_desc">500 miles from Denver to Durango, on foot.</div>
						<div class="bi_biline">By Ben Kraushaar</div></li>
						
						<li>Permit Page<br />
						<div class="bi_article_desc">Protect the spawning grounds.</div>
						<div class="bi_biline">By Terry Gibson</div></li>
					</ul>
				</div>
			</div>
		</div>
EOT;

// catid = 344  - Spring 2015
	$spring_2015_slug = $link_slug . "345" . $link_slug_end;
	$spring_2015 = <<<EOT
		<h3>$bi_link $winter_2014_slug Spring 2015 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2015-Spring-Issue_p_88.html"><img src="/images/back_issue/2015/2015_spring_issue.jpg" alt="Drake 2015 Spring Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>			
					<li><a href="">On the Peninsula</a><br />
						<div class="bi_article_desc">Being raised in the upper left corner of the upper left state makes it hard to be anything but a steelheader.</div>
						<div class="bi_biline">By Kevin Maier</div></li>
						
						<li><a href=""><a href="http://www.drakemag.com/back-issues/2015/spring/1387-christmas-island-comeback.html">Christmas Island Comeback</a></a><br />
						<div class="bi_article_desc">After thirty years of hosting flyfishers, with maybe a little low spot in the middle, Christmas Island is as good as ever.</div>
						<div class="bi_biline">By Tom Bie</div></li>
						
						<li><a href="">Prince and Preacher</a><br />
						<div class="bi_article_desc">Like rowing your driftboat? Or fishing out of somebody else’s? These two paved the way, just like the story says. More or less.</div>
						<div class="bi_biline">By John Larison</div></li>
						
						<li><a href="">The Transfer</a><br />
						<div class="bi_article_desc">Something strange is brewing in Utah, and it’s spreading to other parts of the West. Our public lands are in jeapordy.</div>
						<div class="bi_biline">By Drew Simmons</div></li>
											
					<h2>Departments</h2>
	
						<li>Page Six Chix<br />
						<div class="bi_article_desc">Five on six, from sockeye to smallmouth.</div></li>
						
						<li>Put-in<br />
						<div class="bi_article_desc">We’re fine with fish photos&mdash;but please be careful.</div></li>
						
						<li>Rises<br />
						<div class="bi_article_desc">Old Drakes, new fonts, and a little bluefish love.</div></li>
						
						<li>Scuddlebutt<br />
							<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2015/spring/1383-drone-zone.html">Drone nation</a>, green drakes, <a href="http://www.drakemag.com/back-issues/2015/spring/1382-fort-smith-flyby.html">Clyde hits the Bighorn</a>, grayling studies, travel tips, <a href="http://www.drakemag.com/back-issues/2015/spring/1384-spring-training.html">fishing and baseball</a>, a salmon savior, and some trailrunners go flyfishing.</div>
						</li>	
			
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
						<li>Tippets <br />
							<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2015/spring/1385-the-tarpon-bite.html">Fast tarpon</a>, colors of Russia, ode to dads, secret stripers, Montana bull trout, church and flyfishing, bass fishing New York, and steelheading California.</div> 
						</li>
						
						<li>Tailwater Weekend<br />
						<div class="bi_article_desc">Chasing brookies on Connecticut’s Mill River.</div>
						<div class="bi_biline">By Steve Zakur</div></li>
		
						<li>Redspread<br />
						<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2015/spring/1386-the-tailing-dead.html">The Tailing Dead</a>. Drum lessons.</div>
						<div class="bi_biline">By Tosh Brown</div></li>
						
						<li>Passport<br />
						<div class="bi_article_desc">Chasing big Bulgarian ’bows on the Mesta River.</div>
						<div class="bi_biline">By Peter Scorzetti</div></li>
						
						<li>Bugs<br />
						<div class="bi_article_desc">The time for Hendricksons is upon us.</div>
						<div class="bi_biline">By Steve Zakur</div></li>
						
						<li>City Limits<br />
						<div class="bi_article_desc">Scranton, Pennsylvania’s Lackawanna River.</div>
						<div class="bi_biline">By Kevin McNicholas</div></li>
						
						<li>Rodholders<br />
						<div class="bi_article_desc">The woman who brought flyfishing to Maine.</div>
						<div class="bi_biline">By Tony Lolli</div></li>					
	
						<li>Backcountry<br />
						<div class="bi_article_desc">It’s a dangerous world in Key West.</div>
						<div class="bi_biline">By Paul Bruun</div></li>
						
						<li>Permit Page<br />
						<div class="bi_article_desc">To find permit in the Bahamas, you first need to look.</div>
						<div class="bi_biline">By John Frazier</div></li>
					</ul>
				</div>
			</div>
		</div>
EOT;

// catid = 338  - Winter 2014-2015
	$winter_2014_slug = $link_slug . "338" . $link_slug_end;
	$winter_2014 .= <<<EOT
		<h3>$bi_link $winter_2014_slug Winter 2014-2015 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2014-Winter-Issue_p_86.html"><img src="/images/back_issue/2014/2014_winter_issue.png" alt="Drake 2014 Winter Issue"></a>
			</div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>			
						<li><a href="http://www.drakemag.com/back-issues/2014/winter/1349-diy-dialectic.html">DIY Dialectic</a><br />
						<div class="bi_article_desc">The decision to hire a guide or fish on your own can be a difficult one to make, especially for ex-guides. Let the internal debate begin.</div><div class="bi_biline">
						By Franklin Tate</div></li>
						
						<li>Meet the Metolius<br />
						<div class="bi_article_desc">Bull trout are like Bigfoot: huge, mysterious, misunderstood, and really hard to find. But go deep enough, and they’ll show up. Hungry.</div><div class="bi_biline">
						Story and Photos by Sam Lungren</div></li>
						
						<li>Pyramid Scheme<br />
						<div class="bi_article_desc">Hatcheries are the worst thing to ever happen to trout. Until one saved a species. Our man in Nevada climbs his corporate ladder.</div><div class="bi_biline">
						By Steven Hawley</div></li>

						<li>Postcards from Playa Blanca<br />
						<div class="bi_article_desc">Whatever happened to postcards? We bring them back, with notes to mom. From Mexico.</div><div class="bi_biline">
						Photos by Corey Kruitbosch</div></li>

					
					<h2>Departments</h2>
	
						<li>Page Six Chix<br />
						<div class="bi_article_desc">From desert to mountains to flats.</div></li>
						
						<li>Put-in<br />
						<div class="bi_article_desc">Broaden your horizons.</div></li>
						
						<li>Rises<br />
						<div class="bi_article_desc">Dogs, gators, and imperial presidents.</div></li>
						
						<li>Scuddlebutt<br />
						<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2014/winter/1345-chill-out.html">Pesca Maya’s alternative energy</a>, <a href="http://www.drakemag.com/back-issues/2014/winter/1346-rough-ride.html">Clyde turns three</a>, striper art, NorCal’s Putah Creek, huge peacock bass, Land and Water Conservation Fund, Virginia billfish, mayfly storms, and lodge-owner legal troubles</div></li>	
			
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
						<li>Tippets <br />
						<div class="bi_article_desc">Steelheading at first light, jig intervention, <a href="http://www.drakemag.com/back-issues/2014/winter/1348-first-bone.html">Exuma bonefishing</a>, ice-out pike, false false-casting, <a href="http://www.drakemag.com/back-issues/2014/winter/1347-the-blue-duffel.html">Alaskan delivery service</a>, and the joys of fishing alone</div> </li>
						
						<li>Tailwater Weekend<br />
						<div class="bi_article_desc">Smallies, pike, and lake trout on Fort Peck Rez.</div><div class="bi_biline">
						By Geoff Mueller</div></li>
		
						<li>Redspread<br />
						<div class="bi_article_desc">A number-crunching approach to Texas redfishing.</div><div class="bi_biline">
						By Tosh Brown</div></li>
						
						<li>Passport<br />
						<div class="bi_article_desc">Good golly, Miss Molly</div><div class="bi_biline">
						By Preston Marson</div></li>
						
						<li>Bugs<br />
						<div class="bi_article_desc">Death by Dobsonfly</div><div class="bi_biline">
						By Zach Matthews</div></li>
						
						<li>City Limits<br />
						<div class="bi_article_desc">Who’s up for a little carp tourney?</div><div class="bi_biline">
						By Dan Frasier</div></li>
						
						<li>Backcountry<br />
						<div class="bi_article_desc">A bloody day of brookie fishing.</div><div class="bi_biline">
						By Warren Winders</div></li>
						
						<li>Permit Page<br />
						<div class="bi_article_desc">He swam from a land Down Under.</div><div class="bi_biline">
						By Brett Seng</div></li>
					</ul>
				</div>
			</div>
		</div>
EOT;


// catid = 337  - Fall 2014
	$fall_2014_slug = $link_slug . "337" . $link_slug_end;
	$fall_2014 = <<<EOT
		<h3>$bi_link $fall_2014_slug 2014 Fall Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2014-Fall-Issue_p_82.html"><img src="/images/back_issue/2014/2014_fall_issue.png" alt="Drake 2014 Fall Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>			
					<li><a href="http://www.drakemag.com/back-issues/2014/fall/1316-hating-the-thompson.html">Hating the Thompson</a><br />
					British Columbia’s famed Thompson River will be open to steelheaders this October, for the first time in a decade. But what will the fishermen find when they get there?<div class="bi_article_desc"></div><div class="bi_biline">
					By Dana Sturn | Photos by Adam Tavender</div></li>
					
					<li>A Fisherman’s Monument<br />
					Idaho’s Boulder and White Clouds Mountains make up the largest unprotected roadless area in the Lower 48. It’s time to preserve it.<div class="bi_article_desc"></div><div class="bi_biline">
					By Mark Menlove | Photos by Ed Cannady</div></li>
					
					<li>Black Tar Permit<br />
					They say you always remember your first time. But is that a really a good thing?<div class="bi_article_desc"></div><div class="bi_biline">
					By Zach Mathews</div></li>
					
					<li>Insert Trout, Here<br />
					Meet John Goodall, the godfather of Tierra del Fuego’s sea-run brown trout.<div class="bi_article_desc"></div><div class="bi_biline">
					By Geoff Mueller</div></li>

					
					
					<h2>Departments</h2>
	
					<li>Page Six Chix<br />
					<div class="bi_article_desc">Two freshies, two salties</div></li>
					
					<li>Put-in<br />
					<div class="bi_article_desc">The value of Wilderness (with a capital “W”)</div></li>
					
					<li>Rises<br />
					<div class="bi_article_desc">On hatcheries, access, Yellowstone, and smallmouth</div></li>
					
					<li>Scuddlebutt<br />
					<div class="bi_article_desc">A glossary for striper fishermen, <a href="http://www.drakemag.com/back-issues/2014/fall/1309-keeping-it-classy-with-clyde.html">Clyde meets a cop</a>, an Olympic Peninsula fly shack, paddle-makers in Minnesota, <a href="http://www.drakemag.com/back-issues/2014/fall/1310-trending-golf-course-bass.html">more golf-course bass</a>, a collaborative fish-art project, and if tarpon could text each other</div></li>
			
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<li>Tippets <br />
					<div class="bi_article_desc">Ode to the Elwha, <a href="http://www.drakemag.com/back-issues/2014/fall/1311-another-cup.html">Gierach’s coffee</a>, a lost strain of striper fishermen, fake fly anglers, resilient brim in Texas, gangster carp in Jersey, nights in northern Michigan, and death of a dog (spoiler alert: it’s sad.)</div> </li>
					
					<li>Tailwater Weekend<br />
					<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2014/fall/1317-the-other-gunnison-river.html">Colorado’s other Gunnison River</a></div><div class="bi_biline">
					By Tom Bie</div></li>
	
					<li>Redspread<br />
					<div class="bi_article_desc">Alabama’s year-round redfishing</div><div class="bi_biline">
					By Wally Kirkland</div></li>
					
					<li>Passport<br />
					<div class="bi_article_desc">Pike of the River Test</div><div class="bi_biline">
					By John Hall</div></li>
					
					<li>Bugs<br />
					<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2014/fall/1315-autumn-monarchs.html">Want East Coast fish? Find Monarch butterflies.</a></div><div class="bi_biline">
					By Jason Skruck</div></li>
					
					<li>City Limits<br />
					<div class="bi_article_desc">The smallmouth of Traverse City, Michigan</div><div class="bi_biline">
					By Alex Cerveniak</div></li>
					
					<li>Rod Holders<br />
					<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2014/fall/1313-the-guardian.html">Lee Spencer, guardian of North Umpqua steelhead</a></div><div class="bi_biline">
					By Tom Bie</div></li>
					
					<li>Backcountry<br />
					<div class="bi_article_desc">Montana’s South Fork of the Flathead</div><div class="bi_biline">
					By Christopher Solomon</div></li>
					
					<li>Permit Page<br />
					<div class="bi_article_desc">Some considerations when tying permit flies.</div><div class="bi_biline">
					By Drew Chicone</div></li>
				</ul>
			</div>
			</div>
		</div>
EOT;


// catid = 335
	$summer_2014_slug = $link_slug . "335" . $link_slug_end;
	$summer_2014 = <<<EOT
		<h3>$bi_link $summer_2014_slug 2014 Summer Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2014-summer-Issue_p_81.html"><img src="/images/back_issue/2014/2014_summer_issue.png" alt="Drake 2014 Summer Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>			
					
					<li>Holy Trinity of the Right Coast<br />
					<div class="bi_article_desc">Striped bass on the flats, false albacore from shore, and bluefin tuna from anywhere.</div><div class="bi_biline">
					By Jason Skruck. Photos by David Skok</div></li>
					
					<li>What the Smallmouth Tells Us<br />
					<div class="bi_article_desc">Smallies are a marquee summertime fish.<br />
					And the health of smallmouth bass populations tells us much about our waterways.</div><div class="bi_biline">
					By Pete McDonald</div></li>
					
					<li>Brown Drake Bonanza<br />
					<div class="bi_article_desc">It’s an annual Gem State event: A bazillion brown drakes hatching on world-renown Silver Creek, near Picabo, Idaho.</div><div class="bi_biline">
					By Nick Price</div></li>
					
					<li>The Bass Phase<br />
					<div class="bi_article_desc">A coming-of-age story about the power of popper-eating largemouth, and how they can change a boy’s life, forever.</div><div class="bi_biline">
					By Tosh Brown</div></li>

					
					
					<h2>Departments</h2>
					
					<li>Page Six Chix<br />
					<div class="bi_article_desc">Four great smiles, four great fish, one lucky lab.</div></li>
					
					<li>Put-in<br />
					<div class="bi_article_desc">Let’s not forget about floating mayflies.</div></li>
					
					<li>Rises<br />
					<div class="bi_article_desc">A few words on weed, warmwater, and public access.</div></li>
					
					<li>Tailwater Weekend<br />
					<div class="bi_article_desc">Asses and elbows on the South Fork of the Snake. </div><div class="bi_biline">
					By Geoff Mueller</div></li>
					
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<li>Scuddlebutt<br />
					<div class="bi_article_desc">Transboundary rivers, <a href="http://www.drakemag.com/back-issues/2014/summer/1250-clyde-naked-gun.html">Clyde nearly gets shot</a>, <a href="http://www.drakemag.com/back-issues/2014/summer/1251-the-anti-artist.html">artists	Hartman</a> and Larko, Stilly after the slide, Penny’s for breakfast, <a href="http://www.drakemag.com/back-issues/2014/summer/1254-matching-the-batch.html">beer and hatches</a>, hope for wild steelhead, a headbanger’s fly shop, a flyfishing Bassmaster, drama on the Dean, and Trask takes on Hero Cat.</div></li>

					<li>Tippets <br />
					<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2014/summer/1253-girls-night-in.html">A woman’s perspective on fishing trips</a>; skeeters in the ‘Glades; mahseer by motorcycle; a plea to quit yer bitchin’; an African license plate hunt, and more.</div> </li>
					
					<li>Redspread<br />
					<div class="bi_article_desc">Fishing the TFZ in Louisiana’s Calcasieo Estuary.</div><div class="bi_biline">
					By Ron Begnaud</div></li>
					
					<li>Passport<br />
					<div class="bi_article_desc">It hurts to miss fish on Patagonia’s Rio Chimehuin.</div><div class="bi_biline">
					By Christopher Solomon</div></li>
					
					<li>Bugs<br />
					<div class="bi_article_desc">August means white fly fever in New England.</div><div class="bi_biline">
					By Stephen Zakur</div></li>
					
					<li>City Limits<br />
					<div class="bi_article_desc">Milwaukie’s got baseball and steelheading. Together.</div><div class="bi_biline">
					By James Card</div></li>
					
					<li>Rod Holders<br />
					<div class="bi_article_desc">Lacy Kelly heads to Belize.</div><div class="bi_biline">
					By Larry Littrell</div></li>
					
					<li>Backcountry<br />
					<div class="bi_article_desc">Golden trout in California’s southern Sierra.</div><div class="bi_biline">
					By Brett Wedeking</div></li>
					
					<li>Permit Page<br />
					<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2014/summer/1252-the-flies-of-enrico-puglisi.html">The flies of Enrico Puglisi.</div><div class="bi_biline">
					By Monte Burke</a></div></li>
				</ul-->
			</div>
			</div>
		</div>
EOT;


// catid = 334
	$spring_2014_slug = $link_slug . "334" . $link_slug_end;
	$spring_2014 = <<<EOT
		<h3>$bi_link $spring_2014_slug 2014 Spring Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2014-Spring-Issue_p_80.html"><img src="/images/back_issue/2014/2014_spring_issue.png" alt="Drake 2014 Spring Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>
					<li><a href="http://www.drakemag.com/back-issues/2014/spring/1214-silver-tiger-taimen.html">Silver Tiger Taimen</a><br />
					<div class="bi_article_desc">Exploring Russia’s Koppi River watershed for seductive, sea-going, Sakhalin taimen.</div><div class="bi_biline">
					By Ryan Peterson. Photos by John Sherman</div></li>
					
					<li>Shop Dogs<br />
					<div class="bi_article_desc">Meet the greeting committee.</div><div class="bi_biline">
					By Stephen Schwartz</div></li>
					
					<li>Starting Out <br />
					<div class="bi_article_desc">With Mother’s Day and Father’s Day right around the corner, we share a few stories on the people who taught us to fish.</div><div class="bi_biline">
					By Richard Bach, Reid Bryant, and Andrew Stoehr</div></li>
					
					<li>The Lake Trout Issue<br />
					<div class="bi_article_desc">Many people have strong, passionate feelings about native vs. introduced species. Especially in Yellowstone National Park.</div><div class="bi_biline">
					By Sarah Grigg, Carter Andrews, and Tom Bie</div></li>
					
					
					<h2>Departments</h2>
					
					<li>Page Six Chix<br />
					<div class="bi_article_desc">Tats, a jack, and a smallmouth? What’s not to love?</div></li>
					
					<li>Put-in<br />
					<div class="bi_article_desc">On regionalism and “smuggling”</div></li>
					
					<li>Rises<br />
					<div class="bi_article_desc">More Skeena feedback, and some really sweet skis</div></li>
					
					<li>Tailwater Weekend<br />
					<div class="bi_article_desc">Oregon’s Crooked River is more romantic than Paris </div><div class="bi_biline">
					By Chester Allen</div></li>
					
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<li>Scuddlebutt<br />
					<div class="bi_article_desc"><a href="http://www.drakemag.com/back-issues/2014/spring/1220-wild-vs-hatchery-steelhead.html">Northwest steelhead drama</a>, <a href="http://www.drakemag.com/back-issues/2014/spring/1215-ride-with-clyde-ix.html">Clyde heads east</a>, scotch tasting, California’s Klamath, <a href="http://www.drakemag.com/back-issues/2014/spring/1217-colorado-stoners-go-fishing.html">weed-buyer’s guide</a>, Connecticut’s survivor-strain browns, <a href="http://www.drakemag.com/back-issues/2014/spring/1216-kickstarded.html">Kickstarter fundraising</a>, Henry’s Fork Anglers, pike fishing in Maine, and more Montana access issues</div></li>

					<li>Tippets <br />
					<div class="bi_article_desc">A Driftless story, <a href="http://www.drakemag.com/back-issues/2014/spring/1218-i-went-steelheading-today.html">despondent steelheading</a>, spring stripers, Michigan by moonlight, a bonefish poem, a really nice rooster, fab fallfish, and ‘Glades tarpon </div> </li>
					
					<li>Redspread<br />
					<div class="bi_article_desc">St. Augustine, Florida: more redfish, fewer yankees</div><div class="bi_biline">
					By Mike Hodge</div></li>
					
					<li>Passport<br />
					<div class="bi_article_desc">Hanging with a holy man</div><div class="bi_biline">
					By Kym Goldsworthy</div></li>
					
					<li>Bugs<br />
					<div class="bi_article_desc">Yellow Sally of the South </div><div class="bi_biline">
					By Zach Matthews </div></li>
					
					<li>City Limits<br />
					<div class="bi_article_desc">A morning commute in Chicago</div><div class="bi_biline">
					By Timothy Adkins</div></li>
					
					<li>Rod Holders<br />
					<div class="bi_article_desc">Igor Linda, flyfishing’s man in Poland</div><div class="bi_biline">
					By Dave Karczynski</div></li>
					
					<li>Backcountry<br />
					<div class="bi_article_desc">Colorado’s Indian Peaks Wilderness Area</div><div class="bi_biline">
					By Steven Schweitzer</div></li>
					
					<li>Permit Page<br />
					<div class="bi_article_desc">I’m so much better than you are.</div><div class="bi_biline">
					By Jason Houston</div></li>
				</ul>
			</div>
			</div>
		</div>
EOT;



// catid = 332
	$winter_2013_slug = $link_slug . "332" . $link_slug_end;
	$winter_2013 = <<<EOT
		<h3>$bi_link $winter_2013_slug 2013 Winter Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2013-Winter-Issue_p_79.html"><img src="/images/back_issue/2013/2013_winter_issue.png" alt="Drake 2013 winter Issue"></a></div>
			<div class="bi_details">
				<ul>
					<h2>Features</h2>
					<li><a href="/back-issues/2013/winter/1180-when-is-a-wild-steelhead-no-longer-wild.html">Pride of the Quinault</a><br /><div class="bi_biline">Trey Combs</div><div class="bi_article_desc">The Quinault Indian Nation boasts some of the largest wild steelhead on earth. Now, if we can only keep them that way.</div></li>
					
					<li>The Lagoon Factor<br /><div class="bi_biline">Tom Bie</div><div class="bi_article_desc">Chasing snook and tarpon at Mexico’s Paradise Lodge, on the southern Yucatan coast.</div></li>
					
					<li>Good Times in Terrace<br />
					<div class="bi_biline">Photos by Drew Stoecklein<br />
					Captions by Tyler Maxwell</div><div class="bi_article_desc">Sketchy snowpack and a steelhead solution in British Columbia’s Coast Range.</div></li>
					
					<h2>Departments</h2>
					
					<li>Page Six Chix<div class="bi_article_desc">One brown, one red, one brook</div></li>
					
					<li>Put-in<div class="bi_article_desc">Feeling salty</div></li>
					
					<li>Rises<div class="bi_article_desc">Drake editors remap Michigan... and fail</div></li>
					
					<li>Scuddlebutt<div class="bi_article_desc"><a href="/back-issues/2013/winter/1191-groundhog-day-for-salmon.html">Groundhog Day for Salmon</a>, Magnuson-Stevens Act in action, your new 2014 fishing license, <a href="/back-issues/2013/winter/1177-ride-with-clyde-viii.html">Clyde’s sleepover with Puget Sound silvers</a>, and a New York apparel company takes fishing to the streets.</div></li>
					
					<li>Tailwater Weekend</strong><br/>
					<div class="bi_article_desc">Road-tripping to Arkansas’ White River</div> <br /><div class="bi_biline">Zach Matthews</li>
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<li>Redspread<br /><div class="bi_biline">Tosh Brown</div><div class="bi_article_desc">In Texas, it’s best not to piss-off the locals</div></li>

					<li>Tippets<div class="bi_article_desc"><a href="/back-issues/2013/winter/1179-spey%E2%80%99d.html">An unforgettable rant on spey rigs</a>, how to swallow a hopper whole, one great driftboat, Grand Bahamas bonefish, <a href="/back-issues/2013/winter/1178-naming-rights.html">summertime salmon for the mid-winter blues</a>, and hanging with old guys that rule</div>.</li>
					
					<li>Passport<br /><div class="bi_biline">Jako Lucas</div><div class="bi_article_desc">Master wrasse of the Indo-Pacific</div></li>
					
					<li>Bugs<br />
					<div class="bi_biline">Images by Nick Price, Lucas Carrol, and Corey Kruitbosch</div><div class="bi_article_desc">Midges. Because it’s winter, that’s why.</div> </li>
					
					<li>City Limits<br /><div class="bi_biline">Michael Israelson</div><div class="bi_article_desc">More strange for Colorado’s Front Range</div></li>
					
					<li>Rod Holders<br /><div class="bi_biline">Joshua Prestin</div><div class="bi_article_desc">Liminal lessons with Josh DeSmit</div></li>
					
					<li>Backcountry<br /><div class="bi_biline">Monte Burke</div><div class="bi_article_desc">Mangrove roots and 150 pounds of poon</div></li>
					
					<li>Permit Page<br /><div class="bi_article_desc">Mini-doppelganger gets its due.</div></li>
					
										
				</ul>
			</div>
			</div>
		</div>
EOT;



// catid = 331
	$fall_2013_slug = $link_slug . "331" . $link_slug_end;
	$fall_2013 = <<<EOT
		<h3>$bi_link</a> $fall_2013_slug 2013 Fall Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2013-Fall-Issue_p_76.html"><img src="/images/back_issue/2013/2013_fall_issue.png" alt="Drake 2013 fall Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Triple Threat</li>
					<li>The Homestead</li>			
					<li>(Still) Steelhead Paradise</li>					
					<li>Page Six Chix</li>					
					<li>Put-in</li>					
					<li>Rises</li>					
					<li>Scuddlebutt</li>					
					<li><a href="http://www.drakemag.com/back-issues/2013/fall/1141-idylwilde%E2%80%99s-wild-ride-the-fly-tying-company-that-lost%E2%80%94and-blogged%E2%80%94it-all.html">Idylwilde’s Wild Ride</a></li>
					<li>Carping Montana&acute;s Holter Reservoir</li>
					<li>Tippets</li>
					<li><a href="http://www.drakemag.com/back-issues/2013/fall/1142-still-steelhead-paradise-retracing-steps-in-the-skeena-valley.html">(Still) Steelhead Paradise</a></li>
					<li>Redspread</li>
					<li>Passport</li>					
				</ul>
				<ul>					
					<li>Chasing love in Eleuthera, chasing trout in the U.P., chasing stripers in NYC, and chasing bass in Florida. Also: loving jetties and sandwiches, but hating muskies (sorta).</li>
					<li><a href="http://www.drakemag.com/back-issues/2013/fall/1139-the-catch-landing-the-indiana-state-record-atlantic-salmon.html">The Catch</a></li>
					<li>Bugs</li>
					<li>City Limits</li>
					<li>Rod Holders</li>
					<li><a href="http://www.drakemag.com/back-issues/2013/fall/1140-the-fish-counters.html">The Fish Counters</a></li>
					<li>Backcountry</li>
					<li>Permit Page</li>
				</ul>
			</div>
		</div>
EOT;

// catid = 329
	$summer_2013_slug = $link_slug . "329" . $link_slug_end;
	$summer_2013 = <<<EOT
		<h3>$bi_link</a> $summer_2013_slug 2013 Summer Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2013-Summer-Issue_p_67.html"><img src="/images/back_issue/2013/2013_summer_issue.png" alt="Drake 2013 Summer Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>The Skeena Significance</li>
					<li>Lightning Rod Rivers</li>
					<li>The Promised Land</li>
					<li>Hyrids: A Dozen New Fish Species</li>
					<li><a href="/back-issues/2013/summer/1080-ride-with-clyde-vi-adventures-in-porn-camp.html">Clyde Goes Camping</a></li>
					<li>Searching for Snook</li>
					<li><a href="/back-issues/2013/summer/1082-on-the-water-music-venues-because-fishing-and-fiddling-go-well-together.html">Riverside Music</a></li>
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<li>Oregon Steelhead Guardians</li>
					<li>New Mexico&acute;s Costilla Creek</li>
					<li><a href="/back-issues/2013/summer/1081-dear-humpy-re-gaining-a-penchant-for-pinks.html">Hating on Humpies</a></li>
					<li><a href="/back-issues/2013/summer/1083-floating-with-barry.html">Floating the Yakima</a></li>
					<li>Redfish Relocation Program</li>
					<li>The Big Bonefish of Tahiti</li>
					<li><a href="/back-issues/2013/summer/1079-hexabridged-nighttime-is-the-right-time.html">Hexabridged: A Colossal Hex Hatch</a></li>
					<li>And more...</li>
				</ul>
				
			</div>
			</div>
		</div>
EOT;

// catid = 328 
	$spring_2013_slug = $link_slug . "328" . $link_slug_end;
	$spring_2013 = <<<EOT
		<h3>$bi_link</a> $spring_2013_slug 2013 Spring Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2013-Spring-Issue_p_57.html"><img src="/images/back_issue/2013/2013_spring_issue.png" alt="Drake 2013 Spring Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>New Water</li>
					<li>Return of the Clark Fork</li>
					<li><a href="/back-issues/2013/spring/1041-ride-with-clyde-v.html">Ride with Clyde V</a></li>
					<li>Fishing with Kids</li>
					<li>Zero Dark Thirty</li>
					<li>Extraction Blues</li>
					<li>Third Time"'"s the Charm</li>
					<li>Aesthetics of Death</li>
					<li>North Fork Blues</li>
					<li><a href="/back-issues/2013/spring/1042-bones-on-top.html">Bones on Top</a></li>
					<li><a href="/back-issues/2013/spring/1039-dick-mcguire.html">Dick McGuire</a></li>
					<li>Boulder Boat Works</li>
					<li>True Colors</li>
					<li>Adam DeBruin</li>
					<li>Get Ready to Rumble</li>
					<li>Injustice</li>
					<li>Buying the Farm</li>
					<li>The Way of the Spey</li>
				</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					
					<li><a href="/back-issues/2013/spring/1044-re-moved-to-alaska.html">Re-Moved to Alaska</a></li>
					<li>My Spot, Burned</li>
					<li>Southie Serendipity</li>
					<li><a href="/back-issues/2013/spring/1040-hotel-california.html">Hotel California</a></li>
					<li>Vernacular</li>
					<li>My Morning Tarpon</li>
					<li>Blue Bastard</li>
					<li>The Water Hazard</li>
					<li>Seeing Red</li>
					<li>American Muddler</li>
					<li>Chinook Winds</li>
					<li>The Crux</li>
					<li>The Salvation of Stoneflies</li>
					<li><a href="/back-issues/2013/spring/1043-richmond,-virginia.html">Richmond, Virginia</a></li>
					<li>Working Man</li>
					<li>Floating the Flathead</li>
					<li>Remembering Jon Ain</li>
					
				</ul>
				
			</div>
			</div>
		</div>
EOT;

// catid = 327 
	$winter_2012_slug = $link_slug . "327" . $link_slug_end;
	$winter_2012 = <<<EOT
		<h3>$bi_link</a> $winter_2012_slug 2012 Winter Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2013-Winter-Issue_p_53.html"><img src="/images/back_issue/2012/2012_winter_issue.png" alt="Drake 2013 Winter Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li><a href="/back-issues/2013/2013-winter/993-winter-2013-put-in.html">Winter 2013: Put-In</a></li>
					<li><a href="/featured-content/industry-scuttlebutt/994-undammed-rivers-revival.html">Undammed Rivers Revival</a></li>
					<li>Escape to Scorpion Reef</li>
					<li>Bill McMillan</li>
					<li>Ride with Clyde Part IV</li>
					<li>Beach Boys</li>
					<li>Medalhead</li>
					<li><a href="/featured-content/industry-scuttlebutt/981-tagging-alberto.html">Tagging Alberto</a></li>
					<li>Andrew Bennett</li>
					<li>7 Traits of the Tropical Dog</li>
					<li>Please Release Me</li>
					<li>Dark Knight of Bristol Bay</li>
					<li>Fishin The Boat</li>
					<li>Wind with a Change of Stripers</li>
					<li>Vice Dreams</li>
					<li>Chocolate Milk Creek</li>
									</ul>
				<span class="span3" tabindex="0">Show Full Contents</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<span class="span2" tabindex="0">Hide Contents</span>
				<div class="alert" >
					<ul>
					<li><a href="/back-issues/2013/2013-winter/982-take-me-back-to-the-heartland.html">Take Me Back to the Heartland</a></li>
					<li>High Water Smallies</li>
					<li>The Tunnel</li>
					<li>Crazy for Kunzha</li>
					<li>Night Dancer</li>
					<li>Two-Dollar Breakfast</li>
					<li>Redspread: Texas Tails</li>
					<li>Sasha and the Salmon</li>
					<li>Finding Grace in the Rainforest</li>
					<li>The Teachings of Tusheti</li>
					<li>Mister Baetis</li>
					<li><a href="/back-issues/2013/2013-winter/980-city-limits.html">Orlando, Florida</a></li>
					<li>Dan Rather Speaks</li>
					<li><a href="/back-issues/2013/2013-winter/995-accident-in-bear-gulch.html">Accident in Bear Gulch</a></li>
					<li>The Consulation Prize</li>
					</ul>
					
				</div>
			</div>
				
		</div>
EOT;
	// catid = 155 
	$fall_2012_slug = $link_slug . "155" . $link_slug_end;
	$fall_2012 = <<<EOT
		<h3>$bi_link</a> $fall_2012_slug 2012 Fall Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2012-Fall-Issue_p_22.html"><img src="/images/back_issue/2012/2012_fall_issue.png" alt="Drake 2012 Fall Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li><a href="/index.php?option=com_content&view=article&id=908:water-florida-bay-and-bonefish&catid=155&Itemid=153">Water, Florida Bay, and Bonefish</a></li>
					<li><a href="/index.php?option=com_content&view=article&id=906:deconstructing-dworshak&catid=155&Itemid=153">Deconstructing Dworshak</a></li>
					<li><a href="/index.php?option=com_content&view=article&id=905:seven-fishing-presidents&catid=155&Itemid=153">Seven fishing Presidents</a> </li>
					<li><a href="/index.php?option=com_content&view=article&id=907:ride-with-clyde-part-iii&catid=155&Itemid=153">Ride with Clyde Part III: Taking on the Oregon Trail</a> </li>
					<li>My Rod Collection</li>
					<li>Put-In: Autumn Light Is...</li>
				</ul>
					<ul>
					<li>Roadless Road Trips</li>
					<li>No Pasa Nada</li>
					<li><a href="/index.php?option=com_content&view=article&id=904:defining-dude&catid=155&Itemid=153">Defining Dude</a></li>
					<li><a href="/index.php?option=com_content&view=article&id=903:trading-up&catid=155&Itemid=153">Trading Up</a></li>
					<li>Passport: Bahamas</li>
					<li>Tailwater Weekend</li>
				</ul>
			</div>
		</div>
EOT;

	// catid = 153
	$summer_2012_slug = $link_slug . "153" . $link_slug_end;
	$summer_2012 = <<<EOT
		<h3>$bi_link</a> $summer_2012_slug 2012 Summer Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2012-Summer-Issue_p_42.html"><img src="/images/back_issue/2012/2012_summer_issue.png" alt="Drake 2012 Summer Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li><a href="/index.php?option=com_content&view=article&id=860:lighten-up-its-time-for-a-two-weight&catid=153&Itemid=154">Nine Two-Weight Rods</a></li>
					<li><a href="/index.php?option=com_content&view=article&id=861:canned-beer-7-standouts&catid=153&Itemid=154">Seven Canned Beers</a></li>
					<li><a href="/index.php?option=com_content&view=article&id=862:salmonfly-backup-plans&catid=153&Itemid=154">Four Salmonfly Solutions</a></li>
					<li>Carp in Wisconsin</li>
					<li><a href="/index.php?option=com_content&view=article&id=858:forever-in-bluegill&catid=153&Itemid=154">Bluegill in Ohio</a></li>
					<li>Steelhead in Washington</li>
					<li>Redfish in Louisiana</li>
					<li>Pink Alberts in Idaho</li>
				</ul>
					<ul>
					<li>Bonefish in the Bahamas</li>
					<li>Cutthroats in Wyoming</li>
					<li>Rainbows in New Mexico</li>
					<li>Musician Greg Brown</li>
					<li>A Brief History of Flats Boats</li>
					<li><a href="/index.php?option=com_content&view=article&id=859:tippets-the-other-side-of-bamboo&catid=153&Itemid=154">How Bamaboo Rods are like Herpes</a></li>
				</ul>
			</div>
		</div>
EOT;

	// catid ='152'
	$spring_2012_slug = $link_slug . "152" . $link_slug_end;
	$spring_2012 = <<<EOT
		<h3>$bi_link</a> $spring_2012_slug 2012 Spring Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2012-Spring-Issue_p_23.html"><img src="/images/back_issue/2012/2012_spring_issue.png" alt="Drake 2012 Spring Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li><a href="/index.php?option=com_content&view=article&id=812:the-secret-lives-of-salmon-and-gangsters&catid=152&Itemid=155">Salmon and gangsters</a></li>
					<li><a href="/index.php?option=com_content&view=article&id=808:ride-w-clyde&catid=152&Itemid=155">Riding with Clyde</a> </li>
					<li>Alaska steelhead </li>
					<li>Utah musky</li>
					<li><a href="/index.php?option=com_content&view=article&id=816:times-up-for-north-carolina&catid=152&Itemid=155">North Carolina</a> redfish</li>
					<li>Idaho bull trout</li>
					<li>Bhutan brown trout</li>
					<li>drakes on the Henry&rsquo;s Fork </li>
				</ul>
					<ul>
					<li>vampires on the Hoh</li>
					<li>Punta Allen permit</li>
					<li>Grand Rapids kings</li>
					<li>striper candy</li>
					<li>flyfishing photographers</li>
					<li>flyfishing limericks</li>
					<li>and why Bob White still paints</li>
				</ul>
			</div>
		</div>
EOT;
	
	// catid ='149'
	$winter_2011_slug = $link_slug . "149" . $link_slug_end;
	$winter_2011 = <<<EOT
		<h3>$bi_link</a> $winter_2012_slug 2011 Winter Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2012-Winter-Issue_p_21.html"><img src="/images/back_issue/2011/2011_winter_issue.png" alt="Drake 2011 Winter Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Belize bonefish </li>
					<li>South Carolina redfish </li>
					<li>ski pass for fisherman </li>
					<li>Texas smallmouth</li>
					<li>Felt Soul update</li>
					<li>permit rods</li>
					<li>winter steelheading</li>
				</ul>
					<ul>
					<li><a href="/index.php?option=com_content&view=article&id=762:occupy-duval-street&catid=149&Itemid=156">Occupy Duval Street</a> </li>
					<li>striper status</li>
					<li>New Jersey trout</li>
					<li>Panama</li>
					<li>scuds</li>
					<li>and camping with Justin Bieber</li>
				</ul>
			</div>
		</div>
EOT;
	
	// catid ='148'
	$fall_2011_slug = $link_slug . "148" . $link_slug_end;
	$fall_2011 = <<<EOT
		<h3>$bi_link</a> $fall_2011_slug 2011 Fall Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2011-Fall-Issue_p_18.html"><img src="/images/back_issue/2011/2011_fall_issue.png" alt="Drake 2011 Fall Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Thomas McGuane catches the last steelhead</li>
					<li>Rock Creek, MT</li>
					<li>Michigan carp</li>
					<li>Maine brookies</li>
					<li><a href="/index.php?option=com_content&view=article&id=702:big-trouble-on-little-mountain&catid=148&Itemid=158">Wyoming cutties</a></li>
					<li>Wisconsin pike</li>
					<li>New York Albies</li>
					<li>peacocks in the canals</li>
				</ul>
					<ul>
					<li>anchovies in the bay</li>
					<li>stripers in the surf</li>
					<li>Trey Combs</li>
					<li>Baja roosters</li>
					<li>midge over mayfly</li>
					<li>Bighorn over everything</li>
					<li>Charles Bukowski goes steelheading</li>
					<li>and David DiBenedetto goes crazy</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='143'
	$summer_2011_slug = $link_slug . "143" . $link_slug_end;
	$summer_2011 = <<<EOT
		<h3>$bi_link</a> $summer_2011_slug 2011 Summer Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2011-Summer-Issue_p_20.html"><img src="/images/back_issue/2011/2011_summer_issue.png" alt="Drake 2011 Summer Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Callibaetis</li>
					<li>Brazilian peacocks</li>
					<li>public lands</li>
					<li>permit flies</li>
					<li><a href="/index.php?option=com_content&view=article&id=640:saving-oregons-sandy-river&catid=143:summer&Itemid=159">steelheading Oregon</a></li>
					<li>flyfishing Denver</li>
					<li>two more emergers</li>
				</ul>
					<ul>
					<li><a href="/index.php?option=com_content&view=article&id=639:the-12-apostles-part-ii&catid=143&Itemid=159">three more apostles</a></li>
					<li><a href="/index.php?option=com_content&view=article&id=638:a-fishing-dog-the-life-and-times-of-trask&catid=143&Itemid=159">farewell to Trask</a></li>
					<li>hello to hoppers</li>
					<li>Michigan blue wings</li>
					<li>Florida redfish</li>
					<li>Dustin & Steve Huff</li>
					<li>and men who stare at trout</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='146'
	$spring_2011_slug = $link_slug . "146" . $link_slug_end;
	$spring_2011 = <<<EOT
		<h3>$bi_link</a> $spring_2011_slug 2011 Spring Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2011-Spring-Issue_p_19.html"><img src="/images/back_issue/2011/2011_spring_issue.png" alt="Drake 2011 Spring Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Michigan regs battle</li>
					<li><a href="/index.php?option=com_content&view=article&id=659:tippets-barracuda-blues&catid=146&Itemid=160">believing in barracuda</a></li>
					<li>flyfishing apps</li>
					<li>Glacier National Park</li>
					<li>North Platte</li>
					<li>Georgia coast</li>
				</ul>
					<ul>
					<li>Grand Canyon</li>
					<li>Alaskan steelhead</li>
					<li>yellow drakes</li>
					<li>John Turcot</li>
					<li>and one great letter from a Marine</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='136'
	$fall_winter_2010_slug = $link_slug . "136" . $link_slug_end;
	$fall_winter_2010 = <<<EOT
		<h3>$bi_link</a> $fall_winter_2010_slug 2010 Fall/Winter Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2010-FallWinter-Issue_p_15.html"><img src="/images/back_issue/2010/2010_fallwinter_issue.png" alt="Drake 2010 Fall/Winter Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Skeena Steelhead</li>
					<li>How to Spot a Striper Fisherman</li>
					<li>Snook vs. Snookie</li>
					<li>October Caddis</li>
					<li>N.C. Albies</li>
				</ul>
					<ul>
					<li>Fishing NYC</li>
					<li>Derek DeYoung</li>
					<li>Mag Bay</li>
					<li>and Saving Silver Creek</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='47'
	$winter_spring_2010_slug = $link_slug . "147" . $link_slug_end;
	$winter_spring_2010 = <<<EOT
		<h3>$bi_link</a> $winter_spring_2010_slug 2010 Winter/Spring Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2010-SpringSummer-Issue_p_16.html"><img src="/images/back_issue/2010/2010_winterspring_issue.png" alt="Drake 2010 Winter/Spring Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Montana Backcountry</li>
					<li>Stripers in the Surf</li>
					<li>Snook at Night</li>
					<li>Redfish at Dawn</li>
					<li>Oregon Steel</li>
					<li>Alaskan Coho</li>
				</ul>
					<ul>
					<li>Henry&rsquo;s Fork</li>
					<li>San Juan</li>
					<li>Skwalas</li>
					<li>Bluewings</li>
					<li>Bonefish</li>
					<li>and a Very Scary Fly Tyer</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='135'
	$winter_2009_slug = $link_slug . "135" . $link_slug_end;
	$winter_2009 = <<<EOT
		<h3>$bi_link</a> $winter_2009_slug 2009 Winter Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2009-Winter-Issue_p_14.html"><img src="/images/back_issue/2009/2009_winter_issue.png" alt="Drake 2009 Winter Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Recession Fish</li>
					<li>Birth of a Fly Tyer</li>
					<li>Winter Reds</li>
					<li>Arizona Bass</li>
				</ul>
					<ul>
					<li>Night Stripers</li>
					<li>Stoneflies</li>
					<li>and a Girlfriend&rsquo;s guide to Flyfishing</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='138'
	$fall_2009_slug = $link_slug . "138" . $link_slug_end;
	$fall_2009 = <<<EOT
		<h3>$bi_link</a> $fall_2009_slug 2009 Fall Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2009-Fall-Issue_p_12.html"><img src="/images/back_issue/2009/2009_fall_issue.png" alt="Drake 2009 Fall Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Steelhead</li>
					<li>Stripers</li>
					<li>Dorado</li>
					<li>Brown Trout</li>
					<li>Backcountry Snook</li>
				</ul>
					<ul>
					<li>Lowcountry Redfish</li>
					<li>Florida Bass</li>
					<li>Atlantic Salmon</li>
					<li>some cool bugs</li>
					<li>and why carp are so hot right now</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='41'
	$fall_2008_slug = $link_slug . "41" . $link_slug_end;
	$fall_2008 = <<<EOT
		<h3>$bi_link</a> $fall_2008_slug 2008 Fall Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><img src="/images/back_issue/2008/2008_fall_issue.png" alt="Drake 2008 Fall Issue"></div>
			<div class="bi_details">
				<ul>
					<li>Hoodoo Voodoo</li>
					<li>Fall Photo Gallery</li>
					<li>Grand Canyons</li>
					<li>Belize Beatdown</li>
					<li>Rod Holders: Bob Clay</li>
					<li>Bugs: Beetlemania</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='37'
	$spring_2008_slug = $link_slug . "37" . $link_slug_end;
	$spring_2008= <<<EOT
		<h3>$bi_link</a> $spring_2008_slug 2008 Spring Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><img src="/images/back_issue/2008/2008_spring_issue.png" alt="Drake 2008 Spring Issue"></div>
			<div class="bi_details">
				 <ul>
		<li>Water Dogs</li>
						<li>The Magical Mystery Tailwater Tour</li>
						<li>Faces of Flyfishing</li>
						<li>Close Calls</li>
						<li>Page Six Chix</li>
						<li>Gear Closet</li>
						</ul>
			</div>
		</div>

EOT;
	

	// catid ='20'
	$fall_2007_slug = $link_slug . "20" . $link_slug_end;
	$fall_2007 = <<<EOT
		<h3>$bi_link</a> $fall_2007_slug 2007 Fall Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2007-Fall-Issue_p_10.html"><img src="/images/back_issue/2007/2007_fall_issue.png" alt="Drake 2007 Fall Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Big Bass</li>
					<li>Angry Albies</li>
					<li>Horny Steelhead</li>
					<li>Ski-town trout</li>
					<li>Fiddler Crabs</li>
				</ul>
					<ul>
					<li>Socal Sharks</li>
					<li>Wingshooting</li>
					<li>Montana Backcountry</li>
					<li>and one unsolved mystery</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='40'
	$spring_2007_slug = $link_slug . "40" . $link_slug_end;
	$spring_2007 = <<<EOT
		<h3>$bi_link</a> $spring_2007_slug 2007 Spring Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2007-Spring-Issue_p_11.html"><img src="/images/back_issue/2007/2007_spring_issue.png" alt="Drake 2007 Spring Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>The B List - Beauty Below the Radar</li>
					<li>Oregon - Deschutes, Clackamas, Sandy</li>
					<li>The Florida Keys - A Trio of Top Tarpon Guides</li>
					<li>Northwest Steelhead</li>
					<li>Wisconsin Muskies</li>
					<li>Nantucket Stripers</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='39'
	$issue_2006_slug = $link_slug . "39" . $link_slug_end;
	$issue_2006 = <<<EOT
		<h3>$bi_link</a> $issue_2006_slug 2006 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2006-Issue_p_9.html"><img src="/images/back_issue/2006/2006_issue.png" alt="Drake 2006 Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>Baywatch: Bristol, Ascension, San Fran, Tampa, Chesepeake</li>
					<li>Seven Shooters: Picture Takers and Their Pictures</li>
					<li>Dorado on the Fly</li>
					<li>Carp on the Flats</li>
					<li>New Orleans on the Mend</li>
					<li>Smithhammer on the Road</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='38'
	$issue_2005_slug = $link_slug . "38" . $link_slug_end;
	$issue_2005 = <<<EOT
		<h3>$bi_link</a> $issue_2005_slug 2005 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><a href="http://shop.drakemag.com/2005-Issue_p_8.html"><img src="/images/back_issue/2005/2005_issue.png" alt="Drake 2005 Issue"></a></div>
			<div class="bi_details">
				<ul>
					<li>LIVE TROUT FISHING by Brad Bohen</li>
					<li>MOOD SWING by Tyler Hughen</li>
					<li>FLYFISHING, A TO Z by Tom Bie</li>
					<li>PAGE SIX CHICKS - 2 flyfishers, 2 photographers.</li>
					<li>RISES</li>
					<li>SCUTTLEBUTT</li>
				</ul>
					<ul>
					<li>TIPPETS - Essays</li>
					<li>BUGS - The dirt on damselflies</li>
					<li>RODHOLDERS - Robert Gorman of Green River Rods</li>
					<li>CITY LIMITS - Cutthroat of Seattle are calling</li>
					<li>GEAR CLOSET - Speywatch: Two-handed rod revolution</li>
				</ul>
			</div>
		</div>

EOT;
	

	// catid ='3'
	$issue_2003_slug = $link_slug . "3" . $link_slug_end;
	$issue_2003 = <<<EOT
		<h3>$bi_link</a> $issue_2003_slug 2003 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><img src="/images/back_issue/2003/2003_issue.png" alt="Drake 2003 Issue"></div>
			<div class="bi_details"> 
				 <ul>
					<li>P.J. O&rsquo; Rourke goes Cat Fishing</li>
					<li>John Geirach gets High</li>
					<li>Rick Ruoff Reminisces</li>
				</ul>
			</div>
		</div>
EOT;
	

	// catid ='4'
	$issue_2002_slug = $link_slug . "4" . $link_slug_end;
	$issue_2002 = <<<EOT
		<h3>$bi_link</a> $issue_2002_slug 2002 Contents</a></h3>
		<div class="bi_desc">
			<div class="bi_image"><img src="/images/back_issue/2002/2002_issue.png" alt="Drake 2002 Issue"></div>
			<div class="bi_details">
				<ul>
					<li>Stripers and</li>
					<li>Steelhead and</li>
					<li>Sex, Oh My</li>
				</ul>
			</div>
		</div>
EOT;
	
/*
	// catid ='5'
	$issue_2001_slug = $link_slug . "5" . $link_slug_end;
	$issue_2001 = <<<EOT

EOT;
	
	// catid ='7'
	$issue_1999_slug = $link_slug . "7" . $link_slug_end;
	$issue_1999 = <<<EOT

EOT;

	// catid ='8'
	$issue_1998_slug = $link_slug . "8" . $link_slug_end;
	$issue_1998 = <<<EOT

EOT;
*/
	
?>
