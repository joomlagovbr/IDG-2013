<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Microsoft SQL Server query building class.
 *
 * Based on Joomla! Platform 11.3
 */
class AEQuerySqlsrv extends AEAbstractQuery
{
	/**
	 * The character(s) used to quote SQL statement names such as table names or field names,
	 * etc.  The child classes should define this as necessary.  If a single character string the
	 * same character is used for both sides of the quoted name, else the first character will be
	 * used for the opening quote and the second for the closing quote.
	 *
	 * @var    string
	 */
	protected $name_quotes = '`';

	/**
	 * The null or zero representation of a timestamp for the database driver.  This should be
	 * defined in child classes to hold the appropriate value for the engine.
	 *
	 * @var    string
	 */
	protected $null_date = '1900-01-01 00:00:00';

	/**
	 * Magic function to convert the query to a string.
	 *
	 * @return  string    The completed query.
	 */
	public function __toString()
	{
		$query = '';

		switch ($this->type)
		{
			case 'insert':
				$query .= (string)$this->insert;

				// Set method
				if ($this->set)
				{
					$query .= (string)$this->set;
				}
				// Columns-Values method
				elseif ($this->values)
				{
					if ($this->columns)
					{
						$query .= (string)$this->columns;
					}

					$elements = $this->insert->getElements();
					$tableName = array_shift($elements);

					$query .= 'VALUES ';
					$query .= (string)$this->values;

					if ($this->autoIncrementField)
					{
						$query = 'SET IDENTITY_INSERT ' . $tableName . ' ON;' . $query . 'SET IDENTITY_INSERT ' . $tableName . ' OFF;';
					}

					if ($this->where)
					{
						$query .= (string)$this->where;
					}

				}

				break;

			default:
				$query = parent::__toString();
				break;
		}

		return $query;
	}

	/**
	 * Casts a value to a char.
	 *
	 * Ensure that the value is properly quoted before passing to the method.
	 *
	 * @param   string $value The value to cast as a char.
	 *
	 * @return  string  Returns the cast value.
	 */
	public function castAsChar($value)
	{
		return 'CAST(' . $value . ' as NVARCHAR(10))';
	}

	/**
	 * Gets the number of characters in a string.
	 *
	 * Note, use 'length' to find the number of bytes in a string.
	 *
	 * Usage:
	 * $query->select($query->charLength('a'));
	 *
	 * @param   string $field     A value.
	 * @param   string $operator  Comparison operator between charLength integer value and $condition
	 * @param   string $condition Integer value to compare charLength with.
	 *
	 * @return  string  The required char length call.
	 */
	public function charLength($field, $operator = null, $condition = null)
	{
		if (empty($operator) && empty($condition))
		{
			$operator = 'IS NOT';
			$condition = 'NULL';
		}

		return 'DATALENGTH(' . $field . ')' . (isset($operator) && isset($condition) ? ' ' . $operator . ' ' . $condition : '');
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
			return '(' . implode('+' . $this->quote($separator) . '+', $values) . ')';
		}
		else
		{
			return '(' . implode('+', $values) . ')';
		}
	}

	/**
	 * Gets the current date and time.
	 *
	 * @return  string
	 */
	public function currentTimestamp()
	{
		return 'GETDATE()';
	}

	/**
	 * Get the length of a string in bytes.
	 *
	 * @param   string $value The string to measure.
	 *
	 * @return  integer
	 */
	public function length($value)
	{
		return 'LEN(' . $value . ')';
	}

	/**
	 * Add to the current date and time.
	 * Usage:
	 * $query->select($query->dateAdd());
	 * Prefixing the interval with a - (negative sign) will cause subtraction to be used.
	 *
	 * @param   datetime $date     The date to add to; type may be time or datetime.
	 * @param   string   $interval The string representation of the appropriate number of units
	 * @param   string   $datePart The part of the date to perform the addition on
	 *
	 * @return  string  The string with the appropriate sql for addition of dates
	 *
	 * @note Not all drivers support all units.
	 * @link http://msdn.microsoft.com/en-us/library/ms186819.aspx for more information
	 */
	public function dateAdd($date, $interval, $datePart)
	{
		return "DATEADD('" . $datePart . "', '" . $interval . "', '" . $date . "'" . ')';
	}
}
