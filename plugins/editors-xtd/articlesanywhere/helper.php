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

defined('_JEXEC') or die;

/**
 * Plugin that places the button
 */
class PlgButtonArticlesAnywhereHelper
	extends \RegularLabs\Library\EditorButtonHelper
{
	/**
	 * Display the button
	 *
	 * @param string $editor_name
	 *
	 * @return JObject|null A button object
	 */
	public function render($editor_name)
	{
		return $this->renderPopupButton($editor_name);
	}
}
