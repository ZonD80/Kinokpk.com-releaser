var do_linked_resize = parseInt( "1" );
var resize_percent = parseInt( "50" );

add_onload_event(fix_linked_image_sizes);

function fix_linked_image_sizes() {
	if (do_linked_resize!=1) {
		return true;
	}
	var images=document.getElementsByTagName('IMG');
	var _padding=2;
	var _count=0;
	var _img='<img src="pic/img-resized.png" style="vertical-align:middle" border="0" alt="" />';
	var _sw=screen.width*(parseInt(resize_percent)/100);
	for (var i=0;i<images.length;i++) {
		if (images[i].className=='linked-image') {
			_count++;
			if (images[i].width>_sw) {
				var _width=images[i].width;
				var _height=images[i].height;
				var _percent=0;
				images[i].width=_sw;
				if (images[i].width<_width&&_width>0&&images[i].width>0) {
					_percent=Math.ceil(parseInt(images[i].width/_width*100));
				}
				images[i].id='--ipb-img-resizer-'+_count;
				images[i]._resized=1;
				images[i]._width=_width;
				var div=document.createElement('div');
				div.innerHTML=_img+'&nbsp;'+'Уменьшено: '+_percent+'% от оригинала [ '+_width+' x '+_height+' ] - Нажмите для просмотра полного изображения';
				div.style.width=images[i].width-(_padding*2)+'px';
				div.className='resized-linked-image';
				div.style.textAlign='left';
				div.style.fontWeight='normal';
				div.style.paddingTop=_padding+"px";
				div.style.paddingBottom=_padding+"px";
				div.style.paddingLeft=_padding+"px";
				div.style.paddingRight=_padding+"px";
				div._is_div=1;div._resize_id=_count;
				div.onclick=fix_linked_images_onclick;
				div.onmouseover=fix_linked_images_mouseover;
				div.title='Нажмите для просмотра полного изображения';
				div._src=images[i].src;
				images[i].parentNode.insertBefore(div,images[i]);
			}
		}
	}
}

function fix_linked_images_onclick(e) {
	PopUp(this._src,'popup',screen.width,screen.height,1,1,1);
	//e=ipsclass.cancel_bubble_all(e);
	return false;
}

function fix_attach_images_mouseover(e) {
	try {
		this.style.cursor='pointer';
	}
	catch (acold) {}
}

function fix_linked_images_mouseover(e) {
	try {
		this.style.cursor='pointer';
	}
	catch (acold) {}
}

function PopUp (url, name, width, height, center, resize, scroll, posleft, postop) {
	showx="";
	showy="";
	if (posleft!=0) {
		X=posleft;
	}
	if (postop!=0) {
		Y=postop;
	}
	if (!scroll) {
		scroll=1;
	}
	if (!resize) {
		resize=1;
	}
	if ((parseInt(navigator.appVersion)>=4)&&(center)) {
		X=(screen.width-width)/2;
		Y=(screen.height-height)/2;
	}
	if (X>0) {
		showx=',left='+X;
	}
	if (Y>0) {
		showy=',top='+Y;
	}
	if (scroll!=0) {
		scroll=1;
	}
	window.location = url;
	//var Win=window.open(url,name,'width='+width+',height='+height+showx+showy+',resizable='+resize+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no');
}

function add_onload_event(func) {
	var oldonload=window.onload;
	if (typeof window.onload!='function') {
		window.onload=func;
	} else {
		window.onload=function() {
			if (oldonload) {
				oldonload();
			};
			func();
		};
	}
}