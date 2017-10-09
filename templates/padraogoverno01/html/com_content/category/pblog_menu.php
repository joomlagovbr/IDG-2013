<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

?>
<?php
// var_dump($this->children[$this->category->id]);
if ($this->menu->query['categoria_menu']) {
	//$idmenu
	$idmenu = $this->menu->query['categoria_menu'];
	// Initialiase variables.
	$db    = JFactory::getDbo();
	
	// $db    = $this->getDbo();
	$query = $db->getQuery(true);
	
	// Create the base select statement.
	$query->select('*')
	->from($db->quoteName('#__content'))
	->where($db->quoteName('state') . ' = ' . $db->quote('1'))
	->where($db->quoteName('catid') . ' = ' . $db->quote($idmenu))
	->order($db->quoteName('ordering') . ' DESC');
	
	// Set the query and load the result.
	$db->setQuery($query);
	$result = $db->loadObjectList();

	// Check for a database error.
	if ($db->getErrorNum())
	{
		JError::raiseWarning(500, $db->getErrorMsg());

		return null;
	}
	?>
	<div class="row-fluid module ">
		<ul class="menumenu-verde sublinks-noticias">
			<?php
	// apresentando os itens
			foreach ($result as $key => $value) {
				$url=json_decode($value->urls);
				$link = JRoute::_(ContentHelperRoute::getArticleRoute($value->id, $value->catid));
				?>
				<li class="item-<?php echo $value->id ?>">
					<?php if ($url->urla){ ?>
						<a href="<?php echo $url->urla; ?>" target="_blank">
					<?php } else {?>
						<a href="<?php echo $link; ?>">
					<?php } ?>
						<?php echo ($value->xreference)? $value->xreference : $value->title ; ?>
					</a>
				</li>
					<?php
				}
			}
			?>
		</ul>
	</div>