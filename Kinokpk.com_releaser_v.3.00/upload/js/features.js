$(document).ready(
function(){
	initSpoilers();
	init_js();
	$('input').checkBox();
});

function initSpoilers(context) {
	var context = context || 'body';
	$('div.sp-head', $(context))
		.click(function(){
		    var ctx = $(this).next('div.sp-body');
			var code = ctx.children('textarea').text();
			if (code) {
			    ctx.children('textarea').replaceWith(code);
			    initSpoilers(ctx);
			}
			$(this).toggleClass('unfolded');
            $(this).next('div.sp-body').slideToggle('slow');
            $(this).next('div.sp-body').next().slideToggle('slow');
		});
}
 
function init_js() {
$(function() {
  $('a[href!="http://"]').each(
    function(){
            if((this.href.indexOf(location.hostname) == -1) && (this.href.indexOf('javascript') == -1))
            {
            	$(this).addClass('external');//.attr('target', '_blank');
            	this.href='away.php?url='+this.href;
            }
    })
  });

 $('img').error(function() {
        $(this).attr({
        src: 'pic/imgmiss.gif'
        });
    });
}

function rateit(rid,rtype,rate) {

      field = "#ratearea-"+rid+"-"+rtype;

$(field).each(
    function(){
   $(this).empty();
   $(this).append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
}
);

    $.get("rate.php", { ajax: 1, id: rid, type: rtype, act: rate}, function(data){
   $(field).each(
    function(){
   $(this).empty();
   $(this).append(data);
}
);
});

return false;

}

function notifyme(cid,id,type,act) {
      if ($) no_ajax = false;
      field = "#notifarea-"+id;
   $(field).empty();
   $(field).append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
    $.get("notifs.php", { ajax: 1, cid: cid, id: id, type: type, action: act}, function(data){
   $(field).empty();
   $(field).append(data);
});
return false;

}
$(function(){
				
				$('#toggle-all').click(function(){
					$('#message input[type=checkbox]').checkBox('toggle');
					return false;
				});
				
			});
//Get user selection text on page
function getSelectedText() {
    if (window.getSelection) {
        return window.getSelection();
    }
    else if (document.selection) {
        return document.selection.createRange().text;
    }
    return false;
}
function quote_comment(nickname) {
	selection = getSelectedText();
	if (selection=='') { alert(REL_LANG_NO_TEXT_SELECTED);} else {
		insert = "<blockquote><p>"+selection+"</p><cite>"+nickname+"</cite></blockquote><hr/><br/><br/>";
	if (tinyMCE) {
		tinyMCE.execCommand("mceInsertContent", false, insert);
		tinyMCE.execCommand("mceRepaint");
	} else {
		$("input[name=text]").append(insert);
	}
	}
	
}
var REL_LANG_NO_TEXT_SELECTED = 'Не выбран текст!';