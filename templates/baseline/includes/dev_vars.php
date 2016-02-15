		<link rel="stylesheet" type="text/css" href="/templates/baseline/css/dddropdownpanel.css" />
		<script type="text/javascript" src="/templates/baseline/js/dddropdownpanel.js">
		/***********************************************
		* DD Drop Down Panel- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
		* This notice MUST stay intact for legal use
		* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
		***********************************************/
		</script>
		<style type="text/css">
		#dropdownpanel{z-index: 1001;}
		#dev_vars{padding: 0 60px;}
		#dev_vars *,
		#dev_vars p{color: #ccc !important;}
		.ddpanel .ddpaneltab {
		  margin-right: 644px !important;
		  position: absolute;
		}
		.ddpaneltab{left:400px;}
		/* CSS Document */
		*{margin:0px; padding:0px; font-family:Arial, Verdana, Helvetica, sans-serif; color: #444;}
		td{vertical-align:top; padding:0 20px 0 0;}

		#dev_vars span.dev_lbl{font-size: 10px; color: #666 !important;}
		#dev_vars span.dev_value{font-size: 10px; color: #fff !important;}
		</style>

		<div id="mypanel" class="ddpanel">
		<div id="mypanelcontent" class="ddpanelcontent">

		<p style="padding:10px 50px;">

		<div id="dev_vars">

		<a src="#" class="closepanel" style="">[close]</a>
		<br />
		<table>
			<tr>
				<td>
		 <?php
			echo '<span class="dev_lbl">$itemid= </span><span class="dev_value">' . $itemid. '</span><br />';
			echo '<span class="dev_lbl">$var_parent_id= </span><span="dev_value">' . $var_parent_id . '</span><br />';
			echo '<span class="dev_lbl">$var_layout_type= </span><span class="dev_value">' . $var_layout_type. '</span><br />';
			echo '<span class="dev_lbl">$var_menu_anchor_title= </span><span class="dev_value">' . $var_menu_anchor_title. '</span><br />';
			echo '<span class="dev_lbl">$var_page_title= </span><span class="dev_value">' . $var_page_title. '</span><br />';
			echo '<span class="dev_lbl">$var_page_heading= </span><span class="dev_value">' . $var_page_heading. '</span><br />';
			echo '<span class="dev_lbl">$var_pageclass_sfx= </span><span class="dev_value">' . $var_pageclass_sfx. '</span><br />';
			echo '<span class="dev_lbl">$var_menu_meta_description= </span><span class="dev_value">' . $var_menu_meta_description. '</span><br />';
			echo '<span class="dev_lbl">$var_menu_meta_keywords= </span><span class="dev_value">' . $var_menu_meta_keywords. '</span><br />';
			echo '<span class="dev_lbl">$var_num_intro_articles= </span><span class="dev_value">' . $var_num_intro_articles. '</span><br />';
			echo '<span class="dev_lbl">$var_option= </span><span class="dev_value">' . $var_option. '</span><br />';
			echo '<span class="dev_lbl">$var_view= </span><span class="dev_value">' . $var_view. '</span><br />';
			echo '<span class="dev_lbl">$var_layout= </span><span class="dev_value">' . $var_layout. '</span><br />';
			echo '<span class="dev_lbl">$var_id= </span><span class="dev_value">' . $var_id. '</span><br />';
			echo '<hr />';
		?>
		</td>
		<td>
		<?php
			echo '<span class="dev_lbl">$var_show_category_heading_title_text = </span><span class="dev_value">' . $var_show_category_heading_title_text . '</span><br />';
			echo '<span class="dev_lbl">$var_show_category_title = </span><span class="dev_value">' . $var_show_category_title . '</span><br />';
			echo '<span class="dev_lbl">$var_show_description = </span><span class="dev_value">' . $var_show_description . '</span><br />';
			echo '<span class="dev_lbl">$var_show_description_image = </span><span class="dev_value">' . $var_show_description_image . '</span><br />';
			echo '<span class="dev_lbl">$var_maxLevel = </span><span class="dev_value">' . $var_maxLevel . '</span><br />';
			echo '<span class="dev_lbl">$var_show_empty_categories = </span><span class="dev_value">' . $var_show_empty_categories . '</span><br />';
			echo '<span class="dev_lbl">$var_show_no_articles = </span><span class="dev_value">' . $var_show_no_articles . '</span><br />';
			echo '<span class="dev_lbl">$var_show_subcat_desc = </span><span class="dev_value">' . $var_show_subcat_desc . '</span><br />';
			echo '<span class="dev_lbl">$var_show_cat_num_articles = </span><span class="dev_value">' . $var_show_cat_num_articles . '</span><br />';
			echo '<span class="dev_lbl">$var_show_cat_tags = </span><span class="dev_value">' . $var_show_cat_tags . '</span><br />';
			echo '<span class="dev_lbl">$var_page_subheading = </span><span class="dev_value">' . $var_page_subheading . '</span><br />';
			echo '<span class="dev_lbl">$var_num_leading_articles = </span><span class="dev_value">' . $var_num_leading_articles . '</span><br />';
			echo '<span class="dev_lbl">$var_num_intro_articles = </span><span class="dev_value">' . $var_num_intro_articles . '</span><br />';
			echo '<span class="dev_lbl">$var_num_columns = </span><span class="dev_value">' . $var_num_columns . '</span><br />';
			echo '<span class="dev_lbl">$var_num_links = </span><span class="dev_value">' . $var_num_links . '</span><br />';
		?>
		</td>
		<td>
		<?php
			echo '<span class="dev_lbl">$var_multi_column_order = </span><span class="dev_value">' . $var_multi_column_order . '</span><br />';
			echo '<span class="dev_lbl">$var_show_subcategory_content = </span><span class="dev_value">' . $var_show_subcategory_content . '</span><br />';
			echo '<span class="dev_lbl">$var_orderby_pri = </span><span class="dev_value">' . $var_orderby_pri . '</span><br />';
			echo '<span class="dev_lbl">$var_orderby_sec = </span><span class="dev_value">' . $var_orderby_sec . '</span><br />';
			echo '<span class="dev_lbl">$var_order_date = </span><span class="dev_value">' . $var_order_date . '</span><br />';
			echo '<span class="dev_lbl">$var_show_pagination = </span><span class="dev_value">' . $var_show_pagination . '</span><br />';
			echo '<span class="dev_lbl">$var_show_pagination_results = </span><span class="dev_value">' . $var_show_pagination_results . '</span><br />';
			echo '<span class="dev_lbl">$var_show_title = </span><span class="dev_value">' . $var_show_title . '</span><br />';
			echo '<span class="dev_lbl">$var_link_titles = </span><span class="dev_value">' . $var_link_titles . '</span><br />';
			echo '<span class="dev_lbl">$var_show_intro = </span><span class="dev_value">' . $var_show_intro . '</span><br />';
			echo '<span class="dev_lbl">$var_info_block_position = </span><span class="dev_value">' . $var_info_block_position . '</span><br />';
			echo '<span class="dev_lbl">$var_show_category = </span><span class="dev_value">' . $var_show_category . '</span><br />';
			echo '<span class="dev_lbl">$var_link_category = </span><span class="dev_value">' . $var_link_category . '</span><br />';
			echo '<span class="dev_lbl">$var_show_parent_category = </span><span class="dev_value">' . $var_show_parent_category . '</span><br />';
			echo '<span class="dev_lbl">$var_link_parent_category = </span><span class="dev_value">' . $var_link_parent_category . '</span><br />';
			echo '<span class="dev_lbl">$var_show_author = </span><span class="dev_value">' . $var_show_author . '</span><br />';
		?>
		</td>
		<td>
		<?php
			echo '<span class="dev_lbl">$var_link_author = </span><span class="dev_value">' . $var_link_author . '</span><br />';
			echo '<span class="dev_lbl">$var_show_create_date = </span><span class="dev_value">' . $var_show_create_date . '</span><br />';
			echo '<span class="dev_lbl">$var_show_modify_date = </span><span class="dev_value">' . $var_show_modify_date . '</span><br />';
			echo '<span class="dev_lbl">$var_show_publish_date = </span><span class="dev_value">' . $var_show_publish_date . '</span><br />';
			echo '<span class="dev_lbl">$var_show_item_navigation = </span><span class="dev_value">' . $var_show_item_navigation . '</span><br />';
			echo '<span class="dev_lbl">$var_show_vote = </span><span class="dev_value">' . $var_show_vote . '</span><br />';
			echo '<span class="dev_lbl">$var_show_readmore = </span><span class="dev_value">' . $var_show_readmore . '</span><br />';
			echo '<span class="dev_lbl">$var_show_readmore_title = </span><span class="dev_value">' . $var_show_readmore_title . '</span><br />';
			echo '<span class="dev_lbl">$var_show_icons = </span><span class="dev_value">' . $var_show_icons . '</span><br />';
			echo '<span class="dev_lbl">$var_show_print_icon = </span><span class="dev_value">' . $var_show_print_icon . '</span><br />';
			echo '<span class="dev_lbl">$var_show_email_icon = </span><span class="dev_value">' . $var_show_email_icon . '</span><br />';
			echo '<span class="dev_lbl">$var_show_hits = </span><span class="dev_value">' . $var_show_hits . '</span><br />';
			echo '<span class="dev_lbl">$var_show_noauth = </span><span class="dev_value">' . $var_show_noauth . '</span><br />';
			echo '<span class="dev_lbl">$var_show_feed_link = </span><span class="dev_value">' . $var_show_feed_link . '</span><br />';
			echo '<span class="dev_lbl">$var_feed_summary = </span><span class="dev_value">' . $var_feed_summary . '</span><br />';
			echo '<span class="dev_lbl">$var_menu-anchor_title = </span><span class="dev_value">' . $var_menu_anchor_title . '</span><br />';
		?>
		</td>
		<td>
		<?php
			echo '<span class="dev_lbl">$var_menu-anchor_css = </span><span class="dev_value">' . $var_menu_anchor_css . '</span><br />';
			echo '<span class="dev_lbl">$var_menu_image = </span><span class="dev_value">' . $var_menu_image . '</span><br />';
			echo '<span class="dev_lbl">$var_menu_text = </span><span class="dev_value">' . $var_menu_text . '</span><br />';
			echo '<span class="dev_lbl">$var_page_title = </span><span class="dev_value">' . $var_page_title . '</span><br />';
			echo '<span class="dev_lbl">$var_show_page_heading = </span><span class="dev_value">' . $var_show_page_heading . '</span><br />';
			echo '<span class="dev_lbl">$var_page_heading = </span><span class="dev_value">' . $var_page_heading . '</span><br />';
			echo '<span class="dev_lbl">$var_pageclass_sfx = </span><span class="dev_value">' . $var_pageclass_sfx . '</span><br />';
			echo '<span class="dev_lbl">$var_menu-meta_description = </span><span class="dev_value">' . $var_menu_meta_description . '</span><br />';
			echo '<span class="dev_lbl">$var_menu-meta_keywords = </span><span class="dev_value">' . $var_menu_meta_keywords . '</span><br />';
			echo '<span class="dev_lbl">$var_robots = </span><span class="dev_value">' . $var_robots . '</span><br />';
			echo '<span class="dev_lbl">$var_secure = </span><span class="dev_value">' . $var_secure . '</span><br />';
		?>
		</td></tr></table>
		</div>

		</p>
		<br style="clear: left" />

		</div>
		<div id="mypaneltab" class="ddpaneltab">
		<a href="#"><span>Toggle</span></a>
		</div>

		</div>



		 

