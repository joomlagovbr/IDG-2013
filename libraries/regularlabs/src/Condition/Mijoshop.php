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

use JFactory;
use MijoShop as MijoShopClass;

/**
 * Class Mijoshop
 * @package RegularLabs\Library\Condition
 */
abstract class Mijoshop
	extends \RegularLabs\Library\Condition
{
	public function initRequest(&$request)
	{
		$input = JFactory::getApplication()->input;

		$category_id = $input->getCmd('path', 0);

		if (strpos($category_id, '_'))
		{
			$category_id = end(explode('_', $category_id));
		}

		$request->item_id     = $input->getInt('product_id', 0);
		$request->category_id = $category_id;
		$request->id          = $request->item_id ?: $request->category_id;

		$view = $input->getCmd('view', '');

		if (empty($view))
		{
			$mijoshop = JPATH_ROOT . '/components/com_mijoshop/mijoshop/mijoshop.php';

			if ( ! file_exists($mijoshop))
			{
				return;
			}

			require_once $mijoshop;

			$route = $input->getString('route', '');
			$view  = MijoShopClass::get('router')->getView($route);
		}

		$request->view = $view;
	}
}
