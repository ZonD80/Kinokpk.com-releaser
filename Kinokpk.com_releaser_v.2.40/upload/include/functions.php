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

if (!function_exists("mysql_set_charset")) {
	function mysql_set_charset($charset) {
		mysql_query("SET NAMES $charset");
	}
}

// retrieve images on the site
function check_images($file){
	$h1count = preg_match_all('/(<img.*?)\s (src="([a-zA-Z0-9\.;:\/\?&=_|\r|\n]{1,})")/isxmU',$file,$patterns);
	$imagesarray = array();
	array_push($imagesarray,$patterns[3]);
	array_push($imagesarray,$patterns[0]);

	$images = $imagesarray[0];
	$imagecodes = $imagesarray[1];
	if ($images)
	foreach ($images as $key => $image) {
		$img = @fopen($image, "r");
		if (!$img) {$bb[] = $imagecodes[$key]; $html[] = $image; } else fclose($img);
	}
	if ($bb)
	$file = str_replace($bb,$html,$file);
	return $file;
}

function httpauth(){
	global $CURUSER, $tracker_lang;

	if(isset($_SERVER['HTTP_AUTHORIZATION']))
	list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(":" , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

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

function textbbcode($form, $name, $content="") {
	?>
<script type="text/javascript" language="JavaScript">
function RowsTextarea(n, w) {
	var inrows = document.getElementById(n);
	if (w < 1) {
		var rows = -5;
	} else {
		var rows = +5;
	}
	var outrows = inrows.rows + rows;
	if (outrows >= 5 && outrows < 50) {
		inrows.rows = outrows;
	}
	return false;
}

var SelField = document.forms['<?=$form;?>'].elements['<?=$name;?>'];
var TxtFeld  = document.forms['<?=$form;?>'].elements['<?=$name;?>'];

var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));

var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

function StoreCaret(text) {
	if (text.createTextRange) {
		text.caretPos = document.selection.createRange().duplicate();
	}
}
function FieldName(text, which) {
	if (text.createTextRange) {
		text.caretPos = document.selection.createRange().duplicate();
	}
	if (which != "") {
		var Field = eval("document.forms['<?=$form?>'].elements['"+which+"']");
		SelField = Field;
		TxtFeld  = Field;
	}
}
function AddSmile(SmileCode) {
	var SmileCode;
	var newPost;
	var oldPost = SelField.value;
	newPost = oldPost+SmileCode;
	SelField.value=newPost;
	SelField.focus();
	return;
}
function AddSelectedText(Open, Close) {
	if (SelField.createTextRange && SelField.caretPos && Close == '\n') {
		var caretPos = SelField.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? Open + Close + ' ' : Open + Close;
		SelField.focus();
	} else if (SelField.caretPos) {
		SelField.caretPos.text = Open + SelField.caretPos.text + Close;
	} else {
		SelField.value += Open + Close;
		SelField.focus();
	}
}
function InsertCode(code, info, type, error) {
	if (code == 'name') {
		AddSelectedText('[b]' + info + '[/b]', '\n');
	} else if (code == 'url' || code == 'mail') {
		if (code == 'url') var url = prompt(info, 'http://');
		if (code == 'mail') var url = prompt(info, '');
		if (!url) return alert(error);
		if ((clientVer >= 4) && is_ie && is_win) {
			selection = document.selection.createRange().text;
			if (!selection) {
				var title = prompt(type, type);
				AddSelectedText('[' + code + '=' + url + ']' + title + '[/' + code + ']', '\n');
			} else {
				AddSelectedText('[' + code + '=' + url + ']', '[/' + code + ']');
			}
		} else {
			mozWrap(TxtFeld, '[' + code + '=' + url + ']', '[/' + code + ']');
		}
	} else if (code == 'color' || code == 'family' || code == 'size') {
		if ((clientVer >= 4) && is_ie && is_win) {
			AddSelectedText('[' + code + '=' + info + ']', '[/' + code + ']');
		} else if (TxtFeld.selectionEnd && (TxtFeld.selectionEnd - TxtFeld.selectionStart > 0)) {
			mozWrap(TxtFeld, '[' + code + '=' + info + ']', '[/' + code + ']');
		}
	} else if (code == 'li' || code == 'hr') {
		if ((clientVer >= 4) && is_ie && is_win) {
			AddSelectedText('[' + code + ']', '');
		} else {
			mozWrap(TxtFeld, '[' + code + ']', '');
		}
	} else {
		if ((clientVer >= 4) && is_ie && is_win) {
			var selection = false;
			selection = document.selection.createRange().text;
			if (selection && code == 'quote') {
				AddSelectedText('[' + code + ']' + selection + '[/' + code + ']', '\n');
			} else {
				AddSelectedText('[' + code + ']', '[/' + code + ']');
			}
		} else {
			mozWrap(TxtFeld, '[' + code + ']', '[/' + code + ']');
		}
	}
}

function mozWrap(txtarea, open, close)
{
        var selLength = txtarea.textLength;
        var selStart = txtarea.selectionStart;
        var selEnd = txtarea.selectionEnd;
        if (selEnd == 1 || selEnd == 2)
                selEnd = selLength;

        var s1 = (txtarea.value).substring(0,selStart);
        var s2 = (txtarea.value).substring(selStart, selEnd)
        var s3 = (txtarea.value).substring(selEnd, selLength);
        txtarea.value = s1 + open + s2 + close + s3;
        txtarea.focus();
        return;
}

language=1;
richtung=1;
var DOM = document.getElementById ? 1 : 0,
opera = window.opera && DOM ? 1 : 0,
IE = !opera && document.all ? 1 : 0,
NN6 = DOM && !IE && !opera ? 1 : 0;
var ablauf = new Date();
var jahr = ablauf.getTime() + (365 * 24 * 60 * 60 * 1000);
ablauf.setTime(jahr);
var richtung=1;
var isChat=false;
NoHtml=true;
NoScript=true;
NoStyle=true;
NoBBCode=true;
NoBefehl=false;

function setZustand() {
	transHtmlPause=false;
	transScriptPause=false;
	transStylePause=false;
	transBefehlPause=false;
	transBBPause=false;
}
setZustand();
function keks(Name,Wert){
	document.cookie = Name+"="+Wert+"; expires=" + ablauf.toGMTString();
}
function changeNoTranslit(Nr){
	if(document.trans.No_translit_HTML.checked)NoHtml=true;else{NoHtml=false}
	if(document.trans.No_translit_BBCode.checked)NoBBCode=true;else{NoBBCode=false}
	keks("NoHtml",NoHtml);keks("NoScript",NoScript);keks("NoStyle",NoStyle);keks("NoBBCode",NoBBCode);
}
function changeRichtung(r){
	richtung=r;keks("TransRichtung",richtung);setFocus()
}
function changelanguage(){
	if (language==1) {language=0;}
	else {language=1;}
	keks("autoTrans",language);
	setFocus();
	setZustand();
}
function setFocus(){
	TxtFeld.focus();
}
function repl(t,a,b){
	var w=t,i=0,n=0;
	while((i=w.indexOf(a,n))>=0){
		t=t.substring(0,i)+b+t.substring(i+a.length,t.length);
		w=w.substring(0,i)+b+w.substring(i+a.length,w.length);
		n=i+b.length;
		if(n>=w.length){
			break;
		}
	}
	return t;
}
var rus_lr2 = ('Е-е-О-о-Ё-Ё-Ё-Ё-Ж-Ж-Ч-Ч-Ш-Ш-Щ-Щ-Ъ-Ь-Э-Э-Ю-Ю-Я-Я-Я-Я-ё-ё-ж-ч-ш-щ-э-ю-я-я').split('-');
var lat_lr2 = ('/E-/e-/O-/o-ЫO-Ыo-ЙO-Йo-ЗH-Зh-ЦH-Цh-СH-Сh-ШH-Шh-ъ'+String.fromCharCode(35)+'-ь'+String.fromCharCode(39)+'-ЙE-Йe-ЙU-Йu-ЙA-Йa-ЫA-Ыa-ыo-йo-зh-цh-сh-шh-йe-йu-йa-ыa').split('-');
var rus_lr1 = ('А-Б-В-Г-Д-Е-З-И-Й-К-Л-М-Н-О-П-Р-С-Т-У-Ф-Х-Х-Ц-Щ-Ы-Я-а-б-в-г-д-е-з-и-й-к-л-м-н-о-п-р-с-т-у-ф-х-х-ц-щ-ъ-ы-ь-я').split('-');
var lat_lr1 = ('A-B-V-G-D-E-Z-I-J-K-L-M-N-O-P-R-S-T-U-F-H-X-C-W-Y-Q-a-b-v-g-d-e-z-i-j-k-l-m-n-o-p-r-s-t-u-f-h-x-c-w-'+String.fromCharCode(35)+'-y-'+String.fromCharCode(39)+'-q').split('-');
var rus_rl = ('А-Б-В-Г-Д-Е-Ё-Ж-З-И-Й-К-Л-М-Н-О-П-Р-С-Т-У-Ф-Х-Ц-Ч-Ш-Щ-Ъ-Ы-Ь-Э-Ю-Я-а-б-в-г-д-е-ё-ж-з-и-й-к-л-м-н-о-п-р-с-т-у-ф-х-ц-ч-ш-щ-ъ-ы-ь-э-ю-я').split('-');
var lat_rl = ('A-B-V-G-D-E-JO-ZH-Z-I-J-K-L-M-N-O-P-R-S-T-U-F-H-C-CH-SH-SHH-'+String.fromCharCode(35)+String.fromCharCode(35)+'-Y-'+String.fromCharCode(39)+String.fromCharCode(39)+'-JE-JU-JA-a-b-v-g-d-e-jo-zh-z-i-j-k-l-m-n-o-p-r-s-t-u-f-h-c-ch-sh-shh-'+String.fromCharCode(35)+'-y-'+String.fromCharCode(39)+'-je-ju-ja').split('-');
var transAN=true;
function transliteText(txt){
	vorTxt=txt.length>1?txt.substr(txt.length-2,1):"";
	buchstabe=txt.substr(txt.length-1,1);
	txt=txt.substr(0,txt.length-2);
	return txt+translitBuchstabeCyr(vorTxt,buchstabe);
}
function translitBuchstabeCyr(vorTxt,txt){
	var zweiBuchstaben = vorTxt+txt;
	var code = txt.charCodeAt(0);

	if (txt=="<")transHtmlPause=true;else if(txt==">")transHtmlPause=false;
	if (txt=="<script")transScriptPause=true;else if(txt=="<"+"/script>")transScriptPause=false;
	if (txt=="<style")transStylePause=true;else if(txt=="<"+"/style>")transStylePause=false;
	if (txt=="[")transBBPause=true;else if(txt=="]")transBBPause=false;
	if (txt=="/")transBefehlPause=true;else if(txt==" ")transBefehlPause=false;

	if (
		(transHtmlPause==true &&   NoHtml==true)||
		(transScriptPause==true &&   NoScript==true)||
		(transStylePause==true &&   NoStyle==true)||
		(transBBPause==true &&   NoBBCode==true)||
		(transBefehlPause==true &&   NoBefehl==true)||

		!(((code>=65) && (code<=123))||(code==35)||(code==39))) return zweiBuchstaben;

	for (x=0; x<lat_lr2.length; x++){
		if (lat_lr2[x]==zweiBuchstaben) return rus_lr2[x];
	}
	for (x=0; x<lat_lr1.length; x++){
		if (lat_lr1[x]==txt) return vorTxt+rus_lr1[x];
	}
	return zweiBuchstaben;
}
function translitBuchstabeLat(buchstabe){
	for (x=0; x<rus_rl.length; x++){
		if (rus_rl[x]==buchstabe)
		return lat_rl[x];
	}
	return buchstabe;
}
function translateAlltoLatin(){
	if (!IE){
		var txt=TxtFeld.value;
		var txtnew = "";
		var symb = "";
		for (y=0;y<txt.length;y++){
			symb = translitBuchstabeLat(txt.substr(y,1));
			txtnew += symb;
		}
		TxtFeld.value = txtnew;
		setFocus()
	} else {
		var is_selection_flag = 1;
		var userselection = document.selection.createRange();
		var txt = userselection.text;

		if (userselection==null || userselection.text==null || userselection.parentElement==null || userselection.parentElement().type!="textarea"){
			is_selection_flag = 0;
			txt = TxtFeld.value;
		}
		txtnew="";
		var symb = "";
		for (y=0;y<txt.length;y++){
			symb = translitBuchstabeLat(txt.substr(y,1));
			txtnew +=  symb;
		}
		if (is_selection_flag){
			userselection.text = txtnew; userselection.collapse(); userselection.select();
		}else{
			TxtFeld.value = txtnew;
			setFocus()
		}
	}
	return;
}
function TransliteFeld(object, evnt){
	if (language==1 || opera) return;
	if (NN6){
		var code=void 0;
		var code =  evnt.charCode;
		var textareafontsize = 14;
		var textreafontwidth = 7;
		if(code == 13){
			return;
		}
		if ( code && (!(evnt.ctrlKey || evnt.altKey))){
			pXpix = object.scrollTop;
			pYpix = object.scrollLeft;
        	evnt.preventDefault();
			txt=String.fromCharCode(code);
			pretxt = object.value.substring(0, object.selectionStart);
			result = transliteText(pretxt+txt);
			object.value = result+object.value.substring(object.selectionEnd);
			object.setSelectionRange(result.length,result.length);
			object.scrollTop=100000;
			object.scrollLeft=0;

			cXpix = (result.split("\n").length)*(textareafontsize+3);
			cYpix = (result.length-result.lastIndexOf("\n")-1)*(textreafontwidth+1);
			taXpix = (object.rows+1)*(textareafontsize+3);
			taYpix = object.clientWidth;

			if ((cXpix>pXpix)&&(cXpix<(pXpix+taXpix))) object.scrollTop=pXpix;
			if (cXpix<=pXpix) object.scrollTop=cXpix-(textareafontsize+3);
			if (cXpix>=(pXpix+taXpix)) object.scrollTop=cXpix-taXpix;

			if ((cYpix>=pYpix)&&(cYpix<(pYpix+taYpix))) object.scrollLeft=pYpix;
			if (cYpix<pYpix) object.scrollLeft=cYpix-(textreafontwidth+1);
			if (cYpix>=(pYpix+taYpix)) object.scrollLeft=cYpix-taYpix+1;
		}
		return true;
	} else if (IE){
		if (isChat){
			var code = frames['input'].event.keyCode;
			if(code == 13){
				return;
			}
			txt=String.fromCharCode(code);
			cursor_pos_selection = frames['input'].document.selection.createRange();
			cursor_pos_selection.text="";
			cursor_pos_selection.moveStart("character",-1);
			vorTxt = cursor_pos_selection.text;
			if (vorTxt.length>1){
				vorTxt="";
			}
			frames['input'].event.keyCode = 0;
			if (richtung==2){
				result = vorTxt+translitBuchstabeLat(txt)
			}else{
				result = translitBuchstabeCyr(vorTxt,txt)
			}
			if (vorTxt!=""){
				cursor_pos_selection.select(); cursor_pos_selection.collapse();
			}
			with(frames['input'].document.selection.createRange()){
				text = result; collapse(); select()
			}
		} else {
			var code = event.keyCode;
			if(code == 13){
				return;
			}
			txt=String.fromCharCode(code);
			cursor_pos_selection = document.selection.createRange();
			cursor_pos_selection.text="";
			cursor_pos_selection.moveStart("character",-1);
			vorTxt = cursor_pos_selection.text;
			if (vorTxt.length>1){
				vorTxt="";
			}
			event.keyCode = 0;
			if (richtung==2){
				result = vorTxt+translitBuchstabeLat(txt)
			}else{
				result = translitBuchstabeCyr(vorTxt,txt)
			}
			if (vorTxt!=""){
				cursor_pos_selection.select(); cursor_pos_selection.collapse();
			}
			with(document.selection.createRange()){
				text = result; collapse(); select()
			}
		}
		return;
   }
}
function translateAlltoCyrillic(){
	if (!IE){
		txt = TxtFeld.value;
		var txtnew = translitBuchstabeCyr("",txt.substr(0,1));
		var symb = "";
		for (kk=1;kk<txt.length;kk++){
			symb = translitBuchstabeCyr(txtnew.substr(txtnew.length-1,1),txt.substr(kk,1));
			txtnew = txtnew.substr(0,txtnew.length-1) + symb;
		}
		TxtFeld.value = txtnew;
		setFocus()
	}else{
		var is_selection_flag = 1;
		var userselection = document.selection.createRange();
		var txt = userselection.text;
		if (userselection==null || userselection.text==null || userselection.parentElement==null || userselection.parentElement().type!="textarea"){
			is_selection_flag = 0;
			txt = TxtFeld.value;
		}
		var txtnew = translitBuchstabeCyr("",txt.substr(0,1));
		var symb = "";
		for (kk=1;kk<txt.length;kk++){
			symb = translitBuchstabeCyr(txtnew.substr(txtnew.length-1,1),txt.substr(kk,1));
			txtnew = txtnew.substr(0,txtnew.length-1) + symb;
		}
		if (is_selection_flag){
			userselection.text = txtnew; userselection.collapse(); userselection.select();
		}else{
			TxtFeld.value = txtnew;
			setFocus()
		}
	}
	return;
}
function openpopup(win)
{
windop = window.open(win+".php?form=<?=$form?>&text=<?=$name?>","mywin","height=600,width=600,resizable=no,scrollbars=yes");
}
</script>
<textarea class="editorinput" id="area" name="<?=$name;?>" cols="65"
	rows="10" style="width: 400px" OnKeyPress="TransliteFeld(this, event)"
	OnSelect="FieldName(this, this.name)"
	OnClick="FieldName(this, this.name)"
	OnKeyUp="FieldName(this, this.name)"><?=$content;?></textarea>
<table>
	<tr>
		<td><a href="javascript:openpopup('stamp');"><b>Штампы</b></a></td>
		<td><a href="javascript:openpopup('moresmiles');"><b>Все смайлы</b></a></td>
	</tr>
</table>
<div class="editor"
	style="background-image: url(editor/bg.gif); background-repeat: repeat-x;">
<div class="editorbutton" OnClick="RowsTextarea('area',1)"><img
	title="Увеличить окно" src="editor/plus.gif"></div>
<div class="editorbutton" OnClick="RowsTextarea('area',0)"><img
	title="Уменьшить окно" src="editor/minus.gif"></div>
<div class="editorbutton" OnClick="InsertCode('b')"><img
	title="Жирный текст" src="editor/bold.gif"></div>
<div class="editorbutton" OnClick="InsertCode('i')"><img
	title="Наклонный текст" src="editor/italic.gif"></div>
<div class="editorbutton" OnClick="InsertCode('u')"><img
	title="Подчеркнутый текст" src="editor/underline.gif"></div>
<div class="editorbutton" OnClick="InsertCode('s')"><img
	title="Перечеркнутый текст" src="editor/striket.gif"></div>
<div class="editorbutton" OnClick="InsertCode('li')"><img
	title="Маркированный список" src="editor/li.gif"></div>
<div class="editorbutton" OnClick="InsertCode('hr')"><img
	title="Разделительная линия" src="editor/hr.gif"></div>
<div class="editorbutton" OnClick="InsertCode('left')"><img
	title="Выравнивание по левому краю" src="editor/left.gif"></div>
<div class="editorbutton" OnClick="InsertCode('center')"><img
	title="Выравнивание по центру" src="editor/center.gif"></div>
<div class="editorbutton" OnClick="InsertCode('right')"><img
	title="Выравнивание по правому краю" src="editor/right.gif"></div>
<div class="editorbutton" OnClick="InsertCode('justify')"><img
	title="Выравнивание по ширине" src="editor/justify.gif"></div>
<div class="editorbutton" OnClick="InsertCode('code')"><img title="Код"
	src="editor/code.gif"></div>
<div class="editorbutton" OnClick="InsertCode('php')"><img
	title="PHP-Код" src="editor/php.gif"></div>
<div class="editorbutton" OnClick="InsertCode('spoiler')"><img
	title="Спойлер" src="editor/hide.gif"></div>
<div class="editorbutton"
	OnClick="InsertCode('url','Введите полный адрес','Введите описание','Вы не указали адрес!')"><img
	title="Вставить ссылку" src="editor/url.gif"></div>
<div class="editorbutton"
	OnClick="InsertCode('mail','Введите полный адрес','Введите описание','Вы не указали адрес!')"><img
	title="Вставить E-Mail" src="editor/mail.gif"></div>
<div class="editorbutton" OnClick="InsertCode('img')"><img
	title="Вставить картинку" src="editor/img.gif"></div>
</div>
<div class="editor"
	style="background-image: url(editor/bg.gif); background-repeat: repeat-x;">
<div class="editorbutton" OnClick="InsertCode('quote')"><img
	title="Цитировать" src="editor/quote.gif"></div>
<div class="editorbutton" OnClick="translateAlltoCyrillic()"><img
	title="Перевод текста с латиницы в кириллицу" src="editor/rus.gif"></div>
<div class="editorbutton" OnClick="translateAlltoLatin()"><img
	title="Перевод текста с кириллицы в латиницу" src="editor/eng.gif"></div>
<div class="editorbutton" OnClick="changelanguage()"><img
	title="Автоматический перевод текста" src="editor/auto.gif"></div>
<div class="editorbutton"><select class="editorinput" tabindex="1"
	style="font-size: 10px;" name="family"
	onChange="InsertCode('family',this.options[this.selectedIndex].value); this.value='Verdana';">
	<option style="font-family: Verdana;" value="Verdana">Verdana</option>
	<option style="font-family: Arial;" value="Arial">Arial</option>
	<option style="font-family: 'Courier New';" value="Courier New">Courier
	New</option>
	<option style="font-family: Tahoma;" value="Tahoma">Tahoma</option>
	<option style="font-family: Helvetica;" value="Helvetica">Helvetica</option>
</select></div>
<div class="editorbutton"><select class="editorinput" tabindex="1"
	style="font-size: 10px;" name="color"
	onChange="InsertCode('color',this.options[this.selectedIndex].value); this.value='black';">
	<option style="color: black;" value="black">Цвет шрифта</option>
	<option style="color: silver;" value="silver">Цвет шрифта</option>
	<option style="color: gray;" value="gray">Цвет шрифта</option>
	<option style="color: white;" value="white">Цвет шрифта</option>
	<option style="color: maroon;" value="maroon">Цвет шрифта</option>
	<option style="color: red;" value="red">Цвет шрифта</option>
	<option style="color: purple;" value="purple">Цвет шрифта</option>
	<option style="color: fuchsia;" value="fuchsia">Цвет шрифта</option>
	<option style="color: green;" value="green">Цвет шрифта</option>
	<option style="color: lime;" value="lime">Цвет шрифта</option>
	<option style="color: olive;" value="olive">Цвет шрифта</option>
	<option style="color: yellow;" value="yellow">Цвет шрифта</option>
	<option style="color: navy;" value="navy">Цвет шрифта</option>
	<option style="color: blue;" value="blue">Цвет шрифта</option>
	<option style="color: teal;" value="teal">Цвет шрифта</option>
	<option style="color: aqua;" value="aqua">Цвет шрифта</option>
</select></div>
<div class="editorbutton"><select class="editorinput" tabindex="1"
	style="font-size: 10px;" name="size"
	onChange="InsertCode('size',this.options[this.selectedIndex].value); this.value='10';">
	<option value="8">Размер 8</option>
	<option value="10">Размер 10</option>
	<option value="12">Размер 12</option>
	<option value="14">Размер 14</option>
	<option value="18">Размер 18</option>
	<option value="24">Размер 24</option>
</select></div>
</div>
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
	global $smilies, $privatesmilies,$CACHEARRAY;
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
	$html[] = $CACHEARRAY['defaultbaseurl'];

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
	$s = str_replace("[spoiler]","<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Скрытый текст</i></td></tr></table></div><div class=\"news-body\">", $s);
	// continue below //

	//[spoiler=name]Text[/spoiler]
	$s = preg_replace("#\[spoiler=\s*((\s|.)+?)\s*\]#si",
"<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\"><i>\\1</i></div><div style=\"display: none;\" class=\"news-body\">", $s);

	$s = str_replace("[/spoiler]","</div></div>",$s);
	// URLs
	$s = format_urls($s);
	//$s = format_local_urls($s);

	// Maintain spacing
	//$s = str_replace("  ", " &nbsp;", $s);

	foreach ($smiliese as $code => $url)
	$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\" alt=\"" . htmlspecialchars_uni($code) . "\">", $s);

	foreach ($privatesmilies as $code => $url)
	$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\">", $s);

	return check_images($s);
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

function check_banned_emails ($email) {
	$expl = explode("@", $email);
	$wildemail = "*@".$expl[1];
	$res = mysql_query("SELECT id, comment FROM bannedemails WHERE email = ".sqlesc($email)." OR email = ".sqlesc($wildemail)."") or sqlerr(__FILE__, __LINE__);
	if ($arr = mysql_fetch_assoc($res))
	stderr("Ошибка!","Этот емайл адресс забанен!<br /><br /><strong>Причина</strong>: $arr[comment]", false);
}

function get_elapsed_time($date,$showseconds=true,$unix=true){
	if($date == "0000-00-00 00:00:00") return "---";
	if(!$unix){$U = date('U',strtotime($date));}else{$U=$date;};
	$N = time();
	$diff = $N-$U;
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

function dbconn($autoclean = false, $lightmode = false) {
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset, $CACHEARRAY;

	if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
	die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());

	mysql_select_db($mysql_db)
	or die("dbconn: mysql_select_db: " + mysql_error());

	mysql_set_charset($mysql_charset);

	// caching begin
	$cacherow = sql_query("SELECT * FROM cache_stats");
	while ($cacheres = mysql_fetch_array($cacherow))
	$CACHEARRAY[$cacheres['cache_name']] = $cacheres['cache_value'];
	//caching end

	userlogin($lightmode);

	if (basename($_SERVER['SCRIPT_FILENAME']) == 'index.php')
	register_shutdown_function("autoclean");

	register_shutdown_function("mysql_close");

	// This is original copyright, please leave it alone. Remember, that the Developers worked hard for weeks, drank ~67 litres of a beer (hoegaarden and baltica 7) and ate more then 15.1 kilogrammes of hamburgers to present this source. Don't be evil (C) Google
	// Не удаляйте оригинальный копирайт. Помните, что разработчики неделями трудились над этим движком, выпили ~67 литров пива (hoegaarden and baltica 7) и съели 15.1 килограмма гамбургеров. Не будьте дьяволом (С) Гугл
	define ("TBVERSION", ($CACHEARRAY['yourcopy']?str_replace("{datenow}",date("Y"),$CACHEARRAY['yourcopy']).". ":"")."Powered by <a target=\"_blank\" href=\"http://www.kinokpk.com\">Kinokpk.com</a> releaser v. ".RELVERSION." &copy; 2008-".date("Y").". <a target=\"_blank\" href=\"http://dev.kinokpk.com\">Developer's corner</a> of this source.");

}

function userlogin($lightmode = false) {
	global $tracker_lang, $CACHEARRAY, $visit_t_minutes;
	unset($GLOBALS["CURUSER"]);

	$ip = getip();

	if ($CACHEARRAY['use_ipbans']) {

		if (!defined("CACHE_REQUIRED")){
			require_once(ROOT_PATH . 'classes/cache/cache.class.php');
			require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
			define("CACHE_REQUIRED",1);
		}

		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

		$maskres = $cache->get('bans', 'query');
		if ($maskres ===false){
			$res = sql_query("SELECT mask FROM bans");
			$maskres = array();

			while (list($mask) = mysql_fetch_array($res))
			$maskres[] = $mask;

			$cache->set('bans', 'query', $maskres);
		}

		require_once(ROOT_PATH.'classes/bans/ipcheck.class.php');
		$ipsniff = new IPAddressSubnetSniffer($maskres);
		if ($ipsniff->ip_is_allowed($ip) )
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
	$id = 0 + $_COOKIE["uid"];
	$res = sql_query("SELECT * FROM users WHERE id = $id AND status = 'confirmed'");// or die(mysql_error());
	$row = mysql_fetch_array($res);
	if (!$row) {
		getlang();
		user_session();
		return;
	} elseif (($row['enabled'] == 'no') && !defined("IN_CONTACT")) die('Sorry, your account has been disabled by administation. You can contact admins via <a href="contact.php">FeedBack Form</a>. Reason: '.$row['dis_reason']);

	$sec = hash_pad($row["secret"]);
	if ($_COOKIE["pass"] !== $row["passhash"]) {
		getlang();
		user_session();
		return;
	}

	$updateset = array();


	if ($ip != $row['ip'])
	$updateset[] = 'ip = '. sqlesc($ip);
	if (strtotime($row['last_access']) < (strtotime(get_date_time()) - 300))
	$updateset[] = 'last_access = ' . sqlesc(get_date_time());

	if (count($updateset))
	sql_query("UPDATE LOW_PRIORITY users SET ".implode(", ", $updateset)." WHERE id=" . $row["id"]);// or die(mysql_error());
	$row['ip'] = $ip;

	if ($row['override_class'] < $row['class'])
	$row['class'] = $row['override_class']; // Override class and save in GLOBAL array below.

	$GLOBALS["CURUSER"] = $row;
	getlang();

	if (!$lightmode)
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
			$load = split("load averages?: ", $load);
			$serverload = explode(",", $load[1]);
		}
	} else {
		$load = @exec("uptime");
		$load = split("load averages?: ", $load);
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
	sql_query("UPDATE sessions SET ".implode(", ", $updateset)." WHERE ".implode(" AND ", $where)) or sqlerr(__FILE__,__LINE__);
	if (mysql_modified_rows() < 1)
	sql_query("INSERT INTO sessions (sid, uid, username, class, ip, time, url, useragent) VALUES (".implode(", ", array_map("sqlesc", array($sid, $uid, $username, $class, $ip, $ctime, $url, $agent))).")") or sqlerr(__FILE__,__LINE__);
}

function unesc($x) {
	$x = trim($x);

	if (get_magic_quotes_gpc())
	$x = stripslashes($x);
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
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && validip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && validip($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	} else {
		if (getenv('HTTP_X_FORWARDED_FOR') && validip(getenv('HTTP_X_FORWARDED_FOR'))) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_CLIENT_IP') && validip(getenv('HTTP_CLIENT_IP'))) {
			$ip = getenv('HTTP_CLIENT_IP');
		} else {
			$ip = getenv('REMOTE_ADDR');
		}
	}

	return $ip;
}

function autoclean() {
	global $CACHEARRAY;

	$now = time();
	$docleanup = 0;

	$row = $CACHEARRAY['lastcleantime'];

	if ($row + $CACHEARRAY['autoclean_interval'] > $now)
	return;
	sql_query("UPDATE cache_stats SET cache_value=$now WHERE cache_name='lastcleantime'") or die(mysql_error());
	if (!mysql_affected_rows())
	return;

	require_once(ROOT_PATH . 'include/cleanup.php');

	docleanup();
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

function mkprettytime($input, $time = true) {
	$search = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$replace = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
	$seconds = strtotime($input);
	if ($time == true)
	$data = date("j F Y в H:i:s", $seconds);
	else
	$data = date("j F Y", $seconds);
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

function validemail($email)
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
				return false;
			}
		}
	}
	return true;
}

function sent_mail($to,$fromname,$fromemail,$subject,$body,$multiple=false,$multiplemail='') {
	global $CACHEARRAY,$smtp,$smtp_host,$smtp_port,$smtp_from,$smtpaddress,$accountname,$accountpassword;
	# Sent Mail Function v.05 by xam (This function to help avoid spam-filters.)
	$result = true;
	if ($CACHEARRAY['smtptype'] == 'default') {
		@mail($to, $subject, $body, "From: {$CACHEARRAY['siteemail']}") or $result = false;
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
		if ($smtp == "yes") {
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
	// Stripslashes
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	// Quote if not a number or a numeric string
	if (!is_numeric($value)) {
		$value = "'" . mysql_real_escape_string($value) . "'";
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
		$d = str_replace("\n", "\n<br>", $d);
	}
	return $d;
}

function stdhead($title = "", $msgalert = true) {

	global $CURUSER, $FUNDS, $CACHEARRAY, $ss_uri, $tracker_lang;

	$row = unserialize($CACHEARRAY['siteonline']);

	if ($row["onoff"] !=1){
		$my_siteoff = 1;
		$my_siteopenfor = $row['class_name'];
	}

	//$row["onoff"] = 1;//АВАРИЙНЫЙ ВХОД: Раскомментировать строку, если не можете войти !!!


	if (($row["onoff"] !=1) && (!$CURUSER)){
		die("<title>{$CACHEARRAY['sitename']} :: Under construction/Сайт Закрыт!</title>
        <table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>
        <h1 style='color: #CC3300;'>".$row['reason']."</h1>
        <h1 style='color: #CC3300;'>
        Пожалуйста, зайдите позже...</h1>
        <br><center><form method='post' action='takesiteofflogin.php'>
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
			if($row2['seeder'] == 'yes')
			$activeseed++;

			if($row2['seeder'] == 'no')
			$activeleech++;
		}

		if ($ss_a)
		$ss_uri = $ss_a["uri"];
		else
		$ss_uri = $CACHEARRAY['default_theme'];
	} else
	$ss_uri = $CACHEARRAY['default_theme'];

	if ($msgalert && $CURUSER) {
		$msgs = sql_query("SELECT location, sender, receiver, unread, saved FROM messages WHERE receiver = " . $CURUSER["id"] . " OR sender = " . $CURUSER["id"]) or sqlerr(__FILE__,__LINE__);

		while ($message = mysql_fetch_array($msgs)) {
			if ($message["unread"] == "yes" && $message["location"] == 1 && $message["receiver"] == $CURUSER["id"])
			$unread++;
			if ($message["location"] == 1 && $message["receiver"] == $CURUSER["id"])
			$messages++;
			if ($message["saved"] == "yes" && $message["location"] != 0 && $message["sender"] == $CURUSER["id"])
			$outmessages++;
		}
	}
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");

	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='. $tracker_lang['language_charset'].'" />
<meta name="Description" content="'.$CACHEARRAY['description'].'" />
<meta name="Keywords" content="'.$CACHEARRAY['keywords'].'" />
<!--Тоже любишь смотреть исходники HTML? Знаешь еще и PHP/MySQL? обратись к админам, наверняка для тебя есть местечко в нашей команде http://www.kinokpk.com/staff.php -->
<title>'.$title.'</title>
<link rel="stylesheet" href="themes/'.$ss_uri.'/'.$ss_uri.'.css" type="text/css"/>
<link rel="stylesheet" href="css/features.css" type="text/css"/>
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>'
	.((!$CURUSER || ($CURUSER['extra_ef'] == 'yes'))?'
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->
<script language="javascript" type="text/javascript" src="js/tooltips.js"></script>':'').
'<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(
function(){
  $(\'div.news-head\')
  .click(function() {
    $(this).toggleClass(\'unfolded\');
    $(this).next(\'div.news-body\').slideToggle(\'slow\');
  });
    $(function() {
  $("a[@href^=http]").each(
    function(){
            if(this.href.indexOf(location.hostname) == -1) {
        $(this).addClass(\'external\').attr(\'target\', \'_blank\');
      }
    }
  )
  });
});
</script>
<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$CACHEARRAY['defaultbaseurl'].'/rss.php" />
<link rel="alternate" type="application/atom+xml" title="Atom" href="'.$CACHEARRAY['defaultbaseurl'].'/atom.php" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
');

	if ($row['onoff'] != 1) print('<div align="center"><font color="red" size="20">ADMIN WARNING: SITE IS CLOSED FOR MAINTENANCE!</font></div>');

	@require_once(ROOT_PATH."themes/" . $ss_uri . "/template.php");
	@require_once(ROOT_PATH."themes/" . $ss_uri . "/stdhead.php");

} // stdhead

function stdfoot() {
	global $CURUSER, $ss_uri, $tracker_lang, $queries, $tstart, $query_stat, $querytime, $CACHEARRAY;

	@require_once(ROOT_PATH."themes/" . $ss_uri . "/template.php");
	@require_once(ROOT_PATH."themes/" . $ss_uri . "/stdfoot.php");

	if (($CACHEARRAY['debug_mode']) && count($query_stat) && (get_user_class() >= UC_SYSOP)) {
		$qsec = 0;
		foreach ($query_stat as $key => $value) {
			$qsec = $qsec + $value['seconds'];
			print("<div>[".($key+1)."] => <b>".$value["seconds"]."</b> [$value[query]]</div>\n");
		}
		print("<b>ALL DATABASE QUERIES TOOK $qsec SECONDS</b>");
		print('<div align="center"><font color="red"><b>Warning! Debug mode active! Only SYSOP can see this message and above queries.</b></font></div><br/>');
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

function gmtime() {
	return strtotime(get_date_time());
}

function logincookie($id, $passhash, $language, $updatedb = 1, $expires = 0x7fffffff) {
	setcookie("uid", $id, $expires, "/");
	setcookie("pass", $passhash, $expires, "/");
	setcookie("lang", $language, $expires, "/");

	if ($updatedb)
	sql_query("UPDATE users SET last_login = NOW() WHERE id = $id");
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

	sql_query("DELETE FROM ratings WHERE torrent = $id") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM checkcomm WHERE checkid = $id AND torrent = 1") or sqlerr(__FILE__,__LINE__);

	sql_query("DELETE FROM torrents WHERE id = $id");
	sql_query("DELETE FROM bookmarks WHERE id = $id");
	foreach(explode(".","snatched.descr_torrents.peers.files.comments.ratings") as $x)
	sql_query("DELETE FROM $x WHERE torrent = $id");
	@unlink("torrents/$id.torrent");
	sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='torrents_lastupdate'");

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
		$pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\">$pager $pagerstr $pager2</table>\n";
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
		$pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\">$pager $pagerstr $pager2</table>\n";
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
			if ($row["seeder"] == "yes")
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
	global $CURUSER, $CACHEARRAY;

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
		if (strtotime($row["last_access"]) > gmtime() - 600) {
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
			print(":: <img src=\"pic/buttons/button_".$online.".gif\" alt=\"".$online_text."\" title=\"".$online_text."\" style=\"position: relative; top: 2px;\" border=\"0\" height=\"14\">"
			." <a name=comm". $row["id"]." href=userdetails.php?id=" . $row["user"] . " class=altlink_white><b>". get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a> ::"
			.($row["donor"] == "yes" ? "<img src=pic/star.gif alt='Donor'>" : "") . ($row["warned"] == "yes" ? "<img src=\"/pic/warned.gif\" alt=\"Warned\">" : "") . " $title ::\n")
			." <img src=\"pic/upl.gif\" alt=\"upload\" border=\"0\" width=\"12\" height=\"12\"> ".mksize($row["uploaded"]) ." :: <img src=\"pic/down.gif\" alt=\"download\" border=\"0\" width=\"12\" height=\"12\"> ".mksize($row["downloaded"])." :: <font color=\"".get_ratio_color($ratio)."\">$ratio</font> :: ";

		} else {
			print("<a name=\"comm" . $row["id"] . "\"><i>[Anonymous]</i></a>\n");
		}

		$avatar = ($CURUSER["avatars"] == "yes" ? htmlspecialchars_uni($row["avatar"]) : "");
		if (!$avatar){$avatar = "pic/default_avatar.gif"; }
		$text = format_comment($row["text"]);

		if ($row["editedby"]) {
			//$res = mysql_fetch_assoc(sql_query("SELECT * FROM users WHERE id = $row[editedby]")) or sqlerr(__FILE__,__LINE__);
			$text .= "<p><font size=1 class=small>Последний раз редактировалось <a href=userdetails.php?id=$row[editedby]><b>$row[editedbyname]</b></a> в $row[editedat]</font></p>\n";
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
		.($row["editedby"] && get_user_class() >= UC_MODERATOR ? " [<a href=\"".$redaktor.".php?action=vieworiginal&amp;cid=$row[id]\" class=\"altlink_white\">Оригинал</a>]" : "")
		.(get_user_class() >= UC_MODERATOR ? " IP: ".($row["ip"] ? "<a href=\"usersearch.php?ip=$row[ip]\" class=\"altlink_white\">".$row["ip"]."</a>" : "Неизвестен" ) : "")
		."</div>";

		print("<div align=\"right\"><!--<font size=1 class=small>-->Комментарий добавлен: ".$row["added"]." GMT<!--</font>--></td></tr>");
		print("</table><br>");
	}

}

function searchfield($s) {
	return preg_replace(array('/[^a-z0-9]/si', '/^\s*/s', '/\s*$/s', '/\s+/s'), array(" ", "", "", " "), $s);
}

function genrelist() {
	$ret = array();
	$res = sql_query("SELECT id, name FROM categories ORDER BY sort ASC");
	while ($row = mysql_fetch_array($res))
	$ret[] = $row;
	return $ret;
}

function descrlist($cat) {
	$ret = array();
	$res = sql_query("SELECT id, type AS name FROM descr_types WHERE category = $cat");
	while ($row = mysql_fetch_array($res))
	$ret[] = $row;
	return $ret;
}

function taggenrelist($cat) {
	if (!$cat) return;
	$ret = array();
	$res = sql_query("SELECT id, name FROM tags WHERE category=$cat ORDER BY name ASC");
	while ($row = mysql_fetch_array($res))
	$ret[] = $row;
	return $ret;
}

function tag_info() {

	$result = sql_query("SELECT name, howmuch FROM tags WHERE howmuch > 0 ORDER BY id DESC");

	while($row = mysql_fetch_assoc($result)) {
		// suck into array
		$arr[$row['name']] = $row['howmuch'];
	}
	//sort array by key
	@ksort($arr);

	return $arr;
}

function cloud3d() {
	//min / max font sizes
	$small = 7;
	$big = 20;
	//get tag info from worker function
	$tags = tag_info();
	//amounts
	$minimum_count = @min(array_values($tags));
	$maximum_count = @max(array_values($tags));
	$spread = $maximum_count - $minimum_count;

	if($spread == 0) {$spread = 1;}

	$cloud_html = '';

	$cloud_tags = array();
	$i = 0;
	if ($tags)
	foreach ($tags as $tag => $count) {

		$size = $small + ($count - $minimum_count) * ($big - $small) / $spread;

		//spew out some html malarky!
		$cloud_tags[] = "<a href='browse.php?tag=" . $tag . "%26amp;cat=0%26amp;incldead=1' style='font-size:". floor($size) . "px;'>"
		. htmlentities($tag,ENT_QUOTES, "cp1251") . "(".$count.")</a>";
		$cloud_links[] = "<br/><a href='browse.php?tag=" . $tag . "&cat=&incldead=1' style='font-size:". floor($size) . "px;'>$tag</a><br/>";
		$i++;
	}
	$cloud_links[$i-1].="Ваш браузер не поддерживает flash!";
	$cloud_html[0] = join("", $cloud_tags);
	$cloud_html[1] = join("", $cloud_links);


	return $cloud_html;
}

function cloud ($style = '',$name = '', $color='',$bgcolor='',$width='',$height='',$speed='',$size='') {
	$tagsres = array();
	$tagsres = cloud3d();
	$tags = $tagsres[0];
	$links = $tagsres[1];
	if (!$style) $style = '<style type="text/css">
.tag_cloud
{padding: 3px; text-decoration: none;
font-family: verdana; }
.tag_cloud:link { color: #0099FF; text-decoration:none;border:1px transparent solid;}
.tag_cloud:visited { color: #00CCFF; border:1px transparent solid;}
.tag_cloud:hover { color: #0000FF; background: #ddd;border:1px #bbb solid; }
.tag_cloud:active { color: #0000FF; background: #fff; border:1px transparent solid;}
#tag
{
line-height:28px;
font-family:Verdana, Arial, Helvetica, sans-serif;
text-align:justify;
}
</style>';

	$cloud_html = $style.'<div id="wrapper"><p id="tag">
  <script type="text/javascript" src="js/swfobject.js"></script>
<div id="'.($name?$name:"wpcumuluswidgetcontent").'">'.$links.'</div>
<script type="text/javascript">
var rnumber = Math.floor(Math.random()*9999999);
var widget_so = new SWFObject("swf/tagcloud.swf?r="+rnumber, "tagcloudflash", "'.($width?$width:"100%").'", "'.($height?$height:"100%").'", "'.($size?$size:"9").'", "'.($bgcolor?$bgcolor:"#fafafa").'");
widget_so.addParam("allowScriptAccess", "always");
widget_so.addVariable("tcolor", "'.($color?$color:"0x0054a6").'");
widget_so.addVariable("tspeed", "'.($speed?$speed:"250").'");
widget_so.addVariable("distr", "true");
widget_so.addVariable("mode", "tags");
widget_so.addVariable("tagcloud", "<span>'.$tags.'</span>");
widget_so.write("'.($name?$name:"wpcumuluswidgetcontent").'");
</script></p></div>';
	return $cloud_html;
}

function linkcolor($num) {
	if (!$num)
	return "red";
	//	if ($num == 1)
	//		return "yellow";
	return "green";
}

function ratingpic($num) {
	global $tracker_lang;
	$r = round($num * 2) / 2;
	if ($r < 1 || $r > 5)
	return;
	return "<img src=\"pic/$r.gif\" border=\"0\" alt=\"".$tracker_lang['rating'].": $num / 5\" />";
}

function writecomment($userid, $comment) {
	$res = sql_query("SELECT modcomment FROM users WHERE id = '$userid'") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res);

	$modcomment = date("d-m-Y") . " - " . $comment . "" . ($arr[modcomment] != "" ? "\n" : "") . "$arr[modcomment]";
	$modcom = sqlesc($modcomment);

	return sql_query("UPDATE users SET modcomment = $modcom WHERE id = '$userid'") or sqlerr(__FILE__, __LINE__);
}

function torrenttable($res, $variant = "index") {

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
<td class="colhead" align="center"><?=$tracker_lang['type'];?></td>
<td class="colhead" align="center">Постер</td>
<td class="colhead" align="left"><?=$tracker_lang['name'];?> / <?=$tracker_lang['added'];?></td>
	<?

	if ($variant != 'bookmarks') print('<td class="colhead" align="center">Теги(жанры)</td>');

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
	<?

	if ($variant == "index" || $variant == "bookmarks")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['uploadeder']."</td>\n");

	if ((get_user_class() >= UC_MODERATOR) && $variant == "index")
	print("<td class=\"colhead\" align=\"center\">Изменен</td>");

	if ($variant == "bookmarks")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['delete']."</td>\n");

	print("</tr>\n");

	print("<tbody id=\"highlighted\">");

	foreach ($res as $row) {
		$id = $row["id"];
		print("<tr".($row["sticky"] == "yes" ? " class=\"highlight\"" : "").">\n");
		print("<td align=\"center\" style=\"padding: 0pc\">");
		if (isset($row["cat_name"]))
		print("<a href=\"browse.php?cat=" . $row["category"] . "\">".$row['cat_name']."</a>\n");
		print("</td>\n");

		print("<td align=\"center\" style=\"padding: 0px\">");

		if (isset($row["name"])) {
			print("<a href=\"details.php?id=" . $id . "\">");
			if ($row['images']) {
				$row['images'] = explode(',',$row['images']);
				$image = array_shift($row['images']);
				print("<img border=\"0\" src=\"thumbnail.php?image=" . $image . "&for=browse\" alt=\"" . $row["name"] . "\" />");
			}
			else
			print($row["name"]);
			print("</a>");
		}
		else
		print("-");
		print("</td>\n");

		$dispname = $row["name"];
		$thisisfree = ($row[free]=="yes" ? "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\"/>" : "");
		print("<td align=\"left\">".($row["sticky"] == "yes" ? "Важный: " : "")."<a class=\"browselink\" href=\"details.php?");
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

		print("<br /><i>".$row["added"]."</i> &nbsp&nbsp");
		////////////////////////////////////////////////////////////////////////////////////
		if ($variant !='bookmarks') {
			$s = "";
			if (!isset($row["rating"])) {
				if ($CACHEARRAY['minvotes'] > 1) {
					$s = sprintf($tracker_lang['not_enough_votes'], $CACHEARRAY['minvotes']);
					if ($row["numratings"])
					$s .= sprintf($tracker_lang['only_votes'], $row["numratings"]);
					else
					$s .= $tracker_lang['none_voted'];
					$s .= ")";
				}
				else
				$s .= $tracker_lang['no_votes'];
			}
			else {
				$rpic = ratingpic($row["rating"]);
				if (!isset($rpic))
				$s .= "invalid?";
				else
				$s .= "$rpic (" . $row["rating"] . " ".$tracker_lang['from']." 5)";
			}
			$s .= "\n";

			print ($tracker_lang['rating']." : ".$s);


			/////////////////////////////////////////////////////////////////////////////
			print("<td align=\"center\">".str_replace(",",",<br/>",$row['tags'])."</td>");
		}

		if ($wait)
		{
			$elapsed = floor((gmtime() - strtotime($row["added"])) / 3600);
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
			if ($row["visible"] == "no")
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
		$ttl = ($CACHEARRAY['ttl_days']*24) - floor((gmtime() - sql_timestamp_to_unix_timestamp($row["added"])) / 3600);
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
			if ($row["moderated"] == "no")
			print("<td align=\"center\"><font color=\"red\"><b>Нет</b></font></td>\n");
			else
			print("<td align=\"center\"><a href=\"userdetails.php?id=$row[moderatedby]\"><font color=\"green\"><b>Да</b></font></a></td>\n");
		}

		print("</tr>\n");

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
	$pics = $arr["donor"] == "yes" ? "<img src=\"pic/$donorpic\" alt='Donor' border=\"0\" $style>" : "";
	if ($arr["enabled"] == "yes")
	$pics .= $arr["warned"] == "yes" ? "<img src=pic/$warnedpic alt=\"Warned\" border=0 $style>" : "";
	else
	$pics .= "<img src=\"pic/$disabledpic\" alt=\"Disabled\" border=\"0\" $style>\n";
	$pics .= $arr["parked"] == "yes" ? "<img src=pic/$parkedpic alt=\"Parked\" border=\"0\" $style>" : "";
	return $pics;
}

function parked() {
	global $CURUSER;
	if ($CURUSER["parked"] == "yes")
	stderr($tracker_lang['error'], "Ваш аккаунт припаркован.");
}

function mysql_modified_rows () {
	$info_str = mysql_info();
	$a_rows = mysql_affected_rows();
	ereg("Rows matched: ([0-9]*)", $info_str, $r_matched);
	return ($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows;
}

define ("BETA", 0);
define ("BETA_NOTICE", "\n<br />This isn't complete release of source!");
define("RELVERSION","2.40");
?>
