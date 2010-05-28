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

var SelField = document.post.comment;
var TxtFeld  = document.post.comment;

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
		var Field = eval("document.post."+which);
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
		AddSelectedText('[b]' + info + '[/b]', ', ');
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
		} else if (TxtFeld.selectionEnd && (TxtFeld.selectionEnd - TxtFeld.selectionStart > 0)) {
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
var rus_lr2 = ('Å-å-Î-î-¨-¨-¨-¨-Æ-Æ-×-×-Ø-Ø-Ù-Ù-Ú-Ü-Ý-Ý-Þ-Þ-ß-ß-ß-ß-¸-¸-æ-÷-ø-ù-ý-þ-ÿ-ÿ').split('-');
var lat_lr2 = ('/E-/e-/O-/o-ÛO-Ûo-ÉO-Éo-ÇH-Çh-ÖH-Öh-ÑH-Ñh-ØH-Øh-ú'+String.fromCharCode(35)+'-ü'+String.fromCharCode(39)+'-ÉE-Ée-ÉU-Éu-ÉA-Éa-ÛA-Ûa-ûo-éo-çh-öh-ñh-øh-ée-éu-éa-ûa').split('-');
var rus_lr1 = ('À-Á-Â-Ã-Ä-Å-Ç-È-É-Ê-Ë-Ì-Í-Î-Ï-Ð-Ñ-Ò-Ó-Ô-Õ-Õ-Ö-Ù-Û-ß-à-á-â-ã-ä-å-ç-è-é-ê-ë-ì-í-î-ï-ð-ñ-ò-ó-ô-õ-õ-ö-ù-ú-û-ü-ÿ').split('-');
var lat_lr1 = ('A-B-V-G-D-E-Z-I-J-K-L-M-N-O-P-R-S-T-U-F-H-X-C-W-Y-Q-a-b-v-g-d-e-z-i-j-k-l-m-n-o-p-r-s-t-u-f-h-x-c-w-'+String.fromCharCode(35)+'-y-'+String.fromCharCode(39)+'-q').split('-');
var rus_rl = ('À-Á-Â-Ã-Ä-Å-¨-Æ-Ç-È-É-Ê-Ë-Ì-Í-Î-Ï-Ð-Ñ-Ò-Ó-Ô-Õ-Ö-×-Ø-Ù-Ú-Û-Ü-Ý-Þ-ß-à-á-â-ã-ä-å-¸-æ-ç-è-é-ê-ë-ì-í-î-ï-ð-ñ-ò-ó-ô-õ-ö-÷-ø-ù-ú-û-ü-ý-þ-ÿ').split('-');
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
	if (txt=="<script")transScriptPause=true;else if(txt=="</script>")transScriptPause=false;
	if (txt=="<style")transStylePause=true;else if(txt=="</style>")transStylePause=false;
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