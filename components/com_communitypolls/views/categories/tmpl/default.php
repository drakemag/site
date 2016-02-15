<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_communityanswers
 *
 * @copyright   Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

$avatar = $this->params->get('avatar_component', 'none');
$profile = $this->params->get('profile_component', 'none');
$layout = $this->params->get('layout', 'default');
$this->params->set('show_parent', true);
$language = JFactory::getLanguage()->getTag();
?>

<div id="cj-wrapper" class="category-list<?php echo $this->pageclass_sfx;?>">
	<?php 
	echo JLayoutHelper::render($layout.'.toolbar', array('params'=>$this->params, 'state'=>$this->params));

	if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0)
	{ 
		foreach($this->items[$this->parent->id] as $id => $item)
		{
		    if(in_array($item->language, array($language, '*')))
		    {
                echo JLayoutHelper::render($layout.'.category_list', array('category'=>$item, 'params'=>$this->params, 'maxlevel'=>$this->maxLevelcat));
		    }
		}
	}
	
	if($this->params->get('show_categories_footer', 0) == 1)
	{
    	?>
    	<div class="panel panel-default margin-top-20">
    		<div class="panel-body">
	    		<?php
	    		echo JLayoutHelper::render($layout.'.footer', array('params'=>$this->params, 'state'=>$this->state));
	    		?>
    		</div>
    	</div>
    	<?php 
	}
	?>
</div>