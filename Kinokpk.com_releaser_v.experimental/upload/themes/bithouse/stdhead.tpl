<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="{$REL_CONFIG.description}{$descradd}" />
<meta name="Keywords" content="{$keywordsadd}{$REL_CONFIG.keywords}" />
<base href="{$REL_CONFIG.defaultbaseurl}/" />
<title>{$title} | {$REL_CONFIG.sitename}</title>
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>
{if !$CURUSER || $CURUSER.extra_ef}
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->
{/if}
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.history.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.cookie.js"></script>
<script language="javascript" type="text/javascript"
	src="js/facebox.min.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.jgrowl_minimized.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.scrollTo-min.js"></script>
<script language="javascript" type="text/javascript" src="js/coding.js"></script>
<script language="javascript" type="text/javascript"
	src="js/features.js"></script>
<script language="javascript" type="text/javascript"
	src="js/swfobject.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.paginator3000.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
<script language="javascript" type="text/javascript" src="js/json.js"></script>
<link rel="stylesheet" href="css/features.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.facebox.css" type="text/css" />
<!--[if IE]>
<link rel="stylesheet" href="css/features_ie.css" type="text/css"/>
<![endif]-->
<link rel="stylesheet"
	href="themes/{$REL_CONFIG.ss_uri}/{$REL_CONFIG.ss_uri}.css"
	type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS"
	href="{$REL_SEO->make_link('rss')}" />
<link rel="alternate" type="application/atom+xml" title="Atom"
	href="{$REL_SEO->make_link('atom')}" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

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
{if $OFFLINE}
<h1>Site offline!</h1>
{/if}
<!-------------98%---------------->
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
  <tr>
        <td width="10" nowrap="nowrap" class="lefttd"></td>
        <td width="100%">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
							    <td align="middle"  valign="middle" bgcolor="#FFFFFF" background="/themes/bithouse/images/logo-ver.jpg"><a href="index.php"><img src="/themes/bithouse/images/logo3.gif" align="left" border="0" hspace="0"></a></td>

								


<td  align="middle"  bgcolor="#FFFFFF" background="/themes/bithouse/images/logo-ver.jpg">
<!---тут может быть реклама
<center></center><p>
тут может быть реклама--->
<!-- BOF xImperia.com CODE //-->
<div align=center></div>
<!-- EOF xImperia.com CODE //-->


</td>
                        </tr>
                        </table>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">


              <td class="navpic" width="100%" nowrap align="left">

<div align=center>
<center>
<font color=red><font size="1">
<b>
{if !$CURUSER}<a href="{$REL_SEO->make_link('login')}">{$REL_LANG->_('Login')}</a>
		  • {/if}
		  <a href="{$REL_SEO->make_link('index')}">{$REL_LANG->_('Main')}</a>
		  • 
		  <a href="{$REL_SEO->make_link('browse')}">{$REL_LANG->_('Releases')}</a>
		  		  {if get_privilege('upload_releases',false)}
		  • 

		  <a href="{$REL_SEO->make_link('upload')}">{$REL_LANG->_('Upload')}</a>{/if}
		  • 
		  {if get_privilege('view_logs',false)}
		  <a href="{$REL_SEO->make_link('log')}">{$REL_LANG->_('View site log')}</a>
		  • {/if}
		  <a href="{$REL_SEO->make_link('forum')}">{$REL_LANG->_('Forum')}</a>
		  • 
		    <a href="{$REL_SEO->make_link('chat')}"><font size="1"><b><u>{$REL_LANG->_('Chat')}</b></u></font></a>
		  • 
		  <a href="javascript:alert('Еще не реализовано, простите!');">Доска Почёта</a>
		  • 
		  <a href="{$REL_SEO->make_link('staff')}">{$REL_LANG->say_by_key('staff')}</a>

{if $CURUSER}
• 
		  <a href="{$REL_SEO->make_link('message')}">{$REL_LANG->_('PM')}</a>
• 
		  <a href="{$REL_SEO->make_link('my')}">{$REL_LANG->_('Account settings')}</a>
          • 
          {if get_privilege('access_to_admincp',false)}
          		  <a href="{$REL_SEO->make_link('admincp')}">{$REL_LANG->say_by_key('Admin')}</a>
          		  {/if}
          • 
		  <a href="{$REL_SEO->make_link('logout')}">{$REL_LANG->say_by_key('logout')}</a>
{/if}	
		  </b>
		  </center>
              </font>
			  </font>
			  </div>
			  </td>
			  <td align=right class="navpic" width="100%" nowrap>		
<form method="get" action="{$REL_SEO->make_link('browse')}"><input type="text" name="search" size="20"><input type="hidden" name=incldead value="1"> <input type="submit" value="{$REL_LANG->_('Search')}!"></form>
			  </td>
                </table>

<!---<br><center>тут может быть реклама</center>--->

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
      <tr valign="top">
        <td><img src="/themes/bithouse/images/7px.gif" width="1" height="1" border="0" alt=""></td>
</tr></table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
<tr valign="top">
  <td valign="top" align="center" width="1" background="/themes/bithouse/images/7px.gif">

  <tr>
    <td valign="top" height="100%">
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td valign="top" width="180">

          <!--- block panel start here -->
{show_blocks('l')}
                             <!--- block panel finish here -->

          </td>
          <td valign="top" align="center">
{include file='begin_frame.tpl'}
<!---тут может быть реклама--->
{show_blocks('t')}
<!---тут может быть реклама--->
