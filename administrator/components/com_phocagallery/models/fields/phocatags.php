<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
phocagalleryimport('phocagallery.tag.tag');

class JFormFieldPhocaTags extends JFormField
{
	protected $type 		= 'PhocaTags';

	protected function getInput() {
		
		$id = (int) $this->form->getValue('id');

		$activeTags = array();
		if ((int)$id > 0) {
			$activeTags	= PhocaGalleryTag::getTags($id, 1);
		}
		return PhocaGalleryTag::getAllTagsSelectBox($this->name, $this->id, $activeTags, NULL,'id' );
	}
}
?>