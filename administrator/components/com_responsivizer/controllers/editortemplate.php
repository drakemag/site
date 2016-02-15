<?php
// namespace administrator\components\com_responsivizer\controllers;
/**
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Controller for inline Editor Template
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage controllers
 * @since 2.0
 */
class ResponsivizerControllerEditortemplate extends ResponsivizerController {
	/**
	 * Default task
	 * 
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		$this->app->input->set ( 'hidemainmenu', 1 );
		$option= $this->option;
		$document = $this->document;
		
		$viewType = $document->getType ();
		$coreName = $this->getNames ();
		$viewLayout = $this->app->input->get ( 'layout', 'default' );
		
		$view =  $this->getView ( $coreName, $viewType, '', array ('base_path' => $this->basePath ) );
		
		// Get/Create the model
		if ($model =  $this->getModel ( $coreName )) {
			// Push the model into the view (as default)
			$view->setModel ( $model, true );
		}
		// Set model state
		$model->setState('option', $option);
		$cParams = JComponentHelper::getParams($option);
		
		// Get data into controller and make dependency injection into view for presentation layer
		$data = $model->getData($cParams);
		
		// Set the layout
		$view->setLayout ( $viewLayout );
		$view->display (null, $data);
	}
}