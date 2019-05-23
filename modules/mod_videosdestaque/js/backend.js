function completarValores( obj, fillValues )
{
	value = obj.val();

	medias = new Array();

	if(value=='' && obj.parent().parent().find('*[name^="name-"]').val() != ''  && obj.parent().parent().find('*[name^="description-"]').val() != '')
	{
		if(window.confirm('Apagar dados?'))
		{
			apagarExibicaoEdados( obj );
		}
	}
	
	//youtube
	medias[0] = new Array();
	medias[0]['regex'] = new RegExp('youtube\.com\/');
	medias[0]['function'] = function() {
		apiKey = jQuery('#jform_params_google_api_key').val();
		if(apiKey=='')
		{
			if(jQuery('.youtube_msg_apikey').size()==0)
				obj.parent().append('<br><div class="youtube_msg_apikey">Informe uma chave de API do Google<br>para carregar os dados do vídeo<br>automaticamente.</div>');
			return;
		}
		jQuery('.youtube_msg_apikey').remove();

		posVideoId = value.indexOf('?v=');
		videoId = value.substr(posVideoId + String('?v=').length);
		if(videoId.indexOf('&')!=-1)
		{
			posEndVideoId = videoId.indexOf('&');
			videoId = videoId.substr(0, posEndVideoId);
		}

		value = 'https://www.googleapis.com/youtube/v3/videos?id='+videoId+'&key='+apiKey+'&part=snippet';
		jQuery.getJSON(value,function(data,status,xhr){

			apagarExibicaoEdados( obj );
			
			if(data.items.length==0)
			{
				alert('Vídeo não encontrado.');
				return;
			}

			title = data.items[0].snippet.title;
			description = data.items[0].snippet.description;
	    	obj.parent().parent().find('*[name^="original-"]').val(description);
    		obj.parent().parent().find('*[name^="name-"]').val(title);
    		obj.parent().parent().find('*[name^="description-"]').val(description);


    		if(obj.parent().find('iframe').size()==0)
    		{
	    		
	    		html = '<br><iframe width="178" height="100" frameborder="0" allowfullscreen="" src="//www.youtube.com/embed/'+videoId+'?showinfo=0"></iframe>';
	    		obj.parent().append(html);

	    		qtdCaracteres = document.getElementById('jform_params_limitar_caracteres').value;
	    		elementName = 'elm'+String( Math.floor((Math.random() * 100) + 1) );

	    		html = '<p>Opções da descrição:</p>';
	    		html += '<label><input type="radio" name="'+elementName+'" onClick="limitarDescricao(this, \'ponto\')" />&nbsp; Limitar à primeira frase.</label>';
	    		html += '<label><input type="radio" name="'+elementName+'" onClick="limitarDescricao(this, \'caracteres\')" />';
	    		html += '&nbsp; Limitar a <input type="text" class="textLimit" value="'+qtdCaracteres+'" style="width:30px !important"> caracteres.</label>';
	    		html += '<label><input type="radio" name="'+elementName+'" onClick="limitarDescricao(this, \'original\')" />&nbsp; Original.</label>';
	    		obj.parent().parent().find('*[name^="name-"]').parent().append(html);
    		}    		
		});	
	};

	for (var i = medias.length - 1; i >= 0; i--) {
		regex = medias[i]['regex'];		
		if(regex.test(value))
		{
			medias[i]['function']();
		}
	};
}

function limitarDescricao( elm, criterio )
{
	desc = jQuery(elm).parent().parent().parent().find('*[name^="original-"]').val();

	if(criterio=='ponto')
	{
		desc = desc.substr(0, desc.indexOf('.')+1);
		jQuery(elm).parent().parent().parent().find('*[name^="description-"]').val(desc);
	}
	else if(criterio=='caracteres')
	{
		qtd = Number( jQuery(elm).parent().find('input[type="text"]').val() );
		desc = desc.substr(0, qtd+1);
		jQuery(elm).parent().parent().parent().find('*[name^="description-"]').val(desc);
	}
	else if(criterio=='original')
	{
		desc = jQuery(elm).parent().parent().parent().find('*[name^="original-"]').val();
		jQuery(elm).parent().parent().parent().find('*[name^="description-"]').val( desc );
	}
	return;
}

function apagarExibicaoEdados( elm )
{
	elm.parent().parent().find('*[name^="url-"]').next().next().remove();
	elm.parent().parent().find('*[name^="url-"]').next().remove();
	elm.parent().parent().find('*[name^="name-"]').val('');
	elm.parent().parent().find('*[name^="description-"]').val('');
	elm.parent().parent().find('*[name^="original-"]').val('');
	elm.parent().parent().find('*[name^="name-"]').next().next().next().next().remove();
	elm.parent().parent().find('*[name^="name-"]').next().next().next().remove();
	elm.parent().parent().find('*[name^="name-"]').next().next().remove();
	elm.parent().parent().find('*[name^="name-"]').next().remove();
}