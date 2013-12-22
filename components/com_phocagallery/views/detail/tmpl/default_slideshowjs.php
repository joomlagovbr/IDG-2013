<?php
defined('_JEXEC') or die('Restricted access');
$document			= &JFactory::getDocument();
$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/jquery/jquery-1.6.4.min.js');
$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/fadeslideshow/fadeslideshow.js');

?><script type="text/javascript">
/***********************************************
* Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
var phocagallery=new fadeSlideShow({
	wrapperid: "phocaGallerySlideshowC",
	dimensions: [<?php echo $this->tmpl['largewidth']; ?>, <?php echo $this->tmpl['largeheight']; ?>],
	imagearray: [<?php echo $this->item->slideshowfiles ;?>],
	displaymode: {type:'auto', pause: <?php echo $this->tmpl['slideshow_pause'] ?>, cycles:0, wraparound:false, randomize: <?php echo $this->tmpl['slideshowrandom'] ?>},
	persist: false,
	fadeduration: <?php echo $this->tmpl['slideshow_delay'] ?>,
	descreveal: "<?php echo $this->tmpl['slideshow_description'] ?>",
	togglerid: "",
})
</script>
