/**
* Config class for configurations advanced management
* 
* @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer 
* @subpackage js 
* @author Joomla! Extensions Store
* @copyright (C)2014 Joomla! Extensions Store
* @license GNU/GPLv2 or later, see license.txt
*/
//'use strict';
(function ($) {
    var Config = function() {
    	/**
		 * Images counter currently active
		 * @access private
		 * @var Object
		 */
    	var numOfImages = 1;
    	
    	/**
    	 * Mapping of themes and control settings values to automate predefined styles
    	 * @access private
    	 * @var Object
    	 */
    	var stylesMapping = {
			'jform_predefined_theme_00' : {
				'jform_general_background' : 'white',
				'jform_color_theme' : 'blue',
				'jform_font_content' : 'Ubuntu',
				'jform_font' : 'Ubuntu',
				'jform_fontSize' : '1.00em',
				'jform_main_background_image_element0' : {'action' : 'click', 'target' : 'jform_main_background_image_element0'}
			},
			'jform_predefined_theme_10' : {
				'jform_general_background' : 'black',
				'jform_color_theme' : 'white',
				'jform_font_content' : 'Metrophobic',
				'jform_font' : 'Maven+Pro',
				'jform_fontSize' : '1.00em',
				'jform_main_background_image_element1' : {'action' : 'click', 'target' : 'jform_main_background_image_element1'}
			},
			'jform_predefined_theme_20' : {
				'jform_general_background' : 'android',
				'jform_color_theme' : 'android',
				'jform_font_content' : 'Ubuntu',
				'jform_font' : 'Ubuntu',
				'jform_fontSize' : '1.10em',
				'jform_main_background_image_element1' : {'action' : 'click', 'target' : 'jform_main_background_image_element1'}
			},
			'jform_predefined_theme_30' : {
				'jform_general_background' : 'ios',
				'jform_color_theme' : 'ios',
				'jform_font_content' : 'Ubuntu',
				'jform_font' : 'Ubuntu',
				'jform_fontSize' : '1.10em',
				'jform_main_background_image_element1' : {'action' : 'click', 'target' : 'jform_main_background_image_element1'}
			},
			'jform_predefined_theme_40' : {
				'jform_general_background' : 'gray',
				'jform_color_theme' : 'blue',
				'jform_font_content' : 'Nobile',
				'jform_font' : 'Nobile',
				'jform_fontSize' : '1.00em',
				'jform_main_background_image_element1' : {'action' : 'click', 'target' : 'jform_main_background_image_element1'}
			},
			'jform_predefined_theme_50' : {
				'jform_general_background' : 'white',
				'jform_color_theme' : 'blue',
				'jform_font_content' : 'Chicle',
				'jform_font' : 'Trade+Winds',
				'jform_fontSize' : '1.10em',
				'jform_main_background_image_element1' : {'action' : 'click', 'target' : 'jform_main_background_image_element1'}
			},
			'jform_predefined_theme_60' : {
				'jform_general_background' : 'green',
				'jform_color_theme' : 'orange',
				'jform_font_content' : 'Ribeye+Marrow',
				'jform_font' : 'Ribeye+Marrow',
				'jform_fontSize' : '1.00em',
				'jform_main_background_image_element1' : {'action' : 'click', 'target' : 'jform_main_background_image_element1'}
			},
			'jform_predefined_theme_70' : {
				'jform_general_background' : 'blue',
				'jform_color_theme' : 'yellow',
				'jform_font_content' : 'Kreon',
				'jform_font' : 'Kreon',
				'jform_fontSize' : '1.00em',
				'jform_main_background_image_element1' : {'action' : 'click', 'target' : 'jform_main_background_image_element1'}
			},
			'jform_switcher_menutype0' : {
				'jform_menuitem_width' : '45',
				'jform_menuitem_vert_padding' : '5',
				'jform_menuitem_horiz_padding' : '10',
				'jform_submenuitem_splacement' : '40',
				'jform_menutext_alignment' : 'center',
				'jform_submenu_open_mode' : 'absolute',
				'jform_transition_menu0' : {'action' : 'click', 'target' : 'jform_transition_menu0'}
			},
			'jform_switcher_menutype1' : {
				'jform_menuitem_width' : '100',
				'jform_menuitem_vert_padding' : '5',
				'jform_menuitem_horiz_padding' : '10',
				'jform_submenuitem_splacement' : '0',
				'jform_menutext_alignment' : 'center',
				'jform_submenu_open_mode' : 'relative',
				'jform_transition_menu1' : {'action' : 'click', 'target' : 'jform_transition_menu1'}
			}
		}
    	
    	/**
		 * Public interface to add image rows
		 * 
		 * @access public
		 * @return Void
		 */
        this.insertImageRow = function(targetButton) {
            var lastID = '_' + (Number(getMaxId()) + 1);
            numOfImages = $('#module_images input[id^=jform_image_]').length;
            var last5Divs = $('div#module_images > ul > li > div').slice(-5);
            var cloned5Divs = $(last5Divs).clone();
            $(cloned5Divs).find('textarea').text('');
            $(cloned5Divs).find('input').attr('value', '');

            var cleanedClonedHtml = '<li>';
            $.each(cloned5Divs, function(k, elem){
            	cleanedClonedHtml += '<div class="control-group">' + $(elem).html() + '</div>';
            });
            cleanedClonedHtml += '</li>';
            cleanedClonedHtml = cleanedClonedHtml.replace(/(image)(_\d+)/gi, '$1' + lastID);
            cleanedClonedHtml = cleanedClonedHtml.replace(/(alt)(_\d+)/gi, '$1' + lastID);
            cleanedClonedHtml = cleanedClonedHtml.replace(/(caption)(_\d+)/gi, '$1' + lastID);
            cleanedClonedHtml = cleanedClonedHtml.replace(/(link)(_\d+)/gi, '$1' + lastID);
            cleanedClonedHtml = $(cleanedClonedHtml).find('img').attr('src', responsivizerBaseURI + 'administrator/components/com_responsivizer/images/no-image.png').end();
            $('div#module_images ul').append(cleanedClonedHtml);
            if (numOfImages == 1) {
            	$('.dialog_trigger').after('<button class="btn btn-small btn-danger deleterbtn"><span class="icon-remove"></span><span class="label-icon">' + COM_RESPONSIVIZER_REMOVEIMAGE + '</span></button>');
            }
        }
        
        /**
		 * Public interface to delete image rows
		 * 
		 * @access public
		 * @return Void
		 */
        this.deleteImageRow = function(deleterElement) {
            $(deleterElement).parent().parent().nextAll().andSelf().slice(0, 5).remove();
            numOfImages = $('#module_images input[id^=jform_image_]').length;
            if (numOfImages == 1) {
                $('button.deleterbtn').remove();
            }
            // Reordering
            reorderImages();
        }
       	
        /**
         * Reordering algorythm
         * 
         * Used on delete of images and on sorting of images
         * @access private
         * @return Void
         */
        function reorderImages() {
        	var currentIndexReordered = 1;
        	$.each($('input,select,textarea', '#module_images'), function (i, target) {
				var currentID = $(target).attr('id');
                var tempID = (currentID.slice(0, (currentID.lastIndexOf('_') + 1)));
                var currentName = $(target).attr('name');
                var tempName = (currentName.slice(0, (currentName.lastIndexOf('_') + 1)));
                
                if(i > 0) {
                	currentIndexReordered = parseInt(i/4) + 1;
                }

                $(target).attr('id', tempID + currentIndexReordered);
                $(target).attr('name', tempName + currentIndexReordered + ']');
            });
        }
        
        /**
		 * Get current max ID reached
		 * 
		 * @access private
		 * @return Void
		 */
        function getMaxId() {
            var MAX = new Number(0);
            var maxtempid = new Number();
            $('#module_images input[id^=jform_image_]').each(function (e, v) {
                maxtempid = Number($(v).attr('id').substring(12));
                if (maxtempid > MAX) {
                    MAX = maxtempid;
                }
            });
            return MAX;
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
            numOfImages = $('#module_images input[id^=jform_image_]').length;
            
            $('#module_images').prepend('<button id="adderbtn" class="btn btn-default btn-success"><span class="icon-plus"></span><span class="label-icon">' + COM_RESPONSIVIZER_ADDIMAGE + '</span></button>');
            $('#adderbtn').click(function (event) {
            	event.preventDefault();
            	context.insertImageRow(this);
            	return false;
            });
            
            if (numOfImages > 1) {
                $('.dialog_trigger').after('<button class="btn btn-small btn-danger deleterbtn"><span class="icon-remove"></span><span class="label-icon">' + COM_RESPONSIVIZER_REMOVEIMAGE + '</span></button>');
            }
            
            // Deleter buttons
            $(document).on('click', 'button.deleterbtn', function () {
            	context.deleteImageRow(this);
            });
            
            // Modal iframe dialog images choose
            $(document).on('click', '.dialog_trigger', function(event){
    			event.preventDefault();
    			
    			var prevTarget = $(this).prev().attr('id');
    		    $('#dialog_iframe').attr('src', 'index.php?option=com_media&view=images&tmpl=component&asset=com_responsivizer&author=&fieldid=' + prevTarget);
    			$('#suggestions_modal').modal('show');
    			return false;
    		});
            
            // Intercept modal bootstrap autofocus
    		$('#suggestions_modal').on('shown.bs.modal', function() {
    		    $(document).off('focusin.modal');
    		    $('div.modal.fade.in').animate({'width': '960px', 'margin-left':'-480px'},300)
    		});
    		$('#suggestions_modal').on('hide.bs.modal', function() {
    		    $('div.modal.fade.in').animate({'width': '800px', 'margin-left':'-400px'},300)
    		});
    		$('#suggestions_modal').on('hidden.bs.modal', function() {
    			$('#suggestions_modal').hide();
    		});

    		// Set spacer styles
            $('#sortable .spacer').parent().css({'width': '100%', 'border-bottom': '2px dashed #CCC', 'margin-bottom':'10px'});

            // Start sortable image rows
            $('ul#sortable').sortable({
            	placeholder: 'ui-state-highlight',
            	stop: function(event, ui) {
            		// Reordering
                    reorderImages();
            	}
            });
            
            // Input number types
            try {
            	$('input[class=number]').prop('type', 'number').prop('min', 0).prop('max', 100);
            } catch(e) {
            	// DO NULL
            }
            
            // Management for predefined themes style
            $('fieldset.mutex label, fieldset.switcher label').on('click', function(event){
            	var targetStyle = ($(this).prev().attr('id'));
            	// Assign new styles to controls
            	$.each(stylesMapping[targetStyle], function(control, controlValue){
            		if(typeof(controlValue) !== "object") {
            			$('#' + control).val(controlValue);
            		} else {
            			$('label[for=' + controlValue.target + ']').trigger(controlValue.action);
            		}
            	});
            });
            
            // Create color picker controls
            $("input[id*=_color], input[id*=color_]").after('<div class="colorpicker_preview"><div></div></div>')
            var loadColor = function(elem, colorHex) {
            	// Set input HEX color value
            	$(elem).val(colorHex);
            	$(elem).ColorPickerSetColor(colorHex);
            	
            	// Set background color of preview box
            	var nextElPreview = $(elem).next('div.colorpicker_preview');
        		$('div', nextElPreview).css('background-color', colorHex);
            }
            
            $("input[id*=_color], input[id*=color_]").ColorPicker({
            	onSubmit : function(hsb, hex, rgb, el){
            		loadColor(el, '#' + hex);
            	}
            });
            $("input[id*=_color], input[id*=color_]").each(function(k, elem){
            	var colorValue = $(elem).val();
            	loadColor(elem, colorValue);
            });
        }).call(this);
    }

    // On DOM Ready
    $(function () {
        var ResponsivizerConfig = new Config();
    });
})(jQuery);

/*
 * Legacy service functions for behavior com_media
 */
function jInsertFieldValue(value, id) {
	var old_value = document.id(id).value;
	if (old_value != value) {
		var elem = document.id(id);
		elem.value = value;
		elem.fireEvent("change");
		if (typeof(elem.onchange) === "function") {
			elem.onchange();
		}
		jQuery('#suggestions_modal').modal('hide');
		// Update here selected image preview
		jQuery('#' + id).nextAll('img').attr('src',responsivizerBaseURI + value);
	}
}