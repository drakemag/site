<?php
// namespace components\com_responsivizer\controllers;
/**
 * @package RESPONSIVIZER::OFFLINECACHE::components::com_responsivizer
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main controller class
 *
 * @package RESPONSIVIZER::OFFLINECACHE::components::com_responsivizer
 * @subpackage controllers
 * @since 2.0
 */
class ResponsivizerControllerOfflinecache extends ResponsivizerController {
	/**
	 * Manage rendering of offline cache manifest generating on the fly for the current page resources
	 * @access public
	 * @return void
	 */
	public function showEntity() {
		$idEntity = $this->app->input->getInt ('menuid', null);
		$user = JFactory::getUser();
		$model = $this->getModel();
		
		// Try to load record from model 
		if(!$record = $model->loadEntity($idEntity)) {
			return false;
		}
		
		$document =  JFactory::getDocument ();
		// Get model and view core
		$format = $this->app->input->get('format', null);
		$viewType = $document->getType ();
		$coreName = $this->getNames ();
		$viewLayout = $this->app->input->get ( 'layout', 'default' );
		
		$view =  $this->getView ( $coreName, $viewType, '', array ('base_path' => $this->basePath ) );
		// Push the model into the view (as default)
		$view->setModel ( $model, true );
		
		// Set the layout
		$view->setLayout ( $viewLayout );
		// Call view method
		$view->showEntity($record, $format); 
	}
}