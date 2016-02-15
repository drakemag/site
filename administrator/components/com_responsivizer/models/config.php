<?php
// namespace administrator\components\com_responsivizer\models;
/**
 *
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modelform');

/**
 * Config model responsibilities
 *
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage models
 * @since 1.0
 */
interface IConfigModel {

	/**
	 * Ottiene i dati di configurazione da db params field record component
	 *
	 * @access public
	 * @return Object
	 */
	public function &getData();

	/**
	 * Effettua lo store dell'entity config
	 *
	 * @access public
	 * @return boolean
	 */
	public function storeEntity();
}

/**
 * Config model concrete implementation
 *
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage models
 * @since 1.0
 */
class ResponsivizerModelConfig extends JModelForm implements IConfigModel {
	/**
	 * Clean the cache
	 * @param   string   $group      The cache group
	 * @param   integer  $client_id  The ID of the client
	 * @return  void
	 * @since   11.1
	 */
	private function cleanComponentCache($group = null, $client_id = 0) {
		// Initialise variables;
		$conf = JFactory::getConfig ();
		$dispatcher = JDispatcher::getInstance ();
		
		$options = array (
				'defaultgroup' => ($group) ? $group : $this->app->input->get('option'),
				'cachebase' => ($client_id) ? JPATH_ADMINISTRATOR . '/cache' : $conf->get ( 'cache_path', JPATH_SITE . '/cache' ) 
		);
		
		$cache = JCache::getInstance ( 'callback', $options );
		$cache->clean ();
		
		// Trigger the onContentCleanCache event.
		$dispatcher->trigger ( 'onContentCleanCache', $options );
	}
	
	/**
	 * Clean the cache
	 * @param   string   $group      The cache group
	 * @param   integer  $client_id  The ID of the client
	 * @return  void
	 * @since   11.1
	 */
	private function cleanSiteCache() {
		// Initialise variables;
		$conf = JFactory::getConfig();
		$dispatcher = JDispatcher::getInstance();
		
		$options = array('defaultgroup' => 'com_responsivizer', 'cachebase' => $conf->get('cache_path', JPATH_SITE . '/cache'));
		
		$cache = JCache::getInstance('callback', $options);
		$cache->clean();
		
		// Trigger the onContentCleanCache event.
		$dispatcher->trigger('onContentCleanCache', $options);
	}

	/**
	 * Ottiene i dati di configurazione da db params field record component
	 *
	 * @access public
	 * @return Object
	 */
	private function &getConfigData() {
		$instance = JComponentHelper::getParams('com_responsivizer');
		return $instance;
	}

	/**
	 * Store form data
	 *
	 * @access protected
	 * @var Object
	 */
	protected $formData;

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 *
	 * @since   11.1
	 */
	protected function loadFormData() {
		return $this->formData;
	}

	/**
	 * Effettua lo storing dell'asset delle permissions sul component level
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function storePermissionsAsset($data) {
		// Save the rules.
		if (isset($data['jform']) && isset($data['jform']['rules'])) {
			$form = $this->getForm($data);
			// Validate the posted data.
			$postedRules = $this->validate($form, $data['jform']);

			$rules = new JAccessRules($postedRules['rules']);
			$asset = JTable::getInstance('asset');

			if (!$asset->loadByName($data['option'])) {
				$root = JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = $data['option'];
				$asset->title = $data['option'];
				$asset->setLocation($root->id, 'last-child');
			}
			$asset->rules = (string) $rules;

			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to get a form object.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		jimport('joomla.form.form');
		$this->formData = $data;
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_responsivizer');

		// Get the form.
		$form = $this->loadForm('com_responsivizer.component', 'config', array('control' => 'jform', 'load_data' => $loadData), false, '/config');

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get a form object.
	 *
	 * @param array $data
	 *        	the form.
	 * @param boolean $loadData
	 *        	the form is to load its own data (default case), false if not.
	 *
	 * @return mixed JFormResponsivizer object on success, false on failure
	 * @since 1.6
	 */
	public function getExtendedForm($data = array(), $loadData = true) {
		jimport('joomla.form.form');
		$data = $this->getConfigData();
		$this->formData = $data;

		// Add additional params xml elements
		$stringParams = $data->toString();
		$additionalParams = '<?xml version="1.0" encoding="utf-8"?><config><fieldset id="module_images" name="module_images">';
		$iterations = preg_match_all('/image_\d+/i', $stringParams, $matches);
		if (is_array($matches) && !count($matches[0])) {
			$matches[0] = array(1);
		}

		for ($i = 1, $k = count($matches[0]); $i <= $k; $i++) {
			$additionalParams .= '<field name="image_' . $i . '" type="configurabletext" label="Image"  />
							  	  <field name="imagealt_' . $i . '" type="text" default="" label="Text alt" description=""/>
							  	  <field name="imagecaption_' . $i . '" type="textarea" cols="30" rows="2" default="" label="Caption" description="" filter="raw"/>
							  	  <field name="imagelink_' . $i . '" type="text" default="" label="Image link" description=""/>
							  	  <field name="" type="spacer" default="" label="" description="" class="separator"/>';
		}
		$additionalParams .= '</fieldset></config>';

		// Get the form.
		$form = $this->loadForm('com_responsivizer.component.xtdform', $additionalParams, array('control' => 'jform', 'load_data' => $loadData), true, '/config');

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Ottiene i dati di configurazione del componente
	 *
	 * @access public
	 * @return Object
	 */
	public function &getData() {
		return $this->getConfigData();
	}

	/**
	 * Effettua lo store dell'entity config
	 *
	 * @access public
	 * @return boolean
	 */
	public function storeEntity() {
		$table = JTable::getInstance('extension');
		// Replace SEF images links
		$base = JUri::root(true) . '/';
		$protocols = '[a-zA-Z0-9]+:';
		$regex     = '#(src|href|poster)="(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
		
		try {
			// Found as installed extension
			if (!$extensionID = $table->find(array('element' => 'com_responsivizer'))) {
				throw new ResponsivizerException($table->getError(), 'error');
			}

			// Existant params
			$table->load($extensionID);
			// Posted params
			$post = $this->app->input->post;
			$post = $post->getArray ( $_POST );

			$asArrayParams = json_decode($table->params);
			// Extra check remove for images slideshow module if no more set
			if ($this->state->get('fieldset') === 'module') {
				foreach ($asArrayParams as $singleParamName => $singleParamValue) {
					// Found an image param, check if image still exists in POST
					if (strpos($singleParamName, 'image') === 0) {
						if (!array_key_exists($singleParamName, $post['jform'])) {
							unset($asArrayParams->$singleParamName);
						} else {
							// Ensure overwrite to make JRegistry merge work
							$asArrayParams->$singleParamName = $post['jform'][$singleParamName];
						}
					}
				}
			}

			$subsetParams = new JRegistry($post['jform']);
			
			// Special handling params for template fieldset
			if ($subsetParams->exists('istemplate')) {
				// Collapse theme multiple params to unique param
				for ($i = 0, $limit = 8; $i < $limit; $i++) {
					unset($asArrayParams->{'predefined_theme_'.$i});
				}
				
				// Force always override for social icons links
				$asArrayParams->text_social_icons = $subsetParams->get('text_social_icons');
				$asArrayParams->facebook = $subsetParams->get('facebook');
				$asArrayParams->google = $subsetParams->get('google');
				$asArrayParams->twitter = $subsetParams->get('twitter');
				$asArrayParams->linkedin = $subsetParams->get('linkedin');
				$asArrayParams->youtube = $subsetParams->get('youtube');
				$asArrayParams->flickr = $subsetParams->get('flickr');
				$asArrayParams->instagram = $subsetParams->get('instagram');
				$asArrayParams->pinterest = $subsetParams->get('pinterest');
				$asArrayParams->tumblr = $subsetParams->get('tumblr');
				$asArrayParams->vimeo = $subsetParams->get('vimeo');
				$asArrayParams->delicious = $subsetParams->get('delicious');
				$asArrayParams->skype = $subsetParams->get('skype');
				$asArrayParams->rss = $subsetParams->get('rss');
				
				// Force media parameters reset
				$asArrayParams->explicit_logo = $subsetParams->get('explicit_logo');
				$asArrayParams->home_icon = $subsetParams->get('home_icon');
				$asArrayParams->main_background_image = $subsetParams->get('main_background_image');
			}

			$actualParams = new JRegistry($asArrayParams);
			$actualParams->merge($subsetParams);
			
			// Special handling params for template fieldset for footer html
			if ($subsetParams->exists('istemplate')) {
				$rawHTML = $_POST['jform']['copyright_footer_html'];
				$rawHTMLImgReplace = preg_replace($regex, "$1=\"$base\$2\"", $rawHTML);
				$actualParams->set('copyright_footer_html', $rawHTMLImgReplace);
				// Additional empty override fields
				$actualParams->set('tel_number', $subsetParams->get('tel_number'));
			}
			
			// Convert to JSON for DB storing
			$post['params'] = $actualParams->toString();

			if (!$table->bind($post)) {
				throw new ResponsivizerException($table->getError(), 'error');
			}

			// pre-save checks
			if (!$table->check()) {
				throw new ResponsivizerException($table->getError(), 'error');
			}

			// save the changes
			if (!$table->store()) {
				throw new ResponsivizerException($table->getError(), 'error');
			}

			// save the changes
			if (!$this->storePermissionsAsset($post)) {
				throw new ResponsivizerException(JTEXT::_('ERROR_STORING_PERMISSIONS'), 'error');
			}

		} catch (ResponsivizerException $e) {
			$this->setError($e);
			return false;
		} catch (Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$this->setError($rvzrException);
			return false;
		}

		// Clean the cache.
		$this->cleanComponentCache('_system', 0);
		$this->cleanComponentCache('_system', 1);
		$this->cleanSiteCache();
		
		return true;
	}
	
	/**
	 * Class contructor
	 *
	 * @access public
	 * @return Object&
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
	
		// App reference
		$this->app = JFactory::getApplication ();
	}
}