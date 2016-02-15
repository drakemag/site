<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php 

	if ( (bool)$this->params->get( 'include_jquery', true ) )
 		JHtml::_('jquery.framework');
		
	if ( (bool)$this->params->get( 'include_bootstrap', true ) )
		JHtml::_('bootstrap.framework');
 	
	$document = JFactory::getDocument();
	$document->addScript( 'components/com_storelocator/assets/map.js' );
	if($this->include_css) 
	{
		switch($this->layout_theme)
		{
			case 1:
				$document->addStyleSheet('components/com_storelocator/assets/styles.css');
				break;
			case 0:
			default:	
				$document->addStyleSheet('components/com_storelocator/assets/styles_bottom.css');
		}
		
	}
	
	if ($this->params->get( 'menu-meta_description', '' )) 	$document->setMetaData('description', 	$this->params->get( 'menu-meta_description', '' ));
	if ($this->params->get( 'menu-meta_keywords', '' )) 	$document->setMetaData('keywords', 		$this->params->get( 'menu-meta_keywords', '' ));
	if ($this->params->get( 'robots', '' )) 					$document->setMetaData('robots', 		$this->params->get( 'robots', '' ));
	
	
	// Add Javascript  
	$document->addScriptDeclaration("
		
		var NO_RESULTS			= \"". JText::_( 'NO_RESULTS' ) ."\";
		var SEARCH_RESULTS_FOR 	= \"". JText::_( 'SEARCH_RESULTS_FOR' ) ."\";
		var MI 					= \"". JText::_( 'MI' ) ."\";
		var KM 					= \"". JText::_( 'KM' ) ."\";
		var PHONE 				= \"". JText::_( 'PHONE' ) ."\";
		var WEBSITE 			= \"". JText::_( 'WEBSITE' ) ."\";
		var GET_DIRECTIONS 		= \"". JText::_( 'GET_DIRECTIONS' ) ."\";
		var FROM_ADDRESS 		= \"". JText::_( 'FROM_ADDRESS' ) ."\";
		var GO 					= \"". JText::_( 'GO' ) ."\";
		var DIR_EXAMPLE 		= \"". JText::_( 'DIR_EXAMPLE' ) ."\";
		var VISIT_SITE 			= \"". JText::_( 'VISIT_SITE' ) ."\";
		var CATEGORY 			= \"". JText::_( 'CATEGORY' ) ."\";
		var TAGS 				= \"". JText::_( 'TAGS' ) ."\";
		var DID_YOU_MEAN 		= \"". JText::_( 'DID_YOU_MEAN' ) ."\";
		var LIMITED_RESULTS 	= \"". str_replace( '%s', substr($this->params->get('search_limit_period', '-1 Day'), 1), JText::_( 'LIMITED_RESULTS' )) ."\";
		var JOOMLA_ROOT			= \"". JURI::root( true ) ."\";
		var COM_STORELOCATOR_GEO_SEARCH = \"". JText::_( 'COM_STORELOCATOR_GEO_SEARCH' ) ."\";
		
		var show_all_onload 		= ". $this->params->get( 'show_all_onload', '1' ) .";
		var map_auto_zoom 			= ". ($this->params->get( 'map_auto_zoom', '1' )?'true':'false') .";  
		var map_directions  		= ". ($this->params->get( 'map_directions', '1' )?'true':'false') .";
		var google_suggest 			= ". ($this->params->get( 'google_suggest', '1' )?'true':'false') ."; 
		var map_center_lat 			= ". $this->params->get( 'map_center_lat', 40  ) .";
		var map_center_lon 			= ". $this->params->get( 'map_center_lon', -100 ) .";
		var map_default_zoom_level 	= ". $this->params->get( 'map_default_zoom_level', 3 ) .";
		var catsearch_enabled  		= ". ((int)$this->params->get( 'cat_mode', 1 )>-1?'true':'false') .";
		var featsearch_enabled 		= ". ($this->params->get( 'featsearch_enabled', 1 )?'true':'false') .";
		var tagsearch_enabled  		= ". ((int)$this->params->get( 'tag_mode', 2 )>0?'true':'false') .";
		var map_units 				= ". $this->params->get( 'map_units', 1 ) .";
		var isModSearch 			= ". ($this->isModSearch?'true':'false') .";
		var hide_list_onload  		= ". ($this->params->get( 'hide_list_onload', 0 )?'true':'false') .";
		var list_enabled  			= ". ($this->params->get( 'list_enabled', 1 )?'true':'false') .";
		var map_type_id 			= google.maps.MapTypeId.". $this->params->get( 'map_view_state', 'ROADMAP' ) .";
		var scroll_wheel			= ". ($this->params->get( 'map_zoom_wheel', '1' )?'true':'false') .";
		var cat_mode				= ". $this->params->get( 'cat_mode', '1' ) .";
		var tag_mode 				= ". $this->params->get( 'tag_mode', '2' ) .";
		var menuitemid 				= ". $this->menuitemid .";
		var map_include_terrain		= ". ($this->params->get( 'map_include_terrain', '0' )?'true':'false') .";
		var map_theme  				= ". $this->params->get( 'map_theme', 0 ) .";
		var regional_bias 			= \"". addslashes($this->params->get( 'regional_bias', '' )) ."\";
		var sl_settings 			= ". json_encode( $this->params->toArray() )  .";
	");
?>
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<?php if ( $this->params->get( 'articleid_head', 0 ) ) : ?>
	<?php $articleid_head = StorelocatorHelper::getArticle($this->params->get( 'articleid_head', 0 )); ?>
	<div class="sl_article_top"><?php echo $articleid_head->introtext . $articleid_head->fulltext; ?></div>
<?php endif; ?>

<div class="locator_container">
<div id="sl_search_container" <?php if(!$this->search_enabled) echo 'style="display:none;"'; ?> class="row-fluid">
    <form action="#" onsubmit="searchLocations(); return false;" id="locate_form" class="form-inline">
    
        <div class="row-fluid">
        	<h3><?php echo JText::_( 'INSTRUCTIONS' ); ?></h3>
            <input type="text" id="addressInput" value="<?php echo $this->addressInput?>" class="span10" placeholder="<?php echo JText::_( 'ADDRESS_PLACEHOLDER' ); ?>" onkeydown="if (event.keyCode == 13) { searchLocations(); return false; }" />
            <?php if ( $this->params->get( 'geo_location', 1 ) ) : ?>
            <button class="mylocation-button" onclick="searchGeo(); return false;"> <div class="mylocation-widget"></div> </button>
            <?php endif; ?>
            <input type="button" class="btn btn-primary" onclick="searchLocations()" value="<?php echo JText::_( 'COM_STORELOCATOR_SEARCH_BTN_LABEL' ); ?>"/>
            <img src="components/com_storelocator/assets/spinner.gif" alt="Loading" style="display:none; padding-left:3px; vertical-align:middle;" id="sl_map_spinner" />
        </div>
        
        <?php if ( $this->params->get( 'name_search', 1 ) ||  $this->params->get( 'radiussearch_enabled', 1 ) || $this->featsearch_enabled) : ?>
        <div class="row-fluid">
        	<h5><strong><?php echo JText::_( 'COM_STORELOCATOR_OPTIONS_LABEL' ); ?></strong></h5>
            <?php if ( $this->params->get( 'name_search', 1 ) ) : ?>
            <input type="text" id="name_search" value="<?php echo $this->name_search?>" class="span4 rightmargin10" placeholder="<?php echo JText::_( 'COM_STORELOCATOR_NAME_SEARCH_LABEL' ); ?>" onkeydown="if (event.keyCode == 13) { searchLocations(); return false; }" />
            <?php endif; ?>
            
            <?php if ( $radius_search = $this->params->get( 'radiussearch_enabled', 1 ) ) : ?>
            <span class="help-inline"><?php echo JText::_( 'RADIUS' ); ?></span>
            <select id="radiusSelect" class="span2 rightmargin10">
            <?php
                foreach( $this->radius_list as $radius )
                    printf("<option value=\"%g\" %s>%g %s</option>", $radius, ($this->radiusSelect==$radius)?'selected="selected"':'', $radius, ($this->map_units?JText::_( 'MILES' ):JText::_( 'KILOMETERS' )));
            ?>
            </select> 
            <?php endif; ?>
            
            <?php if($this->featsearch_enabled): ?>
            <span class="help-inline"><?php echo JText::_( 'FEATURED' ); ?></span>
            <?php echo $this->featsearch; ?>
            <?php endif;?>
       
        </div>
        <?php endif;?>
        
        <?php if($this->catsearch_enabled > -1): ?>
        <div class="row-fluid">
            <h5><?php echo JText::_( 'CATEGORY' ); ?> <small><?php echo JText::_( 'COM_STORELOCATOR_OPTIONAL_LABEL' ); ?></small></h5>
            <?php echo $this->catsearch; ?>
        </div>
        <?php endif;?>
        
        <?php if($this->tagsearch_enabled > 0): ?>
        <div class="row-fluid">
            <h5><?php echo JText::_( 'TAGS' ); ?> <small><?php echo JText::_( 'COM_STORELOCATOR_OPTIONAL_LABEL' ); ?></small></h5>
            <?php echo $this->tagsearch; ?>
        </div>
        <?php endif;?>
    
       <?php if ( !$radius_search ) : ?>
       <input type="hidden" id="radiusSelect" name="radiusSelect" value="<?php echo $this->radiusSelect; ?>"  />
       <?php endif; ?>
   </form>
</div>
<br/>
  
<div id="sl_results_container" class="row-fluid">
  
    <div class="row-fluid">
      <div class="span12" id="sl_locate_results" <?php if(!$this->search_enabled) echo 'style="display:none;"'; ?>><?php echo JText::_( 'PRESEARCH_TEXT' ); ?></div>
    </div>
    
<?php switch($this->layout_theme): 
	 case 1: ?>
    <div class="row-fluid">
  	  <div id="map" style="height: <?php echo intval($this->map_height)?>px" class="<?php echo (!$this->list_enabled || $this->hide_list_onload)?'span12':'span9'; ?>"></div>
      <div id="sl_sidebar" style="height: <?php echo $this->map_height?>px;<?php if(!$this->list_enabled || $this->hide_list_onload) echo 'display:none;'; ?>" class="span3"><?php echo JText::_( 'NO_RESULTS' ); ?></div>
    </div>
	<?php break;?>
    <?php case 0: ?>
    <?php default: ?>
    <div class="row-fluid">
  	  <div id="map" style="height: <?php echo intval($this->map_height)?>px" class="span12"></div>
      <div id="sl_sidebar" style="<?php if(!$this->list_enabled || $this->hide_list_onload) echo 'display:none;'; ?>" class="span12"> &nbsp; </div>
    </div>
    <?php break;?>
<?php endswitch; ?>
    
    
		
    
  
	<?php if ( $this->params->get( 'show_copyright', 1 ) ): ?>  
    <div class="row-fluid">
      <div class="span12" id="copyright-block">
        <a title="Store Locator Joomla Component" href="http://www.sysgenmedia.com" target="_blank">Store Locator</a> by <a title="Joomla Dealer Locator Extension" href="http://www.sysgenmedia.com" target="_blank">Sysgen Media LLC</a>
      </div>
    </div>
    <?php endif; ?>
</div>
</div>
<?php if ( $this->params->get( 'articleid_foot', 0 ) ) : ?>
	<?php $articleid_foot = StorelocatorHelper::getArticle($this->params->get( 'articleid_foot', 0 )); ?>
	<div class="sl_article_bottom"><?php echo $articleid_foot->introtext . $articleid_foot->fulltext; ?></div>
<?php endif; ?>