<?php

/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

include_once JPATH_ADMINISTRATOR . '/components/com_categories/models/category.php';

class CYENDModelCategory extends CategoriesModelCategory {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_categories/tables');
    }

    public function getTable($type = 'Category', $prefix = 'CategoriesTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

// import the Joomla modellist library
jimport('joomla.application.component.model');

class SPTransferModelCom extends JModelLegacy {

    protected $jAp;
    protected $tableLog;
    protected $destination_db;
    protected $destination_query;
    protected $destination_table;
    protected $destination_model;
    protected $table_name;
    protected $source_db;
    protected $source_query;
    protected $source_model;
    protected $user;
    protected $params;
    protected $task;
    protected $factory;
    protected $source;
    protected $id;
    protected $alias;
    protected $batch;
    protected $status;

    function __construct($config = array()) {
        parent::__construct($config);
        
        $this->factory = new CYENDFactory();
        $this->source = new CYENDSource();
        $this->jAp = JFactory::getApplication();
        $this->tableLog = $this->factory->getTable('Log', 'SPTransferTable');
        $this->destination_db = $this->getDbo();
        $this->destination_query = $this->destination_db->getQuery(true);
        $this->source_db = $this->source->source_db;
        $this->source_query = $this->source_db->getQuery(true);
        $this->user = JFactory::getUser();
        $this->params = JComponentHelper::getParams($this->factory->getComponentName());
        $this->alias = 'alias';
        $this->id = 'id';
        $this->batch = $this->params->get('batch', 100);
        $this->task = $config['task'];
        $this->status = $config['status'];
        $this->task->state = 2;        
    }

    public function categories($ids = null) {
        $this->destination_model = new CYENDModelCategory(array('dbo' => $this->destination_db));
        $this->source_model = new CYENDModelCategory(array('dbo' => $this->source_db));

        $this->task->state = 2; //state for success

        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__categories
            WHERE extension LIKE "' . $this->task->extension_name . '"';

        $this->items_new($ids);
    }

    public function categories_fix($ids = null) {
        $this->destination_model = new CYENDModelCategory(array('dbo' => $this->destination_db));
        $this->source_model = new CYENDModelCategory(array('dbo' => $this->source_db));

        $this->task->state = 4; //state for success

        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__categories
            WHERE extension LIKE "' . $this->task->extension_name . '"';

        $this->items_new_fix($ids);
    }

    public function items_new($pks = null) {
        // Initialize
        $factory = $this->factory;
        //$source = $this->source;
        $jAp = $this->jAp;
        $tableLog = $this->tableLog;
        $destination_db = $this->destination_db;
        //$destination_query = $this->destination_query;
        $source_db = $this->source_db;
        //$source_query = $this->source_query;
        $source_model = $this->source_model;
        $destination_model = $this->destination_model;
        $this->destination_table = $destination_model->getTable();
        $destination_table = $this->destination_table;
        //$user = $this->user;
        $params = $this->params;
        $task = $this->task;
        $table_name = $this->task->name;
        $id = $this->id;

        // Load pks
        $query = 'SELECT source_id
            FROM #__sptransfer_log
            WHERE tables_id = ' . (int) $task->id . ' AND state >= 2
            ORDER BY id ASC';
        $destination_db->setQuery($query);
        if (!CYENDFactory::execute($destination_db)) {
            jexit($destination_db->getErrorMsg());
        }
        $excludes = $destination_db->loadColumn();

        //Find ids
        if (is_null($pks[0])) {
            $existing_id = true;
            $query = $this->task->query;
            $query .= ' ORDER BY ' . $id . ' ASC';
            $source_db->setQuery($query);
            if (!CYENDFactory::execute($source_db)) {
                jexit($source_db->getErrorMsg());
            }
            $pks = $source_db->loadColumn();
        } else {
            $existing_id = false;
        }

        // Loop to save pks
        foreach ($pks as $pk) {

            //Load data from source
            $exclude = array_search($pk, $excludes);
            if ($exclude !== false) {
                unset($excludes[$exclude]);
                continue;
            }

            // Load object
            JFactory::$database = $this->source_db;
            try {
                $source_data = JArrayHelper::fromObject($source_model->getItem($pk));
            } catch (Exception $exc) {
                jexit($exc->getMessage());
            }
            JFactory::$database = $this->destination_db;

            if (empty($source_data[$id]) || ($source_data[$id] == 0)) {
                if ($existing_id) {
                    jexit($source_db->getErrorMsg());
                } else
                    continue;
            }

            //status pending
            $this->batch -= 1;
            if ($this->batch < 0) 
                return;

            //log            
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => $task->id, "source_id" => $pk));
            $tableLog->created = null;
            $tableLog->note = "";
            $tableLog->source_id = $pk;
            $tableLog->destination_id = $pk;
            $tableLog->state = 1;
            $tableLog->tables_id = $task->id;
            $tableLog->store();

            //rules
            if (array_key_exists('asset_id', $source_data))
                $source_data['rules'] = $this->getRules($source_data['asset_id']);
            //tags            
            if (array_key_exists('tags', $source_data))
                $source_data['tags'] = $this->convertTags($source_data['tags']['tags']);

            $tableLog->load(array("tables_id" => $task->id, "source_id" => $pk));

            // Create record
            $destination_db->setQuery(
                    "INSERT INTO #__" . $table_name .
                    " (" . $id . ")" .
                    " VALUES (" . $destination_db->quote($pk) . ")"
            );
            if (!CYENDFactory::execute($destination_db)) {
                if ($params->get("new_ids", 0) == 1) {
                    $destination_db->setQuery(
                            "INSERT INTO #__" . $table_name .
                            " (" . $id . ")" .
                            " VALUES (" . $destination_db->quote(0) . ")"
                    );
                    if (!CYENDFactory::execute($destination_db)) {
                        $tableLog->note = $destination_db->getErrorMsg();
                        $tableLog->store();
                        jexit($destination_db->getErrorMsg());
                        continue;
                    }
                    $destination_db->setQuery(
                            "SELECT id FROM #__" . $table_name .
                            " ORDER BY id DESC "
                    );
                    CYENDFactory::execute($destination_db);
                    $tableLog->destination_id = $destination_db->loadResult();
                    $message = '<p>' . JText::sprintf('COM_SPTRANSFER_MSG_NEW_IDS', $pk, $tableLog->destination_id) . '</p>';
                    $pk = $tableLog->destination_id;
                    $source_data['id'] = $tableLog->destination_id;
                    $tableLog->note = $message;
                } elseif ($params->get("new_ids", 0) == 0) {
                    $tableLog->note = $destination_db->getErrorMsg();
                    $tableLog->store();
                    jexit($destination_db->getErrorMsg());
                    continue;
                }
            }

            // Reset
            $destination_table->reset();
            $destination_table->_errors = Array();

            //Tags
            if ((!empty($source_data['tags']) && $source_data['tags'][0] != '')) {
                $destination_table->newTags = $source_data['tags'];
            }

            //Replace existing pk
            if ($params->get("new_ids", 0) == 2) {
                $destination_table->load($tableLog->source_id);
            }

            // Bind
            if (!$destination_table->bind($source_data)) {
                // delete record
                $destination_db->setQuery(
                        "DELETE FROM #__" . $table_name .
                        " WHERE " . $id . " = " . $destination_db->quote($pk)
                );
                if (!CYENDFactory::execute($destination_db)) {
                    jexit($destination_db->getErrorMsg());
                }
                $tableLog->note = $destination_db->getErrorMsg();
                $tableLog->store();
                jexit($destination_db->getErrorMsg());
                continue;
            }

            // Store
            try {
                $destination_table->store();
            } catch (RuntimeException $e) {
                //JError::raiseWarning(500, $e->getMessage());
                $destination_table->setError($e->getMessage());
                //do nothing
            }
            if ($destination_table->getError()) {
                if ($params->get("duplicate_alias", 0)) {
                    $destination_table->{$this->alias} .= '-sp-' . rand(100, 999);
                    if (!$destination_table->store()) {
                        // delete record
                        $destination_db->setQuery(
                                "DELETE FROM #__" . $table_name .
                                " WHERE " . $id . " = " . $destination_db->quote($pk)
                        );
                        if (!CYENDFactory::execute($destination_db)) {
                            jexit($destination_db->getErrorMsg());
                        }                        
                        $tableLog->note = $destination_db->getErrorMsg();
                        $tableLog->store();
                        jexit($destination_db->getErrorMsg());
                        continue;
                    }
                    $tableLog->note = '<p>' . JText::sprintf('COM_SPTRANSFER_MSG_DUPLICATE_ALIAS', $pk, $destination_table->{$this->alias}) . '</p>';
                } else {
                    // delete record
                    $destination_db->setQuery(
                            "DELETE FROM #__" . $table_name .
                            " WHERE " . $id . " = " . $destination_db->quote($pk)
                    );
                    if (!CYENDFactory::execute($destination_db)) {
                        jexit($destination_db->getErrorMsg());
                    }
                    $tableLog->note = $destination_db->getErrorMsg();
                    $tableLog->store();
                    jexit($destination_db->getErrorMsg());
                    continue;
                }
            }

            //save with model            
            $destination_object = $destination_model->getItem($destination_table->id);
            unset($destination_object->{$this->alias});
            $destination_data = JArrayHelper::fromObject($destination_object);

            //Various assignments
            //password
            if (array_key_exists('password', $destination_data)) {
                $destination_data['password2'] = $destination_data['password'];
            }
            //tags            
            if (array_key_exists('tags', $destination_data)) {
                $destination_data['tags'] = explode(',', $destination_data['tags']['tags']);
            }
            //images & urls
            $destination_data['images'] = $source_data['images'];
            $destination_data['urls'] = $source_data['urls'];
            //user profile
            if (array_key_exists('profile', $destination_data)) {
                $destination_data['profile'] = $source_data['profile'];
            }

            $destination_model->getState($destination_model->getName() . '.id');
            try {
                $destination_model->save($destination_data);
            } catch (Exception $exc) {
                $tableLog->note = $exc->getMessage();
                $tableLog->store();
                jexit($exc->getMessage());
                continue;
            }

            //add extra coding
            $this->tableLog = $tableLog;
            switch ($task->extension_name . '_' . $task->name) {
                case 'com_content_content':
                    $this->com_content_content();
                    break;
                case 'com_banners_banners':
                    $this->com_banners_banners();
                    break;
                case 'com_modules_modules':
                    $this->com_modules_modules();
                    break;
            }

            //Log
            $tableLog->state = $this->task->state; //state for success;
            $tableLog->store();
        } //Main loop end
        
        //status completed
        $this->status = 'completed';
    }

    public function items_new_fix($pks = null) {
        // Initialize
        $factory = $this->factory;
        //$source = $this->source;
        $jAp = $this->jAp;
        $tableLog = $this->tableLog;
        $destination_db = $this->destination_db;
        //$destination_query = $this->destination_query;
        //$source_query = $this->source_query;
        $destination_model = $this->destination_model;
        $this->destination_table = $destination_model->getTable();
        $source_db = $this->source_db;
        //$source_query = $this->source_query;
        //$destination_table = $this->destination_table;
        //$user = $this->user;
        //$params = $this->params;
        $task = $this->task;
        $id = $this->id;

        // Load items
        $query = 'SELECT destination_id
            FROM #__sptransfer_log
            WHERE tables_id = ' . (int) $task->id . ' AND ( state = 2 OR state = 3 )';
        $query .= ' ORDER BY id ASC';
        $destination_db->setQuery($query);
        if (!CYENDFactory::execute($destination_db)) {
            jexit($destination_db->getErrorMsg());
        }
        $excludes = $destination_db->loadColumn();

        //Find ids
        if (is_null($pks[0])) {
            $existing_id = true;
            $query = $this->task->query;
            $query .= ' ORDER BY ' . $id . ' ASC';
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                jexit($destination_db->getErrorMsg());
            }
            $pks = $destination_db->loadColumn();
        } else {
            $existing_id = false;
        }

        // Loop to save pks
        foreach ($pks as $pk) {

            //Load data from source
            if (!$existing_id) {
                $tableLog->reset();
                $tableLog->id = null;
                $tableLog->load(array("tables_id" => $task->id, "source_id" => $pk));
                $pk = $tableLog->destination_id;
            }

            $exclude = array_search($pk, $excludes);
            if ($exclude === false)
                continue;
            else
                unset($excludes[$exclude]);

            //save with model
            $destination_data = JArrayHelper::fromObject($destination_model->getItem($pk));

            if (empty($destination_data[$id]) || ($destination_data[$id] == 0)) {
                if ($existing_id) {
                    jexit($source_db->getErrorMsg());
                } else
                    continue;
            }

            //status pending
            $this->batch -= 1;
            if ($this->batch < 0) 
                return;

            //tags            
            if (array_key_exists('tags', $destination_data))
                unset($destination_data['tags']);

            //add extra coding
            $this->tableLog = $tableLog;
            switch ($task->extension_name . '_' . $task->name) {
                case 'com_users_viewlevels':
                    $destination_data = $this->com_users_viewlevels_fix($destination_data);
                    break;
                case 'com_users_users':
                    $destination_data = $this->com_users_users_fix($destination_data);
                    break;
                case 'com_banners_banners':
                    $destination_data = $this->com_banners_banners_fix($destination_data);
                    break;
                case 'com_menus_menu':
                    $destination_data = $this->com_menus_menu_fix($destination_data);
                    break;
                case 'com_modules_modules':
                    $this->com_modules_modules_fix($destination_data);
                    break;
            }

            // Set destination_id
            //created_by
            if ($destination_data['created_by'] > 0) {
                $tableLog->load(array("tables_id" => 3, "source_id" => $destination_data['created_by']));
                if ($tableLog->source_id != $tableLog->destination_id) {
                    $destination_data['created_by'] = $tableLog->destination_id;
                }
            }

            //modified_by
            /*
              if ($destination_data['modified_by'] > 0) {
              $tableLog->load(array("tables_id" => 3, "source_id" => $destination_data['modified_by']));
              if ($tableLog->source_id != $tableLog->destination_id) {
              $destination_data['modified_by'] = $tableLog->destination_id;
              }
              }
             * 
             */

            //catid
            if ($destination_data['catid'] > 1) {

                $tableLog->reset();
                $tableLog->id = null;
                $tableLog->load(array("tables_id" => $this->task->category, "source_id" => $destination_data['catid']));
                if ($tableLog->source_id == $tableLog->destination_id) {
                    $tableLog->load(array("tables_id" => $task->id, "destination_id" => $destination_data['id']));
                    $tableLog->state = 4;
                    $tableLog->store();
                    continue;
                }
                $destination_data['catid'] = $tableLog->destination_id;
            } /* else {
              $tableLog->reset();
              $tableLog->id = null;
              $tableLog->load(array("tables_id" => $task->id, "destination_id" => $destination_data['id']));
              $tableLog->state = 4;
              $tableLog->store();
              continue;
              }
             * 
             */

            //parent_id
            if ($destination_data['parent_id'] > 1) {
                $tableLog->reset();
                $tableLog->id = null;
                $tableLog->load(array("tables_id" => $task->id, "source_id" => $destination_data['parent_id']));
                $destination_data['parent_id'] = $tableLog->destination_id;
            }

            //Password
            if (array_key_exists('password', $destination_data)) {
                $destination_data['password2'] = $destination_data['password'];
            }

            //log            
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => $task->id, "destination_id" => $destination_data['id']));
            $tableLog->created = null;
            $tableLog->state = 3;
            $tableLog->tables_id = $task->id;

            //save model
            try {
                $destination_model->save($destination_data);
            } catch (Exception $exc) {
                $tableLog->note = $message;
                $tableLog->store();
                jexit($exc->getMessage());
                continue;
            }
            
            //add extra coding
            $this->tableLog = $tableLog;
            switch ($task->extension_name . '_' . $task->name) {
                case 'com_users_users':
                    $this->com_users_users_fix_new($destination_data);
                    break;
            }
    
            //Log
            $tableLog->state = 4;
            $tableLog->store();
        } //Main loop end   

        if (method_exists($destination_model, 'rebuild')) {
            $destination_model->rebuild();
        }
        
        //status completed
        $this->status = 'completed';
    }

    private function com_users_users() {
        $tableLog = $this->tableLog;
        $factory = $this->factory;
        $source_db = $this->source_db;
        $destination_db = $this->destination_db;

        // User Usergroup Map
        $query = 'SELECT group_id'
                . ' FROM #__user_usergroup_map '
                . ' WHERE user_id = ' . (int) $tableLog->source_id
        ;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $group_ids = $source_db->loadColumn();
        foreach ($group_ids as $group_id) {
            $query = "INSERT INTO #__user_usergroup_map" .
                    " (user_id,group_id)" .
                    " VALUES (" . $destination_db->quote($tableLog->destination_id) . ',' . $destination_db->quote($group_id) . ")";
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                $tableLog->note = $destination_db->getErrorMsg();
                $tableLog->store();
                jexit($destination_db->getErrorMsg());
                continue;
            }
        }

        // User Profiles
        $query = 'SELECT *'
                . ' FROM #__user_profiles '
                . ' WHERE user_id = ' . (int) $tableLog->source_id
        ;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $profiles = $source_db->loadObjectList();
        foreach ($profiles as $profile) {
            $query = "INSERT INTO #__user_profiles" .
                    " (user_id,profile_key,profile_value,ordering)" .
                    " VALUES (" . $destination_db->quote($profile->user_id) .
                    ',' . $destination_db->quote($profile->profile_key) .
                    ',' . $destination_db->quote($profile->profile_value) .
                    ',' . $destination_db->quote($profile->ordering) .
                    ")";
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                $tableLog->note = $destination_db->getErrorMsg();
                $tableLog->store();
                jexit($destination_db->getErrorMsg());
                continue;
            }
        }
    }

    private function com_users_users_fix($item) {

        $tableLog = $this->tableLog;

        $groups = $item['groups'];
        $item['groups'] = null;
        //groups
        foreach ($groups as $value) {
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => 1, "source_id" => $value));
            $item['groups'][$tableLog->destination_id] = $tableLog->destination_id;
        }

        return $item;
    }

    private function com_users_users_fix_new($item) {
        $tableLog = $this->tableLog;
        $source_db = $this->source_db;
        $destination_db = $this->destination_db;

        //load password from source
        $query = 'SELECT password
                FROM #__users
                WHERE id = ' . (int) $tableLog->source_id;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $password = $source_db->loadResult();
        
        //update in destination
        $query = "UPDATE `#__users` SET `password` = '".$password."' WHERE `id` = " . (int) $tableLog->destination_id;
        $destination_db->setQuery($query);
        CYENDFactory::execute($destination_db);

        return true;
    }

    private function com_users_viewlevels_fix($item) {
        $tableLog = $this->tableLog;

        $rules = $item['rules'];

        foreach ($rules as $k => $rule) {
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => 1, "source_id" => $rule));
            $rules2[$k] = (int) $tableLog->destination_id;
            if ($rules2[$k] == 0)
                $rules2[$k] = 1;
        }
        $item['rules'] = $rules2;

        return $item;
    }

    private function com_content_content() {
        $tableLog = $this->tableLog;
        $source_db = $this->source_db;
        $destination_db = $this->destination_db;

        //featured
        $query = 'SELECT *
                FROM #__content_frontpage
                WHERE content_id = ' . (int) $tableLog->source_id;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $result = $source_db->loadAssoc();
        if (!is_null($result)) {
            $destination_db->setQuery(
                    "INSERT INTO #__content_frontpage
                        (content_id, ordering)
                        VALUES (" . $tableLog->destination_id . " , " . $result['ordering'] . ")"
            );
            CYENDFactory::execute($destination_db);
        } else {
            $destination_db->setQuery(
                    "DELETE FROM #__content_frontpage
                        WHERE content_id = " . $destination_db->quote($tableLog->destination_id)
            );
            CYENDFactory::execute($destination_db);
        }

        //rating
        $query = 'SELECT *
                FROM #__content_rating
                WHERE content_id = ' . (int) $tableLog->source_id;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $result = $source_db->loadAssoc();
        if (!is_null($result)) {
            $destination_db->setQuery(
                    "INSERT INTO #__content_rating
                        (content_id, rating_sum, rating_count, lastip)
                        VALUES (" . $tableLog->destination_id . " , " . $result['rating_sum'] . " , " . $result['rating_count'] . " , '" . $result['lastip'] . "')"
            );
            CYENDFactory::execute($destination_db);
        }
    }

    private function com_banners_banners() {
        $tableLog = $this->tableLog;
        $source_db = $this->source_db;
        $destination_db = $this->destination_db;

        //banner tracks
        $query = 'SELECT *
                FROM #__banner_tracks
                WHERE banner_id = ' . (int) $tableLog->source_id;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $result = $source_db->loadAssoc();
        if (!is_null($result)) {
            $query = "INSERT INTO #__banner_tracks
                        (track_date, track_type, banner_id, count)
                        VALUES (" . $destination_db->quote($result['track_date']) . " , " .
                    $destination_db->quote($result['track_type']) . " , " .
                    $destination_db->quote($tableLog->destination_id) . " , " .
                    $destination_db->quote($result['count']) .
                    ")";
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                jexit($destination_db->getErrorMsg());
            }
        }
    }

    private function com_banners_banners_fix($item) {
        $tableLog = $this->tableLog;

        //cid
        if ($item['cid'] > 0) {
            $tableLog->load(array("tables_id" => 13, "source_id" => $item['cid']));
            if ($tableLog->source_id != $tableLog->destination_id) {
                $item['cid'] = $tableLog->destination_id;
            }
        }
        return $item;
    }

    private function com_menus_menu_fix($item) {
        $tableLog = $this->tableLog;
        $destination_db = $this->destination_db;
        $source_db = $this->source_db;

        //menutype
        $menutype_table = $this->factory->getTable('MenuType', 'JTable');
        $menutype_table->load(array("menutype" => $item['menutype']));
        if (empty($menutype_table->id)) {
            $query = 'SELECT menutype FROM #__menu_types WHERE menutype LIKE "' . $item['menutype'] . '-sp-%"';
            $destination_db->setQuery($query);
            CYENDFactory::execute($destination_db);
            $item['menutype'] = $destination_db->loadResult();
        }

        //component_id
        $query = "SELECT component_id FROM #__menu WHERE id = " . (int) $item['id'];
        $destination_db->setQuery($query);
        CYENDFactory::execute($destination_db);
        $extension_id = $destination_db->loadResult();
        $query = "SELECT name FROM #__extensions WHERE extension_id = " . (int) $extension_id;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $name = $source_db->loadResult();
        $query = 'SELECT extension_id FROM #__extensions WHERE name LIKE "' . $name . '"';
        $destination_db->setQuery($query);
        CYENDFactory::execute($destination_db);
        $item['component_id'] = $destination_db->loadResult();

        //parent_id
        /*
          if ($item['parent_id'] > 1) {
          $tableLog->reset();
          $tableLog->id = null;
          $tableLog->load(array("tables_id" => $task->id, "source_id" => $item['parent_id']));
          $item['parent_id'] = $tableLog->destination_id;
          }
         * 
         */

        //link
        $link_1 = preg_split('/[&=]/', str_replace('index.php?', '', $item['link']));
        foreach ($link_1 as $key => $value) {
            if ($key % 2 == 0) {
                $link[$value] = $link_1[$key + 1];
            }
        }


        $query = 'SELECT id FROM #__sptransfer_tables' .
                ' WHERE extension_name LIKE ' . $destination_db->quote($link['option']);
        if (($link['view'] == 'category') || ($link['view'] == 'categories')) {
            $query .= " AND name LIKE 'categories'";
        } else {
            $query .= " AND name  NOT LIKE 'categories'";
        }
        $destination_db->setQuery($query);
        CYENDFactory::execute($destination_db);
        $table_id = $destination_db->loadResult();
        $tableLog->reset();
        $tableLog->id = null;
        $tableLog->load(array("tables_id" => $table_id, "source_id" => $link['id']));
        $item['link'] = str_replace('id=' . $link['id'], 'id=' . $tableLog->destination_id, $item['link']);

        return $item;
    }

    private function com_modules_modules($item) {
        $destination_db = $this->destination_db;
        $tableLog = $this->tableLog;
        $source_db = $this->source_db;
        $factory = $this->factory;

        // Modules_Menu
        //First delete
        $destination_db->setQuery(
                "DELETE FROM #__modules_menu
                    WHERE moduleid = " . $destination_db->quote($tableLog->destination_id)
        );
        CYENDFactory::execute($destination_db);
        //Then insert
        $query = 'SELECT *'
                . ' FROM #__modules_menu '
                . ' WHERE moduleid = ' . (int) $tableLog->source_id
        ;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $modules_menus = $source_db->loadAssocList();
        foreach ($modules_menus as $modules_menu) {
            $query = "INSERT INTO #__modules_menu" .
                    " (moduleid,menuid)" .
                    " VALUES (" . $destination_db->quote($tableLog->destination_id) . ',' . $destination_db->quote($modules_menu['menuid']) . ")";
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                $tableLog->note = $destination_db->getErrorMsg();
                $tableLog->store();
                jexit($destination_db->getErrorMsg());
                continue;
            }
        }
    }

    private function com_modules_modules_fix($item) {
        $destination_db = $this->destination_db;
        $source_db = $this->source_db;
        $tableLog = $this->tableLog;
        $factory = $this->factory;
        $task = $this->task;

        // Set destination_id
        $tableLog->reset();
        $tableLog->id = null;
        $tableLog->load(array("tables_id" => 16, "source_id" => $item['menuid']));
        $item['menuid'] = $tableLog->destination_id;
        $menuid = $tableLog->source_id;
        if ($tableLog->source_id == $tableLog->destination_id) {
            $tableLog->load(array("tables_id" => $task->id, "destination_id" => $item['id']));
            $tableLog->state = 4;
            $tableLog->store();
            return;
        }

        //log            
        $tableLog->reset();
        $tableLog->id = null;
        $tableLog->load(array("tables_id" => $task->id, "destination_id" => $item['id']));
        $tableLog->created = null;
        $tableLog->state = 3;
        $tableLog->tables_id = $task->id;

        // update
        $query = 'UPDATE #__modules_menu '
                . ' SET menuid = ' . (int) $item['menuid']
                . ' WHERE moduleid = ' . (int) $tableLog->destination_id
                . ' AND menuid = ' . (int) $menuid;
        ;
        $destination_db->setQuery($query);
        CYENDFactory::execute($destination_db);
        $source_db->loadResult();
        if (!CYENDFactory::execute($destination_db)) {
            $tableLog->note = $destination_db->getErrorMsg();
            $tableLog->store();
            jexit($destination_db->getErrorMsg());
            return;
        }
    }

    private function getRules($asset_id) {
        $source_db = $this->source_db;

        // update
        $query = 'SELECT rules FROM #__assets '
                . ' WHERE id = ' . (int) $asset_id;
        $source_db->setQuery($query);
        CYENDFactory::execute($source_db);
        $rules_json = $source_db->loadResult();
        $rules_object = json_decode($rules_json);
        $rules_array = JArrayHelper::fromObject($rules_object);
        return $rules_array;
    }

    private function convertTags($tags) {
        $tagsArray = explode(',', $tags);

        $tableLog = $this->tableLog;

        foreach ($tagsArray as $key => $tagID) {
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => 20, "source_id" => $tagID));
            if ($tableLog->destination_id == 0)
                return null;
            $tagsArray[$key] = $tableLog->destination_id;
        }

        return $tagsArray;
    }
    
    public function getResult() {
        
        $result = Array();
        $result['status'] = $this->status;
        $result['message'] = $this->task->extension_name . ' - ' . $this->task->name;
        
        return $result;
    }

}
