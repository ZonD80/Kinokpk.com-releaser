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