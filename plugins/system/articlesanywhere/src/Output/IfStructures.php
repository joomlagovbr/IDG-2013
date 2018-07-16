<?php
/**
 * @package         Articles Anywhere
 * @version         8.0.3
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2018 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\ArticlesAnywhere\Output;

use RegularLabs\Library\Condition\Php;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\StringHelper as RL_String;
use RegularLabs\Plugin\System\ArticlesAnywhere\Collection\Item;
use RegularLabs\Plugin\System\ArticlesAnywhere\Config;
use RegularLabs\Plugin\System\ArticlesAnywhere\Output\Data\Numbers;
use RegularLabs\Plugin\System\ArticlesAnywhere\Params;

defined('_JEXEC') or die;

class IfStructures extends OutputObject
{
	private $php;

	public function __construct(Config $config, Item $item, Numbers $numbers)
	{
		parent::__construct($config, $item, $numbers);

		$this->php = new Php;
	}

	public function handle(&$content)
	{
		list($tag_start, $tag_end) = Params::getTagCharacters();

		RL_RegEx::matchAll(
			RL_RegEx::quote($tag_start) . 'if[\: ].*?' . RL_RegEx::quote($tag_start) . '/if' . RL_RegEx::quote($tag_end),
			$content,
			$structures
		);

		if (empty($structures))
		{
			return;
		}

		foreach ($structures as $structure)
		{
			RL_RegEx::matchAll(
				$tag_start
				. '(?<keyword>if|else ?if|else)'
				. '(?:[\: ](?<expression>.+?))?'
				. $tag_end
				. '(?<content>.*?)'
				. '(?=' . $tag_start . '(?:else|\/if))',
				$structure[0],
				$statements
			);

			if (empty($statements))
			{
				continue;
			}

			$replace = $this->getResult($statements);

			// replace if block with the IF value
			$content = RL_String::replaceOnce($structure[0], $replace, $content);
		}
	}

	protected function getResult(&$statements)
	{
		foreach ($statements as $statement)
		{
			if ( ! $this->pass($statement))
			{
				continue;
			}

			return $statement['content'];
		}

		return '';
	}

	protected function pass($statement)
	{
		$keyword    = trim($statement['keyword']);
		$expression = trim($statement['expression']);

		if ($keyword == 'else' && $expression == '')
		{
			return true;
		}

		if ($expression == '')
		{
			return false;
		}

		$expression = RL_String::html_entity_decoder($expression);
		$expression = str_replace(
			[' AND ', ' OR '],
			[' && ', ' || '],
			$expression
		);

		$pass = false;

		$ands = explode(' && ', $expression);

		foreach ($ands as $and_part)
		{
			$ors = explode(' || ', $and_part);
			foreach ($ors as $condition)
			{
				if ($pass = $this->passCondition($condition))
				{
					break;
				}
			}

			if ( ! $pass)
			{
				break;
			}
		}

		return $pass;
	}

	protected function passCondition($condition)
	{
		$condition = trim($condition);

		/*
		* In array syntax
		* 'bar' IN foo
		* 'bar' !IN foo
		* 'bar' NOT IN foo
		*/
		if (RL_RegEx::match('^[\'"]?(?<val>.*?)[\'"]?\s+(?<operator>(?:NOT\s+)?\!?IN)\s+(?<key>[a-zA-Z0-9-_:]+)$', $condition, $match))
		{
			$reverse = ($match['operator'] == 'NOT IN' || $match['operator'] == '!NOT');

			return $this->passArray(
				$this->values->get($match['key'], null, (object) ['output' => 'raw']),
				$this->values->get($match['val'], $match['val'], (object) ['output' => 'raw']),
				$reverse
			);
		}

		/*
		* String comparison syntax:
		* foo = 'bar'
		* foo != 'bar'
		*/
		if (RL_RegEx::match('^(?<key>[a-z0-9-_]+)\s*(?<operator>\!?=)=*\s*[\'"]?(?<val>.*?)[\'"]?$', $condition, $match))
		{
			$reverse = ($match['operator'] == '!=');

			return $this->passArray(
				$this->values->get($match['key'], null, (object) ['output' => 'raw']),
				$this->values->get($match['val'], $match['val'], (object) ['output' => 'raw']),
				$reverse
			);
		}

		/*
		* Lesser/Greater than comparison syntax:
		* foo < bar
		* foo > bar
		* foo <= bar
		* foo >= bar
		*/
		if (RL_RegEx::match('^(?<key>[a-z0-9-_]+)\s*(?<operator>>=?|<=?)=*\s*[\'"]?(?<val>.*?)[\'"]?$', $condition, $match))
		{
			return $this->passCompare(
				$this->values->get($match['key'], null, (object) ['output' => 'raw']),
				$this->values->get($match['val'], $match['val'], (object) ['output' => 'raw']),
				$match['operator']
			);
		}

		/*
		* Variable check syntax:
		* foo (= not empty)
		* !foo (= empty)
		*/
		if (RL_RegEx::match('^(?<operator>\!?)(?<key>[a-z0-9-_]+)$', $condition, $match))
		{
			$reverse = ($match['operator'] == '!');

			return $this->passSimple(
				$this->values->get($match['key'], null, (object) ['output' => 'raw']),
				$reverse
			);
		}

		return $this->passPHP($condition);
	}

	protected function passSimple($haystack, $reverse = 0)
	{
		if (is_null($haystack))
		{
			return false;
		}

		$pass = ! empty($haystack);

		return $reverse ? ! $pass : $pass;
	}

	protected function passCompare($haystack, $needle, $operator)
	{
		switch ($operator)
		{
			case '<':
				return $haystack < $needle;

			case '<=':
				return $haystack <= $needle;

			case '>':
				return $haystack > $needle;

			case '>=':
				return $haystack >= $needle;
		}

		return false;
	}

	protected function passArray($haystack, $needle, $reverse = 0)
	{
		if (is_null($haystack))
		{
			return false;
		}

		if ( ! is_array($haystack))
		{
			$haystack = explode(',', str_replace(', ', ',', $haystack));
		}

		if ( ! is_array($haystack))
		{
			return false;
		}

		$pass = false;
		foreach ($haystack as $string)
		{
			if ($pass = $this->passString($string, $needle))
			{
				break;
			}
		}

		return $reverse ? ! $pass : $pass;
	}

	protected function passPHP($statement)
	{
		$php = RL_String::html_entity_decoder($statement);
		$php = RL_RegEx::replace('([^<>])=([^<>])', '\1==\2', $php);

		// replace keys with $article->key
		$php = '$article->' . RL_RegEx::replace('\s*(&&|&&|\|\|)\s*', ' \1 $article->', $php);

		// fix negative keys from $article->!key to !$article->key
		$php = str_replace('$article->!', '!$article->', $php);

		$numbers = $this->numbers->getAll();

		// replace back data variables
		foreach ($numbers as $key => $val)
		{
			$php = str_replace('$article->' . $key, (int) $val, $php);
		}

		$php = str_replace('$article->empty', (int) ($this->numbers->get('count') > 0), $php);

		// Place statement in return check
		$php = 'return ( ' . $php . ' ) ? true : false;';

		// Trim the text that needs to be checked and replace weird spaces
		$php = RL_RegEx::replace(
			'(\$article->[a-z0-9-_]*)',
			'trim(str_replace(chr(194) . chr(160), " ", \1))',
			$php
		);

		// Fix extra-1 field syntax: $article->extra-1 to $article->{'extra-1'}
		$php = RL_RegEx::replace(
			'->(extra-[a-z0-9]+)',
			'->{\'\1\'}',
			$php
		);

		return $this->php->execute($php);
	}

	protected function passString($haystack, $needle)
	{
		if ( ! is_string($haystack) && ! is_string($needle)
			&& ! is_numeric($haystack)
			&& ! is_numeric($needle)
		)
		{
			return false;
		}

		// Simple string comparison
		if (strpos($needle, '*') === false && strpos($needle, '+') === false)
		{
			return strtolower($haystack) == strtolower($needle);
		}

		// Using wildcards
		$needle = RL_RegEx::quote($needle);
		$needle = str_replace(
			['\\\\\\*', '\\*', '[:asterisk:]', '\\\\\\+', '\\+', '[:plus:]'],
			['[:asterisk:]', '.*', '\\*', '[:plus:]', '.+', '\\+'],
			$needle
		);

		return RL_RegEx::match($needle, $haystack);
	}

}
