<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * AgendaDirigentes component helper.
 */
class AgendaDirigentesHelper extends JHelperContent
{

    public static $extension = 'com_agendadirigentes';
    public static $coreEdit = 'notset';
    public static $coreEditOwn = 'notset';
    public static $coreEditState = 'notset';
    public static $permissions = array();
    public static $assets = array();
    public static $user = NULL;
    public static $cmp_params = NULL;
    public static $permissionType = NULL;
    public static $editOwnState = NULL;

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

    public static function getGranularPermissions($type = 'compromissos', $item = NULL )
    {
        $canManage = false;
        $canChange = false;

        if(!$item)
        {
            JFactory::getApplication()->enqueueMessage('AgendaDirigentesHelper::getGranularPermissions -> Item não carregado.', 'error');
            return array($canManage, $canChange);
        }

        if($type == 'compromissos')
        {
            $coreEdit = self::getCoreEdit();
            $coreEditOwn = self::getCoreEditOwn();
            $coreEditState = self::getCoreEditState();

            $permissionType = self::getPermissionType();
            $editOwnState = self::getEditOwnState();
            $user = self::getUser();
            
            @$catid = $item->catid;
            @$created_by = $item->created_by;

            $categoryEdit = self::getPermission( 'core.edit', 'category', $catid ); 
            $categoryEditOwn = self::getPermission( 'core.edit.own', 'category', $catid ); 
            $categoryEditState = self::getPermission( 'core.edit.state', 'category', $catid ); 

            if($permissionType == 'implicit')
            {
                if($coreEdit && $categoryEdit !== false)
                {
                    $canManage = true;
                }
                else if($coreEditOwn && $categoryEditOwn !== false && $item->created_by == $user->id)
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
            elseif( $permissionType == 'explicit' )
            {
                if($coreEdit && $categoryEdit)
                {
                    $canManage = true;
                }
                else if($coreEditOwn && $categoryEditOwn && $item->created_by == $user->id)
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
        }

        return array($canManage, $canChange);
    }

    protected static function getUser()
    {
        if( is_null(self::$user) )
            self::$user = JFactory::getUser();

        return self::$user;
    }

    protected static function getCoreEdit()
    {
        $user = self::getUser();

        if(self::$coreEdit == 'notset')
        {
            self::$coreEdit = $user->authorise( "core.edit", self::$extension );
        }

        return self::$coreEdit;
    }

    protected static function getCoreEditOwn()
    {
        $user = self::getUser();

        if(self::$coreEditOwn == 'notset')
        {
            self::$coreEditOwn = $user->authorise( "core.edit.own", self::$extension );
        }

        return self::$coreEditOwn;
    }

    protected static function getCoreEditState()
    {
        $user = self::getUser();

        if(self::$coreEditState == 'notset')
        {
            self::$coreEditState = $user->authorise( "core.edit.state", self::$extension );
        }

        return self::$coreEditState;
    }

    protected static function getParams()
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
                    // = $user->authorise( "core.edit", "com_agendadirigentes.category." . $catid );
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

}