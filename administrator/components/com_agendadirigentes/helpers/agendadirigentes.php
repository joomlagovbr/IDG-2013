<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * AgendaDirigentes component helper.
 */
class AgendaDirigentesHelper extends JHelperContent
{

    public static $extension = 'com_agendadirigentes';
    public static $coreCreate = array();
    public static $coreDelete = array();
    public static $coreEdit = array();
    public static $coreEditOwn = array();
    public static $coreEditState = array();
    public static $permissions = array();
    public static $assets = array();
    public static $user = NULL;
    public static $isSuperUser = NULL;
    public static $cmp_params = NULL;
    public static $permissionType = NULL;
    public static $editOwnState = NULL;
    public static $pluginActive = NULL;

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($submenu = NULL, $canDo = NULL)
    {
        if(is_null($canDo))
            $canDo  = self::getActions('com_agendadirigentes');

        $can_categories_list = $canDo->get('categories.list');
        $can_dirigentes_list = $canDo->get('dirigentes.list');
        $can_cargos_list = $canDo->get('cargos.list');

        if ($can_categories_list || $can_dirigentes_list || $can_cargos_list)
        {
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_COMPROMISSOS'),
                'index.php?option=com_agendadirigentes&view=compromissos',
                $submenu == 'compromissos'
            );
        }

        if ($can_categories_list) 
        {
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_CATEGORIES'),
                'index.php?option=com_categories&extension=com_agendadirigentes',
                $submenu == 'categories');
        }

        if ($can_dirigentes_list) 
        {
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_DIRIGENTES'),
                'index.php?option=com_agendadirigentes&view=dirigentes',
                $submenu == 'dirigentes'
            );  
        }

        if ($can_cargos_list) 
        {
            JHtmlSidebar::addEntry(
                JText::_('COM_AGENDADIRIGENTES_SUBMENU_CARGOS'),
                'index.php?option=com_agendadirigentes&view=cargos',
                $submenu == 'cargos'
            );                  
        }
        
    }

    // nova getAtions, necessaria mesmo no joomla 3. Codigo se baseia na funcao original JHelperContent::getActions
    public static function getActions($component = '', $section = '', $id = 0)
    {
        if ( (is_int($component) || is_null($component)) || (empty($section) || $section=='component') ) //linha modificada em relacao a funcao original
        {
            $result = JHelperContent::getActions($component, $section, $id);                        
            return $result;
        }

        $user   = JFactory::getUser();
        $result = new JObject;

        $path = JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml';

        if ($section && $id)
        {
            $assetName = $component . '.' . $section . '.' . (int) $id;
        }
        else
        {
            $assetName = $component;
        }

        $actions = JAccess::getActionsFromFile($path, "/access/section[@name='" . $section . "']/"); //linha modificada em relacao a funcao original

        foreach ($actions as $action)
        {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

    public static function getGranularPermissions($type = 'compromissos', $item = NULL, $action = 'manage')
    {
        $canManage = false;
        $canChange = false;
        $canCreate = false;
        $canDelete = false;

        if(!$item)
        {
            JFactory::getApplication()->enqueueMessage( JText::_('COM_AGENDADIRIGENTES_HELPER_NOTLOADED_OR_NOTNUMBER'), 'error');
            return array($canManage, $canChange);
        }

        @$catid = (is_object($item))? $item->catid : intval($item);
        $permissionType = self::getPermissionType();

        if($type == 'compromissos')
        {
            if($action == 'manage') //--> && $type == 'compromissos'
            {
                $coreEdit = self::getCoreEdit();
                $coreEditOwn = self::getCoreEditOwn();
                $coreEditState = self::getCoreEditState();
                $editOwnState = self::getEditOwnState();

                $user = self::getUser();
                @$created_by = (is_object($item))? $item->created_by : 0;

                $categoryEdit = self::getPermission( 'core.edit', 'category', $catid ); 
                $categoryEditOwn = self::getPermission( 'core.edit.own', 'category', $catid ); 
                $categoryEditState = self::getPermission( 'core.edit.state', 'category', $catid );             

                if($permissionType == 'implicit') //--> && $action == 'manage' && $type == 'compromissos'
                {
                    if($coreEdit && $categoryEdit !== false)
                    {
                        $canManage = true;
                    }
                    else if($coreEditOwn && $categoryEditOwn !== false && $created_by == $user->id)
                    {
                        $canManage = true;
                    }

                    if(!$canManage && $coreEditState && $categoryEditState !== false)
                    {
                        $canChange = true;
                    }
                    else if($canManage && ($editOwnState || ($coreEditState && $categoryEditState !== false)))
                    {
                        $canChange = true;
                    }

                }
                elseif( $permissionType == 'explicit' ) //--> && $action == 'manage' && $type == 'compromissos'
                {
                    if($coreEdit && $categoryEdit)
                    {
                        $canManage = true;
                    }
                    else if($coreEditOwn && $categoryEditOwn && $created_by == $user->id)
                    {
                        $canManage = true;
                    }

                    if(!$canManage && $coreEditState && $categoryEditState)
                    {
                        $canChange = true;
                    }
                    else if($canManage && ($editOwnState || ($coreEditState && $categoryEditState)))
                    {
                        $canChange = true;
                    }  
                }

                return array($canManage, $canChange);

            }
            elseif ($action == 'create') //--> && $type == 'compromissos'
            {
                $coreCreate = self::getCoreCreate();                
                $categoryCreate = self::getPermission( 'core.create', 'category', $catid );

                if($permissionType == 'implicit')
                {
                    if($coreCreate && $categoryCreate !== false)
                    {
                        $canCreate = true;
                    }             
                }
                elseif( $permissionType == 'explicit' )
                {
                    if($coreCreate && $categoryCreate)
                    {
                        $canCreate = true;
                    }
                }

                return $canCreate;
            }
            elseif ($action == 'delete') //--> && $type == 'compromissos'
            {
                $coreDelete = self::getCoreDelete();                
                $categoryDelete = self::getPermission( 'core.delete', 'category', $catid );

                if($permissionType == 'implicit')
                {
                    if($coreDelete && $categoryDelete !== false)
                    {
                        $canDelete = true;
                    }             
                }
                elseif( $permissionType == 'explicit' )
                {
                    if($coreDelete && $categoryDelete)
                    {
                        $canDelete = true;
                    }
                }

                return $canDelete;
            }
        }
        elseif( $type == 'cargos' || $type == 'dirigentes' )
        {
            if($action == 'manage') //--> && ($type == 'cargos' || $type == 'dirigentes')
            {
                $coreEdit = self::getCoreEdit( $type );
                $coreEditState = self::getCoreEditState( $type );
                $categoryEdit = self::getPermission( $type . '.edit', 'category', $catid ); 
                $categoryEditState = self::getPermission( $type . '.edit.state', 'category', $catid );  

                if($permissionType == 'implicit')
                {
                    if($coreEdit && $categoryEdit !== false)
                    {
                        $canManage = true;
                    }

                    if($coreEditState && $categoryEditState !== false)
                    {
                        $canChange = true;
                    }                
                }
                elseif( $permissionType == 'explicit' )
                {
                    if($coreEdit && $categoryEdit)
                    {
                        $canManage = true;
                    }

                    if($coreEditState && $categoryEditState)
                    {
                        $canChange = true;
                    }  
                }

                return array($canManage, $canChange);

            }
            elseif ($action == 'create')  //--> && ($type == 'cargos' || $type == 'dirigentes')
            {
                $coreCreate = self::getCoreCreate( $type );
                $categoryCreate = self::getPermission( $type . '.create', 'category', $catid );
                
                if($permissionType == 'implicit')
                {
                    if($coreCreate && $categoryCreate !== false)
                    {
                        $canCreate = true;
                    }             
                }
                elseif( $permissionType == 'explicit' )
                {
                    if($coreCreate && $categoryCreate)
                    {
                        $canCreate = true;
                    }
                }

                return $canCreate;
            }
            elseif ($action == 'delete')  //--> && ($type == 'cargos' || $type == 'dirigentes')
            {
                $coreDelete = self::getCoreDelete( $type );
                $categoryDelete = self::getPermission( $type . '.delete', 'category', $catid );
                
                if($permissionType == 'implicit')
                {
                    if($coreDelete && $categoryDelete !== false)
                    {
                        $canDelete = true;
                    }             
                }
                elseif( $permissionType == 'explicit' )
                {
                    if($coreDelete && $categoryDelete)
                    {
                        $canDelete = true;
                    }
                }

                return $canDelete;
            }
        }
        

        return 0;
    }

    protected static function getUser()
    {
        if( is_null(self::$user) )
            self::$user = JFactory::getUser();

        return self::$user;
    }

    protected static function getCoreCreate( $scope = 'core' )
    {
        $user = self::getUser();

        if( ! array_key_exists($scope, self::$coreCreate) )
        {
            self::$coreCreate[$scope] = $user->authorise( $scope . '.create', self::$extension );
        }

        return self::$coreCreate[$scope];
    }

    protected static function getCoreDelete( $scope = 'core' )
    {
        $user = self::getUser();

        if( ! array_key_exists($scope, self::$coreDelete) )
        {
            self::$coreDelete[$scope] = $user->authorise( $scope . '.delete', self::$extension );
        }

        return self::$coreDelete[$scope];
    }

    protected static function getCoreEdit( $scope = 'core' )
    {
        $user = self::getUser();

        if( ! array_key_exists($scope, self::$coreEdit) )
        {
            self::$coreEdit[$scope] = $user->authorise( $scope . '.edit', self::$extension );
        }

        return self::$coreEdit[$scope];
    }

    protected static function getCoreEditOwn( $scope = 'core' )
    {
        $user = self::getUser();

        if( ! array_key_exists($scope, self::$coreEditOwn) )
        {
            self::$coreEditOwn[$scope] = $user->authorise( $scope . '.edit.own', self::$extension );
        }

        return self::$coreEditOwn[$scope];
    }

    protected static function getCoreEditState( $scope = 'core' )
    {
        $user = self::getUser();

        if( ! array_key_exists($scope, self::$coreEditState) )
        {
            self::$coreEditState[$scope] = $user->authorise( $scope . '.edit.state', self::$extension );
        }

        return self::$coreEditState[$scope];
    }

    public static function getParams()
    {
        if( is_null(self::$cmp_params) )
        {
            self::$cmp_params = JComponentHelper::getParams( self::$extension );            
        }

        return self::$cmp_params;
    }

    protected static function getPermissionType()
    {
        if( is_null(self::$permissionType) )
        {
            $params = self::getParams();
            self::$permissionType = $params->get('permissionsType', 'implicit');
        }

        return self::$permissionType;
    }

    protected static function getEditOwnState()
    {
        if( is_null(self::$editOwnState) )
        {
            $params = self::getParams();
            self::$editOwnState = $params->get('editOwnState', 0);
        }

        return self::$editOwnState;
    }

    public static function isSuperUser()
    {
        if( is_null(self::$isSuperUser) )
        {
            $user = self::getUser();
            self::$isSuperUser = (array_search(8, $user->groups)!==false);
        }

        return self::$isSuperUser; 
    }

    public static function getPermission( $action = 'core.edit', $context = 'category', $id = 0 )
    {
        if( ! isset(self::$permissions[$context]) )
        {
            self::$permissions[$context] = array();
        }

        if( ! isset(self::$permissions[$context][$id]) )
        {
            self::$permissions[$context][$id] = array();
        }

        if( ! array_key_exists($action, self::$permissions[$context][$id]) )
        {
            $user = self::getUser();
            $permissionType = self::getPermissionType();

            if($permissionType == 'implicit')
            {
                $permission = $user->authorise( $action, self::$extension . "." . $context . "." . $id );
                //EQUIVALE A: $user->authorise( "core.edit", "com_agendadirigentes.category." . $catid );
            }
            else
            {
                $permission = self::authoriseByAsset( $action, self::$extension . "." . $context . "." . $id );
            }

            self::$permissions[$context][$id][$action] = $permission;
        }

        return self::$permissions[$context][$id][$action];
    }

    protected static function authoriseByAsset( $action, $asset ) //somente para $permissionType == 'explicit'
    {
        if(empty($action) || empty($asset))
            return false;

        $asset = strtolower(preg_replace('#[\s\-]+#', '.', trim($asset)));
        $action = strtolower(preg_replace('#[\s\-]+#', '.', trim($action)));
        
        if( self::isSuperUser() )
        {
            return true;
        }

        if(! array_key_exists($asset, self::$assets))
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select(
                    $db->quoteName('rules')
                )
                ->from(
                    $db->quoteName('#__assets')
                )
                ->where(
                    $db->quoteName('name')
                    . ' = ' .
                    $db->Quote($asset)
                );

            $db->setQuery((string)$query);
            $rules = $db->loadResult();

            if(empty($rules))
            {
                self::$assets[$asset] = NULL;            
                return NULL;
            }

            self::$assets[$asset] = json_decode($rules);            
        }

        $user = self::getUser();
        $return = NULL;

        foreach($user->groups as $group)
        { 
            @$return = self::$assets[$asset]->{$action}->{$group};
            
            if($return == 1)
                break;
        }

        if(is_int($return))
            $return = (bool) $return;

        return $return;

    }

    public static function isPluginActive()
    {
        if( is_null(self::$pluginActive) )
        {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select(
                    $db->quoteName('enabled')
                )->from(
                    $db->quoteName('#__extensions')
                )->where(
                    $db->quoteName('type') . ' = ' . $db->Quote('plugin')
                    . ' AND ' .
                    $db->quoteName('element') . ' = ' . $db->Quote('agendadirigentes')
                    . ' AND ' .
                    $db->quoteName('folder') . ' = ' . $db->Quote('search')
                );
            $db->setQuery((string) $query);

            self::$pluginActive = $db->loadResult();
        }

        return self::$pluginActive; 
    }

    public static function verifySearchPlugin()
    {
        $params = self::getParams();
        $plugin = self::isPluginActive();
        $allow_search = (int) $params->get('allow_search_field', 0);
        
        if(is_numeric($plugin))
            $plugin = (int) $plugin;

        if($allow_search == 0 && $plugin == 1)
        {
            JFactory::getApplication()->enqueueMessage( JText::_('COM_AGENDADIRIGENTES_HELPER_PLUGIN_ENABLED'), 'warning');            
        }
        else if($allow_search == 1 && is_null($plugin))
        {
            JFactory::getApplication()->enqueueMessage( JText::_('COM_AGENDADIRIGENTES_HELPER_PLUGIN_NOT_FOUND'), 'warning');            
        }     
        else if($allow_search == 1 && $plugin == 0)
        {
            JFactory::getApplication()->enqueueMessage( JText::_('COM_AGENDADIRIGENTES_HELPER_PLUGIN_DISABLED'), 'warning');            
        }
         
    }

}