/**
* Modules manager JS APP for list entities, edit entities and edit positions match
* 
* @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer 
* @subpackage js 
* @author Joomla! Extensions Store
* @copyright (C)2014 Joomla! Extensions Store
* @license GNU/GPLv2 or later, see license.txt
*/
//'use strict';
(function($) {
	var ModulesManager = function (ctrlsSelector) {
		/**
		 * Target switcher HTMLElement
		 * 
		 * @access private
		 * @var Object
		 */
		var targetDOMElems = {};
		
		/**
		 * Highlighted elements cache
		 * 
		 * @access private
		 * @var array
		 */
		var highlightedElementsCache = new Array();
		
		/**
		 * Message timeout to resolve deferred
		 * 
		 * @acces private
		 * @var Object
		 */
		var msgTimeout;
		
		/**
		 * Open first operation progress bar
		 * 
		 * @access private
		 * @return void 
		 */
		function showMessages(message) {
			var messageSnippet = '<div class="alert alert-success fade in">' +
						      		'<button type="button" class="close" data-dismiss="alert"><span class="icon-remove"></span></button>' +
						            '<span class="icon-ok"></span><span class="alert-message">' + message + '</span>' +
						          '</div>';		
			
			if(!$('div.alert').length) {
				$('#system-message-container').hide();
			}
			$('#system-message').remove();
			$('div.alert').remove();
			clearTimeout(msgTimeout);
			$('#system-message-container').append(messageSnippet);
			if($('#system-message-container').css('display') == 'none') {
				$('#system-message-container').slideDown();
			}
			
			
			timerReady = $.Deferred();
			$.when(timerReady).done(function(response){
				$('div.alert').removeClass('fade in').slideUp(function(){
					$('#system-message-container').hide();
				});
				
			});
			
			msgTimeout = setTimeout(function(){
				timerReady.resolve();
			}, 9999999);
		};
		
		/**
		 * Switch ajax submit form to model business logic
		 * 
		 * @access private
		 * @param HTMLElement element
		 * @return Void
		 */
		function excludeAjaxEntities(element) {
			// Final status for model operation
			var success = false;
			
			// Extra object to send to server
			var ajaxParams = { 
					idtask : 'mobilePublishModules',
					template : 'json',
					param: {
						id: $(element).data('id'),
						action: $(element).data('action')
					}
			     };
			// Unique param 'data'
			var uniqueParam = JSON.stringify(ajaxParams); 
			
			// Request JSON2JSON
			$.ajax({
				type: 'POST',
				url: "../administrator/index.php?option=com_responsivizer&task=ajaxserver.display&format=raw",
				dataType: 'json',
				async: true,
				context: this,
				data: {data: uniqueParam},
				success: function(data, textStatus, jqXHR){
					var data;
					
					// Manage user message text
					if(!!data.result) {
						if(parseInt(ajaxParams.param.action) === 1) {
							text = COM_RESPONSIVIZER_MOBILE_MODULE_EXCLUDED;
						} else {
							text = COM_RESPONSIVIZER_MOBILE_MODULE_INCLUDED;
						}
						// Manage mobile available icons and make mobile button
						var target = $('img.avl' + ajaxParams.param.id);
						$(target).next().remove();
						switch (ajaxParams.param.action) {
							case 0:
								$(target).attr('src', responsivizerBaseURI + 'administrator/components/com_responsivizer/images/icon-16-tick.png');
								$(target).attr('data-original-title', COM_RESPONSIVIZER_MOBILE_AVAILABLE_MODULE);
								break;
							case 1:
								var hasMobile = !!data.has_mobile;
								var additionalStatusClass = hasMobile ? 'hasmobile' : '';
								var titleHasMobile = hasMobile ? COM_RESPONSIVIZER_MAKE_EXISTS_MOBILE_DESC : COM_RESPONSIVIZER_MAKE_MOBILE_DESC;
								$(target).attr('src', responsivizerBaseURI +'administrator/components/com_responsivizer/images/icon-16-publish_x.png');
								$(target).attr('data-original-title', COM_RESPONSIVIZER_MOBILE_NOAVAILABLE_MODULE);
								$(target).tooltip({trigger:'hover', placement:'top'});
								if(!data.native_mobile) {
									$(target).after('<div><label class="label label-info ' + additionalStatusClass + ' makemobile"><a class="hasTooltip" title="' + titleHasMobile + '" href="index.php?option=com_responsivizer&task=modulesmanager.editEntity&id=' + data.id + '">Make mobile</a></label></div>');
									$(target).next().find('a.hasTooltip').tooltip({trigger:'hover', placement:'top'})
								}
								break;
						}
						
					} else {
						text = COM_RESPONSIVIZER_MOBILE_EXCLUDE_ERROR + data.errorMsg;
					}
					// Format alert bootstrap message
					showMessages(text);
				},
				error: function(jqXHR, textStatus, error){
					text = COM_RESPONSIVIZER_MOBILE_EXCLUDE_ERROR + error;
					// Format alert bootstrap message
					showMessages(text);
				}
			});   
		};
		
		/**
		 * Ajax submit for show title and bg
		 * 
		 * @access private
		 * @param HTMLElement element
		 * @return Void
		 */
		function changeAjaxState(element) {
			// Final status for model operation
			var success = false;
			
			// Extra object to send to server
			var ajaxParams = { 
					idtask : 'changeStateModules',
					template : 'json',
					param: {
						id: $(element).data('id'),
						field: $(element).data('field'),
						value: $(element).data('value')
					}
			     };
			// Unique param 'data'
			var uniqueParam = JSON.stringify(ajaxParams); 
			
			// Request JSON2JSON
			$.ajax({
				type: 'POST',
				url: "../administrator/index.php?option=com_responsivizer&task=ajaxserver.display&format=raw",
				dataType: 'json',
				async: true,
				context: this,
				data: {data: uniqueParam},
				success: function(data, textStatus, jqXHR){
					var data;
					
					// Manage user message text
					if(!!data.result) {
						if(parseInt(ajaxParams.param.value) === 1) {
							text = eval('COM_RESPONSIVIZER_' + ajaxParams.param.field.toUpperCase() + '_ENABLED');
						} else {
							text = eval('COM_RESPONSIVIZER_' + ajaxParams.param.field.toUpperCase() + '_DISABLED');
						}
					} else {
						text = COM_RESPONSIVIZER_CHANGE_STATE_ERROR + data.errorMsg;
					}
					// Format alert bootstrap message
					showMessages(text);
				},
				error: function(jqXHR, textStatus, error){
					text = COM_RESPONSIVIZER_CHANGE_STATE_ERROR + error;
					// Format alert bootstrap message
					showMessages(text);
				}
			});   
		};
		
		/**
		 * Manage direct iframe injection and manipulation being on same domain
		 * 
		 * @access private
		 * @param Object element
		 * @return Void
		 */
		function manageIFramesContents(iFrame) {
			var sourceHeadTag = $(iFrame).contents().find('head');
			var stylesOverride = '<style type="text/css">' +
									'.mod-preview-info{position:relative !important}' +
									'.mod-preview{background-color: #eee;border: 1px dotted black;color: #700;margin-bottom: 20px;}'+
								 '</style>';
			$(sourceHeadTag).append(stylesOverride);
			
			// Remove waiter
			$(iFrame).parent().children('img').remove();
		}
		
		 /**
		 * Function dummy constructor
		 * 
		 * @access private
		 * @method <<IIFE>>
		 * @return Void
		 */
        (function __construct() {
        	var context = this;
        	// Add class to broadcast button
        	
        	targetDOMElems['source'] =  $('iframe.copy_iframe.source');
        	targetDOMElems['target'] = $('iframe.copy_iframe.target');

        	// Inject now waiters
        	$.each(targetDOMElems, function(index, iframeElem){
        		var containerWidth = $(iframeElem).width() / 2;
        		$(iframeElem).parent().prepend('<img/>').children('img').attr('src', responsivizerBaseURI + 'administrator/components/com_responsivizer/images/loading.gif').css({
                    'position': 'absolute',
                    'margin': '50px ' + parseInt(containerWidth - 24) + 'px',
                    'width': '48px'
                });
        	});
        	
            // Register label click as ajax trigger
            $('input[data-role=ajaxswitcher] ~ label').on('click', function(event){
            	excludeAjaxEntities($(this).prev());
            });
            
            // Register label click as ajax trigger for show title and bg
            $('input[data-role=showtitle] ~ label, input[data-role=showbg] ~ label').on('click', function(event){
            	changeAjaxState($(this).prev());
            });
            
            if(targetDOMElems['source'].length) {
            	sourceIFrameLoaded = $.Deferred();
    			$.when(sourceIFrameLoaded).done(function(response){
    				manageIFramesContents(targetDOMElems['source']);
    				
    				// Auto scroll to source iframe
    				var currentSourceTitle= $('#source_moduletitle').data('moduletitle');
    	            var currentSourcePosition = $('#source_position').data('position');
    	        	var targetIFrameContents = targetDOMElems['source'].contents();
    	        	var allSourceTP = targetDOMElems['source'].contents().find('div.mod-preview-info');
    	        	// Most assonance element to scroll to, default the first
    	        	var scrollElement = allSourceTP[0];
    	        	
    	        	// Cycle on all elems and filter optionally by text content = select value
    	        	$.each(allSourceTP, function(index, elem) {
    	    			var innerText = $(elem).text();
    	    			if(innerText.match(currentSourcePosition, 'gi')) {
    	    				// Get parent elem
    	    				var parentElem = $(elem).parent();
    	    				
    	    				// Most assonance element to scroll to, default the first
    	    				if(index == 0) {
    	    					scrollElement = parentElem;
    	    				}
    	    				
    	    				// Apply highlight
    	    				parentElem.css('background-color', '#FF0');
    	    				parentElem.find('div').css('background-color', '#FF0');
    	    				
    	    				var nextSiblingInnertext = $(elem).next().text();
    	    				if(nextSiblingInnertext.match(currentSourceTitle, 'gi')) {
    	    					scrollElement = parentElem;
    	    				}
    	    			}
    	        	});
    	        	
    	        	// Scroll to element
    				$('iframe.source').contents().find('html,body').animate({
    					scrollTop: $(scrollElement).offset().top - 50
    				}, 1000);
    			});
    			
    			targetDOMElems['source'].on('load', function(){
    				setTimeout(function(){
    					sourceIFrameLoaded.resolve();
    				}, 500);
    			});
            }
            
            if(targetDOMElems['target'].length) {
            	targetIFrameLoaded = $.Deferred();
    			$.when(sourceIFrameLoaded).done(function(response){
    				manageIFramesContents(targetDOMElems['target']);
    			});
    			
    			targetDOMElems['target'].on('load', function(){
    				setTimeout(function(){
    					targetIFrameLoaded.resolve();
    				}, 500);
    			});
            }
            
            // Auto size input width button
            $('input.labelctrls').autoSizeInput();
            
            $('#position').on('change', function(event) {
            	var currentValue = $(this).val();
            	var targetIFrameContents = targetDOMElems['target'].contents();
            	var allPreviewTP = targetDOMElems['target'].contents().find('div.mod-preview-info');
            	// Cycle on all elems and filter optionally by text content = select value
            	$.each(allPreviewTP, function(index, elem) {
        			var innerText = $(elem).text();
        			if(innerText.match(currentValue, 'gi')) {
        				// Clear previously highlighted elements if any
        				if(highlightedElementsCache){
        					$.each(highlightedElementsCache, function(index, elem) {
        						elem.css('background-color', elem.data('orig-background'));
        						elem.find('div').css('background-color', '');
        					});
        				}
        				
        				// Get parent elem
        				var parentElem = $(elem).parent();
        				parentElem.find('div').css('background-color', 'inherit');
        				
        				// Store original data for later backup
        				parentElem.data('orig-background', $(parentElem).css('background-color'));
        				highlightedElementsCache[highlightedElementsCache.length] = parentElem;
        				
        				// Apply highlight
        				parentElem.css('background-color', '#FF0');
        				
        				// Scroll to element
        				$('iframe.target').contents().find('html,body').animate({
        					scrollTop: $(parentElem).offset().top - 50
        				}, 500);
        			}
            	});
            });
            
            // Fancybox iframe for native modules management
            if($('a.fancybox_iframe').length) {
				$("a.fancybox_iframe").fancybox({
					type:'iframe',
					height: '80%',
					width: '80%',
					afterLoad: function(upcoming){
							$($('iframe[id^=fancybox]')).attr('scrolling','yes');
						},
					beforeClose: function(){
						var iframe = $("iframe.fancybox-iframe").get(0);
						if(iframe.contentWindow) {
							iframe.contentWindow.Joomla.submitbutton('module.cancel');
						}
						else if(iframe.contentDocument) {
							iframe.contentDocument.Joomla.submitbutton('module.cancel');
						}
					}
				});
			}
			
			$('#fancy_closer').on('click', function(){
				parent.jQuery.fancybox.close();
			});
			
			// Append switch to default modules manager button link
			$('#toolbar').append('<a href="index.php?option=com_modules" class="fancybox_iframe fancybox btn btn-primary btn-xs absolute hidden-phone">' + 
								 '<span class="icon-out-2"></span> ' + COM_RESPONSIVIZER_SWITCH_TO_JOOMLA_MM + '</a>');
        }).call(this);
    }

    // On DOM Ready
    $(function () {
        var ResponsivizerModulesManager = new ModulesManager();
    });
    
    // Auto size jQuery plugin for input text
    $.fn.autoSizeInput = function(o) {
        o = $.extend({
            maxWidth: 1000,
            minWidth: 0,
            comfortZone: 70
        }, o);
        this.each(function(){
        	  var text = $('<span>')
              .html($(this).val())
              .appendTo(this.parentNode);
          var w = text.innerWidth();
          text.remove();
          $(this).width(w+10);
        });
	}
})(jQuery);