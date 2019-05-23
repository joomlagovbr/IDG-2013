<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

require __DIR__.'/_helper.php';
$category_alias_layout = TemplateContentArticleHelper::getTemplateByCategoryAlias( $this->item );

if( $category_alias_layout !== false )
{
	$this->setLayout( $category_alias_layout );
	require __DIR__.'/'. $category_alias_layout .'.php';
}
else
{
	require __DIR__.'/default_.php';
}
// uteis para debug:
// JFactory::getApplication()->getTemplate();
// $this->getLayout();
// $this->getLayoutTemplate();