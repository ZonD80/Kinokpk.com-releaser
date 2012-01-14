<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="{$REL_CONFIG.description}{$descradd}" />
<meta name="Keywords" content="{$keywordsadd}{$REL_CONFIG.keywords}" />
<base href="{$REL_CONFIG.defaultbaseurl}/" />
<title>{$title} | {$REL_CONFIG.sitename}</title>

      <link rel="stylesheet" href="themes/{$REL_CONFIG.ss_uri}/main.css" type="text/css"/>
<link rel="stylesheet" href="css/features.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.facebox.css" type="text/css"/>
<link rel="stylesheet" href="css/link/jquery.linkselect.style.select.css" type="text/css"/>
<!--[if IE]>
<link rel="stylesheet" href="css/features_ie.css" type="text/css"/>
<![endif]-->
<link rel="alternate" type="application/rss+xml" title="RSS" href="{$REL_SEO->make_link('rss')}" />
<link rel="alternate" type="application/atom+xml" title="Atom" href="{$REL_SEO->make_link('atom')}" />
<link rel="shortcut icon" href="themes/{$REL_CONFIG.ss_uri}/images/favicon.ico" type="image/x-icon" />
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>
{if !$CURUSER || $CURUSER.extra_ef}
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->
{/if}
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.scrollTo-min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.cookie.js"></script>
<script language="javascript" type="text/javascript" src="js/facebox.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.jgrowl_minimized.js"></script>
<script language="javascript" type="text/javascript" src="js/coding.js"></script>
<script language="javascript" type="text/javascript" src="js/features.js"></script>
<script language="javascript" type="text/javascript" src="js/swfobject.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.paginator3000.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
{$headadd}
<!--[if IE 7]>
		<script type="text/javascript">  
			var IE6UPDATE_OPTIONS = {
				icons_path: "pic/ie6update/"
			}
			</script>
		<script type="text/javascript" src="js/ie6update.js"></script>
		<![endif]-->

{generate_lang_js()}

     </head>
      <body>
<table width="100%" class="clear" align="center" border="none" cellspacing="0" cellpadding="0" style="background: transparent;">



<tr>
  <td align="middle" valign="middle" bgcolor="#FFFFFF" background="/themes/releaser2009/images/pin.png" style="border:none;">
    <a href="{$REL_CONFIG.defaultbaseurl}/"><img align="left" hspace="0" style="border: none;padding-left:20px;" alt="{$REL_CONFIG.sitename}" title="{$REL_CONFIG.sitename}" src="./themes/releaser2009/images/logo.png" /></a>
  </td>
  <td align="middle" bgcolor="#FFFFFF" background="/themes/releaser2009/images/pin.png" style="border:none">


















  </td>
</tr>
</table>

<!-- Top Navigation Menu for unregistered-->
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" >
<tr><td class=menu width="100%" nowrap="" align="left" style="border:none">

<div id='topmenu'><ul>



		  <a href="{$REL_SEO->make_link('index')}">{$REL_LANG->_('Main')}</a>
		 
		  <a href="{$REL_SEO->make_link('browse')}">{$REL_LANG->_('Releases')}</a>
		  		  {if get_privilege('upload_releases',false)}
		 

		  <a href="{$REL_SEO->make_link('upload')}">{$REL_LANG->_('Upload')}</a>{/if}
		  
		  {if get_privilege('view_logs',false)}
		  <a href="{$REL_SEO->make_link('log')}">{$REL_LANG->_('View site log')}</a>
		  {/if}
		  <a href="{$REL_SEO->make_link('forum')}">{$REL_LANG->_('Forum')}</a>
		
	 <a href="{$REL_SEO->make_link('chat')}">{$REL_LANG->_('Chat')}</a>
		
		  
		 
		  <a href="{$REL_SEO->make_link('staff')}">{$REL_LANG->say_by_key('staff')}</a>












</ul></div>



</td>

		
<td align="right" class="menu" width="100%" nowrap="" style="padding-right:15px;padding-top:2px;background-image: url(themes/releaser2009/images/bar.jpg);border:none;">
<form method="get" action="{$REL_SEO->make_link('browse')}"><input type="text" name="search" size="35"><input type="hidden" name=incldead value="1"> <input type="submit" value="{$REL_LANG->_('Search')}!"></form>
</td>
		

</tr></table>



<div class="AllContent" style="margin-top:5px;">
<table class="mainouter" align="center"  "width="100%" border="0" cellspacing="0" cellpadding="5" >


<div id="columns">
<td class="tt" valign="top" width="180px">
{if !$CURUSER}
<table width="180" border="0" cellspacing="0" cellpadding="0"><tr><td class="block">
        <table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
<td class="block" width="100%" align="center" style="background: url(themes/releaser2009/images/cellpic.gif);" height="24"><font class="block-title"><strong>{$REL_LANG->_('Login')}/{$REL_LANG->_('Registration')}</strong></font></td></tr></table>
        <table width="100%" border="0" cellspacing="1" cellpadding="3"><tr>
        <td align="left" style="border: 1px solid #d8dfea; ">   
<center><form method="post" action="takelogin.php"><br />{$REL_LANG->_('Username')}: <br /><input type="text" size=20 name="email" /><br />{$REL_LANG->_('Password')}: <br /><input type="password" size=20 name="password" /><br />
<input type="submit" value="{$REL_LANG->_('Login')}!" class=\"btn\"><br /></form><a href="{$REL_SEO->make_link('signup')}">{$REL_LANG->_('Registrer now!')}</a></center><br /><br />

</td>
        </tr></table>
</td></tr></table><br/>
{/if}

{show_blocks('l')}




{if $CURUSER}
<table width="180" border="0" cellspacing="0" cellpadding="0"><tr><td class="block">
        <table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
<td class="block" width="100%" align="center" style="background: url(themes/releaser2009/images/cellpic.gif);" height="24"><font class="block-title"><strong>{$REL_LANG->say_by_key('messages')}</strong></font></td></tr></table>
        <table width="100%" border="0" cellspacing="1" cellpadding="3"><tr>
        <td align="left" style="border: 1px solid #d8dfea; ">   
<table border="0" width="100%" cellspacing="0" cellpadding="5"><tr>
<td class="imgblock"><img class="images" src="./themes/{$REL_CONFIG.ss_uri}/images/inbox.png" align="top" alt="" /></td><td class="stblock"><a href="{$REL_SEO->make_link('message')}">{$REL_LANG->say_by_key('inbox')}</a></td>
</tr><tr>
<td class="imgblock"><img class="images" src="./themes/{$REL_CONFIG.ss_uri}/images/outbox.png" align="top" alt="" /></td><td class="stblock"><a href="{$REL_SEO->make_link('message','action','viewmailbox','box',-1)}">{$REL_LANG->say_by_key('outbox')}</a></td></tr></table>
</td>
        </tr></table>
</td></tr></table><br/>
{/if}















</td>
<td valign="top" class="tt" style="padding-top: 5px; padding-bottom: 5px; width:100%">


 {show_blocks('t')}


















