<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Content Component HTML Helper
 *
 * @since  1.5
 */
abstract class JHtmlIcon
{
	/**
	 * Method to generate a link to the create item page for the given category
	 *
	 * @param   object    $category  The category information
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 * @param   boolean   $legacy    True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string  The HTML markup for the create item link
	 */
	public static function create($category, $params, $attribs = array(), $legacy = false)
	{
		JHtml::_('bootstrap.tooltip');

		$uri = JUri::getInstance();

		$url = 'index.php?option=com_content&task=article.add&return=' . base64_encode($uri) . '&a_id=0&catid=' . $category->id;

		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
			}
			else
			{
				$text = '<span class="icon-plus"></span>' . JText::_('JNEW');
			}
		}
		else
		{
			$text = JText::_('JNEW') . '&#160;';
		}

		// Add the button classes to the attribs array
		if (isset($attribs['class']))
		{
			$attribs['class'] = $attribs['class'] . ' btn btn-primary';
		}
		else
		{
			$attribs['class'] = 'btn btn-primary';
		}

		$button = JHtml::_('link', JRoute::_($url), $text, $attribs);

		$output = '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_CONTENT_CREATE_ARTICLE') . '">' . $button . '</span>';

		return $output;
	}

	/**
	 * Method to generate a link to the email item page for the given article
	 *
	 * @param   object    $article  The article information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string  The HTML markup for the email item link
	 */
	public static function email($article, $params, $attribs = array(), $legacy = false)
	{
		require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';

		$uri      = JUri::getInstance();
		$base     = $uri->toString(array('scheme', 'host', 'port'));
		$template = JFactory::getApplication()->getTemplate();
		$link     = $base . JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language), false);
		$url      = 'index.php?option=com_mailto&tmpl=component&template=' . $template . '&link=' . MailToHelper::addLink($link);

		$status = 'width=400,height=350,menubar=yes,resizable=yes';

		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/emailButton.png', JText::_('JGLOBAL_EMAIL'), null, true);
			}
			else
			{
				$text = '<span class="icon-envelope"></span>' . JText::_('JGLOBAL_EMAIL');
			}
		}
		else
		{
			$text = JText::_('JGLOBAL_EMAIL');
		}

		$attribs['title']   = JText::_('JGLOBAL_EMAIL_TITLE');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';

		$output = JHtml::_('link', JRoute::_($url), $text, $attribs);

		return $output;
	}

	/**
	 * Display an edit icon for the article.
	 *
	 * This icon will not display in a popup window, nor if the article is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $article  The article information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string	The HTML for the article edit icon.
	 *
	 * @since   1.6
	 */
	public static function edit($article, $params, $attribs = array(), $legacy = false)
	{
		$user = JFactory::getUser();
		$uri  = JUri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return;
		}

		// Ignore if the state is negative (trashed).
		if ($article->state < 0)
		{
			return;
		}

		JHtml::_('bootstrap.tooltip');

		// Show checked_out icon if the article is checked out by a different user
		if (property_exists($article, 'checked_out')
			&& property_exists($article, 'checked_out_time')
			&& $article->checked_out > 0
			&& $article->checked_out != $user->get('id'))
		{
			$checkoutUser = JFactory::getUser($article->checked_out);
			$date         = JHtml::_('date', $article->checked_out_time);
			$tooltip      = JText::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . JText::sprintf('COM_CONTENT_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br /> ' . $date;

			if ($legacy)
			{
				$button = JHtml::_('image', 'system/checked_out.png', null, null, true);
				$text   = '<span class="hasTooltip" title="' . JHtml::tooltipText($tooltip . '', 0) . '">'
					. $button . '</span> ' . JText::_('JLIB_HTML_CHECKED_OUT');
			}
			else
			{
				$text = '<span class="hasTooltip icon-lock" title="' . JHtml::tooltipText($tooltip . '', 0) . '"></span> ' . JText::_('JLIB_HTML_CHECKED_OUT');
			}

			$output = JHtml::_('link', '#', $text, $attribs);

			return $output;
		}

		$contentUrl = ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language);
		$url        = $contentUrl . '&task=article.edit&a_id=' . $article->id . '&return=' . base64_encode($uri);

		if ($article->state == 0)
		{
			$overlib = JText::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = JText::_('JPUBLISHED');
		}

		$date   = JHtml::_('date', $article->created);
		$author = $article->created_by_alias ? $article->created_by_alias : $article->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= JText::sprintf('COM_CONTENT_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		if ($legacy)
		{
			$icon = $article->state ? 'edit.png' : 'edit_unpublished.png';

			if (strtotime($article->publish_up) > strtotime(JFactory::getDate())
				|| ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != JFactory::getDbo()->getNullDate()))
			{
				$icon = 'edit_unpublished.png';
			}

			$text = JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true);
		}
		else
		{
			$icon = $article->state ? 'edit' : 'eye-close';

			if (strtotime($article->publish_up) > strtotime(JFactory::getDate())
				|| ((strtotime($article->publish_down) < strtotime(JFactory::getDate())) && $article->publish_down != JFactory::getDbo()->getNullDate()))
			{
				$icon = 'eye-close';
			}

			$text = '<span class="hasTooltip icon-' . $icon . ' tip" title="' . JHtml::tooltipText(JText::_('COM_CONTENT_EDIT_ITEM'), $overlib, 0, 0)
				. '"></span>'
				. JText::_('JGLOBAL_EDIT');
		}

		$attribs['title']   = JText::_('JGLOBAL_EDIT_TITLE');
		$output = JHtml::_('link', JRoute::_($url), $text, $attribs);

		return $output;
	}

	/**
	 * Method to generate a popup link to print an article
	 *
	 * @param   object    $article  The article information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string  The HTML markup for the popup link
	 */
	public static function print_popup($article, $params, $attribs = array(), $legacy = false)
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$request = $input->request;

		$url  = ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language);
		$url .= '&tmpl=component&print=1&layout=default&page=' . @ $request->limitstart;

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		// Checks template image directory for image, if non found default are loaded
		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
			}
			else
			{
				$text = '<span class="icon-print"></span>' . JText::_('JGLOBAL_PRINT');
			}
		}
		else
		{
			$text = JText::_('JGLOBAL_PRINT');
		}

		$attribs['title']   = JText::sprintf('JGLOBAL_PRINT_TITLE', htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8'));
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';

		return JHtml::_('link', JRoute::_($url), $text, $attribs);
	}

	/**
	 * Method to generate a link to print an article
	 *
	 * @param   object    $article  Not used, @deprecated for 4.0
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Not used, @deprecated for 4.0
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string  The HTML markup for the popup link
	 */
	public static function print_screen($article, $params, $attribs = array(), $legacy = false)
	{
		// Checks template image directory for image, if none found default are loaded
		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
			}
			else
			{
				$text = '<span class="icon-print"></span>' . JText::_('JGLOBAL_PRINT');
			}
		}
		else
		{
			$text = JText::_('JGLOBAL_PRINT');
		}

		return '<a href="#" onclick="window.print();return false;">' . $text . '</a>';
	}
}
