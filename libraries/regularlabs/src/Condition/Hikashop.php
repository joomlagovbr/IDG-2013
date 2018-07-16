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
 * Class Hikashop
 * @package RegularLabs\Library\Condition
 */
abstract class Hikashop
	extends \RegularLabs\Library\Condition
{
	public function beforePass()
	{
		$input = JFactory::getApplication()->input;

		// Reset $this->request because HikaShop messes with the view after stuff is loaded!
		$this->request->option = $input->get('option', $this->request->option);
		$this->request->view   = $input->get('view', $input->get('ctrl', $this->request->view));
		$this->request->id     = $input->getInt('id', $this->request->id);
		$this->request->Itemid = $input->getInt('Itemid', $this->request->Itemid);
	}
}
