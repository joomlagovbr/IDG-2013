<?php 
echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));

$date_filters = $this->filterForm->getGroup('dates');
	if ($date_filters) : ?>
    <div class="js-stools-container-filters hidden-phone clearfix js-stools-container-bar">
		<div class="btn-wrapper input-append">
	    	<label for="dates_data_inicial">
				<?php echo JText::_('Mostrar compromissos entre: '); ?>
			</label>
		</div>
		<div class="btn-wrapper input-append js-stools-field-filter">
			<?php 
            $data_inicial  = explode('/', $date_filters['dates_data_inicial']->value );
            if(count($data_inicial )==3)
            {
            	$date_filters['dates_data_inicial']->class .= " active";
                $data_inicial  = intval($data_inicial [2]).'-'.intval($data_inicial [1]).'-'.intval($data_inicial [0]);
            }
            else
                $data_inicial  = '';

            $date_filters['dates_data_inicial']->setValue($data_inicial);			
			echo $date_filters['dates_data_inicial']->input; ?>
		</div>
		<div class="btn-wrapper input-append js-stools-field-filter">
			<?php 
            $data_final  = explode('/', $date_filters['dates_data_final']->value );
            if(count($data_final )==3)
            {
            	$date_filters['dates_data_final']->class .= " active";
                $data_final  = intval($data_final [2]).'-'.intval($data_final [1]).'-'.intval($data_final [0]);
            }
            else
                $data_final  = '';

            $date_filters['dates_data_final']->setValue($data_final);
            echo $date_filters['dates_data_final']->input; ?>
		</div>
		<div class="btn-wrapper input-append">
			<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
				<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>
			</button>
		</div>		
    </div>
    <?php if(!empty($data_inicial) || !empty($data_final)): ?>
    <script type="text/javascript">
    //hacking original clear button
	jQuery('.js-stools-btn-clear').addClass('js-stools-btn-clear2');
    jQuery('.js-stools-btn-clear2').removeClass('js-stools-btn-clear');
    jQuery(document).ready(function(){
		jQuery('.js-stools-btn-filter').click();
		jQuery('.js-stools-btn-clear2').click(function(){
			jQuery('.js-stools-container-filters').find('select').each(function(){
				jQuery(this).val('');
				jQuery('#'+jQuery(this).attr('id')+'_chzn').removeClass('active');
				jQuery(this).trigger('liszt:updated');
			});
			jQuery('.js-stools-container-filters').find('input').each(function(){
				jQuery(this).val('');
				jQuery(this).removeClass('active');
			});
			jQuery('#adminForm').submit();						
		})
    });
    </script>
	<?php endif; ?>
<?php
endif;