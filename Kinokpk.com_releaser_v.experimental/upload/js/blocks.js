function showshides(id)
{
        var klappText = document.getElementById('b' + id);
        var klappBild = document.getElementById('pic' + id);
        if (klappText.style.display == 'block') {
                  klappBild.src = 'pic/plus.gif';
              type = "hide";
        } else {
                  klappBild.src = 'pic/minus.gif';
              type = "show";
        }
    $.get("blocks.php", {"type": type, "bid": id}, function(data){}, 'html');
    $(document).ready(function(){
		$('#b' + id).slideToggle("medium");
	});
}

function resize_textarea(to, id)
{
	var orig = 25; 
	var step = 25; 
	var orig_w = 199;
	var textarea = document.getElementById(id);

if (to == 0)
{
	var t_height = textarea.style.height.replace('px', '');
	var t_width = textarea.style.width.replace('px', '');
	if (t_height <= orig) textarea.style.height = orig + 'px';
	if (t_width == orig_w) textarea.style.width = orig_w + 'px';

else
{
	var height = parseInt(t_height) - parseInt(step);
	var width = parseInt(t_width) + parseInt(step);

		textarea.style.height = height + 'px';
		textarea.style.width = width + 'px';
}
}
else
{
		var t_height = textarea.style.height.replace('px', '');
		var t_width = textarea.style.width.replace('px', '');
		var height = parseInt(t_height)+parseInt(step);
		var width = parseInt(t_width)-parseInt(step);
			textarea.style.height = height + 'px';
			textarea.style.width = width + 'px';
}
return false;	
}

//<![CDATA[
//csstopmenu_1
startMenu = function() {
if (document.all&&document.getElementById) {
cssmenu = document.getElementById("csstopmenu_1");
for (i=0; i<cssmenu.childNodes.length; i++) {
node = cssmenu.childNodes[i];
if (node.nodeName=="LI") {
node.onmouseover=function() {
this.className+=" over";
}
node.onmouseout=function(){                  
this.className=this.className.replace(" over", "")
}
}
}
}
}
if (window.attachEvent)
window.attachEvent("onload", startMenu)
else
window.onload=startMenu;
//csstopmenu_2
startMenu = function() {
if (document.all&&document.getElementById) {
cssmenu = document.getElementById("csstopmenu_2");
for (i=0; i<cssmenu.childNodes.length; i++) {
node = cssmenu.childNodes[i];
if (node.nodeName=="LI") {
node.onmouseover=function() {
this.className+=" over";
}
node.onmouseout=function(){                  
this.className=this.className.replace(" over", "")
}
}
}
}
}
if (window.attachEvent)
window.attachEvent("onload", startMenu)
else
window.onload=startMenu;
//]]>

				jQuery(function () {
				 jQuery(window).scroll(function () {
					if (jQuery(this).scrollTop() != 0) {
						 jQuery('#toTop').fadeIn();
					} else {
						 jQuery('#toTop').fadeOut();
					}
				 });
					 jQuery('#toTop').click(function () {
						 jQuery('body,html').animate({
							 scrollTop: 0
						 },
						 800);
						 });

					});

$(document).ready(function(){
	$('select[name!="type"]').evSboxDecorate({style:true,scroll:true});
	$('.pagetop select.linkselect,.pagebottom select.linkselect').evSboxDecorate({style:true,scroll:true,submitForm:true});
	$('#forum table, #torrenttable').evParseTable();

$(window).scroll(function(){
	$('table#torrenttable, table#forumtable').each(function(){
var $elTop = $('#torrenttable,#forumtable').evElementCoords(); //0>>>361
var $elTopFix = $.evScrollTop("table#torrenttable, table#forumtable");//<>361
	if($elTopFix>0){
			if($elTopFix>=$elTop.top){
				$("#releases-table").find("table#torrenttable").find("tr.top").addClass("fixed");
				$("#forum").find("table#forumtable").find("tr.top").addClass("fixed");
				
			}else{
				$("#releases-table").find("table#torrenttable").find("tr.top").removeClass("fixed");
				$("#forum").find("table#forumtable").find("tr.top").removeClass("fixed");
			
			}
		}
	});
});

if ($("#entries").length>0){
	
var $StWidth=$.evScrollHeight("body");//размер тела
var $ElHeight=$("#entries").evElementCoords();

function screenSize() {
      var h; // h - высота окна
      h = (window.innerHeight ? window.innerHeight : (document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.offsetHeight));
      return {h:h};
}



			if ($StWidth>=screenSize().h){
				var $size = $StWidth-screenSize().h;
				$("#entries").css("height", $ElHeight.height-$size+"px" );
			}else{
				var $size = screenSize().h-$StWidth;$("#entries").css("height", $ElHeight.height+$size+"px" );} 
	$(window).each(function(){
		var $hTop = 20+ $("td.blocks_c").height();
		$("div#main").css("top",$hTop);
	});
	$(window).bind('resize', function(){
			if ($StWidth>=screenSize().h){var $size = $StWidth-screenSize().h;$("#entries").css("height", $ElHeight.height-$size+"px" );
			}else{var $size = screenSize().h-$StWidth;$("#entries").css("height", $ElHeight.height+$size+"px" );}

		});

	
var ContentHeight=$("#entries").evElementCoords();
var contentHeight=ContentHeight.height;
var pageHeight = document.documentElement.clientHeight;
var scrollPosition;
var n = 10;
var xmlhttp;

function putImages(){
	
	if (xmlhttp.readyState==4) 
	  {
		  if(xmlhttp.responseText){
			 var resp = xmlhttp.responseText.replace("\r\n", ""); 
			 var files = resp.split(";");
			  var j = 0;
			  for(i=0; i<files.length; i++){
				  if(files[i] != ""){
					 document.getElementById("main").innerHTML += '<a href="img/'+files[i]+'"><img src="thumb/'+files[i]+'" /></a>';
					 j++;
				  
					 if(j == 3 || j == 6)
						  document.getElementById("main").innerHTML += '<br />';
					  else if(j == 9){
						  document.getElementById("main").innerHTML += '<p>'+(n-1)+" Images Displayed | <a href='#header'>top</a></p><br /><hr />";
						  j = 0;
					  }
				  }
			  }
		  }
	  }
}
		
		
function scroll(){
	
	if(navigator.appName == "Microsoft Internet Explorer")
		scrollPosition = document.documentElement.scrollTop;
	else
		scrollPosition = window.pageYOffset;		
	
	if((contentHeight - pageHeight - scrollPosition) < 500){
				
		if(window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			if(window.ActiveXObject)
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			else
				alert ("Извините! Ваш браузер не поддерживает XMLHTTP!");		  
		  
		var url="browse2.php?page="+n;
		
		xmlhttp.open("GET",url,true);
		xmlhttp.send();
		
		n += 9;
		xmlhttp.onreadystatechange=putImages;		
		contentHeight += 400;		
	}
}
	}
	
});
/*console.log($StWidth)
console.log(screenSize().h)
console.log($ElHeight.height)
console.log($size)*/