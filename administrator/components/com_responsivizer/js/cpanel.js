/**
 * CPanel main JS APP class, manage chart generation
 * 
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer 
 * @subpackage js 
 * @author Joomla! Extensions Store
 * @copyright (C)2014 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
*/
//'use strict';
(function ($) {
    var CPanel = function(targetSelector) {
    	/**
		 * Reference to ChartJS lib object
		 * 
		 * @access private
		 * @var Object
		 */
    	var chartJS;
    	
    	/**
		 * Charts options
		 * 
		 * @access private
		 * @var Object
		 */
    	var chartOptions = {animation:true, scaleFontSize: 11};
    	
    	/**
		 * Chart data to render, copy from global injected scope
		 * 
		 * @access private
		 * @var Object
		 */
    	var chartData = {};
    	
    	/**
		 * Element target to render chart
		 * 
		 * @access private
		 * @var HTMLElement
		 */
    	var context;

        /**
		 * Interact with ChartJS lib to generate charts
		 * 
		 * @access private
		 * @return Void
		 */
        function generateLineChart(animation) {

        	// Instance Chart object lib
        	var chartJS = new Chart(context);
        	
        	// Normalize chart data to render
        	chartData.labels = new Array();
        	chartData.datasets = new Array();
        	var subDataSet = new Array();
            $.each(responsivizerChartData, function(label, value){
            	var labelSuffix = label.replace(/([A-Z])/g, "_$1").toUpperCase()
            	chartData.labels[chartData.labels.length] = eval('COM_RESPONSIVIZER_' + labelSuffix + '_CHART');
            	subDataSet[subDataSet.length] = value;
            });
            
            chartData.datasets[0] = {
            		fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					data : subDataSet
            };
        	
            // Override options
            chartOptions.animation = animation;
            
            // Paint chart on canvas
        	chartJS.Line(chartData, chartOptions);
        }
        
        /**
		 * Make fluid canvas width with repaint on resize
		 * 
		 * @access private
		 * @return Void
		 */
        function resizeRepaintCanvas(animation) {
        	// Get HTMLCanvasElement
            var canvas = $(targetSelector).get(0);
            // Get parent container width
            var containerWidth = $(canvas).parent().width();
            // Set dinamically canvas width
            canvas.width  = containerWidth;
            canvas.height = 360;
            // Repaint canvas contents
            generateLineChart(animation);
        }
        
        /**
		 * Function dummy constructor
		 * 
		 * @access private
		 * @param String contextSelector
		 * @method <<IIFE>>
		 * @return Void
		 */
        (function __construct() {
            // Get target canvas context 2d to render chart
        	if(!!document.createElement('canvas').getContext) {
        		context = $(targetSelector).get(0).getContext('2d');
        		
        		$(window).on('resize', function(){
            		resizeRepaintCanvas();
            	})
            	
            	// Start generation
            	resizeRepaintCanvas(true);
        	}
        	
        	// Bind run Emulator application on CPanel icon click
        	$('a.triggerEmulator').popover({trigger:'hover', placement:'right', html:1});
        	$('a.triggerEmulator').on('click', function(event) {
        		event.preventDefault();
        		// Start JS APP for device emulator - options server side manageable
        		var emulatorInstance = new ResponsivizerEmulator({ device : 'apple_iphone_5-6',
        														   deviceType: 'phones',	
												        		   orientation : 'portrait',
												        		   iFrameSource: responsivizerBaseURI + '?template=' + responsiveTemplate,
												        		   showRules : true,
												        		   scrollBars : true,
												        		   showStatsWindow : true
												        		});
        		emulatorInstance.open();
        		
        		return false;
        	});
        }).call(this);
    }

    // On DOM Ready
    $(function () {
        var ResponsivizerCPanel = new CPanel('#chart_canvas');
    });
})(jQuery);