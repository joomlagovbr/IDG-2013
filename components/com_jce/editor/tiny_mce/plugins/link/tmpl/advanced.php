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

defined( '_JEXEC' ) or die('RESTRICTED');
?>
			<table border="0" cellpadding="0" cellspacing="4" class="properties" width="100%">
				<tr>
					<td><label for="id" class="hastip" title="<?php echo WFText::_('WF_LABEL_ID_DESC');?>"><?php echo WFText::_('WF_LABEL_ID');?></label></td>
					<td><input id="id" type="text" value="" /></td> 
				</tr>
				<tr>
					<td><label for="style" class="hastip" title="<?php echo WFText::_('WF_LABEL_STYLE_DESC');?>"><?php echo WFText::_('WF_LABEL_STYLE');?></label></td>
					<td><input type="text" id="style" value="" /></td>
				</tr>
                <tr>
                    <td><label for="classlist" class="hastip" title="<?php echo WFText::_('WF_LABEL_CLASS_LIST_DESC');?>"><?php echo WFText::_('WF_LABEL_CLASS_LIST');?></label></td>
                    <td colspan="3">
                        <select id="classlist" onchange="LinkDialog.setClasses(this.value);">
                            <option value=""><?php echo WFText::_('WF_OPTION_NOT_SET');?></option>
                        </select>
                   </td>
                </tr>
				<tr>
					<td><label for="classes" class="hastip" title="<?php echo WFText::_('WF_LABEL_CLASSES_DESC');?>"><?php echo WFText::_('WF_LABEL_CLASSES');?></label></td>
					<td><input type="text" id="classes" value="" /></td>
				</tr>
				<tr>
					<td><label for="dir" class="hastip" title="<?php echo WFText::_('WF_LABEL_DIR_DESC');?>"><?php echo WFText::_('WF_LABEL_DIR');?></label></td>
					<td>
                    	<select id="dir"> 
							<option value=""><?php echo WFText::_('WF_OPTION_NOT_SET');?></option>
							<option value="ltr"><?php echo WFText::_('WF_OPTION_LTR');?></option>
							<option value="rtl"><?php echo WFText::_('WF_OPTION_RTL');?></option>
						</select>
					</td> 
				</tr>
				<tr>
					<td><label for="hreflang" class="hastip" title="<?php echo WFText::_('WF_LABEL_HREFLANG_DESC');?>"><?php echo WFText::_('WF_LABEL_HREFLANG');?></label></td>
					<td><input type="text" id="hreflang" value="" /></td>
				</tr>
				<tr>
					<td><label for="lang" class="hastip" title="<?php echo WFText::_('WF_LABEL_LANG_DESC');?>"><?php echo WFText::_('WF_LABEL_LANG');?></label></td>
					<td><input id="lang" type="text" value="" /></td> 
				</tr>
				<tr>
					<td><label for="charset" class="hastip" title="<?php echo WFText::_('WF_LABEL_CHARSET_DESC');?>"><?php echo WFText::_('WF_LABEL_CHARSET');?></label></td>
					<td><input type="text" id="charset" value="" /></td>
				</tr>
				<tr>
					<td><label for="type" class="hastip" title="<?php echo WFText::_('WF_LABEL_MIME_TYPE_DESC');?>"><?php echo WFText::_('WF_LABEL_MIME_TYPE');?></label></td>
					<td><input type="text" id="type" value="" /></td>
				</tr>
				<tr>
					<td><label for="rel" class="hastip editable" title="<?php echo WFText::_('WF_LABEL_REL_DESC');?>"><?php echo WFText::_('WF_LABEL_REL');?></label></td>
					<td><select id="rel" class="mceEditableSelect">
							<option value=""><?php echo WFText::_('WF_OPTION_NOT_SET');?></option>
                            <option value="nofollow">No Follow</option>
                            <option value="alternate">Alternate</option> 
							<option value="designates">Designates</option> 
							<option value="stylesheet">Stylesheet</option> 
							<option value="start">Start</option> 
							<option value="next">Next</option> 
							<option value="prev">Prev</option> 
							<option value="contents">Contents</option> 
							<option value="index">Index</option> 
							<option value="glossary">Glossary</option> 
							<option value="copyright">Copyright</option> 
							<option value="chapter">Chapter</option> 
							<option value="subsection">Subsection</option> 
							<option value="appendix">Appendix</option> 
							<option value="help">Help</option> 
							<option value="bookmark">Bookmark</option> 
						</select> 
					</td>
				</tr>
				<tr>
					<td><label for="rev" class="hastip" title="<?php echo WFText::_('WF_LABEL_REV_DESC');?>"><?php echo WFText::_('WF_LABEL_REV');?></label></td>
					<td><select id="rev"> 
							<option value=""><?php echo WFText::_('WF_OPTION_NOT_SET');?></option>
							<option value="alternate">Alternate</option> 
							<option value="designates">Designates</option> 
							<option value="stylesheet">Stylesheet</option> 
							<option value="start">Start</option> 
							<option value="next">Next</option> 
							<option value="prev">Prev</option> 
							<option value="contents">Contents</option> 
							<option value="index">Index</option> 
							<option value="glossary">Glossary</option> 
							<option value="copyright">Copyright</option> 
							<option value="chapter">Chapter</option> 
							<option value="subsection">Subsection</option> 
							<option value="appendix">Appendix</option> 
							<option value="help">Help</option> 
							<option value="bookmark">Bookmark</option>
						</select> 
					</td>
				</tr>
				<tr>
					<td><label for="tabindex" class="hastip" title="<?php echo WFText::_('WF_LABEL_TABINDEX_DESC');?>"><?php echo WFText::_('WF_LABEL_TABINDEX');?></label></td>
					<td><input type="text" id="tabindex" value="" /></td>
				</tr>
				<tr>
					<td><label for="accesskey" class="hastip" title="<?php echo WFText::_('WF_LABEL_ACCESSKEY_DESC');?>"><?php echo WFText::_('WF_LABEL_ACCESSKEY');?></label></td>
					<td><input type="text" id="accesskey" value="" /></td>
				</tr>
			</table>