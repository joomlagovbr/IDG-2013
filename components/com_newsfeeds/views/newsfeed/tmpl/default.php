<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_newsfeeds
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!empty($this->msg))
{
	echo $this->msg;
}
else
{
	$lang      = JFactory::getLanguage();
	$myrtl     = $this->newsfeed->rtl;
	$direction = ' ';

	$isRtl = $lang->isRtl();

		if ($isRtl && $myrtl == 0)
		{
			$direction = ' redirect-rtl';
		}
		elseif ($isRtl && $myrtl == 1)
		{
			$direction = ' redirect-ltr';
		}
		elseif ($isRtl && $myrtl == 2)
		{
			$direction = ' redirect-rtl';
		}
		elseif ($myrtl == 0)
		{
			$direction = ' redirect-ltr';
		}
		elseif ($myrtl == 1)
		{
			$direction = ' redirect-ltr';
		}
		elseif ($myrtl == 2)
		{
			$direction = ' redirect-rtl';
		}
		$images = json_decode($this->item->images);
	?>
	<div class="newsfeed<?php echo $this->pageclass_sfx; ?><?php echo $direction; ?>">
	<?php if ($this->params->get('display_num')) : ?>
	<h1 class="<?php echo $direction; ?>">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>
	<h2 class="<?php echo $direction; ?>">
		<?php if ($this->item->published == 0) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>
		<a href="<?php echo $this->item->link; ?>" target="_blank">
		<?php echo str_replace('&apos;', "'", $this->item->name); ?></a>
	</h2>

	<?php if ($this->params->get('show_tags', 1)) : ?>
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>

	<!-- Show Images from Component -->
	<?php if (isset($images->image_first) and !empty($images->image_first)) : ?>
	<?php $imgfloat = empty($images->float_first) ? $this->params->get('float_first') : $images->float_first; ?>
	<div class="img-intro-<?php echo htmlspecialchars($imgfloat, ENT_COMPAT, 'UTF-8'); ?>"> <img
		<?php if ($images->image_first_caption) :
			echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_first_caption, ENT_COMPAT, 'UTF-8') . '"';
		endif; ?>
		src="<?php echo htmlspecialchars($images->image_first, ENT_COMPAT, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($images->image_first_alt, ENT_COMPAT, 'UTF-8'); ?>"/> </div>
	<?php endif; ?>

	<?php if (isset($images->image_second) and !empty($images->image_second)) : ?>
	<?php $imgfloat = empty($images->float_second) ? $this->params->get('float_second') : $images->float_second; ?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat, ENT_COMPAT, 'UTF-8'); ?> item-image"> <img
	<?php if ($images->image_second_caption) :
		echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_second_caption) . '"';
	endif; ?>
	src="<?php echo htmlspecialchars($images->image_second, ENT_COMPAT, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($images->image_second_alt, ENT_COMPAT, 'UTF-8'); ?>"/> </div>
	<?php endif; ?>
	<!-- Show Description from Component -->
	<?php echo $this->item->description; ?>
	<!-- Show Feed's Description -->

	<?php if ($this->params->get('show_feed_description')) : ?>
		<div class="feed-description">
			<?php echo str_replace('&apos;', "'", $this->rssDoc->description); ?>
		</div>
	<?php endif; ?>

	<!-- Show Image -->
	<?php if (isset($this->rssDoc->image, $this->rssDoc->imagetitle) && $this->params->get('show_feed_image')) : ?>
	<div>
			<img src="<?php echo $this->rssDoc->image; ?>" alt="<?php echo $this->rssDoc->image->decription; ?>" />
</div>
<?php endif; ?>

	<!-- Show items -->
	<?php if (!empty($this->rssDoc[0])) { ?>
	<ol>
	<?php for ($i = 0; $i < $this->item->numarticles; $i++)
	{
		if (empty($this->rssDoc[$i]))
		{
			break;
		}
		?>
		<?php
			$uri   = !empty($this->rssDoc[$i]->guid) || $this->rssDoc[$i]->guid !== null ? trim($this->rssDoc[$i]->guid) : trim($this->rssDoc[$i]->uri);
			$uri   = strpos($uri, 'http') !== 0 ? $this->item->link : $uri;
			$text  = !empty($this->rssDoc[$i]->content) || $this->rssDoc[$i]->content !== null ? trim($this->rssDoc[$i]->content) : trim($this->rssDoc[$i]->description);
			$title = trim($this->rssDoc[$i]->title);
		?>
			<li>
				<?php if (!empty($uri)) : ?>
					<h3 class="feed-link">
					<a href="<?php echo htmlspecialchars($uri); ?>" target="_blank">
					<?php echo $title; ?></a></h3>
				<?php else : ?>
					<h3 class="feed-link"><?php echo $title; ?></h3>
				<?php endif; ?>

				<?php if ($this->params->get('show_item_description') && !empty($text)) : ?>
					<div class="feed-item-description">
					<?php if ($this->params->get('show_feed_image', 0) == 0)
					{
						$text = JFilterOutput::stripImages($text);
					}
					$text = JHtml::_('string.truncate', $text, $this->params->get('feed_character_count'));
						echo str_replace('&apos;', "'", $text);
					?>
					</div>
				<?php endif; ?>
				</li>
			<?php } ?>
			</ol>
		<?php } ?>
	</div>
<?php } ?>
