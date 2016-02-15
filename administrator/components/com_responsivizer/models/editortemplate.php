<?php
// namespace administrator\components\com_responsivizer\models;
/**
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Model responsibilities
 *
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
interface IEditortemplateModel {
	/**
	 * Get template chosen for frontend mobile view switching
	 * 
	 * @access public
	 * @param Object $componentConfig
	 * @return string
	 */
	public function getData($componentConfig = null);
}

/**
 * Model concrete implementation
 *
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
class ResponsivizerModelEditortemplate extends ResponsivizerModel implements IEditortemplateModel {
	/**
	 * Element for template currently used for mobile switcher
	 * 
	 * @access private
	 * @var string
	 */
	private $templateElement;
	
	/**
	 * Build list entities query
	 *
	 * @access protected
	 * @return string
	 */
	protected function buildListQuery() {
		$query = "SELECT " . $this->_db->quoteName('extension_id') . " AS ". $this->_db->quoteName('id') .
				 "\n FROM " . $this->_db->quoteName('#__extensions') .
				 "\n WHERE " . $this->_db->quoteName('type') . " = " . $this->_db->quote('template') .
				 "\n AND " . $this->_db->quoteName('client_id') . " = 0" .
				 "\n AND " . $this->_db->quoteName('element') . " = " . $this->_db->quote($this->templateElement);
		return $query;
	}

	/**
	 * Get template chosen for frontend mobile view switching
	 * 
	 * @access public
	 * @param Object $componentConfig
	 * @return string
	 */
	public function getData($componentConfig = null) {
		// Get chosen mobile template from Responsivizer configuration, hopefully left Responsivizer default
		$this->templateElement = $componentConfig->get('template', 'responsivizer');
		
		// Now build and execute query to translate template element name to template extension ID
		$query = $this->buildListQuery();
		$this->_db->setQuery($query);
		try {
			$result = $this->_db->loadObject();
			if ($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_ERROR_RETRIEVING_TEMPLATE_INFO') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (ResponsivizerException $e) {
			$this->app->enqueueMessage($e->getMessage(), $e->getErrorLevel());
			$result = array();
		} catch (Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$this->app->enqueueMessage($rvzrException->getMessage(), $rvzrException->getErrorLevel());
			$result = array();
		}
		return $result;
	}
}
