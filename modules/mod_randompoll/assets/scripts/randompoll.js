var RandomPollsFactory = null;

(function($){
	
	RandomPollsFactory = function(ctnr){
			
		var container = ctnr;
		var formsubmitted = false;
		
		this.load_gpie_chart = function(poll){
			
			var ctnr = $('.' + container);
			
			var colors = $.parseJSON(ctnr.find('.rp_color_pallete').html());
			var width = parseInt(ctnr.find('.rp_chart_width').text());
			var height = parseInt(ctnr.find('.rp_chart_height').text());
			
			var answers = (null == poll) ? $.parseJSON(ctnr.find('.rp_poll_answers').html()) : poll.answers;
			
			var rows = new Array();
			var row = null;
			
			$.each(answers, function(i, answer){
				row = new Array();
				row.push(answer.title);
				row.push(parseInt(answer.votes));
				rows.push(row);
			});
			
			var data = new google.visualization.DataTable();
			
			data.addColumn('string', $('.lbl_answers').html());
			data.addColumn('number', $('.lbl_votes').html());
			data.addRows(rows);
			
			var chartcolors = new Array();
			
			for(var i=0; i<answers.length; i++){
				
				chartcolors = chartcolors.concat(colors[i % colors.length]);
			}
			
			new google.visualization.PieChart(ctnr.find('.rp-gpie-chart').get(0)).draw(data, {'is3D': true, 'legend': 'none', 'width': width, 'height': height, 'colors': chartcolors});			
		};

		this.init_form = function(){
			
			var thisobj = this;
			
			$('.' + container).find('.rp-btn-result, .rp-btn-form').click(function(){
				$('.' + container).find('.rp-poll-form').slideToggle('slow');
				$('.' + container).find('.rp-results').slideToggle('slow');
				$('.' + container).find('.rp-btn-form, .rp-btn-result, .rp-btn-vote').toggle();
			});
			
			$('.' + container).find('.rp-btn-vote').click(function(){
				
				if($('.' + container).find('.rp-poll-form').find('input:checked').length == 0 && $('.' + container).find('.rp-poll-form').find('input[name="custom_answer"]').val() == ''){
					
					alert($('.' + container).find('.lbl_no_answers_selected').html());
				}else{
					
					var answers = Array();
					var custom_answer = '';
					var params = new Object();
					
					if(formsubmitted) {
						return false; 
					} else {
						formsubmitted = true;
					}
					
					params.charttype = $('.' + container).find('.rp_chart_type').text();
					params.chartwidth = parseInt($('.' + container).find('.rp_chart_width').text());
					params.chartheight = parseInt($('.' + container).find('.rp_chart_height').text());
					
					var ajxdata = $.extend({}, params);
					
					$('.' + container).find('.rp-poll-form').find('input:checked').each(function(){
						
						answers.push($(this).val());
					});
					
					if($('.' + container).find('.rp-poll-form').find('input[name="custom_answer"]').length > 0){
						
						custom_answer = $('.' + container).find('.rp-poll-form').find('input[name="custom_answer"]').val();
					}
					
					var poll_type = $('#pollType').val();
					if(poll_type == 'radio' && answers.length > 0 && $.trim(custom_answer).length > 0)
					{
						alert($('.' + container).find('.lbl_no_select_one_answer').html());
						formsubmitted = false;
						return false;
					}
					
					$.ajax({
						
						url : $('.' + container).find('.rp_url_vote').text(),
		                type : 'post',
		                dataType: 'json',
	                    data: {id: $('.' + container).find('.rp-poll-form').find('.pollId').val(), 'answers': answers, 'custom_answer': custom_answer, 'params': ajxdata,
	                    	'recaptcha_response_field': $('#recaptcha_response_field').val(), 'recaptcha_challenge_field': $('#recaptcha_challenge_field').val()},
	                    
	                    success: function(data) {
	                    	
	                        if(typeof data.error != 'undefined' && data.error.length > 0){
	                        	
	                        	$('.' + container).find('.rp-poll-messages').show().find('.rp-poll-end-message').html(data.error);
	    						
	    						if(typeof Recaptcha != 'undefined'){
	    	    					Recaptcha.reload();
	    	    				}
	                        }else{
	                        	
	                        	$('.' + container).find('.rp-poll-messages').show().find('.rp-poll-end-message').html(data.poll.end_message);
	                        	
	                        	if(data.poll.results){

	                        		if( data.poll.type == 'grid' ){
	                        			
	                        			$.each(data.poll.answers, function(i, row){
	                        				
	                        				$.each(data.poll.columns, function(j, col){
	                        					
	                        					$.each(data.poll.gridvotes, function(k, vote){
	                        						
	                        						if(vote.option_id == row.id && vote.column_id == col.id){
	                        							
	                        							$( '.' + container).find('.answer-' + row.id + '-' + col.id).text( vote.votes );
	                        							
	                        							return false;
	                        						}
	                        					});
	                        				});
	                        			});
	                        		} else {
	                        			
		                        		if(data.poll.chart_type == 'bar' || data.poll.chart_type == 'pie'){
		                        			
		                        			d = new Date();
		                        			$('.' + container).find('.rp-results-src').html( $('<img>', {src: data.poll.src + '?' + d.getTime()}) );
		                        		}
		                        		
		                        		if(data.poll.chart_type == 'pie' || data.poll.chart_type == 'sbar' || data.poll.chart_type == 'gpie'){
			                        		
			                        		$.each(data.poll.answers, function(index, answer){
			                        			
			                        			$( '.' + container).find('.answer-' + answer.id ).find('.votecount').text( answer.votes );
			                        			$( '.' + container).find('.answer-' + answer.id ).find('.rp-sbar-pct').text( answer.pct + '%' );
			                        			$( '.' + container).find('.answer-' + answer.id ).find('.rp-sbar-bar').css('width', answer.pct + '%');
			                        		});
				                        	
				                        	if( $('.' + container).find('.rp-gpie-chart').length > 0 ){
				                        		
				                        		thisobj.load_gpie_chart(data.poll);
				                        	}
		                        		}
	                        		}
	                        		
	                				$('.' + container).find('.rp-poll-form').hide('slow');
	                				$('.' + container).find('.rp-results').show('slow');
	                				$('.' + container).find('.rp-buttons-wrapper').hide('slow');
	                        	}
	                        }
	                        
	                        $('.' + container).find('.rp-anim-icon').hide();
	                        formsubmitted = false;
	                    },
	                    
	                    beforeSend: function(){
	                    	
	                    	$('.' + container).find('.rp-anim-icon').insertAfter($('.' + container).find('.rp-btn-vote')).show();
	                    }
					});
				}
				
				return false;
			});
		};
		
		this.init_charts = function(){
			
			if( $('.' + container).find('.rp-gpie-chart').length > 0 ){
				
				this.load_gpie_chart(null);
			}
			
			$('#btn_custom_answers').click(function(){
				$('.tab-custom-answers').toggle('slow');
			});
		};
	};
})(jQuery);