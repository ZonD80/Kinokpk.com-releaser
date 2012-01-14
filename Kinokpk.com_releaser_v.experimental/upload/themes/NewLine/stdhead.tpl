<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="{$REL_CONFIG.description}{$descradd}" />
<meta name="Keywords" content="{$keywordsadd}{$REL_CONFIG.keywords}" />
<base href="{$REL_CONFIG.defaultbaseurl}/" />
<title>{$title}</title>

      <link rel="stylesheet" href="themes/{$REL_CONFIG.ss_uri}/main.css" type="text/css"/>
 <link rel="stylesheet" href="css/features.css" type="text/css" />
        <link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css" />
        <link rel="stylesheet" href="css/jquery.facebox.css" type="text/css" />
 <link rel="stylesheet" href="css/keyboard.css" type="text/css" />

        <link rel="alternate" type="application/rss+xml" title="RSS" href="{$REL_SEO->make_link('rss')}" />
        <link rel="alternate" type="application/atom+xml" title="Atom" href="{$REL_SEO->make_link('atom')}" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />



<script type="text/javascript" src="themes/full_social/js/jquery.js?v=1.3.2"></script>
<script type="text/javascript" src="themes/full_social/js/common.js?v=2.0"></script>
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>
{if !$CURUSER || $CURUSER.extra_ef}
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->
{/if}
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.history.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.cookie.js"></script>
<script language="javascript" type="text/javascript" src="js/facebox.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.jgrowl_minimized.js"></script>
<script language="javascript" type="text/javascript" src="js/coding.js"></script>
<script language="javascript" type="text/javascript" src="js/features.js"></script>
<script language="javascript" type="text/javascript" src="js/swfobject.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.paginator3000.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
<script language="javascript" type="text/javascript" src="js/json.js"></script>
<script type="text/javascript" language="javascript" src="js/script.js"></script>











        {$headadd}















  



  </head>
  <body>
    <table width="100%" height="85" class="topp" border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td>
              <a href="{$REL_CONFIG.defaultbaseurl}/">
             <img align="left" hspace="0" style="border: none;padding-left:20px;" alt="{$REL_CONFIG.sitename}" title="{$REL_CONFIG.sitename}" src="./themes/NewLine/images/logo.png" />
            </a>
          </td>
        </tr>
      </tbody>
    </table>
    <table width="100%" height="37" class="menutop" border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td width="118" height="39" class="menufirst" align="left"></td>
          <td height="39" align="center" valign="top">
            <li>
           <a href="{$REL_CONFIG.defaultbaseurl}/">{$REL_LANG->say_by_key('homepage')}</a>
            </li>
            
            <li>
            <a href="{$REL_SEO->make_link('forum')}">{$REL_LANG->say_by_key('forum')}</a>
            </li>
            <li>
             <a href="{$REL_SEO->make_link('browse')}">{$REL_LANG->say_by_key('browse')}</a>
            </li>
            <li>
              <a href="{$REL_SEO->make_link('upload')}">{$REL_LANG->say_by_key('upload')}</a>
            </li>
            <li>
            <a href="{$REL_SEO->make_link('chat')}">{$REL_LANG->say_by_key('chat')}</a>
            </li>
           
  {if get_privilege('view_logs',false)}
	  <!--li>	  <a href="{$REL_SEO->make_link('log')}">{$REL_LANG->_('View site log')}</a>     </li-->
		  {/if}
		
		

		
		  
		 
		<li>  <a href="{$REL_SEO->make_link('staff')}">{$REL_LANG->say_by_key('staff')}</a>     </li>













            
          </td>
          <td width="12" align="left">
            <img src="themes/NewLine/images/searchr.jpg" width="7" tooltip="I" height="39">
          </td>
          <td align="left" width="201" height="39" class="search">
          <form method="get" action="browse.php">
  <input type="text" name="search" class="inp" value="{$REL_LANG->say_by_key('search')} ..." onblur="if(this.value=='') this.value='{$REL_LANG->say_by_key('search')} ...';" onfocus="if(this.value=='{$REL_LANG->say_by_key('search')} ...') this.value='';">
  <input type="image" src="themes/NewLine/images/t.gif" class="btun" value="{$REL_LANG->say_by_key('search')}">
</form>
          </td>
        </tr>
      </tbody>
    </table>
    <table width="100%" height="13" class="menutop2" border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td width="118" height="13" class="menusecond" align="left"></td>
        </tr>
      </tbody>
    </table>
    <table width="100%" class="conten" border="0" cellspacing="0" cellpadding="0">
   
<tr>
<td width="229" align="left" valign="top" style="padding-left:15px;">
       





{if !$CURUSER}
<table width="229" class="nob" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="52" align="center" valign="top" class="blt">
        <p>{$REL_LANG->_('Login')}/{$REL_LANG->_('Registration')}</p>
      </td>
    </tr>
    <tr>
      <td class="blc" valign="top" align="left">
<center><form method="post" action="takelogin.php"><br />{$REL_LANG->_('Username')}: <br /><input type="text" size=20 name="email" /><br />{$REL_LANG->_('Password')}: <br /><input type="password" size=20 name="password" /><br />
<input type="submit" value="{$REL_LANG->_('Login')}!" class=\"btn\"><br /></form><a href="{$REL_SEO->make_link('signup')}">{$REL_LANG->_('Registrer now!')}</a></center><br /><br />

 </td>
    </tr>
    <tr>
      <td height="28" class="blf"></td>
    </tr>
  </tbody>
</table>




<br/>
{/if}










        {show_blocks('l')}   
     


{if $CURUSER}
<table width="229" class="nob" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td height="52" align="center" valign="top" class="blt">
        <p>{$REL_LANG->say_by_key('messages')}</p>
      </td>
    </tr>
    <tr>
      <td class="blc" valign="top" align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="5"><tr>
<td class="imgblock"><img class="images" src="./themes/{$REL_CONFIG.ss_uri}/images/inbox.png" align="top" alt="" /></td><td class="stblock"><a href="{$REL_SEO->make_link('message')}">{$REL_LANG->say_by_key('inbox')}</a></td>
</tr><tr>
<td class="imgblock"><img class="images" src="./themes/{$REL_CONFIG.ss_uri}/images/outbox.png" align="top" alt="" /></td><td class="stblock"><a href="{$REL_SEO->make_link('message','action','viewmailbox','box',-1)}">{$REL_LANG->say_by_key('outbox')}</a></td></tr></table>
</td>
    </tr>
    <tr>
      <td height="28" class="blf"></td>
    </tr>
  </tbody>
</table>




<br/>
{/if}














</td>


<td valign="top" class="bc" align="center" style="padding-right:5px;">
         


 {show_blocks('t')}   










