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

/**
 * Class Virtuemart
 * @package RegularLabs\Library\Condition
 */
abstract class Virtuemart
	extends \RegularLabs\Library\Condition
{
	public function initRequest(&$request)
	{
		$virtuemart_product_id  = JFactory::getApplication()->input->get('virtuemart_product_id', [], 'array');
		$virtuemart_category_id = JFactory::getApplication()->input->get('virtuemart_category_id', [], 'array');

		$request->item_id     = isset($virtuemart_product_id[0]) ? $virtuemart_product_id[0] : null;
		$request->category_id = isset($virtuemart_category_id[0]) ? $virtuemart_category_id[0] : null;
		$request->id          = $request->item_id ?: $request->category_id;
	}
}
