<?php
/**
 * @package         Regular Labs Library
 * @version         19.5.762
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library\Condition;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModel;

/**
 * Class Content
 * @package RegularLabs\Library\Condition
 */
abstract class Content
	extends \RegularLabs\Library\Condition
{
	use \RegularLabs\Library\ConditionContent;

	public function getItem($fields = [])
	{
		if ($this->article)
		{
			return $this->article;
		}

		if ( ! class_exists('ContentModelArticle'))
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
		}

		$model = JModel::getInstance('article', 'contentModel');

		if ( ! method_exists($model, 'getItem'))
		{
			return null;
		}

		$this->article = $model->getItem($this->request->id);

		return $this->article;
	}
}
