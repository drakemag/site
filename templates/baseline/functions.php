<?php
	//truncates to character length
	if (!function_exists("admiralsTruncate_blog")) {
		function admiralsTruncate_blog($string_blog, $length_blog){
			settype($string_blog, 'string');
			settype($length_blog, 'integer');
			settype($output_blog, 'string');
			for($a_blog = 0; $a_blog < $length_blog AND $a_blog < strlen($string_blog); $a_blog++){
				$output_blog .= $string_blog[$a_blog];
			}
			return($output_blog);
		}
	}
	
	// this cleans-up all invalid characters
	if (!function_exists("htmlallentities")) {
		function htmlallentities($string_in){
		  $resulting_string = '';
		  $strlen = strlen($string_in);
		  for($i=0; $i<$strlen; $i++){
			$byte = ord($string_in[$i]);
			if($byte < 128) // 1-byte char
			  $resulting_string .= $string_in[$i];
			elseif($byte < 192); // invalid utf8
			elseif($byte < 224) // 2-byte char
			  $resulting_string .= '&#'.((63&$byte)*64 + (63&ord($string_in[++$i]))).';';
			elseif($byte < 240) // 3-byte char
			  $resulting_string .= '&#'.((15&$byte)*4096 + (63&ord($string_in[++$i]))*64 + (63&ord($string_in[++$i]))).';';
			elseif($byte < 248) // 4-byte char
			  $resulting_string .= '&#'.((15&$byte)*262144 + (63&ord($string_in[++$i]))*4096 + (63&ord($string_in[++$i]))*64 + (63&ord($string_in[++$i]))).';';
		  }
		  return $resulting_string;
		}
	}
	
	// trunctates to whole words
	//$this ="";
	if (!function_exists("truncate_to_word")) {
		function truncate_to_word($trunc_string,$trunc_word_length){
			$content_truncated_word = substr($trunc_string, 0, strrpos( substr( $trunc_string, 0, $trunc_word_length), ' ' ) );
			return ($content_truncated_word);
		}
	}
		
	if (!function_exists("display_all_issues")) {
		function display_all_issues(){
			include('templates/baseline/includes/back_issue_contents3.php');
			echo "<ul id=\"display_all_issues\">";
				echo "<li>" . $winter_2015 . "</li>";		// 2015 winter
				echo "<li>" . $fall_2015 . "</li>";		// 2015 fall
				echo "<li>" . $summer_2015 . "</li>";		// 2015 summer
				echo "<li>" . $spring_2015 . "</li>";		// 2015 spring
				echo "<li>" . $winter_2014 . "</li>";		// 2014 Winter
				echo "<li>" . $fall_2014 . "</li>";		// 2014 Fall
				echo "<li>" . $summer_2014 . "</li>";		// 2014 Summer
				echo "<li>" . $spring_2014 . "</li>";		// 2014 Spring
				echo "<li>" . $winter_2013 . "</li>";		// 2013 Winter
				echo "<li>" . $fall_2013 . "</li>";		// 2013 Fall
				echo "<li>" . $summer_2013 . "</li>";		// 2013 Summer
				echo "<li>" . $spring_2013 . "</li>";		// 2013 Spring
				echo "<li>" . $winter_2012 . "</li>";		// 2012 Winter
				echo "<li>" . $fall_2012 . "</li>";		// 2012 Fall
				echo "<li>" . $summer_2012 . "</li>";		// 2012 Summer
				echo "<li>" . $spring_2012 . "</li>";		// 2012 Spring
				echo "<li>" . $winter_2011 . "</li>";		// 2011 Winter
				echo "<li>" . $fall_2011 . "</li>";		// 2011 Fall
				echo "<li>" . $summer_2011 . "</li>";		// 2011 Summer
				echo "<li>" . $spring_2011 . "</li>";		// 2011 Spring
				echo "<li>" . $fall_winter_2010 . "</li>";		// 2010 Fall/Winter
				echo "<li>" . $winter_spring_2010 . "</li>";		// 2010 Winter/Spring
				echo "<li>" . $winter_2009 . "</li>";		// 2009 Winter
				echo "<li>" . $fall_2009 . "</li>";		// 2009 Fall
				echo "<li>" . $fall_2008 . "</li>";		// 2008 Fall
				echo "<li>" . $spring_2008 . "</li>";		// 2008 Spring
				echo "<li>" . $fall_2007 . "</li>";		// 2007 Fall
				echo "<li>" . $spring_2007 . "</li>";		// 2007 Spring
				echo "<li>" . $issue_2006 . "</li>";		// 2006
				echo "<li>" . $issue_2005 . "</li>";		// 2005
				echo "<li>" . $issue_2003 . "</li>";		// 2003
				echo "<li>" . $issue_2002 . "</li>";		// 2002
				//echo "<li>" . $issue_2001 . "</li>";		// 2001
				//echo "<li>" . $issue_1999 . "</li>";		// 1999
				//echo "<li>" . $issue_1998 . "</li>";		// 1998 
			echo "</ul>";
		}
	}
	
	if (!function_exists("show_issue_TOC")) {
		function show_issue_TOC($cAtiD_str){
			$trigger_arrow = 1;
			include('templates/baseline/includes/back_issue_contents3.php');
			switch ($cAtiD_str){
				case 350:
					echo $winter_2015;		// 2015 winter
					break;
				case 349:
					echo $fall_2015;		// 2015 fall
					break;
				case 346:
					echo $summer_2015;		// 2015 summer
					break;
				case 345:
					echo $spring_2015;		// 2015 spring
					break;
				case 338:
					echo $winter_2014;		// 2014 Winter
					break;
				case 337:
					echo $fall_2014;		// 2014 Fall
					break;
				case 335:
					echo $summer_2014;		// 2014 Summer
					break;
				case 334:
					echo $spring_2014;		// 2014 Spring
					break;
				case 332:
					echo $winter_2013;		// 2013 Winter
					break;
				case 331:
					echo $fall_2013;		// 2013 Fall
					break;
				case 329:
					echo $summer_2013;		// 2013 Summer
					break;
				case 328:
					echo $spring_2013;		// 2013 Spring
					break;
				case 327:
					echo $winter_2012;		// 2012 Winter
					break;
				case 155:
					echo $fall_2012;		// 2012 Fall
					break;
				case 153:
					echo $summer_2012;		// 2012 Summer
					break;
				case 152:
					echo $spring_2012;		// 2012 Spring
					break;
				case 149:
					echo $winter_2011;		// 2011 Winter
					break;
				case 148:
					echo $fall_2011;		// 2011 Fall
					break;
				case 143:
					echo $summer_2011;		// 2011 Summer
					break;
				case 146:
					echo $spring_2011;		// 2011 Spring
					break;
				case 136:
					echo $fall_winter_2010;		// 2010 Fall/Winter
					break;
				case 47:
					echo $winter_spring_2010;		// 2010 Winter/Spring
					break;
				case 135:
					echo $winter_2009;		// 2009 Winter
					break;
				case 138:
					echo $fall_2009;		// 2009 Fall
					break;
				case 41:
					echo $fall_2008;		// 2008 Fall
					break;
				case 37:
					echo $spring_2008;		// 2008 Spring
					break;
				case 20:
					echo $fall_2007;		// 2007 Fall
					break;
				case 40:
					echo $spring_2007;		// 2007 Spring
					break;
				case 39:
					echo $issue_2006;		// 2006
					break;
				case 38:
					echo $issue_2005;		// 2005
					break;
				case 3:
					echo $issue_2003;		// 2003
					break;
				case 4:
					echo $issue_2002;		// 2002
					break;
				case 5:
					echo $issue_2001;		// 2001
					break;
				case 7:
					echo $issue_1999;		// 1999
					break;
				case 8:
					echo $issue_1998;		// 1998 
			 } 
					
		}
	}


	if (!function_exists("show_issue_breadcrumbs")) {
		function show_issue_breadcrumbs($cAtiD_str){
			$remove_contents = 1;
			include('templates/drake/includes/back_issue_contents3.php');
			switch ($cAtiD_str){
				case 350:
					echo $winter_2015;		// 2015 winter
					break;
				case 349:
					echo $fall_2015;		// 2015 fall
					break;
				case 346:
					echo $summer_2015;		// 2015 summer
					break;
				case 345:
					echo $spring_2015;		// 2015 spring
					break;
				case 338:
					echo $winter_2014;		// 2014 Winter
					break;
				case 337:
					echo $fall_2014;		// 2014 Fall
					break;
				case 335:
					echo $summer_2014;		// 2014 Summer
					break;
				case 334:
					echo $spring_2014;		// 2014 Spring
					break;
				case 332:
					echo $winter_2013;		// 2013 Winter
					break;
				case 331:
					echo $fall_2013;		// 2013 Fall
					break;
				case 329:
					echo $summer_2013;		// 2013 Summer
					break;
				case 328:
					echo $spring_2013;		// 2013 Spring
					break;
				case 327:
					echo $winter_2012;		// 2012 Winter
					break;
				case 155:
					echo $fall_2012;		// 2012 Fall
					break;
				case 153:
					echo $summer_2012;		// 2012 Summer
					break;
				case 152:
					echo $spring_2012;		// 2012 Spring
					break;
				case 149:
					echo $winter_2011;		// 2011 Winter
					break;
				case 148:
					echo $fall_2011;		// 2011 Fall
					break;
				case 143:
					echo $summer_2011;		// 2011 Summer
					break;
				case 146:
					echo $spring_2011;		// 2011 Spring
					break;
				case 136:
					echo $fall_winter_2010;		// 2010 Fall/Winter
					break;
				case 47:
					echo $winter_spring_2010;		// 2010 Winter/Spring
					break;
				case 135:
					echo $winter_2009;		// 2009 Winter
					break;
				case 138:
					echo $fall_2009;		// 2009 Fall
					break;
				case 41:
					echo $fall_2008;		// 2008 Fall
					break;
				case 37:
					echo $spring_2008;		// 2008 Spring
					break;
				case 20:
					echo $fall_2007;		// 2007 Fall
					break;
				case 40:
					echo $spring_2007;		// 2007 Spring
					break;
				case 39:
					echo $issue_2006;		// 2006
					break;
				case 38:
					echo $issue_2005;		// 2005
					break;
				case 3:
					echo $issue_2003;		// 2003
					break;
				case 4:
					echo $issue_2002;		// 2002
					break;
				case 5:
					echo $issue_2001;		// 2001
					break;
				case 7:
					echo $issue_1999;		// 1999
					break;
				case 8:
					echo $issue_1998;		// 1998 
			 } 
					
		}
	}

 
	if (!function_exists("closetags")) {
		function closetags($html) {
		  #put all opened tags into an array
		  preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		  $openedtags = $result[1];   #put all closed tags into an array
		  preg_match_all('#</([a-z]+)>#iU', $html, $result);
		  $closedtags = $result[1];
		  $len_opened = count($openedtags);
		  # all tags are closed
		
		  if (count($closedtags) == $len_opened) {
			return $html;
		  }
		
		  $openedtags = array_reverse($openedtags);
		
		  # close tags
		  for ($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)){
			  $html .= '</'.$openedtags[$i].'>';
			} else {
			  unset($closedtags[array_search($openedtags[$i], $closedtags)]);    }
		  }  return $html;
		}  
	}

	if (!function_exists("byline")) {
		function byline($this_parent_id,$this_item_author){
		/* BYLINES 20121204 RGS *************************************************/

		$non_byline_list = array(
			160, //video
			165, //error pages
			167, //DD
			168, //dealers
			158, //general
			164, //contest
			35, //IS
			26, //FSF
			1 // equals = no parentid
		);

	if (!in_array($this_parent_id,$non_byline_list)){
		
		switch ($this_item_author) {
	    case "63":
	        echo "
				<div class=\"byline\">
					<div class=\"left\"><img src=\"/images/bios/tom.jpg\" alt=\"Tom Bie\" /></div>
					<p><span class=\"author_name\"><strong>Tom Bie, Editor</strong></span> is the founder, editor, and publisher of The Drake. He started the magazine in 1998 as an annual newsprint publication based in Jackson Hole, Wyoming. He then moved it to Steamboat, Colorado (1999), Boulder, Colorado (2001), and San Clemente, California (2004), as he took jobs as managing editor at Paddler, Senior Editor at Skiing, and Editor-in-Chef at Powder, respectively. Tom and The Drake are now both based in Fort Collins, Colorado, where The Drake is finally become all grows up to a quarterly magazine.</p>
				</div>		
			";
	        break;
	    case "11690":
	        echo '
				<div class="byline">
					<div class="left"><img src="/images/bios/geoff_bio_pic.jpg" alt="Geoff Mueller" /></div>
					<p><span class="author_name"><strong>Geoff Mueller</strong></span> is senior editor at The Drake. He lives in Fort Collins, Colorado. Follow him: <a href="https://twitter.com/search?q=thedrakemagazine&src=typd" target="_blank">@thedrakemagazine</a>, <a href="https://instagram.com/geoffmonline/" target="_blank">@geoffmonline</a>.<br /><br /><br /></p>
				</div>
			';
	        break;
	    case "66":
	        echo '
				<div class="byline">
					<div class="left"><img src="/images/bios/smithhammer.jpg" alt="Bruce Smithhammer " /></div>
					<p><span class="author_name"><strong>Bruce Smithhammer </strong></span>. Being completely un-employable in any normal capacity, despite repeated attempts at doing so, Smithhammer is eternally grateful to those who continue to support his freelance work. In his spare time, he focuses on fishing and hunting for non-native species. He currently has his hands full readying the bunker for the Zombie Apocalypse.</p>
				</div>
			';
	        break;
	    case "10757":
	        echo '
				<div class="byline">
					<div class="left"><img src="/images/bios/will_rice.jpg" alt="Will Rice" /></div>
					<p><span class="author_name"><strong>Will Rice</strong></span> is a freelance journalist and a contributing editor for the Drake Magazine. He\'s written for the Denver Post, Salt Water Fly Fishing (RIP), FlyFish Journal, and he is a regular contributor to Angling Trade Magazine. Will grew up fishing for small mouth and large mouth bass in upstate NY but has lived in Colorado for the past 15 years.</p>

					<p>"I have always had one guiding philosophy for my outdoor writing: I am not alone," said Will.  "If I think something is cool or have a kick ass experience, there are a ton of other folks who will be interested in hearing about it and best case entertained - and possibly even inspired to get out and do something new. Pretty simple stuff."</p>
					
					<p>If you want to send any hate mail directly to the author he can be reached at <a href="mailto:williamhrice72@yahoo.com">williamhrice72@yahoo.com</a>.
					</p>
				</div>
			';
	        break;
	    case "16919":
	        echo '
				<div class="byline">
					<div class="left"><img src="/images/bios/abarbour.jpg" alt="Andrew Barbour" /></div>
					<p><span class="author_name"><strong>Andrew Barbour</strong></span> is a PhD student at the University of Florida.  After growing up fly-fishing on Montana’s Blackfoot river, Andrew transitioned to saltwater angling when he moved to Florida for his graduate research.  His work is focused on the role of juvenile nursery habitats in maintaining fisheries, using the common snook as a model species in Charlotte Harbor, Florida.  </p>
					<p>If you have any comments, questions, or ideas for future articles, Andrew can be reached at <a href="mailto:barbour_andrew@hotmail.com">barbour_andrew@hotmail.com</a>.</p>
				</div>
			';
	        break;
	    case "107":
	        echo '
				<div class="byline">
					<div class="left"><img src="/images/bios/alex_c.jpg" alt="Alex Cerveniak" /></div>
					<p><span class="author_name"><strong>Alex Cerveniak</strong></span> is a freelance writer/photographer and regular contributor to The Drake magazine.  Alex is a former Editor at MidCurrent and Hatches Magazine.  He has contributed words and photos to various online and print outdoor media outlets, e-books, businesses, and organizations.  Alex has a degree in Environmental Science and is an active member of several conservation groups.  He lives in northern Michigan, but spent the latter half of his twenties fishing in upstate New York.  Outside of the obvious, Alex enjoys hunting, camping, hiking, greasy pizza you have to fold to pick up, and his wife’s pineapple upside-down cake.</p>
					<p>If you have any comments, questions, or ideas for future articles, Alex can be reached at <a href="mailto:cerveniak@gmail.com.com">cerveniak@gmail.com</a>.</p>
				</div>
			';
	        break;
	    case "20327":
	        echo '
				<div class="byline">
					<div class="left"><img src="/images/bios/wedeking.jpg" alt="Brett Wedeking" /></div>
					<p>West coast native <span class="author_name"><strong>Brett Wedeking</strong></span> claimed the titles: shop jockey, custom tyer, guide, and beer snob... at one time or another. He spends most of his time chasing steelhead and daydreaming about permit, so he doesn\'t catch very many fish. Currently, he\'s considering a move to Oregon, where he can get skunked before work.</p>
				</div>
			';
	        break;

		}}; 
	/**** end bylines ************************************************/
	}
}


?>