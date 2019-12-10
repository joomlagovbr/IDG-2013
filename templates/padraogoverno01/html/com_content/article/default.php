<?php

/**
 * @package     Joomla.Site
 * @subpackage  Components.Content
 *
 * @author      JoomlaGovBR <joomlagovbr@gmail.com>
 * @copyright   Copyright (C) 2013 - 2019 JoomlaGovBR Team. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://github.com/joomlagovbr
 */

// No direct access.
defined('_JEXEC') or die('Restricted access!');

require __DIR__ . '/_helper.php';
$category_alias_layout = TemplateContentArticleHelper::getTemplateByCategoryAlias($this->item);

if ($category_alias_layout !== false) {
	$this->setLayout($category_alias_layout);
	require __DIR__ . '/' . $category_alias_layout . '.php';
} else {
	require __DIR__ . '/default_.php';
}
// uteis para debug:
// JFactory::getApplication()->getTemplate();
// $this->getLayout();
// $this->getLayoutTemplate();
