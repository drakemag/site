<?php
// namespace administrator\components\com_responsivizer\models;
/**
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Sources model responsibilities
 *
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
interface ISourcesModel {
	/**
	 * Storing entity by ORM table
	 * 
	 * @access public
	 * @param IResponsivizerHttp $HTTPTransport
	 * @return mixed
	 */
	public function storeEntity(IResponsivizerHttp $HTTPClient = null);
}

/**
 * Sources model concrete implementation <<testable_behavior>>
 *
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
class ResponsivizerModelOfflinecache extends ResponsivizerModel implements ISourcesModel {
	/**
	 * Build list entities query
	 *
	 * @access protected
	 * @return string
	 */
	protected function buildListQuery() {
		// WHERE
		$where = array ('m.client_id = 0', 's.deleted = 0', 'm.type=' . $this->_db->quote('component'));
		$whereString = null;
		$orderString = null;
		// STATE FILTER
		if ($filter_state = $this->state->get ( 'state' )) {
			if ($filter_state == 'P') {
				$where [] = 's.published = 1';
			} else if ($filter_state == 'U') {
				$where [] = 's.published = 0';
			}
		}
	
		// TEXT FILTER
		if ($this->state->get ( 'searchword' )) {
			$where [] = "m.title LIKE '%" . $this->state->get ( 'searchword' ) . "%'";
		}
	
		if (count ( $where )) {
			$whereString = "\n WHERE " . implode ( "\n AND ", $where );
		}
	
		// ORDERBY
		if ($this->state->get ( 'order' )) {
			$orderString = "\n ORDER BY " . $this->state->get ( 'order' ) . " ";
		}
	
		// ORDERDIR
		if ($this->state->get ( 'order_dir' )) {
			$orderString .= $this->state->get ( 'order_dir' );
		}
	
		$query = "SELECT s.*, m.title AS " . $this->_db->qn('pagetitle') .
				 "\n FROM #__rvzr_offline_urls AS s" .
				 "\n LEFT JOIN #__menu AS m ON m.id = s.itemid" .
				 $whereString .
				 $orderString;
	
		return $query;
	}
	
	/**
	 * Build list entities query
	 *
	 * @access protected
	 * @param string $url Target url to fetch data to parse
	 * @param Object $client Transport client to fetch data from remote resource
	 * @param Object The table object to check flags chosen for resources to parse
	 * @param boolean Whether the SEF is on or off
	 * @return array
	 */
	protected function generateManifest($url, IResponsivizerHttp $client, $table, $sefmode) {
		// Resources found
		$foundResources = array();
		$uriInstance = JURI::getInstance();
		$liveSite = rtrim(JURI::root(), '/');
		$resourceLiveSite = rtrim($uriInstance->getScheme() . '://' . $uriInstance->getHost(), '/');
		
		// Retrieve data from remote url resource through HTTP client connector
		$chunkLiveSite = $sefmode ? $resourceLiveSite : $liveSite;
		$url = preg_match('/http/i', $url) ? $url : $chunkLiveSite . '/' . ltrim($url, '/');
		$dataString = $client->get($url, array('X-Requested-With'=>'manifest'))->body;
		
		// Start finding resources to be cached using DOM-based approach
		$domDocumentObject = new DOMDocument();
		$domDocumentObject->loadHTML($dataString);
		
		// XPath object 
		$domXPathObject = new DOMXPath($domDocumentObject);
		
		// Process stylesheets
		if($table->process_css) {
			foreach ($domXPathObject->query("//link") as $link) {
				$relAttribute = $link->getAttribute("rel");
				if ($relAttribute && $relAttribute != "stylesheet") {
					continue;
				}
			
				$foundHref = $link->getAttribute('href');
				// Process absolute urls
				$absoluteHRef = preg_match('/http/i', $foundHref) ? $foundHref : $resourceLiveSite . '/' . ltrim($foundHref, '/');
				$foundResources[] = $absoluteHRef;
			}
		}
		
		// Process scripts
		if($table->process_js) {
			foreach ($domXPathObject->query("//script") as $link) {
				$foundScriptSrc = $link->getAttribute('src');
				// Check if script is not inline and has a src before assignment
				if(!$foundScriptSrc) {
					continue;
				}
				
				// Process absolute urls
				$absoluteSrc = preg_match('/http/i', $foundScriptSrc) ? $foundScriptSrc : $resourceLiveSite . '/' . ltrim($foundScriptSrc, '/');
				$foundResources[] = $absoluteSrc;
			}
		}
		
		// Process images
		if($table->process_img) {
			foreach ($domXPathObject->query("//img") as $link) {
				$foundScriptSrc = $link->getAttribute('src');
				
				// Process absolute urls
				$absoluteSrc = preg_match('/http/i', $foundScriptSrc) ? $foundScriptSrc : $resourceLiveSite . '/' . ltrim($foundScriptSrc, '/');
				
				// Additional check for valid images link, also if SEF Joomla plugin is not working. An invalid img resource invalidate the whole cache!
				$responseCode = $client->get($absoluteSrc)->code;
				if($responseCode == 404) {
					$absoluteSrc = $liveSite . '/' . ltrim($foundScriptSrc, '/');
					$responseCode = $client->get($absoluteSrc)->code;
					if($responseCode == 404) {
						continue;
					}
				}
				
				$foundResources[] = $absoluteSrc;
			}
		}
		
		return $foundResources;
	}
	
	/**
	 * Return select lists used as filter for listEntities
	 *
	 * @access public 
	 * @return array
	 */
	public function getFilters() {
		$filters ['state'] = JHTML::_ ( 'grid.state', $this->getState ( 'state' ) );
		
		return $filters;
	}
	
	/**
	 * Return select lists used as filter for editEntity
	 *
	 * @access public
	 * @param Object $record
	 * @return array
	 */
	public function getLists($record) {
		$lists = array ();
		// Grid states
		$lists ['published'] = JHTML::_ ( 'select.booleanlist', 'published', null, $record->published );
		$lists ['process_css'] = JHTML::_ ( 'select.booleanlist', 'process_css', null, $record->process_css );
		$lists ['process_js'] = JHTML::_ ( 'select.booleanlist', 'process_js', null, $record->process_js );
		$lists ['process_img'] = JHTML::_ ( 'select.booleanlist', 'process_img', null, $record->process_img );
		
		// Use Framework Elements
		$menuAvailableOptions = ResponsivizerElementsMenuselect::getOptions($record->itemid);
		$lists['itemids']	= JHTML::_('select.genericlist', $menuAvailableOptions, 'itemid', 'class="inputbox" data-validation="required" size="15"', 'value', 'text', $record->itemid, 'itemid' );
		
		return $lists;
	}
	
	/**
	 * Storing entity by ORM table
	 * 
	 * @access public
	 * @param ResponsivizerHttp $HTTPClient
	 * @return mixed
	 */
	public function storeEntity(IResponsivizerHttp $HTTPClient = null){
		$table = $this->getTable();
		try {
			if (!$table->bind ($_POST, true)) {
				throw new ResponsivizerException($table->getError (), 'error');
			}
	
			if (!$table->check ( )) {
				throw new ResponsivizerException($table->getError (), 'error');
			}
	
			// Generate manifest resources if new record or itemid has changed
			if(!$table->id || $this->getState('hasChanged', false)) {
				$menuTable = $this->getTable('Menu', 'JTable');
				$menuTable->load($table->itemid);
				
				if(JFactory::getConfig()->get('sef', 1)) {
					// Build SEF route using site router
					$siteRouter = JRouterSite::getInstance('site', array('mode'=>JROUTER_MODE_SEF));
					$sefURI = $siteRouter->build('?Itemid=' . $menuTable->id)->toString();
					
					// Check if multilanguage is enabled
					$activeMultiLang = ResponsivizerLanguageMultilang::isEnabled();
					if($activeMultiLang) {
						if($menuTable->language != '*') {
							// New language manager instance
							$languageManager = ResponsivizerLanguageMultilang::getInstance($menuTable->language);
						} else {
							// Get the default language tag
							// New language manager instance
							$languageManager = ResponsivizerLanguageMultilang::getInstance();
						}
						
						// Extract the language tag
						$localeTag = $languageManager->getLocale();
						$sefTag = $localeTag[4];
						$sefURI = str_replace('/administrator', '/' . $sefTag, $sefURI);
					} else {
						$sefURI = str_replace('/administrator', '', $sefURI);
					}
					$table->resources = json_encode($this->generateManifest($sefURI, $HTTPClient, $table, true));
				} else {
					$sefURI = $menuTable->link . '&Itemid=' . $menuTable->id;
					
					// Check if multilanguage is enabled
					$activeMultiLang = ResponsivizerLanguageMultilang::isEnabled();
					if($activeMultiLang) {
						if($menuTable->language != '*') {
							// New language manager instance
							$languageManager = ResponsivizerLanguageMultilang::getInstance($menuTable->language);
						} else {
							// Get the default language tag
							// New language manager instance
							$languageManager = ResponsivizerLanguageMultilang::getInstance();
						}
					
						// Extract the language tag
						$localeTag = $languageManager->getLocale();
						$sefTag = $localeTag[4];
						$sefURI .= '&lang=' . $sefTag;
					}
					$table->resources = json_encode($this->generateManifest($sefURI, $HTTPClient, $table, false));
				}
			}
			
			if (! $table->store (false)) {
				throw new ResponsivizerException($table->getError (), 'error');
			} 
		} catch(ResponsivizerException $e) {
			$this->setError($e);
			return false;
		}  catch(Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$this->setError($rvzrException);
			return false;
		}
			
		return $table;
	}
	
	/**
	 * Publishing state changer for entities
	 *
	 * @access public
	 * @param int $idEntity
	 * @param string $state
	 * @return boolean
	 */
	public function deleteEntity($ids) {
		// Table load
		$table = $this->getTable();
		// Ciclo su ogni entity da cancellare
		foreach ($ids as $id) {
			try {
				if (!$table->load($id)) {
					throw new ResponsivizerException($table->getError(), 'notice');
				}
				$table->deleted = 1;
	
				if (!$table->store(true)) {
					throw new ResponsivizerException($table->getError(), 'notice');
				}
			} catch (ResponsivizerException $e) {
				$this->setError($e);
				return false;
			} catch (Exception $e) {
				$rvzrException = new ResponsivizerException($e->getMessage(), 'notice');
				$this->setError($rvzrException);
				return false;
			}
		}
		return true;
	}
}