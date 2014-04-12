<?php

/**
 * @package   	JCE
 * @copyright 	Copyright (c) 2009-2013 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class WFSpellcheckerPluginConfig {
	public static function getConfig( &$settings ){

		$wf = WFEditor::getInstance();
                
                $engine = $wf->getParam('spellchecker.engine', 'browser', 'browser');
                
                //$url = JURI::base(true).'/index.php?option=com_jce&view=editor&layout=plugin&plugin=spellchecker&component_id=' . $settings['component_id'];
                
                switch($engine) {
                    default:
                    case 'browser':
                    case 'googlespell':
                        $languages = '';
                        
                        $settings['spellchecker_browser_state'] = $wf->getParam('spellchecker.browser_state', 0, 0);
                        
                        $engine = 'browser';
                        
                        break;
                    /*case 'googlespell':
                        $languages = $wf->getParam('spellchecker.googlespell_languages', '');
                        
                        // use a default
                        if (!$languages) {
                            $languages = $wf->getParam('spellchecker.languages', 'English=en', '' );
                        }
                        break;*/
                    case 'pspell':
                    case 'pspellshell':
                    case 'enchantspell':
                        $languages = $wf->getParam('spellchecker.languages', 'English=en', '' );
                        break;
                }
                
                //$settings['spellchecker_rpc_url'] = $url;
                
                // cast as array
                if ($languages) {
                    $languages = (array) $languages;
                }

                if (!empty($languages)) {
                    $settings['spellchecker_languages'] = '+' . implode(',', $languages);
                }

		$settings['spellchecker_engine'] = $engine;
                $settings['spellchecker_suggestions'] = $wf->getParam('spellchecker.suggestions', 1, 1);
	}
}
?>
