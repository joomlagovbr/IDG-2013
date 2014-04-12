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
class WFFormatPluginConfig {

    protected static $fonts = array('Andale Mono=andale mono,times', 'Arial=arial,helvetica,sans-serif', 'Arial Black=arial black,avant garde', 'Book Antiqua=book antiqua,palatino', 'Comic Sans MS=comic sans ms,sans-serif', 'Courier New=courier new,courier', 'Georgia=georgia,palatino', 'Helvetica=helvetica', 'Impact=impact,chicago', 'Symbol=symbol', 'Tahoma=tahoma,arial,helvetica,sans-serif', 'Terminal=terminal,monaco', 'Times New Roman=times new roman,times', 'Trebuchet MS=trebuchet ms,geneva', 'Verdana=verdana,geneva', 'Webdings=webdings', 'Wingdings=wingdings,zapf dingbats');
    protected static $formats = array(
        'p' => 'advanced.paragraph',
        'address' => 'advanced.address',
        'pre' => 'advanced.pre',
        'h1' => 'advanced.h1',
        'h2' => 'advanced.h2',
        'h3' => 'advanced.h3',
        'h4' => 'advanced.h4',
        'h5' => 'advanced.h5',
        'h6' => 'advanced.h6',
        'div' => 'advanced.div',
        'blockquote' => 'advanced.blockquote',
        'code' => 'advanced.code',
        'samp' => 'advanced.samp',
        'span' => 'advanced.span',
        'section' => 'advanced.section',
        'article' => 'advanced.article',
        'aside' => 'advanced.aside',
        'figure' => 'advanced.figure',
        'dt' => 'advanced.dt',
        'dd' => 'advanced.dd',
        'div_container' => 'advanced.div_container'
    );

    public static function getConfig(&$settings) {
        wfimport('admin.models.editor');
        $model = new WFModelEditor();
        $wf = WFEditor::getInstance();

        // Add format plugin to plugins list
        if (!in_array('format', $settings['plugins'])) {
            $settings['plugins'][] = 'format';
        }

        $settings['inline_styles'] = $wf->getParam('editor.inline_styles', 1, 1);

        // Paragraph handling
        $settings['forced_root_block'] = $wf->getParam('editor.forced_root_block', 'p');

        // set as boolean if disabled
        if (is_numeric($settings['forced_root_block'])) {
            $settings['forced_root_block'] = (bool) $settings['forced_root_block'];

            if ($wf->getParam('editor.force_br_newlines', 0, 0, 'boolean') === false) {
                // legacy
                $settings['force_p_newlines'] = $wf->getParam('editor.force_p_newlines', 1, 0, 'boolean');
            }
        }

        if (strpos($settings['forced_root_block'], '|') !== false) {
            // multiple values
            $values = explode('|', $settings['forced_root_block']);

            foreach ($values as $value) {
                $kv = explode(':', $value);

                if (count($kv) == 2) {
                    $settings[$kv[0]] = (bool) $kv[1];
                } else {
                    $settings['forced_root_block'] = (bool) $kv[0];
                }
            }
        }

        $settings['removeformat_selector'] = $wf->getParam('editor.removeformat_selector', 'span,b,strong,em,i,font,u,strike', 'span,b,strong,em,i,font,u,strike');

        // html5 block elements
        $html5 = array('section', 'article', 'aside', 'figure');
        // get current schema
        $schema = $wf->getParam('editor.schema', 'html4');
        $verify = (bool) $wf->getParam('editor.verify_html', 0);

        // get blockformats from parameter
        $blockformats = $wf->getParam('editor.theme_advanced_blockformats', 'p,div,address,pre,h1,h2,h3,h4,h5,h6,code,samp,span,section,article,aside,figure,dt,dd', 'p,address,pre,h1,h2,h3,h4,h5,h6');

        $list = array();
        $blocks = array();

        // make an array
        if (is_string($blockformats)) {
            $blockformats = explode(',', $blockformats);
        }

        // create label / value list using default
        foreach ($blockformats as $v) {
            
            if (array_key_exists($v, self::$formats)) {
                $key = self::$formats[$v];
            }
            
            // skip html5 blocks for html4 schema
            if ($verify && $schema == 'html4' && in_array($v, $html5)) {
                continue;
            }

            if ($key) {
                $list[$key] = $v;
            }

            $blocks[] = $v;

            if ($v == 'div') {
                $list['advanced.div_container'] = 'div_container';
            }
        }

        // Format list / Remove Format
        $settings['theme_advanced_blockformats'] = json_encode($list);

        // Relative urls
        $settings['relative_urls'] = $wf->getParam('editor.relative_urls', 1, 1, 'boolean');
        if ($settings['relative_urls'] == 0) {
            $settings['remove_script_host'] = false;
        }

        $fonts = $wf->getParam('editor.fonts');

        if (!empty($fonts)) {
            $list = array();

            foreach (json_decode($fonts, true) as $k => $v) {
                $list[] = $k . '=' . $v;
            }

            $fonts = implode(';', $list);
        } else {
            $fonts = self::getFonts();
        }
        
        /*        
        $selector = empty($settings['removeformat_selector']) ? 'span,b,strong,em,i,font,u,strike' : $settings['removeformat_selector'];
        $selector = explode(',', $selector);

        // set the root block
        $rootblock = (!$settings['forced_root_block']) ? 'p' : $settings['forced_root_block'];

        if ($k = array_search($rootblock, $blocks) !== false) {
            unset($blocks[$k]);
        }

        // remove format selector
        $selector = array_unique(array_merge($blocks, $selector));
        */
        
        // Fonts
        $settings['theme_advanced_fonts'] = $fonts;
        $settings['theme_advanced_font_sizes'] = $wf->getParam('editor.theme_advanced_font_sizes', '8pt,10pt,12pt,14pt,18pt,24pt,36pt');

        // Styles list (legacy)
        $styles = $wf->getParam('editor.theme_advanced_styles', '');

        if ($styles) {
            $settings['theme_advanced_styles'] = implode(';', explode(',', $styles));
        }

        $custom_styles = json_decode($wf->getParam('editor.custom_styles', ''));

        if (!empty($custom_styles)) {
            $styles = array();

            $blocks = array('section', 'nav', 'article', 'aside', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'footer', 'address', 'main', 'p', 'pre', 'blockquote', 'figure', 'figcaption', 'div');

            foreach ((array) $custom_styles as $style) {
                if (isset($style->styles)) {
                    $style->styles = self::cleanJSON($style->styles);
                }

                if (isset($style->element)) {
                    if (in_array($style->element, $blocks)) {
                        $style->block = $style->element;
                    } else {
                        $style->inline = $style->element;
                    }
                    // remove
                    $style->remove = "all";
                    
                    $selector[] = $style->element;

                    unset($style->element);
                }

                $styles[] = $style;
            }

            if (!empty($styles)) {
                $settings['style_formats'] = htmlentities(json_encode($styles), ENT_NOQUOTES, "UTF-8");
            }
        }
        // remove format selector
        //$settings['removeformat_selector'] = implode(',', array_unique($selector));
    }

    protected static function cleanJSON($string, $delim = ";") {
        $ret = array();

        foreach (explode($delim, $string) as $item) {
            $item = trim($item);

            // split style at colon
            $parts = explode(":", $item);

            if (count($parts) < 2) {
                continue;
            }

            // cleanup string
            $parts = preg_replace('#^["\']#', '', $parts);
            $parts = preg_replace('#["\']$#', '', $parts);

            $ret[trim($parts[0])] = trim($parts[1]);
        }

        return $ret;
    }

    /**
     * Get a list of editor font families
     *
     * @return string font family list
     * @param string $add Font family to add
     * @param string $remove Font family to remove
     */
    protected static function getFonts() {
        $wf = WFEditor::getInstance();

        $add = $wf->getParam('editor.theme_advanced_fonts_add');
        $remove = $wf->getParam('editor.theme_advanced_fonts_remove');

        // Default font list
        $fonts = self::$fonts;

        if (empty($remove) && empty($add)) {
            return "";
        }

        $remove = preg_split('/[;,]+/', $remove);

        if (count($remove)) {
            foreach ($fonts as $key => $value) {
                foreach ($remove as $gone) {
                    if ($gone && preg_match('/^' . $gone . '=/i', $value)) {
                        // Remove family
                        unset($fonts[$key]);
                    }
                }
            }
        }
        foreach (explode(";", $add) as $new) {
            // Add new font family
            if (preg_match('/([^\=]+)(\=)([^\=]+)/', trim($new)) && !in_array($new, $fonts)) {
                $fonts[] = $new;
            }
        }

        natcasesort($fonts);
        return implode(';', $fonts);
    }

}

?>
