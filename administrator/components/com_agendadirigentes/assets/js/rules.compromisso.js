jQuery('document').ready(function() {
        document.formvalidator.setHandler('compromisso-title',
                function (value) {
                        regex=/^[^\<\>\=\n\t\r]+$/;
                        return regex.test(value);
        });
        document.formvalidator.setHandler('compromisso-catid',
                function (value) {
                        if(value==0)
                        	return false;

                        return true;
        });
        document.formvalidator.setHandler('compromisso-data_inicial',
                function (value) {
			      regex=/^\d{2}\/\d{2}\/\d{4}$/;
			      return regex.test(value);                	
        });
        document.formvalidator.setHandler('compromisso-data_final',
                function (value) {
                	regex=/^\d{2}\/\d{2}\/\d{4}$/;
			      	if(!regex.test(value))
			      		return false;

                 	value = value.split("/");
                 	if(value.length != 3)
                 		return false;

                 	valueNumber = Number( String(value[2]) + String(value[1]) + String(value[0]) );  
                 	
                 	valueInicial = jQuery('#jform_data_inicial').val();
                 	valueInicial = valueInicial.split("/");

                 	if (valueInicial.length != 3)
                 		return false;

                 	valueInicialNumber = Number( String(valueInicial[2]) + String(valueInicial[1]) + String(valueInicial[0]) ); 

                 	if(valueInicialNumber > valueNumber)
                 	{
                 		alert(Joomla.JText._('COM_AGENDADIRIGENTES_FORMVALIDATOR_DATAFINAL_MENORQUE_DATAINICIAL',
                                             'Data final é menor do que data inicial.'));
                 		return false;	
                 	}

                 	return true;

        }); //*/

});

function copiarValorParaDataFinal( elm )
{
	if(elm.value=="" || jQuery('#jform_data_final').val()!="")
		return;

	jQuery('#jform_data_final').val( elm.value );
}