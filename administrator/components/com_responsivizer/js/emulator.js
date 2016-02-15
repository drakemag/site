/**
 * EMULATOR main JS APP class
 * 
 * @package RESPONSIVIZER::EMULATOR::administrator::components::com_responsivizer
 * @subpackage js
 * @author Joomla! Extensions Store
 * @copyright (C)2014 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
//'use strict';
(function($) {
	var Emulator = function(options) {
		/**
		 * Current active device
		 * 
		 * @access private
		 * @var String
		 */
		var activeDevice;
		
		/**
		 * Current active device family type
		 * 
		 * @access private
		 * @var String
		 */
		var activeDeviceType;
		
		/**
		 * Supported devices specifications object
		 * 
		 * @access private
		 * @var Object
		 */
		var devices = {
			'phones' : {
				'motorola_razr_v3m' : {
					H1 : 176,
					H2 : 220,
					manufacturer: 'motorola'
				},
				'motorola_razr_v8' : {
					H1 : 240,
					H2 : 320,
					manufacturer: 'motorola'
				},
				'blackberry_8300' : {
					H1 : 240,
					H2 : 320,
					manufacturer: 'blackberry'
				},
				'apple_iphone_3-4' : {
					H1 : 320,
					H2 : 480,
					manufacturer: 'apple'
				},
				'apple_iphone_5-6' : {
					H1 : 320,
					H2 : 568,
					manufacturer: 'apple'
				},
				'lg_optimus' : {
					H1 : 320,
					H2 : 480,
					manufacturer: 'android'
				},
				'asus_galaxy_7' : {
					H1 : 320,
					H2 : 533,
					manufacturer: 'windows'
				},
				'samsung_s2' : {
					H1 : 320,
					H2 : 533
					,
					manufacturer: 'android'
				},
				'samsung_s3-4-5' : {
					H1 : 360,
					H2 : 640,
					manufacturer: 'android'
				}
			},
			'tablets' : {
				'kindle_fire' : {
					H1 : 600,
					H2 : 800,
					manufacturer: 'android'
				},
				'kindle_fire_hd7' : {
					H1 : 533,
					H2 : 853,
					manufacturer: 'android'
				},
				'kindle_fire_hd9' : {
					H1 : 800,
					H2 : 1280,
					manufacturer: 'android'
				},
				'samsung_galaxy_tab' : {
					H1 : 600,
					H2 : 1024,
					manufacturer: 'android'
				},
				'google_nexus_7' : {
					H1 : 603,
					H2 : 966,
					manufacturer: 'android'
				},
				'apple_ipad_1-3-Mini' : {
					H1 : 768,
					H2 : 1024,
					manufacturer: 'apple'
				}
			}
		};

		/**
		 * Dimension H1
		 * 
		 * @access private
		 * @var Integer
		 */
		var H1;

		/**
		 * Dimension H2
		 * 
		 * @access private
		 * @var Integer
		 */
		var H2;

		/**
		 * Wrapper element for Emulator app
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var wrapper = $('<div id="wrapper"/>');

		/**
		 * Main emulator window
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var emulatorIFrame = $('<iframe id="emulator_iframe" name="emulator_iframe"/>');

		/**
		 * Controller toolbar for emulator app
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var toolbar = $('<div id="emulator_toolbar"/>');

		/**
		 * Dropdown to choose target device among phones
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var devicesDropdowns = $('<div id="navbar-example" class="navbar navbar-static">' +
						              '<div class="navbar-inner">' +
						               '<div class="container" style="width: auto;">' +
						                  '<ul class="nav" role="navigation">' +
						                    '<li id="phones_container" data-family="phones" class="dropdown">' +
						                      '<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b>' + COM_RESPONSIVIZER_CHOOSE_PHONE + '</a>' +
						                      '<ul id="phones" class="dropdown-menu" role="menu" aria-labelledby="drop1"></ul>' +
						                    '</li>' +
						                    '<li id="tablets_container" data-family="tablets" class="dropdown">' +
						                      '<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b>' + COM_RESPONSIVIZER_CHOOSE_TABLET + '</a>' +
						                      '<ul id="tablets" class="dropdown-menu" role="menu" aria-labelledby="drop2"></ul>' +
						                    '</li>' +
						                  '</ul>' +
						                '</div>' +
						              '</div>' +
						            '</div>');

		/**
		 * Controls for dimensions and ranges
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var sizesInputs = $('<div class="input-prepend"><span class="add-on"><span class="icon-expand-2"></span>' + COM_RESPONSIVIZER_SCREEN_SIZE + '</span>' +
						    '<input type="number" class="sizes" placeholder="Size 1" min="100" max="20000" step="1" id="h1" value="">' +
						    '<input type="number" class="sizes" placeholder="Size 2" min="100" max="20000" step="1" id="h2" value=""></div>');

		/**
		 * Control to refresh application and iframe state
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var refreshButton = $('<button class="btn btn-small" type="button" id="refresh_trigger"><span class="icon-refresh"></span>Refresh</button>');

		/**
		 * Control to change orientation for device iframe
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var orientationSwitcher = $('<button class="btn btn-small" type="button" id="orientation_switcher"><span class="icon-loop"></span>Rotate</button>');

		/**
		 * Control to toggle device emulator toolbars
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var toggleToolbarButton = $('<button class="btn btn-small" type="button" id="toggle_toolbars"><span class="icon-move"></span>Scrolling</button>');
		
		/**
		 * Control to toggle device emulator toolbars
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var reloadToolbarButton = $('<button class="btn btn-small" type="button" id="reload_device"><span class="icon-cogs"></span>Reload</button>');

		/**
		 * Controls that contain labels for stats about window sizes and pixel
		 * ratio
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var statsLabels = $('<span class="label" id="windowsize">-</span>');
		
		/**
		 * Current device label
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var currentDeviceLabel = $('<span class="label label-info" id="currentdevice"></span>');
		
		/**
		 * Closing button
		 * 
		 * @access private
		 * @var String
		 */
		var closeButton = '<button class="btn btn-mini" type="button" id="closer"><span class="icon-remove"></span>Close</button>';
		
		/**
		 * Waiter image
		 * 
		 * @access private
		 * @var Object jQuery
		 */
		var waiter = $('<img id="waiter" src="' + responsivizerBaseURI + 'administrator/components/com_responsivizer/images/loading.gif" />');

		/**
		 * Default options for Emulator App
		 * 
		 * @access private
		 * @var Object
		 */
		var emulatorOptions = {
			device : 'apple_iphone_5-6',
			deviceType: 'phones',
			orientation : 'portrait',
			iFrameSource : window.location.href,
			showRules : true,
			scrollBars : true,
			showStatsWindow : true
		};

		/**
		 * CUSTOMS EVENTS HANDLERS SECTION
		 */

		/**
		 * Manage orientation change
		 * 
		 * @access private
		 * @param Object
		 *            event
		 * @return Void
		 */
		var onRotateDevice = function(event) {
			var device = event.device;
			
			// Get current dimensions
			var currentWidth = $(device).width();
			var currentHeight = $(device).height();
			var currentH1Value = $('#h1').val();
			var currentH2Value = $('#h2').val();
			
			// Get new final orientation
			var detectedOrientation = currentHeight < currentWidth ? 'portrait' : 'landscape';
			if(detectedOrientation == 'portrait') {
				var bunchOfPaddingDuringAnimation = {
					'padding-top': 80,
					'padding-right': 27,
					'padding-bottom': 80,
					'padding-left': 27
				};
			} else {
				var bunchOfPaddingDuringAnimation = {
					'padding-top': 27,
					'padding-right': 80,
					'padding-bottom': 27,
					'padding-left': 80
				};
			}
			
			// Merge final animation object
			var animations = {
					width: currentHeight,
					height: currentWidth
					};
			$.extend(animations, bunchOfPaddingDuringAnimation);
			
			// Inversion of sizes
			$(device).animate(animations,{
				duration: 500,
				start: function(deferred) {
					$(device).addClass('animating');
				},
				complete: function(deferred) {
					$(device).removeClass('portrait landscape animating').addClass(detectedOrientation);
				},
				always: function(deferred) {
					// Inversion of sizes for input number controls
					$('#h2').val(currentH1Value);
					$('#h1').val(currentH2Value);
				}
			});
		}

		/**
		 * Refresh application state and sizes
		 * 
		 * @access private
		 * @param Object
		 *            event
		 * @return Void
		 */
		var onRefresh = function(event) {
			// Get new final orientation
			var detectedOrientation = event.h1 < event.h2 ? 'portrait' : 'landscape';
			if(detectedOrientation == 'portrait') {
				var bunchOfPaddingDuringAnimation = {
					'padding-top': 80,
					'padding-right': 27,
					'padding-bottom': 80,
					'padding-left': 27
				};
			} else {
				var bunchOfPaddingDuringAnimation = {
					'padding-top': 27,
					'padding-right': 80,
					'padding-bottom': 27,
					'padding-left': 80
				};
			}
			
			var animations = {
					width: event.h1,
					height: event.h2
				};
			$.extend(animations, bunchOfPaddingDuringAnimation);
			// Animate to new dimensions the device iframe
			$('#emulator_iframe').animate(animations, 500, function(){
				$('#emulator_iframe').removeClass('portrait landscape animating').addClass(detectedOrientation);
			});
		}
		
		/**
		 * Refresh toolbars state for iframe device emulator
		 * 
		 * @access private
		 * @param Object
		 *            event
		 * @return Void
		 */
		var onToggleToolbars = function(event) {
			var device = event.device;
			
			// Toggle scrolling state for iframe device
			$(device).attr('scrolling', function(idx, oldAttr){
				if(oldAttr === 'undefined') {
					oldAttr = 'no';
				} else {
					oldAttr = oldAttr == 'no' ? 'yes' : 'no';
				}
				return oldAttr;
			});
			
			// Cloning iframe to apply changes for sccrollbars
			var clonedIFrameDevice = $(device).clone();
			
			// Append and remove original device iframe
			$(device).hide().after(clonedIFrameDevice).remove();
		}

		/**
		 * Reload the iframe device emulator
		 * 
		 * @access private
		 * @param Object
		 *            event
		 * @return Void
		 */
		var onReloadDeviceIFrame = function(event) {
			var device = event.device;
			
			// Double assignment to refresh
			device.attr("src", device.attr("src"));
		}
		
		/**
		 * Manage devices change by dropdown interaction
		 * 
		 * @access private
		 * @param Object
		 *            event
		 * @return Void
		 */
		var onChangeDevice = function(event) {
			// Get event vars
			var switch2Device = event.device;
			var switch2Family = event.family;
			var switch2H1 = parseInt(event.h1);
			var switch2H2 = parseInt(event.h2);
			
			// Change dropdown elements state to create pathway to active device
			$('ul.nav li').removeClass('container_active');
			$('ul.nav li[data-family=' + switch2Family + ']').addClass('container_active');
			$('ul.dropdown-menu li').removeClass('active').removeAttr('class');
			$('ul.dropdown-menu li[data-device=' + switch2Device + ']').addClass('active');
			
			// Set label with user device name
			var userDeviceName = switch2Device.replace(/_/g, ' ');
			userDeviceName = ucFirstAllWords(userDeviceName);
			$('#currentdevice').text(userDeviceName);
			
			// Set dimensions for number input
			$('#h1').val(switch2H1);
			$('#h2').val(switch2H2);
			
			// Now animate to new dimensions the device iframe
			var bunchOfPaddingDuringAnimation = {
					'padding-top': 80,
					'padding-right': 27,
					'padding-bottom': 80,
					'padding-left': 27
				};
			var animations = {
					width: switch2H1,
					height: switch2H2
				};
			$.extend(animations, bunchOfPaddingDuringAnimation);
			$('#emulator_iframe').animate(animations, 500, function(){
				$('#emulator_iframe').removeClass('portrait landscape animating').addClass('portrait');
			});
		}
		
		/**
		 * Get infos about device pixel ratio between DIP and physical pixel
		 * 
		 * @access private
		 * @return Int
		 */
		var getDeviceStatsInfo = function () {
			var pixelRatio = Math.round(window.devicePixelRatio) || 1;
			
			return pixelRatio;
		}
		
		/**
		 * Capitalize all words in phrase
		 * 
		 * @access private
		 * @return Object
		 */
		var ucFirstAllWords = function ( str ) {
		    var pieces = str.split(" ");
		    for ( var i = 0; i < pieces.length; i++ ) {
		        var j = pieces[i].charAt(0).toUpperCase();
		        pieces[i] = j + pieces[i].substr(1);
		    }
		    return pieces.join(" ");
		}
		
		/**
		 * Register user events for interface controls
		 * Based on jQuery custom events
		 * 
		 * @access private
		 * @return Void
		 */
		var registerEvents = function () {
			// Register closing button
			$(document).on('click.emulator', '#closer', {context:this}, function(event) {
				event.data.context.close();
			});
			
			// Register dropdown change device
			$(document).on('click.emulator', 'ul.dropdown-menu li', function(event){
				$.event.trigger({
						type: 'onChangeDevice.emulator',
						device: $(this).data('device'),
						family: $(this).data('family'),
						h1: $(this).data('h1'),
						h2: $(this).data('h2')
					});
			});
			// Event subscriber notify
			$(document).on('onChangeDevice.emulator', onChangeDevice);
			
			// Window resize event binding
			$(window).resize(function(event){
				var currentViewportWidth = $(this).width();
				var currentViewportHeight = $(this).height();
				// Refresh current width
				$(statsLabels).text(COM_RESPONSIVIZER_SCREEN_SIZE + currentViewportWidth + COM_RESPONSIVIZER_RESOLUTION_CHARACTER + currentViewportHeight + COM_RESPONSIVIZER_PIXEL_RATIO + getDeviceStatsInfo());
			});
			
			// Register dropdown change device
			$(document).on('click.emulator', '#refresh_trigger', function(event){
				var newH1 = $('#h1').val();
				var newH2 = $('#h2').val();
				$.event.trigger({
					type: 'onRefresh.emulator',
					h1: newH1,
					h2: newH2
				});
			});
			// Event subscriber notify
			$(document).on('onRefresh.emulator', onRefresh);
			
			// Register change orientation
			$(document).on('click.emulator', '#orientation_switcher', function(event){
				var deviceIFrame = $('#emulator_iframe');
				$.event.trigger({
					type: 'onRotateDevice.emulator',
					device: deviceIFrame
				});
			});
			// Event subscriber notify
			$(document).on('onRotateDevice.emulator', onRotateDevice);
			
			// Register toggle scrollbars
			$(document).on('click.emulator', '#toggle_toolbars', function(event){
				var deviceIFrame = $('#emulator_iframe');
				$.event.trigger({
					type: 'onToggleToolbars.emulator',
					device: deviceIFrame
				});
			});
			// Event subscriber notify
			$(document).on('onToggleToolbars.emulator', onToggleToolbars);
			
			// Register toggle scrollbars
			$(document).on('click.emulator', '#reload_device', function(event){
				var deviceIFrame = $('#emulator_iframe');
				$.event.trigger({
					type: 'onReloadDeviceIFrame.emulator',
					device: deviceIFrame
				});
			});
			// Event subscriber notify
			$(document).on('onReloadDeviceIFrame.emulator', onReloadDeviceIFrame);
		}

		/**
		 * METHODS SECTION for emulator application
		 */

		/**
		 * Start application injecting inside DOM
		 * 
		 * @access public
		 * @return Void
		 */
		this.open = function() {
			// Append controls to toolbar
			$(toolbar).append(devicesDropdowns, 
							  sizesInputs, 
							  refreshButton,
							  orientationSwitcher, 
							  toggleToolbarButton,
							  reloadToolbarButton,
							  statsLabels,
							  closeButton,
							  waiter);
			
			// Append toolbar and iframe to main wrapper
			var emulatorHtml = wrapper.append(toolbar, currentDeviceLabel, emulatorIFrame);
			
			// Append application DOM to body element
			$('body').append(emulatorHtml).hide().fadeIn(500);
			
			// Remove waiter when iframe loaded
			$('#emulator_iframe').on('load', function(event){
				$('#waiter').remove();
				
				// Edit links inside iframe to propagate emulator session
				var child_frame = window.frames['emulator_iframe'];
			    if (child_frame) {
			        var cont = child_frame.contentWindow || child_frame.contentDocument;
			        // cont might be the iFrame window or the document
			        if (cont) {
			            iFrameDocument = cont.document || cont;
			        // For current browsers that don't have the above
			        } else {
			        	iFrameDocument = child_frame.document
			        }   
			    }
			    
				$('a', iFrameDocument).each(function(index, element){
					// Initial state
					var currentHRef = $(element).attr('href');
					if(currentHRef && currentHRef != '#') {
						// Found query string
						if(currentHRef.match(/\?/gi)) {
							currentHRef += '&template=' + responsiveTemplate;
						} else {
							// No query string found in URL
							currentHRef += '?template=' + responsiveTemplate;
						}
						// New state
						$(element).attr('href', currentHRef);
					}
				});
			});
		};

		/**
		 * Shutdown application removing DOM elements
		 * 
		 * @access public
		 * @return Void
		 */
		this.close = function() {
			$(wrapper).fadeOut(200, function(){$(this).remove()});
			var originalBodyStyles = $('body').data('bodystyles');
			$.each(originalBodyStyles, function(property, value){
				$('body').css(property, value);
			});
		};

		/**
		 * Refresh application state
		 * 
		 * @access public
		 * @return Void
		 */
		this.refresh = function() {
			// Trigger main native event
			$('#refresh_trigger').trigger('click.emulator');
		};

		/**
		 * Rotate invert H1/H2 dimensions on width/height of iframe Should also
		 * change the portrait/landscape CSS class width<height = portrait mode
		 * width>height = landscape mode
		 * 
		 * @access public
		 * @return Void
		 */
		this.rotate = function() {
			// Trigger main native event
			$('#orientation_switcher').trigger('click.emulator');
		};

		/**
		 * Change selected device specifications and refresh application
		 * 
		 * @access public
		 * @param String datadevice
		 * @return Void
		 */
		this.changeDevice = function(datadevice) {
			// Trigger event for click programmatically by data device attribute
			$('ul.dropdown-menu li[data-device=' + datadevice + ']').trigger('click.emulator');
		};

		/**
		 * Toggle toolbars for iframe
		 * 
		 * @access public
		 * @return Void
		 */
		this.toggleToolbars = function() {
			// Trigger event for click programmatically
			$('#toggle_toolbars').trigger('click.emulator');
		};

		/**
		 * Function dummy constructor
		 * 
		 * @access private
		 * @param Object
		 *            options
		 * @method <<IIFE>>
		 * @return Void
		 */
		(function __construct() {
			$.extend(emulatorOptions, options);

			// Get default device information
			activeDevice = emulatorOptions.device;
			activeDeviceType = emulatorOptions.deviceType;
			// Set state active on family container <li> item
			devicesDropdowns.find('#' + activeDeviceType + '_container').addClass('container_active');
			
			// Set dimensions
			H1 = devices[emulatorOptions.deviceType][emulatorOptions.device]['H1'];
			H2 = devices[emulatorOptions.deviceType][emulatorOptions.device]['H2'];
			// Set dimensions for input 
			$(sizesInputs).find('#h1').val(H1);
			$(sizesInputs).find('#h2').val(H2);
			
			// Populate dropdowns for devices
			$.each(devices, function(family, subdevices){
				$.each(subdevices, function(deviceName, elem){
					var activeClass = deviceName == activeDevice ? 'class="active"' : '';
					var userDeviceName = deviceName.replace(/_/g, ' ');
					userDeviceName = ucFirstAllWords(userDeviceName);
					var listItem = $('<li ' + activeClass + ' data-family="' + family + '" data-device="' + deviceName + '" data-h1="' + elem.H1 + '" data-h2="' + elem.H2 + '">' + userDeviceName + 
									 '<span class="resolution_detail">' + elem.H1 + COM_RESPONSIVIZER_RESOLUTION_CHARACTER + elem.H2 + 
									 '</span><span class="manufacturer ' + elem.manufacturer + '"></span></li>');
					$(devicesDropdowns).find('#'+family).append(listItem);
					// Manage label for current active device
					if(deviceName == activeDevice) {
						$(currentDeviceLabel).text(userDeviceName);
					}
				});
			});
			
			/**
			 * Init elements attribute
			 */ 
			// Init controls
			$('#device_select').val(emulatorOptions.device);
			 
			// Init main iframe
			$(emulatorIFrame).attr({'src': emulatorOptions.iFrameSource,
									'class': emulatorOptions.orientation}).css({
									'width': H1 + 'px',	
									'height': H2 + 'px'
									});

			// Registering user events when elements on page
			registerEvents.call(this);
			
			// Init body styles to make emulator span fully
			var originalBodyStyles = {};
			originalBodyStyles.padding = $('body').css('padding');
			originalBodyStyles.margin = $('body').css('margin');
			$('body').data('bodystyles', originalBodyStyles);
			$('body').css({'position':'relative',
						   'margin':0,
						   'padding':0
						});
			
			
			// Trigger window resize
			$(window).trigger('resize');
		}).call(this);

	};

	// Make it global to instantiate as plugin
	window.ResponsivizerEmulator = Emulator;
})(jQuery);