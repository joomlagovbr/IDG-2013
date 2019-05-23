<?php
/**
 * @package         Articles Anywhere
 * @version         9.3.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Fields;

defined('_JEXEC') or die;

use RegularLabs\Library\DB as RL_DB;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\DB;
use RegularLabs\Plugin\System\ArticlesAnywhere\CurrentArticle;

class CustomFields extends Fields
{
	public function getAvailableFields()
	{
		$id = $this->config->getContext();

		if (isset(self::$available_fields[$id]))
		{
			return self::$available_fields[$id];
		}

		if ( ! RL_DB::tableExists($this->config->getTableFields(false)))
		{
			return [];
		}

		$query = $this->db->getQuery(true)
			->select($this->config->get('fields_id'))
			->select($this->config->get('fields_name'))
			->select($this->config->get('fields_type'))
			->from($this->config->getTableFields())
			->where($this->db->quoteName('context') . ' = ' . $this->db->quote($this->config->getContext()))
			->where($this->config->get('fields_state') . ' = 1');

		self::$available_fields[$id] = DB::getResults($query, 'loadObjectList', ['id']) ?: [];

		return self::$available_fields[$id];
	}

	public static function getByName($fields, $name)
	{
		foreach ($fields as $field)
		{
			if ($field->name == $name)
			{
				return $field;
			}
		}

		return false;
	}

	public function getFieldValue($key, $value)
	{
		$current_value = $this->getFieldValueByKey($key);

		return $this->getValue($key, $value, $current_value);
	}

	public function getFieldValueByKey($key, $item_id = 0)
	{
		$custom_fields = $this->getAvailableFields();

		if ( ! $field = self::getByName($custom_fields, $key))
		{
			return false;
		}

		return $this->getFieldValueFromDatabase($field->id, $item_id);
	}

	public function getFieldByKey($key, $item_id = 0)
	{
		$custom_fields = $this->getAvailableFields();

		if ( ! $field = self::getByName($custom_fields, $key))
		{
			return false;
		}

		return $this->getFieldFromDatabase($field->id, $item_id);
	}

	protected function getFieldValueFromDatabase($field_id, $item_id)
	{
		if ( ! RL_DB::tableExists($this->config->getTableFieldsValues(false)))
		{
			return false;
		}

		if (empty($field_id))
		{
			return false;
		}

		return $this->getFieldValuesFromDatabase($field_id, $item_id);
	}

	protected function getFieldType($field_id)
	{
		if (isset(self::$field_types[$field_id]))
		{
			return self::$field_types[$field_id];
		}

		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('type'))
			->from($this->config->getTableFields())
			->where($this->db->quoteName('id') . ' = ' . (int) $field_id);
		$this->db->setQuery($query);

		self::$field_types[$field_id] = DB::getResults($query, 'loadResult');

		return self::$field_types[$field_id] ?: '';
	}

	protected function getFieldFromDatabase($field_id, $item_id)
	{
		if ( ! RL_DB::tableExists($this->config->getTableFieldsValues(false)))
		{
			return false;
		}

		if (empty($field_id))
		{
			return false;
		}

		$field = $this->getFieldObjectFromDatabase($field_id);

		$values = $this->getFieldValuesFromDatabase($field_id, $item_id);

		if (empty($values))
		{
			$field->value = $field->default;

			return [$field];
		}

		$fields = [];

		if ( ! is_array($values))
		{
			$values = [$values];
		}

		foreach ($values as $value)
		{
			$field->value = $value;
			$fields[]     = clone $field;
		}

		return $fields;
	}

	protected function getFieldObjectFromDatabase($field_id)
	{
		if (isset(self::$fields[$field_id]))
		{
			return self::$fields[$field_id];
		}

		$query = $this->db->getQuery(true)
			->select(
				[
					$this->db->quoteName('id'),
					$this->config->get('fields_label', 'label'),
					$this->config->get('fields_type', 'type'),
					$this->config->get('fields_params', 'params'),
					$this->config->get('fields_field_params', 'fieldparams'),
					$this->config->get('fields_default', 'default'),
				])
			->from($this->config->getTableFields('fields'))
			->where($this->db->quoteName('id') . ' = ' . (int) $field_id);
		$this->db->setQuery($query);
		self::$fields[$field_id] = DB::getResults($query, 'loadObject');

		return self::$fields[$field_id];
	}

	protected function getFieldValuesFromDatabase($field_id, $item_id)
	{
		if ( ! RL_DB::tableExists($this->config->getTableFieldsValues(false)))
		{
			return false;
		}

		if (empty($field_id))
		{
			return false;
		}

		$id = $item_id . '.' . $field_id;

		if (isset(self::$field_values[$id]))
		{
			return self::$field_values[$id];
		}

		$item_id = $item_id ?: CurrentArticle::get('id', $this->config->getComponentName());

		$query = $this->db->getQuery(true)
			->select($this->config->get('fields_values_value', 'value'))
			->from($this->config->getTableFieldsValues('values'))
			->where($this->config->get('fields_values_id') . ' = ' . (int) $field_id)
			->where($this->config->get('fields_values_item_id') . ' = ' . (int) $item_id);
		$this->db->setQuery($query);

		$result = DB::getResults($query);

		self::$field_values[$id] = $this->normalizeFieldValue($result);

		return self::$field_values[$id];
	}

	protected function normalizeFieldValue($value)
	{
		if (empty($value))
		{
			return '';
		}

		if (is_array($value) && count($value) == 1)
		{
			return $value[0];
		}

		return $value;
	}
}
