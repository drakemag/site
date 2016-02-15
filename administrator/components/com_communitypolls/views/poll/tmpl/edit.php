<?php
/**
 * @version		$Id: view.html.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

CJLib::behavior('bscore');
CJFunctions::load_jquery(array('libs'=>array('form', 'fontawesome')));

$this->hiddenFieldsets = array();
$this->hiddenFieldsets[0] = 'basic-limited';
$this->configFieldsets = array();
$this->configFieldsets[0] = 'editorConfig';

// Create shortcut to parameters.
$params = $this->state->get('params');
$editor = $params->get('default_editor', 'wysiwygbb');

$app = JFactory::getApplication();
$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);
$editoroptions = isset($params->show_publishing_options);

if (!$editoroptions)
{
	$params->show_publishing_options = '1';
	$params->show_poll_options = '1';
}

// Check if the poll uses configuration settings besides global. If so, use them.
if (isset($this->item->attribs['show_publishing_options']) && $this->item->attribs['show_publishing_options'] != '')
{
	$params->show_publishing_options = $this->item->attribs['show_publishing_options'];
}

if (isset($this->item->attribs['show_poll_options']) && $this->item->attribs['show_poll_options'] != '')
{
	$params->show_poll_options = $this->item->attribs['show_poll_options'];
}
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task, form)
	{
		if (task == 'poll.cancel' || document.formvalidator.isValid(document.id('adminForm')))
		{
			if(PollsFactory.load_form_data()){
				<?php 
				if($editor == 'wysiwyg')
				{
					echo $this->form->getField('description')->save(); 
				}
				?>
				if (typeof(form) === 'undefined') {
			        form = document.getElementById('adminForm');
			    }
			    if (typeof(task) !== 'undefined' && task !== "") {
			        form.task.value = task;
			    }
			    if (typeof form.onsubmit == 'function') {
			        form.onsubmit();
			    }
			    if (typeof form.fireEvent == "function") {
			        form.fireEvent('submit');
			    }
			    jQuery('#dummy-submit').click();
			}
		}
	}
</script>

<div id="cj-wrapper">
	<form action="<?php echo JRoute::_('index.php?option=com_communitypolls&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
		
		<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
		
		<div class="form-horizontal">
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'answers')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'answers', JText::_('COM_COMMUNITYPOLLS_FIELDSET_POLL_ANSWERS', true)); ?>
			<div class="row-fluid">
				<div class="span9">
					<div class="file-upload-error hide alert alert-error">
						<i class="icon-warning-sign"></i> <span class="error-msg"></span>
					</div>
					
					<?php if($this->item->id > 0):?>
					<div class="alert alert-warning"><i class="icon icon-info"></i> <?php echo JText::_('MSG_DELETE_EXISTING_ANSWER');?></div>
					<?php endif;?>
					
					<div class="panel panel-default poll-answers">
						<div class="panel-heading">
							<strong><?php echo JText::_('COM_COMMUNITYPOLLS_POLL_ANSWERS');?></strong>
						</div>
						<div class="panel-body">
							<?php foreach ($this->item->answers as $i=>$answer):?>
							<div class="poll-answer">
								<div class="control-group poll-answer-data-row form-inline">
									<input type="text" name="poll-order" class="required input-mini center input-order" data-toggle="tooltip" 
										value="<?php echo $this->escape($answer->order);?>" title="<?php echo JText::_('LBL_ORDER');?>" size="2" maxlength="2">
									<input type="text" name="poll-answer-<?php echo $answer->id;?>" class="required input-answer input-xlarge" 
										value="<?php echo $this->escape($answer->title);?>" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_ADD_YOUR_ANSWER');?>">									
		
									<div class="btn-group">
										<button type="button" class="btn btn-add-media" data-toggle="tooltip" title="<?php echo JText::_('TXT_ADD_MEDIA');?>"><i class="icon-picture"></i></button>
										<button type="button" class="btn btn-add-url" data-toggle="tooltip" title="<?php echo JText::_('TXT_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
										<button type="button" class="btn btn-delete-answer" data-toggle="tooltip" title="<?php echo JText::_('TXT_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
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
						<div class="panel panel-default grid-columns">
							<div class="panel-heading">
								<strong><?php echo JText::_('COM_COMMUNITYPOLLS_POLL_GRID_COLUMNS');?></strong>
							</div>
							<div class="panel-body">
								<?php foreach ($this->item->columns as $answer):?>
								<div class="poll-answer">
									<div class="control-group poll-answer-data-row form-inline">
										<input type="text" name="poll-order" class="input-order input-mini center" data-toggle="tooltip" 
											value="<?php echo $this->escape($answer->order);?>" title="<?php echo JText::_('LBL_ORDER');?>" size="2" maxlength="2">
										<input type="text" name="poll-answer" class="input-answer input-xlarge" 
											value="<?php echo $this->escape($answer->title);?>" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_ADD_YOUR_ANSWER');?>">
										<div class="btn-group">
											<button type="button" class="btn btn-add-url" data-toggle="tooltip" title="<?php echo JText::_('TXT_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
											<button type="button" class="btn btn-delete-answer" data-toggle="tooltip" title="<?php echo JText::_('TXT_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
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
				<div class="span3">
					<div class="control-group">
						<?php echo $this->form->getLabel('type'); ?>
						<?php echo $this->form->getInput('type'); ?>
					</div>
					<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
				</div>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'editorFields', JText::_('COM_COMMUNITYPOLLS_FIELDSET_POLL_CONTENT', true)); ?>
			<div class="row-fluid">
				<div class="span12">
					<fieldset class="adminform" id="editorFieldsWrapper">
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<?php echo $this->form->getLabel('description'); ?>
									<?php echo CJFunctions::load_editor($editor, 'jform_description', 'jform[description]', $this->item->description, 10, 40, '100%', '250px', '', 'width: 100%; height: 250px;', true);?>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<?php echo $this->form->getLabel('end_message'); ?>
									<?php echo CJFunctions::load_editor($editor, 'jform_end_message', 'jform[end_message]', $this->item->end_message, 10, 40, '100%', '250px', '', 'width: 100%; height: 250px;', true);?>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'preferences', JText::_('COM_COMMUNITYPOLLS_FIELDSET_CUSTOMIZE', true)); ?>
				<?php foreach ($this->form->getFieldset('preferences') as $field) : ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $field->label; ?>
					</div>
					<div class="controls">
						<?php echo $field->input; ?>
					</div>
				</div>
				<?php endforeach;?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		
			<?php // Do not show the publishing options if the edit form is configured not to. ?>
			<?php if ($params->show_publishing_options == 1) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_COMMUNITYPOLLS_FIELDSET_PUBLISHING', true)); ?>
				<div class="row-fluid form-horizontal-desktop">
					<div class="span6">
						<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
					</div>
					<div class="span6">
						<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
					</div>
				</div>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>
		
			<?php if ($assoc) : ?>
				<?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS', true)); ?>
					<?php //echo $this->loadTemplate('associations'); ?>
				<?php //echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>
		
			<?php $this->show_options = $params->show_poll_options; ?>
			<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
		
			<?php if ($this->canDo->get('core.admin')) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'editor', JText::_('COM_COMMUNITYPOLLS_FIELDSET_SLIDER_EDITOR_CONFIG', true)); ?>
					<?php foreach ($this->form->getFieldset('editorConfig') as $field) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>
		
			<?php if ($this->canDo->get('core.admin')) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_COMMUNITYPOLLS_FIELDSET_RULES', true)); ?>
					<?php echo $this->form->getInput('rules'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>
		
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		</div>
	
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
			
		<?php echo $this->form->getInput('poll-final-answers'); ?>
		<?php echo $this->form->getInput('poll-final-columns'); ?>
		<input type="submit" id="dummy-submit" style="display: none"/>
		
		<?php echo JHtml::_('form.token'); ?>
		
	</form>

	<div style="display: none;">
		<div class="poll-answer-template">
			<div class="poll-answer">
				<div class="control-group poll-answer-data-row form-inline">
					<input type="text" name="poll-order" class="required input-mini center input-order" data-toggle="tooltip" value="" title="<?php echo JText::_('LBL_ORDER');?>" size="2" maxlength="2">
					<input type="text" name="poll-answer" class="required input-answer input-xlarge" value="" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_ADD_YOUR_ANSWER');?>">
					<div class="btn-group">
						<button type="button" class="btn btn-add-media" data-toggle="tooltip" title="<?php echo JText::_('TXT_ADD_MEDIA');?>"><i class="icon-picture"></i></button>
						<button type="button" class="btn btn-add-url" data-toggle="tooltip" title="<?php echo JText::_('TXT_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
						<button type="button" class="btn btn-delete-answer" data-toggle="tooltip" title="<?php echo JText::_('TXT_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
					</div>
				</div>
				<div class="poll-answer-resources"></div>
				<input name="answer-id" type="hidden" value="0">
			</div>
		</div>
		
		<div class="poll-column-template">
			<div class="poll-answer">
				<div class="control-group poll-answer-data-row form-inline">
					<input type="text" name="poll-order" class="input-order input-mini center" data-toggle="tooltip" value="" title="<?php echo JText::_('LBL_ORDER');?>" size="2" maxlength="2">
					<input type="text" name="poll-answer" class="input-answer input-xlarge" value="" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_ADD_YOUR_ANSWER');?>">
					<div class="btn-group">
						<button type="button" class="btn tooltip-hover btn-add-url" title="<?php echo JText::_('TXT_LINK_ANSWER');?>"><i class="icon-paperclip"></i></button>
						<button type="button" class="btn tooltip-hover btn-delete-answer" title="<?php echo JText::_('TXT_DELETE_ANSWER');?>"><i class="icon-trash"></i></button>
					</div>
				</div>
				<div class="poll-answer-resources"></div>
				<input name="answer-id" type="hidden" value="0">
			</div>
		</div>
		
		<div class="poll-resource-template">
			<span class="poll-resource">
				<i class="icon-picture"> </i> <span class="resource-value"></span> 
				<a class="btn-delete-attachment" href="#" onclick="return false;" data-toggle="tooltip" title="<?php echo JText::_('TXT_DELETE_ATTACHMENT')?>"><i class="icon-remove"></i>&nbsp;</a>
				<input type="hidden" name="resource-image" value="">
			</span>
		</div>
		
		<div class="poll-resource-url-template">
			<span class="poll-resource">
				<i class="icon-paperclip"> </i> <span class="resource-value"></span> 
				<a class="btn-delete-attachment" href="#" onclick="return false;" data-toggle="tooltip" title="<?php echo JText::_('TXT_DELETE_ATTACHMENT')?>"><i class="icon-remove"></i>&nbsp;</a>
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
		<span id="msg_field_required"><?php echo JText::_('MSG_FIELD_REQUIRED');?></span>
	</div>
	
	<form id="file-upload-form" action="<?php echo JRoute::_('index.php?option=com_communitypolls&view=poll&task=poll.upload');?>" 
		enctype="multipart/form-data" method="post" style="position: absolute; top:-9999px;">
		<input name="input-attachment" class="input-file-upload" type="file">
	</form>
	
	<div id="add-url-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="editModalLabel"><?php echo JText::_('TXT_LINK_ANSWER');?></h3>
		</div>
		<div class="modal-body">
			<form action="#" id="submit-url-form" onsubmit="return false;">
				<label><?php echo JText::_('TXT_ANSWER_URL_HELP');?></label>
				<input type="text" class="input-xlarge required url" value="" name="input-url" placeholder="<?php echo JText::_('LBL_ENTER_URL')?>">
			</form>
		</div>
		<div class="modal-footer">
			<button class="btn btn-cancel" type="button" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('LBL_CANCEL');?></button>
			<button class="btn btn-primary btn-submit" type="button" aria-hidden="true"><?php echo JText::_('JSUBMIT');?></button>
		</div>
	</div>	
</div>
