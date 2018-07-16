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

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Extension as RL_Extension;
use RegularLabs\Library\Language as RL_Language;
use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\StringHelper as RL_String;

$user = JFactory::getUser();
if ($user->get('guest')
	|| (
		! $user->authorise('core.edit', 'com_content')
		&& ! $user->authorise('core.edit.own', 'com_content')
		&& ! $user->authorise('core.create', 'com_content')
	)
)
{
	throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

$params = RL_Parameters::getInstance()->getPluginParams('articlesanywhere');

if (RL_Document::isClient('site'))
{
	if ( ! $params->enable_frontend)
	{
		throw new JAccessExceptionNotallowed(JText::_('JERROR_ALERTNOAUTHOR'), 403);
	}
}

$class = new PlgButtonArticlesAnywherePopup;
$class->render($params);

class PlgButtonArticlesAnywherePopup
{
	public function render(&$params)
	{
		$app = JFactory::getApplication();

		// load the admin language file

		RL_Language::load('plg_system_regularlabs');
		RL_Language::load('plg_editors-xtd_articlesanywhere');
		RL_Language::load('plg_system_articlesanywhere');
		RL_Language::load('com_content', JPATH_ADMINISTRATOR);

		RL_Document::style('regularlabs/popup.min.css');
		RL_Document::style('regularlabs/style.min.css');

		require_once JPATH_ADMINISTRATOR . '/components/com_content/helpers/content.php';

		$use_k2 = false;/* <<< [PRO] <<< */

		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$filter = null;

		// Get some variables from the request
		$option           = 'articlesanywhere';
		$filter_order     = $app->getUserStateFromRequest($option . '_filter_order', 'filter_order', 'ordering', 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest($option . '_filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_featured  = $app->getUserStateFromRequest($option . '_filter_featured', 'filter_featured', '', 'int');
		$filter_category  = $app->getUserStateFromRequest($option . '_filter_category', 'filter_category', 0, 'int');
		$filter_author    = $app->getUserStateFromRequest($option . '_filter_author', 'filter_author', 0, 'int');
		$filter_state     = $app->getUserStateFromRequest($option . '_filter_state', 'filter_state', '', 'word');
		$filter_search    = $app->getUserStateFromRequest($option . '_filter_search', 'filter_search', '', 'string');
		$filter_search    = RL_String::strtolower($filter_search);

		$limit      = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option . '_limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$lists = [];

		// filter_search filter
		$lists['filter_search'] = $filter_search;

		// table ordering
			if ($filter_order == 'featured')
			{
				$filter_order     = 'ordering';
				$filter_order_Dir = '';
			}

		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order']     = $filter_order;

			$options = JHtml::_('category.options', 'com_content');
			array_unshift($options, JHtml::_('select.option', '0', JText::_('JOPTION_SELECT_CATEGORY')));
			$lists['categories'] = JHtml::_('select.genericlist', $options, 'filter_category', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_category);
			//$lists['categories'] = JHtml::_( 'select.genericlist',  $categories, 'filter_category', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_category );

			// get list of Authors for dropdown filter
			$query->clear()
				->select('c.created_by, u.name')
				->from('#__content AS c')
				->join('LEFT', '#__users AS u ON u.id = c.created_by')
				->where('c.state != -1')
				->where('c.state != -2')
				->group('u.id')
				->order('u.id DESC');
			$db->setQuery($query);
			$options = $db->loadObjectList();
			array_unshift($options, JHtml::_('select.option', '0', JText::_('JOPTION_SELECT_AUTHOR'), 'created_by', 'name'));
			$lists['authors'] = JHtml::_('select.genericlist', $options, 'filter_author', 'class="inputbox" size="1" onchange="this.form.submit( );"', 'created_by', 'name', $filter_author);

			// state filter
			$lists['state'] = JHtml::_('grid.state', $filter_state, 'JPUBLISHED', 'JUNPUBLISHED', 'JARCHIVED');

			/* ITEMS */
			$where   = [];
			$where[] = 'c.state != -2';

			/*
			 * Add the filter specific information to the where clause
			 */
			// Category filter
			if ($filter_category > 0)
			{
				$where[] = 'c.catid = ' . (int) $filter_category;
			}
			// Author filter
			if ($filter_author > 0)
			{
				$where[] = 'c.created_by = ' . (int) $filter_author;
			}
			// Content state filter
			if ($filter_state)
			{
				if ($filter_state == 'P')
				{
					$where[] = 'c.state = 1';
				}
				else
				{
					if ($filter_state == 'U')
					{
						$where[] = 'c.state = 0';
					}
					else if ($filter_state == 'A')
					{
						$where[] = 'c.state = -1';
					}
					else
					{
						$where[] = 'c.state != -2';
					}
				}
			}
			// Keyword filter
			if ($filter_search)
			{
				if (stripos($filter_search, 'id:') === 0)
				{
					$where[] = 'c.id = ' . (int) substr($filter_search, 3);
				}
				else
				{
					$cols = ['id', 'title', 'alias', 'introtext', 'fulltext'];
					$w    = [];
					foreach ($cols as $col)
					{
						$w[] = 'LOWER(c.' . $col . ') LIKE ' . $db->quote('%' . $db->escape($filter_search, true) . '%', false);
					}
					$where[] = '(' . implode(' OR ', $w) . ')';
				}
			}

			// Build the where clause of the content record query
			$where = implode(' AND ', $where);

			// Get the total number of records
			$query->clear()
				->select('COUNT(*)')
				->from('#__content AS c')
				->join('LEFT', '#__categories AS cc ON cc.id = c.catid')
				->where($where);
			$db->setQuery($query);
			$total = $db->loadResult();

			// Create the pagination object
			jimport('joomla.html.pagination');
			$page = new JPagination($total, $limitstart, $limit);

			if ($filter_order == 'ordering')
			{
				$order = 'category, ordering ' . $filter_order_Dir;
			}
			else
			{
				$order = $filter_order . ' ' . $filter_order_Dir . ', category, ordering';
			}

			// Get the articles
			$query->clear()
				->select('c.*, c.state as published, g.title AS accesslevel, cc.title AS category')
				->select('u.name AS editor, f.content_id AS frontpage, v.name AS author')
				->from('#__content AS c')
				->join('LEFT', '#__categories AS cc ON cc.id = c.catid')
				->join('LEFT', '#__viewlevels AS g ON g.id = c.access')
				->join('LEFT', '#__users AS u ON u.id = c.checked_out')
				->join('LEFT', '#__users AS v ON v.id = c.created_by')
				->join('LEFT', '#__content_frontpage AS f ON f.content_id = c.id')
				->where($where)
				->order($order);
			$db->setQuery($query, $page->limitstart, $page->limit);
			$rows = $db->loadObjectList();

			// If there is a database query error, throw a HTTP 500 and exit
			if ($db->getErrorNum())
			{
				throw new Exception($db->stderr(), 500);
			}

		$this->outputHTML($params, $rows, $page, $lists, $use_k2);
	}

	function outputHTML(&$params, &$rows, &$page, &$lists)
	{
		JHtml::_('behavior.tooltip');
		JHtml::_('formbehavior.chosen', 'select');

		$plugin_tag = explode(',', $params->article_tag);
		$plugin_tag = trim($plugin_tag[0]);

		$content_type = 'core';/* <<< [PRO] <<< */

		if ( ! empty($_POST))
		{
			foreach ($params as $key => $val)
			{
				if (array_key_exists($key, $_POST))
				{
					$params->{$key} = $_POST[$key];
				}
			}
		}

		// Tag character start and end
		list($tag_start, $tag_end) = explode('.', $params->tag_characters);
		// Data tag character start and end
		list($tag_data_start, $tag_data_end) = explode('.', $params->tag_characters_data);
		?>
		<div class="header">
			<h1 class="page-title">
				<span class="icon-reglab icon-articlesanywhere"></span>
				<?php echo JText::_('INSERT_ARTICLE'); ?>
			</h1>
		</div>

		<?php if (RL_Document::isClient('administrator') && JFactory::getUser()->authorise('core.admin', 1)) : ?>
		<div class="subhead">
			<div class="container-fluid">
				<div class="btn-toolbar" id="toolbar">
					<div class="btn-wrapper" id="toolbar-options">
						<button
								onclick="window.open('index.php?option=com_plugins&filter_folder=system&filter_search=<?php echo JText::_('ARTICLES_ANYWHERE') ?>');"
								class="btn btn-small">
							<span class="icon-options"></span> <?php echo JText::_('JOPTIONS') ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

		<div style="margin-bottom: 20px"></div>

		<div class="container-fluid container-main">
			<form action="" method="post" name="adminForm" id="adminForm">
				<div class="alert alert-info">
					<?php
					$tag = $tag_start . $plugin_tag . ' ' . JText::_('JGRID_HEADING_ID') . '/' . JText::_('JGLOBAL_TITLE') . '/' . JText::_('JFIELD_ALIAS_LABEL') . $tag_end
						. $tag_data_start . JText::_('AA_DATA') . $tag_data_end
						. $tag_start . '/' . $plugin_tag . $tag_end;
					echo RL_String::html_entity_decoder(JText::sprintf('AA_CLICK_ON_ONE_OF_THE_ARTICLE_LINKS', $tag));
					?>
				</div>

				<div class="form-vertical">
					<?php include __DIR__ . '/layouts/layout.php'; ?>

					<div rel="data_layout_enable" class="toggle_div reverse" style="display:none;">

						<div class="row-fluid">
							<div class="span4">
								<?php include __DIR__ . '/layouts/title.php'; ?>
								<?php include __DIR__ . '/layouts/intro_image.php'; ?>
							</div>

							<div class="span4">
								<?php include __DIR__ . '/layouts/content.php'; ?>
							</div>

							<div class="span4">
								<?php include __DIR__ . '/layouts/readmore.php'; ?>
							</div>
						</div>
					</div>
				</div>

				<div style="clear:both;"></div>

				<?php
					$this->outputTableCore($rows, $page, $lists, $params);
				?>

				<input type="hidden" name="name"
				       value="<?php echo JFactory::getApplication()->input->getString('name', 'text'); ?>">
				<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>">
				<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>">
			</form>
		</div>

		<script type="text/javascript">
			var articlesanywhere_jInsertEditorText = null;
			(function($) {
				articlesanywhere_jInsertEditorText = function(id) {
					var t_start      = '<?php echo addslashes($tag_start); ?>';
					var t_end        = '<?php echo addslashes($tag_end); ?>';
					var td_start     = '<?php echo addslashes($tag_data_start); ?>';
					var td_end       = '<?php echo addslashes($tag_data_end); ?>';
					var content_type = '<?php echo addslashes($content_type); ?>';

					if (content_type == 'k2') {
						id = 'type="k2" item="' + id.replace(/"/g, '\\"') + '"';
					} else if (id.match(/[\"\'\|\:,]/)) {
						id = 'title="' + id.replace(/"/g, '\\"') + '"';
					}

					var str = getDataTags().trim();

					if ($('input[name="enable_div"]:checked').val() == 1) {
						var params = [];
						if ($('input[name="div_width"]').val()) {
							params[params.length] = 'width="' + $('input[name="div_width"]').val() + '"';
						}
						if ($('input[name="div_height"]').val()) {
							params[params.length] = 'height="' + $('input[name="div_height"]').val() + '"';
						}
						if ($('input[name="div_float"]:checked').val() != 0) {
							params[params.length] = 'float="' + $('input[name="div_float"]:checked').val() + '"';
						}
						if ($('input[name="div_class"]').val()) {
							params[params.length] = 'class="' + $('input[name="div_class"]').val() + '"';
						}
						str = td_start + ('div ' + params.join(' ')).trim() + td_end
							+ str
							+ td_start + '/div' + td_end;
					}

					str = t_start + '<?php echo $plugin_tag; ?> ' + id + t_end
						+ str
						+ t_start + '/<?php echo $plugin_tag; ?>' + t_end;

					window.parent.jInsertEditorText(str, '<?php echo JFactory::getApplication()->input->getString('name', 'text'); ?>');
					window.parent.SqueezeBox.close();
				}

				function getDataTags() {
					var start = '<?php echo addslashes($tag_data_start); ?>';
					var end   = '<?php echo addslashes($tag_data_end); ?>';

					if ($('input[name="data_layout_enable"]:checked').val() == 1) {
						var layout = $('input[name="data_layout_layout"]').val();

						if (!layout) {
							return '';
						}

						return start + 'article layout="' + layout + '"' + end;
					}

					var str = '';

					if ($('input[name="data_title_enable"]:checked').val() == 1) {
						var title_heading = $('select[name="data_title_heading"]').val();

						var heading_start = '';
						var heading_end   = ' ';

						if (title_heading) {
							heading_start = '</p><' + title_heading + '>';
							heading_end   = '</' + title_heading + '><p>';

						}
						str += heading_start + start + 'title' + end + heading_end;
					}

					if ($('input[name="data_intro_image_enable"]:checked').val() == 1) {
						str += start + 'image-intro' + end + ' ';
					}

					if ($('input[name="data_text_enable"]:checked').val() == 1) {
						var tag         = $('select[name="data_text_type"]').val();
						var text_length = parseInt($('input[name="data_text_length"]').val());

						if (text_length && text_length != 0) {
							tag += ' limit="' + text_length + '"';
						}

						if ($('input[name="data_text_strip"]:checked').val() == 1) {
							tag += ' strip="1"';
						}

						str += start + tag + end + ' ';
					}

					if ($('input[name="data_readmore_enable"]:checked').val() == 1) {
						var tag            = 'readmore';
						var readmore_text  = $('input[name="data_readmore_text"]').val();
						var readmore_class = $('input[name="data_readmore_class"]').val();

						if (readmore_text) {
							tag += ' text="' + readmore_text + '"';
						}

						if (readmore_class && readmore_class != 'readon') {
							tag += ' class="' + readmore_class + '"';
						}

						str += start + tag + end + ' ';
					}

					return str;
				}

				function initDivs() {
					$('div.toggle_div').each(function(i, el) {
						$('input[name="' + $(el).attr('rel') + '"]').each(function(i, el) {
							$(el).click(function() {
								toggleDivs();
							});
						});
					});
					toggleDivs();
				}

				function toggleDivs() {
					$('div.toggle_div').each(function(i, el) {
						var el    = $(el);
						var value = el.hasClass('reverse') ? 0 : 1;

						if ($('input[name="' + el.attr('rel') + '"]:checked').val() == value) {
							el.slideDown();
							return true;
						}

						el.slideUp();
					});
				}

				$(document).ready(function() {
					initDivs();
				});
			})(jQuery);
		</script>
		<?php
	}


	function outputTableCore(&$rows, &$page, &$lists, $params)
	{
		// Tag character start and end
		list($tag_start, $tag_end) = explode('.', $params->tag_characters);

		$plugin_tag = explode(',', $params->article_tag);
		$plugin_tag = trim($plugin_tag[0]);
		?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search"
				       class="element-invisible"><?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE'); ?></label>
				<input type="text" name="filter_search" id="filter_search"
				       placeholder="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"
				       value="<?php echo $lists['filter_search']; ?>"
				       title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>">
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn btn-default" type="submit" rel="tooltip"
				        title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
					<span class="icon-search"></span></button>
				<button class="btn btn-default" type="button" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"
				        onclick="document.id('filter_search').value='';this.form.submit();">
					<span class="icon-remove"></span></button>
			</div>

			<div class="btn-group pull-right hidden-phone">
				<?php echo $lists['categories']; ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<?php echo $lists['authors']; ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<?php echo $lists['state']; ?>
			</div>
		</div>

		<table class="table table-striped">
			<thead>
				<tr>
					<th width="1%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'title', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', 'JFIELD_ALIAS_LABEL', 'alias', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="10%" class="nowrap title">
						<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'category', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CREATED_BY', 'author', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', @$lists['order_Dir'], @$lists['order']); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="13">
						<?php echo $page->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$k = 0;
				foreach ($rows as $row)
				{
					if ($row->created_by_alias)
					{
						$author = $row->created_by_alias;
					}
					else
					{
						$author = $row->created_by;
					}
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td class="center">
							<?php
							echo '<button class="btn btn-default" rel="tooltip" title="<strong>' . JText::_('AA_USE_ID_IN_TAG') . '</strong><br>'
								. $tag_start . $plugin_tag . ' ' . $row->id . $tag_end . '...' . $tag_start . '/' . $plugin_tag . $tag_end
								. '" onclick="articlesanywhere_jInsertEditorText( \'' . $row->id . '\' );return false;">'
								. $row->id
								. '</button>';
							?>
						</td>
						<td class="title">
							<?php
							echo '<button class="btn btn-default" rel="tooltip" title="<strong>' . JText::_('AA_USE_TITLE_IN_TAG') . '</strong><br>'
								. $tag_start . $plugin_tag . ' ' . htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8') . $tag_end . '...' . $tag_start . '/' . $plugin_tag . $tag_end
								. '" onclick="articlesanywhere_jInsertEditorText( \'' . addslashes(htmlspecialchars($row->title, ENT_COMPAT, 'UTF-8')) . '\' );return false;">'
								. htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8')
								. '</button>';
							?>
						</td>
						<td class="title">
							<?php
							echo '<button class="btn btn-default" rel="tooltip" title="<strong>' . JText::_('AA_USE_ALIAS_IN_TAG') . '</strong><br>'
								. $tag_start . $plugin_tag . ' ' . $row->alias . $tag_end . '...' . $tag_start . '/' . $plugin_tag . $tag_end
								. '" onclick="articlesanywhere_jInsertEditorText( \'' . $row->alias . '\' );return false;">'
								. $row->alias
								. '</button>';
							?>
						</td>
						<td>
							<?php echo $row->category; ?>
						</td>
						<td class="hidden-phone">
							<?php echo $author; ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $row->published, $row->id, 'articles.', 0, 'cb', $row->publish_up, $row->publish_down); ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
			</tbody>
		</table>
		<?php
	}
}
