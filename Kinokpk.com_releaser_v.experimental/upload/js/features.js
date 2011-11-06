jQuery(document).ready(
function(){
	initSpoilers();
	init_js();

	jQuery("#trailer_body a.close").bind('click',function(e){
		e.preventDefault();
		jQuery("div#trailer_body").hide();
		
		});
});


function initSpoilers(context) {
	var context = context || 'body';
	jQuery('div.sp-head', jQuery(context))
		.click(function(){
		    var ctx = jQuery(this).next('div.sp-body');
			var code = ctx.children('textarea').text();
			if (code) {
			    ctx.children('textarea').replaceWith(code);
			    initSpoilers(ctx);
			}
			jQuery(this).toggleClass('unfolded');
            jQuery(this).next('div.sp-body').slideToggle('slow');
            jQuery(this).next('div.sp-body').next().slideToggle('slow');
		});
}
 
function init_js() {
jQuery(function() {
  jQuery('a[href!="http://"]').each(
    function(){
            if((this.href.indexOf(location.hostname) == -1) && (this.href.indexOf('javascript') == -1) && (this.href.indexOf('magnet') == -1) && (!this.onclick))
            {
            	jQuery(this).addClass('external');//.attr('target', '_blank');
            	this.href='away.php?url='+this.href;
            }
    })
  });

 jQuery('img').error(function() {
        jQuery(this).attr({
        src: 'pic/imgmiss.gif'
        });
    });
 
 jQuery('#toggle-all').click(
		   function()
		   {
		      jQuery("INPUT[type='checkbox']").attr('checked', jQuery('#toggle-all').is(':checked'));   
		   }
		);
}

function rateit(rid,rtype,rate) {

      field = "#ratearea-"+rid+"-"+rtype;

jQuery(field).each(
    function(){
   jQuery(this).empty();
   jQuery(this).append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
}
);

    jQuery.get("rate.php", { ajax: 1, id: rid, type: rtype, act: rate}, function(data){
   jQuery(field).each(
    function(){
   jQuery(this).empty();
   jQuery(this).append(data);
}
);
});

return false;

}

function notifyme(cid,id,type,act) {
      if (jQuery) no_ajax = false;
      field = "#notifarea-"+id;
   jQuery(field).empty();
   jQuery(field).append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
    jQuery.get("notifs.php", { ajax: 1, cid: cid, id: id, type: type, action: act}, function(data){
   jQuery(field).empty();
   jQuery(field).append(data);
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
		jQuery("input[name=text]").append(insert);
	}
	jQuery.scrollTo("#rel_wysiwyg");
	}
	
}

function delete_comment(id) {
	sure = confirm(REL_LANG_ARE_YOU_SURE);
	if (!sure) return false;
	jQuery.get('comments.php',{action: 'delete', 'cid[]': [id] },function(data){
		jQuery("#comm"+id).html('<h1>'+data+'</h1>');
		jQuery("#comm"+id).fadeOut('slow');
		});
	return false;
}

function send_comment(type,to_id) {
	jQuery("#submit_button").attr('disabled', true);
	if (tinyMCE) {
		tinyMCE.triggerSave();
		content = tinyMCE.activeEditor.getContent();
	} else content = jQuery("input[name=text]").val();
	jQuery('<div id="loading" align="center"><img src="pic/loading.gif" border="0"/></div>').insertAfter("#newcomment_placeholder").slideDown('slow');
	jQuery.post('comments.php?action=add&type='+type,{to_id: to_id, text: content}, function (data) {
	jQuery('#loading').hide();
	jQuery('#newcomment_placeholder').slideUp('slow');
	jQuery('#loading').html(data).slideDown('slow');
	jQuery('#loading').removeAttr('id');
	//jQuery("#old").before('<div id="newcomment_placeholder" style="visible:none;"></div>');
	});
	if (tinyMCE) {
		 tinyMCE.activeEditor.setContent('');
	} else jQuery("input[name=text]").val('');
	jQuery("#submit_button").removeAttr('disabled');
	if (jQuery('#pager_scrollbox'))
	jQuery('#pager_scrollbox').scrollTo(jQuery('#newcomment_placeholder'),800);
	return false;
}
var REL_LANG_NO_TEXT_SELECTED = 'Не выбран текст!';
var REL_LANG_ARE_YOU_SURE = 'Вы верены?';