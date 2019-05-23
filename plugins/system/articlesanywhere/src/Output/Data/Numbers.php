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

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data;

use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Plugin\System\ArticlesAnywhere\Output\Pagination;

defined('_JEXEC') or die;

class Numbers
{
	private $pagination = null;
	private $current    = true;

	private $total               = 1;
	private $total_no_limit      = 1;
	private $total_no_pagination = 1;

	private $count        = 1;
	private $first        = true;
	private $last         = true;
	private $even         = false;
	private $uneven       = true;
	private $next         = 1;
	private $previous     = 1;
	private $has_next     = true;
	private $has_previous = false;

	private $count_no_pagination        = 1;
	private $first_no_pagination        = true;
	private $last_no_pagination         = true;
	private $next_no_pagination         = 1;
	private $previous_no_pagination     = 1;
	private $has_next_no_pagination     = true;
	private $has_previous_no_pagination = false;

	private $limit             = 1;
	private $per_page          = 1;
	private $pages             = 1;
	private $page              = 1;
	private $next_page         = 1;
	private $previous_page     = 1;
	private $has_next_page     = false;
	private $has_previous_page = false;

	public function __construct($total_no_limit, $total_no_pagination, $total, Pagination $pagination)
	{
		$this->total_no_limit      = $total_no_limit;
		$this->total_no_pagination = $total_no_pagination;
		$this->total               = $total;
		$this->pagination          = $pagination;

	}

	public function setCount($count)
	{
		$this->count        = $count;
		$this->first        = $count == 1;
		$this->last         = $count == $this->total;
		$this->next         = $count == $this->total ? 1 : $count + 1;
		$this->previous     = $count == 1 ? $this->total : $count - 1;
		$this->has_next     = $count != $this->last;
		$this->has_previous = $count > 1;

		$this->count_no_pagination        = $count + ($this->per_page * $this->page) - $this->per_page;
		$this->first_no_pagination        = $this->count_no_pagination == 1;
		$this->last_no_pagination         = $this->count_no_pagination == $this->total_no_pagination;
		$this->next_no_pagination         = $this->count_no_pagination == $this->total_no_pagination ? 1 : $this->count_no_pagination + 1;
		$this->previous_no_pagination     = $this->count_no_pagination == 1 ? $this->total_no_pagination : $this->count_no_pagination - 1;
		$this->has_next_no_pagination     = $this->count_no_pagination != $this->last_no_pagination;
		$this->has_previous_no_pagination = $this->count_no_pagination > 1;

		$this->even   = ($count % 2) == 0;
		$this->uneven = ($count % 2) != 0;

		return $this;
	}

	public function setCurrent($is_current = true)
	{
		$this->current = $is_current;

		return $this;
	}

	public function getAll()
	{
		return [
			'current'             => $this->current,
			'total'               => $this->total,
			'total_no_limit'      => $this->total_no_limit,
			'total_no_pagination' => $this->total_no_pagination,

			'count'        => $this->count,
			'first'        => $this->first,
			'last'         => $this->last,
			'next'         => $this->count == $this->last ? $this->first : $this->count + 1,
			'previous'     => $this->count == $this->first ? $this->last : $this->count - 1,
			'has_next'     => $this->count != $this->last,
			'has_previous' => $this->count != $this->first,

			'count_no_pagination'        => $this->count_no_pagination,
			'first_no_pagination'        => $this->first_no_pagination,
			'last_no_pagination'         => $this->last_no_pagination,
			'next_no_pagination'         => $this->first_no_pagination,
			'previous_no_pagination'     => $this->last_no_pagination,
			'has_next_no_pagination'     => $this->last_no_pagination,
			'has_previous_no_pagination' => $this->first_no_pagination,

			'even'   => $this->even,
			'uneven' => $this->uneven,

			'limit'             => $this->limit,
			'per_page'          => $this->per_page,
			'pages'             => $this->pages,
			'page'              => $this->page,
			'next_page'         => $this->next_page,
			'previous_page'     => $this->previous_page,
			'has_next_page'     => $this->has_next_page,
			'has_previous_page' => $this->has_previous_page,
		];
	}

	public function exists($key)
	{
		$clean_key = $this->getCleanKey($key);

		return isset($this->{$clean_key});
	}

	public function get($key)
	{
		$clean_key = $this->getCleanKey($key);

		if ( ! isset($this->{$clean_key}))
		{
			return null;
		}

		$value = $this->{$clean_key};

		if ( ! is_numeric($value)
			|| ! RL_RegEx::match('^([a-z_]+)([\+\-\/\*])([0-9]+)$', $key, $match)
		)
		{
			return $value;
		}

		switch ($match[2])
		{
			case '+':
				return $value + $match[3];
			case '-':
				return $value - $match[3];
			case '/':
				return $value / $match[3];
			case '*':
				return $value * $match[3];
		}

		// This should never happen.
		return $value;
	}

	public function isEvery($number = 1)
	{
		return $this->count % $number == 0;
	}

	public function isColumn($number = 1, $column_count = 1)
	{
		// Make sure the number is below the total column count
		// number will be 0 when it is equal to the column count
		// ie: col_1_of_3 = 1, col_3_of_3 = 0
		$number = $number % $column_count;

		return $this->count % $column_count == $number;
	}

	public function getCleanKey($key)
	{
		$key = $this->getKey($key);

		if ( ! RL_RegEx::match('^([a-z_]+)([\+\-\/\*])([0-9]+)*', $key, $match))
		{
			return $key;
		}

		return $match[1];
	}

	public function getKey($key)
	{
		if (isset($this->{$key}))
		{
			return $key;
		}

		// Search for key aliases
		switch ($key)
		{
			case 'counter':
				return 'count';

			case 'totalcount':
				return 'total';

			case 'count_next':
				return 'next';

			case 'count_previous':
				return 'previous';

			case 'is_current':
				return 'current';

			case 'is_even':
				return 'even';

			case 'is_uneven':
				return 'uneven';

			case 'is_first':
				return 'first';

			case 'is_last':
				return 'last';

			case 'total_without_limit':
			case 'total_before_limit':
				return 'total_no_limit';

			default:
				return $key;
		}
	}
}
