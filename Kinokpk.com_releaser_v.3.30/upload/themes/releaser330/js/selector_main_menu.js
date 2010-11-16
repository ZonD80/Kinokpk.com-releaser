$(function(){
	setTimeout("creatingMenu('#service_pager')", 1);
	setTimeout("creatingMenu('#payment_pager')", 1);	
}); 
	


var width;
var formNum = 0;
function creatingMenu(selector) {
	
	var selector1 = $(selector);
		selector1.addClass('hidden_it');
	
		
	var	selector1_li = $('option', selector1),
		select_header1 = $(selector).prev('h4'),
		select_header1_text = $(selector).prev('h4').html(),
		page_body = $('#page-body'),	
		top = select_header1.offset().top - 14,
		left= select_header1.offset().left - 13;
		
		width=select_header1.width();
		
		
				
	// кладем 1 div под 	
	// selector1.parent().append('<ul id="selector_menu_js"></ul>');
	
		page_body.append('<ul class="selector_menu" id="selector_menu_js'+formNum+'"></ul>');		
	// получаем объект из div
	var selector_menu_js = $("#selector_menu_js"+formNum);
		selector_menu_js.css('top', top).css('left', left);
		select_header1.addClass("selector_menu_js"+formNum);
	
	// создадим массив в котороый положим все значения
	var oLinks1 = [];
	
	
	// пробегаемся по всем option
	selector1_li.each(function(i){
		// собираю отдельно все внутрнности каждого Option
		var text = $(this).html();		
		// собираю отдельно все ссылки Option
		var href = $(this).attr('value');
		
		var dataUl = '';
		if(i>0){			
			dataUl += "<li><a href='"+href+"'>"+text+"</a></li>";	
		} else {
			dataUl += "<li class='close_form_js'>X</li><li style='width:"+width+"px';' class='open_form_js'><span>"+select_header1_text+"</span></li>";
			//dataUl += "<li style='width:"+width+"px';' class='open_form_js'><span>"+select_header1_text+"</span></li>";
		}
		selector_menu_js.append(dataUl);		
		//организовываем в массиве объектное хранение данных
		//oLinks1[i] = { text: text, href: href };	
	});
		
	
	$('.close_form_js').click(function(){
		$(this).parent().hide('fast');
	});
	
	$('.selector_menu li.open_form_js').click(function(){
		$(this).parent().hide('fast');
	});
	
	$('.selector_menu').hover(
		function(){},
		function(){
			$(this).hide('fast');
		}
	);
	
	
	
	select_header1.click(function(){
		var idForm = select_header1.attr('class');
		$("#"+idForm).show('fast');
		width=select_header1.width();
		$("#"+idForm+" li.open_form_js").css('width',width);	
	});
	
	formNum++;
}



