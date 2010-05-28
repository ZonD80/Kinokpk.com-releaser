window.onerror = null;

tooltip = {

/* НАЧАЛО НАСТРОЕК */

attr_name: "tooltip", // наименование создаваемого tooltip'ого атрибута
blank_text: "(ссылка откроется в новом окне)", // текст для ссылок с target="_blank"
newline_entity: "~", // укажите пустую строку (""), если не хотите использовать в tooltip'ах многострочность; ежели хотите, то укажите тот символ или символы, которые будут заменяться на перевод строки
max_width: 0, // максимальная ширина tooltip'а в пикселах; обнулите это значение, если ширина должна быть нелимитирована
delay: 0, // задержка при показе tooltip'а в миллисекундах

/* КОНЕЦ НАСТРОЕК */

t: document.createElement("DIV"),
c: null,
g: false,

m: function(e){
if (tooltip.g){
oCanvas = document.getElementsByTagName(
(document.compatMode && document.compatMode == "CSS1Compat") ? "HTML" : "BODY"
)[0];
x = window.event ? event.clientX + oCanvas.scrollLeft : e.pageX;
y = window.event ? event.clientY + oCanvas.scrollTop : e.pageY;
tooltip.a(x, y);
}
},

d: function(){
tooltip.t.setAttribute("id", "tooltip");
//tooltip.t.style.filter = "alpha(opacity=85)"; // buggy in ie5.0
document.body.appendChild(tooltip.t);
a = document.all ? document.all : document.getElementsByTagName("*");
aLength = a.length;
for (var i = 0; i < aLength; i++){

//if (a[i].tagName == "A" || a[i].tagName == "BUTTON" || (a[i].tagName == "INPUT" && (a[i].type == "submit" || a[i].type == "button" || a[i].type == "reset"))) a[i].onclick = self.focus;

if (!a[i]) continue;

tooltip_title = a[i].getAttribute("title"); // returns form object if IE & name="title"; then IE crashes; so...
if (tooltip_title && typeof tooltip_title != "string") tooltip_title = "";

tooltip_alt = a[i].getAttribute("alt");
tooltip_blank = a[i].getAttribute("target") && a[i].getAttribute("target") == "_blank" && tooltip.blank_text;
if (tooltip_title || tooltip_blank){
a[i].setAttribute(tooltip.attr_name, tooltip_blank ? (tooltip_title ? tooltip_title + " " + tooltip.blank_text : tooltip.blank_text) : tooltip_title);
if (a[i].getAttribute(tooltip.attr_name)){
a[i].removeAttribute("title");
if (tooltip_alt && a[i].complete) a[i].removeAttribute("alt");
tooltip.l(a[i], "mouseover", tooltip.s);
tooltip.l(a[i], "mouseout", tooltip.h);
}
}else if (tooltip_alt && a[i].complete){
a[i].setAttribute(tooltip.attr_name, tooltip_alt);
if (a[i].getAttribute(tooltip.attr_name)){
a[i].removeAttribute("alt");
tooltip.l(a[i], "mouseover", tooltip.s);
tooltip.l(a[i], "mouseout", tooltip.h);
}
}
if (!a[i].getAttribute(tooltip.attr_name) && tooltip_blank){
//
}
}
document.onmousemove = tooltip.m;
window.onscroll = tooltip.h;
tooltip.a(-99, -99);
},

s: function(e){
d = window.event ? window.event.srcElement : e.target;
if (!d.getAttribute(tooltip.attr_name)) return;
s = d.getAttribute(tooltip.attr_name);
if (tooltip.newline_entity){
s = s.replace(/\&/g,"&amp;");
s = s.replace(/\</g,"&lt;");
s = s.replace(/\>/g,"&gt;");
s = s.replace(eval("/" + tooltip.newline_entity + "/g"), "<br />");
tooltip.t.innerHTML = s;
}else{
if (tooltip.t.firstChild) tooltip.t.removeChild(tooltip.t.firstChild);
tooltip.t.appendChild(document.createTextNode(s));
//tooltip.t.innerText = s;
}
tooltip.c = setTimeout("tooltip.t.style.visibility = 'visible';", tooltip.delay);
tooltip.g = true;
},

h: function(e){
tooltip.t.style.visibility = "hidden";
if (!tooltip.newline_entity && tooltip.t.firstChild) tooltip.t.removeChild(tooltip.t.firstChild);
clearTimeout(tooltip.c);
tooltip.g = false;
tooltip.a(-99, -99);
},

l: function(o, e, a){
if (o.addEventListener) o.addEventListener(e, a, false); // was true--Opera 7b workaround!
else if (o.attachEvent) o.attachEvent("on" + e, a);
else return null;
},

a: function(x, y){
oCanvas = document.getElementsByTagName(
(document.compatMode && document.compatMode == "CSS1Compat") ? "HTML" : "BODY"
)[0];

w_width = oCanvas.clientWidth ? oCanvas.clientWidth + oCanvas.scrollLeft : window.innerWidth + window.pageXOffset;
w_height = window.innerHeight ? window.innerHeight + window.pageYOffset : oCanvas.clientHeight + oCanvas.scrollTop; // should be vice verca since Opera 7 is crazy!

tooltip.t.style.width = ((tooltip.max_width) && (tooltip.t.offsetWidth > tooltip.max_width)) ? tooltip.max_width + "px" : "auto";

t_width = tooltip.t.offsetWidth;
t_height = tooltip.t.offsetHeight;

tooltip.t.style.left = x + 6 + "px";
tooltip.t.style.top = y + 16 + "px";

if (x + t_width > w_width-8) tooltip.t.style.left = w_width - t_width + "px";
if (y + t_height > w_height-8) tooltip.t.style.top = w_height - t_height + "px";
}
}

var root = window.addEventListener || window.attachEvent ? window : document.addEventListener ? document : null;
if (root){
if (root.addEventListener) root.addEventListener("load", tooltip.d, false);
else if (root.attachEvent) root.attachEvent("onload", tooltip.d);
}