/* 

	Blankwin function 
	written by Alen Grakalic, provided by Css Globe (cssglobe.com)
	please visit http://cssglobe.com/post/1281/open-external-links-in-new-window-automatically/ for more info
	
*/

this.blankwin = function(){
	var hostname = window.location.hostname;
	hostname = hostname.replace("www.","").toLowerCase();
	var a = document.getElementsByTagName("a");	
	this.check = function(obj){
		var href = obj.href.toLowerCase();
		return (href.indexOf("http://")!=-1 && href.indexOf(hostname)==-1) ? true : false;				
	};
	this.set = function(obj){
		obj.target = "_blank";
	};	
	for (var i=0;i<a.length;i++){
		if(check(a[i])) set(a[i]);
	};		
};



// script initiates on page load. 

this.addEvent = function(obj,type,fn){
	if(obj.attachEvent){
		obj['e'+type+fn] = fn;
		obj[type+fn] = function(){obj['e'+type+fn](window.event );}
		obj.attachEvent('on'+type, obj[type+fn]);
	} else {
		obj.addEventListener(type,fn,false);
	};
};
addEvent(window,"load",blankwin);