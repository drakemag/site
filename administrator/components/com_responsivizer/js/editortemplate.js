/**
* JS APP to manage template editor iframebox for com_templates
* 
* @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer 
* @subpackage js 
* @author Joomla! Extensions Store
* @copyright (C)2014 Joomla! Extensions Store
* @license GNU/GPLv2 or later, see license.txt
*/
//'use strict';
(function($) {
	var EditorTemplate = function (IFrameSelector) {
		/**
		 * Target switcher HTMLElement
		 * 
		 * @access private
		 * @var Object
		 */
		var targetIFrame = null;
		
		/**
		 * Mimic submitButton for inner com_templates from external context
		 * 
		 * @access private
		 * @param String task
		 * @return Boolean
		 */
		function mimicSubmitButton(task){
			// Call inner Joomla submitbutton
			window.frames['com_templates'].Joomla.submitbutton(task);
		};
		
		/**
		 * Manage direct iframe injection and manipulation being on same domain
		 * 
		 * @access private
		 * @param Object element
		 * @return Void
		 */
		function manageIFrameContents(iFrame) {
			// Manipulate all inner links to append responsivizer recognize param working with notoolbar plugin, also if not mandatory managed by session
			var innerIFrameLinks = $(iFrame).contents().find('a');
			$.each(innerIFrameLinks, function(index, link){
				var currentHRef = $(link).attr('href');
				currentHRef = currentHRef + '&rvzr=1';
				// Set back
				$(link).attr('href', currentHRef);
			});
			
			// Remove waiter
			$(iFrame).parent().children('img').remove();
		};
		
		/**
		 * Inject additional toolbar buttons needed when in edit source code mode 
		 * 
		 * @access public
		 * @return void
		 */
		this.injectToolbarButtons = function() {
			var toolbarElement = $('#toolbar');
			
			var saveSnippet = '<div class="btn-wrapper prepended" id="toolbar-save">' +
							  '<button class="btn btn-small btn-success">' +
							  '<span class="icon-apply icon-white"></span>' +
							  'Save changes</button>' +
							  '</div>';
			
			// Remove and prepend buttons
			$('div.prepended', toolbarElement).remove();
			$(toolbarElement).prepend(saveSnippet);

			// Bind onlick on save button
			$('#toolbar-save').on('click', function(){
				var manageJVersion = !!targetIFrame.data('jversion');
				if(manageJVersion) {
					mimicSubmitButton('source.apply');
				} else { // New 3.2+
					mimicSubmitButton('template.apply');
				}
			});
		};
		
		
		 /**
		 * Function dummy constructor
		 * 
		 * @access private
		 * @method <<IIFE>>
		 * @return Void
		 */
        (function __construct() {
        	var context = this;
        	targetIFrame = $(IFrameSelector);
        	
        	// Init deferred
        	var templateIFrameLoaded = $.Deferred();
        	
        	// Start preparing IFrame
        	$.when(templateIFrameLoaded).done(function(response){
        		manageIFrameContents(targetIFrame);
        	});
        	
        	// Wait for iframe load event
        	targetIFrame.on('load', function(){
				setTimeout(function(){
					templateIFrameLoaded.resolve();
				}, 500);
			});
        	
        	// If we are in source code edit mode try to expand editor height to 100%
			var sourceEditorContainer = $(targetIFrame).contents().find('*[class*=wrapping]');
			$(sourceEditorContainer).css('height', '100%');
        	
        }).call(this);
    }

    // On DOM Ready
    $(function () {
        ResponsivizerEditorTemplate = new EditorTemplate('#com_templates');
    });
})(jQuery);