<?php
/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
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
	$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_ ".
		"абвгдеёжзиклмнопрстуфхшщэюяьъАБВГДЕЁЖЗИКЛМНОПРСТУФХШЩЭЮЯЬЪ";

	for ($i = 0; $i < strlen($username); ++$i)
	if (strpos($allowedchars, $username[$i]) === false)
	return false;

	return true;
}

function send_comment_notifs($id,$page,$type) {
	global $tracker_lang, $CURUSER;
	getlang('comment_notifs');
	$subject = sqlesc($tracker_lang['new_comment']);
	$msg = sqlesc(sprintf($tracker_lang['comment_notice_'.$type],$page));
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, ".time().", $msg, 0, $subject FROM notifs WHERE checkid = $id AND type='$type' AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);
}

function ratearea($currating,$currid,$type) {
	global $CURUSER,$ALREADY_RATED, $tracker_lang;

	if (!$currid) return '';
	if (!$ALREADY_RATED[$type]) {
		$res = sql_query("SELECT rid,type FROM ratings WHERE userid={$CURUSER['id']}");
		while (list($rid,$rtype) = mysql_fetch_array($res)) {
			$ALREADY_RATED[$rtype][] = $rid;
		}
	}
	if ($currating>0) $znak='+';
	$text='<strong>'.$znak.$currating.'</strong>';
	if (@in_array($currid,$ALREADY_RATED[$type])) return $text;
	else return ('<div style="display:inline;" id="ratearea-'.$currid.'-'.$type.'"><a href="rate.php?id='.$currid.'&amp;type='.$type.'&amp;act=up" onclick="return rateit('.$currid.',\''.$type.'\',\'up\');"><img style="border:none;" src="pic/arrowup.gif" title="'.$tracker_lang['rate_up'].'"/></a>&nbsp;'.$text.'<a href="rate.php?id='.$currid.'&amp;type='.$type.'&amp;act=down" onclick="return rateit('.$currid.',\''.$type.'\',\'down\');"><img style="border:none;" src="pic/arrowdown.gif" title="'.$tracker_lang['rate_down'].'"/></a>&nbsp;</div>');

}

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

function timer() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
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

function my_set_charset($charset) {
	if (!function_exists("mysql_set_charset") || !mysql_set_charset($charset)) mysql_query("SET NAMES $charset");
}

function write_sys_msg($receiver,$msg,$subject) {
	sql_query("INSERT INTO messages (receiver, added, msg, subject) VALUES($receiver, '" . time() . "', ".sqlesc($msg).", ".sqlesc($subject).")");// or sqlerr(__FILE__, __LINE__);
}
function mcejstemplate () {
	global $CURUSER;

	if (defined("MCE")) return; else
	define("MCE",1);
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
  +"|vspace|width],"
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
		return $valid_elm.'invalid_elements: "script,embed,iframe",
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,spoiler,stamps,kinopoisk,reltemplates",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,';
	} elseif (get_user_class() >= UC_MODERATOR) {
		return $valid_elm.'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,stamps,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : ",|,cite,abbr,acronym,del,ins,|,visualchars,nonbreaking,|,spoiler,|,kinopoisk,reltemplates",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
  invalid_elements: "script,embed,iframe",
        theme_advanced_resizing : true,';
	} else return $valid_elm.'theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect,|,cite,abbr,acronym,del,ins,|,visualchars,nonbreaking",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,stamps,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen,|,spoiler,|,kinopoisk,reltemplates",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
  invalid_elements: "script,embed,iframe",
        theme_advanced_resizing : true,';
}

function mcejs() {
	print '<script type="text/javascript" src="js/tiny_mce/tiny_mce_gzip.js"></script>
<script language="javascript" type="text/javascript">
function mcejs() {
tinyMCE_GZ.init({
	plugins : \'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,stamps,kinopoisk,\'+
        \'searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,spoiler,reltemplates\',
	themes : \'simple,advanced\',
	languages : \'ru, en\',
	disk_cache : true,
	gecko_spellcheck:"1",
	debug : false
}, function() { tinyMCE.init({
       forced_root_block : false,
   force_br_newlines : true,
   force_p_newlines : false,
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,spoiler,stamps,kinopoisk",

  '.mcejstemplate ().'
	mode : "exact",
	elements : "tmce",
	language: "ru"

});
});
}
</script>
'.(defined("NO_TINYMCE")?'':'<script language="javascript" type="text/javascript">mcejs();</script>');
}

function get_retrackers() {
	global $IPCHECK;
	$ip = getip();
	$row = sql_query("SELECT announce_url, mask FROM retrackers ORDER BY sort ASC");
	while ($res = mysql_fetch_assoc($row)) { $rtarray[] = $res; }

	if (!$rtarray) return false;

	foreach ($rtarray as $retracker) {

		if (!empty($retracker['mask'])) {
			$RTCHECK = new IPAddressSubnetSniffer(array($retracker['mask']));

			if ($RTCHECK->ip_is_allowed($ip)) $retrackers[] = $retracker['announce_url'];
		}
		else $retrackers[] = $retracker['announce_url'];
	}

	if ($retrackers) return implode(',',$retrackers); else return false;
}

function httpauth(){
	global $CURUSER, $tracker_lang;

	if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
		$auth_params = explode(":" , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		$_SERVER['PHP_AUTH_USER'] = $auth_params[0];
		unset($auth_params[0]);
		$_SERVER['PHP_AUTH_PW'] = implode('',$auth_params);
	}

	if ($CURUSER['passhash'] != md5($CURUSER['secret'].$_SERVER["PHP_AUTH_PW"].$CURUSER['secret'])) {
		header("WWW-Authenticate: Basic realm=\"Kinokpk.com releaser\"");
		header("HTTP/1.0 401 Unauthorized");
		stderr($tracker_lang['error'],$tracker_lang['access_denied']);

	}

}
//functions_global:

function getlang($option = 'main') {
	global $CACHEARRAY, $tracker_lang;

	if (empty($_COOKIE["lang"]) || !$CACHEARRAY['use_lang'])
	include_once(ROOT_PATH.'languages/lang_' . $CACHEARRAY['default_language'] . '/lang_'.$option.'.php');
	else
	@include_once(ROOT_PATH.'languages/lang_' . $_COOKIE["lang"] . '/lang_'.$option.'.php');
}

function images_array_search($val, $array) {

	$i = 0;

	foreach($array as $v) {

		if(preg_match("#$val#si", $v)) return $i;
		$i++;

	}
}

function get_user_class_color($class, $username)
{
	global $tracker_lang;
	switch ($class)
	{
		case UC_SYSOP:
			return "<span style=\"color:#0F6CEE\" title=\"".$tracker_lang['class_sysop']."\">" . $username . "</span>";
			break;
		case UC_ADMINISTRATOR:
			return "<span style=\"color:green\" title=\"".$tracker_lang['class_administrator']."\">" . $username . "</span>";
			break;
		case UC_MODERATOR:
			return "<span style=\"color:red\" title=\"".$tracker_lang['class_moderator']."\">" . $username . "</span>";
			break;
		case UC_UPLOADER:
			return "<span style=\"color:orange\" title=\"".$tracker_lang['class_uploader']."\">" . $username . "</span>";
			break;
		case UC_VIP:
			return "<span style=\"color:#9C2FE0\" title=\"".$tracker_lang['class_vip']."\">" . $username . "</span>";
			break;
		case UC_POWER_USER:
			return "<span style=\"color:#D21E36\" title=\"".$tracker_lang['class_power_user']."\">" . $username . "</span>";
			break;
		case UC_USER:
			return "<span title=\"".$tracker_lang['class_user']."\">" . $username . "</span>";
			break;
	}
	return "$username";
}

function display_date_time($timestamp = 0 , $tzoffset = 0){
	return date("Y-m-d H:i:s", $timestamp + ($tzoffset * 60));
}

function cut_text ($txt, $car) {
	while(strlen($txt) > $car) {
		return substr($txt, 0, $car) . "...";
	}
	return $txt;
}

function makesafe($text) {
	return strip_tags($text);
}

function textbbcode($name, $content="") {

	mcejs();
	return '<textarea id="tmce" name="'.$name.'" cols="80" rows="25">'.$content.'</textarea>';
}


function get_row_count($table, $suffix = "")
{
	if ($suffix)
	$suffix = " $suffix";
	($r = sql_query("SELECT SUM(1) FROM $table$suffix")) or die(mysql_error());
	($a = mysql_fetch_row($r)) or die(mysql_error());
	return $a[0];
}
function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
	if ($htmlstrip) {
		$heading = htmlspecialchars_uni(trim($heading));
		$text = htmlspecialchars_uni(trim($text));
	}
	print("<table class=\"main\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"embedded\">\n");
	print("<div class=\"$div\">".($heading ? "<b>$heading</b><br />" : "")."$text</div></td></tr></table>\n");
}

function stderr($heading = '', $text = '', $div ='error') {
	stdhead();
	stdmsg($heading, $text, $div);
	stdfoot();
	die;
}

function newerr($heading = '', $text = '', $head = true, $foot = true, $die = true, $div = 'error', $htmlstrip = true) {
	if ($head)
	stdhead($heading);

	newmsg($heading, $text, $div, $htmlstrip);

	if ($foot)
	stdfoot();

	if ($die)
	die;
}

function sqlerr($file = '', $line = '') {
	global $queries, $CURUSER;
	$err = mysql_error();
	$res = sql_query("SELECT id FROM users WHERE class=".UC_SYSOP);
	while (list($id) = mysql_fetch_array($res)) write_sys_msg($id,'MySQL got error: '.htmlspecialchars_uni($err).'<br />File: '.$file.'<br />Line: '.$line.'<br />URI: '.$_SERVER['REQUEST_URI'].'<br />User: <a href="users.php?id='.$CURUSER['id'].'">'.get_user_class_color($CURUSER['class'],$CURUSER['username'].'</a>'),'MySQL error detected!');
	print("<table border=\"0\" bgcolor=\"blue\" align=\"left\" cellspacing=\"0\" cellpadding=\"10\" style=\"background: blue\">" .
	"<tr><td class=\"embedded\"><font color=\"white\"><h1>Ошибка в SQL</h1>\n" .
	"<b>Ответ от сервера MySQL: " . htmlspecialchars_uni($err) . ($file != '' && $line != '' ? "<p>в $file, линия $line</p>" : "") . "<p>Запрос номер $queries.</p></b></font></td></tr></table>");
	die;
}

function get_dt_num() {
	return date("YmdHis");
}

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

function _strlastpos ($haystack, $needle, $offset = 0)
{
	$addLen = strlen ($needle);
	$endPos = $offset - $addLen;
	while (true)
	{
		if (($newPos = strpos ($haystack, $needle, $endPos + $addLen)) === false) break;
		$endPos = $newPos;
	}
	return ($endPos >= 0) ? $endPos : false;
}

// retrieve images on the site
function get_images($file){
	$pattern = '/<img.*? src=[\'"]?([^\'" >]+)[\'" >]/';
	preg_match_all($pattern, $file, $matches);

	return $matches[1];
}

function cleanhtml($code) {
	// Set htmLawed; some configuration need not be specified because the default behavior is good enough
	$config = array(
    'safe'=>1, // Dangerous elements and attributes thus not allowed
'comments'=>0, 'cdata'=>0, 'deny_attribute'=>'on*', 'elements'=>'*-applet-embed-iframe-object-script', 'scheme'=>'href: aim, feed, file, ftp, gopher, http, https, irc, mailto, news, nntp, sftp, ssh, telnet; style: nil; *:file, http, https'
	);
	$spec = 'a = title, href;'; // The 'a' element can have only these attributes
	$images = get_images($code);

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
	$code = str_replace($bb,$html,$code);

	return htmLawed($code, $config, $spec);
}

function format_comment($text) {
	global $CACHEARRAY, $CACHE, $bb,$html;

	if (!$bb) $bb = $CACHE->get('pages','bb');
	if (!$html) $html =$CACHE->get('pages','html');

	if (($bb===false) || ($html===false)) {
		$bb = array();
		$html=array();
		$row = sql_query("SELECT id,searchwords FROM pages WHERE indexed=1") or die(mysql_error());
		while ($res = mysql_fetch_assoc($row)) {
			if (!empty($res['searchwords'])) { $searchwords = explode(',',$res['searchwords']);
			foreach ($searchwords as $word) {
				$bb[] = "#$word#";
				$html[] = "<a href=\"pages.php?id={$res['id']}\">$word</a>";
			}
			}
		}
		$CACHE->set('pages','bb',$bb);
		$CACHE->set('pages','html',$html);
	}

	$text = preg_replace($bb,$html,$text);
	return cleanhtml($text);
}

function get_user_class() {
	global $CURUSER;
	return $CURUSER['class'];
}


function get_user_class_name($class) {
	global $tracker_lang;
	switch ($class) {
		case UC_USER: return $tracker_lang['class_user'];

		case UC_POWER_USER: return $tracker_lang['class_power_user'];

		case UC_VIP: return $tracker_lang['class_vip'];

		case UC_UPLOADER: return $tracker_lang['class_uploader'];

		case UC_MODERATOR: return $tracker_lang['class_moderator'];

		case UC_ADMINISTRATOR: return $tracker_lang['class_administrator'];

		case UC_SYSOP: return $tracker_lang['class_sysop'];
	}
	return "";
}

function is_valid_user_class($class) {
	return is_numeric($class) && floor($class) == $class && $class >= UC_USER && $class <= UC_SYSOP;
}

//----------------------------------
//---- Security function v0.1 by xam
//----------------------------------
function int_check($value,$stdhead = false, $stdfood = true, $die = true, $log = true) {
	global $CURUSER;
	$msg = "Invalid ID Attempt: Username: ".$CURUSER["username"]." - UserID: ".$CURUSER["id"]." - UserIP : ".getip();
	if ( is_array($value) ) {
		foreach ($value as $val) int_check ($val);
	} else {
		if (!is_valid_id($value)) {
			if ($stdhead) {
				if ($log)
				write_log($msg);
				stderr("ERROR","Invalid ID! For security reason, we have been logged this action.");
			}else {
				Print ("<h2>Error</h2><table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text>");
				Print ("Invalid ID! For security reason, we have been logged this action.</td></tr></table>");
				if ($log)
				write_log($msg);
			}

			if ($stdfood)
			stdfoot();
			if ($die)
			die;
		}
		else
		return true;
	}
}
//----------------------------------
//---- Security function v0.1 by xam
//----------------------------------

function is_valid_id($id) {
	return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

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

function write_log($text, $color = "transparent", $type = "tracker") {
	getlang('logs');
	$type = sqlesc($type);
	$color = sqlesc($color);
	$text = sqlesc($text);
	$added = time();
	sql_query("INSERT INTO sitelog (added, color, txt, type) VALUES($added, $color, $text, $type)");
}

function check_banned_emails ($email) {
	$expl = explode("@", $email);
	$wildemail = "*@".$expl[1];
	$res = mysql_query("SELECT id, comment FROM bannedemails WHERE email = ".sqlesc($email)." OR email = ".sqlesc($wildemail)."") or sqlerr(__FILE__, __LINE__);
	if ($arr = mysql_fetch_assoc($res))
	stderr("Ошибка!","Этот емайл адресс забанен!<br /><br /><strong>Причина</strong>: $arr[comment]", false);
}

function get_elapsed_time($U,$showseconds=true){
	if(!$U) return "---";
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
// Functions_global END

function local_user() {
	return $_SERVER["SERVER_ADDR"] == $_SERVER["REMOTE_ADDR"];
}

function sql_query($query) {
	global $queries, $query_stat, $querytime;
	$queries++;
	$query_start_time = timer(); // Start time
	$result = mysql_query($query);
	$query_end_time = timer(); // End time
	$query_time = ($query_end_time - $query_start_time);
	$querytime = $querytime + $query_time;
	$query_time = substr($query_time, 0, 8);
	$query_stat[] = array("seconds" => $query_time, "query" => $query);
	return $result;
}

function dbconn($lightmode = false) {
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset, $CACHEARRAY, $CACHE;

	if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
	die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());

	mysql_select_db($mysql_db)
	or die("dbconn: mysql_select_db: " + mysql_error());

	my_set_charset($mysql_charset);

	// configcache init

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


	// This is original copyright, please leave it alone. Remember, that the Developers worked hard for weeks, drank ~84 litres of a beer (hoegaarden, baltica 7 and lowenbrau) and ate more then 20.1 kilogrammes of hamburgers to present this source. Don't be evil (C) Google
	// Не удаляйте оригинальный копирайт. Помните, что разработчики неделями трудились над этим движком, выпили ~84 литра пива (hoegaarden, baltica 7 и lowenbrau) и съели 20.1 килограмма гамбургеров. Не будьте дьяволом (С) Гугл
	define ("TBVERSION", ($CACHEARRAY['yourcopy']?str_replace("{datenow}",date("Y"),$CACHEARRAY['yourcopy']).". ":"")."Powered by <a class=\"copyright\" target=\"_blank\" href=\"http://www.kinokpk.com\">Kinokpk.com</a> <a class=\"copyright\" target=\"_blank\" href=\"http://dev.kinokpk.com\">releaser</a> ".RELVERSION." &copy; 2008-".date("Y").".");
	register_shutdown_function("mysql_close");
}

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
		if ($BAN->ip_is_allowed($ip) )
		die("Sorry, you (or your subnet) are banned by IP and MAC addresses!");

	}

	if (empty($_COOKIE["uid"]) || empty($_COOKIE["pass"])) {
		getlang();
		user_session();
		return;
	}

	if (!is_valid_id($_COOKIE["uid"]) || strlen($_COOKIE["pass"]) != 32) {
		die("Cokie ID invalid or cookie pass hash problem.");

	}
	$id = (int) $_COOKIE["uid"];
	$res = sql_query("SELECT * FROM users WHERE id = $id AND confirmed=1");// or die(mysql_error());
	$row = mysql_fetch_array($res);
	if (!$row) {
		getlang();
		user_session();
		return;
	} elseif ((!$row['enabled']) && !defined("IN_CONTACT")) die('Sorry, your account has been disabled by administation. You can contact admins via <a href="contact.php">FeedBack Form</a>. Reason: '.$row['dis_reason']);

	$sec = hash_pad($row["secret"]);
	if ($_COOKIE["pass"] !== $row["passhash"]) {
		getlang();
		user_session();
		return;
	}

	$updateset = array();


	if ($ip != $row['ip'])
	$updateset[] = 'ip = '. sqlesc($ip);
	if ($row['last_access'] < (time() - 300))
	$updateset[] = 'last_access = ' . sqlesc(time());

	if (count($updateset))
	sql_query("UPDATE LOW_PRIORITY users SET ".implode(", ", $updateset)." WHERE id=" . $row["id"]);// or die(mysql_error());
	$row['ip'] = $ip;

	if ($row['override_class'] < $row['class'])
	$row['class'] = $row['override_class']; // Override class and save in GLOBAL array below.

	$GLOBALS["CURUSER"] = $row;
	getlang();

	user_session();

}

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

function user_session() {
	global $CURUSER, $CACHEARRAY;

	if (!$CACHEARRAY['use_sessions'])
	return;

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
	$where = array();
	$updateset = array();
	if ($sid)
	$where[] = "sid = ".sqlesc($sid);
	elseif ($uid)
	$where[] = "uid = $uid";
	else
	$where[] = "ip = ".sqlesc($ip);
	//sql_query("DELETE FROM sessions WHERE ".implode(" AND ", $where));
	$ctime = time();
	$agent = htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
	$updateset[] = "sid = ".sqlesc($sid);
	$updateset[] = "uid = ".sqlesc($uid);
	$updateset[] = "username = ".sqlesc($username);
	$updateset[] = "class = ".sqlesc($class);
	$updateset[] = "ip = ".sqlesc($ip);
	$updateset[] = "time = ".sqlesc($ctime);
	$updateset[] = "url = ".sqlesc($url);
	$updateset[] = "useragent = ".sqlesc($agent);
	if (count($updateset))
	sql_query("INSERT INTO sessions (sid, uid, username, class, ip, time, url, useragent) VALUES (".implode(", ", array_map("sqlesc", array($sid, $uid, $username, $class, $ip, $ctime, $url, $agent))).") ON DUPLICATE KEY UPDATE ".implode(", ", $updateset)) or sqlerr(__FILE__,__LINE__);
}

function unesc($x) {
	$x = trim($x);

	return $x;
}

function gzip() {
	global $CACHEARRAY;
	if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler' && $CACHEARRAY['use_gzip']) {
		@ob_start('ob_gzhandler');
	}
}

// IP Validation
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

function mkprettytime($seconds, $time = true) {
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

function mkglobal($vars) {
	if (!is_array($vars))
	$vars = explode(":", $vars);
	foreach ($vars as $v) {
		if (isset($_GET[$v]))
		$GLOBALS[$v] = unesc($_GET[$v]);
		elseif (isset($_POST[$v]))
		$GLOBALS[$v] = unesc($_POST[$v]);
		else
		return 0;
	}
	return 1;
}

function tr($x, $y, $noesc=0, $prints = true, $width = "", $relation = '') {
	if ($noesc)
	$a = $y;
	else {
		$a = htmlspecialchars_uni($y);
		$a = str_replace("\n", "<br />\n", $a);
	}
	if ($prints) {
		$print = "<td width=\"". $width ."\" class=\"heading\" valign=\"top\" align=\"right\">$x</td>";
		$colpan = "align=\"left\"";
	} else {
		$colpan = "colspan=\"2\"";
	}

	print("<tr".( $relation ? " relation=\"$relation\"" : "").">$print<td valign=\"top\" $colpan>$a</td></tr>\n");
}

function validfilename($name) {
	return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
}

function validemail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL)?true:false;
}

function sent_mail($to,$fromname,$fromemail,$subject,$body,$multiple=false,$multiplemail='') {
	global $CACHEARRAY,$smtp,$smtp_host,$smtp_port,$smtp_from,$smtpaddress,$accountname,$accountpassword;
	# Sent Mail Function v.05 by xam (This function to help avoid spam-filters.)
	$result = true;
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
		$headers .= "Content-Type: text/html; charset=\"windows-1251\"".$eol;
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

	return $result;
}

function sqlesc($value) {
	// Quote if not a number or a numeric string
	if (!is_numeric($value)) {
		$value = "'" . mysql_real_escape_string((string)$value) . "'";
	}
	return $value;
}

function sqlwildcardesc($x) {
	return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}
function sqlforum($x) {

	return mysql_real_escape_string(unesc($x));
}

function urlparse($m) {
	$t = $m[0];
	if (preg_match(',^\w+://,', $t))
	return "<a href=\"$t\">$t</a>";
	return "<a href=\"http://$t\">$t</a>";
}

function parsedescr($d, $html) {
	if (!$html) {
		$d = htmlspecialchars_uni($d);
		$d = str_replace("\n", "\n<br />", $d);
	}
	return $d;
}

function stdhead($title = "") {

	global $CURUSER, $FUNDS, $CACHEARRAY, $ss_uri, $tracker_lang, $CRON;

	$row = unserialize($CACHEARRAY['siteonline']);
	if ($row["onoff"] !=1){
		$my_siteoff = 1;
		$my_siteopenfor = $row['class_name'];
	}

	//$row["onoff"] = 1;//АВАРИЙНЫЙ ВХОД: Раскомментировать строку, если не можете войти !!!


	if (($row["onoff"] !=1) && (!$CURUSER)){
		die("<head><meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\" /><title>{$CACHEARRAY['sitename']} :: Under construction/Сайт Закрыт!</title></head>
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
        </td></tr></table>");
	}
	elseif (($row["onoff"] !=1) and (($CURUSER["class"] < $row["class"]) && ($CURUSER["id"] != 1))){

		die("<title>{$CACHEARRAY['sitename']} :: Under construction/Сайт Закрыт!</title>
        <table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>
        <h1 style='color: #CC3300;'>".$row['reason']."</h1>
        <h1 style='color: #CC3300;'>
        Пожалуйста, зайдите позже...</h1></td></tr></table>");
	}

	$title = $CACHEARRAY['sitename']. " :: " . htmlspecialchars_uni($title);

	if (isset($_GET['styleid']) && $CURUSER) {
		if (is_valid_id($_GET['styleid'])) {
			$styleid = $_GET['styleid'];
			sql_query("UPDATE users SET stylesheet = $styleid WHERE id=" . $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
			header("Location: {$CACHEARRAY['defaultbaseurl']}/");
			//$CURUSER["stylesheet"] = $styleid;
		} else stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
	}

	if ($CURUSER) {
		$ss_a = @mysql_fetch_array(@sql_query("SELECT uri FROM stylesheets WHERE id = " . $CURUSER["stylesheet"]));

		$activeseed = $activeleech = 0;
		$res2 = sql_query("SELECT seeder FROM peers WHERE userid=" . $CURUSER["id"] . "")  or print(mysql_error());

		while($row2 = @mysql_fetch_assoc($res2))
		{
			if($row2['seeder'])
			$activeseed++;

			if(!$row2['seeder'])
			$activeleech++;
		}

		if ($ss_a)
		$ss_uri = $ss_a["uri"];
		else
		$ss_uri = $CACHEARRAY['default_theme'];
	} else
	$ss_uri = $CACHEARRAY['default_theme'];

	if ($CURUSER) {
		$msgs = sql_query("SELECT location, sender, receiver, unread, saved FROM messages WHERE receiver = " . $CURUSER["id"] . " OR sender = " . $CURUSER["id"]) or sqlerr(__FILE__,__LINE__);
		$CURUSER['unread']=$CURUSER['messages']=$CURUSER['outmessages']=0;
		while ($message = mysql_fetch_array($msgs)) {
			if ($message["unread"] && $message["location"] == 1 && $message["receiver"] == $CURUSER["id"])
			$CURUSER['unread']++;
			if ($message["location"] == 1 && $message["receiver"] == $CURUSER["id"])
			$CURUSER['messages']++;
			if ($message["saved"] && $message["location"] != 0 && $message["sender"] == $CURUSER["id"])
			$CURUSER['outmessages']++;
		}
		if (get_user_class()>=UC_MODERATOR) $CURUSER['unchecked']=(int)@mysql_result(sql_query("SELECT COUNT(*) FROM torrents WHERE moderatedby=0"),0);
	}
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");

	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<!--[if IE]>
<link rel="stylesheet" href="css/features_ie.css" type="text/css"/>
<![endif]-->
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>'
	.((!$CURUSER || ($CURUSER['extra_ef']))?'
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->':'').
'<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
<script language="javascript" type="text/javascript" src="js/features.js"></script>
<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$CACHEARRAY['defaultbaseurl'].'/rss.php" />
<link rel="alternate" type="application/atom+xml" title="Atom" href="'.$CACHEARRAY['defaultbaseurl'].'/atom.php" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
');

	if (get_user_class() == UC_SYSOP) {
		if ($row['onoff'] != 1) print('<div align="center"><font color="red" size="20">ADMIN WARNING: SITE IS CLOSED FOR MAINTENANCE!</font></div>');
	}
	@require_once(ROOT_PATH."themes/" . $ss_uri . "/template.php");
	@require_once(ROOT_PATH."themes/" . $ss_uri . "/stdhead.php");

} // stdhead

function stdfoot() {
	global $CURUSER, $ss_uri, $tracker_lang, $queries, $tstart, $query_stat, $querytime, $CACHEARRAY, $CRON;

	@require_once(ROOT_PATH."themes/" . $ss_uri . "/template.php");
	@require_once(ROOT_PATH."themes/" . $ss_uri . "/stdfoot.php");

	$cronrow=sql_query("SELECT * FROM cron WHERE cron_name IN ('last_cleanup','in_cleanup','in_remotecheck','num_cleaned','num_checked','remotecheck_disabled','autoclean_interval','last_remotecheck')");
	while ($cronres = mysql_fetch_assoc($cronrow)) $CRON[$cronres['cron_name']]=$cronres['cron_value'];
	//		  var_dump($CRON);
	if (!$CRON['in_cleanup'] && ((time()-$CRON['last_cleanup'])>$CRON['autoclean_interval'])) print '<img width="0px" height="0px" alt="" title="" src="cleanup.php"/>';
	if (!$CRON['remotecheck_disabled'] && !$CRON['in_remotecheck'] && ((time()-$CRON['last_remotecheck'])>$CRON['remotepeers_cleantime'])) print '<img width="0px" height="0px" alt="" title="" src="remote_check.php"/>';

	if (($CACHEARRAY['debug_mode']) && count($query_stat) && ($CURUSER['class'] >= UC_SYSOP)) {
		$qsec = 0;
		foreach ($query_stat as $key => $value) {
			$qsec = $qsec + $value['seconds'];
			print("<div>[".($key+1)."] => <b>".$value["seconds"]."</b> [$value[query]]</div>\n");
		}
		print '<b>'.sprintf($tracker_lang['all_db_q'],$qsec)."<br />";
		if (!$CRON['in_cleanup']) print $tracker_lang['cleanup_not_running'].'<br />';
		if ($CRON['remotecheck_disabled']) print $tracker_lang['remotecheck_disabled'].'<br />'; elseif  (!$CRON['in_remotecheck']) print $tracker_lang['remotecheck_not_running']; else print $tracker_lang['remotecheck_is_running']; print '<br />';
		print sprintf($tracker_lang['num_cleaned'],$CRON['num_cleaned'])."<br />";
		print sprintf($tracker_lang['num_checked'],$CRON['num_checked'])."<br />";
		print $tracker_lang['last_cleanup'].' '.mkprettytime($CRON['last_cleanup'],true,true)." (".get_elapsed_time($CRON['last_cleanup'])." {$tracker_lang['ago']})<br />";
		print $tracker_lang['last_remotecheck'].' '.mkprettytime($CRON['last_remotecheck'],true,true)." (".get_elapsed_time($CRON['last_remotecheck'])." {$tracker_lang['ago']})<br />";
		print('<div align="center"><font color="red"><b>'.$tracker_lang['in_debug'].'</b></font></div><br />');
	}
	print('</body></html>');
}

function genbark($x,$y) {
	stdhead($y);
	print("<h2>" . htmlspecialchars_uni($y) . "</h2>\n");
	print("<p>" . htmlspecialchars_uni($x) . "</p>\n");
	stdfoot();
	exit();
}

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

function httperr($code = 404) {
	$sapi_name = php_sapi_name();
	if ($sapi_name == 'cgi' OR $sapi_name == 'cgi-fcgi') {
		header('Status: 404 Not Found');
	} else {
		header('HTTP/1.1 404 Not Found');
	}
	exit;
}

function logincookie($id, $passhash, $language, $updatedb = 1, $expires = 0x7fffffff) {
	setcookie("uid", $id, $expires, "/");
	setcookie("pass", $passhash, $expires, "/");
	setcookie("lang", $language, $expires, "/");

	if ($updatedb)
	sql_query("UPDATE users SET last_login = ".time()." WHERE id = $id");
}

function logoutcookie() {
	setcookie("uid", "", 0x7fffffff, "/");
	setcookie("pass", "", 0x7fffffff, "/");
	setcookie("lang", "", 0x7fffffff, "/");
}

function loggedinorreturn($nowarn = false) {
	global $CURUSER, $CACHEARRAY;
	if (!$CURUSER) {
		header("Location: login.php?returnto=" . urlencode(basename($_SERVER["REQUEST_URI"])).($nowarn ? "&nowarn=1" : ""));
		exit();
	}
}

function deletetorrent($id) {

	sql_query("DELETE FROM checkcomm WHERE checkid = $id AND torrent=1") or sqlerr(__FILE__,__LINE__);

	sql_query("DELETE FROM torrents WHERE id = $id");
	sql_query("DELETE FROM bookmarks WHERE id = $id");
	foreach(explode(".","snatched.peers.files.comments") as $x)
	sql_query("DELETE FROM $x WHERE torrent = $id");
	@unlink("torrents/$id.torrent");

}

function pager($rpp, $count, $href, $opts = array()) {
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
		$pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\"><tr>$pager $pagerstr $pager2</tr></table>\n";
	}
	else {
		$pagertop = $pager;
		$pagerbottom = $pagertop;
	}

	$start = $page * $rpp;

	return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
}

function browsepager($rpp, $count, $href, $postfix = '', $opts = array()) {
	global $tracker_lang;
	$pages = ceil($count / $rpp);

	if ((!is_valid_id($_GET['page'])) && ((int) $_GET['page'] !=0)) {stdmsg($tracker_lang["error"], $tracker_lang['invalid_id'],'error'); stdfoot(); die(); }

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
		$pager .= "<a href=\"{$href}page=" . ($page - 1) . $postfix."\" onclick=\"return pageswitcher(" . ($page - 1) . ")\" style=\"text-decoration: none;\">$as</a>";
		$pager .= "</td><td class=\"pagebr\">&nbsp;</td>";
	}

	$as = "<b>»</b>";
	if ($page < $mp && $mp >= 0) {
		$pager2 .= "<td class=\"pager\">";
		$pager2 .= "<a href=\"{$href}page=" . ($page + 1) . $postfix."\" onclick=\"return pageswitcher(" . ($page + 1) . ")\" style=\"text-decoration: none;\">$as</a>";
		$pager2 .= "</td>$bregs";
	}else     $pager2 .= $bregs;

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
			$pagerarr[] = "<td class=\"pager\"><a title=\"$start&nbsp;-&nbsp;$end\" href=\"{$href}page=$i$postfix\" onclick=\"return pageswitcher($i)\" style=\"text-decoration: none;\"><b>$text</b></a></td><td class=\"pagebr\">&nbsp;</td>";
			else
			$pagerarr[] = "<td class=\"highlight\"><b>$text</b></td><td class=\"pagebr\">&nbsp;</td>";

		}
		$pagerstr = join("", $pagerarr);
		$pagertop = "<table class=\"main\"><tr>$pager $pagerstr $pager2</tr></table>\n";
		$pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\"><tr>$pager $pagerstr $pager2</tr></table>\n";
	}
	else {
		$pagertop = $pager;
		$pagerbottom = $pagertop;
	}

	$start = $page * $rpp;

	return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
}

function downloaderdata($res) {
	$rows = array();
	$ids = array();
	$peerdata = array();
	while ($row = mysql_fetch_assoc($res)) {
		$rows[] = $row;
		$id = $row["id"];
		$ids[] = $id;
		$peerdata[$id] = array(downloaders => 0, seeders => 0, comments => 0);
	}

	if (count($ids)) {
		$allids = implode(",", $ids);
		$res = sql_query("SELECT COUNT(*) AS c, torrent, seeder FROM peers WHERE torrent IN ($allids) GROUP BY torrent, seeder");
		while ($row = mysql_fetch_assoc($res)) {
			if ($row["seeder"])
			$key = "seeders";
			else
			$key = "downloaders";
			$peerdata[$row["torrent"]][$key] = $row["c"];
		}
		$res = sql_query("SELECT COUNT(*) AS c, torrent FROM comments WHERE torrent IN ($allids) GROUP BY torrent");
		while ($row = mysql_fetch_assoc($res)) {
			$peerdata[$row["torrent"]]["comments"] = $row["c"];
		}
	}

	return array($rows, $peerdata);
}

function commenttable($rows, $redaktor = "comment") {
	global $CURUSER, $CACHEARRAY, $tracker_lang;

	$count = 0;
	foreach ($rows as $row)	{
		if ($row["downloaded"] > 0) {
			$ratio = $row['uploaded'] / $row['downloaded'];
			$ratio = number_format($ratio, 2);
		} elseif ($row["uploaded"] > 0) {
			$ratio = "Inf.";
		} else {
			$ratio = "---";
		}
		if ($row["last_access"] > time() - 600) {
			$online = "online";
			$online_text = "В сети";
		} else {
			$online = "offline";
			$online_text = "Не в сети";
		}

		print("<table class=maibaugrand width=100% border=1 cellspacing=0 cellpadding=3>");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\" height=\"24\">");

		if (isset($row["username"]))
		{
			$title = $row["title"];
			if ($title == ""){
				$title = get_user_class_name($row["class"]);
			}else{
				$title = htmlspecialchars_uni($title);
			}
			print(":: <img src=\"pic/button_".$online.".gif\" alt=\"".$online_text."\" title=\"".$online_text."\" style=\"position: relative; top: 2px;\" border=\"0\" height=\"14\">"
			." <a name=comm". $row["id"]." href=userdetails.php?id=" . $row["user"] . " class=altlink_white><b>". get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a> ::"
			.($row["donor"] ? "<img src=pic/star.gif title='Donor' alt='Donor'>" : "") . ($row["warned"] ? "<img src=\"/pic/warned.gif\" alt=\"Warned\">" : "") . " $title ::\n")
			." <img src=\"pic/arrowup.gif\" alt=\"upload\" border=\"0\" width=\"12\" height=\"12\"> ".mksize($row["uploaded"]) ." :: <img src=\"pic/arrowdown.gif\" alt=\"download\" border=\"0\" width=\"12\" height=\"12\"> ".mksize($row["downloaded"])." :: <font color=\"".get_ratio_color($ratio)."\">$ratio</font> :: {$tracker_lang['rate_comment']} ".ratearea($row['ratingsum'],$row['id'],$redaktor.'s');

		} else {
			print("<a name=\"comm" . $row["id"] . "\"><i>[Anonymous]</i></a>\n");
		}

		$avatar = ($CURUSER["avatars"] ? htmlspecialchars_uni($row["avatar"]) : "");
		if (!$avatar){$avatar = "pic/default_avatar.gif"; }
		$text = format_comment($row["text"]);

		if ($row["editedby"]) {
			//$res = mysql_fetch_assoc(sql_query("SELECT * FROM users WHERE id = $row[editedby]")) or sqlerr(__FILE__,__LINE__);
			$text .= "<p><font size=1 class=small>Последний раз редактировалось <a href=userdetails.php?id=$row[editedby]><b>$row[editedbyname]</b></a> ".mkprettytime($row['editedat'])." (".get_elapsed_time($row['editedat'],false)." {$tracker_lang['ago']})</font></p>\n";
	 }
		print("</td></tr>");
		print("<tr valign=top>\n");
		print("<td style=\"padding: 0px; width: 5%;\" align=\"center\"><img src=\"$avatar\"> </td>\n");
		print("<td width=100% class=text>");
		//print("<span style=\"float: right\"><a href=\"#top\"><img title=\"Top\" src=\"pic/top.gif\" alt=\"Top\" border=\"0\" width=\"15\" height=\"13\"></a></span>");
		print("$text</td>\n");
		print("</tr>\n");
		print("<tr><td class=colhead align=\"center\" colspan=\"2\">");
		print"<div style=\"float: left; width: auto;\">"
		.($CURUSER ? " [<a href=\"".$redaktor.".php?action=quote&amp;cid=$row[id]\" class=\"altlink_white\">Цитата</a>]" : "")
		.($row["user"] == $CURUSER["id"] || get_user_class() >= UC_MODERATOR ? " [<a href=".$redaktor.".php?action=edit&amp;cid=$row[id] class=\"altlink_white\">Изменить</a>]" : "")
		.(get_user_class() >= UC_MODERATOR ? " [<a href=\"".$redaktor.".php?action=delete&amp;cid=$row[id]\" onClick=\"return confirm('Вы уверены?')\" class=\"altlink_white\">Удалить</a>]" : "")
		.(get_user_class() >= UC_MODERATOR ? " IP: ".($row["ip"] ? "<a href=\"usersearch.php?ip=$row[ip]\" class=\"altlink_white\">".$row["ip"]."</a>" : "Неизвестен" ) : "")
		."</div>";

		print("<div align=\"right\">Комментарий добавлен: ".mkprettytime($row["added"])."</td></tr>");
		print("</table><br />");
	}

}


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
		$cloud_tags[] = urlencode("<a href='browse.php?cat=" . $taginfo['id'] . "' style='font-size:". floor($size) . "px;'>"). htmlentities($tag,ENT_QUOTES, "windows-1251"). urlencode("(".$taginfo['count'].")</a>");
		$cloud_links[] = "<br /><a href='browse.php?cat=" . $taginfo['id'] . "' style='font-size:". floor($size) . "px;'>$tag</a><br />";
		$i++;
	}
	$cloud_links[$i-1].="Ваш браузер не поддерживает flash!";
	$cloud_html[0] = join("", $cloud_tags);
	$cloud_html[1] = join("", $cloud_links);


	return $cloud_html;
}

function cloud ($name = '', $color='',$bgcolor='',$width='',$height='',$speed='',$size='') {
	$tagsres = array();
	$tagsres = cloud3d();
	$tags = $tagsres[0];
	$links = $tagsres[1];


	$cloud_html = '
  <script type="text/javascript" src="js/swfobject.js"></script>
<div id="'.($name?$name:"wpcumuluswidgetcontent").'">'.$links.'</div>
<script type="text/javascript">
//<![CDATA[
var rnumber = Math.floor(Math.random()*9999999);
var widget_so = new SWFObject("swf/tagcloud.swf?r="+rnumber, "tagcloudflash", "'.($width?$width:"100%").'", "'.($height?$height:"100%").'", "'.($size?$size:"9").'", "'.($bgcolor?$bgcolor:"#fafafa").'");
widget_so.addParam("allowScriptAccess", "always");
widget_so.addVariable("tcolor", "'.($color?$color:"0x0054a6").'");
widget_so.addVariable("tspeed", "'.($speed?$speed:"250").'");
widget_so.addVariable("distr", "true");
widget_so.addVariable("mode", "tags");
widget_so.addVariable("tagcloud", "'.urlencode('<tags>') . $tags . urlencode('</tags>').'");
widget_so.write("'.($name?$name:"wpcumuluswidgetcontent").'");
//]]>
</script>';
	return $cloud_html;
}

function linkcolor($num) {
	if (!$num)
	return "red";
	//	if ($num == 1)
	//		return "yellow";
	return "green";
}

function writecomment($userid, $comment) {
	$res = sql_query("SELECT modcomment FROM users WHERE id = '$userid'") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res);

	$modcomment = date("d-m-Y") . " - " . $comment . "" . ($arr[modcomment] != "" ? "\n" : "") . "$arr[modcomment]";
	$modcom = sqlesc($modcomment);

	return sql_query("UPDATE users SET modcomment = $modcom WHERE id = '$userid'") or sqlerr(__FILE__, __LINE__);
}

function torrenttable($res, $variant = "index") {
	global $tree;
	if (!$tree) $tree = make_tree();

	$owned = $moderator = 0;
	if (get_user_class() >= UC_MODERATOR)
	$owned = $moderator = 1;
	elseif ($CURUSER["id"] == $row["owner"])
	$owned = 1;
	global $CURUSER, $CACHEARRAY, $tracker_lang;

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
<td class="colhead" align="center">Постер</td>
<td class="colhead" align="center">Информация о релизе</td>
	<?

	print("</tr>\n");

	print("<tbody id=\"highlighted\">");

	foreach ($res as $row) {
		$id = $row["id"];
		print("<tr".($row["sticky"] ? " class=\"highlight\"" : "").">\n");

		print("<td align=\"center\" style=\"padding: 0px\">");

		if (isset($row["name"])) {
			print("<a href=\"details.php?id=" . $id . "\">");
			if ($row['images']) {
				$row['images'] = explode(',',$row['images']);
				$image = array_shift($row['images']);
				print("<img border=\"0\" src=\"$image\" width=\"100\" alt=\"" . $row["name"] . "\" />");
			}
			else
			print($row["name"]);
			print("</a>");
		}
		else
		print("-");
		print("</td>\n");
		?>
<td>
<table width="100%">
	<tr>
		<td class="colhead" align="left"><?=$tracker_lang['name'];?> / <?=$tracker_lang['added'];?></td>
		<?

		if ($wait)
		print("<td class=\"colhead\" align=\"center\">".$tracker_lang['wait']."</td>\n");

		if ($variant == "mytorrents")
		print("<td class=\"colhead\" align=\"center\">".$tracker_lang['visible']."</td>\n");


		?>
		<td class="colhead" align="center"><?=$tracker_lang['files'];?></td>
		<td class="colhead" align="center"><?=$tracker_lang['comments'];?></td>
		<? if ($CACHEARRAY['use_ttl']) {
			?>
		<td class="colhead" align="center"><?=$tracker_lang['ttl'];?></td>
		<?
		}
		?>
		<td class="colhead" align="center"><?=$tracker_lang['size'];?></td>

		<td class="colhead" align="center"><?=$tracker_lang['seeds'];?>|<?=$tracker_lang['leechers'];?></td>

		<?php
		if ($variant == "index" || $variant == "bookmarks")
		print("<td class=\"colhead\" align=\"center\">".$tracker_lang['uploadeder']."</td>\n");

		if ((get_user_class() >= UC_MODERATOR) && $variant == "index")
		print("<td class=\"colhead\" align=\"center\">Изменен</td>");

		if ($variant == "bookmarks")
		print("<td class=\"colhead\" align=\"center\">".$tracker_lang['delete']."</td>\n");

		print('</tr><tr><td colspan="10"><small>'.$row['cat_names'].'</small></td></tr><tr>');
		$dispname = $row["name"];
		$thisisfree = ($row[free] ? "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\"/>" : "");
		print("<td align=\"left\">".($row["sticky"] ? "Важный: " : "")."<a class=\"browselink\" href=\"details.php?");
		print("id=$id");
		print("\"><b>$dispname</b></a> $thisisfree\n");

		if ($variant != "bookmarks" && $CURUSER)
		print("<a href=\"bookmark.php?torrent=$row[id]\"><img border=\"0\" src=\"pic/bookmark.gif\" alt=\"".$tracker_lang['bookmark_this']."\" title=\"".$tracker_lang['bookmark_this']."\" /></a>\n");

		print("<a href=\"download.php?id=$id&amp;name=" . rawurlencode($row["filename"]) . "\"><img src=\"pic/download.gif\" border=\"0\" alt=\"".$tracker_lang['download']."\" title=\"".$tracker_lang['download']."\"/></a>\n");

		if ($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR)
		$owned = 1;
		else
		$owned = 0;

		if ($owned)
		print("<a href=\"edit.php?id=$row[id]\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a>\n");

		print("<br /><i>".mkprettytime($row["added"])."</i> &nbsp;&nbsp;");

		if ($wait)
		{
			$elapsed = floor((time() - $row["added"]) / 3600);
			if ($elapsed < $wait)
			{
				$color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
				print("<td align=\"center\"><nobr><a href=\"faq.php#dl8\"><font color=\"$color\">" . number_format($wait - $elapsed) . " h</font></a></nobr></td>\n");
			}
			else
			print("<td align=\"center\"><nobr>".$tracker_lang['no']."</nobr></td>\n");
		}

		print("</td>\n");

		if ($variant == "mytorrents") {
			print("<td align=\"right\">");
			if (!$row["visible"])
			print("<font color=\"red\"><b>".$tracker_lang['no']."</b></font>");
			else
			print("<font color=\"green\">".$tracker_lang['yes']."</font>");
			print("</td>\n");
		}

		if ($row["type"] == "single")
		print("<td align=\"right\">" . $row["numfiles"] . "</td>\n");
		else {
			if ($variant == "index")
			print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;filelist=1\">" . $row["numfiles"] . "</a></b></td>\n");
			else
			print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;filelist=1#filelist\">" . $row["numfiles"] . "</a></b></td>\n");
		}

		if (!$row["comments"])
		print("<td align=\"right\">" . $row["comments"] . "</td>\n");
		else {
			if ($variant == "index")
			print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;tocomm=1\">" . $row["comments"] . "</a></b></td>\n");
			else
			print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;page=0#startcomments\">" . $row["comments"] . "</a></b></td>\n");
		}

		//		print("<td align=center><nobr>" . str_replace(" ", "<br />", $row["added"]) . "</nobr></td>\n");
		$ttl = ($CACHEARRAY['ttl_days']*24) - floor((time() - $row["added"]) / 3600);
		if ($ttl == 1) $ttl .= " час"; else $ttl .= "&nbsp;часов";
		if ($CACHEARRAY['use_ttl'])
		print("<td align=\"center\">$ttl</td>\n");
		print("<td align=\"center\">" . str_replace(" ", "<br />", mksize($row["size"])) . "</td>\n");
		//		print("<td align=\"right\">" . $row["views"] . "</td>\n");
		//		print("<td align=\"right\">" . $row["hits"] . "</td>\n");

		print("<td align=\"center\">");
		if ($row["filename"] != 'nofile') {
			if ($row["seeders"]) {
				if ($variant == "index")
				{
					if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"]; else $ratio = 1;
					print("<b><a href=\"details.php?id=$id&amp;toseeders=1\"><font color=" .
					get_slr_color($ratio) . ">" . $row["seeders"] . "</font></a></b>\n");
				}
				else
				print("<b><a class=\"" . linkcolor($row["seeders"]) . "\" href=\"details.php?id=$id&amp;dllist=1#seeders\">" .
				$row["seeders"] . "</a></b>\n");
			}
			else
			print("<span class=\"" . linkcolor($row["seeders"]) . "\">" . $row["seeders"] . "</span>");

			print(" | ");

			if ($row["leechers"]) {
				if ($variant == "index")
				print("<b><a href=\"details.php?id=$id&amp;todlers=1\">" .
				number_format($row["leechers"]) . ($peerlink ? "</a>" : "") .
				   "</b>\n");
				else
				print("<b><a class=\"" . linkcolor($row["leechers"]) . "\" href=\"details.php?id=$id&amp;dllist=1#leechers\">" .
				$row["leechers"] . "</a></b>\n");
			}
			else
			print("0\n");
		} else print("<b>N/A</b>\n");
		print("</td>");

		if ($variant == "index" || $variant == "bookmarks")
		print("<td align=\"center\">" . (isset($row["username"]) ? ("<a href=\"userdetails.php?id=" . $row["owner"] . "\"><b>" . get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a>") : "<i>(unknown)</i>") . "</td>\n");

		if ($variant == "bookmarks")
		print ("<td align=\"center\"><input type=\"checkbox\" name=\"delbookmark[]\" value=\"" . $row['bookmarkid'] . "\" /></td>");

		if ((get_user_class() >= UC_MODERATOR) && $variant == "index") {
			if (!$row["moderated"])
			print("<td align=\"center\"><font color=\"green\"><b>Нет</b></font></td>\n");
			else
			print("<td align=\"center\"><a href=\"userdetails.php?id=$row[moderatedby]\"><font color=\"red\"><b>Да</b></font></a></td>\n");
		}
		print("</tr></table>\n");
		print('</td></tr>');

	}

	print("</tbody>");

	//print("</table>\n");

	return $rows;
}

function hash_pad($hash) {
	return str_pad($hash, 20);
}

function hash_where($name, $hash) {
	$shhash = preg_replace('/ *$/s', "", $hash);
	return "($name = " . sqlesc($hash) . " OR $name = " . sqlesc($shhash) . ")";
}

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
		$parkedpic = "parked.gif";
		$style = "style=\"margin-left: 2pt\"";
	}
	$pics = $arr["donor"] ? "<img src=\"pic/$donorpic\" alt='Donor' border=\"0\" $style>" : "";
	if ($arr["enabled"])
	$pics .= $arr["warned"] ? "<img src=pic/$warnedpic alt=\"Warned\" border=0 $style>" : "";
	else
	$pics .= "<img src=\"pic/$disabledpic\" alt=\"Disabled\" border=\"0\" $style>\n";
	$pics .= $arr["parked"] ? "<img src=pic/$parkedpic alt=\"Parked\" border=\"0\" $style>" : "";
	return $pics;
}

function parked() {
	global $CURUSER;
	if ($CURUSER["parked"])
	stderr($tracker_lang['error'], "Ваш аккаунт припаркован.");
}

function mysql_modified_rows () {
	$info_str = mysql_info();
	$a_rows = mysql_affected_rows();
	preg_match("#Rows matched\: \([0-9]*\)#si", $info_str, $r_matched);
	return ($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows;
}

function assoc_cats() {
	global $CACHE;
	$cats = $CACHE->get('system','cat_assoc');
	if ($cats===false) {
		$cats=array();
		$catsrow = sql_query("SELECT id,name FROM categories ORDER BY sort ASC");
		while ($catres= mysql_fetch_assoc($catsrow)) $cats[$catres['id']]=$catres['name'];
		$CACHE->set('system','cat_assoc',$cats);
	}
	return $cats;
}

function &make_tree()
{
	global $CACHE;

	$tree = $CACHE->get('system','cat_tree');
	if ($tree === false) {
		$tree = array();

		$query = mysql_query('SELECT id,parent_id,name,image FROM categories ORDER BY sort ASC');
		if (! $query) return $tree;

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
		$CACHE->set('system','cat_tree',$tree);
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
 * Gets array of ids of ALL children of branch
 * @param array $tree Tree to be processed
 * @param int $tid id of processing branch
 * @param array $array already processed ids, used only in recursion
 * @param boolean $recurs is function running recursive? default false, used only in recursion
 * @param int $level level of processing tree, used only in recursion
 * @return array|boolean Array of ids of ALL children of branch, id of a branch if there are no children and false if category does note exist
 */
function get_full_childs_ids($tree, $tid, &$array = array(), &$recurs = false, &$level = 0) {
	global $CACHE;
	$return = false;
	if (!$recurs)
	$return = $CACHE->get('system','full-childs-'.$tid);
	if ($return===false) {
		$branch = get_cur_branch($tree,$tid);
		if (!$branch) return false;
		if (!$branch['nodes']) {
			$array[] = $branch['id'];
		} else {
			$level++;
			$recurs = true;
			foreach ($branch['nodes'] as $child) $array = get_full_childs_ids($branch['nodes'],$child['id'],$array,$recurs,$level);
			$level--;
			if (!$level) $recurs = false;
		}
		if (!$recurs) $CACHE->set('system','full-childs-'.$tid,$array);
		return $array;
	} else return $return;
}

function get_cur_branch($tree, $tid) {
	foreach ($tree as $branch) {
		if ($branch['id'] == $tid) return $branch; else
		if ($branch['nodes']) {
			$br=get_cur_branch($branch['nodes'],$tid);
			if (is_array($br)) return $br;
		}
	}
}

function get_childs($tree, $tid) {
	$branch = get_cur_branch($tree,$tid);
	return ($branch['nodes']);

}

function get_cur_position($tree, $cid, $viewer='browse', $byimages=false, &$array = '') {
	foreach ($tree as $branch) {
		if ($cid==$branch['id']) { $array[]="<a href=\"$viewer.php?cat=".$branch['id']."\">".(($byimages && $branch['image'])?"<img style=\"border:none;\" src=\"pic/cats/{$branch['image']}\" title=\"{$branch['name']}\" alt=\"{$branch['name']}\"/>":$branch['name'])."</a>"; return $array; }
		elseif ($branch['nodes']) {
			$array[]="<a href=\"$viewer.php?cat=".$branch['id']."\">".(($byimages && $branch['image'])?"<img style=\"border:none;\" src=\"pic/cats/{$branch['image']}\" title=\"{$branch['name']}\" alt=\"{$branch['name']}\"/>":$branch['name'])."</a>";
			$res = get_cur_position($branch['nodes'],$cid, $viewer, $byimages, $array);
			if (!$res) array_pop($array); else return $res;
		}

	}
}

function get_cur_position_str($tree,$tid, $viewer = 'browse', $byimages=false, $separator=' / ') {
	$array = get_cur_position($tree,$tid, $viewer, $byimages);
	if (!$array) return '';
	return implode($separator,$array);
}

define ("BETA", 0);
define ("BETA_NOTICE", "\n<br />This isn't complete release of source!");
define("RELVERSION","2.70");
?>