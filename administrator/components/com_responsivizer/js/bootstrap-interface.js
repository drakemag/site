// Turn radios into btn-group
jQuery(function($) {
	$('input[type=radio]~label').addClass('radio btn btn-default');
	$('input[type=radio]').hide();
	$('input[type=radio]:first-child').next('label').css({'border-top-left-radius':'5px', 'border-bottom-left-radius':'5px','border-right':'none'});
	$('input[type=radio]~label:last-child').css({'border-top-right-radius':'5px', 'border-bottom-right-radius':'5px','border-left':'none'});
	$('fieldset.mutex label').css({'border-left':'1px solid #bbb', 'border-right':'1px solid #bbb'});
	
	$("input[type=radio]~label:not(.active)").click(
		function(event) {
			var label = $(this);
			var input = $(label).prev();

			if (!input.prop('checked')) {
				label.parent('div, td, span, fieldset').find("label").removeClass('active btn-success btn-danger btn-primary');
				// Mutex handling
				if($(this).parent().hasClass('mutex')) {
					$('fieldset.mutex input').prop('checked', false);
					$('fieldset.mutex label').removeClass('active btn-success btn-danger btn-primary');
				}
				
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
				
			}
		});
	$("input[type=radio][checked=checked]").each(
		function() {
			if ($(this).val() == '') {
				$(this).next('label').addClass(
						'active btn-primary');
			} else if ($(this).val() == 0) {
				$(this).next('label').addClass(
						'active btn-danger');
			} else {
				$(this).next('label').addClass(
						'active btn-success');
			}
	});
	
	/**
	 * Enables bootstrap popover
	 */
	$('label.hasPopover, button.hasPopover, div.hasPopover, span.hasPopover, img.hasPopover').popover({trigger:'hover', placement:'top', html:1});
	
	/**
	 * Enables bootstrap tooltip
	 */
	$('label.hasTooltip, img.hasTooltip, a.hasTooltip').tooltip({trigger:'hover', placement:'top'});
	
	/**
	 * Accordion panels local storage memoize and set open
	 */
	var defaultAccordionObject = {'rvzr_accordion_cpanel':'responsivizer_stats', 
								  'rvzr_accordion_help':'responsivizer_cpanel'
								 };
	$('div.accordion').on('shown.bs.collapse', function (event) {
		if(!$(event.target).hasClass('accordion-body')) {
			return;
		}
		event.stopPropagation();
		$('div.accordion-heading').removeClass('opened');
		var localStorageAccordion = $.jStorage.get('rvzrAccordionOpened', defaultAccordionObject);
		localStorageAccordion[this.id] = event.target.id;
		$.jStorage.set('rvzrAccordionOpened', localStorageAccordion);
		
		// Scroll to accordion header if needed
		if(document.body.scrollHeight > window.innerHeight) {
			$('html, body').animate({ scrollTop: parseInt($("#"+event.target.id).prev().offset().top) - 180}, 500);
		}
		// Add open state
		$(event.target).prev().addClass('opened');
	});
	
	$.each($.jStorage.get('rvzrAccordionOpened', defaultAccordionObject), function(namespace, element) {
		if($('#'+element, '#'+namespace).length) {
			$('#'+element, '#'+namespace).addClass('in').prev().addClass('opened');
		}
	});
	
	/**
	 * Tab panels local storage memoize and set open
	 */
	var defaultTabObject = {'tab_configuration_template':'template_general',
							'tab_configuration_plugin':'plugin_responsivizer',
							'tab_configuration_module':'module_settings'};
	$('.nav.nav-tabs').on('shown.bs.tab', function (event) {
		var localStorageTab = $.jStorage.get('rvzrTabOpened', defaultTabObject);
		localStorageTab[this.id] = $(event.target).data('element');
		$.jStorage.set('rvzrTabOpened', localStorageTab);
	});
	
	// Check for a specific tab trigger using url hash
	var tabRequest = window.location.hash.substr(2);
	if(tabRequest) {
		$('a[data-element=' + tabRequest + ']').tab('show');
	}
	
	$.each($.jStorage.get('rvzrTabOpened', defaultTabObject), function(namespace, element) {
		$('a[data-element='+element+']', '#'+namespace).tab('show');
	});
});