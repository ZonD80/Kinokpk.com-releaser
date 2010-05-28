function _get_obj_toppos(obj)
{
var top = obj.offsetTop;
while( (obj = obj.offsetParent) != null )
{
top += obj.offsetTop;
}
return top;
}
function center_div()
{
this.divname = '';
this.divobj  = '';
}
center_div.prototype.clear_div = function()
{
try
{
if ( ! this.divobj )
{
return;
}
else
{
this.divobj.style.display  = 'none';
}
}
catch(e)
{
return;
}
}
center_div.prototype.Ywindow = function()
{
var scrollY = 0;
if ( document.documentElement && document.documentElement.scrollTop )
{
scrollY = document.documentElement.scrollTop;
}
else if ( document.body && document.body.scrollTop )
{
scrollY = document.body.scrollTop;
}
else if ( window.pageYOffset )
{
scrollY = window.pageYOffset;
}
else if ( window.scrollY )
{
scrollY = window.scrollY;
}
return scrollY;
}
center_div.prototype.move_div = function()
{
try
{
this.divobj = document.getElementById( this.divname );
}
catch(e)
{
return;
}
// Figure width and height
var my_width  = 0;
var my_height = 0;
if ( typeof( window.innerWidth ) == 'number' )
{
my_width  = window.innerWidth;
my_height = window.innerHeight;
}
else if ( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
{
my_width  = document.documentElement.clientWidth;
my_height = document.documentElement.clientHeight;
}
else if ( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
{
my_width  = document.body.clientWidth;
my_height = document.body.clientHeight;
}
this.divobj.style.position = 'absolute';
this.divobj.style.display  = 'block';
this.divobj.style.zIndex   = 99;
var divheight = parseInt( this.divobj.style.Height );
var divwidth  = parseInt( this.divobj.style.Width );
divheight = divheight ? divheight : 50;
divwidth  = divwidth  ? divwidth  : 200;
var scrolly = this.Ywindow();
var setX = ( my_width  - divwidth  ) / 2;
var setY = ( my_height - divheight ) / 2 + scrolly;
setX = ( setX < 0 ) ? 0 : setX;
setY = ( setY < 0 ) ? 0 : setY;
this.divobj.style.left = setX + "px";
this.divobj.style.top  = setY + "px";
}
function tbdev_ajax(file){
this.AjaxFailedAlert = "Ваш браузер не поддерживает расширенные возможности управления сайтом, мы настоятельно рекомендуем сменить браузер.\n";
this.requestFile = file;
this.method = "POST";
this.URLString = "";
this.encodeURIString = true;
this.execute = false;
this.loading_fired		= 0;
this.centerdiv          = null;
this.onLoading = function() { };
this.onLoaded = function() { };
this.onInteractive = function() { };
this.onCompletion = function() { };
this.onShow = function( message )
{
if ( ! this.loading_fired )
{
this.loading_fired = 1;
// Change text?
if ( message )
{
document.getElementById( 'loading-layer-text' ).innerHTML = message;
}
this.centerdiv         = new center_div();
this.centerdiv.divname = 'loading-layer';
this.centerdiv.move_div();
}
return;
};
this.onHide = function()
{
try
{
if ( this.centerdiv && this.centerdiv.divobj )
{
this.centerdiv.clear_div();
}
}
catch(e)
{
}
this.loading_fired = 0;
return;
};
this.createAJAX = function() {
try {
this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
try {
this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
} catch (err) {
this.xmlhttp = null;
}
}
if(!this.xmlhttp && typeof XMLHttpRequest != "undefined")
this.xmlhttp = new XMLHttpRequest();
if (!this.xmlhttp){
this.failed = true;
}
};
this.setVar = function(name, value){
if (this.URLString.length < 3){
this.URLString = name + "=" + value;
} else {
this.URLString += "&" + name + "=" + value;
}
}
this.encVar = function(name, value){
var varString = encodeURIComponent(name) + "=" + encodeURIComponent(value);
return varString;
}
this.encodeURLString = function(string){
varArray = string.split('&');
for (i = 0; i < varArray.length; i++){
urlVars = varArray[i].split('=');
if (urlVars[0].indexOf('amp;') != -1){
urlVars[0] = urlVars[0].substring(4);
}
varArray[i] = this.encVar(urlVars[0],urlVars[1]);
}
return varArray.join('&');
}
this.encodeVAR = function(url){
url = url.toString();
var regcheck = url.match(/[\x90-\xFF]/g);
if ( regcheck )
{
for (var i = 0; i < i.length; i++)
{
url = url.replace(regcheck[i], '%u00' + (regcheck[i].charCodeAt(0) & 0xFF).toString(16).toUpperCase());
}
}
return escape(url).replace(/\+/g, "%2B");
}
this.runResponse = function(){
eval(this.response);
}
this.sendAJAX = function(urlstring){
this.responseStatus = new Array(2);
if(this.failed && this.AjaxFailedAlert){
alert(this.AjaxFailedAlert);
} else {
if (urlstring){
if (this.URLString.length){
this.URLString = this.URLString + "&" + urlstring;
} else {
this.URLString = urlstring;
}
}
if (this.encodeURIString){
var timeval = new Date().getTime();
this.URLString = this.encodeURLString(this.URLString);
//this.setVar("rndval", timeval);
}
if (this.element) { this.elementObj = document.getElementById(this.element); }
if (this.xmlhttp) {
var self = this;
if (this.method == "GET") {
var totalurlstring = this.requestFile + "?" + this.URLString;
this.xmlhttp.open(this.method, totalurlstring, true);
} else {
this.xmlhttp.open(this.method, this.requestFile, true);
}
if (this.method == "POST"){
try {
this.xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded')
} catch (e) {}
}
this.xmlhttp.send(this.URLString);
this.xmlhttp.onreadystatechange = function() {
switch (self.xmlhttp.readyState){
case 1:
self.onLoading();
break;
case 2:
self.onLoaded();
break;
case 3:
self.onInteractive();
break;
case 4:
self.response = self.xmlhttp.responseText;
self.responseXML = self.xmlhttp.responseXML;
self.responseStatus[0] = self.xmlhttp.status;
self.responseStatus[1] = self.xmlhttp.statusText;
self.onCompletion();
if(self.execute){ self.runResponse(); }
if (self.elementObj) {
var elemNodeName = self.elementObj.nodeName;
elemNodeName.toLowerCase();
self.onHide();
if (elemNodeName == "input" || elemNodeName == "select" || elemNodeName == "option" || elemNodeName == "textarea"){
if (self.response == 'error') { alert('Доступ отклонен'); } else {
self.elementObj.value = self.response;
}
} else {
if (self.response == 'error') { alert('Доступ отклонен'); } else {
self.elementObj.innerHTML = self.response;
}
}
}
self.URLString = "";
break;
}
};
}
}
};
this.createAJAX();
}
function show_tags(category){
var ajax = new tbdev_ajax();
ajax.onShow ('');
var varsString = "";
ajax.requestFile = "showtags.php";
ajax.setVar("cat", category);
ajax.method = 'POST';
ajax.element = 'tags';
ajax.sendAJAX(varsString);
}
