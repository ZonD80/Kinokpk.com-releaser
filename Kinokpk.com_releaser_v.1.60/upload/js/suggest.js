function $(e){if(typeof e=='string')e=document.getElementById(e);return e};
function collect(a,f){var n=[];for(var i=0;i<a.length;i++){var v=f(a[i]);if(v!=null)n.push(v)}return n};

ajax={};
ajax.x=function(){try{return new ActiveXObject('Msxml2.XMLHTTP')}catch(e){try{return new ActiveXObject('Microsoft.XMLHTTP')}catch(e){return new XMLHttpRequest()}}};
ajax.serialize=function(f){var g=function(n){return f.getElementsByTagName(n)};var nv=function(e){if(e.name)return encodeURIComponent(e.name)+'='+encodeURIComponent(e.value);else return ''};var i=collect(g('input'),function(i){if((i.type!='radio'&&i.type!='checkbox')||i.checked)return nv(i)});var s=collect(g('select'),nv);var t=collect(g('textarea'),nv);return i.concat(s).concat(t).join('&');};
ajax.send=function(u,f,m,a){var x=ajax.x();x.open(m,u,true);x.onreadystatechange=function(){if(x.readyState==4)f(x.responseText)};if(m=='POST')x.setRequestHeader('Content-type','application/x-www-form-urlencoded');x.send(a)};
ajax.get=function(url,func){ajax.send(url,func,'GET')};
ajax.gets=function(url){var x=ajax.x();x.open('GET',url,false);x.send(null);return x.responseText};
ajax.post=function(url,func,args){ajax.send(url,func,'POST',args)};
ajax.update=function(url,elm){var e=$(elm);var f=function(r){e.innerHTML=r};ajax.get(url,f)};
ajax.submit=function(url,elm,frm){var e=$(elm);var f=function(r){e.innerHTML=r};ajax.post(url,f,ajax.serialize(frm))};

var pos = 0;
var count = 0;

function noenter(key) {
	suggcont = document.getElementById("suggcontainer");
	if (suggcont.style.display == "block") {
		if (key == 13) {
			choiceclick(document.getElementById(pos));
			return false;
		} else {
			return true;
		}
	} else {
		return true;
	}
}

document.onclick = function () { closechoices(); }

function suggest(key,query) {
	if (key == 38) {
		goPrev();
	} else if (key == 40) {
		goNext();
	} else if (key != 13) {
		if (query.length > 3) {
			t = new Date();
			ajax.get('suggest.php?q='+query+'&bla='+t.getTime(),update);
		} else {
			update('');
		}
	}
}

function update(result) {
	arr = new Array();
	arr = result.split('\r\n');

	if (arr.length > 10) {
		count = 10;
	} else {
		count = arr.length;
	}

	suggdiv = document.getElementById("suggestions");
	suggcont = document.getElementById("suggcontainer");
	if (arr[0].length > 0) {
		suggcont.style.display = "block";
		suggdiv.innerHTML = '';
		suggdiv.style.height = count * 20;
	
		for (i = 1; i <= count; i++) {
			novo = document.createElement("div");
			suggdiv.appendChild(novo);
			novo.id = i;
			novo.style.height = "14px";
			novo.style.padding = "3px";
			novo.onmouseover = function() { select(this,true); }
			novo.onmouseout = function() { unselect(this,true); }
			novo.onclick = function() { choiceclick(this); }
			novo.innerHTML = arr[i-1];
		}
	} else {
		suggcont.style.display = "none";
		count = 0;
	}
}

function select(obj,mouse) {
	obj.style.backgroundColor = '#3399ff';
	obj.style.color = '#ffffff';
	if (mouse) {
		pos = obj.id;
		unselectAllOther(pos);
	}
}

function unselect(obj,mouse) {
	obj.style.backgroundColor = '#ffffff';
	obj.style.color = '#000000';
	if (mouse) {
		pos = 0;
	}
}

function goNext() {
	if (pos <= count && count > 0) {
		if (document.getElementById(pos)) {
			unselect(document.getElementById(pos));
		}
		pos++;
		if (document.getElementById(pos)) {
			select(document.getElementById(pos));
		} else {
			pos = 0;
		}
	}
}

function goPrev() {
	if (count > 0) {
		if (document.getElementById(pos)) {
			unselect(document.getElementById(pos));
			pos--;
			if (document.getElementById(pos)) {
				select(document.getElementById(pos));
			} else {
				pos = 0;
			}
		} else {
			pos = count;
			select(document.getElementById(count));
		}
	}
}

function choiceclick(obj) {
	document.getElementById("searchinput").value = obj.innerHTML;
	count = 0;
	pos = 0;
	suggcont = document.getElementById("suggcontainer");
	suggcont.style.display = "none";
	document.getElementById("searchinput").focus();
}

function closechoices() {
	suggcont = document.getElementById("suggcontainer");
	if (suggcont.style.display == "block") {
		count = 0;
		pos = 0;
		suggcont.style.display = "none";
	}
}

function unselectAllOther(id) {
	for (i = 1; i <= count; i++) {
		if (i != id) {
			document.getElementById(i).style.backgroundColor = '#ffffff';
			document.getElementById(i).style.color = '#000000';
		}
	}
}