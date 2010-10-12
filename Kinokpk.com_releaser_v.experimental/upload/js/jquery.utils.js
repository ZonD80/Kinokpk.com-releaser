$.extend({
	// cookieExpireTime в часах
	setCookie: function(cookieName,cookieContent,cookieExpireTime){
		if(cookieExpireTime>0){
			var expDate=new Date();
			expDate.setTime(expDate.getTime()+cookieExpireTime*1000*60*60);
			var expires=expDate.toGMTString();
			document.cookie=cookieName+"="+escape(cookieContent)+"; path="+escape('/')+"; expires="+expires;
		}else{
			document.cookie=cookieName+"="+escape(cookieContent)+"; path="+escape('/')+"";
		}
	},
	getCookie: function(cookieName){
		var ourCookie=document.cookie;
		if(!ourCookie || ourCookie=="")return "";
			ourCookie=ourCookie.split(";");
		var i=0;
		var Cookie;
		while(i<ourCookie.length){
			Cookie=ourCookie[i].split("=")[0];
			if(Cookie.charAt(0)==" ")
				Cookie=Cookie.substring(1);
			if(Cookie==cookieName){
				return unescape(ourCookie[i].split("=")[1]);
			}
			i++;
		}
		return ""
	},
	evIE: function(command){
		if(!$.browser.msie){return command;}
		switch(command){
			case 'slideToggle':
				return 'toggle';
			case 'slideUp':
				return 'hide';
			case 'slideDown':
				return 'show';
		}
	},
	evIE6: function(){
		return ($.browser.msie && parseFloat($.browser.version)<=6);
	},
	evScrollTop: function(){
		var $body=$('body');
		return Math.max($body[0].scrollTop,$body.parent()[0].scrollTop);
	},
	evScrollLeft: function(){
		var $body=$('body');
		return Math.max($body[0].scrollLeft,$body.parent()[0].scrollLeft);
	},
	evScreenWidth: function(){
		// var $body=$('body');
		// return Math.max($body[0].clientWidth,$body.parent()[0].clientWidth);
		return $('html')[0].clientWidth;
	},
	evScreenHeight: function(){
		// var $body=$('body');
		// return Math.max($body[0].clientHeight,$body.parent()[0].clientHeight);
		return $('html')[0].clientHeight;
	},
	evScrollWidth: function(){
		var $body=$('body');
		return Math.max($body[0].scrollWidth,$body.parent()[0].scrollWidth);
	},
	evScrollHeight: function(){
		var $body=$('body');
		return Math.max($body[0].scrollHeight,$body.parent()[0].scrollHeight);
	},
	// расположен ли элемент в видимой части экрана
	evElementInScreen: function(html_elem){
		//определяем координаты элемента на странице
		var elem_coords=$.evElementCoords(html_elem);
		//определяем координаты видимой области экрана
		var screen_coords=$.evScreenCoords(html_elem);
		//определяем находится ли элемент в видимой области
		var bool=false;
		var getCross=function(x11,w1,x21,w2){
			var min=Math.min(x11,x21);
			var max=Math.max((x11+w1),(x21+w2));
			var real_sum=max-min;
			var ideal_sum=w1+w2;
			return (real_sum < ideal_sum);
		}
		if(getCross(elem_coords.left,elem_coords.width,screen_coords.left,screen_coords.width)){
			if(getCross(elem_coords.top,elem_coords.height,screen_coords.top,screen_coords.height)){
				bool=true;
			}
		}
		return bool;
	},
	evElementCoords: function(html_elem){
		if(html_elem.offsetTop || html_elem.offsetParent){
			var left=html_elem.offsetLeft, top=html_elem.offsetTop;
			var offset_parent=html_elem.offsetParent;
			while(true){
				if(!offset_parent || offset_parent.tagName=='BODY'){break;}
				left+=offset_parent.offsetLeft;
				top+=offset_parent.offsetTop;
				offset_parent=offset_parent.offsetParent;
			}
			return {
				left: left,
				top: top,
				width: html_elem.offsetWidth,
				height: html_elem.offsetHeight
			}
		}
	},
	evScreenCoords: function(){
		var width, height;
		if(window.innerWidth){
			width=window.innerWidth;
			height=window.innerHeight;
		}else if(document.documentElement){
			width=document.documentElement.clientWidth;
			height=document.documentElement.clientHeight;
		}else if(document.body){
			width=document.body.clientWidth;
			height=document.body.clientHeight;
		}else{
			alert('error in $.evScreenCoords()');
		}
		return {
			left: $.evScrollLeft(),
			top: $.evScrollTop(),
			width: width,
			height: height
		}
	},
	evValidateEmail: function(input_or_string){
		//проверяем передана ли строка
		if(typeof input_or_string=='string'){
			var email=input_or_string;
		}else{
			//проверяем передан ли тег
			if(typeof input_or_string.value=='string'){
				var email=input_or_string.value;
			}else{
				//проверяем передан ли объект jQuery
				if(typeof input_or_string[0].value=='string'){
					var email=input_or_string[0].value;
				}else{
					return false;
				}
			}
		}
		return (email.search(/^[^@\s]+@[^@\s]+\.[^@\s]{2,}$/)==0);
	},
	evPopup: function(url,w,h,hash){
		var hash=(hash || {});
		var name=(hash.name || '_blank');
		if(!(window.screen 
			&& window.screen.height 
			&& window.screen.height > (h+50) 
			&& window.screen.width 
			&& window.screen.width > (w+50)
		)){
			w=(window.screen.width>(w+50))?(w+25):(window.screen.width-50);
			h=(window.screen.height>(h+50))?(h+25):(window.screen.height-50);
			hash.s=1;
		}
		var left=($.getCookie('lastPopupLeft') || 75);
		left=parseInt(left)+ 25;
		if(window.screen && window.screen.width && left+parseInt(w)>window.screen.width)left=50;
		$.setCookie('lastPopupLeft',left);
		var top=($.getCookie('lastPopupTop') || 75);
		top=parseInt(top)+ 25;
		if(window.screen && window.screen.height && top+parseInt(w)>window.screen.height)top=50;
		$.setCookie('lastPopupTop',top);
		var params='';
		params+='left='+ parseInt(left)+ ',';
		params+='top='+ parseInt(top)+ ',';
		params+='width='+ parseInt(w)+ ',';
		params+='height='+ parseInt(h)+ ',';
		params+='scrollbars='+ (hash.s || hash.scroll || hash.scrollbars || '0')+ ',';
		params+='resizable='+ (hash.r || hash.resize || hash.resizable || '0')+ ',';
		params+='menubar='+ (hash.m || hash.menu || hash.menubar || '0')+ ',';
		//params+='titlebar='+ (hash.titlebar || '0')+ ',';
		params+='toolbar='+ (hash.toolbar || '0')+ ',';
		params+='location='+ (hash.location || '0')+ ',';
		//params+='directories='+ (hash.directories || '0')+ ',';
		//params+='hotkeys='+ (hash.hotkeys || '0')+ ',';
		params+='status='+ (hash.status || '0')+ ',';
		//params+='dependent='+ (hash.dependent || '0')+ ',';
		//if(hash.fullscreen){params+='fullscreen='+ hash.fullscreen+ ',';}
		//params+='channelmode='+ (hash.channelmode || '0');
		//alert(params);
		var win=window.open(url,name,params);
		try{
			win.focus();
			return win;
		}catch(e){}
	},
	evTry: function(function2run,testCondition){
		var testCondition=unescape(testCondition);
		var function2run=unescape(function2run);
		if(eval(testCondition)){
			try{
				eval(function2run);
			}catch(e){/*поскольку событие было отложено, возможно что к моменту реализации функция уже перестанет существовать*/}
		}else{
			var date=new Date();
			var mls=parseInt(arguments[2] || 100);
			setTimeout('$.evTry("'+ escape(function2run)+ '","'+ escape(testCondition)+ '",'+ mls+ ')',mls);
		}
	},
	// нажатие ctrl Enter
	evCtrlEnter: function(event){
		if(event.keyCode==13 && event.ctrlKey){
			return true;
		}
		return false;
	},
	evNewin: function(link){
		if(link && link.href){
			var newin=window.open(link.href);
			return false;
		}
	},
	evPostfix: function(num,one,two,five){
		var rest=num%10;
		if(rest==1 && num%100!=11){
			return one;
		}else if(rest>=2 && rest<=4 && num%100!=12 && num%100!=13 && num%100!=14){
			return two;
		}else{
			return five;
		}
	},
	evDecryptText: function(text){
		var result='';
		var arr=text.split(',');
		var log='';
		for(var i=0; i<arr.length; i++){
			var sym=arr[i];
			log+='i='+i+', sym='+sym+'';
			if(parseInt(sym)>0){
				result+=String.fromCharCode(parseInt(sym));
			}else{
				result+=sym;
			}
		}
		return result;
	},
	evDecryptTextWrite: function(text){
		document.write($.evDecryptText(text));
	},
	evCSSrule: function(selector, style){
		if( !document.styleSheets.length ){
			$(document.createElement('style')).attr('text/css').appendTo('head');
		}
		var css_rule_text=selector + '{'+ style+ '}';
		try{
			var x=document.styleSheets[document.styleSheets.length-1];
			if($.browser.msie){
				x.addRule(selector, style);
			}else{
				x.insertRule(css_rule_text,x.cssRules.length);
			}
		}catch(e){
			// alert('can\'t insert css rule:\n' + css_rule_text);
		}
	}
})

$.fn.extend({
	evDragDrop: function(hash){
		this.each(function(){
			//придумываем идентификатор объекту, чтобы отличать его от других
			var $target=$(this);
			var random_id='random_id_'+Math.random().toString().substr(2);
			if(!C.__evDragDrop__)C.__evDragDrop__=[];
			C.__evDragDrop__[random_id]={};
			// определяем функцию для вызоыва hash.callback
			var callback_trigger_function=function(hash,evt,evt_name,lt){
				if(hash.callback){
					hash.callback({
						event: evt,
						$target: $target,
						type: evt_name,
						left: lt[0],
						top: lt[1]
					})
				}
			}
			//реагируем на нажатие
			$target.mousedown(function(evt){
				evt.preventDefault();
				C.__evDragDrop__[random_id].is_drag=true;
				C.__evDragDrop__[random_id].delta_xy=[
					evt.clientX - $target[0].offsetLeft,
					evt.clientY - $target[0].offsetTop
				];
				// дергаем hash.callback
				callback_trigger_function(hash,evt,'start',[$target[0].offsetLeft,$target[0].offsetTop]);
			});
			//реагируем на drag
			$('body').mousemove(function(evt){
				evt.preventDefault();
				if(C.__evDragDrop__[random_id].is_drag){
					var lt=[
						evt.clientX - C.__evDragDrop__[random_id].delta_xy[0],
						evt.clientY - C.__evDragDrop__[random_id].delta_xy[1],
					];
					//преобразуем новые координаты так, чтобы объект не вылезал за рамки
					if(hash.left){
						if(lt[0]<hash.left[0])lt[0]=hash.left[0];
						if(lt[0]>hash.left[1])lt[0]=hash.left[1];
					}
					if(hash.top){
						if(lt[1]<hash.top[0])lt[1]=hash.top[0];
						if(lt[1]>hash.top[1])lt[1]=hash.top[1];
					}
					//позициорируем
					$target.css({'left':lt[0],'top':lt[1]});
					// дергаем hash.callback
					callback_trigger_function(hash,evt,'drag',lt);
				}
			});
			//реагируем на drop
			$('body').mouseup(function(evt){//привязываем событие к document, потому что нам не важны координаты
				evt.preventDefault();
				if(C.__evDragDrop__[random_id].is_drag){
					//если происходит перетаскивание, то заканчиваем его
					C.__evDragDrop__[random_id].is_drag=false;
					C.__evDragDrop__[random_id].delta_xy=null;
					callback_trigger_function(hash,evt,'drop',[$target[0].offsetLeft,$target[0].offsetTop]);
				}
			});
		})
		return this;
	},
	evTop50: function(){
		this.each(function(){
			var top=$.evScrollTop()+ ($.evScreenHeight() -this.offsetHeight)/2;
			$(this).css({top:top});
		})
		return this;
	},
	// в зависимости от фокуса показывает или скрывает текст value
	evSwitchField: function(value){
		this.each(function(){
			var $field=$(this);
			if($field.attr('type')!='password'){
				value=(value || $field.attr('value'));
				$field.evSwitchInputField(value);
			}else{
				value=(value || 'Пароль');
				$field.evSwitchPasswordField(value);
			}
		})
		return this;
	},
	evSwitchInputField: function(value){
		this.each(function(){
			$(this).bind('focus blur',function(event){
				if(event.type=='focus' && $.trim(event.target.value)==value){
					event.target.value='';
					$(event.target).removeClass('helpText')
				}else if(event.type=='blur' && $.trim(event.target.value)==''){
					$(event.target).addClass('helpText')
					event.target.value=value;
				}
			}).trigger('blur');
		})
		return this;
	},
	// в зависимости от фокуса переключает поле типа password в тип text 
	// и показывает в нем текст (value) 
	evSwitchPasswordField: function(value){
		this.each(function(){
			var $field=$(this);
			$field.bind('focus blur',function(event){
				var field_name=this.name;
				if(event.type=='focus' && event.target.value==value){
					var $input_pswd=$(document.createElement('input')).attr({
						type:'password',
						name:field_name,
						maxlength:16
					}).insertAfter(this);
					$input_pswd[0].focus();
					$input_pswd.evSwitchPasswordField(value,true);
					$(this).remove();
				}else if(event.type=='blur' && event.target.value==''){
					var $input_txt=$(document.createElement('input')).attr({
						name:field_name,
						maxlength:16,
						value:value,
						className:'helpText'
					}).insertAfter(this);
					$(this).remove();
					$input_txt.evSwitchPasswordField(value,true);
				}
			});
			if(!arguments[1] && $field.attr('type')=='password'){
				$field.trigger('blur');
			}
		})
		return this;
	},
	// функция вешается на blur, добавляет "http://" и убирает конечный слэш
	evCorrectURL: function(){
		this.each(function(){
			var url=$.trim($(this).attr('value'));
			//с адресом все ОК если:
			if(false 
				|| !url //или еще ничего не набрали
				|| url.search(/:\/\//)>0 //или http:// уже набрали
				|| url.search(/\./)==-1 //или пока не набрали ни одной точки
				|| url.search(/mailto:/)>=0 //или это mailto
			){
				//ничего не делаем
			}else{
				// добавляем mailto: если набрали валидный e-mail
				if($.evValidateEmail(url)){
					url='mailto:'+ url;
				}else{
					//добавляем "http://" если:
					if(true
						&& url.search(/:\/\//)==-1 // "http://" еще не набрали
						&& url.search(/\.(ru|su|net|com|org|biz|info|tv)/)>0 //в адресе присутствует какой-то из основных доменов 1 уровня
					){
						url='http://'+ url;
					}
				}
			}
			//убираем конечный слэш, если:
			if(true
				&& url.search(/\/$/)>0 //конечный слэш имеется
				&& url.search(/:\/\//)>0 //в адресе имеется http://
				&& url.match(/\//g).length==3 //нет других слэшей кроме последнего и двух рядом в http://
			){
				url=url.substr(0,url.length-1);
			}
			$(this).attr('value',url);
		})
		return this;
	},
	evSboxSelect: function(value){
		this.each(function(){
			var sbox=this;
			if(sbox.tagName=='SELECT'){
				var ops=sbox.options;
				for(var i=0; i<ops.length; i++){
					if(ops[i].value==value){
						ops.selectedIndex=i;
						break;
					}
				}
			}
		})
		return this;
	},
	evSboxValue: function(){
		var sbox=this[0];
		var index=sbox.options.selectedIndex;
		var current=sbox.options[index].value;
		return current;
	},
	evDisableTextSelect: function(){
		this.each(function(){
			var $target=$(this);
			if($.browser.mozilla){//Firefox
				$target.css('MozUserSelect','none');
			}else if($.browser.msie){//IE
				$target.bind('selectstart',function(){return false;});
			}else{//Opera, etc.
				$target.mousedown(function(){return false;});
			}
		})
		return this
	},
	evElementCoords: function(relative_elem){
		var coords=$.evElementCoords(this[0]);
		if(relative_elem){
			if( typeof relative_elem == 'string' ){
				relative_elem = $(relative_elem)[0];
			}else if( typeof body.jquery == 'string' ){
				relative_elem = relative_elem[0];
			}
			var relative_coords=$.evElementCoords(relative_elem);
			coords.left-=relative_coords.left;
			coords.top-=relative_coords.top;
		}
		return coords
	},
	evSboxDecorate: function(hash){
		/*hash={style, skipFirst, submitForm, delay}*/
		this.each(function(j){
			hash=(hash || {})
			hash.style=(hash.style || false);
			hash.skipFirst=(hash.skipFirst || false);
			hash.submitForm=(hash.submitForm || false);
			hash.delay=(hash.delay || false);
			hash.scroll=(hash.scroll || false);
			hash.destroy=(hash.destroy || false);
			var $select=$(this);
			if($select.is('select')){
				if(hash.destroy){
					var $wrap=$select.parent();
					$select.insertAfter($wrap).show();
					$wrap.remove();
					return;
				}
				$select.hide();
				// var form_action=($select.parents('form').eq(0).attr('action') || '');
				var sbox_name=($select.attr('name') || '');
				if($select.parent().is('span.sboxDecorated')){
					var $wrap=$select.parent();
					// если происходит повторное декорирование 
					// пытаемся удалить b.first, a.darr, span.options
					$wrap.children('b.first, a.darr, span.options').remove();
				}else{
					var $wrap=$select.wrap($(document.createElement('span')).addClass('sboxDecorated')).parent();
					if(hash.style){
						$wrap.css({position:'relative', display:'block'});
					}
				}
				var $opts_scroll=$(document.createElement('span')).addClass('scroll');
				var selected=$select[0].selectedIndex;
				var opts_length=$select.children().length;
				var click_listener_function=function(value){
					return function(evt){
						evt.preventDefault();
						var $current_opt=$opts_scroll.children('a[rel='+ $select.evSboxValue() +']').removeClass('active btmActive');
						var current_opt_text=$current_opt.children('b').text();
						$current_opt.empty().text(current_opt_text);
						var $new_opt=$opts_scroll.children('a[rel='+ value +']');
						var new_opt_text=$new_opt.text();
						$new_opt.addClass( $new_opt.is('a.btm')?'active btmActive':'active' ).empty().append($(document.createElement('b')).text(new_opt_text));
						$first.text(new_opt_text);
						$select.evSboxSelect(value).trigger('change');
						if(hash.submitForm){
							$select.parents('form').eq(0).submit();
						}
					}
				}
				var $first;
				$select.children().each(function(i){
					var option_text=$(this).text();
					var option_value=$(this).val();
					if(i==0){
						$first=$(document.createElement('b')).addClass('first').text(option_text).prependTo($wrap);
					}
					if(i>0 || !hash.skipFirst){
						var $item=$(document.createElement('a')).attr({href:'#', rel:option_value}).text(option_text);
						if( !hash.submitForm ){
							// $item.attr({href: form_action+'?'+sbox_name+'='+option_value });
						}
						$item.bind('click', click_listener_function(option_value));
						if(i==selected){
							$item.empty().append($(document.createElement('b')).text(option_text));
							$item.addClass( $item.is('a.btm')?'active btmActive':'active' );
							$first.text(option_text);
						}
						if(i+1==opts_length){
							$item.addClass('btm');
						}
						$opts_scroll.append($item);
					}
				});
				window['evSboxDecorate_delay_function'+j]=function(){
					var wrap_wh=[$wrap[0].offsetWidth,$wrap[0].offsetHeight];
					var $darr=$(document.createElement('a')).attr({href:'#'}).addClass('darr').appendTo($wrap);
					// добавляем $opts_scroll в последнюю очередь, ведь если это сделать раньше, то размеры wrap_wh определятся неверно
					$opts_scroll.appendTo($(document.createElement('span')).addClass('ofh').appendTo($(document.createElement('span')).addClass('options').appendTo($wrap)));
					if(hash.style){
						$.evCSSrule('span.sboxDecorated a.darr','height: '+wrap_wh[1]+'px;width: '+wrap_wh[0]+'px;');
						$.evCSSrule('span.sboxDecorated span.options','top: '+wrap_wh[1]+'px;');
					}
					// устанавливаем обработчик, который разворачивает опции
					$darr.bind('click',function(evt){
						evt.preventDefault();
						evt.stopPropagation();
						$(this).blur();
						var $current_sbox=$(this).parent();
						var $current_opts=$current_sbox.children('span.options');
						// определяем, нужно свернуть или развернуть сбокс
						if($current_sbox.children('span.options')[0].offsetHeight>0){
							// нужно свернуть и сбросить высоту у ofh
							window.evSboxDecorate_hide_function($current_sbox);
						}else{
							// нужно развернуть
							// если есть другие развернутые селектбоксы — сворачиваем их
							if($('span.sboxDecoratedActive').length){
								window.evSboxDecorate_hide_function($('span.sboxDecoratedActive'));
							}
							// определяем, сколько места осталось на сайте, чтобы развернуть наши опции
							var darr_coords=$.evElementCoords(this);
							if(hash.scroll){
								var space_height=$.evScreenHeight()+$.evScrollTop() - (darr_coords.top+darr_coords.height);
								// назначаем минимальную высоту — 5 высот darr
								space_height=Math.max(space_height, 5*darr_coords.height);
								// разворачиваем или сворачиваем текущий селектбокс, определяем его настоящую высоту
								$current_sbox.addClass('sboxDecoratedActive');
								var original_height=$current_opts.css({visibility:'hidden'}).children()[0].offsetHeight;
								var height=Math.min(space_height, original_height);
								$current_opts.css({visibility:'visible'}).children('span.ofh').css({height:height});
								// при необходимости добавляем обработчик mousemove для реализации прокрутки
								if(height<original_height){
									window['evSboxDecorate_scroll_listener']=function(evt){
										var opts_coords=$.evElementCoords(this);
										var layer_x=($.evScrollTop()+evt.clientY) - opts_coords.top;
										window['evSboxDecorate_scroll_k']=2*layer_x/opts_coords.height - 1;
									}
									$current_opts.bind('mousemove',window.evSboxDecorate_scroll_listener);
									var $scroll=$current_opts.children('span.ofh').children('span.scroll').css({top:0});
									window['evSboxDecorate_scroll_info']={
										e: $scroll[0],
										oh: original_height,
										h: height,
										t: 0
									};
									window['evSboxDecorate_scroll_function']=function(){
										if(window.evSboxDecorate_scroll_k<=0){
											// пытаемся прокрутить вниз
											window.evSboxDecorate_scroll_info.t -= 10 * Math.pow(window.evSboxDecorate_scroll_k,3);
											if(window.evSboxDecorate_scroll_info.t > 0){
												window.evSboxDecorate_scroll_info.t=0
											}
											window.evSboxDecorate_scroll_info.e.style.top=window.evSboxDecorate_scroll_info.t+'px';
										}else if(window.evSboxDecorate_scroll_k>0){
											// пытаемся прокрутить вверх
											window.evSboxDecorate_scroll_info.t -= 10 * Math.pow(window.evSboxDecorate_scroll_k,3);
											if(window.evSboxDecorate_scroll_info.t < window.evSboxDecorate_scroll_info.h-window.evSboxDecorate_scroll_info.oh ){
												window.evSboxDecorate_scroll_info.t=window.evSboxDecorate_scroll_info.h-window.evSboxDecorate_scroll_info.oh;
											}
											window.evSboxDecorate_scroll_info.e.style.top=window.evSboxDecorate_scroll_info.t+'px';
										}
									}
									window['evSboxDecorate_scroll_timer']=setInterval('window.evSboxDecorate_scroll_function()',10);
								}
							}else{
								$current_sbox.addClass('sboxDecoratedActive');
							}
						}
					})
					// устанавливаем обработчик клика по телу страницы, который скроет развернутый sbox
					if(typeof window['evSboxDecorate_document_click_listener']=='undefined'){
						window['evSboxDecorate_document_click_listener']=true;
						$(document).click(function(evt){
							//определяем, имеется ли видимый sbox
							var $active_sbox=$('span.sboxDecoratedActive');
							if($active_sbox.length){
								var hide, cancel;
								var $target_sbox=$(evt.target).parents('span.sboxDecorated');
								if($target_sbox.length){
									if($target_sbox!=$active_sbox){
										hide=true;
										cancel=false;
									}else{
										hide=false;
										cancal=false;
									}
								}else{
									hide=true;
									cancel=true;
								}
								if(cancel){
									// запрещаем действие по-умолчанию
									evt.preventDefault();
								}
								if(hide){
									// скрываем sbox
									window.evSboxDecorate_hide_function($active_sbox);
								}
							}
						})
					}
					if(hash.style){
						evSboxDecorate_style_function=function(){
							if(typeof window['evSboxDecorate_css_rules']=='undefined'){
								window['evSboxDecorate_css_rules']=true;
								$.evCSSrule('span.sboxDecorated','z-index: 1;');
								$.evCSSrule('span.sboxDecorated b.first','position: relative;z-index: 1;');
								$.evCSSrule('span.sboxDecorated a.darr','position: absolute;z-index: 2;left: 0;top: 0;display: block;');
								$.evCSSrule('span.sboxDecorated span.options','position: absolute;z-index: 1;left: 0;display: none;');
								$.evCSSrule('span.sboxDecorated span.options span.ofh','display: block;overflow: hidden;');
								$.evCSSrule('span.sboxDecorated span.options span.ofh span.scroll','position: relative;display: block;');
								$.evCSSrule('span.sboxDecoratedActive','z-index: 2;');
								$.evCSSrule('span.sboxDecoratedActive span.options','display: block;');
								$.evCSSrule('span.sboxDecorated span.options a','display: block;');
								$.evCSSrule('span.sboxDecorated span.options b','display: block;');
							}
						}
						evSboxDecorate_style_function();
						if($.browser.safari){
							setTimeout('evSboxDecorate_style_function()',10);
						}
					}
				}
				window['evSboxDecorate_hide_function']=function($current_sbox){
					$current_sbox.removeClass('sboxDecoratedActive');
					$current_sbox.children('span.options').unbind('mousemove', window.evSboxDecorate_ofh_listener).
						children('span.ofh').css({height:'auto'});
					if(window.evSboxDecorate_scroll_timer){
						window.evSboxDecorate_scroll_k=0;
						clearInterval(window.evSboxDecorate_scroll_timer);
					}
				}
				if(hash.delay){
					setTimeout('window.evSboxDecorate_delay_function'+j+'()',50);
				}else{
					window['evSboxDecorate_delay_function'+j]();
				}
			}
		})
		return this;
	},
	evParseTable: function(){
		this.each(function(){
			var $table=$(this);
			if($table.is('table')){
				// 1. 
				// исследуем таблицу $table, определяем ее ширину и высоту,
				// а также создаем матрицу таблицы — одномерный массив, 
				// каждый элемент которого есть число ячеек в СТОЛБЦЕ
				var matrix=[];//матрица ячеек
				var line=1;//текущая строка
				var mxc=1;//max colspan
				var mxr=1;//max rowspan
				$('tr',$table).each(function(){//перебираем строки
					var cells=$('th,td',this).get();//получаем массив ячеек строки
					var index=0;//текущий столбец
					for(var i=0;i<cells.length;i++){//перебираем ячейки
						if(line==1)mxr=(mxr<rowspan)?rowspan:mxr;//отыскиваем максимальный rowspan в первой строке, чтобы знать высоту заголовка страницы
						var rowspan=( parseInt($(cells[i]).attr('rowspan')) || 1 );//устанавливаем rowspan ячейки или 1
						var colspan=( parseInt($(cells[i]).attr('colspan')) || 1 );//устанавливаем colspan ячейки или 1
						for(var j=0;j<colspan;j++){//перебираем colspan
							while((matrix[index] || 0)>=line)index++;//двигаемся по матрице вправо пока не найдем незанятую ячейку
							if(index==0)mxc=(mxc<colspan)?colspan:mxc;//отыскиваем максимальный colspan в первой колонке, чтобы знать ширину заголовка таблицы
							matrix[index]=(matrix[index] || 0) + rowspan;//заполняем матрицу ячейками
							index++;
						}
					}
					line++;
				})
				var matrix_height=0;//определяем высоту таблицы
				for(var i=0;i<matrix.length;i++)matrix_height=(matrix[i]>matrix_height)?matrix[i]:matrix_height;
				var matrix_width=matrix.length;//определяем ширину таблицы
				// 2.
				// устанавливаем ячейкам необходимые атрибуты на основе полученных о таблице сведений
				// механизмом перебора строк и ячеек аналогичен 1, поэтому часть комментариев опущена
				var matrix=[];
				var line=1;
				$('tr',$table).each(function(){
					var cells=$('th,td',this).get();
					var index=0;
					for(var i=0;i<cells.length;i++){
						var rowspan=( parseInt($(cells[i]).attr('rowspan')) || 1 );
						var colspan=( parseInt($(cells[i]).attr('colspan')) || 1 );
						$(this).addClass('row'+line);
						$(this).addClass((line%2==0)?'even':'odd');
						$(cells[i]).addClass('col'+(index+1));
						for(var j=0;j<colspan;j++){
							while((matrix[index] || 0)>=line)index++;
							matrix[index]=(matrix[index] || 0) + rowspan;
							if(line==1)$(cells[i]).addClass('top');//всем верхним ячейкам добавляем класс "top"
							if((index+1)==matrix_width)$(cells[i]).addClass('allright');//всем правым "right"
							if(matrix[index]==matrix_height)$(cells[i]).addClass('bottom');//всем нижним "bottom"
							if(index==0)$(cells[i]).addClass('allleft');//всем левым ячейкам добавляем класс "left"
							if(matrix[index]<=mxr)$(cells[i]).addClass('headrow');//всем ячейкам в составе горизонтального (или верхнего) заголовка таблицы добавляем класс "headrow"
							if((index+colspan)<=mxc)$(cells[i]).addClass('headcol');//всем ячейкам в составе вертикального (или левого) заголовка таблицы - класс "headrow"
							if(colspan>1)$(cells[i]).addClass('wide wide'+colspan);// всем ячейкам с атрибутом colspan добавляем классы wide и wideN
							if(rowspan>1)$(cells[i]).addClass('high high'+rowspan);// всем ячейкам с атрибутом rowspan добавляем классы high и highN
							index++;
						}
					}
					line++;
				})
			}
		})
		return this;
	},
	evPositionFixed: function(hash){
		// hash={attach:'lt|lb|rt|rb',width:123}
		this.each(function(){
			hash=(hash || {});
			hash.attach=(hash.attach || 'lt');
			var $element=$(this);
			if($element.length){
				var h=$element[0].offsetHeight;
				$element.css({position: 'absolute',height: h});
				if(hash.width){
					$element.width(hash.width);
				}
				var opts={
					el: $element,
					attach: hash.attach
				};
				if(hash.attach.indexOf('r')>=0){
					try{
						opts.r=$element.css('right');
					}catch(e){
						opts.r=0;
					}
				}else{
					try{
						opts.l=$element.css('left');
					}catch(e){
						opts.l=0;
					}
				}
				if(hash.attach.indexOf('b')>=0){
					try{
						opts.b=$element.css('bottom');
					}catch(e){
						opts.b=0;
					}
				}else{
					try{
						opts.t=$element.css('top');
					}catch(e){
						opts.t=0;
					}
				}
				// alert([opts.t,opts.r,opts.b,opts.l,opts.attach])
				$(window).bind('scroll resize', (function(opts){
					return function(evt){
						if(opts.attach.indexOf('r')>=0){
							if(opts.r!='auto'){
								var l=$.evScrollLeft() + $.evScreenWidth() - parseInt(opts.r) - opts.el[0].offsetWidth;
								opts.el.css({left:l});
							}
						}else{
							if(opts.l!='auto'){
								var l=$.evScrollLeft() + parseInt(opts.l);
								opts.el.css({left:l});
							}
						}
						if(opts.attach.indexOf('b')>=0){
							if(opts.b!='auto'){
								var t=$.evScrollTop() + $.evScreenHeight() - parseInt(opts.b) - opts.el[0].offsetHeight;
								opts.el.css({top:t});
							}
						}else{
							if(opts.t!='auto'){
								var t=$.evScrollTop() + parseInt(opts.t);
								opts.el.css({top:t});
							}
						}
					}
				})(opts)).trigger('resize');
			}
		})
		return this;
	},
	evGMapping: function(){
		this.each(function(i){
			var $element=$(this);
			// создаем массив window.evGMapping_GMap2 со ссылками на карты
			if(i==0 && typeof window['evGMapping_GMap2']=='undefined'){
				window['evGMapping_GMap2']=[];
			}
			// параметры карты по атрибутам элемента
			var gmap2_index=window.evGMapping_GMap2.length;
			var lat__lng=($element.attr('data-coords') || '55.72711, 42.099609');
			var lat=parseFloat(lat__lng.split(',')[0]);
			var lng=parseFloat(lat__lng.split(',')[1]);
			var zoom=parseInt($element.attr('data-zoom') || 4);
			var width=($element.attr('data-width') || '100%');
			if(parseInt(width) == width) width+='px';
			var height=($element.attr('data-height') || 600);
			if(parseInt(height) == height) height+='px';
			// скрываем детей, создаем бокс для карты
			$element.children().hide();
			var $gmap_box = $(document.createElement('div')).addClass('GMapBox').attr({'data-gmap2index':gmap2_index}).width(width).height(height).prependTo($element);
			// создаем карту
			window.evGMapping_GMap2[gmap2_index] = new GMap2($gmap_box[0]);
			window.evGMapping_GMap2[gmap2_index].setCenter(new GLatLng(lat, lng), zoom);
			// управление картой
			window.evGMapping_GMap2[gmap2_index].addControl(new GMapTypeControl());
			window.evGMapping_GMap2[gmap2_index].enableScrollWheelZoom();
			// window.evGMapping_GMap2[gmap2_index].addMapType(G_PHYSICAL_MAP);
			// window.evGMapping_GMap2[gmap2_index].removeMapType(G_HYBRID_MAP);
			window.evGMapping_GMap2[gmap2_index].addControl(new GLargeMapControl3D());
			// нестандартная кнопка со слоем
			$element.children('div.GMcontrol').each(function(){
				var $control_element=$(this);
				// параметры
				var group_title=$control_element.attr('data-title');
				// чтобы создать подкласс элемента GControl, 
				// задаем GControl в качестве объекта-прототипа
				// и реализуем два его метода: initialize и getDefaultPosition
				var GroupControl=function(){}
				GroupControl.prototype = new GControl();
				GroupControl.prototype.initialize = function(map) {
					var width=(parseInt($control_element.attr('data-width')) || 197);
					var $container=$(document.createElement('div')).addClass('GMgroupControl').width(width).
						append($(document.createElement('div')).addClass('brd'));
					var $btn=$(document.createElement('div')).addClass('btn').text(group_title).
						appendTo($container.children('div.brd'));
					if($control_element.children('div.GMgroup').length){
						var $ul=$(document.createElement('ul')).
							appendTo($(document.createElement('div')).addClass('ul').
								appendTo($container.children('div.brd')));
						$control_element.children('div.GMgroup').each(function(){
							var $group=$(this);
							var title=$group.attr('data-title');
							var $a=$(document.createElement('a')).attr({href:'#'}).text(title).
								appendTo($(document.createElement('li')).appendTo($ul));
							// обработчик клика по ссылке
							$a.bind('click',function(evt){
								evt.preventDefault();
								var lat__lng=$group.attr('data-coords');
								var lat=parseFloat(lat__lng.split(',')[0]);
								var lng=parseFloat(lat__lng.split(',')[1]);
								var zoom=parseInt($group.attr('data-zoom'));
								map.setCenter(new GLatLng(lat, lng), zoom);
							})
						})
						// обработчик mouseover mouseout на кнопке группы
						window['evGMapping_hidegroup_function'+i]=function(){$container.removeClass('GMgroupControlActive');}
						$container.bind('mouseover mouseout', function(evt){
							if(evt.type=='mouseover'){
								try{clearTimeout(window['evGMapping_hidegroup_timer'+i])}catch(e){}
								$container.addClass('GMgroupControlActive');
							}else if(evt.type=='mouseout'){
								window['evGMapping_hidegroup_timer'+i]=setTimeout('window.evGMapping_hidegroup_function'+i+'()',100);
							}
						});
						$btn.bind('click',function(){
							$container.toggleClass('GMgroupControlActive');
						})
					}
					map.getContainer().appendChild($container[0]);
				  return $container[0];
				}
				GroupControl.prototype.getDefaultPosition = function() {
					var corner;
					if($control_element.attr('data-right-top')){
						var x__y=$control_element.attr('data-right-top');
						corner=G_ANCHOR_TOP_RIGHT;
					}else if($control_element.attr('data-left-top')){
						var x__y=$control_element.attr('data-left-top');
						corner=G_ANCHOR_TOP_LEFT;
					}else if($control_element.attr('data-left-bottom')){
						var x__y=$control_element.attr('data-left-bottom');
						corner=G_ANCHOR_BOTTOM_LEFT;
					}else if($control_element.attr('data-right-bottom')){
						var x__y=$control_element.attr('data-right-bottom');
						corner=G_ANCHOR_BOTTOM_RIGHT;
					}else{
						var x__y='7,33';
						corner=G_ANCHOR_TOP_RIGHT;
					}
					var x=parseInt(x__y.split(',')[0]);
					var y=parseInt(x__y.split(',')[1]);
					return new GControlPosition(corner, new GSize(x, y));
				}
				// задаем первичные стили
				if(i==0 && typeof window['evGMapping_css_control_done']=='undefined'){
					window['evGMapping_css_control_done']=true;
					$.evCSSrule('div.GMapBox div.GMgroupControl', 'background: white;border: 1px solid black;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd', 'border-style: solid;border-width: 1px;border-color: #fff #b0b0b0 #b0b0b0 #fff;font: 12px "Arial", sans-serif;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd div.btn', 'text-align: center;cursor: pointer;height: 16px;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd div.ul', 'display: none;margin: -1px 4px 4px;padding: 4px 0 2px;border-top: 1px solid #ddd;')
					$.evCSSrule('div.GMapBox div.GMgroupControlActive div.brd div.ul', 'display: block;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd div.ul ul', 'margin: 0 4px 0 8px;padding: 0;list-style-type: disc;font-size: 11px;line-height: 1em;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd div.ul ul', 'overflow: auto;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd div.ul ul li', 'margin: 4px 0;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd div.ul ul li:before', 'content: "●";font-size: 9px;padding-right: 6px;')
					$.evCSSrule('div.GMapBox div.GMgroupControl div.brd div.ul ul li a', 'color: red;')
				}
				window.evGMapping_GMap2[gmap2_index].addControl(new GroupControl());
			})
			// создаем метод для создания иконок
			var get_icon_function=function($e, base_icon){
				var icon=new GIcon(base_icon || G_DEFAULT_ICON);
				if($e.attr('data-icon-png'))
					icon.image=$e.attr('data-icon-png');
				if($e.attr('data-icon-gif'))
					icon.printImage=$e.attr('data-icon-gif');
				if($e.attr('data-icon-size'))
					icon.iconSize=new GSize(parseInt($e.attr('data-icon-size').split(',')[0]), parseInt($e.attr('data-icon-size').split(',')[1]));
				if($e.attr('data-icon-anchor'))
					icon.iconAnchor=new GPoint(parseInt($e.attr('data-icon-anchor').split(',')[0]), parseInt($e.attr('data-icon-anchor').split(',')[1]));
				if($e.attr('data-icon-map')){
					var map=[];
					for(var j=0; j<$e.attr('data-icon-map').split(',').length; j++)
						map[map.length]=parseInt($e.attr('data-icon-map').split(',')[j]);
					icon.imageMap=map;
				}
				if($e.attr('data-shadow-png'))
					icon.shadow=$e.attr('data-shadow-png');
				if($e.attr('data-shadow-gif'))
					icon.printShadow=$e.attr('data-shadow-gif');
				if($e.attr('data-shadow-size'))
					icon.shadowSize=new GSize(parseInt($e.attr('data-shadow-size').split(',')[0]), parseInt($e.attr('data-shadow-size').split(',')[1]));
				return icon;
			}
			// создаем общую иконку для карты в целом
			var base_icon=get_icon_function($element);
			// кластерер и маркеры
			$element.find('div.GMgroup').each(function(){
				var $group_element=$(this);
				var max_visible_markers = parseInt($group_element.attr('data-max-visible-markers'));
				var min_markers_per_cluster = parseInt($group_element.attr('data-min-markers-per-cluster'));
				var clusterer = false;
				var group_icon=get_icon_function($group_element, base_icon);
				if(max_visible_markers && min_markers_per_cluster){
					try{
						clusterer = new Clusterer(window.evGMapping_GMap2[gmap2_index]);
					}catch(e){
						alert('You need to download and include Clusterer2.js\n\nhttp://www.acme.com/javascript/Clusterer2.js');
					}
					clusterer.SetMaxVisibleMarkers(max_visible_markers);
					clusterer.SetMinMarkersPerCluster(min_markers_per_cluster);
					if($group_element.data()){}
					clusterer.SetIcon(group_icon);
				}
				$group_element.children('div.GMpoint').each(function(){
					var $point_element=$(this);
					var lat__lng=$point_element.attr('data-coords');
					var lat=parseFloat(lat__lng.split(',')[0]);
					var lng=parseFloat(lat__lng.split(',')[1]);
					var point = new GLatLng(lat, lng);
					var point_icon = get_icon_function($point_element, group_icon);
					var marker = new GMarker(point, {icon: point_icon});
					if(clusterer){
						clusterer.AddMarker(marker, 'title');
					}else{
						window.evGMapping_GMap2[gmap2_index].addOverlay(marker);
					}
					var html=$point_element.children('div.htmlInfo').html();
					if(html){
						GEvent.addListener(marker, "click", (function(marker, html){
							return function(){
								marker.openInfoWindowHtml(html);
							}
						})(marker, html));
					}
				})
			});
		})
	}
})
