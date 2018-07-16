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

namespace RegularLabs\Plugin\System\ArticlesAnywhere;

defined('_JEXEC') or die;

use RegularLabs\Library\Protect as RL_Protect;

class Clean
{
	/**
	 * Just in case you can't figure the method name out: this cleans the left-over junk
	 */
	public static function cleanLeftoverJunk(&$string)
	{
		RL_Protect::removeAreaTags($string, 'ARTA');

		$params = Params::get();

		Protect::unprotectTags($string);

		RL_Protect::removeFromHtmlTagContent($string, Params::getTags(true));
		RL_Protect::removeInlineComments($string, 'ArticlesAnywhere');

		if ( ! $params->place_comments)
		{
			RL_Protect::removeCommentTags($string, 'ArticlesAnywhere');
		}
	}
}
