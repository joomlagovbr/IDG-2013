<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_agendadirigentes
 */

defined('_JEXEC') or die;

/**
 * Contact Component Category Tree
 *
 * @package     Joomla.Site
 * @subpackage  com_agendadirigentes
 */
class AgendadirigentesCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__agendadirigentes_cargos';
		$options['extension'] = 'com_agendadirigentes';
		$options['statefield'] = 'published';
		parent::__construct($options);
	}
}
