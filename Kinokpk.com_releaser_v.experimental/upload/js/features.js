$(document).ready(
function(){
	initSpoilers();
	init_js();

	$("#trailer_body a.close").bind('click',function(e){
		e.preventDefault();
		$("div#trailer_body").hide();
		
		});
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
            if((this.href.indexOf(location.hostname) == -1) && (this.href.indexOf('javascript') == -1) && (this.href.indexOf('magnet') == -1) && (!this.onclick))
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
 
 $('#toggle-all').click(
		   function()
		   {
		      $("INPUT[type='checkbox']").attr('checked', $('#toggle-all').is(':checked'));   
		   }
		);
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
	$.scrollTo("#rel_wysiwyg");
	}
	
}

function delete_comment(id) {
	sure = confirm(REL_LANG_ARE_YOU_SURE);
	if (!sure) return false;
	$.get('comments.php',{action: 'delete', 'cid[]': [id] },function(data){
		$("#comm"+id).html('<h1>'+data+'</h1>');
		$("#comm"+id).fadeOut('slow');
		});
	return false;
}

function send_comment(type,to_id) {
	$("#submit_button").attr('disabled', true);
	if (tinyMCE) {
		tinyMCE.triggerSave();
		content = tinyMCE.activeEditor.getContent();
	} else content = $("input[name=text]").val();
	$('<div id="loading" align="center"><img src="pic/loading.gif" border="0"/></div>').insertBefore("#newcomment_placeholder").slideDown('slow');
	$.post('comments.php?action=add&type='+type,{to_id: to_id, text: content}, function (data) {
	$('#loading').hide();
	$('#newcomment_placeholder').slideUp('slow');
	$('#loading').html(data).slideDown('slow');
	$('#loading').removeAttr('id');
	//$("#old").before('<div id="newcomment_placeholder" style="visible:none;"></div>');
	});
	if (tinyMCE) {
		 tinyMCE.activeEditor.setContent('');
	} else $("input[name=text]").val('');
	$("#submit_button").removeAttr('disabled');
	return false;
}
var REL_LANG_NO_TEXT_SELECTED = 'Не выбран текст!';
var REL_LANG_ARE_YOU_SURE = 'Вы верены?';