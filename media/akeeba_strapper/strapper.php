<?php
/**
 * Akeeba Strapper
 * A handy distribution of namespaced jQuery, jQuery UI and Twitter
 * Bootstrapper for use with Akeeba components.
 *
 * @copyright (c) 2012-2014 Akeeba Ltd
 * @license GNU General Public License version 2 or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

if (!defined('F0F_INCLUDED'))
{
    include_once JPATH_LIBRARIES . '/f0f/include.php';
}

if (!@include_once(JPATH_SITE . '/media/akeeba_strapper/version.php') && !defined('AKEEBASTRAPPER_VERSION'))
{
	define('AKEEBASTRAPPER_VERSION', 'dev');
	define('AKEEBASTRAPPER_DATE', gmdate('Y-m-d'));
	define('AKEEBASTRAPPER_MEDIATAG', md5(AKEEBASTRAPPER_VERSION . AKEEBASTRAPPER_DATE));
}

class AkeebaStrapper
{

    /** @var bool True when jQuery is already included */
    public static $_includedJQuery = false;

    /** @var bool True when jQuery UI is already included */
    public static $_includedJQueryUI = false;

    /** @var bool True when Bootstrap is already included */
    public static $_includedBootstrap = false;

    /** @var array List of URLs to Javascript files */
    public static $scriptURLs = array();

    /** @var array List of script definitions to include in the head */
    public static $scriptDefs = array();

    /** @var array List of URLs to CSS files */
    public static $cssURLs = array();

    /** @var array List of URLs to LESS files */
    public static $lessURLs = array();

    /** @var array List of CSS definitions to include in the head */
    public static $cssDefs = array();

    /** @var string The jQuery UI theme to use, default is 'smoothness' */
    protected static $jqUItheme = 'smoothness';

    /** @var string A query tag to append to CSS and JS files for versioning purposes */
    public static $tag = null;

	/**
	 * Gets the query tag.
	 *
	 * Uses AkeebaStrapper::$tag as the default tag for the extension's mediatag. If
	 * $overrideTag is set then that tag is used in stead.
	 *
	 * @param	string	$overrideTag	If defined this tag is used in stead of
	 * 									AkeebaStrapper::$tag
	 *
	 * @return 	string	The extension's query tag (e.g. ?23f742d04111881faa36ea8bc6d31a59)
	 * 					or an empty string if it's not set
	 */
	public static function getTag($overrideTag = null)
	{
		if ($overrideTag !== null)
		{
			$tag = $overrideTag;
		}
		else
		{
			$tag = self::$tag;
		}

		if (empty($tag))
		{
			$tag = '';
		}
		else
		{
			$tag = '?' . ltrim($tag, '?');
		}

		return $tag;
	}

    /**
     * Is this something running under the CLI mode?
     * @staticvar bool|null $isCli
     * @return null
     */
    public static function isCli()
    {
        static $isCli = null;

        if (is_null($isCli))
        {
            try
            {
                if (is_null(JFactory::$application))
                {
                    $isCli = true;
                }
                else
                {
                    $isCli = version_compare(JVERSION, '1.6.0', 'ge') ? (JFactory::getApplication() instanceof JException) : false;
                }
            }
            catch (Exception $e)
            {
                $isCli = true;
            }
        }

        return $isCli;
    }

	public static function getPreference($key, $default = null)
	{
		static $config = null;

		if(is_null($config))
		{
			// Load a configuration INI file which controls which files should be skipped
			$iniFile = F0FTemplateUtils::parsePath('media://akeeba_strapper/strapper.ini', true);

			$config = parse_ini_file($iniFile);
		}

		if (!array_key_exists($key, $config))
		{
			$config[$key] = $default;
		}

		return $config[$key];
	}

    /**
     * Loads our namespaced jQuery, accessible through akeeba.jQuery
     */
    public static function jQuery()
    {
        if (self::isCli())
		{
            return;
		}

		$jQueryLoad = self::getPreference('jquery_load', 'auto');
		if (!in_array($jQueryLoad, array('auto', 'full', 'namespace', 'none')))
		{
			$jQueryLoad = 'auto';
		}

        self::$_includedJQuery = true;

		if ($jQueryLoad == 'none')
		{
			return;
		}
		elseif ($jQueryLoad == 'auto')
		{
			if (version_compare(JVERSION, '3.0', 'gt'))
			{
				$jQueryLoad = 'namespace';
				JHtml::_('jquery.framework');
			}
			else
			{
				$jQueryLoad = 'full';
			}
		}

        if ($jQueryLoad == 'full')
        {
            self::addJSfile('media://akeeba_strapper/js/akeebajq.js', AKEEBASTRAPPER_MEDIATAG);
            self::addJSfile('media://akeeba_strapper/js/akjqmigrate.js', AKEEBASTRAPPER_MEDIATAG);
        }
        else
        {
            self::addJSfile('media://akeeba_strapper/js/namespace.js', AKEEBASTRAPPER_MEDIATAG);
        }
    }

    /**
     * Sets the jQuery UI theme to use. It must be the name of a subdirectory of
     * media/akeeba_strapper/css or templates/<yourtemplate>/media/akeeba_strapper/css
     *
     * @param $theme string The name of the subdirectory holding the theme
     */
    public static function setjQueryUItheme($theme)
    {
        if (self::isCli())
		{
            return;
		}

        self::$jqUItheme = $theme;
    }

    /**
     * Loads our namespaced jQuery UI and its stylesheet
     */
    public static function jQueryUI()
    {
        if (self::isCli())
		{
            return;
		}

        if (!self::$_includedJQuery)
        {
            self::jQuery();
        }

        self::$_includedJQueryUI = true;

		$jQueryUILoad = self::getPreference('jqueryui_load', 1);
		if (!$jQueryUILoad)
		{
			return;
		}

		$theme = self::getPreference('jquery_theme', self::$jqUItheme);

		self::addJSfile('media://akeeba_strapper/js/akeebajqui.js', AKEEBASTRAPPER_MEDIATAG);
		self::addCSSfile("media://akeeba_strapper/css/$theme/theme.min.css", AKEEBASTRAPPER_MEDIATAG);
    }

    /**
     * Loads our namespaced Twitter Bootstrap. You have to wrap the output you want style
     * with an element having the class akeeba-bootstrap added to it.
     */
    public static function bootstrap()
    {
        if (self::isCli())
		{
            return;
		}

		if (version_compare(JVERSION, '3.2', 'ge'))
		{
			$key = 'joomla32';
			$default = 'lite';
		}
		elseif (version_compare(JVERSION, '3.0', 'ge'))
		{
			$key = 'joomla3';
			$default = 'lite';
		}
		else
		{
			$key = 'joomla2';
			$default = 'full';
		}
		$loadBootstrap = self::getPreference('bootstrap_' . $key, $default);

		if ($loadBootstrap == 'front')
		{
			if (in_array($key, array('joomla3', 'joomla32')))
			{
				$isFrontend = F0FPlatform::getInstance()->isFrontend();

				$loadBootstrap = $isFrontend ? 'full' : 'lite';
			}
			else
			{
				$loadBootstrap = 'full';
			}
		}
		elseif (!in_array($loadBootstrap, array('full','lite','none')))
		{
			if ($key == 'joomla3')
			{
				$loadBootstrap = 'lite';
			}
			elseif ($key == 'joomla32')
			{
				$loadBootstrap = 'lite';
			}
			else
			{
				$loadBootstrap = 'full';
			}
		}

		if (($key == 'joomla3') && in_array($loadBootstrap, array('lite', 'none')))
		{
			// Use Joomla!'s Javascript
			JHtml::_('bootstrap.framework');
		}

        if (!self::$_includedJQuery)
        {
            self::jQuery();
        }

		if ($loadBootstrap == 'none')
		{
			return;
		}

		self::$_includedBootstrap = true;

		$source = self::getPreference('bootstrap_source', 'css');
		if (!in_array($source, array('css','less')))
		{
			$source = 'css';
		}

        $altCss = array('media://akeeba_strapper/css/strapper.min.css');

        if ($loadBootstrap == 'full')
        {
            array_unshift($altCss, 'media://akeeba_strapper/css/bootstrap.min.css');

			$filename = F0FTemplateUtils::parsePath('media://akeeba_strapper/js/bootstrap.min.js', true);
			if (@filesize($filename) > 5)
			{
				self::addJSfile('media://akeeba_strapper/js/bootstrap.min.js', AKEEBASTRAPPER_MEDIATAG);
			}

			if ($source == 'less')
			{
				self::addLESSfile('media://akeeba_strapper/less/bootstrap.j25.less', $altCss, AKEEBASTRAPPER_MEDIATAG);
			}
        }
        else
        {
			switch ($key)
			{
				case 'joomla3':
					$qualifier = '.j3';
					break;

				case 'joomla32':
					$qualifier = '.j32';
					break;

				default:
					$qualifier = '';
					break;
			}

            array_unshift($altCss, 'media://akeeba_strapper/css/bootstrap' . $qualifier . '.min.css');
			if ($source == 'less')
			{
				self::addLESSfile('media://akeeba_strapper/less/bootstrap' . $qualifier . '.less', $altCss, AKEEBASTRAPPER_MEDIATAG);
			}
        }

		if ($source == 'css')
		{
			foreach($altCss as $css)
			{
				self::addCSSfile($css, AKEEBASTRAPPER_MEDIATAG);
			}
		}
    }

    /**
     * Adds an arbitrary Javascript file.
     *
     * @param $path 		string 	The path to the file, in the format media://path/to/file
	 * @param $overrideTag	string	If defined this version tag overrides AkeebaStrapper::$tag
	 */
    public static function addJSfile($path, $overrideTag = null)
    {
		if (self::isCli())
		{
            return;
		}

		$tag = self::getTag($overrideTag);

        self::$scriptURLs[] = array(F0FTemplateUtils::parsePath($path), $tag);
    }

    /**
     * Add inline Javascript
     *
     * @param $script string Raw inline Javascript
     */
    public static function addJSdef($script)
    {
		if (self::isCli())
		{
            return;
		}

        self::$scriptDefs[] = $script;
    }

    /**
     * Adds an arbitrary CSS file.
     *
     * @param $path 		string 	The path to the file, in the format media://path/to/file
	 * @param $overrideTag	string	If defined this version tag overrides AkeebaStrapper::$tag
     */
    public static function addCSSfile($path, $overrideTag = null)
    {
		if (self::isCli())
		{
            return;
		}

		$tag = self::getTag($overrideTag);

		self::$cssURLs[] = array(F0FTemplateUtils::parsePath($path), $tag);
    }

    /**
     * Adds an arbitraty LESS file.
     *
     * @param $path string The path to the file, in the format media://path/to/file
     * @param $altPaths string|array The path to the alternate CSS files, in the format media://path/to/file
	 * @param $overrideTag	string	If defined this version tag overrides AkeebaStrapper::$tag
	 */
    public static function addLESSfile($path, $altPaths = null, $overrideTag = null)
    {
		if (self::isCli())
		{
            return;
		}

		$tag = self::getTag($overrideTag);

		self::$lessURLs[] = array($path, $altPaths, $tag);
    }

    /**
     * Add inline CSS
     *
     * @param $style string Raw inline CSS
     */
    public static function addCSSdef($style)
    {
		if (self::isCli())
		{
            return;
		}

        self::$cssDefs[] = $style;
    }

	/**
	 * Do we need to preload?
	 *
	 * @return bool True if we need to preload
	 */
	public static function needPreload()
	{
		$isJ2 = version_compare(JVERSION, '3.0', 'lt');
		$isJ3 = version_compare(JVERSION, '3.0', 'ge');

		$preloadJ2 = (bool)self::getPreference('preload_joomla2', 1);
		$preloadJ3 = (bool)self::getPreference('preload_joomla3', 0);

		// Do not allow Joomla! 3+ preloading if jQueryLoad is "auto" or "namespace" (which are both
		// namespace in Joomla! 3+). Else only the namespacing for the jQuery library will be loaded,
		// without a jQuery library being loaded on forehand, which results in jQuery error(s).
		$jQueryLoad = self::getPreference('jquery_load', 'auto');
		if(in_array($jQueryLoad, array('auto', 'namespace')))
		{
			$preloadJ3 = false;
		}

		$needPreload = $isJ2 && $preloadJ2 || $isJ3 && $preloadJ3;

		return $needPreload;
	}
}

/**
 * This is a workaround which ensures that Akeeba's namespaced JavaScript and CSS will be loaded
 * without being tampered with by any system plugin. Moreover, since we are loading first, we can
 * be pretty sure that namespacing *will* work and we won't cause any incompatibilities with third
 * party extensions loading different versions of these GUI libraries.
 *
 * This code works by registering a system plugin hook :) It will grab the HTML and drop its own
 * JS and CSS definitions in the head of the script, before anything else has the chance to run.
 *
 * Peace.
 */
function AkeebaStrapperLoader()
{
    // If there are no script defs, just go to sleep
    if (
        empty(AkeebaStrapper::$scriptURLs) &&
        empty(AkeebaStrapper::$scriptDefs) &&
        empty(AkeebaStrapper::$cssDefs) &&
        empty(AkeebaStrapper::$cssURLs) &&
        empty(AkeebaStrapper::$lessURLs)
    )
    {
        return;
    }

    $myscripts = '';

	$preloadJ2 = (bool)AkeebaStrapper::getPreference('preload_joomla2', 1);
	$preload = AkeebaStrapper::needPreload();

	if ($preload)
    {
		if (version_compare(JVERSION, '3.2', 'ge'))
		{
			$buffer = JFactory::getApplication()->getBody();
		}
		else
		{
			$buffer = JResponse::getBody();
		}
    }
	else
	{
		$preloadJ2 = false;
		$preload  = false;
	}

    // Include Javascript files
    if (!empty(AkeebaStrapper::$scriptURLs))
        foreach (AkeebaStrapper::$scriptURLs as $entry)
        {
			list($url, $tag) = $entry;

			if ($preloadJ2 && (basename($url) == 'bootstrap.min.js'))
            {
                // Special case: check that nobody else is using bootstrap[.min].js on the page.
                $scriptRegex = "/<script [^>]+(\/>|><\/script>)/i";
                $jsRegex = "/([^\"\'=]+\.(js)(\?[^\"\']*){0,1})[\"\']/i";
                preg_match_all($scriptRegex, $buffer, $matches);
                $scripts = @implode('', $matches[0]);
                preg_match_all($jsRegex, $scripts, $matches);
                $skip = false;
                foreach ($matches[1] as $scripturl)
                {
                    $scripturl = basename($scripturl);
                    if (in_array($scripturl, array('bootstrap.min.js', 'bootstrap.js')))
                    {
                        $skip = true;
                    }
                }
                if ($skip)
                    continue;
            }
            if ($preload)
            {
                $myscripts .= '<script type="text/javascript" src="' . $url . $tag . '"></script>' . "\n";
            }
            else
            {
                JFactory::getDocument()->addScript($url . $tag);
            }
        }

    // Include Javscript snippets
    if (!empty(AkeebaStrapper::$scriptDefs))
    {
		if ($preload)
        {
            $myscripts .= '<script type="text/javascript" language="javascript">' . "\n";
        }
        else
        {
            $myscripts = '';
        }
        foreach (AkeebaStrapper::$scriptDefs as $def)
        {
            $myscripts .= $def . "\n";
        }
		if ($preload)
        {
            $myscripts .= '</script>' . "\n";
        }
        else
        {
            JFactory::getDocument()->addScriptDeclaration($myscripts);
        }
    }

    // Include LESS files
    if (!empty(AkeebaStrapper::$lessURLs))
    {
        foreach (AkeebaStrapper::$lessURLs as $entry)
        {
            list($lessFile, $altFiles, $tag) = $entry;

            $url = F0FTemplateUtils::addLESS($lessFile, $altFiles, true);

			if ($preload)
            {
                if (empty($url))
                {
                    if (!is_array($altFiles) && empty($altFiles))
                    {
                        $altFiles = array($altFiles);
                    }
                    if (!empty($altFiles))
                    {
                        foreach ($altFiles as $altFile)
                        {
                            $url = F0FTemplateUtils::parsePath($altFile);
                            $myscripts .= '<link type="text/css" rel="stylesheet" href="' . $url . $tag . '" />' . "\n";
                        }
                    }
                }
                else
                {
                    $myscripts .= '<link type="text/css" rel="stylesheet" href="' . $url . $tag . '" />' . "\n";
                }
            }
            else
            {
                if (empty($url))
                {
                    if (!is_array($altFiles) && empty($altFiles))
                    {
                        $altFiles = array($altFiles);
                    }
                    if (!empty($altFiles))
                    {
                        foreach ($altFiles as $altFile)
                        {
                            $url = F0FTemplateUtils::parsePath($altFile);
                            JFactory::getDocument()->addStyleSheet($url . $tag);
                        }
                    }
                }
                else
                {
                    JFactory::getDocument()->addStyleSheet($url . $tag);
                }
            }
        }
    }

    // Include CSS files
    if (!empty(AkeebaStrapper::$cssURLs))
        foreach (AkeebaStrapper::$cssURLs as $entry)
        {
			list($url, $tag) = $entry;

			if ($preload)
            {
                $myscripts .= '<link type="text/css" rel="stylesheet" href="' . $url . $tag . '" />' . "\n";
            }
            else
            {
                JFactory::getDocument()->addStyleSheet($url . $tag);
            }
        }

    // Include style definitions
    if (!empty(AkeebaStrapper::$cssDefs))
    {
        $myscripts .= '<style type="text/css">' . "\n";
        foreach (AkeebaStrapper::$cssDefs as $def)
        {
			if ($preload)
            {
                $myscripts .= $def . "\n";
            }
            else
            {
                JFactory::getDocument()->addScriptDeclaration($def . "\n");
            }
        }
        $myscripts .= '</style>' . "\n";
    }

	if ($preload)
    {
        $pos = strpos($buffer, "<head>");
        if ($pos > 0)
        {
            $buffer = substr($buffer, 0, $pos + 6) . $myscripts . substr($buffer, $pos + 6);

			if (version_compare(JVERSION, '3.2', 'ge'))
			{
				JFactory::getApplication()->setBody($buffer);
			}
			else
			{
				JResponse::setBody($buffer);
			}
        }
    }
}

// Add our pseudo-plugin to the application event queue
if (!AkeebaStrapper::isCli())
{
	$app = JFactory::getApplication();
    if (AkeebaStrapper::needPreload())
    {
        $app->registerEvent('onAfterRender', 'AkeebaStrapperLoader');
    }
    else
    {
        $app->registerEvent('onBeforeRender', 'AkeebaStrapperLoader');
    }
}