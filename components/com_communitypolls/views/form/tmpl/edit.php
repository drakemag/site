<?php
/**
 * @version		$Id: edit.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

CJFunctions::load_jquery(array('libs'=>array('validate', 'form')));

// Create shortcut to parameters.
$params = $this->state->get('params');
$editor = $this->params->get('default_editor', 'wysiwygbb');
$user = JFactory::getUser();
$asset = $this->item->catid ? 'com_communitypolls.category.'.$this->item->catid : 'com_communitypolls';
$layout = $this->params->get('layout', 'default');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'poll.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			if(PollsFactory.load_form_data()){
				<?php
				if($editor == 'wysiwyg')
				{
					echo $this->form->getField('description')->save();
				}
				?>
				jQuery('#btn-submit-poll').button('loading');
				Joomla.submitform(task, document.getElementById('adminForm'));
			}
		}
	}
</script>
<div id="cj-wrapper" class="edit item-page<?php echo $this->pageclass_sfx; ?>">

	<?php echo JLayoutHelper::render($layout.'.toolbar', array('params'=>$this->params, 'state'=>$this->state));?>
	
	<?php if ($params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_communitypolls&p_id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
		<fieldset>
			<div class="control-group">
				<?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?>
			</div>

			<?php if (is_null($this->item->id) && $this->params->get('show_alias')) : ?>
			<div class="control-group">
				<?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?>
			</div>
			<?php endif; ?>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?>
			</div>
			
			<div class="control-group">
				<?php echo $this->form->getLabel('type'); ?>
				<?php echo $this->form->getInput('type'); ?>
			</div>
			
			<ul class="nav nav-tabs" id="myTabTabs" style="margin-bottom: 10px;">
				<li class="active"><a href="#answers" data-toggle="tab"><?php echo JText::_('COM_COMMUNITYPOLLS_ANSWERS') ?></a></li>
				<li><a href="#editorFields" data-toggle="tab"><?php echo JText::_('COM_COMMUNITYPOLLS_DESCRIPTION') ?></a></li>
				
				<?php if($params->get('show_poll_options', 0) ==  1):?>
				<li><a href="#options" data-toggle="tab"><?php echo JText::_('COM_COMMUNITYPOLLS_CUSTOMIZE') ?></a></li>
				<?php endif;?>
				
				<?php if($params->get('show_publishing_options', 0) ==  1):?>
				<li><a href="#publishing" data-toggle="tab"><?php echo JText::_('COM_COMMUNITYPOLLS_PUBLISHING') ?></a></li>
				<?php endif;?>
				
				<li><a href="#language" data-toggle="tab"><?php echo JText::_('JFIELD_LANGUAGE_LABEL') ?></a></li>
				<li><a href="#metadata" data-toggle="tab"><?php echo JText::_('COM_COMMUNITYPOLLS_METADATA') ?></a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="answers">
					<div class="file-upload-error hide alert alert-error">
						<i class="fa fa-warning"></i> <span class="error-msg"></span>
					</div>
					
					<?php if($this->item->id > 0):?>
					<div class="alert alert-warning"><i class="icon icon-info"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_MSG_DELETE_EXISTING_ANSWER');?></div>
					<?php endif;?>
					
					<div class="panel panel-<?php echo $this->theme;?> poll-answers">
						<div class="panel-heading">
							<strong><?php echo JText::_('COM_COMMUNITYPOLLS_POLL_ANSWERS');?></strong>
						</div>
						<div class="panel-body">
							<?php foreach ($this->item->answers as $i=>$answer):?>
							<div class="poll-answer">
								<div class="control-group poll-answer-data-row form-inline">
									<input type="text" name="poll-order" class="required input-mini center input-order" data-toggle="tooltip" 
										value="<?php echo $this->escape($answer->order);?>" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_ORDER');?>" size="2" maxlength="2">
									<input type="text" name="poll-answer-<?php echo $answer->id;?>" class="required input-answer input-xlarge" 
										value="<?php echo $this->escape($answer->title);?>" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_POLL_ANSWER_TEXT');?>">									
	
									<div class="btn-group">
										<button type="button" class="btn btn-add-media" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_ADD_IMAGE');?>"><i class="icon-picture"></i></button>
										<button type="button" class="btn btn-add-url" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
										<button type="button" class="btn btn-delete-answer" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
									</div>
								</div>
								
								<div class="poll-answer-resources">
									<?php foreach($answer->resources as $resource):?>
									<?php if(strcmp($resource->type, 'image') == 0):?>
									<span class="poll-resource">
										<i class="icon-picture"> </i> <?php echo $resource->value;?> 
										<button class="btn btn-danger btn-mini btn-delete-attachment"><i class="icon-remove"></i></button>
										<input type="hidden" name="resource-image" value="<?php echo $resource->value;?>">
									</span>
									<?php elseif(strcmp($resource->type, 'url') == 0):?>
									<span class="poll-resource">
										<i class="icon-paperclip"></i> <?php echo $resource->value;?>
										<button class="btn btn-danger btn-mini btn-delete-attachment"><i class="icon-remove"></i></button>
										<input type="hidden" name="resource-url" value="<?php echo $resource->value;?>">
									</span>
									<?php endif;?>
									<?php endforeach;?>
								</div>
								
								<input name="answer-id" type="hidden" value="<?php echo $answer->id?>">
							</div>
							<?php endforeach;?>
						</div>
						<div class="panel-footer">
							<button type="button" class="btn btn-success btn-add-answer"><?php echo JText::_('COM_COMMUNITYPOLLS_ADD_ANSWER');?></button>
						</div>
					</div>
					
					<fieldset class="poll-columns-wrapper"<?php echo $this->form->getData()->get('type') != 'grid' ? ' style="display: none;"' : '';?>>
						<div class="panel panel-<?php echo $this->theme;?> grid-columns">
							<div class="panel-heading">
								<strong><?php echo JText::_('COM_COMMUNITYPOLLS_POLL_GRID_COLUMNS');?></strong>
							</div>
							<div class="panel-body">
								<?php foreach ($this->item->columns as $answer):?>
								<div class="poll-answer">
									<div class="control-group poll-answer-data-row form-inline">
										<input type="text" name="poll-order" class="input-order input-mini center" data-toggle="tooltip" 
											value="<?php echo $this->escape($answer->order);?>" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_ORDER');?>" size="2" maxlength="2">
										<input type="text" name="poll-answer" class="input-answer input-xlarge" 
											value="<?php echo $this->escape($answer->title);?>" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_POLL_ANSWER_TEXT');?>">
										<div class="btn-group">
											<button type="button" class="btn btn-add-url" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
											<button type="button" class="btn btn-delete-answer" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
										</div>
									</div>
									<div class="poll-answer-resources">
										<?php foreach($answer->resources as $resource):?>
										<?php if(strcmp($resource->type, 'url') == 0):?>
										<span class="poll-resource">
											<i class="icon-paperclip"></i> <?php echo $resource->value;?>
											<button class="btn btn-danger btn-mini btn-delete-attachment"><i class="icon-remove"></i></button>
											<input type="hidden" name="resource-url" value="<?php echo $resource->value;?>">
										</span>
										<?php endif;?>
										<?php endforeach;?>
									</div>
									<input name="answer-id" type="hidden" value="<?php echo $answer->id?>">
								</div>
								<?php endforeach;?>
							</div>
							<div class="panel-footer">
								<button type="button" class="btn btn-success btn-add-column"><?php echo JText::_('COM_COMMUNITYPOLLS_ADD_ANSWER');?></button>
							</div>
						</div>
					</fieldset>
				</div>
			
				<div class="tab-pane" id="editorFields">
				</div>
				
				<?php if($params->get('show_poll_options', 0) ==  1):?>
				<div class="tab-pane" id="options">

					<?php if($params->get('show_option_poll_closing_date', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('close_date'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('close_date'); ?>
						</div>
					</div>
					<?php endif;?>

					<?php if($params->get('show_option_publish_results_date', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('results_up'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('results_up'); ?>
						</div>
					</div>
					<?php endif;?>

					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('min_answers'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('min_answers'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('max_answers'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('max_answers'); ?>
						</div>
					</div>
					
					<?php if($params->get('show_option_chart_type', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('chart_type'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('chart_type'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<?php if($params->get('show_option_custom_answer', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('custom_answer'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('custom_answer'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<?php if($params->get('show_option_embed_poll', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('anywhere'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('anywhere'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<?php if($params->get('show_option_anonymous', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('anonymous'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('anonymous'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<?php if($params->get('show_option_private', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('private'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('private'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<?php if($params->get('show_option_answers_order', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('answers_order'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('answers_order'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<?php if($params->get('show_option_modify_answers', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('modify_answers'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('modify_answers'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<?php if($params->get('show_option_color_pallete', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('pallete'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('pallete'); ?>
						</div>
					</div>
					<?php endif;?>
					
				</div>
				<?php endif;?>
				
				<?php if($params->get('show_publishing_options', 0) ==  1):?>
				<div class="tab-pane" id="publishing">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('tags'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('tags'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('created_by_alias'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('created_by_alias'); ?>
						</div>
					</div>
					
					<?php if ($this->item->params->get('access-change')) : ?>
					<?php if($params->get('show_option_featured_poll', 0) == 1):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('featured'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('featured'); ?>
						</div>
					</div>
					<?php endif;?>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('publish_up'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('publish_up'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('publish_down'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('publish_down'); ?>
						</div>
					</div>
					<?php endif; ?>
					
					<?php if($user->authorise('core.edit.state', 'com_communitypolls')):?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('published'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('published'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('access'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('access'); ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<?php endif;?>
				
				<div class="tab-pane" id="language">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('language'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('language'); ?>
						</div>
					</div>
					<div style="min-height: 100px; height: 100px;"></div>
				</div>
				
				<div class="tab-pane" id="metadata">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('metadesc'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('metadesc'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('metakey'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('metakey'); ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="control-group" data-relocate="#editorFields">
				<?php echo $this->form->getLabel('description'); ?>
				<?php echo CJFunctions::load_editor($editor, 'jform_description', 'jform[description]', $this->item->description, 10, 40, 
						'100%', '250px', 'form-control', 'width: 100%; height: 250px;', true);?>
			</div>
			
			<?php if($params->get('show_option_end_message', 0) == 1):?>
			<div class="control-group" data-relocate="#editorFields">
				<?php echo $this->form->getLabel('end_message'); ?>
				<?php echo CJFunctions::load_editor($editor, 'jform_end_message', 'jform[end_message]', $this->item->end_message, 10, 40, 
						'100%', '250px', 'form-control controls', 'width: 100%; height: 250px;', true);?>
			</div>
			<?php endif;?>

			<input type="hidden" name="task" id="task" value="" />
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
			
			<?php echo $this->form->getInput('poll-final-answers'); ?>
			<?php echo $this->form->getInput('poll-final-columns'); ?>
			
			<?php if ($this->params->get('enable_category', 0) == 1) :?>
			<input type="hidden" name="jform[catid]" value="<?php echo $this->params->get('catid', 1); ?>" />
			<?php endif; ?>
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
		
		<?php if(!$user->authorise('core.vcaptcha', $asset)):?>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('captcha'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('captcha'); ?>
			</div>
		</div>
		<?php endif;?>
            		
		<div class="well well-small">
			<div class="center">
				<button type="button" class="btn" onclick="Joomla.submitbutton('poll.cancel')">
					<span class="icon-cancel"></span>&#160;<?php echo JText::_('JCANCEL') ?>
				</button>
				<button id="btn-submit-poll" type="button" class="btn btn-primary" onclick="Joomla.submitbutton('poll.save')">
					<span class="icon-ok"></span>&#160;<?php echo JText::_('JSAVE') ?>
				</button>
			</div>
		</div>
	</form>
	
	<div style="display: none;">
		<div class="poll-answer-template">
			<div class="poll-answer">
				<div class="control-group poll-answer-data-row form-inline">
					<input type="text" name="poll-order" class="required input-mini center input-order" data-toggle="tooltip" value="" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_ORDER');?>" size="2" maxlength="2">
					<input type="text" name="poll-answer" class="required input-answer input-xlarge" value="" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_POLL_ANSWER_TEXT');?>">
					<div class="btn-group">
						<button type="button" class="btn btn-add-media" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_ADD_MEDIA');?>"><i class="icon-picture"></i></button>
						<button type="button" class="btn btn-add-url" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
						<button type="button" class="btn btn-delete-answer" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
					</div>
				</div>
				<div class="poll-answer-resources"></div>
				<input name="answer-id" type="hidden" value="0">
			</div>
		</div>
		
		<div class="poll-column-template">
			<div class="poll-answer">
				<div class="control-group poll-answer-data-row form-inline">
					<input type="text" name="poll-order" class="input-order input-mini center" data-toggle="tooltip" value="" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_ORDER');?>" size="2" maxlength="2">
					<input type="text" name="poll-answer" class="input-answer input-xlarge" value="" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_POLL_ANSWER_TEXT');?>">
					<div class="btn-group">
						<button type="button" class="btn tooltip-hover btn-add-url" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
						<button type="button" class="btn tooltip-hover btn-delete-answer" title="<?php echo JText::_('COM_COMMUNITYPOLLS_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
					</div>
				</div>
				<div class="poll-answer-resources"></div>
				<input name="answer-id" type="hidden" value="0">
			</div>
		</div>
		
		<div class="poll-resource-template">
			<span class="poll-resource">
				<i class="icon-picture"> </i> <span class="resource-value"></span> 
				<a class="btn-delete-attachment" href="#" onclick="return false;" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_DELETE_ATTACHMENT')?>">
					<i class="icon-remove"></i>&nbsp;
				</a>
				<input type="hidden" name="resource-image" value="">
			</span>
		</div>
		
		<div class="poll-resource-url-template">
			<span class="poll-resource">
				<i class="icon-paperclip"> </i> <span class="resource-value"></span> 
				<a class="btn-delete-attachment" href="#" onclick="return false;" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_DELETE_ATTACHMENT')?>">
					<i class="icon-remove"></i>&nbsp;
				</a>
				<input type="hidden" name="resource-url" value="">
			</span>
		</div>
		
		<div id="color-pallets">
			<div class="pallete-default"><?php echo json_encode(ChartsHelper::get_rgb_colors('default'))?></div>
			<div class="pallete-shankar"><?php echo json_encode(ChartsHelper::get_rgb_colors('shankar'))?></div>
			<div class="pallete-kamala"><?php echo json_encode(ChartsHelper::get_rgb_colors('kamala'))?></div>
			<div class="pallete-autumn"><?php echo json_encode(ChartsHelper::get_rgb_colors('autumn'))?></div>
			<div class="pallete-blind"><?php echo json_encode(ChartsHelper::get_rgb_colors('blind'))?></div>
			<div class="pallete-evening"><?php echo json_encode(ChartsHelper::get_rgb_colors('evening'))?></div>
			<div class="pallete-kitchen"><?php echo json_encode(ChartsHelper::get_rgb_colors('kitchen'))?></div>
			<div class="pallete-light"><?php echo json_encode(ChartsHelper::get_rgb_colors('light'))?></div>
			<div class="pallete-navy"><?php echo json_encode(ChartsHelper::get_rgb_colors('navy'))?></div>
			<div class="pallete-shade"><?php echo json_encode(ChartsHelper::get_rgb_colors('shade'))?></div>
			<div class="pallete-spring"><?php echo json_encode(ChartsHelper::get_rgb_colors('spring'))?></div>
			<div class="pallete-summer"><?php echo json_encode(ChartsHelper::get_rgb_colors('summer'))?></div>
		</div>
		
		<input id="cjpageid" value="form" type="hidden">
		<span id="msg_field_required"><?php echo JText::_('COM_COMMUNITYPOLLS_MSG_FIELD_REQUIRED');?></span>
	</div>
	
	<form id="file-upload-form" action="<?php echo JRoute::_('index.php?option=com_communitypolls&view=poll&task=poll.upload');?>" 
		enctype="multipart/form-data" method="post" style="position: absolute; top:-9999px;">
		<input name="input-attachment" class="input-file-upload" type="file">
	</form>
	
	<div id="add-url-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="editModalLabel"><?php echo JText::_('COM_COMMUNITYPOLLS_LINK_ANSWER');?></h3>
		</div>
		<div class="modal-body">
			<form action="#" id="submit-url-form" onsubmit="return false;">
				<label><?php echo JText::_('COM_COMMUNITYPOLLS_ANSWER_URL_HELP');?></label>
				<input type="text" class="input-xlarge required url" value="" name="input-url" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_ENTER_URL')?>">
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-cancel" type="button" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CANCEL');?></button>
			<button class="btn btn-primary btn-submit" type="button" aria-hidden="true"><?php echo JText::_('JSUBMIT');?></button>
		</div>
	</div>	
</div>
