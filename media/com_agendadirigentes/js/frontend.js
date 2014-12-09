function setCalendar(elm, dia_atual, url)
{	
	arr_dia_atual = dia_atual.split('-');
	dia = arr_dia_atual[2];
	mes = arr_dia_atual[1]-1;
	ano = arr_dia_atual[0];
	jQuery(elm).fullCalendar({
		header: {
	        left: 'prev', //, today
	        center: 'title',
	        right: 'next'
	    },			
		weekends: true,
		selectable: true,
		height:"auto",
		year: ano,
		month: mes,
		day: dia,
		dayClick: function(date){
			url = url.replace('{DATA}', jQuery(this).attr("data-date"));			
			window.location = url;
		}			
	}).fullCalendar('gotoDate', dia_atual);

	jQuery(elm).find('td').each(function(){
		if(jQuery(this).attr('data-date') == dia_atual)
			jQuery(this).addClass('active');
	});

	jQuery(elm + ' .fc-today-button').click(function(){
		 jQuery(elm).fullCalendar('today');			 
	});
}