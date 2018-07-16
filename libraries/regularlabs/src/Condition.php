<?php
/**
 * @package         Regular Labs Library
 * @version         18.7.10792
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use DateTimeZone;
use JFactory;

/**
 * Class Condition
 * @package RegularLabs\Library
 */
abstract class Condition
	implements \RegularLabs\Library\Api\ConditionInterface
{
	public $request      = null;
	public $date         = null;
	public $db           = null;
	public $selection    = null;
	public $params       = null;
	public $include_type = null;
	public $article      = null;
	public $module       = null;

	public function __construct($condition = [], $article = null, $module = null)
	{
		$tz         = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
		$this->date = JFactory::getDate()->setTimeZone($tz);

		$this->request = self::getRequest();

		$this->db = JFactory::getDbo();

		$this->selection    = isset($condition->selection) ? $condition->selection : [];
		$this->params       = isset($condition->params) ? $condition->params : [];
		$this->include_type = isset($condition->include_type) ? $condition->include_type : 'none';

		$this->article = $article;
		$this->module  = $module;
	}

	public function init()
	{
	}

	public function initRequest(&$request)
	{
	}

	public function beforePass()
	{
	}

	private function getRequest()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$id = $input->get('id', [0], 'array');

		$request = (object) [
			'idname' => 'id',
			'option' => $input->get('option'),
			'view'   => $input->get('view'),
			'task'   => $input->get('task'),
			'layout' => $input->getString('layout'),
			'Itemid' => $this->getItemId(),
			'id'     => (int) $id[0],
		];

		switch ($request->option)
		{
			case 'com_categories':
				$extension       = $input->getCmd('extension');
				$request->option = $extension ? $extension : 'com_content';
				$request->view   = 'category';
				break;

			case 'com_breezingforms':
				if ($request->view == 'article')
				{
					$request->option = 'com_content';
				}
				break;
		}

		$this->initRequest($request);

		if ( ! $request->id)
		{
			$cid         = $input->get('cid', [0], 'array');
			$request->id = (int) $cid[0];
		}

		// if no id is found, check if menuitem exists to get view and id
		if (Document::isClient('site')
			&& ( ! $request->option || ! $request->id)
		)
		{
			$menuItem = empty($request->Itemid)
				? $app->getMenu('site')->getActive()
				: $app->getMenu('site')->getItem($request->Itemid);

			if ($menuItem)
			{
				if ( ! $request->option)
				{
					$request->option = (empty($menuItem->query['option'])) ? null : $menuItem->query['option'];
				}

				$request->view = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
				$request->task = (empty($menuItem->query['task'])) ? null : $menuItem->query['task'];

				if ( ! $request->id)
				{
					$request->id = (empty($menuItem->query[$request->idname])) ? $menuItem->params->get($request->idname) : $menuItem->query[$request->idname];
				}
			}

			unset($menuItem);
		}

		return $request;
	}

	public function _($pass = true, $include_type = null)
	{
		$include_type = $include_type ?: $this->include_type;

		return $pass ? ($include_type == 'include') : ($include_type == 'exclude');
	}

	public function passSimple($values = '', $caseinsensitive = false, $include_type = null, $selection = null)
	{
		$values       = $this->makeArray($values);
		$include_type = $include_type ?: $this->include_type;
		$selection    = $selection ?: $this->selection;

		$pass = false;
		foreach ($values as $value)
		{
			if ($caseinsensitive)
			{
				if (in_array(strtolower($value), array_map('strtolower', $selection)))
				{
					$pass = true;
					break;
				}

				continue;
			}

			if (in_array($value, $selection))
			{
				$pass = true;
				break;
			}
		}

		return $this->_($pass, $include_type);
	}

	public function passInRange($value = '', $include_type = null, $selection = null)
	{
		$include_type = $include_type ?: $this->include_type;

		if (empty($value))
		{
			return $this->_(false, $include_type);
		}

		$selections = $this->makeArray($selection ?: $this->selection);

		$pass = false;
		foreach ($selections as $selection)
		{
			if (empty($selection))
			{
				continue;
			}

			if (strpos($selection, '-') === false)
			{
				if ((int) $value == (int) $selection)
				{
					$pass = true;
					break;
				}

				continue;
			}

			list($min, $max) = explode('-', $selection, 2);

			if ((int) $value >= (int) $min && (int) $value <= (int) $max)
			{
				$pass = true;
				break;
			}
		}

		return $this->_($pass, $include_type);
	}

	public function passItemByType(&$pass, $type = '', $data = null)
	{
		$pass_type = ! empty($data) ? $this->{'pass' . $type}($data) : $this->{'pass' . $type}();

		if ($pass_type == null)
		{
			return true;
		}

		$pass = $pass_type;

		return $pass;
	}

	public function passByPageType($option, $selection = [], $include_type = 'all', $add_view = false, $get_task = false, $get_layout = true)
	{
		if ($this->request->option != $option)
		{
			return $this->_(false, $include_type);
		}

		if ($get_task && $this->request->task && $this->request->task != $this->request->view && $this->request->task != 'default')
		{
			$pagetype = ($add_view ? $this->request->view . '_' : '') . $this->request->task;

			return $this->passSimple($pagetype, $selection, $include_type);
		}

		if ($get_layout && $this->request->layout && $this->request->layout != $this->request->view && $this->request->layout != 'default')
		{
			$pagetype = ($add_view ? $this->request->view . '_' : '') . $this->request->layout;

			return $this->passSimple($pagetype, $selection, $include_type);
		}

		return $this->passSimple($this->request->view, $selection, $include_type);
	}

	public function getMenuItemParams($id = 0)
	{
		$cache_id = 'getMenuItemParams_' . $id;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$query = $this->db->getQuery(true)
			->select('m.params')
			->from('#__menu AS m')
			->where('m.id = ' . (int) $id);
		$this->db->setQuery($query);
		$params = $this->db->loadResult();

		$parameters = Parameters::getInstance();

		return Cache::set(
			$cache_id,
			$parameters->getParams($params)
		);
	}

	public function getParentIds($id = 0, $table = 'menu', $parent = 'parent_id', $child = 'id')
	{
		if ( ! $id)
		{
			return [];
		}

		$cache_id = 'getParentIds_' . $id . '_' . $table . '_' . $parent . '_' . $child;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$parent_ids = [];

		while ($id)
		{
			$query = $this->db->getQuery(true)
				->select('t.' . $parent)
				->from('#__' . $table . ' as t')
				->where('t.' . $child . ' = ' . (int) $id);
			$this->db->setQuery($query);
			$id = $this->db->loadResult();

			// Break if no parent is found or parent already found before for some reason
			if ( ! $id || in_array($id, $parent_ids))
			{
				break;
			}

			$parent_ids[] = $id;
		}

		return Cache::set(
			$cache_id,
			$parent_ids
		);
	}

	public function makeArray($array = '', $delimiter = ',', $trim = false)
	{
		if (empty($array))
		{
			return [];
		}

		$cache_id = 'makeArray_' . json_encode($array) . '_' . $delimiter . '_' . $trim;

		if (Cache::has($cache_id))
		{
			return Cache::get($cache_id);
		}

		$array = $this->mixedDataToArray($array, $delimiter);

		if (empty($array))
		{
			return $array;
		}

		if ( ! $trim)
		{
			return $array;
		}

		foreach ($array as $k => $v)
		{
			if ( ! is_string($v))
			{
				continue;
			}

			$array[$k] = trim($v);
		}

		return Cache::set(
			$cache_id,
			$array
		);
	}

	private function mixedDataToArray($array = '', $onlycommas = false)
	{
		if ( ! is_array($array))
		{
			$delimiter = ($onlycommas || strpos($array, '|') === false) ? ',' : '|';

			return explode($delimiter, $array);
		}

		if (empty($array))
		{
			return $array;
		}

		if (isset($array[0]) && is_array($array[0]))
		{
			return $array[0];
		}

		if (count($array) === 1 && strpos($array[0], ',') !== false)
		{
			return explode(',', $array[0]);
		}

		return $array;
	}

	private function getItemId()
	{
		$app = JFactory::getApplication();

		if ($id = $app->input->getInt('Itemid', 0))
		{
			return $id;
		}

		$menu = $this->getActiveMenu();

		return isset($menu->id) ? $menu->id : 0;
	}

	private function getActiveMenu()
	{
		$menu = JFactory::getApplication()->getMenu()->getActive();

		if (empty($menu->id))
		{
			return false;
		}

		return $this->getMenuById($menu->id);
	}

	private function getMenuById($id = 0)
	{
		$menu = JFactory::getApplication()->getMenu()->getItem($id);

		if (empty($menu->id))
		{
			return false;
		}

		if ($menu->type == 'alias')
		{
			$params = $menu->getParams();

			return $this->getMenuById($params->get('aliasoptions'));
		}

		return $menu;
	}
}
