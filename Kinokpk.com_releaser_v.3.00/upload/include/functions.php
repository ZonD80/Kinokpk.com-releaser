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


$zodiac[] = array("Козерог", "capricorn.gif", "22-12");
$zodiac[] = array("Стрелец", "sagittarius.gif", "23-11");
$zodiac[] = array("Скорпион", "scorpio.gif", "24-10");
$zodiac[] = array("Весы", "libra.gif", "24-09");
$zodiac[] = array("Дева", "virgo.gif", "24-08");
$zodiac[] = array("Лев", "leo.gif", "23-07");
$zodiac[] = array("Рак", "cancer.gif", "22-06");
$zodiac[] = array("Близнецы", "gemini.gif", "22-05");
$zodiac[] = array("Телец", "taurus.gif", "21-04");
$zodiac[] = array("Овен", "aries.gif", "22-03");
$zodiac[] = array("Рыбы", "pisces.gif", "21-02");
$zodiac[] = array("Водолей", "aquarius.gif", "21-01");


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
	$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_. ".
		"абвгдеёжзиклмнопрстуфхшщэюяьъАБВГДЕЁЖЗИКЛМНОПРСТУФХШЩЭЮЯЬЪ";

	for ($i = 0; $i < strlen($username); ++$i)
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
 * @param string|array $url URL of redirection or array of GET values.
 * <code>
 * safe_redirect('index.php', 'id' => '300', 'view' => 'deleted');
 * safe_redirect('index.php?id=300&view=deleted');
 * </code>
 * @todo class for generation of seo-based urls and links as arrays
 * @param int|float $timeout timeout in seconds before redirection
 * @return void
 */
function safe_redirect($url,$timeout = 0) {
	if (!is_array($url)) {
		$url = trim($url);
		if (REL_AJAX || ob_get_length()) print('
    <script type="text/javascript" language="javascript">
    function Redirect() {
      location.href = "'.addslashes($url).'";
      }
      setTimeout(\'Redirect()\','.($timeout*1000).');
    </script>
');
		else header("Refresh: $timeout; url=$url");
	}
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
	global $CURUSER,$ALREADY_RATED, $tracker_lang, $CACHEARRAY;
	if (!$currid) return '';
	if (!is_array($ALREADY_RATED[$type])) {
		$ALREADY_RATED[$type]=array();
		$res = sql_query("SELECT rid,type FROM ratings WHERE userid={$CURUSER['id']}");
		while (list($rid,$rtype) = mysql_fetch_array($res)) {
			$ALREADY_RATED[$rtype][] = $rid;
		}
	}
	if ($currating>0) $znak='+';
	$text='<strong>'.$znak.$currating.'</strong>';
	if (@in_array($currid,$ALREADY_RATED[$type]) || ($currid==$owner_id)) return $text;
	else return ('<div style="display:inline;" id="ratearea-'.$currid.'-'.$type.'" class="ratearea">&nbsp;'.$text.'<a href="rate.php?id='.$currid.'&amp;type='.$type.'&amp;act=up" onclick="return rateit('.$currid.',\''.$type.'\',\'up\');">
	<img class="arrowup" style="border:none;" src="pic/null.gif" title="'.$tracker_lang['rate_up'].'"/></a><a href="rate.php?id='.$currid.'&amp;type='.$type.'&amp;act=down" onclick="return rateit('.$currid.',\''.$type.'\',\'down\');">
	<img class="arrowdown" style="border:none;" src="pic/null.gif" title="'.$tracker_lang['rate_down'].'"/></a>&nbsp;</div>');

}

/**
 * Generates report area
 * @param int $id id of to be reported element
 * @param unknown_type $type report type
 * @return string report area with link to reporting script
 */
function reportarea($id,$type) {
	global $CURUSER,$ALREADY_REPORTED, $tracker_lang;
	if (!$id) return '';
	if (!is_array($ALREADY_REPORTED[$type])) {
		$ALREADY_REPORTED[$type]=array();
		$res = sql_query("SELECT reportid,type,motive FROM reports WHERE userid={$CURUSER['id']}");
		while (list($reportid,$rtype,$motive) = mysql_fetch_array($res)) {
			$ALREADY_REPORTED[$rtype][$reportid] = $motive;
		}
	}
	$text='<strong>'.$tracker_lang['you_already_reported'].($motive?' '.$tracker_lang['report_reason'].$motive:'').'</strong>';
	if (@array_key_exists($id,$ALREADY_RATED[$type])) return $text;
	else return ('&nbsp;<div style="display:inline;" id="reportarea-'.$id.'-'.$type.'">[<a class="altlink_white" href="report.php?id='.$id.'&amp;type='.$type.'">'.$tracker_lang['report_it'].'</a>]&nbsp;</div>');

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
 * Sets charset to database connection.
 * @param string $charset Charset to be set
 * @return void
 */
function my_set_charset($charset) {
	if (!function_exists("mysql_set_charset") || !mysql_set_charset($charset)) mysql_query("SET NAMES $charset");
	return;
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
 * Generates TinyMCE template, that validates TinyMCE input to XHTML 1.0 transitional
 * @return void|string If TinyMCE already initialized return void, else part of javascript code
 * @see mcejs()
 */
function mcejstemplate () {
	global $CURUSER;

	$valid_elm = 'verify_html : true,
	 valid_elements : ""
	 +"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
	 +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
	 +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
	 +"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
	 +"|height|hspace|id|name|object|style|title|vspace|width],"
	 +"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
	 +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
	 +"|shape<circle?default?poly?rect|style|tabindex|title|target],"
	 +"base[href|target],"
	 +"basefont[color|face|id|size],"
	 +"bdo[class|dir<ltr?rtl|id|lang|style|title],"
	 +"big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"blockquote[dir|style|cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
	 +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
	 +"|onmouseover|onmouseup|style|title],"
	 +"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
	 +"br[class|clear<all?left?none?right|id|style|title],"
	 +"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
	 +"|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
	 +"|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
	 +"|value],"
	 +"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
	 +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
	 +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
	 +"|valign<baseline?bottom?middle?top|width],"
	 +"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
	 +"|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
	 +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
	 +"|valign<baseline?bottom?middle?top|width],"
	 +"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
	 +"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
	 +"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
	 +"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
	 +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
	 +"|style|title|target],"
	 +"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
	 +"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
	 +"frameset[class|cols|id|onload|onunload|rows|style|title],"
	 +"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"head[dir<ltr?rtl|lang|profile],"
	 +"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
	 +"html[dir<ltr?rtl|lang|version],"
	 +"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
	 +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
	 +"|title|width],"
	 +"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
	 +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|src|style|title|usemap|vspace|width],"
	 +"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
	 +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
	 +"|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
	 +"|readonly<readonly|size|src|style|tabindex|title"
	 +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
	 +"|usemap|value],"
	 +"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
	 +"kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
	 +"|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
	 +"|onmouseover|onmouseup|style|title],"
	 +"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
	 +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
	 +"|value],"
	 +"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
	 +"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
	 +"noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"noscript[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"object[align<bottom?left?middle?right?top|archive|border|class|classid"
	 +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
	 +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
	 +"|vspace|width|allowfullscreen],"
	 +"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|start|style|title|type],"
	 +"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
	 +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
	 +"|onmouseover|onmouseup|selected<selected|style|title|value],"
	 +"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|style|title],"
	 +"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
	 +"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
	 +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
	 +"|onmouseover|onmouseup|style|title|width],"
	 +"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
	 +"samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"script[charset|defer|language|src|type],"
	 +"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
	 +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
	 +"|tabindex|title],"
	 +"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"span[align|class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title],"
	 +"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"style[dir<ltr?rtl|lang|media|title|type],"
	 +"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title],"
	 +"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
	 +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
	 +"|style|summary|title|width],"
	 +"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
	 +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
	 +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
	 +"|valign<baseline?bottom?middle?top],"
	 +"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
	 +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
	 +"|style|title|valign<baseline?bottom?middle?top|width],"
	 +"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
	 +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
	 +"|readonly<readonly|rows|style|tabindex|title],"
	 +"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
	 +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
	 +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
	 +"|valign<baseline?bottom?middle?top],"
	 +"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
	 +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
	 +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
	 +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
	 +"|style|title|valign<baseline?bottom?middle?top|width],"
	 +"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
	 +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
	 +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
	 +"|valign<baseline?bottom?middle?top],"
	 +"title[dir<ltr?rtl|lang],"
	 +"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
	 +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title|valign<baseline?bottom?middle?top],"
	 +"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
	 +"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
	 +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
	 +"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
	 +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
	 +"|onmouseup|style|title|type],"
	 +"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
	 +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
	 +"|title]",';
	if (get_user_class() >= UC_ADMINISTRATOR) {
		return $valid_elm.'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,spoiler,stamps,kinopoisk,reltemplates",
		theme_advanced_toolbar_location : "external",
		theme_advanced_toolbar_align : "center",
		theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        dialog_type:"modal",
        entities:"38,amp,60,lt,62,gt",
        paste_remove_spans:"1", 
        paste_strip_class_attributes:"all"';
	} elseif (get_user_class() >= UC_MODERATOR) {
		return $valid_elm.'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup'./*,help,code*/',|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "'./*insertlayer,moveforward,movebackward,absolute,|,styleprops,|,*/'cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,'./*template,*/'blockquote,'./*pagebreak,*/'.|,spoiler,stamps,kinopoisk,reltemplates",
		theme_advanced_toolbar_location : "external",
		auto_resize : "true",
		theme_advanced_toolbar_align : "center",
		theme_advanced_statusbar_location : "bottom",
  invalid_elements: "script,embed,iframe",
        theme_advanced_resizing : true,
        dialog_type:"modal",
        entities:"38,amp,60,lt,62,gt",
        paste_remove_spans:"1",
        paste_strip_class_attributes:"all"';
	} else return $valid_elm.'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup'./*,help,code*/',|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "'./*insertlayer,moveforward,movebackward,absolute,|,styleprops,|,*/'cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,'./*template,*/'blockquote,'./*pagebreak,*/'.|,spoiler,stamps,kinopoisk,reltemplates",
		theme_advanced_toolbar_location : "external",
		theme_advanced_toolbar_align : "center",
		theme_advanced_statusbar_location : "bottom",
  invalid_elements: "script,embed,iframe",
        theme_advanced_resizing : true,
        dialog_type:"modal",
        entities:"38,amp,60,lt,62,gt",
        paste_remove_spans:"1",
        paste_strip_class_attributes:"all"';
}

/**
 * Generates complete TinyMCE js code
 * @return string The js code
 */
function mcejs() {
	if (defined("TINYMCE_REQUIRED")) return;
	global $REL_CONFIG,$ss_uri,$CURUSER;

	$lang = (($CURUSER['language']=='ua')?'uk':$CURUSER['language']);
	$return .= '<script type="text/javascript" src="js/tiny_mce/tiny_mce_gzip.js"></script>
<script language="javascript" type="text/javascript">
function mcejs() {
tinyMCE_GZ.init({
	plugins : \'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,stamps,kinopoisk,\'+
        \'searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,spoiler,reltemplates\',
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
        \'searchreplace,'./*contextmenu,*/'print,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,spoiler,reltemplates\',
	languages : \''.$lang.'\',
	disk_cache : true,
	gecko_spellcheck:"1",
	debug : false,
   forced_root_block : false,
   force_br_newlines : true,
   force_p_newlines : false,
   theme : "advanced",
	content_css : "/themes/'.$ss_uri.'/'.$ss_uri.'.css",
    language: "'.$lang.'",
    	mode : "exact",
	elements : "tmce",
	template_replace_values : {
		username : "'.$CURUSER['username'].'"
	},
   '.mcejstemplate ().'
});
});
}
</script>
'.(defined("NO_TINYMCE")?'':'<script language="javascript" type="text/javascript">mcejs();</script>');
	define("TINYMCE_REQUIRED",true);
	return $return;
}

/**
 * HTTP auth in admincp, modtask, etc
 * @return void
 */
function httpauth(){
	global $CURUSER, $tracker_lang;

	if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
		$auth_params = explode(":" , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		$_SERVER['PHP_AUTH_USER'] = $auth_params[0];
		unset($auth_params[0]);
		$_SERVER['PHP_AUTH_PW'] = implode('',$auth_params);
	}

	if (($CURUSER['passhash'] != md5($CURUSER['secret'].$_SERVER["PHP_AUTH_PW"].$CURUSER['secret'])) || ($CURUSER['username']<>$_SERVER['PHP_AUTH_USER'])) {
		if ($_SERVER["PHP_AUTH_PW"]) write_log("<a href=\"userdetails.php?id={$CURUSER['id']}\">".get_user_class_color($CURUSER['class'],$CURUSER['username'])."</a> at ".getip()." <font color=\"red\">ADMIN CONTROL PANEL Authentication FAILED</font>",'admincp_auth');

		header("WWW-Authenticate: Basic realm=\"Kinokpk.com releaser\"");
		header("HTTP/1.0 401 Unauthorized");
		stderr($tracker_lang['error'],$tracker_lang['access_denied'],'error');

	}
	return;
}
//functions_global:

/**
 * Gets required language for language file stored in languages/LANG/param.php
 * @param string $option Language to include
 * @return void
 */
function getlang($option = 'main') {
	global $CACHEARRAY, $tracker_lang, $CURUSER;

	if (empty($_COOKIE["lang"]) || !$CACHEARRAY['use_lang']) {
		if (!file_exists(ROOT_PATH.'languages/lang_' . $CACHEARRAY['default_language'] . '/lang_'.$option.'.php')) die ("Language problem, check config ($option)");

		include_once(ROOT_PATH.'languages/lang_' . $CACHEARRAY['default_language'] . '/lang_'.$option.'.php');
		@setcookie("lang", $CACHEARRAY['default_language'], $expires, "/");
	}
	else {
		if (!file_exists(ROOT_PATH.'languages/lang_' . $_COOKIE["lang"] . '/lang_'.$option.'.php') && ($option=='main')) {

			@include_once(ROOT_PATH.'languages/lang_' . $CACHEARRAY['default_language'] . '/lang_'.$option.'.php');
			setcookie("lang", $CACHEARRAY['default_language'], $expires, "/");
			if ($CURUSER) sql_query("UPDATE users SET language='{$CACHEARRAY['default_language']}' WHERE id={$CURUSER['id']}");
		} else { @include_once(ROOT_PATH.'languages/lang_' . $_COOKIE["lang"] . '/lang_'.$option.'.php'); }
	}
	return;
}


/**
 * Alias to strip_tags
 * @param string $text Text to make safe
 * @return string
 */
function makesafe($text) {
	return strip_tags($text);
}

/**
 * Generates complete <u>HTML</u> input TinyMCE code
 * @param string $name name of textarea input tag
 * @param string $content contents to be added to texarea
 * @return string The code
 */
function textbbcode($name, $content="") {

	return '<textarea id="tmce" class="tmce" name="'.$name.'" cols="80" rows="25">'.$content.'</textarea>'.mcejs();
}

/**
 * Deletes tracker user
 * @param int $id id of user
 * @return void
 */
function delete_user($id) {
	global $CURUSER;
	sql_query("DELETE FROM users WHERE id = $id");
	sql_query("DELETE FROM usercomments WHERE userid=$id") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM notifs WHERE type='usercomments' AND checkid=$id") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM messages WHERE receiver = $id OR sender = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM friends WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM friends WHERE friendid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM bookmarks WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM invites WHERE inviter = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM peers WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM addedrequests WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM notifs WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
	write_log("<a href=\"userdetails.php?id={$CURUSER['id']}\">".get_user_class_color($CURUSER['class'],$CURUSER['username'])."</a> <font color=\"red\">deleted user with id $id</font>",'system_functions');

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
	($r = sql_query("SELECT SUM(1) FROM $table$suffix")) or die(mysql_error());
	($a = mysql_fetch_row($r)) or die(mysql_error());
	return $a[0]?$a[0]:0;
}
/**
 * Standart notification message
 * @param string $heading Heading of a message, e.g. subject
 * @param string $text Content of a message, e.g. body
 * @param string $div Div to be displayed, default 'success'
 * @param boolean $htmlstrip Strip html? Defalut false
 * @return void
 */
function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
	if ($htmlstrip) {
		$heading = strip_tags(trim($heading));
		$text = strip_tags(trim($text));
	}
	print("<table class=\"main\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"embedded\">\n");
	print("<div class=\"$div\">".($heading ? "<b>$heading</b><br />" : "")."$text</div></td></tr></table>\n");
	return;
}

/**
 * Standart error message with die
 * @param string $heading Heading of a message, e.g. subject
 * @param string $text Content of a message, e.g. body
 * @param string $div Div to be displayed, default 'success'
 * @param boolean $htmlstrip Strip html? Defalut false
 * @return void
 * @see stdmsg()
 */
function stderr($heading = '', $text = '', $div ='error', $htmlstrip = false) {
	if ($_GET['ajax'] || REL_AJAX) { headers(true); die ('<div align="center">'.$heading.': '.$text.'</div>'); }
	stdhead();
	stdmsg($heading, $text, $div, $htmlstrip);
	stdfoot();
	die;
	return;
}

/**
 * Generates SQL error message sending notification to SYSOP
 * @param string $file File where error begins __FILE__
 * @param string $line Line where error begins __LINE__
 * @return void
 */
function sqlerr($file = '', $line = '') {
	global $queries, $CURUSER;
	$err = mysql_error();
	$res = sql_query("SELECT id FROM users WHERE class=".UC_SYSOP);
	while (list($id) = mysql_fetch_array($res)) write_sys_msg($id,'MySQL got error: '.$err.'<br />File: '.$file.'<br />Line: '.$line.'<br />URI: '.$_SERVER['REQUEST_URI'].'<br />User: <a href="userdetails.php?id='.$CURUSER['id'].'">'.get_user_class_color($CURUSER['class'],$CURUSER['username'].'</a>'),'MySQL error detected!');
	$text = ("<table border=\"0\" bgcolor=\"blue\" align=\"left\" cellspacing=\"0\" cellpadding=\"10\" style=\"background: blue\">" .
	"<tr><td class=\"embedded\"><font color=\"white\"><h1>Ошибка в SQL</h1>\n" .
	"<b>Ответ от сервера MySQL: " . $err . ($file != '' && $line != '' ? "<p>в $file, линия $line</p>" : "") . "<p>Запрос номер $queries.</p></b></font></td></tr></table>");
	write_log("<a href=\"userdetails.php?id={$CURUSER['id']}\">".get_user_class_color($CURUSER['class'],$CURUSER['username'])."</a> SQL ERROR: $text</font>",'sql_errors');
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
	if(($age>=5) && ($age<=14)) $str = "лет";
	else {
		$num = $age - (floor($age/10)*10);

		if($num == 1) { $str = "год"; }
		elseif($num == 0) { $str = "лет"; }
		elseif(($num>=2) && ($num<=4)) { $str = "года"; }
		elseif(($num>=5) && ($num<=9)) { $str = "лет"; }
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
	global $tracker_lang;
	$replace = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">Скрытый текст</div><div class=\"sp-body\"><textarea id=\"spoiler\" rows=\"10\" cols=\"60\">\\1</textarea></div></div>";
	$text = preg_replace("#\[spoiler\](.*?)\[/spoiler\]#si", $replace, $text);
	return $text;
}

/**
 * Formats [spoiler=description]text[/spoiler] tag
 * @param string $text Text to be prosessed
 * @return string Processed html code
 */
function encode_spoiler_from($text) {
	global $tracker_lang;
	$replace = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">\\1</div><div class=\"sp-body\"><textarea id=\"spoiler\" rows=\"10\" cols=\"60\">\\2</textarea></div></div>";
	$text = preg_replace("#\[spoiler=(.+?)\](.*?)\[/spoiler\]#si", "".$replace, $text);
	return $text;
}

/**
 * Parses html code using cleanhtml, get images and pages system
 * @param string $text Text to be processed
 * @return string The html code
 * @see cleanhtml()
 * @see get_images()
 */
function format_comment($text) {
	global $CACHEARRAY, $CACHE, $bb,$html, $tracker_lang;

	$text = cleanhtml($text);
	if (!$bb) $bb = $CACHE->get('pages','bb');
	if (!$html) $html =$CACHE->get('pages','html');

	if (($bb===false) || ($html===false)) {
		$bb = array();
		$html=array();
		$row = sql_query("SELECT id,tags FROM pages WHERE indexed=1") or die(mysql_error());
		while ($res = mysql_fetch_assoc($row)) {
			if (!empty($res['tags'])) { $tags = explode(',',$res['tags']);
			foreach ($tags as $word) {
				$bb[] = "#".trim($word)."#";
				$html[] = "<a href=\"pagedetails.php?id={$res['id']}\">$word</a>";
			}
			}
		}
		$CACHE->set('pages','bb',$bb);
		$CACHE->set('pages','html',$html);
	}

	if($bb) $text = preg_replace($bb,$html,$text);
	while (preg_match("#\[spoiler\](.*?)\[/spoiler\]#si", $text)) $text = encode_spoiler($text);
	while (preg_match("#\[spoiler=(.+?)\](.*?)\[/spoiler\]#si", $text)) $text = encode_spoiler_from($text);
	return $text;

}

/**
 * Returns user class
 * @return int User class id
 */
function get_user_class() {
	global $CURUSER;

	return is_valid_user_class($CURUSER['class'])?$CURUSER['class']:0;
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

	//getlang('logs');
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
	$expl = explode("@", $email);
	$wildemail = "*@".$expl[1];
	$res = sql_query("SELECT id, comment FROM bannedemails WHERE email = ".sqlesc($email)." OR email = ".sqlesc($wildemail)."") or sqlerr(__FILE__, __LINE__);
	if ($arr = mysql_fetch_assoc($res))
	stderr("Ошибка!","Этот емайл адресс забанен!<br /><br /><strong>Причина</strong>: $arr[comment]", false);
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
 * Preforms a sql query and writes query and time to statistics
 * @param string $query Query to be performed
 * @return resource Mysql resource
 */
function sql_query($query) {
	global $queries, $query_stat, $querytime;
	$queries++;
	$query_start_time = microtime(true); // Start time
	$result = mysql_query($query);
	$query_end_time = microtime(true); // End time
	$query_time = ($query_end_time - $query_start_time);
	$querytime = $querytime + $query_time;
	//$query_time = substr($query_time, 0, 8);
	$query_stat[] = array("seconds" => $query_time, "query" => $query);
	return $result;
}

/**
 * Connects to database
 * @param boolean $lightmode Begin user session or not. Default false
 * @see user_session()
 * @see userlogin()
 */
function dbconn($lightmode = false) {
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset, $CACHEARRAY, $CACHE, $CURUSER;

	if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
	die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());

	mysql_select_db($mysql_db)
	or die("dbconn: mysql_select_db: " + mysql_error());

	my_set_charset($mysql_charset);

	// configcache init

	/* @var array Array of releaser's configuration */
	$CACHEARRAY=$CACHE->get('system','config');
	//$CACHEARRAY=false;
	if ($CACHEARRAY===false) {

		$CACHEARRAY = array();

		$cacherow = sql_query("SELECT * FROM cache_stats");

		while ($cacheres = mysql_fetch_array($cacherow))
		$CACHEARRAY[$cacheres['cache_name']] = $cacheres['cache_value'];

		$CACHE->set('system','config',$CACHEARRAY);
	}
	//configcache init end

	if (!$lightmode) userlogin();

	gzip();

	// INCLUDE SECURITY BACK-END
	require_once(ROOT_PATH . 'include/ctracker.php');
	/**
	 * This is original copyright, please leave it alone. Remember, that the Developers worked hard for weeks, drank ~67 litres of a beer (hoegaarden and baltica 7) and ate more then 15.1 kilogrammes of hamburgers to present this source. Don't be evil (C) Google
	 * @var constant Copyright of Kinokpk.com releaser
	 */
	define ("TBVERSION", ($CACHEARRAY['yourcopy']?str_replace("{datenow}",date("Y"),$CACHEARRAY['yourcopy']).". ":"")."Powered by <a class=\"copyright\" target=\"_blank\" href=\"http://www.kinokpk.com\">Kinokpk.com</a> <a class=\"copyright\" target=\"_blank\" href=\"http://dev.kinokpk.com\">releaser</a> ".RELVERSION." &copy; 2008-".date("Y").".");
	register_shutdown_function("mysql_close");

	return;
}

/**
 * Logins user
 * @return void
 */
function userlogin() {
	global $tracker_lang, $CACHEARRAY, $CACHE;
	unset($GLOBALS["CURUSER"]);
	$ip = getip();

	if ($CACHEARRAY['use_ipbans']) {

		$maskres = $CACHE->get('bans', 'query');
		if ($maskres ===false){
			$res = sql_query("SELECT mask FROM bans");
			$maskres = array();

			while (list($mask) = mysql_fetch_array($res))
			$maskres[] = $mask;

			$CACHE->set('bans', 'query', $maskres);
		}

		$BAN = new IPAddressSubnetSniffer($maskres);
		if ($BAN->ip_is_allowed($ip) ) {
			//write_log("$ip attempted to access tracker",'bans');
			die("Sorry, you (or your subnet) are banned by IP and MAC addresses!");

		}

	}

	if (empty($_COOKIE["uid"]) || empty($_COOKIE["pass"])) {
		$CACHEARRAY['ss_uri'] = $CACHEARRAY['default_theme'];
		getlang();
		user_session();
		return;
	}

	if (!is_valid_id($_COOKIE["uid"]) || strlen($_COOKIE["pass"]) != 32) {
		die("Cokie ID invalid or cookie pass hash problem.");

	}
	$id = (int) $_COOKIE["uid"];
	$res = sql_query("SELECT users.*,stylesheets.uri FROM users LEFT JOIN stylesheets ON users.stylesheet = stylesheets.id WHERE users.id = $id AND confirmed=1");// or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	if (!$row) {
		$CACHEARRAY['ss_uri'] = $CACHEARRAY['default_theme'];
		getlang();
		user_session();
		return;
	} elseif ((!$row['enabled']) && !defined("IN_CONTACT")) {
		$CACHEARRAY['ss_uri'] = $row['uri'];
		getlang('disableduser');
		/* $cronrow = sql_query("SELECT * FROM cron WHERE cron_name IN ('rating_enabled','rating_dislimit')");

		while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];    */
		headers(true);
		die($tracker_lang['disabled'].$row['dis_reason'].(($row['dis_reason']=='Your rating was too low.')?$tracker_lang['disabled_rating']:'').$tracker_lang['contact_admin']);

	}
	$sec = hash_pad($row["secret"]);
	if ($_COOKIE["pass"] != md5($row["passhash"].COOKIE_SECRET)) {
		$CACHEARRAY['ss_uri'] = $row['uri'];
		$pscheck = htmlspecialchars(trim((string)$_COOKIE['pass']));
		//$res = mysql_fetch_assoc(sql_query("SELECT id,class,username FROM users WHERE passhash=".sqlesc($pscheck)));
		//if (!$res) unset($res); else $res = "of <a href=\"userdetails.php?id=\"{$res['id']}\">".get_user_class_color($res['class'],$res['username'])."</a>";
		write_log(getip()." with cookie ID = $id <font color=\"red\">with passhash ".$pscheck." -> PASSHASH CHECKSUM FAILED!</font>",'security');
		getlang();
		user_session();
		return;
	}
	if (!$CACHEARRAY['ss_uri']) $CACHEARRAY['ss_uri'] = $row['uri'];

	$updateset = array();


	if ($ip != $row['ip'])
	$updateset[] = 'ip = '. sqlesc($ip);
	$updateset[] = 'last_access = ' . time();

	if (count($updateset))
	sql_query("UPDATE LOW_PRIORITY users SET ".implode(", ", $updateset)." WHERE id=" . $row["id"]);// or die(mysql_error());
	$row['ip'] = $ip;

	if ($row['override_class'] < $row['class'])
	$row['class'] = $row['override_class']; // Override class and save in GLOBAL array below.
	/* @var array Not full yet array of variables of current user
	* @see stdhead()
	*/
	$GLOBALS["CURUSER"] = $row;
	getlang();

	user_session();

	// $_SESSION = $CURUSER;

}

/**
 * Gets unix server load
 * @return number|string
 */
function get_server_load() {
	global $tracker_lang, $phpver;
	if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
		return 0;
	} elseif (@file_exists("/proc/loadavg")) {
		$load = @file_get_contents("/proc/loadavg");
		$serverload = explode(" ", $load);
		$serverload[0] = round($serverload[0], 4);
		if(!$serverload) {
			$load = @exec("uptime");
			$load = preg_split("load averages?: ", $load);
			$serverload = explode(",", $load[1]);
		}
	} else {
		$load = @exec("uptime");
		$load = preg_split("load averages?: ", $load);
		$serverload = explode(",", $load[1]);
	}
	$returnload = trim($serverload[0]);
	if(!$returnload) {
		$returnload = $tracker_lang['unknown'];
	}
	return $returnload;
}

/**
 * Begins user session
 * @return void
 */
function user_session() {
	global $CURUSER, $CACHEARRAY;

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
	if ($sid)
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
	global $CACHEARRAY;
	if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler' && $CACHEARRAY['use_gzip']) {
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
	global $CURUSER;

	$seconds = $seconds-date("Z")+$CURUSER['timezone']*3600;
	$search = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$replace = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
	if ($time == true)
	$data = @date("j F Y в H:i:s", $seconds);
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
	global $CACHEARRAY;
	return preg_replace ( "#\\<a(.*?)href(\\s*)\\=(\\s*)(\"|')(?!http://)(.*?)(\"|')(.*?)\\>#si", "<a\\1href=\\4" . $CACHEARRAY['defaultbaseurl'].'/' . "\\5\\6\\7>", $link );
}
/**
 * Sens email message(s)
 * @param string $to receiver email
 * @param string $fromname sender name
 * @param string $fromemail sender email
 * @param string $subject subject of message
 * @param string $body body of message, excluding <html> and <body> tags
 * @param string $multiple multiple receivers? Default false
 * @param string $multiplemail Multiple receivers mail adresses separated by space
 * @todo Normal SMTP functionality
 * @return boolean True or false while sending email
 */
function sent_mail($to,$fromname,$fromemail,$subject,$body,$multiple=false,$multiplemail='') {
	global $CACHEARRAY,$smtp,$smtp_host,$smtp_port,$smtp_from,$smtpaddress,$accountname,$accountpassword, $tracker_lang;
	getlang();
	# Sent Mail Function v.05 by xam (This function to help avoid spam-filters.)
	$result = true;
	$body = "<html>\n<body>\n".convert_local_urls($body)."</body>\n</html>\n";
	if ($CACHEARRAY['smtptype'] == 'default') {
		@mail($to, $subject, $body, "From: $fromemail") or $result = false;
	} elseif ($CACHEARRAY['smtptype'] == 'advanced') {
		# Is the OS Windows or Mac or Linux?
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol="\r\n";
			$windows = true;
		}
		elseif (strtoupper(substr(PHP_OS,0,3)=='MAC'))
		$eol="\r";
		else
		$eol="\n";
		$mid = md5(getip() . $fromname);
		$name = $_SERVER["SERVER_NAME"];
		$headers .= "From: \"$fromname\" <$fromemail>".$eol;
		$headers .= "Reply-To: \"$fromname\" <$fromemail>".$eol;
		$headers .= "Return-Path: $fromname <$fromemail>".$eol;
		$headers .= "Message-ID: <$mid.thesystem@$name>".$eol;
		$headers .= "X-Mailer: PHP v".phpversion().$eol;
		$headers .= "MIME-Version: 1.0".$eol;
		$headers .= "Content-Type: text/html; charset=\"".$tracker_lang['language_charset']."\"".$eol;
		$headers .= "X-Sender: PHP".$eol;
		if ($multiple)
		$headers .= "Bcc: $multiplemail.$eol";
		if ($smtp) {
			ini_set('SMTP', $smtp_host);
			ini_set('smtp_port', $smtp_port);
			if ($windows)
			ini_set('sendmail_from', $smtp_from);
		}
		@mail($to, $subject, $body, $headers) or $result = false;

		ini_restore(SMTP);
		ini_restore(smtp_port);
		if ($windows)
		ini_restore(sendmail_from);
	} elseif ($CACHEARRAY['smtptype'] == 'external') {
		require_once(ROOT_PATH . 'include/smtp/smtp.lib.php');
		$mail = new smtp;
		$mail->debug(true);
		$mail->open($smtp_host, $smtp_port);
		if (!empty($accountname) && !empty($accountpassword))
		$mail->auth($accountname, $accountpassword);
		$mail->from($CACHEARRAY['siteemail']);
		$mail->to($to);
		$mail->subject($subject);
		$mail->body($body);
		$result = $mail->send();
		$mail->close();
	} else
	$result = false;
	if (!$result) write_log("Sent email to $to ($subject) <b>failed</b>",'email');
	return $result;
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
	global $tracker_lang;
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");
	if ($ajax)   header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);
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
 * Outputs theme header and builds current user variables for notification system
 * @param string $title Title addition
 * @param string $addition <head> tag additon, for example javascript file or css stylesheet link
 * @return void
 */
function stdhead($title = "", $addition = '') {

	global $CURUSER, $FUNDS, $CACHEARRAY, $ss_uri, $tracker_lang, $CRON;

	$row = unserialize($CACHEARRAY['siteonline']);

	if ($row["onoff"] !=1){
		$my_siteoff = 1;
		$my_siteopenfor = $row['class_name'];
	}
	//$row["onoff"] = 1;//АВАРИЙНЫЙ ВХОД: Раскомментировать строку, если не можете войти !!!
	headers(REL_AJAX);

	if (($row["onoff"] !=1) && (!$CURUSER)){
		die((!REL_AJAX?"<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset={$tracker_lang['language_charset']}\" /><title>{$CACHEARRAY['sitename']} :: Under construction/Сайт Закрыт!</title></head>":'')."<div id=\"pagecontent\">
        <table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>
        <h1 style='color: #CC3300;'>".$row['reason']."</h1>
        <h1 style='color: #CC3300;'>
        Пожалуйста, зайдите позже...</h1>
        <br /><center><form method='post' action='takesiteofflogin.php'>
        <table border='1' cellspacing='1' id='table1' cellpadding='3' style='border-collapse: collapse'>
        <tr><td colspan='2' align='center' bgcolor='#CC3300'>
        <font color='#FFFFFF'><b>Вход для обслуживающего персонала:</b></font></td></tr>
        <tr><td><b>Имя:</b></td>
        <td><input type='text' size=20 name='username'></td></tr><tr>
        <td><b>Пароль:</b></td>
        <td><input type='password' size=20 name='password'></td>
        </tr><tr>
        <td colspan='2' align='center'>
        <input type='submit' value='Войти!'></td>
        </tr></table>
        </form></center>
        </td></tr></table></div>");
	}
	elseif (($row["onoff"] !=1) and (($CURUSER["class"] < $row["class"]) && ($CURUSER["id"] != 1))){

		die((!REL_AJAX?"<title>{$CACHEARRAY['sitename']} | Under construction/Сайт Закрыт!</title>":'')."<div id=\"pagecontent\">
        <table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>
        <h1 style='color: #CC3300;'>".$row['reason']."</h1>
        <h1 style='color: #CC3300;'>
        Пожалуйста, зайдите позже...</h1></td></tr></table></div>");
	}

	$title = $CACHEARRAY['sitename']. " | " . strip_tags($title);

	if (isset($_GET['styleid']) && $CURUSER) {
		if (is_valid_id($_GET['styleid'])) {
			$styleid = $_GET['styleid'];
			sql_query("UPDATE users SET stylesheet = $styleid WHERE id=" . $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
			safe_redirect(" {$CACHEARRAY['defaultbaseurl']}/");
			//$CURUSER["stylesheet"] = $styleid;
		} else stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
	}
	$ss_uri = $CACHEARRAY['ss_uri'];
	$CACHEARRAY['ss_uri'] = "themes/$ss_uri/";

	if ($CURUSER) {
		// GET GLOBALS FOR USER
		$allowed_types = array ('unread', 'inbox', 'outbox', 'torrents', 'comments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments', 'pages', 'pagecomments','friends');//,'seeding','leeching','downloaded');
		if (get_user_class() >= UC_MODERATOR) {
			$allowed_types_moderator = array('users', 'reports', 'unchecked');
			$allowed_types = array_merge($allowed_types,$allowed_types_moderator);
		}
		$cronrow = sql_query("SELECT * FROM cron WHERE cron_name IN ('pm_delete_sys_days','pm_delete_user_days')");

		while ($cronres = mysql_fetch_assoc($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];
		$secs_system = $CRON['pm_delete_sys_days']*86400; // Количество дней
		$dt_system = time() - $secs_system; // Сегодня минус количество дней
		$secs_all = $CRON['pm_delete_user_days']*86400; // Количество дней
		$dt_all = time() - $secs_all; // Сегодня минус количество дней

		foreach ($allowed_types as $type) {
			switch ($type) {
				case 'unread' :  $addition = "location=1 AND receiver={$CURUSER['id']} AND unread=1 AND IF(archived_receiver=1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))"; $table='messages'; $noadd=true; break;
				case 'inbox' :  $addition = "location=1 AND receiver={$CURUSER['id']} AND IF(archived_receiver=1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))"; $table='messages'; $noadd=true; break;
				case 'outbox' : $addition = "saved=1 AND sender={$CURUSER['id']} AND IF(archived_receiver<>1, 1=1, IF(sender=0,added>$dt_system,added>$dt_all))"; $table = 'messages'; $noadd=true; break;
				case 'unchecked' : $addition = 'moderatedby=0'; $table = 'torrents'; $noadd=true; break;
				case 'reports' : $noadd=true; break;
				case 'friends' : $noadd=true; $addition = "friendid={$CURUSER['id']} AND confirmed=0"; break;
				case 'pages' : $addition = " AND class <= ".get_user_class(); break;
				//case 'seeding' : $addition = "seeder=1 AND userid={$CURUSER['id']}"; $table= 'peers'; $noadd=true; break;
				// case 'leeching' : $addition = "seeder=0 AND userid={$CURUSER['id']}"; $table= 'peers'; $noadd=true; break;
				// case 'downloaded' : $addition = "snatched.finished=1 AND torrents.free=0 AND NOT FIND_IN_SET(torrents.freefor,userid) AND userid={$CURUSER['id']}"; $table = 'peers'; $noadd=true; break;
			}
			$noselect = @implode(',',@array_map("intval",$_SESSION['visited_'.$type]));

			$string = ($noselect?'id NOT IN ('.$noselect.') AND ':'').($noadd?'':"added>".$CURUSER['last_login']).$addition;

			$sql_query[]="(SELECT GROUP_CONCAT(id) FROM ".($table?$table:$type).($string?" WHERE $string":'').') AS '.$type;
			unset($addition);
			unset($table);
			unset($noadd);
		}
		$sql_query = "SELECT ".implode(', ', $sql_query);

		//die($sql_query);
		$notifysql = sql_query($sql_query);
		$notifs = mysql_fetch_assoc($notifysql);
		foreach ($notifs as $type => $value) if ($value) $CURUSER[$type] = explode(',', $value);
		//$notifs = array_combine($allowed_types,explode(',',$notifs));
		//foreach ($notifs as $name => $value) $CURUSER[$name] = $value;

	}

	if (!REL_AJAX) print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='. $tracker_lang['language_charset'].'" />
<meta name="Description" content="'.$CACHEARRAY['description'].'" />
<meta name="Keywords" content="'.$CACHEARRAY['keywords'].'" />
<base href="'.$CACHEARRAY['defaultbaseurl'].'" />
<!--Тоже любишь смотреть исходники HTML? Знаешь еще и PHP/MySQL? обратись к админам, наверняка для тебя есть местечко в нашей команде http://www.kinokpk.com/staff.php -->
<title>'.$title.'</title>
<link rel="stylesheet" href="themes/'.$ss_uri.'/'.$ss_uri.'.css" type="text/css"/>
<link rel="stylesheet" href="css/features.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.facebox.css" type="text/css"/>
<link rel="stylesheet" href="css/link/jquery.linkselect.style.select.css" type="text/css"/>
<!--[if IE]>
<link rel="stylesheet" href="css/features_ie.css" type="text/css"/>
<![endif]-->
<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$CACHEARRAY['defaultbaseurl'].'/rss.php" />
<link rel="alternate" type="application/atom+xml" title="Atom" href="'.$CACHEARRAY['defaultbaseurl'].'/atom.php" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>'
.((!$CURUSER || ($CURUSER['extra_ef']))?'
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->':'').
'<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.history.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.cookie.js"></script>
<script language="javascript" type="text/javascript" src="js/facebox.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.jgrowl_minimized.js"></script>
<script language="javascript" type="text/javascript" src="js/coding.js"></script>
<script language="javascript" type="text/javascript" src="js/ui.core.js"></script>
<script language="javascript" type="text/javascript" src="js/ui.checkbox.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.bind.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.usermode.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
<script language="javascript" type="text/javascript" src="js/features.js"></script>
<script language="javascript" type="text/javascript" src="js/swfobject.js"></script>
<script language="javascript" type="text/javascript" src="js/paginator3000.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.bgiframe.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.linkselect.js"></script>
'.$addition);
if (get_user_class() == UC_SYSOP) {
	if ($row['onoff'] != 1) print('<div align="center"><font color="red" size="20">ADMIN WARNING: SITE IS CLOSED FOR MAINTENANCE!</font></div>');
}
@require_once(ROOT_PATH."themes/" . $ss_uri . "/template.php");
@require_once(ROOT_PATH."themes/" . $ss_uri . "/stdhead.php");

return;
}

/**
 * Outputs theme footer and debug info
 * @return void
 */
function stdfoot() {
	global $CURUSER, $ss_uri, $tracker_lang, $queries, $tstart, $query_stat, $querytime, $CACHEARRAY, $CRON;

	@require_once(ROOT_PATH."themes/" . $ss_uri . "/template.php");
	@require_once(ROOT_PATH."themes/" . $ss_uri . "/stdfoot.php");
	// notification popup
	print generate_notify_popup();
	// rating warning
	print generate_ratio_popup_warning();
	// close old sessions
	print("<div id=\"debug\">");
	$secs = 1 * 3600;
	$time = time();
	$dt = $time - $secs;
	$updates = sql_query("SELECT uid, time FROM sessions WHERE uid<>-1 AND time < $dt") or sqlerr(__FILE__,__LINE__);
	while ($upd = mysql_fetch_assoc($updates)) {
		sql_query("UPDATE users SET last_login={$upd['time']} WHERE id={$upd['uid']}") or sqlerr(__FILE__,__LINE__);
	}
	sql_query("DELETE FROM sessions WHERE time < $dt") or sqlerr(__FILE__,__LINE__);
	/// end
	$cronrow=sql_query("SELECT * FROM cron WHERE cron_name IN ('last_cleanup','in_cleanup','in_remotecheck','num_cleaned','num_checked','remotecheck_disabled','last_remotecheck','autoclean_interval','remotecheck_interval')");
	while ($cronres = mysql_fetch_assoc($cronrow)) $CRON[$cronres['cron_name']]=$cronres['cron_value'];
	//		  var_dump($CRON);

	if (((($time-$CRON['last_cleanup'])>$CRON['autoclean_interval']) && !$CRON['in_cleanup'])) print '<img width="0px" height="0px" alt="" title="" src="cleanup.php"/>';
	if (!$CRON['remotecheck_disabled'] && (($time-$CRON['last_remotecheck'])>$CRON['remotecheck_interval'])) print '<img width="0px" height="0px" alt="" title="" src="remote_check.php"/>';
	if (($CACHEARRAY['debug_mode']) && count($query_stat) && ($CURUSER['class'] >= UC_SYSOP)) {
		foreach ($query_stat as $key => $value) {
			print("<div class=\"debug_text\">[".($key+1)."] => <b>".$value["seconds"]."</b> [$value[query]]</div>\n");
		}
		print '<div class="debug_text_second">'.sprintf($tracker_lang['all_db_q'],$querytime)."<br />";
		if (!$CRON['in_cleanup']) print $tracker_lang['cleanup_not_running'].'<br />';
		if ($CRON['remotecheck_disabled']) print $tracker_lang['remotecheck_disabled'].'<br />'; elseif  (!$CRON['in_remotecheck']) print $tracker_lang['remotecheck_not_running']; else print $tracker_lang['remotecheck_is_running']; print '<br />';
		print sprintf($tracker_lang['num_cleaned'],$CRON['num_cleaned'])."<br />";
		print sprintf($tracker_lang['num_checked'],$CRON['num_checked'])."<br />";
		print $tracker_lang['last_cleanup'].' '.mkprettytime($CRON['last_cleanup'],true,true)." (".get_elapsed_time($CRON['last_cleanup'])." {$tracker_lang['ago']})<br />";
		print $tracker_lang['last_remotecheck'].' '.mkprettytime($CRON['last_remotecheck'],true,true)." (".get_elapsed_time($CRON['last_remotecheck'])." {$tracker_lang['ago']})</div><br />";
		print('<div align="center"><font color="red"><b>'.$tracker_lang['in_debug'].'</b></font></div><br />');
	}
	if (!REL_AJAX) print('</div></div></body></html>');
	return;
}


/**
 * Generates notification system notificaton. As jgowl window or as static html code
 * @param boolean $blockmode Output as static html code? Default false
 * @return void|string Returns html code or null if fail
 */
function generate_notify_popup($blockmode = false)
{
	global $CURUSER,$tracker_lang;
	getlang('notifypopup');
	$allowed_types = explode(',',$CURUSER['notifs']);
	if ($allowed_types) {
		foreach ($allowed_types AS $type) {

			$temp = count(array_diff((array)$CURUSER[$type], (array)$_SESSION['visited_'.$type]));
			// if ($CURUSER['id']==1) var_dump(array_diff($CURUSER[$type], (array)$_SESSION['visited_'.$type]));
			if ($temp) $output .= '<a href="mynotifs.php?type='.$type.'">'.sprintf($tracker_lang['you_have_'.$type], $temp).'</a><br />';
		}} else {
			if ($blockmode)
			return '<div align="center">'.$tracker_lang['nothing_found'].'</div>';
			else return;
		}

		if ($output) {
			if ($_COOKIE['denynotifs'] && !$blockmode) return; else return

		'<script type="text/javascript" language="javascript">
		function denynotifs(a,t) {
		if (!t) t=0;
		$.cookie(\'denynotifs\',a,{ expires: t });
		if (a) al = "'.$tracker_lang['deny_success'].'"; else al = "'.$tracker_lang['enable_success'].'";
		alert(al);
		}
		'.($blockmode?"</script>\n".$tracker_lang['since_your_last_visit'].$output.($_COOKIE['denynotifs']?'<br /><small><a href="javascript:denynotifs(0);">'.$tracker_lang['enable_popup'].'</small></a>':''):'$.jGrowl("'.addslashes($output.'<hr /><small><a href="javascript:denynotifs(1);">'.$tracker_lang['deny_notifs_session'].'</a> '.$tracker_lang['or'].' <a href="javascript:denynotifs(1,30);">'.$tracker_lang['deny_notifs_month'].'</a></small>').'", { header: "'.addslashes($tracker_lang['since_your_last_visit']).'" });</script>');
		}
		else {
			if ($blockmode)
			return '<div align="center">'.$tracker_lang['since_your_last_visit'].'<br />'.$tracker_lang['nothing_found'].'</div>';
			else return;
		}

}

/**
 * Function to generate ratio popup warning
 * @param boolean $blockmode NO-Javascript static code? Default false
 * @return void|string HTML code with javascript
 */
function generate_ratio_popup_warning($blockmode = false) {
	global $CURUSER, $CRON, $tracker_lang;

	if (!$CURUSER) return;
	if (!$CRON['rating_freetime'] || !isset($CRON['rating_enabled'])) {
		$cronrow = sql_query("SELECT * FROM cron WHERE cron_name IN ('rating_freetime','rating_enabled')");

		while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];
	}
	if (!$CRON['rating_enabled']) return;
	if ($_COOKIE['denynotifs'] && !$blockmode) return;
	if (!$CURUSER['downloaded_rel'] && !$CURUSER['seeding']) {
		$query = sql_query("SELECT (SELECT SUM(1) FROM peers WHERE seeder=1 AND userid={$CURUSER['id']}) AS seeding, (SELECT SUM(1) FROM snatched LEFT JOIN torrents ON snatched.torrent=torrents.id WHERE snatched.finished=1 AND torrents.free=0 AND NOT FIND_IN_SET(torrents.freefor,userid) AND userid={$CURUSER['id']} AND torrents.owner<>{$CURUSER['id']}) AS downloaded");

		list($seeding,$downloaded) = mysql_fetch_array($query);
		$CURUSER['seeding'] = (int)$seeding;
		$CURUSER['downloaded_rel'] = (int)$downloaded;
	}

	if ($CURUSER['seeding'] && ((time()-$CURUSER['added'])>($CRON['rating_freetime']*86400)) && (get_user_class()<>UC_VIP) && $CURUSER['downloaded_rel'] && (($CURUSER['seeding']+$CURUSER['discount'])<$CURUSER['downloaded_rel'])) {
		getlang('ratiowarning');
		$znak = (($CURUSER['ratingsum']>0)?'+':'');

		$output = ($blockmode?'<hr/>':'').sprintf($tracker_lang['ratio_down'],$znak.$CURUSER['ratingsum']);
		return ($blockmode?$output:'<script type="text/javascript" language="javascript">$.jGrowl("'.addslashes($output).'", { header: "'.addslashes($tracker_lang['ratio_warning']).'", sticky:true });</script>');

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
	global $CURUSER;
	if (!$CURUSER) {
		safe_redirect("login.php?returnto=" . urlencode(basename($_SERVER["REQUEST_URI"])));
		exit();
	}
	return;
}

/**
 * Deletes torrent from database and folder
 * @param int $id id for torrent to be deleted
 */
function deletetorrent($id) {
	global $CURUSER;
	sql_query("DELETE FROM notifs WHERE checkid = $id AND type='comments'") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM torrents WHERE id = $id");
	sql_query("DELETE FROM bookmarks WHERE id = $id");
	foreach(explode(".","snatched.peers.files.comments.trackers") as $x)
	sql_query("DELETE FROM $x WHERE torrent = $id");
	@unlink("torrents/$id.torrent");
	write_log("<a href=\"userdetails.php?id={$CURUSER['id']}\">".get_user_class_color($CURUSER['class'],$CURUSER['username'])."</a> deleted torrent with id $id (system message)",'torrent');
	return;
}

/*function pager($rpp, $count, $href, $opts = array()) {
 $pages = ceil($count / $rpp);

 if (!$opts["lastpagedefault"])
 $pagedefault = 0;
 else {
 $pagedefault = floor(($count - 1) / $rpp);
 if ($pagedefault < 0)
 $pagedefault = 0;
 }

 if (isset($_GET["page"])) {
 $page = (int) $_GET["page"];
 if ($page < 0)
 $page = $pagedefault;
 }
 else
 $page = $pagedefault;

 $pager = "<td class=\"pager\">Страницы:</td><td class=\"pagebr\">&nbsp;</td>";

 $mp = $pages - 1;
 $as = "<b>«</b>";
 if ($page >= 1) {
 $pager .= "<td class=\"pager\">";
 $pager .= "<a href=\"{$href}page=" . ($page - 1) . "\" style=\"text-decoration: none;\">$as</a>";
 $pager .= "</td><td class=\"pagebr\">&nbsp;</td>";
 }

 $as = "<b>»</b>";
 if ($page < $mp && $mp >= 0) {
 $pager2 .= "<td class=\"pager\">";
 $pager2 .= "<a href=\"{$href}page=" . ($page + 1) . "\" style=\"text-decoration: none;\">$as</a>";
 $pager2 .= "</td>$bregs";
 }else	 $pager2 .= $bregs;

 if ($count) {
 $pagerarr = array();
 $dotted = 0;
 $dotspace = 3;
 $dotend = $pages - $dotspace;
 $curdotend = $page - $dotspace;
 $curdotstart = $page + $dotspace;
 for ($i = 0; $i < $pages; $i++) {
 if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
 if (!$dotted)
 $pagerarr[] = "<td class=\"pager\">...</td><td class=\"pagebr\">&nbsp;</td>";
 $dotted = 1;
 continue;
 }
 $dotted = 0;
 $start = $i * $rpp + 1;
 $end = $start + $rpp - 1;
 if ($end > $count)
 $end = $count;

 $text = $i+1;
 if ($i != $page)
 $pagerarr[] = "<td class=\"pager\"><a title=\"$start&nbsp;-&nbsp;$end\" href=\"{$href}page=$i\" style=\"text-decoration: none;\"><b>$text</b></a></td><td class=\"pagebr\">&nbsp;</td>";
 else
 $pagerarr[] = "<td class=\"highlight\"><b>$text</b></td><td class=\"pagebr\">&nbsp;</td>";

 }
 $pagerstr = join("", $pagerarr);
 $pagertop = "<table class=\"main\"><tr>$pager $pagerstr $pager2</tr></table>\n";
 <<<<<<< HEAD
 $pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\"><tr>$pager $pagerstr $pager2</tr></table>\n"
 =======
 $pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\"><tr>$pager $pagerstr $pager2</tr></table>\n";
 >>>>>>> ce57a26a436f4363cdb37c78debc150c007cc9eb
 }
 else {
 $pagertop = $pager;
 $pagerbottom = $pagertop;
 }

 $start = $page * $rpp;

 return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
 }*/

/**
 * Page navigation header and footer
 * @param int $rpp records per page
 * @param int $count total records
 * @param string $href a link to record
 * @param array $opts array of options. Default array(0)
 * @return array Array to be used in requested script
 */
function pager($rpp, $count, $href, $opts = array()) {
	global $tracker_lang;
	$pages = ceil($count / $rpp);

	if (!$opts["lastpagedefault"])
	$pagedefault = 1;
	else {
		$pagedefault = floor(($count - 1) / $rpp)+1;
		if ($pagedefault < 0)
		$pagedefault = 0;
	}

	if (isset($_GET["page"])) {
		$page = (int)$_GET["page"];
		if ($page < 0)
		$page = $pagedefault;
	}
	else
	$page = $pagedefault;

	if ($page==0) {
		$pagipage=1;
		$start = $page  * $rpp;
	}
	else    {
		$pagipage = $page ;
		$start = ($page -1) * $rpp;
	}


	if ( fuckIE() ) {
		$pagerr ='<div align="center"><div class="paginator" id="paginator1"></div>
	<div class="paginator_pages">'.sprintf($tracker_lang['pager_text'],$count,$pages,$rpp,($start+1),((($start+$rpp)>$count)?$count:($start+$rpp))).'</div>
	<script type="text/javascript">
	window.onload = function(){
		pag1 = new Paginator(\'paginator1\', '.$pages.', 15, "'. ($pagipage ) .'", "'.$href.'page=");
		pag2 = new Paginator(\'pag2\', '.$pages.', 15, "'. ($pagipage ) .'", "'.$href.'page=");
		Paginator.resizePaginator(pag1);
		Paginator.resizePaginator(pag2);
	}
	</script>';

		$pagerre ='<div align="center"><div id="dataa">
	<div class="paginator" id="pag2"></div>
	<div class="paginator_pages">'.sprintf($tracker_lang['pager_text'],$count,$pages,$rpp,($start+1),((($start+$rpp)>$count)?$count:($start+$rpp))).'</div>';
	} else {

		$pagerr ='<div align="center"><div class="paginator" id="paginator1"></div>
	<div class="paginator_pages">'.sprintf($tracker_lang['pager_text'],$count,$pages,$rpp,($start+1),((($start+$rpp)>$count)?$count:($start+$rpp))).'</div>
				<script type="text/javascript">
		pag1 = new Paginator(\'paginator1\', '.$pages.', 15, "'. ($pagipage ) .'", "'.$href.'page=");
	</script>';

		$pagerre ='<div align="center"><div class="paginator" id="pag2"></div>
	<div class="paginator_pages">'.sprintf($tracker_lang['pager_text'],$count,$pages,$rpp,($start+1),((($start+$rpp)>$count)?$count:($start+$rpp))).'</div>
				<script type="text/javascript">
		pag2 = new Paginator(\'pag2\', '.$pages.', 15, "'. ($pagipage ) .'", "'.$href.'page=");
	</script></div>';
	}
	return array($pagerr, $pagerre, "LIMIT $start,$rpp");
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
 * General function to display comment tables
 * @param array $rows associative array of rows
 * @param string $redaktor script whitch handlers comment table (without .php extention)
 * @return void
 */
function commenttable($rows, $redaktor = "comment") {
	global $CURUSER, $CACHEARRAY, $tracker_lang;

	$count = 0;
	foreach ($rows as $row)	{
		if ($row["last_access"] > (time() - 300)) {
			$online = "online";
			$online_text = "В сети";
		} else {
			$online = "offline";
			$online_text = "Не в сети";
		}

		print("<div id=\"comm{$row['id']}\"><table class=maibaugrand width=100% border=1 cellspacing=0 cellpadding=3>");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\" height=\"24\">");

		if (isset($row["username"]))
		{
			$title = $row["title"];
			if ($title == ""){
				$title = get_user_class_name($row["class"]);
			}else{
				$title = htmlspecialchars($title);
			}
			print("<img src=\"pic/button_".$online.".gif\" alt=\"".$online_text."\" title=\"".$online_text."\" style=\"position: relative; top: 2px;\" border=\"0\" height=\"14\">"
			." <a href=userdetails.php?id=" . $row["user"] . " class=altlink_white><b>". get_user_class_color($row["class"], $row["username"]) . "</b></a> ".get_user_icons($row).
			":&nbsp;Re (<a href=\"{$row['link']}\">#{$row['id']}</a>): ".((strlen($row['subject'])>70)?makesafe(substr($row['subject'],0,67).'...'):makesafe($row['subject']))."<span style=\"float: right\"><small>{$tracker_lang['rate_comment']}</small> ".ratearea($row['ratingsum'],$row['id'],$redaktor.'s',(($CURUSER['id']==$row['user'])?$row['id']:0))."</span>");

		} else {
			print("<i>Анонимус</i>:&nbsp;Re (<a href=\"{$row['link']}\">#{$row['id']}</a>): ".((strlen($row['subject'])>70)?makesafe(substr($row['subject'],0,67).'...'):makesafe($row['subject']))."\n");
		}

		$avatar = ($CURUSER["avatars"] ? htmlspecialchars($row["avatar"]) : "");
		if (!$avatar){$avatar = "pic/default_avatar.gif"; }
		$text = format_comment($row["text"]);

		if ($row["editedby"]) {
			//$res = mysql_fetch_assoc(sql_query("SELECT * FROM users WHERE id = $row[editedby]")) or sqlerr(__FILE__,__LINE__);
			$text .= "<p><font size=1 class=small>Последний раз редактировалось <a href=userdetails.php?id=$row[editedby]><b>$row[editedbyname]</b></a> ".mkprettytime($row['editedat'])." (".get_elapsed_time($row['editedat'],false)." {$tracker_lang['ago']})</font></p>\n";
	 }
		print("</td></tr>");
		print("<tr valign=top>\n");
		print("<td style=\"padding: 0px; width: 5%;\" align=\"center\"><img src=\"$avatar\"><br/>".ratearea($row['urating'],$row['user'],'users',$CURUSER['id'])."</td>\n");
		print("<td width=100% class=text>");
		//print("<span style=\"float: right\"><a href=\"#top\"><img title=\"Top\" src=\"pic/top.gif\" alt=\"Top\" border=\"0\" width=\"15\" height=\"13\"></a></span>");
		print("$text</td>\n");
		print("</tr>\n");
		print("<tr><td class=colhead align=\"center\" colspan=\"2\">");
		print"<div style=\"float: left; width: auto;\">"
		.($CURUSER ? "[<a href=\"javascript:quote_comment('{$row['username']}');\" class=\"altlink_white\">Цитировать выбранное</a>] [<a href=\"".$redaktor.".php?action=quote&amp;cid=$row[id]\" class=\"altlink_white\">Цитата</a>]" : "")
		.($row["user"] == $CURUSER["id"] || get_user_class() >= UC_MODERATOR ? " [<a href=".$redaktor.".php?action=edit&amp;cid=$row[id] class=\"altlink_white\">Изменить</a>]" : "")
		.(get_user_class() >= UC_MODERATOR ? " [<a href=\"".$redaktor.".php?action=delete&amp;cid=$row[id]\" onClick=\"return confirm('Вы уверены?')\" class=\"altlink_white\">Удалить</a>]" : "")
		.(get_user_class() >= UC_MODERATOR ? " IP: ".($row["ip"] ? "<a href=\"usersearch.php?ip=$row[ip]\" class=\"altlink_white\">".$row["ip"]."</a>" : "Неизвестен" ) : "")
		.reportarea($row['id'],$redaktor.'s')."</div>";

		print("<div align=\"right\"><small>Комментарий добавлен: ".mkprettytime($row["added"])."</small></td></tr>");
		print("</table><br /></div>");
		// set that instance was visited
		set_visited($redaktor.'s',$row['id']);
	}
	return;
}


/**
 * Generates 3d flash categories-cloud html code
 * @return string The html code
 * @see cloud()
 */
function cloud3d() {
	global $CACHE;
	$tags = $CACHE->get('system','cat_tags');
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

		$CACHE->set('system','cat_tags',$tags);
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
		$cloud_tags[] = urlencode("<a href='browse.php?cat=" . $taginfo['id'] . "' style='font-size:". floor($size) . "px;'>"). $tag. urlencode("(".$taginfo['count'].")</a>");
		$cloud_links[] = "<br /><a href='browse.php?cat=" . $taginfo['id'] . "' style='font-size:". floor($size) . "px;'>$tag</a><br />";
		$i++;
	}
	$cloud_links[$i-1].="Ваш браузер не поддерживает flash!";
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
 * Prints pages table
 * @param array $res Array to be processed
 */
function pagetable($res) {
	global $tree, $CURUSER, $tracker_lang;
	if (!$tree) $tree = make_pages_tree(get_user_class());

	$owned = $moderator = 0;
	if (get_user_class() >= UC_MODERATOR)
	$owned = $moderator = 1;
	elseif ($CURUSER["id"] == $row["owner"])
	$owned = 1;
	global $CURUSER, $CACHEARRAY, $tracker_lang;


	print("<tr>\n");

	print ('<td class="colhead" align="center"><a href="pageupload.php">Создать свою
страницу</a></td>
</tr>
<tr>
	<td>
	<table style="width: 100%;">
		<tr>
			<td class="colhead" align="left" style="width: 60%;">Название /
			Информация о странице</td>
			<td class="colhead" align="center" style="width: 5%;">Комм.</td>
			<td class="colhead" align="center" style="width: 10%;">Автор</td>');
	if (get_user_class() >= UC_MODERATOR) {
		print("<td class=\"colhead\" align=\"center\" style=\"width:90px;\">Индексируется</td>");
		print("<td class=\"colhead\" align=\"center\" style=\"width:131px;\">Запрет комментов</td>");
	}
	print("</tr>
		</table>
	</td>
</tr></tr>\n");

	print("<tbody id=\"highlighted\">");


	foreach ($res as $row) {
		$id = $row["id"];
		print("<tr".($row["sticky"] ? " class=\"highlight\"" : "").">\n");
		?>

<td>
<table width="100%">


<?php
//print("<td class=\"colhead\" align=\"center\">".'Создатель'."333333333</td>\n");

/*if (get_user_class() >= UC_MODERATOR) {
 print("<td class=\"colhead\" align=\"center\" style=\"width:5%\">Индексируется</td>");
 print("<td class=\"colhead\" align=\"center\" style=\"width:5%\">Запрет комментов</td>");
 }*/

print('<tr><td colspan="13" align="left"><div class="page_cat">'.$row['cat_names'].'</div></td></tr><tr>');///ТЭГИ
//print("")
print("<td align=\"left\" width=\"60%\">".($row["sticky"] ? "Важная: " : "")."<a class=\"browselink\" href=\"pagedetails.php?");//ВАЖНО
print("id=$id");
print("\"><b>{$row['name']}</b></a>&nbsp;\n");//имя

if ($owned)
print("<a href=\"pageedit.php?id=$row[id]\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a>\n");

print("<br /><i>".mkprettytime($row["added"])."</i> &nbsp;&nbsp;");//ВРЕМЯ

print("</td>\n");

if (!$row["comments"])
print("<td align=\"center\" style=\"width:5%\">" . $tracker_lang['no'] . "</td>\n");
else {
	print("<td align=\"center\" style=\"width:5%\"><b><a href=\"pagedetails.php?id=$id#startcomments\">" . $row["comments"] . "</a></b></td>\n");
}

print("<td align=\"center\" style=\"width:10%\">" . (isset($row["username"]) ? ("<a href=\"userdetails.php?id=" . $row["owner"] . "\"><b>" . get_user_class_color($row["class"], $row["username"]) . "</b></a>") : "<i>{$tracker_lang['from_system']}</i>") . "</td>\n");
if (get_user_class() >= UC_MODERATOR) {
	print("<td align=\"center\" style=\"width:90px;\">".($row['indexed']?"<font color=\"red\"><b>{$tracker_lang['yes']}</b></font>":"<font color=\"green\"><b>{$tracker_lang['no']}</b></font>")."</td>\n");//индексация
	print("<td align=\"center\" style=\"width:130px\">".($row['denycomments']?"<b>{$tracker_lang['yes']}</b>":"<b>{$tracker_lang['no']}</b>")."</td>\n");//запрет комментов

}
print("</tr></table>\n");
print('</td></tr>');

	}

	print("</tbody>");

	//print("</table>\n");

	return;
}
/**
 * General function to display table of torrents
 * @param array $res Array of rows
 * @param string $variant Name of script where table is dispaing.
 * @return void
 */
function torrenttable($res, $variant = "index") {
	global $CURUSER, $CACHEARRAY, $tracker_lang, $tree;
	if (!$tree) $tree = make_tree();

	$owned = $moderator = false;
	if (get_user_class() >= UC_MODERATOR)
	$owned = $moderator = true;
	elseif ($CURUSER["id"] == $row["owner"])
	$owned = true;

	if ($CACHEARRAY['use_wait'])
	if (($CURUSER["class"] < UC_VIP) && $CURUSER) {
		$gigs = $CURUSER["uploaded"] / (1024*1024*1024);
		$ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
		if ($ratio < 0.5 || $gigs < 5) $wait = 48;
		elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
		elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
		elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
		else $wait = 0;
	}

	print("<tr>\n");

	?>
	<td class="colhead" align="center"><?=$tracker_lang['added'];?></td>
	<td class="colhead" align="left"><?=$tracker_lang['name'];?></td>
	<?

	if ($wait)
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['wait']."</td>\n");

	if ($variant == "mytorrents")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['visible']."</td>\n");


	?>

	<td class="colhead" align="center"><?=$tracker_lang['comments'];?></td>
	<? if ($CACHEARRAY['use_ttl']) {
		?>
	<td class="colhead" align="center"><?=$tracker_lang['ttl'];?></td>
	<?
	}
	?>
	<td class="colhead" align="center"><?=$tracker_lang['size'];?></td>

	<td class="colhead" align="center"><?=$tracker_lang['seeders'];?>|<?=$tracker_lang['leechers'];?></td>
	<?


	if ((get_user_class() >= UC_MODERATOR) && $variant == "index") {
		print("<td class=\"colhead\" align=\"center\">".$tracker_lang['uploadeder']."</td>\n");
		print("<td class=\"colhead\" align=\"center\" style=\"width: 50px;\">Изменен</td>");
		print("<td class=\"colhead\" align=\"center\" style=\"width: 60px;\">Проверен</td>");
		print("<td class=\"colhead\" align=\"center\" style=\"width: 50px;\">Забанен</td>");
		print("<td class=\"colhead\" align=\"center\" style=\"width: 15px;\">Скрыт</td>");
	}

	if ($variant == "bookmarks")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['delete']."</td>\n");

	print("</tr>\n");

	print("<tbody id=\"highlighted\">");

	foreach ($res as $row) {
		if ($row['rgid']) $rgcontent = ($row['rgimage']?"<img style=\"border:none;\" title=\"Релиз группы {$row['rgname']}\" src=\"{$row['rgimage']}\"/>":$row['rgname']);

		if ((get_user_class()<UC_MODERATOR) && !$row['relgroup_allowed'] && $row['rgid']) {
			$row['name'] = $tracker_lang['relgroup_release'].'&nbsp;'.$rgcontent;
			$row['images'] = 'pic/privaterg.gif';
		}
		$id = $row["id"];
		print("<tr".($row["sticky"]? " class=\"highlight\"" : "").">\n");
		print("<td align=\"center\" style=\"padding: 0pc\">");
		print("<br /><i>".mkprettymonth($row["added"])."</i> &nbsp&nbsp");

		if ($wait){
			$elapsed = floor((time() - strtotime($row["added"])) / 3600);
			if ($elapsed < $wait){
				$color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
				print("<td align=\"center\"><nobr><font color=\"$color\">" . number_format($wait - $elapsed) . " h</font></nobr></td>\n");
			}else
			print("<td align=\"center\"><nobr>".$tracker_lang['no']."</nobr></td>\n");
		}

		$dispname = $row["name"];
		$thisisfree = ($row[free] ? "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\"/>" : "");
		print("<td align=\"left\">".($row["sticky"] ? "Важный: " : "")."
		<div class=\"name_browse\"><a class=\"download\"  href=\"download.php?id=$id\" onclick=\"javascript:$.facebox({ajax:'download.php?id=$id'}); return false;\"><img src=\"pic/download.gif\" border=\"0\" alt=\"".$tracker_lang['download']."\" title=\"".$tracker_lang['download']."\"/></a><a href=\"details.php?");
		print("id=$id");
		print("\"><b>$dispname</b></a>".(@in_array($id,$CURUSER['torrents'])?"&nbsp;<img title=\"{$tracker_lang['new']}\" src=\"pic/new.png\"/>":'')."</div>\n");

		if ($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR)
		$owned = 1;
		else
		$owned = 0;

		if ($owned)
		print("<small><a id='descr' href=\"edit.php?id=$row[id]\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a></small>\n");


		if ($variant != "bookmarks" && $CURUSER)
		print("<small><a href=\"bookmark.php?torrent=$row[id]\"><img border=\"0\" src=\"pic/bookmark.gif\" alt=\"".$tracker_lang['bookmark_this']."\" title=\"".$tracker_lang['bookmark_this']."\" /></a></small>\n");
			
		if ($row['images']) {
			$row['images'] = explode(',',$row['images']);
			$image = array_shift($row['images']);

			if ($image)
			print("<div class=\"cat_pic\"><small><a href=\"javascript:$.facebox({image:'$image'});\"><img border=\"0\" src=\"pic/poster.gif\" alt=\"".$tracker_lang['poster']."\" title=\"".$tracker_lang['poster']."\" /></a></small></div>");
		}
		if (isset($row["cat_names"]))
		print('<div class="cat_name" ><small>'.$row['cat_names'].'</small></div>');
		print("\n");


			

		print("</td>\n");

		if ($variant == "mytorrents") {
			print("<td align=\"right\">");
			if (!$row["visible"])
			print("<font color=\"red\"><b>".$tracker_lang['no']."</b></font>");
			else
			print("<font color=\"green\">".$tracker_lang['yes']."</font>");
			print("</td>\n");
		}

		/*	if ($row["type"] == "single")
			print("<td align=\"right\">" . $row["numfiles"] . "</td>\n");
			else {
			if ($variant == "index")
			print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;filelist=1\">" . $row["numfiles"] . "</a></b></td>\n");
			else
			print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;filelist=1#filelist\">" . $row["numfiles"] . "</a></b></td>\n");
			}
			*/
		if (!$row["comments"])
		print("<td align=\"right\">" . $row["comments"] . "</td>\n");
		else {
			if ($variant == "index")
			print("<td align=\"right\"><b><a href=\"details.php?id=$id#comments\">" . $row["comments"] . "</a></b></td>\n");
			else
			print("<td align=\"right\"><b><a href=\"details.php?id=$id#comments\">" . $row["comments"] . "</a></b></td>\n");
		}

		//		print("<td align=center><nobr>" . str_replace(" ", "<br />", $row["added"]) . "</nobr></td>\n");
		$ttl = ($CACHEARRAY['ttl_days']*24) - floor((time() - ($row["last_action"])) / 3600);
		if ($ttl == 1) $ttl .= " час"; else $ttl .= "&nbsp;часов";
		if ($CACHEARRAY['use_ttl'])
		print("<td align=\"center\">$ttl</td>\n");
		print("<td align=\"center\">" . str_replace(" ", "", mksize($row["size"])) . "</td>\n");
		//		print("<td align=\"right\">" . $row["views"] . "</td>\n");
		//		print("<td align=\"right\">" . $row["hits"] . "</td>\n");

		print("<td align=\"center\" nowrap>");
		if ($row["filename"] != 'nofile') {
			if ($row["seeders"]) {
				if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"]; else $ratio = 1;
				print("<b><a href=\"torrent_info.php?id=$id\"><font color=" .
				get_slr_color($ratio) . ">" . number_format($row["seeders"]) . "</font></a></b>");
			}
			else
			print($tracker_lang['no']);

			print("|");

			if ($row["leechers"]) {
				print("<b><a href=\"torrent_info.php?id=$id\">" .
				number_format($row["leechers"]) . ($peerlink ? "</a>" : "") .
				   "</b>");
			}
			else
			print($tracker_lang['no']);
		} else print("<b>N/A</b>\n");
		print("</td>");

		/*	if ($variant == "index" || $variant == "bookmarks")
			print("<td align=\"center\">" . (isset($row["username"]) ? ("<a href=\"userdetails.php?id=" . $row["owner"] . "\"><b>" . get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a>") : "<i>(unknown)</i>") . "</td>\n");
			*/
		if ((get_user_class() >= UC_MODERATOR) && $variant == "index") {
			print("<td align=\"center\">" . (isset($row["username"]) ? ("<a href=\"userdetails.php?id=" . $row["owner"] . "\"><b>" . get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a>") : "<i>(unknown)</i>") . "</td>\n");
			print("<td align=\"center\" style=\"width: 50px;\">".(!$row['moderated']?"<font color=\"green\"><b>{$tracker_lang['no']}</b></font>":"<font color=\"red\"><b>{$tracker_lang['yes']}</b></font>")."</td>\n");
			print("<td align=\"center\" style=\"width: 60px;\">".(!$row['moderatedby']?"<font color=\"red\"><b>{$tracker_lang['no']}</b></font>":"
<a href=\"userdetails.php?id={$row['moderatedby']}\">".get_user_class_color($row['modclass'],$row['modname'])."</a>")."</td>");
			print("<td align=\"center\" style=\"width: 50px;\">".(!$row['banned']?"<font color=\"green\"><b>{$tracker_lang['no']}</b></font>":"<font color=\"red\"><b>{$tracker_lang['yes']}</b></font>")."</td>\n");
			print("<td align=\"center\" style=\"width: 15px;\">".($row['visible']?"<font color=\"green\"><b>{$tracker_lang['no']}</b></font>":"<font color=\"red\"><b>{$tracker_lang['yes']}</b></font>")."</td>\n");

		}
		if ($variant == "bookmarks")
		print ("<td align=\"center\"><input type=\"checkbox\" name=\"delbookmark[]\" value=\"" . $row['bookmarkid'] . "\" /></td>");

		print("</tr>\n");

	}

	print("</tbody>");

	//print("</table>\n");

	return $rows;
}

/**
 * Funtion to generate pretty month
 * @param int $seconds UNIX-style date
 * @return string Nice month
 */
function mkprettymonth($seconds) {
	$search = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$replace = array('янв','фев','марта','апреля','мая','июня','июля','авг','сент','окт','ноя','дек');
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
	$pics .= $arr["warned"] ? "<img src=pic/$warnedpic alt=\"Warned\" border=0 $style>" : "";
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
	global $CACHE;
	$cats = $CACHE->get('trees','cat_assoc_'.$type);
	if ($cats===false) {
		$cats=array();
		$catsrow = sql_query("SELECT id,name FROM $type ORDER BY sort ASC");
		while ($catres= mysql_fetch_assoc($catsrow)) $cats[$catres['id']]=$catres['name'];
		$CACHE->set('trees','cat_assoc_'.$type,$cats);
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
	global $tracker_lang, $CURUSER;
	getlang('comment_notifs');
	$subject = sqlesc($tracker_lang['new_comment']);
	$msg = sqlesc(sprintf($tracker_lang['comment_notice_'.$type],$page));
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, ".time().", $msg, 0, $subject FROM notifs WHERE checkid = $id AND type='$type' AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
	sql_query("INSERT INTO cron_emails (email, subject, body) SELECT users.email, $subject, $msg FROM notifs LEFT JOIN users ON userid=users.id WHERE checkid = $id AND type='$type' AND FIND_IN_SET('$type',emailnotifs) AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
	return;
}

/**
 * Sends email notifications. This is a part of notification system
 * @param string $type notification type
 * @param string $text notification text
 * @param int $id id of user to be discarded from notify. Default is 0 as 'to all'
 * @return void
 * @todo check messages sending
 */
function send_notifs($type,$text = '',$id = 0) {
	global $tracker_lang, $CURUSER, $CACHEARRAY;
	getlang('delayed_notifs');
	$subject = sqlesc($tracker_lang['new_'.$type]);
	$msg = sqlesc($tracker_lang['notice_'.$type].$text."<hr/ ><a href=\"{$CACHEARRAY['defaultbaseurl']}\">{$CACHEARRAY['sitename']}</a><br /><br /><div align=\"right\">{$tracker_lang['notifications_cp']}</div>");
	//	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, ".time().", $msg, 0, $subject FROM notifs WHERE checkid = $id AND type='$type' AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
	sql_query("INSERT INTO cron_emails (email, subject, body) SELECT users.email, $subject, $msg FROM users WHERE FIND_IN_SET('$type',emailnotifs) AND ".(!$id?"id != ".(int)$CURUSER['id']:"id = $id")) or sqlerr(__FILE__,__LINE__);
}

/**
 * Checks that user is notified, and outputs suggest/discart notification link
 * @param int $id id of notification subject
 * @param string $type type of notification
 * @return string Notification html code
 */
function is_i_notified($id,$type) {
	global $CURUSER,$tracker_lang;
	$res = sql_query("SELECT id FROM notifs WHERE checkid=$id AND userid={$CURUSER['id']} AND type='$type'") or sqlerr(__FILE__,__LINE__);
	list($cid) = mysql_fetch_array($res);
	if ($cid) return("<div id=\"notifarea-$id\" style=\"display:inline;\"><a href=\"notifs.php?action=deny&amp;id=$cid\" onclick=\"return notifyme($cid,$id,'','deny')\">{$tracker_lang['monitor_comments_disable']}</a></div>");
	else return("<div id=\"notifarea-$id\" style=\"display:inline;\"><a href=\"notifs.php?id=$id&amp;type=$type\" onclick=\"return notifyme('',$id,'$type','')\">{$tracker_lang['monitor_comments']}</a></div>");
}

/**
 * Makes pages tree.
 * @param int $class_access minimal class to be accessed to these pages. Default 0 e.g. 'all'
 * @param unknown_type $class_edit minimal class to be accessed to edit these pages, default NULL e.g. 'all'
 * @return array Tree array
 */
function make_pages_tree($class_access = 0, $class_edit = NULL) {
	//print("WHERE class <= $class_access".(isset($class_edit)?" AND class_edit <= $class_edit":''));
	return make_tree('pagescategories',"WHERE class <= $class_access".(isset($class_edit)?" AND class_edit <= $class_edit":''));
}

/**
 * Makes tree of elemants
 * @param string $table Table to be used to make tree
 * @param unknown_type $condition Condition to be added to sql query whitch making a tree
 * @return multitype:|multitype:
 */
function &make_tree($table='categories',$condition='')
{
	global $CACHE;
	if ($condition) $cacheadd = '-'.md5($condition);

	$tree = $CACHE->get('trees',$table.$cacheadd);
	if ($tree === false) {
		$tree = array();

		$query = sql_query('SELECT id,parent_id,name,image FROM '.$table.($condition?' '.$condition:'').' ORDER BY sort ASC') or die(mysql_error());
		if (!$query) return $tree;

		$nodes = array();
		$keys = array();
		while (($node = mysql_fetch_assoc($query)))
		{
			//if ($node['childs'] === '1') //если есть поле определяющее наличие дочерних веток
			//    $node['nodes'] = array();  //то добавляем к записи узел (массив дочерних веток) на данном этапе
			$nodes[$node['id']] =& $node; //заполняем список веток записями из БД
			$keys[] = $node['id']; //заполняем список ключей(ID)
			unset($node);
		}
		mysql_free_result($query);

		foreach ($keys as $key)
		{
			/**
			 * если нашли главную ветку(или одну из главных), то добавляем
			 * её в дерево
			 */
			if ($nodes[$key]['parent_id'] === '0')
			$tree[] =& $nodes[$key];

			/**
			 * else находим родительскую ветку и добавляем текущую
			 * ветку к дочерним элементам родит.ветки.
			 */
			else
			{
				if (isset($nodes[ $nodes[$key]['parent_id'] ])) //на всякий случай, вдруг в базе есть потерянные ветки
				{
					if (! isset($nodes[ $nodes[$key]['parent_id'] ]['nodes'])) //если нет поля определяющего наличие дочерних веток
					$nodes[ $nodes[$key]['parent_id'] ]['nodes'] = array(); //то добавляем к записи узел (массив дочерних веток) на данном этапе

					$nodes[ $nodes[$key]['parent_id'] ]['nodes'][] =& $nodes[$key];
				}
			}
		}
		$CACHE->set('trees',$table.$cacheadd,$tree);
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
	global $tracker_lang;
	if (!$recurs) $t_content = "<select class='linkselect'  name=\"$name\"><option value=\"0\">{$tracker_lang['choose']}</option>\n";

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

/**
 * Gets array of ids of ALL children of branch
 * @param array $tree Tree to be processed
 * @param int $tid id of processing branch
 * @param array $array already processed ids, used only in recursion
 * @param boolean $recurs is function running recursive? default false, used only in recursion
 * @param int $level level of processing tree, used only in recursion
 * @return array|boolean Array of ids of ALL children of branch, id of a branch if there are no children and false if category does note exist
 */
function get_full_childs_ids($tree, $tid, $type='categories', &$array = array(), &$recurs = false, &$level = 0) {
	global $CACHE;
	$return = false;
	if (!$recurs)
	$return = $CACHE->get('trees',$type.'-full-childs-'.$tid);
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
		if (!$recurs) $CACHE->set('trees',$type.'-full-childs-'.$tid,$array);
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
	foreach ($tree as $branch) {
		if ($cid==$branch['id']) { $array[]="<a href=\"$viewer.php?cat=".$branch['id']."\">{$branch['name']}</a>"; return $array; }
		elseif ($branch['nodes']) {
			$array[]="<a href=\"$viewer.php?cat=".$branch['id']."\">".(($byimages && $branch['image'])?"<img src=\"{$branch['image']}\" title=\"{$branch['name']}\" alt=\"{$branch['name']}\"/>":$branch['name'])."</a>";
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
	global $CACHEARRAY;
	if ($CACHEARRAY['use_kinopoisk_trailers']) {
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

   swfobject.embedSWF('swf/player-viral.swf', 'trailer_player', '{$flashcode['width']}', '{$flashcode['height']}', '9', 'false', flashvars, params, attributes);
</script>";			else $online=false;
		}

	}
	return $online;
}

/**
 * Outputs beta warning. Default false.
 * @var boolean
 */
define ("BETA", false);
/**
 * Beta warning as it is
 * @var string
 */
define ("BETA_NOTICE", "\n<br />This isn't complete release of source!");
/**
 * Kinokpk.com releaser's version
 * @var string
 */
define("RELVERSION","3.00");
?>