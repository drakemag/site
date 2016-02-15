<?php
// namespace administrator\components\com_responsivizer\controllers;
/**
 *
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Config controller concrete implementation
 *
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage controllers
 * @since 1.0
 */
class ResponsivizerControllerConfig extends ResponsivizerController {

	/**
	 * Show configuration
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		// App instance
		$app = JFactory::getApplication();
		$option= $this->option;
		$fieldset = $app->getUserStateFromRequest("$option.config.fieldset", 'fieldset', 'template') ;
		
		$model = $this->getModel();
		$model->setState('fieldset', $fieldset);

		parent::display($cachable);
	}

	/**
	 * Save config entity
	 * @access public
	 * @return void
	 */
	public function saveEntity() {
		// App instance
		$app = JFactory::getApplication();
		$option= $this->option;
		// Propagate fieldset to save correctly images for slideshow
		$fieldset = $app->getUserStateFromRequest("$option.config.fieldset", 'fieldset', 'template') ;
		
		$model = $this->getModel();
		$model->setState('fieldset', $fieldset);
		if(!$model->storeEntity()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( 'index.php?option=com_responsivizer&task=config.display', JText::_('COM_RESPONSIVIZER_ERROR_SAVING_PARAMS'));
			return false;
		}
		$this->setRedirect( 'index.php?option=com_responsivizer&task=config.display', JText::_('COM_RESPONSIVIZER_SAVED_PARAMS'));
	}
}
?>