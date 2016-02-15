<?php
// namespace templates\responsivizer\framework\modules;
/**
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage modules
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Responsivizer ModulesManager responsibilities interface
 *
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage modules
 * @since 2.0
 */
interface IResponsivizerModulesManager {
	/**
	 * Count loadable modules using match between Responsivizer positions and common positions
	 *
	 * @access public
	 * @param string $mainPosition
	 * @return boolean
	 */
	public function countModules($mainposition);
	/**
	 * Inject in standard output buffer opened by JDocument tags needed to parse modules in positions
	 *
	 * @access public
	 * @param string $mainPosition
	 * @return boolean
	 */
	public function injectModulesTags($mainposition);
}

/**
 * Responsivizer ModulesManager implementation <<testable_behavior>>
 *
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage modules
 * @since 2.0
 */
class ResponsivizerModulesManager implements IResponsivizerModulesManager {
	/**
	 * Registry object reference
	 * 
	 * @access private
	 * @var Object
	 */
	private $registry;
	
	/**
	 * Total match array positions
	 * 
	 * @access private
	 * @var array
	 */
	private $totalPositions;
	
	/**
	 * JDocument decorator object
	 *
	 * @access private
	 * @var Object
	 */
	private $decorator;

	/**
	 * Count loadable modules using match between Responsivizer positions and common positions
	 *
	 * @access public
	 * @param string $mainPosition
	 * @return boolean
	 */
	public function countModules($mainPosition) {
		// Self override augmenting of $mainPosition
		if(array_key_exists($mainPosition, $this->totalPositions)) {
			$matchedPositions = $this->totalPositions[$mainPosition];
			$totalPositions = array();
			$totalPositions[] = $mainPosition;
			// Merge only if real matching positions are found
			if(!empty($matchedPositions[0])) {
				$totalPositions = array_merge($totalPositions, $matchedPositions);
			}
			$mainPosition = implode('|', $totalPositions);
		}
		
		$operators = '\|';
		$words = preg_split('/' . $operators . '/i', $mainPosition, null);
		$numModules = 0;
		$numPositions = count($words);
		$parent = get_parent_class($this->decorator);
		for ($i = 0, $n = $numPositions; $i < $n; $i++) {
			$name = strtolower($words[$i]);
			$numModules += ((isset($parent::$_buffer['modules'][$name])) && ($parent::$_buffer['modules'][$name] === false)) ? 0 : count(JModuleHelper::getModules($name));
		}

		return $numModules;
	}

	/**
	 * Inject in standard output buffer opened by JDocument tags needed to parse modules in positions
	 *
	 * @access public
	 * @param string $mainPosition
	 * @param string $style
	 * @return void
	 */
	public function injectModulesTags($mainPosition, $style = 'responsivizer') {
		// Inject always main position
		echo '<jdoc:include type="modules" name="' . $mainPosition . '" style="' . $style . '" />';
		$tpVar = $this->app->input->get('tp', false);
		// Inject requested jdoc module tag in output buffer by match positions
		if(array_key_exists($mainPosition, $this->totalPositions) && !$tpVar) {
			$additionalMatchedPositions = $this->totalPositions[$mainPosition];
			// Merge only if real matching positions are found
			if(!empty($additionalMatchedPositions[0])) {
				foreach ($additionalMatchedPositions as $matchedPosition) {
					echo '<jdoc:include type="modules" name="' . $matchedPosition . '" style="' . $style . '" />';
				}
			}
		}
	}

	/**
	 * Class constructor
	 *
	 * @access public
	 * @param IResponsivizerRegistry $registry
	 * @return Object
	 */
	public function __construct(IResponsivizerRegistry $registry, JDocument $document) {
		$this->app = JFactory::getApplication();
		$this->registry = $registry;
		$this->decorator = $document;
		
		// Load merged positions
		// Load native template positions from Json DB file
		$registry->loadFile(JPATH_RVZR_DBPOSITIONS, 'json');
		$this->totalPositions = $registry->toArray();
	}
}
