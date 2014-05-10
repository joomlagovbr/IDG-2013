<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Query Building Class.
 *
 * Based on Joomla! Platform 11.3
 */
class AEQueryMysqli extends AEAbstractQuery implements AEAbstractQuerylimitable
{
	/**
	 * @var    interger  The offset for the result set.
	 */
	protected $offset;

	/**
	 * @var    integer  The limit for the result set.
	 */
	protected $limit;

	/**
	 * Method to modify a query already in string format with the needed
	 * additions to make the query limited to a particular number of
	 * results, or start at a particular offset.
	 *
	 * @param   string  $query  The query in string format
	 * @param   integer $limit  The limit for the result set
	 * @param   integer $offset The offset for the result set
	 *
	 * @return string
	 */
	public function processLimit($query, $limit, $offset = 0)
	{
		if ($limit > 0 || $offset > 0)
		{
			$query .= ' LIMIT ' . $offset . ', ' . $limit;
		}

		return $query;
	}

	/**
	 * Sets the offset and limit for the result set, if the database driver supports it.
	 *
	 * Usage:
	 * $query->setLimit(100, 0); (retrieve 100 rows, starting at first record)
	 * $query->setLimit(50, 50); (retrieve 50 rows, starting at 50th record)
	 *
	 * @param   integer $limit  The limit for the result set
	 * @param   integer $offset The offset for the result set
	 *
	 * @return  AEAbstractQuery  Returns this object to allow chaining.
	 */
	public function setLimit($limit = 0, $offset = 0)
	{
		$this->limit = (int)$limit;
		$this->offset = (int)$offset;

		return $this;
	}

	/**
	 * Concatenates an array of column names or values.
	 *
	 * @param   array  $values    An array of values to concatenate.
	 * @param   string $separator As separator to place between each value.
	 *
	 * @return  string  The concatenated values.
	 */
	public function concatenate($values, $separator = null)
	{
		if ($separator)
		{
			$concat_string = 'CONCAT_WS(' . $this->quote($separator);

			foreach ($values as $value)
			{
				$concat_string .= ', ' . $value;
			}

			return $concat_string . ')';
		}
		else
		{
			return 'CONCAT(' . implode(',', $values) . ')';
		}
	}
}
