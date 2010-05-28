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

# IMPORTANT: Do not edit below unless you know what you are doing!
if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

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

function textbbcode($form,$name,$content="") {
?>

<script language=javascript>
function insertAtCursor(myField, myValue) {
//IE support
  if (document.selection) {
    myField.focus();
    sel = document.selection.createRange();
    sel.text = myValue;
  }

//MOZILLA/NETSCAPE support
  else if (myField.selectionStart || myField.selectionStart == '0') {
    var startPos = myField.selectionStart;
    var endPos = myField.selectionEnd;
    myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
    myField.selectionEnd = startPos + myValue.length;
  } else {
    myField.value += myValue;
  }
  myField.focus();
}

// calling the function
function SmileIT(smile,form,text){
    insertAtCursor(document.forms[form].elements[text], smile)
}

function PopMoreSmiles(form,name) {
         link='moresmiles.php?form='+form+'&text='+name
         newWin=window.open(link,'moresmile','height=500,width=450,resizable=no,scrollbars=yes');
         if (window.focus) {newWin.focus()}
}

function PopMoreTags(form,name) {
         link='moretags.php?form='+form+'&text='+name
         newWin=window.open(link,'moresmile','height=500,width=775,resizable=no,scrollbars=yes');
         if (window.focus) {newWin.focus()}
}


function BBTag(tag,s,text,form){
  var df  = document.forms[form].elements[s];
  var ele = document.forms[form].elements[text];
  var tval;
  var ttag;
  switch(tag) {
    case '[quote]':
            tval = "QUOTE ";
            ttag  = "[/quote]";
            if(df.value == "QUOTE ") {
              tval = "QUOTE*";
              ttag  = "[quote]";
            }
            break;
    case '[img]':
            tval = "IMG ";
            ttag = "[/img]";
            if(df.value == "IMG ") {
              tval = "IMG*";
              ttag  = "[img]";
            }
            break;

    case '[url]':
            tval = "URL ";
            ttag = "[/url]";
            if(df.value == "URL ") {
              tval = "URL*";
              ttag  = "[url]";
            }
            break;

    case '[*]':
            tval= "List ";
                        if(df.value == "List ") ttag  = "[*]";
            break;

            insertAtCursor(ele, tag);
            df.elements[s].value = tval;
            break;

    case '[hr]':
                        tval= "Hr ";
                        if(df.value =="HR "){
                            ttag="[HR]";
            }
                        insertAtCursor(ele,tag);
            df.elements[s].value=tval;
            break;

    case '[center]':    tval= "Center ";
            ttag= "[/center]";
            if(df.value == "Center "){
                    tval = "Center*";
                 ttag="[center]";
                }


            break;
            
    case '[spoiler]':    tval= "Spoiler ";
            ttag= "[/spoiler]";
            if(df.value == "Spoiler "){
                    tval = "Spoiler*";
                 ttag="[spoiler]";
                }


            break;

    case '[b]':
            tval = "B ";
            ttag = "[/b]";
            if(df.value == "B ") {
              tval = "B*";
              ttag  = "[b]";
            }
            break;

    case '[i]':
            tval = "I ";
            ttag = "[/i]";
            if(df.value == "I ") {
              tval = "I*";
              ttag  = "[i]";
            }
            break;

    case '[u]':
            tval = "U ";
            ttag = "[/u]";
            if(df.value == "U ") {
              tval = "U*";
              ttag  = "[u]";
            }
            break;
  }
  if(tval!="") df.value = tval;
  if(ttag!="") insertAtCursor(ele, ttag);
}

function openstamp()
{
windop = window.open("stamp.php?form=<?=$form?>&text=<?=$name?>","mywin","height=600,width=600,resizable=no,scrollbars=yes");
}

</script>

  <table width="100%" style='margin: 3px' cellpadding="0" cellspacing="0">
    <tr>
      <td class=embedded colspan=2>
      <table cellpadding="2" cellspacing="1">
      <tr>
      <td class=embedded><input style="font-weight: bold;" type="button" name="bold" value="B " onclick="javascript: BBTag('[b]','bold','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input style="font-style: italic;" type="button" name="italic" value="I " onclick="javascript: BBTag('[i]','italic','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input style="text-decoration: underline;" type="button" name="underline" value="U " onclick="javascript: BBTag('[u]','underline','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input type="button" name="li" value="List " onclick="javascript: BBTag('[*]','li','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input type="button" name="quote" value="QUOTE " onclick="javascript: BBTag('[quote]','quote','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input type="button" name="url" value="URL " onclick="javascript: BBTag('[url]','url','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input type="button" name="img" value="IMG " onclick="javascript: BBTag('[img]','img','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input type="button" name="hr" value="HR " onclick="javascript: BBTag('[hr]','hr','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input type="button" name="center" value="Center " onclick="javascript: BBTag('[center]','center','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded><input type="button" name="spoiler" value="Spoiler " onclick="javascript: BBTag('[spoiler]','spoiler','<? echo $name; ?>','<? echo $form; ?>')" /></td>
      <td class=embedded>&nbsp;<a href="javascript: PopMoreTags('<? echo $form; ?>','<? echo $name; ?>')"><? print("Еще теги");?></a></td>
      </table>
      </td>
    </tr>
    <tr>
      <td class=embedded>
      <textarea name="<?=$name?>" rows="15" cols="80"><?=$content?></textarea>
      </td>
      <td class=embedded>
      <table cellpadding="3" cellspacing="1">
      <?

      global $smilies, $BASEURL;
      while ((list($code, $url) = each($smilies)) && $count<20) {
         if ($count % 4==0)
            print("<tr>");

            print("\n<td class=embedded style='padding: 3px; margin: 2px'><a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."','$form','$name')\"><img border=0 src=".$GLOBALS['pic_base_url']."/smilies/".$url."></a></td>");
            $count++;

         if ($count % 4==0)
            print("</tr>");
      }
      ?>
      </table>
      <center><a href="javascript: PopMoreSmiles('<? echo $form; ?>','<? echo $name; ?>')"><? print("Все смайлы");?></a></center>
      <center><a href="javascript:openstamp()"><b>Штампы</b></a></center>
      </td>
    </tr>
  </table>
<?
}

function get_row_count($table, $suffix = "")
{
  if ($suffix)
    $suffix = " $suffix";
  ($r = sql_query("SELECT COUNT(*) FROM $table$suffix")) or die(mysql_error());
  ($a = mysql_fetch_row($r)) or die(mysql_error());
  return $a[0];
}

/*function stdmsg($heading = '', $text = '') {
	print("<table class=\"main\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"embedded\">\n");
	if ($heading)
		print("<h2>$heading</h2>\n");
	print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">\n");
	print($text . "</td></tr></table></td></tr></table>\n");
}*/

function stdmsg($heading = '', $text = '', $div = 'success', $htmlstrip = false) {
    if ($htmlstrip) {
        $heading = htmlspecialchars_uni(trim($heading));
        $text = htmlspecialchars_uni(trim($text));
    }
    print("<table class=\"main\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"embedded\">\n");
    print("<div class=\"$div\">".($heading ? "<b>$heading</b><br />" : "")."$text</div></td></tr></table>\n");
}

function stderr($heading = '', $text = '') {
	stdhead();
	stdmsg($heading, $text, 'error');
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
	global $queries;
	print("<table border=\"0\" bgcolor=\"blue\" align=\"left\" cellspacing=\"0\" cellpadding=\"10\" style=\"background: blue\">" .
	"<tr><td class=\"embedded\"><font color=\"white\"><h1>Ошибка в SQL</h1>\n" .
	"<b>Ответ от сервера MySQL: " . htmlspecialchars_uni(mysql_error()) . ($file != '' && $line != '' ? "<p>в $file, линия $line</p>" : "") . "<p>Запрос номер $queries.</p></b></font></td></tr></table>");
	die;
}

// Returns the current time in GMT in MySQL compatible format.
function get_date_time($timestamp = 0) {
	if ($timestamp)
		return date("Y-m-d H:i:s", $timestamp);
	else
		return date("Y-m-d H:i:s");
}

function encodehtml($s, $linebreaks = true) {
	$s = str_replace("<", "&lt;", str_replace("&", "&amp;", $s));
	if ($linebreaks)
		$s = nl2br($s);
	return $s;
}

function get_dt_num() {
	return date("YmdHis");
}

function format_urls($s)
{
	return preg_replace(
    	"/(\A|[^=\]'\"a-zA-Z0-9])((http|ftp|https|ftps|irc):\/\/[^()<>\s]+)/i",
	    "\\1<a href=\"\\2\">\\2</a>", $s);
}

/*

// Removed this fn, I've decided we should drop the redir script...
// it's pretty useless since ppl can still link to pics...
// -Rb

function format_local_urls($s)
{
	return preg_replace(
    "/(<a href=redir\.php\?url=)((http|ftp|https|ftps|irc):\/\/(www\.)?torrentbits\.(net|org|com)(:8[0-3])?([^<>\s]*))>([^<]+)<\/a>/i",
    "<a href=\\2>\\8</a>", $s);
}
*/

//Finds last occurrence of needle in haystack
//in PHP5 use strripos() instead of this
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


function encode_quote($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"FFE5E0\"><td><font class=\"block-title\">Цитата</font></td></tr><tr class=\"bgcolor1\"><td>";
	$end_html = "</td></tr></table></div></div>";
	$text = preg_replace("#\[quote\](.*?)\[/quote\]#si", "".$start_html."\\1".$end_html."", $text);
	return $text;
}

// Format quote from
function encode_quote_from($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"FFE5E0\"><td><font class=\"block-title\">\\1 писал</font></td></tr><tr class=\"bgcolor1\"><td>";
	$end_html = "</td></tr></table></div></div>";
	$text = preg_replace("#\[quote=(.+?)\](.*?)\[/quote\]#si", "".$start_html."\\2".$end_html."", $text);
	return $text;
}

// Format code
function encode_code($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"E5EFFF\"><td colspan=\"2\"><font class=\"block-title\">Код</font></td></tr>"
	."<tr class=\"bgcolor1\"><td align=\"right\" class=\"code\" style=\"width: 5px; border-right: none\">{ZEILEN}</td><td class=\"code\">";
	$end_html = "</td></tr></table></div></div>";
	$match_count = preg_match_all("#\[code\](.*?)\[/code\]#si", $text, $matches);
    for ($mout = 0; $mout < $match_count; ++$mout) {
      $before_replace = $matches[1][$mout];
      $after_replace = $matches[1][$mout];
      $after_replace = trim ($after_replace);
      $zeilen_array = explode ("<br />", $after_replace);
      $j = 1;
      $zeilen = "";
      foreach ($zeilen_array as $str) {
        $zeilen .= "".$j."<br />";
        ++$j;
      }
      $after_replace = str_replace ("", "", $after_replace);
      $after_replace = str_replace ("&amp;", "&", $after_replace);
      $after_replace = str_replace ("", "&nbsp; ", $after_replace);
      $after_replace = str_replace ("", " &nbsp;", $after_replace);
      $after_replace = str_replace ("", "&nbsp; &nbsp;", $after_replace);
      $after_replace = preg_replace ("/^ {1}/m", "&nbsp;", $after_replace);
      $str_to_match = "[code]".$before_replace."[/code]";
      $replace = str_replace ("{ZEILEN}", $zeilen, $start_html);
      $replace .= $after_replace;
      $replace .= $end_html;
      $text = str_replace ($str_to_match, $replace, $text);
    }

    $text = str_replace ("[code]", $start_html, $text);
    $text = str_replace ("[/code]", $end_html, $text);
    return $text;
}

function encode_php($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"F3E8FF\"><td colspan=\"2\"><font class=\"block-title\">PHP - Код</font></td></tr>"
	."<tr class=\"bgcolor1\"><td align=\"right\" class=\"code\" style=\"width: 5px; border-right: none\">{ZEILEN}</td><td>";
	$end_html = "</td></tr></table></div></div>";
	$match_count = preg_match_all("#\[php\](.*?)\[/php\]#si", $text, $matches);
    for ($mout = 0; $mout < $match_count; ++$mout) {
        $before_replace = $matches[1][$mout];
        $after_replace = $matches[1][$mout];
        $after_replace = trim ($after_replace);
		$after_replace = str_replace("&lt;", "<", $after_replace);
		$after_replace = str_replace("&gt;", ">", $after_replace);
		$after_replace = str_replace("&quot;", '"', $after_replace);
		$after_replace = preg_replace("/<br.*/i", "", $after_replace);
		$after_replace = (substr($after_replace, 0, 5 ) != "<?php") ? "<?php\n".$after_replace."" : "".$after_replace."";
		$after_replace = (substr($after_replace, -2 ) != "?>") ? "".$after_replace."\n?>" : "".$after_replace."";
        ob_start ();
        highlight_string ($after_replace);
        $after_replace = ob_get_contents ();
        ob_end_clean ();
		$zeilen_array = explode("<br />", $after_replace);
        $j = 1;
        $zeilen = "";
      foreach ($zeilen_array as $str) {
        $zeilen .= "".$j."<br />";
        ++$j;
      }
		$after_replace = str_replace("\n", "", $after_replace);
		$after_replace = str_replace("&amp;", "&", $after_replace);
		$after_replace = str_replace("  ", "&nbsp; ", $after_replace);
		$after_replace = str_replace("  ", " &nbsp;", $after_replace);
		$after_replace = str_replace("\t", "&nbsp; &nbsp;", $after_replace);
		$after_replace = preg_replace("/^ {1}/m", "&nbsp;", $after_replace);
		$str_to_match = "[php]".$before_replace."[/php]";
		$replace = str_replace("{ZEILEN}", $zeilen, $start_html);
      $replace .= $after_replace;
      $replace .= $end_html;
      $text = str_replace ($str_to_match, $replace, $text);
    }
	$text = str_replace("[php]", $start_html, $text);
	$text = str_replace("[/php]", $end_html, $text);
    return $text;
}

function format_comment($text, $strip_html = true) {
	global $smilies, $privatesmilies,$DEFAULTBASEURL;
	$smiliese = $smilies;
	$s = $text;

  // This fixes the extraneous ;) smilies problem. When there was an html escaped
  // char before a closing bracket - like >), "), ... - this would be encoded
  // to &xxx;), hence all the extra smilies. I created a new :wink: label, removed
  // the ;) one, and replace all genuine ;) by :wink: before escaping the body.
  // (What took us so long? :blush:)- wyz

	$s = str_replace(";)", ":wink:", $s);

	if ($strip_html)
		$s = htmlspecialchars_uni($s);

	$bb[] = "#\[img\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i";
	$html[] = "<img class=\"linked-image\" src=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\" />";
	$bb[] = "#\[img=([a-zA-Z]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\" />";
	$bb[] = "#\[img\ alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\" />";
	$bb[] = "#\[img=([a-zA-Z]+) alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\3\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\" />";
	$bb[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
	$html[] = "<a href=\"\\1\" title=\"\\1\">\\1</a>";
	$bb[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
	$html[] = "<a href=\"http://\\1\" title=\"\\1\">\\1</a>";
	$bb[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$html[] = "<a href=\"\\1\" title=\"\\1\">\\2</a>";
	$bb[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$html[] = "<a href=\"http://\\1\" title=\"\\1\">\\3</a>";
	$bb[] = "/\[url=([^()<>\s]+?)\]((\s|.)+?)\[\/url\]/i";
	$html[] = "<a href=\"\\1\">\\2</a>";
	$bb[] = "/\[url\]([^()<>\s]+?)\[\/url\]/i";
	$html[] = "<a href=\"\\1\">\\1</a>";
	$bb[] = "#\[mail\](\S+?)\[/mail\]#i";
	$html[] = "<a href=\"mailto:\\1\">\\1</a>";
	$bb[] = "#\[mail\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/mail\]#i";
	$html[] = "<a href=\"mailto:\\1\">\\2</a>";
	$bb[] = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*?)\[/color\]#si";
	$html[] = "<span style=\"color: \\1\">\\2</span>";
	$bb[] = "#\[(font|family)=([A-Za-z ]+)\](.*?)\[/\\1\]#si";
	$html[] = "<span style=\"font-family: \\2\">\\3</span>";
	$bb[] = "#\[size=([0-9]+)\](.*?)\[/size\]#si";
	$html[] = "<span style=\"font-size: \\1\">\\2</span>";
	$bb[] = "#\[(left|right|center|justify)\](.*?)\[/\\1\]#is";
	$html[] = "<div align=\"\\1\">\\2</div>";
	$bb[] = "#\[b\](.*?)\[/b\]#si";
	$html[] = "<b>\\1</b>";
	$bb[] = "#\[i\](.*?)\[/i\]#si";
	$html[] = "<i>\\1</i>";
	$bb[] = "#\[u\](.*?)\[/u\]#si";
	$html[] = "<u>\\1</u>";
	$bb[] = "#\[s\](.*?)\[/s\]#si";
	$html[] = "<s>\\1</s>";
	$bb[] = "#\[li\]#si";
	$html[] = "<li>";
	$bb[] = "#\[hr\]#si";
	$html[] = "<hr>";
  $bb[] = "#\[siteurl\]#si";
  $html[] = $DEFAULTBASEURL;

	$s = preg_replace($bb, $html, $s);

	// Linebreaks
	$s = nl2br($s);

	while (preg_match("#\[quote\](.*?)\[/quote\]#si", $s)) $s = encode_quote($s);
	while (preg_match("#\[quote=(.+?)\](.*?)\[/quote\]#si", $s)) {
		$s = encode_quote_from($s);
	}
	while (preg_match("#\[code\](.*?)\[/code\]#si", $s)) $s = encode_code($s);
	while (preg_match("#\[php\](.*?)\[/php\]#si", $s)) $s = encode_php($s);
  //[spoiler]Text[/spoiler]
$s = preg_replace("/\[spoiler\]\s*((\s|.)+?)\s*\[\/spoiler\]\s*/i",
"<div class=\"news-wrap\"><div class=\"news-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Скрытый текст</i></td></tr></table></div><div class=\"news-body\">\\1</div></div>", $s);

    //[spoiler=name]Text[/spoiler]
$s = preg_replace("/\[spoiler=\s*((\s|.)+?)\s*\]((\s|.)+?)\[\/spoiler\]/i",
"<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\"><i>\\1</i></div><div style=\"display: none;\" class=\"news-body\"><div></div>\\3<div class=\"clear\"></div></div></div><div class=\"clear\"></div>", $s);

	// URLs
	$s = format_urls($s);
	//$s = format_local_urls($s);

	// Maintain spacing
	//$s = str_replace("  ", " &nbsp;", $s);

	foreach ($smiliese as $code => $url)
		$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\" alt=\"" . htmlspecialchars_uni($code) . "\">", $s);

	foreach ($privatesmilies as $code => $url)
		$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\">", $s);

	return $s;
}

function get_user_class() {
  global $CURUSER;
  return $CURUSER["class"];
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

function sql_timestamp_to_unix_timestamp($s) {
  return mktime(substr($s, 11, 2), substr($s, 14, 2), substr($s, 17, 2), substr($s, 5, 2), substr($s, 8, 2), substr($s, 0, 4));
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
  $type = sqlesc($type);
  $color = sqlesc($color);
  $text = sqlesc($text);
  $added = sqlesc(get_date_time());
  sql_query("INSERT INTO sitelog (added, color, txt, type) VALUES($added, $color, $text, $type)");
}

function get_elapsed_time($ts) {
  $mins = floor((time() - $ts) / 60);
  $hours = floor($mins / 60);
  $mins -= $hours * 60;
  $days = floor($hours / 24);
  $hours -= $days * 24;
  $weeks = floor($days / 7);
  $days -= $weeks * 7;
  $t = "";
  if ($weeks > 0)
    return "$weeks недел" . ($weeks > 1 ? "и" : "я");
  if ($days > 0)
    return "$days д" . ($days > 1 ? "ней" : "ень");
  if ($hours > 0)
    return "$hours час" . ($hours > 1 ? "ов" : "");
  if ($mins > 0)
    return "$mins минут" . ($mins > 1 ? "" : "а");
  return "< 1 минуты";
}

?>