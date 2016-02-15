/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */
    
    var map;
    var markers = [];
    var infoWindow;
    var map_unit_text;
	var clusters;
	var currentPos = null;
	
	function locatorInit() {         
         
        map_unit_text = map_units ? MI : KM;
        infoWindow = new google.maps.InfoWindow();
	
		var mapTypes = [  
            	google.maps.MapTypeId.ROADMAP,
                google.maps.MapTypeId.SATELLITE,
                google.maps.MapTypeId.HYBRID
            ];
		
		if (map_include_terrain)
			mapTypes = [  
            	google.maps.MapTypeId.ROADMAP,
                google.maps.MapTypeId.SATELLITE,
                google.maps.MapTypeId.HYBRID,
				google.maps.MapTypeId.TERRAIN
            ];
		       
        var myOptions = {
          zoom: map_default_zoom_level,
          center: new google.maps.LatLng(map_center_lat, map_center_lon),
          mapTypeId: map_type_id,          
          mapTypeControlOptions: {
          	mapTypeIds: mapTypes,
            style: google.maps.MapTypeControlStyle.DEFAULT, //TODO Param
            position: google.maps.ControlPosition.TOP_RIGHT //TODO Param
          },
          scrollwheel: scroll_wheel
        }

		if ( map_theme == 1 )
        	myOptions['styles'] = [ { "stylers": [ { "saturation": -100 } ] } ];
       
        map = new google.maps.Map(document.getElementById("map"), myOptions);
		
		// Responsive 
		google.maps.event.addDomListener(window, "resize", function() {
			var center = map.getCenter();
			google.maps.event.trigger(map, "resize");
			map.setCenter(center); 
		});
                                      
        if (sl_settings.map_cluster_method == 'MarkerCluster')
        	clusters = new MarkerClusterer(map, [], {gridSize: 50, maxZoom: 15});
        
        if (sl_settings.map_cluster_method == 'Spiderfier')
        {
        	clusters = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true});
         	
            clusters.addListener('spiderfy', function(dots) {
                for(var i = 0; i < dots.length; i ++) {
                  dots[i].setIcon('http://chart.googleapis.com/chart?chst=d_map_xpin_letter&chld=pin|+|ffee22|000000|ffff00');
                }
                infoWindow.close();
             });
             clusters.addListener('unspiderfy', function(dots) {
                for(var i = 0; i < dots.length; i ++) {
                  dots[i].setIcon(markers[i].icon_orig);
                }
             });   
        }
                                  
        if(isModSearch)
            searchLocations();
            
        if(!isModSearch && show_all_onload > 0) 
        	loadAllLocations();

    }
	

   
   function loadAllLocations() {
    
            
   	 jQuery('#sl_map_spinner').css('display','inline');
     
     var catid = -1;
     var tagid = -1;
     var featstate = (show_all_onload==2)?1:0;
	 currentPos = null;
     
	 if (catsearch_enabled)
     {
         if (cat_mode==1)
         	catid = jQuery('#catid').val();
         else {
            var catstr = new Array();

			jQuery("#locate_form input[name='catid']:checked").each(function() {
				catstr.push(jQuery(this).val());
			});
						
            if (catstr.length > 0)             
            	catid = catstr.join('_');   
            else 
            	catid = 999999; 
         }
     }
     
     if (tagsearch_enabled)
     {
         if (tag_mode == 1)
         	tagid = jQuery('#tagid').value;
         else {
            var tagstr = new Array();
			
			jQuery("#locate_form input[name='tagid']:checked").each(function() {
				tagstr.push(jQuery(this).val());
			});
            
            if (tagstr.length > 0)             
            	tagid = tagstr.join('_');   
         }
     }
        
     if (featsearch_enabled && show_all_onload < 2)
     	featstate = jQuery('#featstate').val();
        
	 var searchUrl = JOOMLA_ROOT + '/index.php?option=com_storelocator&view=map&format=raw&searchall=1&Itemid=' + menuitemid + '&catid=' + catid + '&tagid=' + tagid + '&featstate=' + featstate;

     searchViaRequest(searchUrl);
     
   }
   
   function searchGeo()
   {
	   // Try HTML5 geolocation
	  if(navigator.geolocation) 
	  {
		jQuery('#sl_map_spinner').css('display','inline');
		jQuery('.mylocation-widget').css('background-position','-144px 0');
		jQuery('#sl_locate_results').html(COM_STORELOCATOR_GEO_SEARCH);
		
		navigator.geolocation.getCurrentPosition(function(position) 
		{
			var pos = new google.maps.LatLng(position.coords.latitude,
										   position.coords.longitude);
										   
			currentPos = pos;
										   
			 if (sl_settings.name_search == 1)
				var name_search = jQuery('#name_search').val();
			 else
				var name_search = '';
			 
			 if(hide_list_onload == true && list_enabled == true)
			 {
				onMapFirstSearch();
			 }							   

			searchLocationsNear(pos, name_search);						   
		}, 
		function() 
		{
			jQuery('#addressInput').val('Error: The Geolocation service failed.');
			jQuery('.mylocation-widget').css('background-position','0 0');
		});
		
	  } else {
			jQuery('#addressInput').val('Error: Your browser doesn\'t support geolocation.');
			jQuery('.mylocation-widget').css('background-position','0 0');
	  }
   }
   
   
  function searchLocations() 
  {
	 var address = jQuery('#addressInput').val();
	 	 
	 if(address.trim() != '' && regional_bias.trim() != '')
     	address = address + ', ' + regional_bias;
     
     if (sl_settings.name_search == 1)
     	var name_search = jQuery('#name_search').val();
     else
     	var name_search = '';
     
     jQuery('#sl_map_spinner').css('display','inline');
	 jQuery('#sl_locate_results').html(SEARCH_RESULTS_FOR + " " + address);
		
     
     if (address.replace(/\s/g,"") == "" && (sl_settings.name_search == 0 || name_search.replace(/\s/g,"") == "" ) ) 
     {
     	clearLocations();
        if (show_all_onload > 0)
        	loadAllLocations();
            
        return;
     }
     
     
     if(hide_list_onload == true && list_enabled == true)
     {
        onMapFirstSearch();
     }
     
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
     	
        if (status == google.maps.GeocoderStatus.OK) 
        {
            if (google_suggest && results.length > 1) { 
                var suggest = "<div class=\"sl_suggest\"><span>" + DID_YOU_MEAN + "</span><ul>";
                
                for (var i=0; i< results.length; i++) {
                    var p = results[i].geometry.location;
                    suggest += '<li><a href=\'javascript:searchLocationsNearbyLatLng(' + p.lat() + ',' + p.lng() + ',"' + results[i].formatted_address + '","' + name_search + '")\'>' + results[i].formatted_address + '</a></li>';					
                }
                
                suggest += "</ul></div>";
                jQuery('#sl_sidebar').html(suggest);
                jQuery('#sl_map_spinner').css('display','none');
            } else {
                // ===== If there was a single marker =====           
                searchLocationsNear(results[0].geometry.location, name_search);
            }
        } else { //implement errors
         if (sl_settings.name_search == 1)
            searchLocationsByName(name_search);
         else
            clearLocations();
        }});
   }
   
   
   function searchLocationsNearbyLatLng(lat,lng, query, name_search)
   {
   	  var point = new google.maps.LatLng(lat, lng)
      
      jQuery('#addressInput').val(query);
      
      return searchLocationsNear(point, name_search);
   }
   
   

   function searchLocationsNear(center, name_search) 
   {
        clearLocations(true);
        
   		var catid = -1;
        var tagid = -1;
        var featstate = (featsearch_enabled)?jQuery('#featstate').val():0;
        var radius = jQuery('#radiusSelect').val();
		
		currentPos = center;

        if (catsearch_enabled)
        {
            if (cat_mode==1)
            {
            	catid = jQuery('#catid').val();
            }
            else 
            {
            	var catstr = new Array();
				
				jQuery("#locate_form input[name='catid']:checked").each(function() {
					catstr.push(jQuery(this).val());
				});
            
            	if (catstr.length > 0)             
            		catid = catstr.join('_');   
            	else 
            		catid = 1000000;     
            }
        }
        
        if (tagsearch_enabled)
        {
         if (tag_mode == 1)
            tagid = jQuery('#tagid').val();
         else {
            var tagstr = new Array();
			
			jQuery("#locate_form input[name='tagid']:checked").each(function() {
				tagstr.push(jQuery(this).val());
			});
            
            if (tagstr.length > 0)             
                tagid = tagstr.join('_');   
         }
        }
        
        var searchUrl = JOOMLA_ROOT + '/index.php?option=com_storelocator&view=map&format=raw&searchall=0&Itemid=' + menuitemid + 
     				 '&lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&catid=' + catid + '&tagid=' + tagid + '&featstate=' + featstate + '&name_search=' + encodeData(name_search);

        searchViaRequest(searchUrl);
        
   }
   
   
    function searchLocationsByName(name_search) 
    {
        clearLocations();
        
   		var catid = -1;
        var tagid = -1;
        var featstate = (featsearch_enabled)?jQuery('#featstate').val():0;
        var radius = jQuery('#radiusSelect').val();
		currentPos = null;

        if (catsearch_enabled)
        {
            if (cat_mode==1)
            {
            	catid = jQuery('#catid').val();
            }
            else 
            {
            	var catstr = new Array();
				
				jQuery("#locate_form input[name='catid']:checked").each(function() {
					catstr.push(jQuery(this).val());
				});
            
            	if (catstr.length > 0)             
            		catid = catstr.join('_');   
            	else 
            		catid = 1000000;     
            }
        }
        
        if (tagsearch_enabled)
        {
         if (tag_mode == 1)
            tagid = jQuery('tagid').val();
         else {
            var tagstr = new Array();
			
			jQuery("#locate_form input[name='tagid']:checked").each(function() {
					tagstr.push(jQuery(this).val());
				});
            
            if (tagstr.length > 0)             
                tagid = tagstr.join('_');   
         }
        }
        
       var searchUrl = JOOMLA_ROOT + '/index.php?option=com_storelocator&view=map&format=raw&searchall=0&Itemid=' + menuitemid + 
     				 '&name_search=' + encodeData(name_search) + '&radius=' + radius + '&catid=' + catid + '&tagid=' + tagid + '&featstate=' + featstate;
                     
	   searchViaRequest(searchUrl);
 
  	}
   
   
   function searchViaRequest(searchUrl)
   {
	   
	   
	   
	   jQuery.ajax({
			type: "GET",  
			url: searchUrl,
			dataType: 'xml', 
			success: function(xml){  
				clearLocations();
				
				//console.log(data);
                    
               // var xml = parseXml(data);
                var markerNodes = xml.documentElement.getElementsByTagName("marker");
                var bounds = new google.maps.LatLngBounds(null);
                
                var limited = xml.documentElement.getElementsByTagName("limited")[0].firstChild.nodeValue;
                if(limited==1)
                	jQuery('#sl_sidebar').html(LIMITED_RESULTS);

                
                if (markerNodes.length == 0)
                    return;
                
                jQuery('#sl_sidebar').html('');
                
                for (var i = 0; i < markerNodes.length; i++) {

					  var latlng = new google.maps.LatLng( 	parseFloat(getNodeText(markerNodes[i], 'lat')), 
					  											parseFloat(getNodeText(markerNodes[i], 'lng')) );
                    
                    createMapMarker(latlng, markerNodes[i]);
                    createSidebarEntry(markerNodes[i], i);
    
                    bounds.extend(latlng);
                    
                }
                
                if(map_auto_zoom)
                {
                    map.setOptions( { maxZoom: sl_settings.map_max_search_zoom } );
                    map.fitBounds(bounds);
                    map.setOptions( { maxZoom: null} );
                }
                    
                jQuery('sl_map_spinner').css('display', 'none');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				clearLocations();
			}       
		});
   }
   
   function createMapMarker(latlng, node) 
   {	  	   
	  var mkIcon = getNodeText(node, 'markertype');

      var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        icon: mkIcon,
        icon_orig: mkIcon
      });

      var html = '<div class="map-result">';
	  
      if (getNodeText(node, 'featured', 'bubble') == 'true')
        	html += '<img class="featureicon" src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/featured.png" width="16" height="16" align="absmiddle" /></a>';
            
	  html += '<h4 class="infoloc-name">' + getNodeText(node, 'name')
      
      
      var distance = parseFloat(getNodeText(node, 'distance'));
      if ( distance > 0 )
      	html += ' <span class="infoloc-distance">(' + distance.toFixed(1) + ' ' + map_unit_text + ')</span>';
      
      html += '</h4>';
            
	 if (getNodeText(node, 'address', 'bubble') != '') 
	  	html += '<div class="infoloc-address">' + getNodeText(node, 'address', 'bubble') + '</div>';
        
      if (getNodeText(node, 'fulladdress', 'bubble') != '') 
	  	html += '<div class="infoloc-fulladdress">' + getNodeText(node, 'fulladdress', 'bubble') + '</div>';
       
      if (getNodeText(node, 'phone', 'bubble') != '') 
	  	html += '<div class="infoloc-phone">' + getNodeText(node, 'phone', 'bubble') + '</div>';
        
      if (getNodeText(node, 'category', 'bubble') != '') 
	  	html += '<div class="infoloc-category">' + CATEGORY + ': ' + getNodeText(node, 'category', 'bubble').replace(',',', ') + '</div>';
		
	  if (getNodeText(node, 'tags', 'bubble') != '') 
	  	html += '<div class="infoloc-tags">' + TAGS + ': ' + getNodeText(node, 'tags', 'bubble').replace(',',', ') + '</div>';
      
      if (getNodeText(node, 'custom1', 'bubble') != '') 
	  	html += '<div class="infoloc-custom1"><strong>' + sl_settings.cust1_label + ':</strong> ' + getNodeText(node, 'custom1', 'bubble') + '</div>';
        
      if (getNodeText(node, 'custom2', 'bubble') != '') 
	  	html += '<div class="infoloc-custom2"><strong>' + sl_settings.cust2_label + ':</strong> ' + getNodeText(node, 'custom2', 'bubble') + '</div>';
        
      if (getNodeText(node, 'custom3', 'bubble') != '') 
	  	html += '<div class="infoloc-custom3"><strong>' + sl_settings.cust3_label + ':</strong> ' + getNodeText(node, 'custom3', 'bubble') + '</div>';
      
      if (getNodeText(node, 'custom4', 'bubble') != '') 
	  	html += '<div class="infoloc-custom4"><strong>'+ sl_settings.cust4_label + ':</strong> '  + getNodeText(node, 'custom4', 'bubble') + '</div>';
        
      if (getNodeText(node, 'custom5', 'bubble') != '') 
	  	html += '<div class="infoloc-custom5"><strong>' + sl_settings.cust5_label + ':</strong> ' + getNodeText(node, 'custom5', 'bubble') + '</div>';
        
      if (getNodeText(node, 'url', 'bubble') != '') 
	  	html += '<div class="infoloc-url"><a href="' + getNodeText(node, 'url') + '" target="' + sl_settings.field_bubble_url_target + '">' + VISIT_SITE + ' >></a></div>';

        
      if (getNodeText(node, 'facebook', 'bubble') != '' || getNodeText(node, 'twitter', 'bubble') != '' || getNodeText(node, 'email', 'bubble') != '') 
      {
	  	html += '<div class="infoloc-social">';
        
        if (getNodeText(node, 'facebook', 'bubble') != '')
        	html += '<a href="' + getNodeText(node, 'facebook', 'bubble') + '" target="_blank" class="networkicon infoloc-facebook"><img src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/facebook_16.png" width="16" height="16" align="absmiddle" /></a>';
           
        if (getNodeText(node, 'twitter', 'bubble') != '')
        	html += '<a href="http://twitter.com/' + getNodeText(node, 'twitter', 'bubble') + '" target="_blank" class="networkicon infoloc-twitter"><img src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/twitter_16.png" width="16" height="16" align="absmiddle" /></a>';
        
        if (getNodeText(node, 'email', 'bubble') != '')
        	html += '<a href="mailto:' + getNodeText(node, 'email', 'bubble') + '" class="networkicon infoloc-email"><img src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/email_16.png" width="16" height="16" align="absmiddle" /></a>';
		
		html += '</div>';
             
      }
	  
	  if ( map_directions )
	  {
		  var position = '';
		  
		  if (currentPos)
		  	position = currentPos.lat() + ',' + currentPos.lng();
			
	  		html += '<div class="infoloc-directions"><a href="http://maps.google.com/maps?daddr=' +  encodeData(getNodeText(node, 'address')) 	
					+ '&amp;saddr=' + encodeData(position) + '" target="_blank">' + GET_DIRECTIONS + '</a></div>';
	  }
	  
      html += '</div>';    
            
        if (sl_settings.map_cluster_method == 'MarkerCluster')
        {
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });  
            
            clusters.addMarker(marker);
        
        } 
        else if (sl_settings.map_cluster_method == 'Spiderfier')
        {
        
        	marker.desc = html;
            clusters.addListener('click', function(marker) {
            	infoWindow.setContent(marker.desc);
            	infoWindow.open(map, marker);
            });
            clusters.addMarker(marker);
        } 
        else 
        {
            google.maps.event.addListener(marker, 'click', function() {
            	infoWindow.setContent(html);
          		infoWindow.open(map, marker);
            });     
        }
	
    	markers.push(marker);
	  
    }
	   
   function createSidebarEntry(node, num) {
      
      var entry = jQuery('<div />', {"class": 'result-container'})
      var distance = parseFloat(getNodeText(node, 'distance'));
	        
      html = '<div class="result-inner">';
      
      if (getNodeText(node, 'featured', 'sidebar') == 'true')
        	html += '<img class="featureicon" src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/featured.png" width="16" height="16" align="absmiddle" /></a>';
        
       html += '<h4 class="loc-name">' + getNodeText(node, 'name');
      
      if (distance>0)
      	html += ' <span class="loc-distance">(' + distance.toFixed(1) + ' ' + map_unit_text + ')</span>';
		
	  html += '</h4>';
        
      if (getNodeText(node, 'address', 'sidebar') != '') 
	  	html += '<div class="loc-address">' + getNodeText(node, 'address', 'sidebar') + '</div>';
        
      if (getNodeText(node, 'fulladdress', 'sidebar') != '') 
	  	html += '<div class="loc-fulladdress">' + getNodeText(node, 'fulladdress', 'sidebar') + '</div>';
       
      if (getNodeText(node, 'phone', 'sidebar') != '') 
	  	html += '<div class="loc-phone">' + getNodeText(node, 'phone', 'sidebar') + '</div>';
		
	  if (getNodeText(node, 'category', 'sidebar') != '') 
	  	html += '<div class="loc-category">' + CATEGORY + ': ' + getNodeText(node, 'category', 'sidebar').replace(',',', ') + '</div>';
        
      if (getNodeText(node, 'tags', 'sidebar') != '') 
	  	html += '<div class="loc-tags">' + TAGS + ': ' + getNodeText(node, 'tags', 'sidebar').replace(',',', ') + '</div>';
        
      
      if (getNodeText(node, 'custom1', 'sidebar') != '') 
	  	html += '<div class="loc-custom1">' + sl_settings.cust1_label + ': ' + getNodeText(node, 'custom1', 'sidebar') + '</div>';
        
      if (getNodeText(node, 'custom2', 'sidebar') != '') 
	  	html += '<div class="loc-custom2">' + sl_settings.cust2_label + ': ' + getNodeText(node, 'custom2', 'sidebar') + '</div>';
        
      if (getNodeText(node, 'custom3', 'sidebar') != '') 
	  	html += '<div class="loc-custom3">' + sl_settings.cust3_label + ': ' + getNodeText(node, 'custom3', 'sidebar') + '</div>';
      
      if (getNodeText(node, 'custom4', 'sidebar') != '') 
	  	html += '<div class="loc-custom4">'+ sl_settings.cust4_label + ': '  + getNodeText(node, 'custom4', 'sidebar') + '</div>';
        
      if (getNodeText(node, 'custom5', 'sidebar') != '') 
	  	html += '<div class="loc-custom5">' + sl_settings.cust5_label + ': ' + getNodeText(node, 'custom5', 'sidebar') + '</div>';
                
      if (getNodeText(node, 'url', 'sidebar') != '') 
	  	html += '<div class="loc-url"><a href="' + getNodeText(node, 'url') + '" target="' + sl_settings.field_sidebar_url_target + '">' + VISIT_SITE + ' >></a></div>';
        
      if (getNodeText(node, 'facebook', 'sidebar') != '' || getNodeText(node, 'twitter', 'sidebar') != '' || getNodeText(node, 'email', 'sidebar') != '') 
      {
	  	html += '<div class="loc-social">';
        
        if (getNodeText(node, 'facebook', 'sidebar') != '')
        	html += '<a href="' + getNodeText(node, 'facebook', 'sidebar') + '" target="_blank" class="networkicon loc-facebook"><img src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/facebook_16.png" width="16" height="16" align="absmiddle" /></a>';
           
        if (getNodeText(node, 'twitter', 'sidebar') != '')
        	html += '<a href="http://twitter.com/' + getNodeText(node, 'twitter', 'sidebar') + '" target="_blank" class="networkicon loc-twitter"><img src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/twitter_16.png" width="16" height="16" align="absmiddle" /></a>';
        
        if (getNodeText(node, 'email', 'sidebar') != '')
        	html += '<a href="mailto:' + getNodeText(node, 'email', 'sidebar') + '" class="networkicon loc-email"><img src="' + JOOMLA_ROOT + '/components/com_storelocator/assets/email_16.png" width="16" height="16" align="absmiddle" /></a>';
		
		html += '</div>';
             
      }
	  
	  html += '</div>';
        	 
      entry.html( html );
      
      entry.click(function() {
        google.maps.event.trigger(markers[num], 'click');
        
        if (sl_settings.map_cluster_method == 'Spiderfier')
        	google.maps.event.trigger(markers[num], 'click');
      });

      
      jQuery('#sl_sidebar').append(entry);
    }
	

	function onMapFirstSearch()
	{
		 if(hide_list_onload == true && list_enabled == true)
		 {
			jQuery('#sl_sidebar').css('display', '');
			
			if (sl_settings.layout_theme == 1)
			{
				jQuery('#map').addClass('span9');
				jQuery('#map').removeClass('span12');
			}
		 }							   
	}
    
    
    function getNodeText(node, field, location)
    {
    	var child = node.getElementsByTagName(field)[0].firstChild;
        var paramkey = 'field_' + location + '_' + field;
        
        if (location && sl_settings[paramkey] && sl_settings[paramkey] == '0')
        	return '';
                
        if (child != undefined)
		{
        	 if (child.wholeText)
			 	return child.wholeText.trim();
			 else
			 	return child.nodeValue.trim();
		}
        else
        	return '';

   }
    
    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }
	
	function encodeData(s){
		return encodeURIComponent(s).replace(/\-/g, "%2D").replace(/\_/g, "%5F").replace(/\./g, "%2E").replace(/\!/g, "%21").replace(/\~/g, "%7E").replace(/\*/g, "%2A").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29");
	}
    
   function clearLocations(clear_sidebar) 
   {
        infoWindow.close();
        for (var i = 0; i < markers.length; i++) {
        	markers[i].setMap(null);
        }
        markers.length = 0;
        
        if (sl_settings.map_cluster_method == 'Spiderfier' || sl_settings.map_cluster_method == 'MarkerCluster')
        {
        	clusters.clearMarkers();	
        }	
        
        if (clear_sidebar==true)
        	jQuery('#sl_sidebar').html('');
        else
        	jQuery('#sl_sidebar').html(NO_RESULTS);
			
		jQuery('#sl_map_spinner').css('display', 'none');
		jQuery('.mylocation-widget').css('background-position','0 0');
   }
   
   String.prototype.trim = function() {
      return this.replace(/^\s+|\s+$/g, "");
   };


	jQuery( document ).ready( function(){ locatorInit(); });