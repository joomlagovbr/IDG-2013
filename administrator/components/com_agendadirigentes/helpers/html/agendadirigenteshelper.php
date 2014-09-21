<?php
defined('_JEXEC') or die;

JLoader::register('AgendaDirigentesHelper', JPATH_ADMINISTRATOR . '/components/com_agendadirigentes/helpers/agendadirigentes.php');

abstract class JHtmlAgendaDirigentesHelper
{
	public static function featured($value = 0, $i, $canChange = true, $context = 'compromissos')
	{
		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states	= array(
			0	=> array('unfeatured',	$context . '.featured',	'COM_AGENDADIRIGENTES_HELPER_UNFEATURED',	'COM_AGENDADIRIGENTES_HELPER_TOGGLE_TO_FEATURE'),
			1	=> array('featured',	$context . '.unfeatured',	'COM_AGENDADIRIGENTES_HELPER_FEATURED',		'COM_AGENDADIRIGENTES_HELPER_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];

		if ($canChange)
		{
			$html	= '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-'
					. $icon . '"></i></a>';
		}
		else
		{
			$html	= '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-'
					. $icon . '"></i></a>';
		}

		return $html;
	}
}