<form name="comment" method="post" action="{$REL_SEO->make_link('comments','action','add','type',$FORM_TYPE)}" onsubmit="return send_comment('{$FORM_TYPE}',{$to_id});">
<table id="comment_form" style="margin-top: 2px;" cellpadding="5" width="100%">
<tr><td class=colhead align="left" colspan="2"><div id="comments"></div><b>:: {$REL_LANG->_('Add comment to %s',$FORM_TYPE_LANG)}|  {$is_i_notified}</b></td></tr>
<tr><td width="100%" align="center">
<tr><td align="center">{$textbbcode}</td></tr>
<tr><td align="center">
<input type="hidden" name="to_id" value="{$to_id}"/>
<input type="submit" id="sumbit_button" value="{$REL_LANG->_('Submit comment')}" />
</td></tr></table></form>