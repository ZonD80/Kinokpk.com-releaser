/*
	Paginator 3000
	- idea by ecto (ecto.ru)
	- coded by karaboz (karaboz.ru)
	- edited by WacesRedky (redk.in)
*/
Paginator = function(id, pagesTotal, pagesSpan, currentPage, baseUrl, returnOrder){
	this.htmlBox = document.getElementById(id);
	if(!this.htmlBox || !pagesTotal || !pagesSpan) return;

	if(returnOrder){
		this.returnOrder = returnOrder;
	} else {
		this.returnOrder = false;
	}

	this.pagesTable;
	this.pagesTr;
	this.sliderTr;
	this.pagesCells;
	this.slider;

	this.pagesTotal = pagesTotal;
	if(pagesSpan < pagesTotal){
		this.pagesSpan = pagesSpan;
	} else {
		this.tableWidth = Math.floor(100*pagesTotal/pagesSpan) + '%';
		this.htmlBox.className += ' fullsize';
		this.pagesSpan = pagesTotal;
	}

	this.currentPage = currentPage;
	this.currentPage = currentPage;
	this.firstCellValue;

	if(baseUrl){
		this.baseUrl = baseUrl;
	} else {
		this.baseUrl = "/pages/";
	}
	if (!this.baseUrl.match(/%page%/i))
	  this.baseUrl += '%page%';


	this.initPages();
	this.initSlider();
	this.drawPages();
	this.initEvents();

	this.scrollToCurrentPage();
	this.setCurrentPagePoint();
}

Paginator.prototype.initPages = function(){
	var html = "<table><tr>"
	for (var i= 1; i<=this.pagesSpan; i++){
		html += "<td></td>"
	}
	html += "</tr>" +
	"<tr><td colspan='" + this.pagesSpan + "'>" +
	"<div class='scrollbar'>" +
		"<div class='line'></div>" +
		"<div class='current_page_point'></div>" +
		"<div class='slider'>" +
			"<div class='slider_point'></div>" +
		"</div>" +
	"</div>" +
	"</td></tr></table>"
	this.htmlBox.innerHTML = html;

	this.pagesTable = this.htmlBox.getElementsByTagName('table')[0];
	this.pagesTr = this.pagesTable.getElementsByTagName('tr')[0];
	this.sliderTr = this.pagesTable.getElementsByTagName('tr')[1];
	this.pagesCells = this.pagesTr.getElementsByTagName('td');
	this.scrollbar = getElementsByClassName(this.pagesTable,'div','scrollbar')[0];
	this.slider = getElementsByClassName(this.pagesTable,'div','slider')[0];
	this.currentPagePoint = getElementsByClassName(this.pagesTable,'div','current_page_point')[0];

	this.pagesTable.style.width = this.tableWidth;
//	this.pagesTable.style.width = '100%';
}

Paginator.prototype.initSlider = function(){
	this.slider.xPos = 0;
	this.slider.style.width = this.pagesSpan/this.pagesTotal * 100 + "%";
}

Paginator.prototype.initEvents = function(){
	var _this = this;

	this.htmlBox.onmouseover = function(e){
	  if (!e) var e = window.event;
	  var flag = true;
	  handle = function(delta){
		if(_this.paginatorBox && _this.paginatorBox.hasClass('fullsize')) return;
		if (flag) {
		  flag = false;
		  if (!_this.returnOrder)
			_this.slider.xPos -= _this.pagesSpan/_this.pagesTotal*_this.pagesTable.offsetWidth * delta;
		  else _this.slider.xPos += _this.pagesSpan/_this.pagesTotal*_this.pagesTable.offsetWidth * delta;
		  _this.setSlider();
		  _this.drawPages();
		}
	  };
	}
	this.htmlBox.onmouseout = function(e){
	  handle = null;
	}

	this.slider.onmousedown = function(e){ /* drag */
		if (!e) var e = window.event;
		e.cancelBubble = true;
//		this.dx = getMousePosition(e).x - this.xPos;
		this.dx = !_this.returnOrder ?
		  getMousePosition(e).x - this.xPos :
		  getMousePosition(e).x - (_this.scrollbar.offsetWidth - this.xPos);
		document.onmousemove = function(e){
			if (!e) var e = window.event;
			_this.slider.xPos = !_this.returnOrder ?
			  getMousePosition(e).x - _this.slider.dx:
			  _this.scrollbar.offsetWidth - getMousePosition(e).x + _this.slider.dx;
			_this.setSlider();
			_this.drawPages();
		}
		document.onmouseup = function(){
			document.onmousemove = null;
			_this.enableSelection();
		}
		_this.disableSelection();
	}

	this.scrollbar.onmousedown = function(e){ /* click */
		if(_this.paginatorBox && _this.paginatorBox.hasClass('fullsize')) return;
		if (!e) var e = window.event;
		_this.slider.xPos = !_this.returnOrder ? (getMousePosition(e).x - getPageX(_this.scrollbar) - _this.slider.offsetWidth/2) : (_this.scrollbar.offsetWidth - getMousePosition(e).x + getPageX(_this.scrollbar) - _this.slider.offsetWidth/2);
		_this.setSlider();
		_this.drawPages();
	}

	addEvent(window, 'resize', resizePaginator);

	document.onkeydown = function(event) {
		var tagName = event.target.tagName;
//		if (tagName != 'INPUT' && tagName != 'TEXTAREA' && !event.alt && event.control) {

			if (event.ctrlKey && event.keyCode == 37) {
				if (_this.currentPage > 1) {
					window.location.href = _this.baseUrl.replace(/%page%/i, _this.currentPage - 1);
				}
			} else if (event.ctrlKey && event.keyCode == 39) {
				if (_this.currentPage < _this.pagesTotal) {
					window.location.href = _this.baseUrl.replace(/%page%/i, _this.currentPage + 1);
				}
			}
//		}
	}


}

Paginator.prototype.setSlider = function(){
	this.slider.style.left = (!this.returnOrder ? this.slider.xPos : this.scrollbar.offsetWidth-this.slider.xPos-this.slider.offsetWidth) + "px";
}

Paginator.prototype.drawPages = function(){

	var percentFromLeft = this.slider.xPos/(this.pagesTable.offsetWidth);

	this.firstCellValue = Math.round(percentFromLeft * this.pagesTotal);
	var html = "";
	if(this.firstCellValue < 1){
		this.firstCellValue = 1;
		this.slider.xPos = 0;
		this.setSlider();
	} else if(this.firstCellValue >= this.pagesTotal - this.pagesSpan) {
		this.firstCellValue = this.pagesTotal - this.pagesSpan + 1;
		this.slider.xPos = this.pagesTable.offsetWidth - this.slider.offsetWidth;
		this.setSlider();
	}
	for(var i= 0; i < this.pagesCells.length; i++){
		var currentCellValue = this.firstCellValue + i;
		if(currentCellValue == this.currentPage){
			html = "<span class='pagstrong'>" + String(currentCellValue) + "</span>";
		} else {
			html = "<a href='" + this.baseUrl.replace(/%page%/i,  currentCellValue) + "'><span>" + String(currentCellValue) + "</span></a>";
		}
		this.pagesCells[!this.returnOrder ? i : this.pagesCells.length - i - 1].innerHTML = html;
	}
}

Paginator.prototype.scrollToCurrentPage = function(){
	this.slider.xPos = (this.currentPage - Math.round(this.pagesSpan/2))/this.pagesTotal * this.pagesTable.offsetWidth;
	this.setSlider();
	this.drawPages();
}

Paginator.prototype.setCurrentPagePoint = function(){
	if(this.currentPage == 1){
		this.currentPagePoint.style.left = !this.returnOrder ? 0 : (this.scrollbar.offsetWidth - this.currentPagePoint.offsetWidth) + 'px';
	} else {
		this.currentPagePoint.style.left = !this.returnOrder ? this.currentPage/this.pagesTotal * this.pagesTable.offsetWidth : this.scrollbar.offsetWidth - this.currentPage/this.pagesTotal * this.pagesTable.offsetWidth + "px";
	}
}

Paginator.prototype.disableSelection = function(){
	document.onselectstart = function(){
		return false;
	}
	this.slider.focus();
}

Paginator.prototype.enableSelection = function(){
	document.onselectstart = function(){
		return true;
	}
	this.slider.blur();
}

resizePaginator = function (){
	if(typeof pag == 'undefined') return;

	pag.setCurrentPagePoint();
	pag.scrollToCurrentPage();
}

function getPageX( oElement ) {
	var iPosX = oElement.offsetLeft;
	while ( oElement.offsetParent != null ) {
		oElement = oElement.offsetParent;
		iPosX += oElement.offsetLeft;
		if (oElement.tagName == 'BODY') break;
	}
	return iPosX;
}
function addEvent(objElement, strEventType, ptrEventFunc) {
	if (objElement.addEventListener)
		objElement.addEventListener(strEventType, ptrEventFunc, false);
	else if (objElement.attachEvent)
		objElement.attachEvent('on' + strEventType, ptrEventFunc);
}
function matchClass( objNode, strCurrClass ) {
	return ( objNode && objNode.className.length && objNode.className.match( new RegExp('(^|\\s+)(' + strCurrClass + ')($|\\s+)') ) );
}

function getElementsByClassName(objParentNode, strNodeName, strClassName){
	var nodes = objParentNode.getElementsByTagName(strNodeName);
	if(!strClassName){
		return nodes;
	}
	var nodesWithClassName = [];
	for(var i=0; i<nodes.length; i++){
		if(matchClass( nodes[i], strClassName )){
			nodesWithClassName[nodesWithClassName.length] = nodes[i];
		}
	}
	return nodesWithClassName;
}
function getMousePosition(e) {
	if (e.pageX || e.pageY){
		var posX = e.pageX;
		var posY = e.pageY;
	}else if (e.clientX || e.clientY) 	{
		var posX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
		var posY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
	}
	return {x:posX, y:posY}
}
// Основная Функция mousewheel
function mousewheel(event){
        var delta = 0;
        if (!event) event = window.event; // Событие IE.
        // Установим кроссбраузерную delta
        if (event.wheelDelta) {
                // IE, Opera, safari, chrome - кратность дельта равна 120
                delta = event.wheelDelta/120;
        } else if (event.detail) {
                // Mozilla, кратность дельта равна 3
                delta = -event.detail/3;
        }
        // Вспомогательня функция обработки mousewheel
        if (delta && typeof handle == 'function') {
                handle(delta);
                // Отменим текущее событие - событие поумолчанию (скролинг окна).
                if (event.preventDefault)
                        event.preventDefault();
                event.returnValue = false; // для IE
        }
}

// Инициализация события mousewheel
if (window.addEventListener) // mozilla, safari, chrome
    window.addEventListener('DOMMouseScroll', mousewheel, false);
// IE, Opera.
window.onmousewheel = document.onmousewheel = mousewheel;