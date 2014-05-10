<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  render
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('F0F_INCLUDED') or die;

/**
 * Abstract view renderer class. The renderer is what turns XML view templates
 * into actual HTML code, renders the submenu links and potentially wraps the
 * HTML output in a div with a component-specific ID.
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
abstract class F0FRenderAbstract
{
	/** @var int Priority of this renderer. Higher means more important */
	protected $priority = 50;

	/** @var int Is this renderer enabled? */
	protected $enabled = false;

	/**
	 * Returns the information about this renderer
	 *
	 * @return object
	 */
	public function getInformation()
	{
		return (object) array(
				'priority'	 => $this->priority,
				'enabled'	 => $this->enabled,
		);
	}

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   F0FInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	abstract public function preRender($view, $task, $input, $config = array());

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   F0FInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	abstract public function postRender($view, $task, $input, $config = array());

	/**
	 * Renders a F0FForm and returns the corresponding HTML
	 *
	 * @param   F0FForm   &$form     The form to render
	 * @param   F0FModel  $model     The model providing our data
	 * @param   F0FInput  $input     The input object
	 * @param   string    $formType  The form type: edit, browse or read
	 * @param   boolean   $raw       If true, the raw form fields rendering (without the surrounding form tag) is returned.
	 *
	 * @return  string    The HTML rendering of the form
	 */
	public function renderForm(F0FForm &$form, F0FModel $model, F0FInput $input, $formType = null, $raw = false)
	{
		if (is_null($formType))
		{
			$formType = $form->getAttribute('type', 'edit');
		}
		else
		{
			$formType = strtolower($formType);
		}

		switch ($formType)
		{
			case 'browse':
				return $this->renderFormBrowse($form, $model, $input);
				break;

			case 'read':
				if ($raw)
				{
					return $this->renderFormRaw($form, $model, $input, 'read');
				}
				else
				{
					return $this->renderFormRead($form, $model, $input);
				}

				break;

			default:
				if ($raw)
				{
					return $this->renderFormRaw($form, $model, $input, 'edit');
				}
				else
				{
					return $this->renderFormEdit($form, $model, $input);
				}
				break;
		}
	}

	/**
	 * Renders the submenu (link bar) for a category view when it is used in a
	 * extension
	 *
	 * Note: this function has to be called from the addSubmenu function in
	 * 		 the ExtensionNameHelper class located in
	 * 		 administrator/components/com_ExtensionName/helpers/Extensionname.php
	 *
	 * Example Code:
	 *
	 *	class ExtensionNameHelper
	 *	{
	 * 		public static function addSubmenu($vName)
	 *		{
	 *			// Load F0F
	 *			include_once JPATH_LIBRARIES . '/fof/include.php';
	 *
	 *			if (!defined('F0F_INCLUDED'))
	 *			{
	 *				JError::raiseError('500', 'F0F is not installed');
	 *			}
	 *
	 *			if (version_compare(JVERSION, '3.0', 'ge'))
	 *			{
	 *				$strapper = new F0FRenderJoomla3;
	 *			}
	 *			else
	 *			{
	 *				$strapper = new F0FRenderJoomla;
	 *			}
	 *
	 *			$strapper->renderCategoryLinkbar('com_babioonevent');
	 *		}
	 *	}
	 *
	 * @param   string  $extension  The name of the extension
	 * @param   array   $config     Extra configuration variables for the toolbar
	 *
	 * @return  void
	 */
	public function renderCategoryLinkbar($extension, $config = array())
	{
		// On command line don't do anything
		if (F0FPlatform::getInstance()->isCli())
		{
			return;
		}

		// Do not render a category submenu unless we are in the the admin area
		if (!F0FPlatform::getInstance()->isBackend())
		{
			return;
		}

		$toolbar = F0FToolbar::getAnInstance($extension, $config);
		$toolbar->renderSubmenu();

		$this->renderLinkbarItems($toolbar);
	}

	/**
	 * Renders a F0FForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   F0FForm   &$form  The form to render
	 * @param   F0FModel  $model  The model providing our data
	 * @param   F0FInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormBrowse(F0FForm &$form, F0FModel $model, F0FInput $input);

	/**
	 * Renders a F0FForm for a Read view and returns the corresponding HTML
	 *
	 * @param   F0FForm   &$form  The form to render
	 * @param   F0FModel  $model  The model providing our data
	 * @param   F0FInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormRead(F0FForm &$form, F0FModel $model, F0FInput $input);

	/**
	 * Renders a F0FForm for an Edit view and returns the corresponding HTML
	 *
	 * @param   F0FForm   &$form  The form to render
	 * @param   F0FModel  $model  The model providing our data
	 * @param   F0FInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormEdit(F0FForm &$form, F0FModel $model, F0FInput $input);

	/**
	 * Renders a raw F0FForm and returns the corresponding HTML
	 *
	 * @param   F0FForm   &$form     The form to render
	 * @param   F0FModel  $model     The model providing our data
	 * @param   F0FInput  $input     The input object
	 * @param   string    $formType  The form type e.g. 'edit' or 'read'
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormRaw(F0FForm &$form, F0FModel $model, F0FInput $input, $formType);
}
