<?php
/**
 * @package    AkeebaBackup
 * @copyright  Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license    GNU General Public License version 3, or later
 *
 */
defined('_JEXEC') or die();

// Load FOF if not already loaded
if (!defined('F0F_INCLUDED'))
{
	$paths = array(
		(defined('JPATH_LIBRARIES') ? JPATH_LIBRARIES : JPATH_ROOT . '/libraries') . '/f0f/include.php',
		__DIR__ . '/fof/include.php',
	);

	foreach ($paths as $filePath)
	{
		if (!defined('F0F_INCLUDED') && file_exists($filePath))
		{
			@include_once $filePath;
		}
	}
}

// Pre-load the installer script class from our own copy of FOF
if (!class_exists('F0FUtilsInstallscript', false))
{
	@include_once __DIR__ . '/fof/utils/installscript/installscript.php';
}

// Pre-load the database schema installer class from our own copy of FOF
if (!class_exists('F0FDatabaseInstaller', false))
{
	@include_once __DIR__ . '/fof/database/installer.php';
}

// Pre-load the update utility class from our own copy of FOF
if (!class_exists('F0FUtilsUpdate', false))
{
	@include_once __DIR__ . '/fof/utils/update/update.php';
}

class Com_AkeebaInstallerScript extends F0FUtilsInstallscript
{
	/**
	 * The title of the component (printed on installation and uninstallation messages)
	 *
	 * @var string
	 */
	protected $componentTitle = 'Akeeba Backup';

	/**
	 * The component's name
	 *
	 * @var   string
	 */
	protected $componentName = 'com_akeeba';

	/**
	 * The list of extra modules and plugins to install on component installation / update and remove on component
	 * uninstallation.
	 *
	 * @var   array
	 */
	protected $installation_queue = array(
		// modules => { (folder) => { (module) => { (position), (published) } }* }*
		'modules' => array(
			'admin' => array(),
			'site'  => array()
		),
		// plugins => { (folder) => { (element) => (published) }* }*
		'plugins' => array(
			'installer'	=> array(
				'akeebabackup' => 1,
			),
			'quickicon' => array(
				'akeebabackup' => 1,
			),
			'system'    => array(
				'akeebaupdatecheck' => 0,
				'backuponupdate'    => 0,
				'srp'               => 0,
			),
		)
	);

	/**
	 * Obsolete files and folders to remove from the free version only. This is used when you move a feature from the
	 * free version of your extension to its paid version. If you don't have such a distinction you can ignore this.
	 *
	 * @var   array
	 */
	protected $removeFilesFree = array(
		'files'   => array(
			'administrator/components/com_akeeba/restore.php',
			'plugins/system/akeebaupdatecheck.php',
			'plugins/system/akeebaupdatecheck.xml',
			'plugins/system/aklazy.php',
			'plugins/system/aklazy.xml',
			'plugins/system/srp.php',
			'plugins/system/srp.xml'
		),
		'folders' => array(
			'administrator/components/com_akeeba/akeeba/engines/finalization',
			'plugins/system/akeebaupdatecheck',
			'plugins/system/aklazy',
			'plugins/system/srp',
			'administrator/components/com_akeeba/plugins',
			'administrator/components/com_akeeba/akeeba/plugins',
			'administrator/modules/mod_akadmin',
		)
	);

	/**
	 * Obsolete files and folders to remove from both paid and free releases. This is used when you refactor code and
	 * some files inevitably become obsolete and need to be removed.
	 *
	 * @var   array
	 */
	protected $removeFilesAllVersions = array(
		'files'   => array(
			'cache/com_akeeba.updates.php',
			'cache/com_akeeba.updates.ini',
			'administrator/cache/com_akeeba.updates.php',
			'administrator/cache/com_akeeba.updates.ini',
			'administrator/components/com_akeeba/akeeba/core/03.filters.ini',
			'administrator/components/com_akeeba/akeeba/engines/archiver/directftp.ini',
			'administrator/components/com_akeeba/akeeba/engines/archiver/directftp.php',
			'administrator/components/com_akeeba/akeeba/engines/archiver/directsftp.ini',
			'administrator/components/com_akeeba/akeeba/engines/archiver/directsftp.php',
			'administrator/components/com_akeeba/akeeba/engines/archiver/zipnative.ini',
			'administrator/components/com_akeeba/akeeba/engines/archiver/zipnative.php',
			'administrator/components/com_akeeba/akeeba/engines/proc/email.ini',
			'administrator/components/com_akeeba/akeeba/engines/proc/email.php',
			'administrator/components/com_akeeba/views/buadmin/restorepoint.php',
			'administrator/components/com_akeeba/controllers/installer.php',
			'administrator/components/com_akeeba/controllers/srprestore.php',
			'administrator/components/com_akeeba/controllers/stw.php',
			'administrator/components/com_akeeba/controllers/upload.php',
			'administrator/components/com_akeeba/models/installer.php',
			'administrator/components/com_akeeba/models/srprestore.php',
			'administrator/components/com_akeeba/models/stw.php',
			'administrator/components/com_akeeba/controllers/acl.php',
			'administrator/components/com_akeeba/models/acl.php',
			'administrator/components/com_akeeba/tables/acl.php',
			'administrator/components/com_akeeba/akeeba/platform/joomla15/platform.php',
			'administrator/components/com_akeeba/akeeba/platform/joomlacli/platform.php',
			// Files renamed after using FOF
			'administrator/components/com_akeeba/plugins/controllers/remotefiles.php',
			'administrator/components/com_akeeba/models/cpanel.php',
			'administrator/components/com_akeeba/models/backup.php',
			'administrator/components/com_akeeba/models/config.php',
			'administrator/components/com_akeeba/models/ftpbrowser.php',
			'administrator/components/com_akeeba/models/log.php',
			'administrator/components/com_akeeba/models/fsfilter.php',
			'administrator/components/com_akeeba/models/dbef.php',
			'administrator/components/com_akeeba/plugins/models/discover.php',
			'administrator/components/com_akeeba/plugins/models/s3import.php',
			'administrator/components/com_akeeba/plugins/models/multidb.php',
			'administrator/components/com_akeeba/plugins/models/regexfsfilter.php',
			'administrator/components/com_akeeba/plugins/models/regexdbfilter.php',
			'administrator/components/com_akeeba/plugins/models/extfilter.php',
			'administrator/components/com_akeeba/plugins/models/eff.php',
			'administrator/components/com_akeeba/plugins/models/stw.php',
			'administrator/components/com_akeeba/plugins/models/restore.php',
			'administrator/components/com_akeeba/plugins/models/srprestore.php',
			'administrator/components/com_akeeba/plugins/models/profiles.php',
			'administrator/components/com_akeeba/views/profiles/tmpl/default_edit.php',
			'administrator/components/com_akeeba/views/buadmin/tmpl/default_comment.php',
			'administrator/components/com_akeeba/views/fsfilter/tmpl/default_tab.php',
			'administrator/components/com_akeeba/views/extfilter/tmpl/default_components.php',
			'administrator/components/com_akeeba/views/extfilter/tmpl/default_languages.php',
			'administrator/components/com_akeeba/views/extfilter/tmpl/default_modules.php',
			'administrator/components/com_akeeba/views/extfilter/tmpl/default_plugins.php',
			'administrator/components/com_akeeba/views/extfilter/tmpl/default_templates.php',
			'administrator/components/com_akeeba/views/dbef/tmpl/default_tab.php',
			'administrator/components/com_akeeba/plugins/views/discover/tmpl/default_discover.php',
			'administrator/components/com_akeeba/plugins/views/remotefiles/tmpl/default_dltoserver.php',
			'components/com_akeeba/models/light.php',
			'components/com_akeeba/models/json.php',
			'components/com_akeeba/views/light/view.html.php',
			'components/com_akeeba/views/light/tmpl/default_done.php',
			'components/com_akeeba/views/light/tmpl/default_error.php',
			'components/com_akeeba/views/light/tmpl/default_step.php',
			// Outdated media files
			'media/com_akeeba/js/jquery.js',
			'media/com_akeeba/js/jquery-ui.js',
			'media/com_akeeba/js/akeebajq.js',
			'media/com_akeeba/js/akeebajqui.js',
			'media/com_akeeba/theme/jquery-ui.css',
			'media/com_akeeba/theme/browser.css',
			// Box integration
			'administrator/components/com_akeeba/akeeba/plugins/engines/proc/box.ini',
			'administrator/components/com_akeeba/akeeba/plugins/engines/proc/box.php',
			'administrator/components/com_akeeba/akeeba/plugins/engines/utils/box.php',
			// Old SRP feature, no longer used
			'administrator/components/com_akeeba/plugins/controllers/installer.php',
		),
		'folders' => array(
			'administrator/components/com_akeeba/akeeba/platform/joomla15',
			'administrator/components/com_akeeba/akeeba/platform/joomlacli',
			'administrator/components/com_akeeba/views/installer',
			'administrator/components/com_akeeba/views/srprestore',
			'administrator/components/com_akeeba/views/stw',
			'administrator/components/com_akeeba/views/upload',
			'administrator/components/com_akeeba/views/acl',
			'administrator/components/com_akeeba/assets/images',
			// Folders renamed after using FOF
			'components/com_akeeba/views/backup',
			'components/com_akeeba/views/json',
			// Outdated media directories
			'media/com_akeeba/theme/images',
			// Old SRP feature, no longer used
			'administrator/components/com_akeeba/plugins/views/installer',
		)
	);

	/**
	 * A list of scripts to be copied to the "cli" directory of the site
	 *
	 * @var   array
	 */
	protected $cliScriptFiles = array(
		'akeeba-backup.php',
		'akeeba-altbackup.php',
		'akeeba-check-failed.php',
		'akeeba-altcheck-failed.php',
        'akeeba-update.php',
	);

	/**
	 * Runs after install, update or discover_update. In other words, it executes after Joomla! has finished installing
	 * or updating your component. This is the last chance you've got to perform any additional installations, clean-up,
	 * database updates and similar housekeeping functions.
	 *
	 * @param   string     $type   install, update or discover_update
	 * @param   JInstaller $parent Parent object
	 */
	function postflight($type, $parent)
	{
		$this->isPaid = is_dir($parent->getParent()->getPath('source') . '/plugins/system/srp');

		parent::postflight($type, $parent);

		// Make sure the two plugins folders exist in Core release and are empty
		if (!$this->isPaid)
		{
			if (!JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_akeeba/plugins'))
			{
				JFolder::create(JPATH_ADMINISTRATOR . '/components/com_akeeba/plugins');
			}

			if (!JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_akeeba/akeeba/plugins'))
			{
				JFolder::create(JPATH_ADMINISTRATOR . '/components/com_akeeba/akeeba/plugins');
			}
		}
	}

	/**
	 * Renders the post-installation message
	 */
	protected function renderPostInstallation($status, $fofInstallationStatus, $strapperInstallationStatus, $parent)
	{
		?>
		<img src="../media/com_akeeba/icons/logo-48.png" width="48" height="48" alt="Akeeba Backup" align="right"/>

		<h2>Welcome to Akeeba Backup!</h2>

		<div style="margin: 1em; font-size: 14pt; background-color: #fffff9; color: black">
			You can download translation files <a href="http://cdn.akeebabackup.com/language/akeebabackup/index.html">directly
				from our CDN page</a>.
		</div>

		<?php
		parent::renderPostInstallation($status, $fofInstallationStatus, $strapperInstallationStatus, $parent);
		?>

		<fieldset>
			<p>
				We strongly recommend reading the
				<a href="https://www.akeebabackup.com/documentation/quick-start-guide.html" target="_blank">Quick Start
					Guide</a>
				(short, suitable for beginners) or
				<a href="https://www.akeebabackup.com/documentation/akeeba-backup-documentation.html" target="_blank">Akeeba
					Backup User's Guide</a>
				(lengthy, technical) before proceeding with using this component. Alternatively, you can
				<a href="https://www.akeebabackup.com/documentation/video-tutorials.html" target="_blank">watch some
					video tutorials</a>
				which will get you up to speed with backing up and restoring your site.
			</p>

			<p>
				When you're done with the documentation, you can go ahead and run the
				<a href="index.php?option=com_akeeba">Post-Installation Wizard</a>
				which will help you configure Akeeba Backup's optional settings. If this
				is the first time you installed Akeeba Backup, we strongly recommend
				clicking the last checkbox, or click on the Configuration Wizard button
				in Akeeba Backup's control panel page.
			</p>

			<p>
				Should you get stuck somewhere, our
				<a href="https://www.akeebabackup.com/documentation/troubleshooter.html" target="_blank">Troubleshooting
					Wizard</a>
				is right there to help you. If you need one-to-one support, you can get
				it from our <a href="https://www.akeebabackup.com/support.html" target="_blank">support ticket
					system</a>,
				directly from Akeeba Backup's team.<br/>
				<?php if (is_dir($parent->getParent()->getPath('source') . '/plugins/system/srp')): ?>
				As a subscriber to Akeeba Backup Professional (AKEEBAPRO or AKEEBADELUXE subscription level),
				you have full access to our ticket system for the term of your subscription period. If your
				subscription expires, you will have to renew it in order to request further support.<br/>
				<small>Note: if this component was installed on your site by a third party, e.g. your
					site developer, and you and/or your company do not have an active subscription with
					AkeebaBackup.com, please contact the person who installed the component on your site for
					support.
					<?php else: ?>
						While Akeeba Backup Core is free, access to its support is not. You will need an active
						subscription to request support.
					<?php
					endif; ?>
			</p>
			<p>
				<strong>Remember, you can always get on-line help for the Akeeba Backup
					page you are currently viewing by clicking on the help icon in the top
					right corner of that page.</strong>
			</p>
		</fieldset>
	<?php
	}

	protected function renderPostUninstallation($status, $parent)
	{
		?>
		<h2>Akeeba Backup Uninstallation Status</h2>
		<?php
		parent::renderPostUninstallation($status, $parent);
	}
}