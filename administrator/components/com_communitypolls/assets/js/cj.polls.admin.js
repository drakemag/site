(function($){
	var PollsAdminFactory = {
			
		init_poll_form: function(){
			
	        var upload_button = null;
			$('#poll_closing_date', '#poll_results_up').datepicker({'format': 'yyyy-mm-dd'});
			
			if($('#poll_type').val() == 'grid'){
				$('.grid-columns').show();
			}
			
	        $('#poll_type').change(function(){
	        	
	        	if($(this).val() == 'grid'){
	        		$('.grid-columns').show();
	        	}else{
	        		$('.grid-columns').hide();
	        	}
	        });
	        
	        // add new answer and new grid column button functions.
	        $('.btn-add-answer').click(function(){
	        	$('.poll-answers').find('table').append($('.poll-answer-template').html());
	        	$('.poll-answers').find('.poll-answer:last').find('.input-order').val( $('.poll-answers').find('.poll-answer').length );
	        });
	        $('.btn-add-column').click(function(){
	        	$('.grid-columns').find('table').append($('.grid-column-template').html());
	        	$('.grid-columns').find('.poll-answer:last').find('.input-order').val( $('.grid-columns').find('.poll-answer').length );
	        });
	        
	        $('.poll-answers').on('click', '.btn-add-media', function(){
	        	upload_button = $(this);
	        	$('.input-file-upload').click();
	        });
	        
	        $('.input-file-upload').change(function(){
	        	button = upload_button;
	        	button.find('i').addClass('btn-progress').removeClass('icon-picture');
	        	$('.file-upload-error').hide().find('.error-msg').html('');
	        	
	        	$('#file-upload-form').ajaxSubmit({
					dataType : 'json',
					success: function(data, status, xhr, $form) {
						if(typeof data.error != 'undefined' && data.error.length > 0){
							$('.file-upload-error').show().find('.error-msg').html(data.error);
						}else{
							var template = $('.poll-resource-template');
							template.find('.resource-value').html(data.file_name);
							template.find('input[name="resource-image"]').val(data.file_name);
							
							button.closest('.poll-answer').find('.poll-answer-resources').append(template.html());
						}
						button.find('i:first').addClass('icon-picture').removeClass('btn-progress');
					}
	        	});
	        });
	        
	        $('.poll-answers-block').on('click', '.btn-delete-attachment', function(){
	        	$(this).closest('.poll-resource').remove();
	        });
	        
	        $('.poll-answers-block').on('click', '.btn-delete-answer', function(){
	        	$(this).closest('.poll-answer').remove();
	        });
	        
	        // add url button function of add media block
	        $('.poll-answers-block').on('click', '.btn-add-url', function(){
	        	
	        	var button = $(this);
	        	$('#add-url-modal').modal('show');
	        	
	        	$('#add-url-modal').find('.btn-submit').off().click(function(){
	        		
	        		if($('#add-url-modal').find('input[type="text"]').valid()){
	        			
	            		if(button.closest('.poll-answer').find('.poll-answer-resources').find('input[name="resource-url"]').length > 0){
	            			button.closest('.poll-answer').find('.poll-answer-resources').find('input[name="resource-url"]').closest('.poll-resource').remove();
	            		}
	            		
	    				var template = $('.poll-resource-url-template');
	    				var url = $('#add-url-modal').find('input[type="text"]').val();
	    				
	    				template.find('.resource-value').html(url);
	    				template.find('input[name="resource-url"]').val(url);
	    				template.find('i:first').attr('class', 'icon-globe');
	    				
	            		button.closest('.poll-answer').find('.poll-answer-resources').append(template.html());
	            		
	            		$('#add-url-modal').modal('hide');
	            		$('#add-url-modal').find('input[type="text"]').val('');
	        		}
	        	});
	        });
		},
	
		populate_poll_form: function(){
			
        	var poll_answers = Array();
        	var grid_columns = Array();
        	var answer = null;
        	var order = null;
        	
        	$('.poll-answers').find('.poll-answer').each(function(){
        		
        		answer = $(this).find('.input-answer').val();
        		try{order = parseInt($(this).find('.input-order').val());}catch(e){order = 0;}
        		
        		if(answer != null && answer.length > 0){
        			
	        		var poll_answer = new Object();
	        		
	        		if($(this).find('input[name="answer-id"]').length > 0){
	        			
	        			poll_answer.id = $(this).find('input[name="answer-id"]').val();
	        		}else{
	        			
	        			poll_answer.id = 0;
	        		}
	        		
	        		poll_answer.title = answer;
	        		poll_answer.order = order;
	        		poll_answer.url = $(this).find('input[name="resource-url"]').val();
	        		poll_answer.images = Array();
	        		poll_answer.type = 'x';
	        		poll_answer.resources = Array();
	        		
	        		if(poll_answer.url != null && poll_answer.url != ''){
		        		var resource = new Object();
		        		resource.type = 'url';
		        		resource.value = poll_answer.url;
		        		poll_answer.resources.push(resource);
        			}
        		
	        		$(this).find('input[name="resource-image"]').each(function(){
	        			poll_answer.images.push($(this).val());
		        		var resource = new Object();
		        		resource.type = 'image';
		        		resource.value = $(this).val();
		        		poll_answer.resources.push(resource);
	        		});
	        		
	        		poll_answers.push(poll_answer);
        		}
        	});
        	
        	$('.grid-columns').find('.poll-answer').each(function(){
        		
        		answer = $(this).find('.input-answer').val();
        		
        		try{order = parseInt($(this).find('.input-order').val());}catch(e){order = 0;}
        		
        		if(answer != null && answer.length > 0){
        			
	        		var poll_answer = new Object();
	        		
	        		if($(this).find('input[name="poll-id"]').length > 0){
	        			
	        			poll_answer.id = $(this).find('input[name="poll-id"]').val();
	        		}else{
	        			
	        			poll_answer.id = 0;
	        		}
	        		
	        		poll_answer.title = answer;
	        		poll_answer.order = order;
	        		poll_answer.url = $(this).find('input[name="resource-url"]').val();
	        		poll_answer.images = Array();
	        		poll_answer.type = 'y';
	        		poll_answer.resources = Array();
	        		
	        		if(poll_answer.url != null && poll_answer.url != ''){
		        		var resource = new Object();
		        		resource.type = 'url';
		        		resource.value = poll_answer.url;
		        		poll_answer.resources.push(resource);
        			}
	        		
	        		grid_columns.push(poll_answer);
        		}
        	});
        	
        	$('input[name="poll-final-answers"]').val(JSON.stringify(poll_answers));
        	$('input[name="poll-final-columns"]').val(JSON.stringify(grid_columns));
        	
        	$('#adminForm').submit();
		},
		
		init_config: function(){
			
			$("#config-document").tabs();
		},
		
		reset_listbox: function(selecct){
			
			selectBox = document.getElementById(select); selectBox.selectedIndex = -1;
		},
		
		init_poll_list: function(){
			
			$('.btn-publish').click(function(event){
				event.preventDefault();
				var button = $(this);
				if(button.hasClass('btn-success') || button.hasClass('btn-danger')){
					button.addClass('btn-progress');
					$.ajax({
						url: (button.hasClass('btn-success') ? $('#url-unpublish-poll').text() : $('#url-publish-poll').text()),
						dataType: 'json',
						data: {'cid': button.parent().find('input').val()},
						success: function(data, status, xhr, form){
							if(typeof data.error != 'undefined' && data.error.length > 0){
								$('#message-modal').find('.modal-body').html(data.error);
								$('#message-modal').modal('show');
							}else{
								button.toggleClass('btn-success').toggleClass('btn-danger');
								button.find('i').toggleClass('icon-ok').toggleClass('icon-remove');
							}
							button.removeClass('btn-progress');
						}
					});
				}
				return false;
			});
			
			$('.btn-feature').click(function(event){
				event.preventDefault();
				var button = $(this);
				if(button.hasClass('btn-success') || button.hasClass('btn-danger')){
					button.addClass('btn-progress');
					$.ajax({
						url: (button.hasClass('btn-success') ? $('#url-unfeature-poll').text() : $('#url-feature-poll').text()),
						dataType: 'json',
						data: {'cid': button.parent().find('input').val()},
						success: function(data, status, xhr, form){
							if(typeof data.error != 'undefined' && data.error.length > 0){
								$('#message-modal').find('.modal-body').html(data.error);
								$('#message-modal').modal('show');
							}else{
								button.toggleClass('btn-success').toggleClass('btn-danger');
								button.find('i').toggleClass('icon-ok').toggleClass('icon-remove');
							}
							button.removeClass('btn-progress');
						}
					});
				}
				return false;
			});
			
			$('#btn_save_order').click(function(){
				
				var items = new Array();
				
				$('.row-list').each(function(){
					
					items.push($(this).find('.rowid').text()+'-'+$(this).find('input[name="sort-order"]').val());
				});
				
				$.ajax({
					
					url : $('#url_save_sort_order').text(),
	                type : 'post',
	                dataType: 'json',
                    data: {'sort-order': items},
                    
                    success: function(data) {
                    	
                        if(typeof data.error != 'undefined' && data.error.length > 0){
                        	
                        	$('#message-modal').find('.modal-body').html(data.error);
    						$('#message-modal').modal('show');
                        }
                        
                    	$('#ui-anim-progress').hide();
                    	$('#btn_save_order').show();
                    },
                    
                    beforeSend: function(){
                    	
                    	$('#ui-anim-progress').insertAfter('#btn_save_order').show();
                    	$('#btn_save_order').hide();
                    }
				});
			});
		},
		
		sync_users: function(){
			$('#sync-status-modal').modal('show');
			PollsAdminFactory.do_users_sync(0, 500, 0);
		},
		
		do_users_sync: function(start, count, total){
			
			$.ajax({
				url: $('#url-sync-users').text(),
				dataType: 'json',
				data: {'start': start, 'count': count, 'total': total},
				success: function(data){
					if(typeof data.error != 'undefined' && data.error.length > 0){
						$('#sync-status-modal').modal('hide');
						$('#message-modal').find('.modal-body').html(data.error);
						$('#message-modal').modal('show');
					} else {
						if(data.completed >= 100){
							$('#sync-status-modal').modal('hide');
							location.reload();
						} else {
							$('#sync-progress-anim').find('.bar').attr('style', 'width: '+data.completed+'%');
							PollsAdminFactory.do_users_sync(start + count, count, data.total);
						}
					}
				}
			});
		}
	};
	
	window.PollsAdminFactory = PollsAdminFactory;
})(jQuery);

jQuery(document).ready(function($){
	
	$('body').on('mouseover', '.tooltip-hover', function(){$(this).tooltip('show');});		
	
	switch($('#cjpageid').val()){
	
	case 'poll-form':
		
		PollsAdminFactory.init_poll_form();
		break;
		
	case 'poll-list':
		
		PollsAdminFactory.init_poll_list();
		break;

	case 'config-form':
		
		PollsAdminFactory.init_config();
		break;
	}
});	
