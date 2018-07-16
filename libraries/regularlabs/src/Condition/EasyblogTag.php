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

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

/**
 * Class EasyblogTag
 * @package RegularLabs\Library\Condition
 */
class EasyblogTag
	extends Easyblog
{
	public function pass()
	{
		if ($this->request->option != 'com_easyblog')
		{
			return $this->_(false);
		}

		$pass = (
			($this->params->inc_tags && $this->request->layout == 'tag')
			|| ($this->params->inc_items && $this->request->view == 'entry')
		);

		if ( ! $pass)
		{
			return $this->_(false);
		}

		if ($this->params->inc_tags && $this->request->layout == 'tag')
		{
			$query = $this->db->getQuery(true)
				->select('t.alias')
				->from('#__easyblog_tag AS t')
				->where('t.id = ' . (int) $this->request->id)
				->where('t.published = 1');
			$this->db->setQuery($query);
			$tags = $this->db->loadColumn();

			return $this->passSimple($tags, true);
		}

		$query = $this->db->getQuery(true)
			->select('t.alias')
			->from('#__easyblog_post_tag AS x')
			->join('LEFT', '#__easyblog_tag AS t ON t.id = x.tag_id')
			->where('x.post_id = ' . (int) $this->request->id)
			->where('t.published = 1');
		$this->db->setQuery($query);
		$tags = $this->db->loadColumn();

		return $this->passSimple($tags, true);
	}
}
