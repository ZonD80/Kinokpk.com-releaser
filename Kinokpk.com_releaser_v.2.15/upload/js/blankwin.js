/* 

	Blankwin function edited by ZonD80
	written by Alen Grakalic, provided by Css Globe (cssglobe.com)
	please visit http://cssglobe.com/post/1281/open-external-links-in-new-window-automatically/ for more info
	
*/

this.checkext = function(){
	var hostname = window.location.hostname;
	hostname = hostname.replace("www.","").toLowerCase();
	var a = document.getElementsByTagName("a");	
	this.check = function(obj){
		var href = obj.href.toLowerCase();
		return (href.indexOf("http://")!=-1 && href.indexOf(hostname)==-1) ? true : false;				
	};
};
