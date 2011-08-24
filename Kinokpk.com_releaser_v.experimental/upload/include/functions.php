<?php
/**
 * General functions
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if(!defined("IN_TRACKER") && !defined("IN_ANNOUNCE")) die("Direct access to this page not allowed");

/**
 * Pager in ajax-scroolbox
 * @param integer $perpage Number of entries per page
 * @param integer $count Total number of entries
 * @param array $hrefarray Array to be used in link
 * @see $REL_SEO->make_link();
 * @param string $el_id ID of element data will be appended to
 * @param int $timeout JS function timeout
 */
function ajaxpager($perpage=25,$count,$hrefarray,$el_id,$timeout=500) {
	global $REL_SEO,$REL_TPL, $REL_LANG;
	$page = (int)$_GET['page'];
	$maxpage = floor($count/$perpage);
	$hrefarray[] = 'AJAXPAGER';
	$hrefarray[] = '1';
	$hrefarray[] = 'page';
	$hrefarray[] = '%number%';
	$href = call_user_func_array(array($REL_SEO, 'make_link'),$hrefarray);

	if (!$page) {
	$REL_TPL->assign("AJAXPAGER", "
	<script type=\"text/javascript\">
	var CURR_PAGE = 0;
	var MAX_PAGE = $maxpage;
	var PAGER_HREF = \"$href\";
	$('document').ready(function(){
	$('#pager_scrollbox').after('<div align=\"center\"><input type=\"button\" id=\"pager_button\" value=\"{$REL_LANG->_('Show more')}\" onclick=\"javascript:do_pager();\"/></div>');
});
	function do_pager() {
	if (CURR_PAGE>=MAX_PAGE) { $('#pager_button').val('{$REL_LANG->_('This is an end')}'); $('#pager_button').attr('disabled','disabled'); return false; }
		$('#pager_button').val('{$REL_LANG->_('Loading')}...');
		$('#pager_button').attr('disabled','disabled');
		CURR_PAGE=CURR_PAGE+1;
		page = PAGER_HREF.replace(/%number%/i,CURR_PAGE);

		$.get(page, '', function(newitems){
			$('#$el_id').append(newitems);
			$('#pager_button').removeAttr('disabled');
			$('#pager_button').val('{$REL_LANG->_('Show more')}');
		});
	}
</script>");
	
	return "LIMIT $perpage";
	} else {
		return "LIMIT ".($page*$perpage).",$perpage";
	}
}

/**
 * Checks username
 * @param string $username User name to be checked
 * @return boolean Valid or not:)
 */
function validusername($username)
{
	if ($username == "")
	return false;

	// The following characters are allowed in user names
	$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_.";

	for ($i = 0; $i < mb_strlen($username); ++$i)
	if (strpos($allowedchars, $username[$i]) === false)
	return false;

	return true;
}

/**
 * Function used to generate GMT timezones input=select
 * @param string $name name of input element
 * @param int $selected id of selected timezone
 * @return string code of input=select
 */
function list_timezones($name = 'timezone',$selected = 3) {
	$selected = $selected--;
	$timezones = explode("\n",'Eniwetok (GMT-12)
Samoa (GMT-11)
Hawaii (GMT-10)
Alaska (GMT-9)
Pacific Time (GMT-8)
Mountain Time (GMT-7)
Central Time (GMT-6)
Eastern Time (GMT-5)
Atlantic Time (GMT-4)
Brazilia (GMT-3)
Mid-Atlantic (GMT-2)
Azores (GMT-1)
Greenwich Mean Time (GMT)
Rome (GMT +1)
Israel (GMT +2)
Moscow (GMT +3)
Baghdad, Iraq (GMT +4)
New Delhi (GMT +5)
Dhakar (GMT +6)
Bangkok (GMT +7)
Hong Kong (GMT +8)
Tokyo (GMT +9)
Sydney (GMT +10)
Magadan (GMT +11)
Wellington (GMT +12)');
	$return = '<select id="'.$name.'" class="linkselect" name="'.$name.'">';
	for ($i=0;$i<=24;$i++) {
		$t = $i-12;
		$return .='<option value="'.($t).'"'.($t==$selected?" selected":'').'>'.$timezones[$i]."</option>\n";
	}
	$return .= '</select>';
	return $return;
}

/**
 * Default redirect function
 * @param string|array $url URL of redirection.
 * <code>
 * safe_redirect('index.php?id=300&view=deleted');
 * </code>
 * @param int|float $timeout timeout in seconds before redirection
 * @return void
 */
function safe_redirect($url,$timeout = 0) {
	$url = trim($url);
	/*if (REL_AJAX || ob_get_length())*/ print('
    <script type="text/javascript" language="javascript">
    function Redirect() {
      location.href = "'.addslashes($url).'";
      }
      setTimeout(\'Redirect()\','.($timeout*1000).');
    </script>
');
	//else header("Refresh: $timeout; url=$url");
	return;
}
/**
 * Generates area to rate user
 * @param int $currating Current user rating
 * @param int $currid Current user id
 * @param string $type Rating type
 * @return string Div element with user rating and arrows to change it
 */

function ratearea($currating,$currid,$type,$owner_id = 0) {
	global $CURUSER, $REL_LANG, $REL_CONFIG, $REL_SEO;
	if ($currating>0) $znak='+';
	$text='<strong>'.$znak.$currating.'</strong>';
	if (!$currid || !$CURUSER) return $text;

	if (!$_SESSION['already_rated']) {
		$allowed_types = array('torrents','users','relcomments','pollcomments','newscomments','usercomments','reqcomments','relgroups','rgcomments');
		foreach  ($allowed_types as $atype) $_SESSION['already_rated'][$atype] = array();

		$res = sql_query("SELECT rid,type FROM ratings WHERE userid={$CURUSER['id']}");
		while (list($rid,$rtype) = mysql_fetch_array($res)) {
			$_SESSION['already_rated'][$rtype][] = $rid;
		}
	}
	if (@in_array($currid,$_SESSION['already_rated'][$type]) || ($currid==$owner_id))
	return ('
	<div style="display:inline;" id="ratearea-'.$currid.'-'.$type.'" class="ratearea">
		&nbsp;'.$text.'
			<img class="arrowup_close" style="border:none;" src="pic/null.gif" title="'.$REL_LANG->say_by_key('rate_up').'"/>
			<img class="arrowdown_close" style="border:none;" src="pic/null.gif" title="'.$REL_LANG->say_by_key('rate_down').'"/>
		&nbsp;</div>');
	else return ('<div style="display:inline;" id="ratearea-'.$currid.'-'.$type.'" class="ratearea">&nbsp;'.$text.'<a href="'.$REL_SEO->make_link("rate","id",$currid,"type",$type,"act","up").'" onclick="return rateit('.$currid.',\''.$type.'\',\'up\');">
	<img class="arrowup" style="border:none;" src="pic/null.gif" title="'.$REL_LANG->say_by_key('rate_up').'"/></a><a href="'.$REL_SEO->make_link("rate","id",$currid,"type",$type,"act","down").'" onclick="return rateit('.$currid.',\''.$type.'\',\'down\');">
	<img class="arrowdown" style="border:none;" src="pic/null.gif" title="'.$REL_LANG->say_by_key('rate_down').'"/></a>&nbsp;</div>');

}

/**
 * Generates report area
 * @param int $id id of to be reported element
 * @param unknown_type $type report type
 * @return string report area with link to reporting script
 */
function reportarea($id,$type) {
	global $CURUSER, $REL_LANG, $REL_SEO;
	if (!$id || !$CURUSER) return '';
	if (!$_SESSION['already_reported']) {
		$allowed_types = array ('messages', 'torrents', 'users', 'comments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'relgroups', 'rgcomments');
		foreach ($allowed_types as $atype) $_SESSION['already_reported'][$atype] = array();
		$res = sql_query("SELECT reportid,type,motive FROM reports WHERE userid={$CURUSER['id']}");
		while (list($reportid,$rtype,$motive) = mysql_fetch_array($res)) {
			$_SESSION['already_reported'][$rtype][$reportid] = $motive;
		}
	}
	$text='<strong>'.$REL_LANG->say_by_key('you_already_reported').($motive?' '.$REL_LANG->say_by_key('report_reason').$motive:'').'</strong>';
	if (@array_key_exists($id,$_SESSION['already_reported'][$type])) return $text;
	else return ('&nbsp;<div style="display:inline;" id="reportarea-'.$id.'-'.$type.'">[<a class="altlink_white" href="'.$REL_SEO->make_link('report','id',$id,'type',$type).'">'.$REL_LANG->say_by_key('report_it').'</a>]&nbsp;</div>');

}

/**
 * Checks remote file size using headers
 * @param string $path URI of file/image/etc to be checked
 * @return int|boolean File size or false if not
 */
function remote_fsize($path)
{
	$fp = @fopen($path,"r");
	if (!$fp) return false;
	$inf = stream_get_meta_data($fp);
	fclose($fp);
	if ($inf["wrapper_data"]) {
		foreach($inf["wrapper_data"] as $v)
		if (stristr($v,"content-length"))
		{
			$v = explode(":",$v);
			return trim($v[1]);
		}
	} else return FALSE;
}

/**
 * Writes message to selected user from system
 * @param int $receiver id of receiver
 * @param string $msg message as it is
 * @param string $subject subject of message
 * @return void
 */
function write_sys_msg($receiver,$msg,$subject) {
	sql_query("INSERT INTO messages (receiver, added, msg, subject) VALUES($receiver, '" . time() . "', ".sqlesc($msg).", ".sqlesc($subject).")");// or sqlerr(__FILE__, __LINE__);
	return;
}

/**
 * HTTP auth in admincp, modtask, etc
 * @return void
 */
function httpauth(){
	global $CURUSER, $REL_LANG, $REL_SEO;

	if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
		$auth_params = explode(":" , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		$_SERVER['PHP_AUTH_USER'] = strip_tags($auth_params[0]);
		unset($auth_params[0]);
		$_SERVER['PHP_AUTH_PW'] = implode('',$auth_params);
	}

	if (($CURUSER['passhash'] != md5($CURUSER['secret'].$_SERVER["PHP_AUTH_PW"].$CURUSER['secret'])) || (($_SERVER['PHP_AUTH_USER']<>$CURUSER['email']) && ($_SERVER['PHP_AUTH_USER']<>$CURUSER['username']))) {
		if ($_SERVER["PHP_AUTH_PW"]) write_log(make_user_link()." at ".getip().", login/e-mail: {$_SERVER['PHP_AUTH_USER']} <font color=\"red\">ADMIN CONTROL PANEL Authentication FAILED</font>",'admincp_auth');

		header("WWW-Authenticate: Basic realm=\"Kinokpk.com releaser's admininsration panel. You can use email or username to login. All inputs are case-sensitive\"");
		header("HTTP/1.0 401 Unauthorized");
		stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'),'error');

	}
	return;
}
//functions_global:


/**
 * Alias to strip_tags
 * @param string $text Text to make safe
 * @return string
 */
function makesafe($text) {
	return cleanhtml(trim((string)$text));
}

/**
 * Generates WYSIWYG code
 */
function wysiwyg_init() {
	global $REL_CONFIG, $CURUSER;
	define ("WYSIWYG_REQUIRED",true);

	$lang = (($CURUSER['language']=='ua')?'uk':$CURUSER['language']);
	$return .= '
	<script type="text/javascript" src="js/tiny_mce/tiny_mce_gzip.js"></script>
<script language="javascript" type="text/javascript">
function wysiwygjs() {
tinyMCE_GZ.init({
	plugins : \'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,stamps,kinopoisk,\'+
        \'searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,spoiler,graffiti,reltemplates\',
	themes : \'advanced\',
	languages : \'ru, en\',
	disk_cache : true,
	gecko_spellcheck:"1",
	debug : false
}, function() { tinyMCE.init({
       forced_root_block : false,
   force_br_newlines : true,
   force_p_newlines : false,
		theme : "advanced",
plugins : \'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,stamps,kinopoisk,\'+
        \'searchreplace,'./*contextmenu,*/'print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,spoiler,reltemplates,graffiti\',
	languages : \''.$lang.'\',
	disk_cache : true,
	gecko_spellcheck:"1",
	debug : false,
   forced_root_block : false,
   force_br_newlines : true,
   force_p_newlines : false,
   theme : "advanced",
	content_css : "/themes/'.$REL_CONFIG['ss_uri'].'/'.$REL_CONFIG['ss_uri'].'.css",
    language: "'.$lang.'",
    	mode : "exact",
	elements : "rel_wysiwyg",
	template_replace_values : {
		username : "'.$CURUSER['username'].'"
	},
verify_html : true,';
	if (get_privilege('is_administrator',false)) {
		$return .= 'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,spoiler,stamps,graffiti,kinopoisk,reltemplates",
';
	} elseif (get_privilege('is_moderator',false)) {
		$return .= 'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup'./*,help,code*/',|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "'./*insertlayer,moveforward,movebackward,absolute,|,styleprops,|,*/'cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,'./*template,*/'blockquote,'./*pagebreak,*/'.|,spoiler,stamps,graffiti,kinopoisk,reltemplates",
  invalid_elements: "script,embed,iframe",
';
	} else $return .= 'theme_advanced_buttons1 : "newdocument,removeformat,|,bold,italic,underline,strikethrough,|,link,unlink,image,|,forecolor,hr,emotions,|,fullscreen,cite,blockquote,|,spoiler,stamps,graffiti,kinopoisk,reltemplates",
  invalid_elements: "script,embed,iframe",
      theme_advanced_buttons2 : "", 
    theme_advanced_buttons3 : "",
  theme_advanced_disable : "bullist,numlist,outdent,indent,undo,redo,anchor,cleanup,help,code,sub,sup,charmap,visualaid",
';
	$return .= '
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "center",
		theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        dialog_type:"modal",
        entities:"38,amp,60,lt,62,gt",
        //paste_remove_spans:"1", 
        paste_strip_class_attributes:"all"
});
});
}
</script>';

	return $return;
}
/**
 * Generates complete <u>HTML</u> input TinyMCE code
 * @param string $name name of textarea input tag
 * @param string $content contents to be added to texarea
 * @return string The code
 */
function textbbcode($name, $content="") {

	return '<textarea id="rel_wysiwyg" class="rel_wysiwyg" name="'.$name.'" cols="100" rows="10">'.$content.'</textarea>'.wysiwyg_init();
}

/**
 * Gets user data by given id
 * @param int $id ID of users
 * @param string $type Type of returned data, assoc (default) - associative array, array - array, object - object
 * @return Ambigous <mixed, boolean> Data or false
 */
function get_user($id,$type='assoc') {
	global $REL_DB;
	return $REL_DB->query_row("SELECT * FROM users WHERE id=$id",$type);
}

/**
 * Generates link to user profile.
 * @param unknown_type $data
 * @return string
 */
function make_user_link($data=false) {
	global $REL_SEO,$CURUSER;
	if (!$data) $data = $CURUSER;
	return '<a href="'.$REL_SEO->make_link('userdetails','id',$data['id'],'username',translit($data['username'])).'">'.get_user_class_color($data['class'],$data['username']).'</a>'.get_user_icons($data);
}

/**
 * Deletes tracker user
 * @param int $id id of user
 * @return void
 */
function delete_user($id) {
	global $CURUSER, $REL_SEO, $REL_DB;
	sql_query("DELETE FROM users WHERE id = $id");
	sql_query("DELETE FROM comments WHERE toid=$id AND type='user'") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM notifs WHERE type='usercomments' AND checkid=$id") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM messages WHERE receiver = $id OR sender = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM friends WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM friends WHERE friendid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM bookmarks WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM invites WHERE inviter = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM peers WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM presents WHERE presenter = $id OR userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM addedrequests WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM notifs WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM xbt_users WHERE uid = $id") or sqlerr(__FILE__,__LINE__);
	write_log(make_user_link()." <font color=\"red\">deleted user with id $id</font>",'system_functions');

	return;
}
/**
 * Gets row count as SELECT COUNT(*) FROM ...
 * @param string $table Table to be selected
 * @param string $suffix Options to select
 * @return int Count of rows
 */
function get_row_count($table, $suffix = "")
{
	if ($suffix)
	$suffix = " $suffix";
	($r = sql_query("SELECT SUM(1) FROM $table $suffix")) or die(mysql_error());
	($a = mysql_fetch_row($r)) or die(mysql_error());
	return $a[0]?$a[0]:0;
}
/**
 * Standart notification message
 * @param string $heading Heading of a message, e.g. subject
 * @param string $text Content of a message, e.g. body
 * @param string $div Div to be displayed, default 'success'
 * @param boolean $htmlstrip Strip html? Defalut false
 * @todo Deprecate
 * @return void
 */
function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
	global $REL_TPL;
	$REL_TPL->stdmsg($heading,$text,$div,$htmlstrip);
}

/**
 * Standart error message with die
 * @param string $heading Heading of a message, e.g. subject
 * @param string $text Content of a message, e.g. body
 * @param string $div Div to be displayed, default 'success'
 * @param boolean $htmlstrip Strip html? Defalut false
 * @return void
 * @see stdmsg()
 * @todo Deprecate
 */
function stderr($heading = '', $text = '', $div ='error', $htmlstrip = false) {
	global $REL_TPL;
	$REL_TPL->stderr($heading,$text,$div,$htmlstrip);
}

/**
 * Generates SQL error message sending notification to SYSOP
 * @param string $file File where error begins __FILE__
 * @param string $line Line where error begins __LINE__
 * @return void
 */
function sqlerr($file = '', $line = '') {
	global $queries, $CURUSER, $REL_SEO, $REL_LANG;
	$err = mysql_error();
	$text = ("<table border=\"0\" bgcolor=\"blue\" align=\"left\" cellspacing=\"0\" cellpadding=\"10\" style=\"background: blue\">" .
	"<tr><td class=\"embedded\"><font color=\"white\"><h1>{$REL_LANG->_('SQL error')}</h1>\n" .
	"<b>{$REL_LANG->_('SQL server response')}: " . $err . ($file != '' && $line != '' ? "<p>{$REL_LANG->_('in')} $file, {$REL_LANG->_('line')} $line</p>" : "") . "<p>{$REL_LANG->_('Query number')}: $queries.</p></b></font></td></tr></table>");
	write_log(make_user_link()." SQL ERROR: $text</font>",'sql_errors');
	print $text;
	return;
}

/**
 * Adds nums of ages in russian
 * @param int $age Value of ages
 * @return string Ages and 'years old' in russian
 */
function AgeToStr($age)
{
	global $REL_LANG;
	if(($age>=5) && ($age<=14)) $str = $REL_LANG->_('years');
	else {
		$num = $age - (floor($age/10)*10);

		if($num == 1) { $str = $REL_LANG->_('year'); }
		elseif($num == 0) { $str = $REL_LANG->_('years'); }
		elseif(($num>=2) && ($num<=4)) { if (getlang()!='en') $str = "года"; else $str=$REL_LANG->_('years');  }
		elseif(($num>=5) && ($num<=9)) { if (getlang()!='en') $str = "лет"; else $str=$REL_LANG->_('years'); }
	}
	return $age . " " . $str ;
}

/**
 * Gets all images from text
 * @param string $file Text to be processed
 * @return array|NULL Array of images' uris or NULL
 */
function get_images($file){
	$pattern = '/<img.*? src=[\'"]?([^\'" >]+)[\'" >]/';
	preg_match_all($pattern, $file, $matches);

	return $matches[1];
}

/**
 * Cleans html code using HTMLawed
 * @param string $code Text to be processed
 * @return string The cleaned html code
 */
function cleanhtml($code) {
	$config = array(
	//'safe'=>1, // Dangerous elements and attributes thus not allowed
    'comments'=>1,
    'cdata'=>1,
	'valid_xhtml'=>1,
    'deny_attribute'=>'on*',
    'elements'=>'*-applet'./*-embed*/'-iframe'./*-object*/'-script', //object, embed allowed for youtube video
    'scheme'=>'href: aim, feed, file, ftp, gopher, http, https, irc, mailto, news, nntp, sftp, ssh, telnet; style: nil; *:file, http, https'
    );
    $spec = 'a = title, href;'; // The 'a' element can have only these attributes
    /*$images = get_images($code);

    if ($images)
    {
    $host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
    $host = str_replace('.','\.',$host);
    foreach ($images as $key => $image) {

    if (preg_match('/"?(http:\/\/(?!(www\.|)'.$host.')([^">\s]*))/ie',$image)) {
    $img = @fopen($image, "r");
    if (!$img) {$bb[] = $images[$key]; $html[] = 'pic/disabled.gif'; } else fclose($img);
    }
    }
    }
    if ($bb)
    $code = str_replace($bb,$html,$code);*/

    return htmLawed($code, $config, $spec);
}


/**
 * Formats [spoiler]text[/spoiler] tag
 * @param string $text Text to be prosessed
 * @return string Processed html code
 */
function encode_spoiler($text) {
	global $REL_LANG;
	$replace = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">{$REL_LANG->_("Spoiler")}</div><div class=\"sp-body\"><textarea id=\"spoiler\" rows=\"10\" cols=\"60\">\\1</textarea></div></div>";
	$text = preg_replace("#\[spoiler\](.*?)\[/spoiler\]#si", $replace, $text);
	return $text;
}

/**
 * Formats [spoiler=description]text[/spoiler] tag
 * @param string $text Text to be prosessed
 * @return string Processed html code
 */
function encode_spoiler_from($text) {
	global $REL_LANG;
	$replace = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">\\1</div><div class=\"sp-body\"><textarea id=\"spoiler\" rows=\"10\" cols=\"60\">\\2</textarea></div></div>";
	$text = preg_replace("#\[spoiler=(.+?)\](.*?)\[/spoiler\]#si", $replace, $text);
	return $text;
}

/**
 * Parses html code using cleanhtml
 * @param string $text Text to be processed
 * @return string The html code
 * @see cleanhtml()
 * @see get_images()
 */
function format_comment($text,$nospoiler = false) {
	global $REL_CONFIG, $REL_CACHE, $bb,$html, $REL_LANG, $REL_SEO;

	$text = cleanhtml($text);

	while (preg_match("#\[spoiler\](.*?)\[/spoiler\]#si", $text))
	if ($nospoiler) $text = preg_replace("#\[spoiler\](.*?)\[/spoiler\]#si", "<i>{$REL_LANG->_("Spoiler")}</i><br/>\\1", $text);
	else
	$text = encode_spoiler($text);
	while (preg_match("#\[spoiler=(.+?)\](.*?)\[/spoiler\]#si", $text))
	if ($nospoiler)
	$text = preg_replace("#\[spoiler=(.+?)\](.*?)\[/spoiler\]#si","<i>\\1</i><br/>\\2",$text);
	else
	$text = encode_spoiler_from($text);
	return $text;

}

/**
 * Checks that argument is id
 * @param mixed $id Argument to be checked
 * @return boolean
 */
function is_valid_id($id) {
	return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

/**
 * Gets ratio color
 * @param float $ratio
 * @return string Colored ratio
 */
function get_ratio_color($ratio) {
	if ($ratio < 0.1) return "#ff0000";
	if ($ratio < 0.2) return "#ee0000";
	if ($ratio < 0.3) return "#dd0000";
	if ($ratio < 0.4) return "#cc0000";
	if ($ratio < 0.5) return "#bb0000";
	if ($ratio < 0.6) return "#aa0000";
	if ($ratio < 0.7) return "#990000";
	if ($ratio < 0.8) return "#880000";
	if ($ratio < 0.9) return "#770000";
	if ($ratio < 1) return "#660000";
	return "#000000";
}

/**
 * Gets share ratio color (seeders/leechers)
 * @param float $ratio Ratio to be parsed
 * @return string Colored ratio
 */
function get_slr_color($ratio) {
	if ($ratio < 0.025) return "#ff0000";
	if ($ratio < 0.05) return "#ee0000";
	if ($ratio < 0.075) return "#dd0000";
	if ($ratio < 0.1) return "#cc0000";
	if ($ratio < 0.125) return "#bb0000";
	if ($ratio < 0.15) return "#aa0000";
	if ($ratio < 0.175) return "#990000";
	if ($ratio < 0.2) return "#880000";
	if ($ratio < 0.225) return "#770000";
	if ($ratio < 0.25) return "#660000";
	if ($ratio < 0.275) return "#550000";
	if ($ratio < 0.3) return "#440000";
	if ($ratio < 0.325) return "#330000";
	if ($ratio < 0.35) return "#220000";
	if ($ratio < 0.375) return "#110000";
	return "#000000";
}

/**
 * Writes event to sitelog
 * @param stirng $text Message to be writed to log
 * @param string $type Type of log record, default 'tracker'
 * @return void
 */
function write_log($text, $type = "tracker") {
	global $CURUSER;
	if (!$CURUSER['id']) $id =0; else $id=$CURUSER['id'];

	//
	$type = sqlesc($type);
	$text = sqlesc($text);
	$added = time();
	sql_query("INSERT INTO sitelog (added, userid, txt, type) VALUES($added, $id, $text, $type)") or sqlerr(__FILE__,__LINE__);
	return;
}

/**
 * Check that email is banned and dies if is
 * @param string $email Email to be checked
 * @return void
 * @see stderr()
 */
function check_banned_emails ($email) {
	global $REL_TPL, $REL_LANG;
	$expl = explode("@", $email);
	$wildemail = "*@".$expl[1];
	$res = sql_query("SELECT id, comment FROM bannedemails WHERE email = ".sqlesc($email)." OR email = ".sqlesc($wildemail)."") or sqlerr(__FILE__, __LINE__);
	if ($arr = mysql_fetch_assoc($res))
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('This email address was banned due the following reason: <b>%s</b>',$arr[comment]), false);
	return;
}

/**
 * Gets nice elapsed time
 * @param string $U UNIX-style date
 * @param boolean $showseconds Show seconds? Default true
 * @return string Nice elapsed time
 */
function get_elapsed_time($U,$showseconds=true){
	$N = time();
	if ($N>=$U)
	$diff = $N-$U;
	else
	$diff = $U-$N;
	//year (365 days) = 31536000
	//month (30 days) = 2592000
	//week = 604800
	//day = 86400
	//hour = 3600

	if($diff>=31536000){
		$Iyear = floor($diff/31536000);
		$diff = $diff-($Iyear*31536000);
	}
	if($diff>=2629800){    //2592000 seconds in month with 30 days
		$Imonth = floor($diff/2629800);
		$diff = $diff-($Imonth*2629800);
	}
	if($diff>=604800){
		$Iweek = floor($diff/604800);
		$diff = $diff-($Iweek*604800);
	}
	if($diff>=86400){
		$Iday = floor($diff/86400);
		$diff = $diff-($Iday*86400);
	}
	if($diff>=3600){
		$Ihour = floor($diff/3600);
		$diff = $diff-($Ihour*3600);
	}
	if($diff>=60){
		$Iminute = floor($diff/60);
		$diff = $diff-($Iminute*60);
	}
	if($diff>0){
		$Isecond = floor($diff);
	}

	$j = " ";

	$ret = "";

	if(isset($Iyear)) $ret .= $Iyear." ".rusdate($Iyear,'year').$j;
	if(isset($Imonth)) $ret .= $Imonth ." ".rusdate($Imonth ,'month').$j;
	if(isset($Iweek)) $ret .= $Iweek ." ".rusdate($Iweek ,'week').$j;
	if(isset($Iday)) $ret .= $Iday ." ".rusdate($Iday ,'day').$j;
	if(isset($Ihour)) $ret .= $Ihour ." ".rusdate($Ihour ,'hour').$j;
	if(isset($Iminute)) $ret .= $Iminute ." ".rusdate($Iminute ,'minute').$j;

	//    if($showseconds==false && $Iminute<1)$Iminute=0;
	if($showseconds==false && $Iminute<1 && $Ihour<1 && $Iday<1 && $Iweek<1 && $Imonth<1 && $Iyear<1)return rusdate(0 ,'minute');

	if(($Isecond>0 OR $ret=="") AND $showseconds==true){
		if($ret=="" AND !isset($Isecond))$Isecond=0;
		$ret .= $Isecond ." ".rusdate($Isecond ,'second').$j;
	}
	return $ret;
}

/**
 * Return nice russian date
 * @param int $num Undocumented
 * @param string $type Undocumented
 * @return string Nice russian date
 */
function rusdate($num,$type){
	$rus = array (
        "year"    => array( "лет", "год", "года", "года", "года", "лет", "лет", "лет", "лет", "лет"),
        "month"  => array( "месяцев", "месяц", "месяца", "месяца", "месяца", "месяцев", "месяцев", "месяцев", "месяцев", "месяцев"),
        "week"  => array( "недель", "неделю", "недели", "недели", "недели", "недель", "недель", "недель", "недель", "недель"),
        "day"   => array( "дней", "день", "дня", "дня", "дня", "дней", "дней", "дней", "дней", "дней"),
        "hour"    => array( "часов", "час", "часа", "часа", "часа", "часов", "часов", "часов", "часов", "часов"),
        "minute" => array( "минут", "минуту", "минуты", "минуты", "минуты", "минут", "минут", "минут", "минут", "минут"),
        "second" => array( "секунд", "секунду", "секунды", "секунды", "секунды", "секунд", "секунд", "секунд", "секунд", "секунд"),
	);

	$num = intval($num);
	if ( 10 < $num && $num < 20) return $rus[$type][0];
	return $rus[$type][$num % 10];
}

/**
 * Just alias to $REL_DB->sql_query();
 * @param string $query Query to be performed
 * @return resource Mysql resource
 * @todo Deprecate this shit!
 */
function sql_query($query) {
	global $REL_DB;
	return $REL_DB->query($query);
}

/**
 * Initializes all required stuff for Kinokpk.com releaser operation
 * @param boolean $lightmode Does not begin user session or not. Default false
 * @see user_session()
 * @see userlogin()
 */
function INIT($lightmode = false) {
	global $REL_CONFIG,$REL_CACHE,$REL_CRON,$REL_DB,$REL_SEO,$REL_TPL;
	// configcache init

	/* @var array Array of releaser's configuration */
	$REL_CONFIG=$REL_CACHE->get('system','config');
	//$REL_CONFIG=false;
	if ($REL_CONFIG===false) {

		$REL_CONFIG = array();

		$cacherow = sql_query("SELECT * FROM cache_stats");

		while ($cacheres = mysql_fetch_array($cacherow))
		$REL_CONFIG[$cacheres['cache_name']] = $cacheres['cache_value'];

		$REL_CACHE->set('system','config',$REL_CONFIG);
	}
	$REL_CONFIG['lang'] = getlang();
	//configcache init end
	$cronrow = sql_query("SELECT * FROM cron");
	/* @var array cron array init */
	while ($cronres = mysql_fetch_array($cronrow))
	$REL_CRON[$cronres['cron_name']] = $cronres['cron_value'];

	/* @var object links parser/adder/changer for seo */
	require_once(ROOT_PATH . 'classes/seo/seo.class.php');
	$REL_SEO = new REL_SEO();
	if (!$lightmode) userlogin();

	require_once(ROOT_PATH . 'classes/template/template.class.php');
	/* @var REL_TPL template class */
	$REL_TPL = new REL_TPL($REL_CONFIG);

	gzip();

	// INCLUDE SECURITY BACK-END
	require_once(ROOT_PATH . 'include/ctracker.php');
	/**
	 * This is original copyright, please leave it alone. Remember, that the Developers worked hard for weeks, drank ~67 litres of a beer (hoegaarden and baltica 7) and ate more then 15.1 kilogrammes of hamburgers to present this source. Don't be evil (C) Google
	 * @var constant Copyright of Kinokpk.com releaser
	 */
	define ("TBVERSION", ($REL_CONFIG['yourcopy']?str_replace("{datenow}",date("Y"),$REL_CONFIG['yourcopy']).". ":"")."<br />Powered by <a class=\"copyright\" target=\"_blank\" href=\"http://www.kinokpk.com\">Kinokpk.com</a> <a class=\"copyright\" target=\"_blank\" href=\"http://dev.kinokpk.com\">releaser</a> ".RELVERSION." &copy; 2008-".date("Y").".");

	return;
}

/**
 * Gets current language setting
 * @return string 2-char language code
 */
function getlang() {
	global $REL_CONFIG;
		$return = substr(trim((string)$_COOKIE['lang']),0,2);
	if (!$return) $return = $REL_CONFIG['default_language'];
	return $return;
}
/**
 * Logins user
 * @return void
 */
function userlogin() {
	global $REL_LANG, $REL_CONFIG, $REL_CACHE, $REL_LANG, $CURUSER, $REL_CRON;
	unset($GLOBALS["CURUSER"]);
	/* @var object language system */
	require_once(ROOT_PATH . 'classes/lang/lang.class.php');
	$ip = getip();

	if ($REL_CONFIG['use_ipbans']) {

		$maskres = $REL_CACHE->get('bans', 'query');
		if ($maskres ===false){
			$res = sql_query("SELECT mask FROM bans");
			$maskres = array();

			while (list($mask) = mysql_fetch_array($res))
			$maskres[] = $mask;

			$REL_CACHE->set('bans', 'query', $maskres);
		}

		$BAN = new IPAddressSubnetSniffer($maskres);
		if ($BAN->ip_is_allowed($ip) ) {
			//write_log("$ip attempted to access tracker",'bans');
			die("Sorry, you (or your subnet) are banned by IP and MAC addresses!");

		}

	}

	if (empty($_COOKIE["uid"]) || empty($_COOKIE["pass"])) {
		$REL_CONFIG['ss_uri'] = $REL_CONFIG['default_theme'];
		$REL_LANG = new REL_LANG($REL_CONFIG);
		user_session();
		return;
	}

	if (!is_valid_id($_COOKIE["uid"]) || mb_strlen($_COOKIE["pass"]) != 32) {
		die("FATAL ERROR: Cokie ID invalid or cookie pass hash problem.");

	}
	$id = (int) $_COOKIE["uid"];
	$res = sql_query("SELECT users.*, xbt_users.torrent_pass AS passkey, stylesheets.uri FROM users LEFT JOIN xbt_users ON users.id=xbt_users.uid LEFT JOIN stylesheets ON users.stylesheet = stylesheets.id WHERE users.id = $id AND confirmed=1");// or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	if (!$row) {
		$REL_CONFIG['ss_uri'] = $REL_CONFIG['default_theme'];
		$REL_LANG = new REL_LANG($REL_CONFIG);
		make_zodiac_records($REL_LANG);
		user_session();
		return;
	} elseif ((!$row['enabled']) && !defined("IN_CONTACT")) {
		if ($row['language']<>$REL_CONFIG['lang']) {
			sql_query("UPDATE users SET language=".sqlesc($REL_CONFIG['lang'])." WHERE id={$row['id']}");
			$row['language'] = $REL_CONFIG['lang'];
		}
		$REL_CONFIG['ss_uri'] = $row['uri'];
		$REL_LANG = new REL_LANG($REL_CONFIG);
		make_zodiac_records($REL_LANG);
		headers(true);
		die($REL_LANG->say_by_key('disabled').$row['dis_reason'].(($row['dis_reason']=='Your rating was too low.')?$REL_LANG->say_by_key('disabled_rating'):'').$REL_LANG->say_by_key('contact_admin'));

	}
	if ($row['language']<>$REL_CONFIG['lang']) {
		sql_query("UPDATE users SET language=".sqlesc($REL_CONFIG['lang'])." WHERE id={$row['id']}");
		$row['language'] = $REL_CONFIG['lang'];
	}
	$sec = hash_pad($row["secret"]);
	if ($_COOKIE["pass"] != md5($row["passhash"].COOKIE_SECRET)) {
		$REL_CONFIG['ss_uri'] = $row['uri'];
		$pscheck = htmlspecialchars(trim((string)$_COOKIE['pass']));
		write_log(getip()." with cookie ID = $id <font color=\"red\">with passhash ".$pscheck." -> PASSHASH CHECKSUM FAILED!</font>",'security');
		$REL_LANG = new REL_LANG($REL_CONFIG);
		make_zodiac_records($REL_LANG);
		user_session();
		return;
	}
	if (!$REL_CONFIG['ss_uri']) $REL_CONFIG['ss_uri'] = $row['uri'];

	$updateset = array();


	if ($ip != $row['ip'])
	$updateset[] = 'ip = '. sqlesc($ip);
	$updateset[] = 'last_access = ' . time();

	if (count($updateset))
	sql_query("UPDATE LOW_PRIORITY users SET ".implode(", ", $updateset)." WHERE id=" . $row["id"]);// or die(mysql_error());
	$row['ip'] = $ip;

	if (isset($_COOKIE['override_class'])) {
		$override = (int)$_COOKIE['override_class'];
		if (get_class_priority($override)<get_class_priority($row['class']) && $override>=0)
		$row['class'] = $override;
	}
	
	// Array creation
	$row['notifs'] = explode(',',$row['notifs']);
	$row['emailnotifs'] = explode(',',$row['emailnotifs']);
	$row['custom_privileges'] = explode(',',$row['custom_privileges']);
	
	/* @var array Not full yet array of variables of current user
	 * @see $REL_TPL->stdhead()
	 */
	$GLOBALS["CURUSER"] = $row;
	$REL_LANG = new REL_LANG($REL_CONFIG);
	make_zodiac_records($REL_LANG);

	user_session();

	// $_SESSION = $CURUSER;

}

/**
 * Generates zodiac records for current language
 * @param complex $REL_LANG Language object
 */
function make_zodiac_records($REL_LANG) {
	global $zodiac;
	$zodiac[] = array($REL_LANG->_('Capricorn'), "capricorn.gif", "22-12");
$zodiac[] = array($REL_LANG->_('Sagittarius'), "sagittarius.gif", "23-11");
$zodiac[] = array($REL_LANG->_('Scorpio'), "scorpio.gif", "24-10");
$zodiac[] = array($REL_LANG->_('Libra'), "libra.gif", "24-09");
$zodiac[] = array($REL_LANG->_('Virgo'), "virgo.gif", "24-08");
$zodiac[] = array($REL_LANG->_('Leo'), "leo.gif", "23-07");
$zodiac[] = array($REL_LANG->_('Cancer'), "cancer.gif", "22-06");
$zodiac[] = array($REL_LANG->_('Gemini'), "gemini.gif", "22-05");
$zodiac[] = array($REL_LANG->_('Taurus'), "taurus.gif", "21-04");
$zodiac[] = array($REL_LANG->_('Aries'), "aries.gif", "22-03");
$zodiac[] = array($REL_LANG->_('Pisces'), "pisces.gif", "21-02");
$zodiac[] = array($REL_LANG->_('Aquarius'), "aquarius.gif", "21-01");
$GLOBALS['zodiac'] = $zodiac;
}

/**
 * Gets unix server load
 * @return number|string
 */
function get_server_load() {
	global $REL_LANG, $phpver;
	if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
		return 0;
	} elseif (@file_exists("/proc/loadavg")) {
		$load = @file_get_contents("/proc/loadavg");
		$serverload = explode(" ", $load);
		$serverload[0] = round($serverload[0], 4);
		if(!$serverload) {
			$load = @exec("uptime");
			$load = preg_split("#load averages?: #si", $load);
			$serverload = explode(",", $load[1]);
		}
	} else {
		$load = @exec("uptime");
		$load = preg_split("load averages?: ", $load);
		$serverload = explode(",", $load[1]);
	}
	$returnload = trim($serverload[0]);
	if(!$returnload) {
		$returnload = $REL_LANG->say_by_key('unknown');
	}
	return $returnload;
}

/**
 * Begins user session
 * @return void
 */
function user_session() {
	global $CURUSER, $REL_CONFIG, $REL_CRON;

	$ip = getip();
	$url = htmlspecialchars($_SERVER['REQUEST_URI']);

	if (!$CURUSER) {
		$uid = -1;
		$username = '';
		$class = -1;
	} else {
		$uid = $CURUSER['id'];
		$username = $CURUSER['username'];
		$class = $CURUSER['class'];
	}

	$past = time() - 300;
	$sid = session_id();
	//	$where = array();
	$updateset = array();
	if ($sid) {
		/*	$where[] = "sid = ".sqlesc($sid);
		 elseif ($uid)
		 $where[] = "uid = $uid";
		 else
		 $where[] = "ip = ".sqlesc($ip);*/
		//sql_query("DELETE FROM sessions WHERE ".implode(" AND ", $where));
		$ctime = time();
		$agent = htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
		$updateset[] = "sid = ".sqlesc($sid);
		$uid = (int)$uid;
		$updateset[] = "uid = ".$uid;
		$updateset[] = "username = ".sqlesc($username);
		$class = (int)$class;
		$updateset[] = "class = ".$class;
		$updateset[] = "ip = ".sqlesc($ip);
		$updateset[] = "time = ".$ctime;
		$updateset[] = "url = ".sqlesc($url);
		$updateset[] = "useragent = ".sqlesc($agent);
		if (count($updateset))
		//	sql_query("UPDATE sessions SET ".implode(", ", $updateset)." WHERE ".implode(" AND ", $where)) or sqlerr(__FILE__,__LINE__);
		sql_query("INSERT INTO sessions (sid, uid, username, class, ip, time, url, useragent) VALUES (".implode(", ", array_map("sqlesc", array($sid, $uid, $username, $class, $ip, $ctime, $url, $agent))).") ON DUPLICATE KEY UPDATE ".implode(", ", $updateset)) or sqlerr(__FILE__,__LINE__);
	}
	if ($CURUSER) {

		$allowed_types = array ('torrents', 'relcomments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments','forumcomments','friends');//,'seeding','leeching','downloaded');
		if (get_privilege('is_moderator',false)) {
			$allowed_types_moderator = array('users', 'reports', 'unchecked');
			$allowed_types = array_merge($allowed_types,$allowed_types_moderator);
		}
		$allowed_types=array_merge(array('unread', 'inbox', 'outbox'),array_intersect($allowed_types,$CURUSER['notifs']));

		$secs_system = $REL_CRON['pm_delete_sys_days']*86400;
		$dt_system = time() - $secs_system;
		$secs_all = $REL_CRON['pm_delete_user_days']*86400;
		$dt_all = time() - $secs_all;

		foreach ($allowed_types as $type) {
			if ($type=='torrents') {
				$addition = " AND moderatedby<>0";
			}
			elseif ($type=='unread'){
				$addition = "location=1 AND receiver={$CURUSER['id']} AND unread=1 AND IF(archived_receiver=1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))"; $table='messages'; $noadd=true;
			}
			elseif ($type=='inbox'){
				$addition = "location=1 AND receiver={$CURUSER['id']} AND IF(archived_receiver=1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))"; $table='messages'; $noadd=true;
			}
			elseif ($type=='outbox'){
				$addition = "saved=1 AND sender={$CURUSER['id']} AND IF(archived_receiver<>1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))"; $table = 'messages'; $noadd=true;
			}
			elseif ($type=='unchecked'){
				$addition = 'moderatedby=0'; $table = 'torrents'; $noadd=true;
			}
			elseif ($type=='reports') $noadd=true;
			elseif ($type=='friends') {
				$noadd=true; $addition = "friendid={$CURUSER['id']} AND confirmed=0";
			}
			elseif($type=='forumcomments') {
				$addition = " AND type = 'forum' AND FIND_IN_SET(".get_user_class().",forum_categories.class)"; $table = 'comments LEFT JOIN forum_topics ON forum_topics.id=comments.toid LEFT JOIN forum_categories ON forum_topics.category=forum_categories.id';
				$sel_id = 'comments.';
			}
			elseif (in_array($type,array('relcomments','pollcomments','newscomments',
			'usercomments','reqcomments','rgcomments'))) {
			$addition = " AND type = '".str_replace('comments','',$type)."'"; $table = 'comments';
			}

			//case 'seeding' : $addition = "seeder=1 AND userid={$CURUSER['id']}"; $table= 'peers'; $noadd=true; break;
			// case 'leeching' : $addition = "seeder=0 AND userid={$CURUSER['id']}"; $table= 'peers'; $noadd=true; break;
			// case 'downloaded' : $addition = "snatched.finished=1 AND torrents.free=0 AND NOT FIND_IN_SET(torrents.freefor,userid) AND userid={$CURUSER['id']}"; $table = 'peers'; $noadd=true; break;

			$noselect = @implode(',',@array_map("intval",$_SESSION['visited_'.$type]));

			$string = ($noselect?$sel_id.'id NOT IN ('.$noselect.') AND ':'').($noadd?'':"{$sel_id}added>".$CURUSER['last_login']).$addition;

			$sql_query[]="(SELECT GROUP_CONCAT({$sel_id}id) FROM ".($table?$table:$type).($string?" WHERE $string":'').') AS '.$type;
			unset($addition);
			unset($sel_id);
			unset($table);
			unset($noadd);
			unset($string);
			unset($noselect);
		}

		if ($sql_query) {
			$sql_query = "SELECT ".implode(', ', $sql_query);

			//die($sql_query);
			$notifysql = sql_query($sql_query);
			$notifs = mysql_fetch_assoc($notifysql);
			foreach ($notifs as $type => $value) if ($value) $CURUSER[$type] = explode(',', $value);
			//$notifs = array_combine($allowed_types,explode(',',$notifs));
			//foreach ($notifs as $name => $value) $CURUSER[$name] = $value;
		}

	}
	return;
}

/**
 * Unescapes a string
 * @param string $x String to be unescaped
 * @return string Unescaped string
 */
function unesc($x) {
	$x = trim($x);

	return $x;
}

/**
 * Starts gzip compressor
 * @return void
 */
function gzip() {
	global $REL_CONFIG;
	if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler' && $REL_CONFIG['use_gzip']) {
		@ob_start('ob_gzhandler');
	} else @ob_start();
	return;
}

// IP Validation
/**
 * Validates user ip
 * @param string $ip ip to be validated
 * @return boolean
 */
function validip($ip) {
	if (!empty($ip) && $ip == long2ip(ip2long($ip)))
	{
		// reserved IANA IPv4 addresses
		// http://www.iana.org/assignments/ipv4-address-space
		$reserved_ips = array (
		array('0.0.0.0','2.255.255.255'),
		array('10.0.0.0','10.255.255.255'),
		array('127.0.0.0','127.255.255.255'),
		array('169.254.0.0','169.254.255.255'),
		array('172.16.0.0','172.31.255.255'),
		array('192.0.2.0','192.0.2.255'),
		array('192.168.0.0','192.168.255.255'),
		array('255.255.255.0','255.255.255.255')
		);

		foreach ($reserved_ips as $r) {
			$min = ip2long($r[0]);
			$max = ip2long($r[1]);
			if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
		}
		return true;
	}
	else return false;
}

/**
 * Gets user ip
 * @return string user ip
 */
function getip() {
	$ip = false;
	if(!empty($_SERVER['HTTP_CLIENT_IP']))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if($ip)
		{
			array_unshift($ips, $ip);
			$ip = false;
		}
		for($i = 0; $i < count($ips); $i++)
		{
			if(!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i]))
			{
				if(version_compare(phpversion(), "5.0.0", ">="))
				{
					if(ip2long($ips[$i]) != false)
					{
						$ip = $ips[$i];
						break;
					}
				}
				else
				{
					if(ip2long($ips[$i]) != - 1)
					{
						$ip = $ips[$i];
						break;
					}
				}
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * Make nice size from bytes
 * @param int $bytes Amout of bytes to be processed
 * @return string Nice-sized bytes
 */
function mksize($bytes) {
	if ($bytes < 1000 * 1024)
	return number_format($bytes / 1024, 2) . " kB";
	elseif ($bytes < 1000 * 1048576)
	return number_format($bytes / 1048576, 2) . " MB";
	elseif ($bytes < 1000 * 1073741824)
	return number_format($bytes / 1073741824, 2) . " GB";
	else
	return number_format($bytes / 1099511627776, 2) . " TB";
}

function mksizeint($bytes) {
	$bytes = max(0, $bytes);
	if ($bytes < 1000)
	return floor($bytes) . " B";
	elseif ($bytes < 1000 * 1024)
	return floor($bytes / 1024) . " kB";
	elseif ($bytes < 1000 * 1048576)
	return floor($bytes / 1048576) . " MB";
	elseif ($bytes < 1000 * 1073741824)
	return floor($bytes / 1073741824) . " GB";
	else
	return floor($bytes / 1099511627776) . " TB";
}

/**
 * Makes nice time
 * @param int $seconds UNIX time
 * @param boolean $time Show time or only date, default true
 * @return string Nice time
 */
function mkprettytime($seconds, $time = true) {
	global $CURUSER, $REL_CONFIG, $REL_LANG;
	$seconds = $seconds+$REL_CONFIG['site_timezone']*3600;
	$seconds = $seconds-date("Z")+$CURUSER['timezone']*3600;
	$search = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$replace = array($REL_LANG->_('January'),
	$REL_LANG->_('February'),
	$REL_LANG->_('March'),
	$REL_LANG->_('April'),
	$REL_LANG->_('May'),
	$REL_LANG->_('June'),
	$REL_LANG->_('July'),
	$REL_LANG->_('August'),
	$REL_LANG->_('September'),
	$REL_LANG->_('October'),
	$REL_LANG->_('November'),
	$REL_LANG->_('December'));
	if ($time == true)
	$data = @date("j F Y {$REL_LANG->_('at')} H:i:s", $seconds);
	else
	$data = @date("j F Y", $seconds);
	if (!$data) $data = 'N/A'; else
	$data = str_replace($search, $replace, $data);
	return $data;
}

/**
 * Makes global vars as normal.
 * <code>
 * mkglobal('test'); ->> $_POST['test'] becoming $test
 * </code>
 * @param string|array $vars vars' names, separated by : or array of vars' names
 * @return number|number
 */
function mkglobal($vars) {
	if (!is_array($vars))
	$vars = explode(":", $vars);
	foreach ($vars as $v) {
		if (isset($_GET[$v]))
		$GLOBALS[$v] = unesc($_GET[$v]);
		elseif (isset($_POST[$v]))
		$GLOBALS[$v] = unesc($_POST[$v]);
		else
		return false;
	}
	return true;
}

/**
 * Outputs TR element of a table
 * @param string $x left column name
 * @param string $y right column name
 * @param boolean $noesc Does not strip html? Default false
 * @param boolean $prints Undocumented
 * @param string $width Width
 * @param string $relation Relation
 * @return void
 */
function tr($x, $y, $noesc=false, $prints = true, $width = "", $relation = '') {
	if ($noesc)
	$a = $y;
	else {
		$a = htmlspecialchars($y);
		$a = str_replace("\n", "<br />\n", $a);
	}
	if ($prints) {
		$print = "<td width=\"". $width ."\" class=\"heading\" valign=\"top\" align=\"right\">$x</td>";
		$colpan = "align=\"left\"";
	} else {
		$colpan = "colspan=\"2\"";
	}

	print("<tr".( $relation ? " relation=\"$relation\"" : "").">$print<td valign=\"top\" $colpan>$a</td></tr>\n");
	return;
}

function div($x, $y, $noesc=false, $id = "" ,$class = "", $prints = true, $relation = '') {
	if ($noesc)
	$a = $y;
	else {
		$a = htmlspecialchars($y);
		$a = str_replace("\n", "<br />\n", $a);
	}
	if ($prints) {
		$print = "<div id=\"". $id ."\">$x</div>";
		//	$colpan = "align=\"left\"";
	} /*else {
	$colpan = "colspan=\"2\"";
	}*/

	print("<div id=\"".$class."\">$print<div class=\"". $class ."\">$a</div></div>");
	return;
}
/**
 * Validates filename
 * @param string $name Filename to be processed
 * @return number Something as true and NULL as false
 */
function validfilename($name) {
	return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
}

/**
 * Validates email
 * @param string $email
 * @return boolean
 */
function validemail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL)?true:false;
}

/**
 * Converts local urls to external using site url
 * @param string $link Text contains uris to be converted
 * @return string Converted text
 */
function convert_local_urls($link) {
	global $REL_CONFIG;
	return preg_replace ( "#\\<a(.*?)href(\\s*)\\=(\\s*)(\"|')(?!http://)(.*?)(\"|')(.*?)\\>#si", "<a\\1href=\\4" . $REL_CONFIG['defaultbaseurl'].'/' . "\\5\\6\\7>", $link );
}
/**
 * Sends email message(s)
 * @param string $to receiver email
 * @param string $fromname sender name
 * @param string $fromemail sender email
 * @param string $subject subject of message
 * @param string $body body of message, excluding <html> and <body> tags
 * @param string $multiplemail Multiple receivers mail adresses separated by comma
 * @todo Normal SMTP functionality
 * @return boolean True or false while sending email
 */
function sent_mail($to,$fromname,$fromemail,$subject,$body,$multiplemail='') {
//return true;
	global $REL_CONFIG;
	require_once ROOT_PATH."classes/mail/dSendMail2.inc.php";
	$m = new dSendMail2;
	$m->setCharset('utf-8');
	$m->setSubject($subject);
	$m->setFrom($fromemail,$REL_CONFIG['sitename']);
	$m->setMessage($body);
	if ($multiplemail) {
		return true;
		$m->setTo($REL_CONFIG['siteemail']);
		$m->setBcc($multiplemail);
		$m->groupAmnt = 10;
		$m->delay     = 1;
	} else $m->setTo($to);
	return $m->send();
}

/**
 * Escapes value to make safe sql_query
 * @param string $value Value to be escaped
 * @return string Escaped value
 * @see sql_query()
 */
function sqlesc($value) {
	// Quote if not a number or a numeric string
	if (!is_numeric($value)) {
		$value = "'" . mysql_real_escape_string((string)$value) . "'";
	}
	return $value;
}

/**
 * Escapes value making search query.
 * <code>
 * sqlwildcardesc ('The 120% alcohol');
 * </code>
 * @param string $x Value to be escaped
 * @return string Escaped value
 */
function sqlwildcardesc($x) {
	return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}

/**
 * Send default headers depending on jquery.ajax
 * @param boolean $ajax Send headers for ajax? Default false
 */
function headers($ajax=false) {
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");
	if ($ajax)   header ("Content-Type: text/html; charset=utf-8");
	return;
}

/**
 * Checks that page is loading with ajax and defines boolean constant REL_AJAX
 */
function ajaxcheck() {
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') define ("REL_AJAX",true); else define("REL_AJAX",false);
	return;
}

/**
 * Checks that ajax pager is running
 */
function pagercheck() {
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'&& $_GET["AJAXPAGER"]) return true; else return false;
}

function run_cronjobs() {
	global $REL_CRON,$REL_SEO;
	if ($REL_CRON['cron_is_native']) {
		$time = time();
		if (((($time-$REL_CRON['last_cleanup'])>$REL_CRON['autoclean_interval']) && !$REL_CRON['in_cleanup'])) print '<img width="0px" height="0px" alt="" title="" src="'.$REL_SEO->make_link('cleanup').'"/>';
		if (!$REL_CRON['remotecheck_disabled'] && (($time-$REL_CRON['last_remotecheck'])>$REL_CRON['remotecheck_interval'])) print '<img width="0px" height="0px" alt="" title="" src="'.$REL_SEO->make_link('remote_check').'"/>';
	}
}
/**
 * Generates debug
 */
function debug() {
	global $CURUSER, $REL_LANG, $REL_CONFIG, $REL_CRON, $REL_SEO,$REL_TPL, $REL_DB, $tstart;

	if (($REL_CONFIG['debug_mode']) && (get_privilege('view_sql_debug',false))) {
		//var_dump($REL_DB->query);
		$REL_TPL->assignByRef('query',$REL_DB->query);
		//$REL_TPL->assignByRef('tstart',$tstart);
		$seconds = (microtime(true) - $tstart);
		$display_debug=true;

		$phptime = 		$seconds - $REL_DB->query[0]['seconds'];
		$query_time = 	$REL_DB->query[0]['seconds'];
		$percentphp = 	number_format(($phptime/$seconds) * 100, 2);
		$percentsql = 	number_format(($query_time/$seconds) * 100, 2);
		$REL_TPL->assignByRef('REL_CRON',$REL_CRON);
		$REL_TPL->assign('PAGE_GENERATED',((get_privilege('view_sql_debug',false))?sprintf($REL_LANG->say_by_key("page_generated"), $seconds, count($REL_DB->query), $percentphp, $percentsql):''));
	} else $display_debug=false;
	$REL_TPL->assign('DEBUG',$display_debug);
	//var_dump($display_debug);
}

function generate_post_javascript() {
	if (defined("WYSIWYG_REQUIRED") && !defined("NO_WYSIWYG"))
	print '<script language="javascript" type="text/javascript">
	$(document).ready(
		function(){
			wysiwygjs();
		});
</script>';
}

/**
 * Closes old sessions
 */
function close_sessions() {
	// close old sessions
	$secs = 1 * 3600;
	$time = time();
	$dt = $time - $secs;
	$updates = sql_query("SELECT uid, time FROM sessions WHERE uid<>-1 AND time < $dt") or sqlerr(__FILE__,__LINE__);
	while ($upd = mysql_fetch_assoc($updates)) {
		sql_query("UPDATE users SET last_login={$upd['time']} WHERE id={$upd['uid']}") or sqlerr(__FILE__,__LINE__);
	}
	sql_query("DELETE FROM sessions WHERE time < $dt") or sqlerr(__FILE__,__LINE__);
}


/**
 * Generates array of notifications (just counting it) (newly creaded items since last user visit)
 * @return array of notification data: a[total] - total count of notivs a[notifs] - associative array of notifications
 */
function generate_notify_array()
{
	global $CURUSER,$REL_LANG,$REL_SEO;

	$allowed_types = $CURUSER['notifs'];
	$return['total'] = 0;
	$return['notifs'] = array();
	if ($allowed_types) {
		foreach ($allowed_types AS $type) {

			$temp = (int)count(array_diff((array)$CURUSER[$type], (array)$_SESSION['visited_'.$type]));
			if ($temp) {
				$return['total'] += $temp;

				$return['notifs'][$type] = $temp;
			}

		}
	}
	return $return;
}

/**
 * Function to generate ratio popup warning
 * @param boolean $blockmode NO-Javascript static code? Default false
 * @return void|string HTML code with javascript
 */
function generate_ratio_popup_warning($blockmode = false) {
	global $CURUSER, $REL_CRON, $REL_LANG, $REL_SEO;

	if (!$CURUSER) return;

	if (!$REL_CRON['rating_enabled']) return;
	if ($_COOKIE['denynotifs'] && !$blockmode) return;
	if (!$CURUSER['downloaded_rel'] && !$CURUSER['seeding']) {
		$query = sql_query("SELECT (SELECT SUM(1) FROM peers WHERE seeder=1 AND userid={$CURUSER['id']}) AS seeding, (SELECT SUM(1) FROM snatched LEFT JOIN torrents ON snatched.torrent=torrents.id WHERE snatched.finished=1 AND torrents.free=0 AND NOT FIND_IN_SET(torrents.freefor,userid) AND userid={$CURUSER['id']} AND torrents.owner<>{$CURUSER['id']}) AS downloaded");

		list($seeding,$downloaded) = mysql_fetch_array($query);
		$CURUSER['seeding'] = (int)$seeding;
		$CURUSER['downloaded_rel'] = (int)$downloaded;
	}

	if ($CURUSER['seeding'] && ((time()-$CURUSER['added'])>($REL_CRON['rating_freetime']*86400)) && (!get_privilege('is_vip',false)) && $CURUSER['downloaded_rel'] && (($CURUSER['seeding']+$CURUSER['discount'])<$CURUSER['downloaded_rel'])) {

		$znak = (($CURUSER['ratingsum']>0)?'+':'');

		$output = ($blockmode?'<hr/>':'').sprintf($REL_LANG->say_by_key('ratio_down'),$znak.$CURUSER['ratingsum']);
		$output .= "<br/><br/>{$REL_LANG->_("How to increase rating? Just start seeding!")}<br/>".$REL_LANG->_('You can download all previous releases in one ZIP-archive without rating decrease<br/><a href="%s">View downloaded releases</a> or <a href="%s">Download ZIP-archive with torrents</a>',$REL_SEO->make_link('userhistory','id',$CURUSER['id'],'type','downloaded'),$REL_SEO->make_link('download','a','my'));
		$output .= "<br/><p style=\"text-align:right;\">{$REL_LANG->_('Go to <a href="%s">My rating stats</a> or <a href="%s">exchange rating to discount</a>',$REL_SEO->make_link('myrating'),$REL_SEO->make_link('myrating','discount',1))}</p>";
		return ($blockmode?$output:'<script type="text/javascript" language="javascript">$.jGrowl("'.addslashes($output).'", { header: "'.addslashes($REL_LANG->say_by_key('ratio_warning')).'", sticky:true });</script>');

	} else return;
}

/**
 * Makes user salt/secret
 * @param int $length Length of secret. Default 20
 * @return string
 */
function mksecret($length = 20) {
	$set = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
	$str;
	for($i = 1; $i <= $length; $i++)
	{
		$ch = rand(0, count($set)-1);
		$str .= $set[$ch];
	}
	return $str;
}

/**
 * Sets user cookies
 * @param int $id id of user
 * @param string $passhash passhash of user
 * @param string $language language for user
 * @param boolean $updatedb update last_access for user. Default true
 * @param int|hex $expires manual expire date. default never
 * @return void
 */
function logincookie($id, $passhash, $language, $updatedb = true, $expires = 0x7fffffff) {
	setcookie("uid", $id, $expires);
	setcookie("pass", md5($passhash.COOKIE_SECRET), $expires);
	setcookie("lang", $language, $expires);

	if ($updatedb)
	sql_query("UPDATE users SET last_access = ".time()." WHERE id = $id") or sqlerr(__FILE__,__LINE__);
	return;
}

/**
 * Kills user session and unsets authorization cookies
 */
function logoutcookie() {
	setcookie("uid", "", 0x7fffffff);
	setcookie("pass", "", 0x7fffffff);
	setcookie("lang", "", 0x7fffffff);
	unset($_SESSION);
	return;
}

/**
 * Checks that user logged in. If not, redirects human to login page
 * @return void
 */
function loggedinorreturn() {
	global $CURUSER, $REL_SEO;
	if (!$CURUSER) {
		safe_redirect($REL_SEO->make_link('login','returnto',urlencode(str_replace($REL_CONFIG['defaultbaseurl'],'', $_SERVER["REQUEST_URI"]))));
		exit();
	}
	return;
}

/**
 * Deletes torrent from database and folder
 * @param int $id id for torrent to be deleted
 */
function deletetorrent($id) {
	global $CURUSER, $REL_SEO, $REL_DB;
	sql_query("DELETE FROM notifs WHERE checkid = $id AND type='relcomments'") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM torrents WHERE id = $id");
	sql_query("DELETE FROM bookmarks WHERE id = $id");
	sql_query("DELETE FROM comments WHERE toid = $id AND type='rel'");
	$REL_DB->query("DELETE FROM xbt_files WHERE fid=$id");
	$REL_DB->query("DELETE FROM xbt_files_users WHERE fid=$id");
	//$REL_DB->query("DELETE FROM xbt_files WHERE fid=$id");
	foreach(explode(".","snatched.peers.files.trackers") as $x)
	sql_query("DELETE FROM $x WHERE torrent = $id");
	@unlink("torrents/$id.torrent");
	$images = glob(ROOT_PATH.'torrents/images/'.$id.'-*');
	if ($images) {
		foreach ($images as $img) unlink($img);
	}
	write_log(make_user_link()." deleted torrent with id $id (system message)",'system_functions');
	return;
}

/**
 * Fucks IE:) or just checks that user is runnging ie
 * @return boolean
 */
function fuckIE() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$browserIE = false;
	if (preg_match('/MSIE/',$user_agent)) $browserIE = true;
	return $browserIE;
}

/**
 * Clears caches for blocks (comments)
 * @param string $type Type of comments
 */
function clear_comment_caches($type) {
	global $REL_CACHE;
	$clearcache = array();
	if ($type=='news') $clearcache[] = 'block-news';
	elseif ($type=='poll') $clearcache[] = 'block-polls';
	elseif ($type=='req') $clearcache[] = 'block-req';
	elseif ($type=='rel') {
		$clearcache[] ='block-indextorrents';
		$clearcache[] = 'block-comments';
	}
	foreach ($clearcache as $cachevalue) $REL_CACHE->clearGroupCache($cachevalue);
}
/**
 * Prepares data to use in commenttable
 * @param MySQL resource $subres Resource of MySQL query
 * @param string $subject Subject of all comments (optional)
 * @param string $link Link to commented instance (optional)
 * @return array Prepared array
 * @see commenttable()
 */
function prepare_for_commenttable($subres,$subject='',$link='') {
	global $CURUSER,$REL_SEO, $CURUSER;
	$allrows = array();
	$allowed_links = array (/*'unread' => $REL_SEO->make_link('message','action','viewmessage','id',''), */'comments' => $REL_SEO->make_link('details','id','%s'), 'forum' => $REL_SEO->make_link('forum','a','viewtopic','id','%s'), 'poll' => $REL_SEO->make_link('polloverview','id','%s'), 'news' => $REL_SEO->make_link('newsoverview','id','%s'), 'user' => $REL_SEO->make_link('userdetails','id','%s'), 'req' => $REL_SEO->make_link('requests','id','%s'), 'rg' => $REL_SEO->make_link('relgroups','id','%s'), 'rgnews' => $REL_SEO->make_link('rgnewsoverview','id','%s'));

	while ( $row = mysql_fetch_array ( $subres ) ) {
		$visited_type = $row['type'].'comments';
		if ($row['last_access']>(time()-300)) $row['userstate'] = 'online'; else $row['userstate'] = 'offline';
		if (mb_strlen($row['subject'])>70) $row['subject'] = substr($row['subject'],0,67).'...';
		if ($row['user']) {
			$row['ratearea']['comment'] = ratearea($row['ratingsum'],$row['id'],$row['type'],(($CURUSER['id']==$row['user'])?$row['id']:0));
			$row['ratearea']['user'] = ratearea($row['urating'],$row['user'],'users',$CURUSER['id']);
		}
		$row['reportarea'] = reportarea($row['id'],$row['type']);
		$row['text'] = format_comment($row['text']);
		$avatar = ($CURUSER["avatars"] ? htmlspecialchars($row["avatar"]) : "");
		if (!$avatar){$avatar = "pic/default_avatar.gif"; }
		$row['avatar'] = $avatar;
		set_visited($visited_type,$row['id']);
		if ($subject) $row['subject'] = $subject;
		if ($link) $row['link'] = $link; else {
			$row['link'] = sprintf($allowed_links[$row['type']],$row['toid']);
		}
		$allrows [] = $row;
	}
	return $allrows;
}
/**
 * General function to display comment tables
 * @param array $rows associative array of rows
 * @param boolean $fetch Do not output data to browser, only fetch
 * @param string $custom_tpl Custom template to use, default commenttable.tpl
 * @return void|string
 */
function commenttable($rows,$fetch = false, $custom_tpl='commenttable.tpl') {
	global $CURUSER, $REL_CONFIG, $REL_LANG, $REL_SEO, $REL_TPL;

	$IS_MODERATOR = (get_privilege('is_moderator',false));
	$REL_TPL->assignByRef('IS_MODERATOR',$IS_MODERATOR);
	$REL_TPL->assign('rows',$rows);
	if ($fetch) return $REL_TPL->fetch($custom_tpl);
	$REL_TPL->display($custom_tpl);
}


/**
 * Generates 3d flash categories-cloud html code
 * @return string The html code
 * @see cloud()
 */
function cloud3d() {
	global $REL_CACHE, $REL_SEO, $REL_LANG;
	$tags = $REL_CACHE->get('system','cat_tags');
	if ($tags===false) {
		$cats = assoc_cats();
		$tree=make_tree();
		$arr=array();
		$row = sql_query("SELECT category FROM torrents");
		while (list($tcats) = mysql_fetch_array($row)) {
			if ($tcats) { $tcats = explode(',',$tcats);
			foreach ($tcats as $cat) {
				$childs = get_childs($tree,$cat);
				if (!$childs) {
					$catstr = $cats[$cat];
					$tags[$catstr]['count']++;
					$tags[$catstr]['id']=$cat;
				}
			}
			}
		}

		$REL_CACHE->set('system','cat_tags',$tags);
	}
	//min / max font sizes
	$small = 7;
	$big = 20;

	//amounts
	$minimum_count = @min(array_values($tags));
	$maximum_count = @max(array_values($tags));
	$minimum_count = $minimum_count['count'];
	$maximum_count = $maximum_count['count'];

	$spread = $maximum_count - $minimum_count;

	if($spread == 0) {$spread = 1;}

	$cloud_html = '';

	$cloud_tags = array();
	$i = 0;
	if ($tags)
	foreach ($tags as $tag => $taginfo) {

		$size = $small + ($taginfo['count'] - $minimum_count) * ($big - $small) / $spread;

		//spew out some html malarky!
		$cloud_tags[] = urlencode("<a href='".$REL_SEO->make_link('browse','cat',$taginfo['id']) . "' style='font-size:". floor($size) . "px;'>"). $tag. urlencode("(".$taginfo['count'].")</a>");
		$cloud_links[] = "<br /><a href=\"".$REL_SEO->make_link('browse','cat',$taginfo['id'])."\" style='font-size:". floor($size) . "px;'>$tag</a><br />";
		$i++;
	}
	$cloud_links[$i-1].=$REL_LANG->_('Your browser does not support flash!');
	$cloud_html[0] = join("", $cloud_tags);
	$cloud_html[1] = join("", $cloud_links);


	return $cloud_html;
}

/**
 * Generates static categories-cloud html. All parametres are default empty string
 * @param string $name name of div with cloud
 * @param string $color color of words in cloud
 * @param string $bgcolor backgroud color of flash
 * @param int $width width of flash
 * @param int $height height of flash
 * @param int $speed speed of objects' moving
 * @param int $size minimal font size
 * @return string The html code
 */
function cloud ($name = '', $color='',$bgcolor='',$width='',$height='',$speed='',$size='') {
	$tagsres = array();
	$tagsres = cloud3d();
	$tags = $tagsres[0];
	$links = $tagsres[1];


	$cloud_html = '
<div id="'.($name?$name:"wpcumuluswidgetcontent").'">'.$links.'</div>
<script type="text/javascript">
//<![CDATA[
var rnumber = Math.floor(Math.random()*9999999);
   var flashvars = {
   "tcolor": "'.($color?$color:"0x0054a6").'",
   "tspeed": "'.($speed?$speed:"250").'",
   "distr": "true",
   "mode": "tags",
   "tagcloud": "'.urlencode('<tags>') . $tags . urlencode('</tags>').'"
   };
   
   var params = {
   "allowScriptAccess": "always",
   "wmode": "opaque",
   "bgcolor": "'.($bgcolor?$bgcolor:"#f7f7f7").'"
   }
   
   var attributes = {
	"id": "'.($name?$name:"wpcumuluswidgetcontent").'",
	"name": "'.($name?$name:"wpcumuluswidgetcontent").'"    
	}

swfobject.embedSWF("swf/tagcloud.swf?r="+rnumber, "'.($name?$name:"wpcumuluswidgetcontent").'", "'.($width?$width:"100%").'", "'.($height?$height:"100%").'", "'.($size?$size:"9").'", "false",flashvars,params,attributes);

//]]>
</script>';
	return $cloud_html;
}

/**
 * Just sets color to red if torrent has no seeders
 * @param int $num amout of seeders
 * @return string Color as it is
 * @see torrenttable()
 */
function linkcolor($num) {
	if (!$num)
	return "red";
	return "green";
}

/**
 * @param MySQL resource $res Resource of sql query
 * @return array Array to be used in torrenttable
 * @see torrenttable()
 */
function prepare_for_torrenttable($res) {
	global $tree, $REL_LANG, $REL_CONFIG, $REL_SEO, $CURUSER;

	if (!$tree) $tree = make_tree();
	$resarray = array();
	$REL_CONFIG['pron_cats'] = explode(',',$REL_CONFIG['pron_cats']); //pron

	while ($resvalue = mysql_fetch_array($res)) {
		$chsel = array();
		$cats = explode(',',$resvalue['category']);
		$catq= array_shift($cats);
		$catq = get_cur_branch($tree,$catq);
		$childs = get_childs($tree,$catq['parent_id']);
		if ($childs) {
			foreach($childs as $child)
			if (($catq['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"".$REL_SEO->make_link("browse","cat",$child['id'])."\">".makesafe($child['name'])."</a>";
			$resvalue['cat_names'] = get_cur_position_str($tree,$catq['id']).($chsel && is_array($chsel)?', '.implode(', ',$chsel):'');
			//$resarray[$resvalue['id']] = $resvalue;
		} else $resvalue['cat_names'] = get_cur_position_str($tree,$catq['id']);

		if ($resvalue['sticky']&&!$st_used) {$resvalue['label'] = $REL_LANG->_("Sticky"); $st_used=true;}


		if ($resvalue['rgid']) $rgcontent = ($resvalue['rgimage']?"<img style=\"border:none;\" title=\"{$REL_LANG->_("Release of group")} {$resvalue['rgname']}\" src=\"{$resvalue['rgimage']}\"/>":$resvalue['rgname']);

		if ((!get_privilege('access_to_private_relgroups',false)) && !$resvalue['relgroup_allowed'] && $resvalue['rgid']) {
			$resvalue['relgroup'] = '<br/>'.$REL_LANG->say_by_key('relgroup_release').'&nbsp;'.$rgcontent;
			$resvalue['images'] = 'pic/privaterg.gif';
		}

		///////// pron
		$pron=false;
		if ($REL_CONFIG['pron_cats'] && !$CURUSER['pron'] && $resvalue['category']) {
			$resvalue['category'] = explode(',',$resvalue['category']);
			foreach ($resvalue['category'] as $category)
			if (in_array($category,$REL_CONFIG['pron_cats'])) $pron=true;
		}
		if ($pron) { $resvalue['images'] = $REL_CONFIG['defaultbaseurl'].'/pic/nopron.gif';  $resvalue['name'] = $REL_LANG->say_by_key('xxx_release'); }


		if(!$resvalue['sticky']) {
			$timestr = time()+$CURUSER['timezone']*3600;
			if ($resvalue['added']>(strtotime('today 00:00:00',$timestr))&&!$td_used) {
				$resvalue['label'] = $REL_LANG->_("Today");
				$td_used=true;
			}
			elseif ($resvalue['added']<(strtotime('yesterday 00:00:00',$timestr))&&!$lyd_used) { $yd_used = true; $lyd_used = true;
			$resvalue['label'] = $REL_LANG->_("Later than yesterday");

			}
			elseif ($resvalue['added']<(strtotime('today 00:00:00',$timestr))&&!$yd_used) {
				$resvalue['label'] = $REL_LANG->_("Yesterday");	$yd_used = true;
			}
		}
		$resvalue['month_added'] = mkprettymonth($resvalue["added"]);
		$resvalue['new'] = (@in_array($resvalue['id'],$CURUSER['torrents']));
		if ($resvalue['images']) {
			$resvalue['images'] = explode(',',$resvalue['images']);
			$resvalue['images'] = array_shift($resvalue['images']);
		}
		//		print("<td align=center><nobr>" . str_replace(" ", "<br />", $resvalue["added"]) . "</nobr></td>\n");
		$ttl = ($REL_CONFIG['ttl_days']*24) - floor((time() - ($resvalue["last_action"])) / 3600);
		if ($ttl == 1) $ttl .= " {$REL_LANG->_("hour")}"; else $ttl .= " {$REL_LANG->_("hours")}";
		$resvalue['ttl']=$ttl;
		$resvalue['size']= str_replace(" ", "", mksize($resvalue["size"]));
		if ($resvalue["filename"] != 'nofile') {
			if ($resvalue["seeders"]) {
				if ($resvalue["leechers"]) $ratio = $resvalue["seeders"] / $resvalue["leechers"]; else $ratio = 1;
				$resvalue['seed_col'].=("<b><a href=\"".$REL_SEO->make_link("torrent_info","id",$resvalue['id'],"name",translit($resvalue['name']))."\"><font color=" .
				get_slr_color($ratio) . ">" . number_format($resvalue["seeders"]) . "</font></a></b>");
			}
			else
			$resvalue['seed_col'].=($REL_LANG->say_by_key('no'));

			$resvalue['seed_col'].=("|");

			if ($resvalue["leechers"]) {
				$resvalue['seed_col'].=("<b><a href=\"".$REL_SEO->make_link("torrent_info","id",$resvalue['id'],"name",translit($resvalue['name']))."\">" .
				number_format($resvalue["leechers"]) . ($peerlink ? "</a>" : "") .
				   "</b>");
			}
			else
			$resvalue['seed_col'].=($REL_LANG->say_by_key('no'));
		} else $resvalue['seed_col'].=("<b>N/A</b>\n");

		$resarray[$resvalue['id']] = $resvalue;
	}
	return $resarray;

}
/**
 * General function to display table of torrents
 * @param array $res Array of rows
 * @param string $variant Name of script where table is dispaing.
 * @return void
 */
function torrenttable($res, $variant = "index") {
	global $CURUSER, $REL_CONFIG, $REL_SEO, $REL_LANG, $tree, $REL_TPL;
	if (!$tree) $tree = make_tree();
	$REL_TPL->assign('TABLE_VARIANT',$variant);
	$REL_TPL->assign('res',$res);
	$REL_TPL->assign('IS_MODERATOR',get_privilege('is_moderator',false));
	$REL_TPL->display('torrenttable.tpl');
}

/**
 * Funtion to generate pretty month
 * @param int $seconds UNIX-style date
 * @return string Nice month
 */
function mkprettymonth($seconds) {
	global $REL_LANG;
	$search = array('January','February','March','April','May','June','July','August','September','October','November','December');
		$replace = array($REL_LANG->_('Jan'),
	$REL_LANG->_('Feb'),
	$REL_LANG->_('Mar'),
	$REL_LANG->_('Apr'),
	$REL_LANG->_('May'),
	$REL_LANG->_('Jun'),
	$REL_LANG->_('Jul'),
	$REL_LANG->_('Aug'),
	$REL_LANG->_('Sep'),
	$REL_LANG->_('Oct'),
	$REL_LANG->_('Nov'),
	$REL_LANG->_('Dec'));
	$data = @date("d F ", $seconds);

	if (!$data) $data = 'N/A'; else
	$data = str_replace($search, $replace, $data);
	return $data;
}

if (!function_exists("htmlspecialchars_uni")) {
	function htmlspecialchars_uni($message) {
		$message = preg_replace("#&(?!\#[0-9]+;)#si", "&amp;", $message); // Fix & but allow unicode
		$message = str_replace("<","&lt;",$message);
		$message = str_replace(">","&gt;",$message);
		$message = str_replace("\"","&quot;",$message);
		$message = str_replace("  ", "&nbsp;&nbsp;", $message);
		return $message;
	}
}

/**
 * Pads hash
 * @param sring $hash Hash to be processed
 * @return string
 */
function hash_pad($hash) {
	return str_pad($hash, 20);
}

/**
 * Gets user icons
 * @param array $arr Array of user data
 * @param boolean $big Use big icons? Default false
 * @return string Html code with user icons
 */
function get_user_icons($arr, $big = false) {
	if ($big) {
		$donorpic = "starbig.gif";
		$warnedpic = "warnedbig.gif";
		$disabledpic = "disabledbig.gif";
		$style = "style='margin-left: 4pt'";
	} else {
		$donorpic = "star.gif";
		$warnedpic = "warned.gif";
		$disabledpic = "disabled.gif";
		$style = "style=\"margin-left: 2pt\"";
	}
	$pics = $arr["donor"] ? "<img src=\"pic/$donorpic\" alt='Donor' border=\"0\" $style>" : "";
	if ($arr["enabled"])
	$pics .= $arr["warned"] ? "<img src=\"pic/$warnedpic\" alt=\"Warned\" border=\"0\" $style>" : "";
	else
	$pics .= "<img src=\"pic/$disabledpic\" alt=\"Disabled\" border=\"0\" $style>\n";

	return $pics;
}

/**
 * Associates categories with its ids
 * @param string $type Table to take categories
 * @return array Array of categories, keys are ids, values are categories' names
 */
function assoc_cats($type='categories') {
	global $REL_CACHE;
	$cats = $REL_CACHE->get('trees','cat_assoc_'.$type);
	if ($cats===false) {
		$cats=array();
		$catsrow = sql_query("SELECT id,name FROM $type ORDER BY sort ASC");
		while ($catres= mysql_fetch_assoc($catsrow)) $cats[$catres['id']]=$catres['name'];
		$REL_CACHE->set('trees','cat_assoc_'.$type,$cats);
	}
	return $cats;
}

/**
 * Associates categories with its ids with full data
 * @param string $type Table to take categories
 * @return array Array of categories, keys are ids, values are categories' data
 */
function assoc_full_cats($type='categories') {
	global $REL_CACHE;
	$cats = $REL_CACHE->get('trees','cat_assoc_full_'.$type);
	if ($cats===false) {
		$cats=array();
		$catsrow = sql_query("SELECT * FROM $type ORDER BY sort ASC");
		while ($catres= mysql_fetch_assoc($catsrow)) {
			$catres['class'] = explode(',', $catres['class']);
			//var_dump($catres['class']);
			$cats[$catres['id']]=$catres;
		}
		$REL_CACHE->set('trees','cat_assoc_full_'.$type,$cats);
	}
	return $cats;
}

/**
 * Sends comment notifications to PM or/and Email. This is a part of notification system
 * @param int $id id of notification subject
 * @param string $page link to page with notification subject
 * @param string $type notification type
 * @return void
 */
function send_comment_notifs($id,$page,$type) {
	global $REL_LANG, $CURUSER, $REL_DB;

	$emailssql = $REL_DB->query("SELECT GROUP_CONCAT(users.email) FROM notifs LEFT JOIN users ON userid=users.id WHERE checkid = $id AND type='$type' AND FIND_IN_SET('$type',emailnotifs) AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
	$emails = mysql_result($emailssql, 0);
	if ($emails) {
		$emails = sqlesc($emails);
		$subject = sqlesc($REL_LANG->say_by_key_to($id,'new_comment'));
		$msg = sqlesc(sprintf($REL_LANG->say_by_key_to($id,'comment_notice_'.$type),$page));
		//sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, ".time().", $msg, 0, $subject FROM notifs WHERE checkid = $id AND type='$type' AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
		sql_query("INSERT INTO cron_emails (emails, subject, body) VALUES ($emails, $subject, $msg)") or sqlerr(__FILE__,__LINE__);
	}
}

/**
 * Sends email notifications. This is a part of notification system
 * @param string $type notification type
 * @param string $text notification text
 * @param int $id id of user to be notified. Default is 0 as 'to all, except current user'
 * @return void
 * @todo check messages sending
 */
function send_notifs($type,$text = '',$id = 0) {
	global $REL_LANG, $CURUSER, $REL_CONFIG, $REL_SEO, $REL_DB;

	$emailssql = $REL_DB->query("SELECT GROUP_CONCAT(users.email),users.language FROM users WHERE FIND_IN_SET('$type',emailnotifs) AND ".(!$id?"id != ".(int)$CURUSER['id']:"id = $id")." GROUP BY users.language") or sqlerr(__FILE__,__LINE__);
	while (list($emails,$language) = mysql_fetch_array($emailssql)) {	
	if ($emails) {
		$emails = sqlesc($emails);
		$subject = sqlesc($REL_LANG->say_by_key('new_'.$type,$language));
		$msg = sqlesc($REL_LANG->say_by_key('notice_'.$type,$language).$text."<hr/ ><a href=\"".$REL_SEO->make_link('index')."\">{$REL_CONFIG['sitename']}</a><br /><br /><div align=\"right\">{$REL_LANG->_lang($language,'You can always configure your notifications in <a href="%s">notification settings</a> of your account.',$REL_SEO->make_link("mynotifs","settings"))}</div>");
		//	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, ".time().", $msg, 0, $subject FROM notifs WHERE checkid = $id AND type='$type' AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
		$REL_DB->query("INSERT INTO cron_emails (emails, subject, body) VALUES ($emails,$subject, $msg)") or sqlerr(__FILE__,__LINE__);
	}
	}
}

/**
 * Checks that user is notified, and outputs suggest/discart notification link
 * @param int $id id of notification subject
 * @param string $type type of notification
 * @return string Notification html code
 */
function is_i_notified($id,$type) {
	global $CURUSER,$REL_LANG, $REL_SEO;
	$res = sql_query("SELECT id FROM notifs WHERE checkid=$id AND userid={$CURUSER['id']} AND type='$type'") or sqlerr(__FILE__,__LINE__);
	list($cid) = mysql_fetch_array($res);
	if ($cid) return("<div id=\"notifarea-$id\" style=\"display:inline;\"><a href=\"".$REL_SEO->make_link('notifs','action','deny','id',$cid)."\" onclick=\"return notifyme($cid,$id,'','deny')\">{$REL_LANG->say_by_key('monitor_comments_disable')}</a></div>");
	else return("<div id=\"notifarea-$id\" style=\"display:inline;\"><a href=\"".$REL_SEO->make_link('notifs','id',$id,'type',$type)."\" onclick=\"return notifyme('',$id,'$type','')\">{$REL_LANG->say_by_key('monitor_comments')}</a></div>");
}

/**
 * Makes tree of elemants
 * @param string $table Table to be used to make tree
 * @param unknown_type $condition Condition to be added to sql query whitch making a tree
 * @return multitype:|multitype:
 */
function &make_tree($table='categories',$condition='')
{
	global $REL_CACHE;
	if ($condition) $cacheadd = '-'.md5($condition);

	$tree = $REL_CACHE->get('trees',$table.$cacheadd);
	if ($tree === false) {
		$tree = array();

		$query = sql_query('SELECT * FROM '.$table.($condition?' '.$condition:'').' ORDER BY sort ASC') or sqlerr(__FILE__,__LINE__);
		if (!$query) return $tree;

		$nodes = array();
		$keys = array();
		while (($node = mysql_fetch_assoc($query)))
		{
			$node['class'] = explode(',',$node['class']);

			$nodes[$node['id']] =& $node;
			$keys[] = $node['id'];
			unset($node);
		}
		mysql_free_result($query);

		foreach ($keys as $key)
		{

			if ($nodes[$key]['parent_id'] === '0')
			$tree[] =& $nodes[$key];

			else
			{
				if (isset($nodes[ $nodes[$key]['parent_id'] ]))
				{
					if (! isset($nodes[ $nodes[$key]['parent_id'] ]['nodes']))
					$nodes[ $nodes[$key]['parent_id'] ]['nodes'] = array();

					$nodes[ $nodes[$key]['parent_id'] ]['nodes'][] =& $nodes[$key];
				}
			}
		}
		$REL_CACHE->set('trees',$table.$cacheadd,$tree);
	}
	return $tree;
}

/**
 * Generates input=select for tree
 * @param string $name Name of select element
 * @param array $tree Tree to be processed
 * @param int $selected id of selected element. Defalut 0 as 'none'
 * @param boolean $selectparents allow user select parents. Default false
 * @param boolean $recurs Is recursive launch? Used only inside recursion. Default false
 * @param int $level Level of tree. Default 0 as 'top'. Used only inside recursion.
 * @param string $t_content Already generated content. Default empty string. Used only inside recursion
 * @return string HTML code of input=select
 */
function gen_select_area($name, $tree, $selected=0, $selectparents = false, $recurs = false, $level = 0, &$t_content = '') {
	global $REL_LANG;
	if (!$recurs) $t_content = "<select class='linkselect'  name=\"$name\"><option value=\"0\">{$REL_LANG->say_by_key('choose')}</option>\n";

	foreach ($tree as $branch) {
		$add = str_repeat('--',$level).' ';
		if ($branch['nodes']) {
			$level++;

			$t_content .="<option class=\"select\" value=\"{$branch['id']}\"".(!$selectparents?" disabled=\"disabled\"":(($selected==$branch['id'])?" selected=\"selected\"":'')).">$add{$branch['name']}</option>\n";
			gen_select_area('',$branch['nodes'],$selected, $selectparents, true,$level, $t_content);
			$level--;
		} else {
			$t_content .="<option value=\"{$branch['id']}\"".(($selected==$branch['id'])?" selected=\"selected\"":'').">$add{$branch['name']}</option>\n";
		}
	}
	if (!$recurs) { $t_content.= "</select>\n";  return $t_content; }
}

/**
 * Gets array of elements of current branch
 * @param array $tree Tree to be processed
 * @param int $tid Requested id of a branch
 * @return array Requested branch
 */
function get_cur_branch($tree, $tid) {
	//$branch['class'] = explode(',',$branch['class']);
	foreach ($tree as $branch) {
		if ($branch['id'] == $tid) return $branch; else
		if ($branch['nodes']) {
			$br=get_cur_branch($branch['nodes'],$tid);
			if (is_array($br)) return $br;
		}
	}
}

/**
 * Gets ONCE childs of current branch
 * @param array $tree Tree to be processed
 * @param int $tid Id of processing branch
 * @return array Array of branches-children
 */
function get_childs($tree, $tid) {
	$branch = get_cur_branch($tree,$tid);
	return ($branch['nodes']);

}

function get_top_parent($tree,$tid, $type='categories') {
	global $REL_DB;
	$return = $REL_CACHE->get('trees',"top_parent_$type_".$tid);
	if ($return==false) {
		foreach ($tree as $branch) {
			if (in_array($tid,get_full_childs_ids($tree, $branch['id'], $type))) {
				$return = $brach['id'];
			}
		}
		$REL_CACHE->set('trees',"top_parent_$type_".$tid,$return);
	}
	return $return;
}

/**
 * Gets array of ids of ALL children of branch
 * @param array $tree Tree to be processed
 * @param int $tid id of processing branch
 * @param string $type type of tree
 * @param array $array already processed ids, used only in recursion
 * @param boolean $recurs is function running recursive? default false, used only in recursion
 * @param int $level level of processing tree, used only in recursion
 * @return array|boolean Array of ids of ALL children of branch, id of a branch if there are no children and false if category does not exist
 */
function get_full_childs_ids($tree, $tid, $type='categories', &$array = array(), &$recurs = false, &$level = 0) {
	global $REL_CACHE;
	$return = false;
	if (!$recurs)
	$return = $REL_CACHE->get('trees',$type.'-full-childs-'.$tid);
	if ($return===false) {
		$branch = get_cur_branch($tree,$tid);
		if (!$branch) return false;
		if (!$branch['nodes']) {
			$array[] = $branch['id'];
		} else {
			$level++;
			$recurs = true;
			foreach ($branch['nodes'] as $child) $array = get_full_childs_ids($branch['nodes'],$child['id'],$type,$array,$recurs,$level);
			$level--;
			if (!$level) $recurs = false;
		}
		if (!$recurs) $REL_CACHE->set('trees',$type.'-full-childs-'.$tid,$array);
		return $array;
	} else return $return;
}

/**
 * Gets array of current way to branch
 * @param array $tree Tree to be processed
 * @param int $cid id of processing branch
 * @param string $viewer Script to view by branches. Default 'browse' (without .php extention)
 * @param boolean $byimages Repace branch names by branch images? Default false
 * @param array $array Array of already processed elements. Used only in recursion.
 * @return array Array of way steps
 */
function get_cur_position($tree, $cid, $viewer='browse', $byimages=false, &$array = '') {
	global $REL_SEO;
	foreach ($tree as $branch) {
		if ($cid==$branch['id']) { $array[]="<a href=\"".$REL_SEO->make_link($viewer,"cat",$branch['id'])."\">".(($byimages && $branch['image'])?"<img style=\"border:none;\" src=\"pic/cats/{$branch['image']}\" title=\"{$branch['name']}\" alt=\"{$branch['name']}\"/>":$branch['name'])."</a>"; return $array; }
		elseif ($branch['nodes']) {
			$array[]="<a href=\"".$REL_SEO->make_link($viewer,"cat",$branch['id'])."\">".(($byimages && $branch['image'])?"<img style=\"border:none;\" src=\"pic/cats/{$branch['image']}\" title=\"{$branch['name']}\" alt=\"{$branch['name']}\"/>":$branch['name'])."</a>";
			$res = get_cur_position($branch['nodes'],$cid, $viewer, $byimages, $array);
			if (!$res) array_pop($array); else return $res;
		}

	}
}

/**
 * Implodes way, given by get_cur_position() by passed separator
 * @param array $tree Tree to be processed
 * @param int $cid id of processing branch
 * @param string $viewer Script to view by branches. Default 'browse' (without .php extention)
 * @param boolean $byimages Repace branch names by branch images? Default false
 * @param string $separator Symbol (string) to separate waypoints
 * @return string String of way or empty line on fail
 */
function get_cur_position_str($tree,$tid, $viewer = 'browse', $byimages=false, $separator=' / ') {
	$array = get_cur_position($tree,$tid, $viewer, $byimages);
	if (!$array) return '';
	return implode($separator,$array);
}

/**
 * This is a part of notification system. Sets element visited.
 * @param string $type type of element
 * @param id $id id of element
 * @return void
 */
function set_visited($type,$id) {
	if (!in_array($id,(array)$_SESSION['visited_'.$type])) $_SESSION['visited_'.$type][] = $id;
	return;
}


/**
 * Gets kinopoisk.ru trailer
 * @param string $descr Text where find film link
 * @return string Html code of player
 */
function get_trailer($descr) {
	global $REL_CONFIG;
	if ($REL_CONFIG['use_kinopoisk_trailers']) {
		preg_match("#http://www.kinopoisk.ru/level/1/film/(.*?)/#si",$descr,$matches);

		$filmid = $matches[1];
		if ($filmid) {

			/**
			 * Get variable of flashcode
			 * @param string $text Where to find
			 * @param string $option What to find
			 * @return string Flash id(hash)
			 */
			function get_vars($text, $option)

			{
				if ($option == 'flashcode') {
					$search = "#getTrailer\(\"(.*?)\",\"(.*?)\",\"(.*?)\",\"(.*?)\",\"(.*?)\",\"(.*?)\"#si";
				}

				preg_match($search,$text,$result);
				if ($result) return array('file'=> 'http://'.($result[6]?$result[6]:'trailers').'.kinopoisk.ru/trailers/flv/'.$result[2], 'image' => 'http://trailers.kinopoisk.ru/trailers/flv/'.$result[3], 'width' => $result[4], 'height' => $result[5]); else return false;

			}
			require_once(ROOT_PATH."classes/parser/Snoopy.class.php");
			$page = new Snoopy;

			$page->fetch("http://www.kinopoisk.ru/level/1/film/$filmid/");
			$source = $page->results;
			$flashcode = get_vars($source,'flashcode');

			if ($flashcode)
			$online = "<div id=\"trailer_player\">Trailer Player Loading...</div>
			<script type=\"text/javascript\">
   var flashvars = {
      'file':               '".urlencode($flashcode['file'])."',
      'autostart':          'false',
      'image':	'".urlencode($flashcode['image'])."'
   };

   var params = {
      'allowfullscreen':    'true',
      'allowscriptaccess':  'always',
      'bgcolor':            '#ffffff',
      'wmode': 'opaque'
   };

   var attributes = {
      'id':                 'trailer_player',
      'name':               'trailer_player'
   };

   swfobject.embedSWF('http://tr.kinopoisk.ru/js/jw/player-licensed.swf', 'trailer_player', '{$flashcode['width']}', '{$flashcode['height']}', '9', 'false', flashvars, params, attributes);
</script>";			else $online=false;
		}

	}
	return $online;
}

/**
* Tranliterate chars from russan-ukrainian to english
* @param string $st string to be transliterated
* @param boolean $replace_spaces replase spaces by "_" ? Default true
* @return string Transliterated String
* updated by animan(http://ua-torrents.net)
*/
function translit($st,$replace_spaces = true) {
   $ar = array("а"=>"a","б"=>"b","в"=>"v","г"=>"g","ґ"=>"g","д"=>"d","е"=>"e","ё" =>"yo","ж"=>"j","з"=>"z","и"=>"u","й"=>"i","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"y","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch", "ш"=>"sh","щ"=>"sh","ы"=>"i","і"=>"i","ї"=>"yi","є"=>"ye","э"=>"e","ю"=>"u","я"=>"ya",
"ь"=>"","ъ"=>"",'%'=>'','/'=>'-','['=>'',']'=>'','{'=>'','}'=>'','('=>'',')'=>'','<'=>'','>'=>'','|'=>'','#'=>'','!'=>'','@'=>'','$'=>'','^'=>'','*'=>'',':'=>'',';'=>'',','=>'','?'=>'',' / '=>'-','/ '=>'-',' /'=>'-','&'=>'-','  '=>'-','’'=>'',"'"=>"",'"'=>'','+'=>'','.'=>'','№'=>'','quot'=>'','«'=>'','»'=>'','`'=>'');
   $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю','і','є','ґ','ї');
   $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю','І','Є','Ґ','Ї');

   $st = str_replace($alfavitupper,$alfavitlover,strtolower($st));
   $st = strtr($st,$ar);

   if ($replace_spaces) $st = str_replace(" ","_",$st);
   return $st;
}

function generate_lang_js() {
	global $REL_LANG;
	return "<script type=\"text/javascript\" language=\"javascript\">
			var REL_LANG_NO_TEXT_SELECTED = '{$REL_LANG->_('No text selected!')}';
			var REL_LANG_ARE_YOU_SURE = '{$REL_LANG->_('Are you sure?')}';
			</script>";
}
/**
 * Outputs beta warning. Default false.
 * @var boolean
 */
define ("BETA", true);
/**
 * Beta warning as it is
 * @var string
 */
define ("BETA_NOTICE", "\n<br />This isn't complete release of source!");
/**
 * Kinokpk.com releaser's version
 * @var string
 */
define("RELVERSION","3.40 experimental alpha with xbt support");
?>
