<?php
if (!defined('BLOCK_FILE')) {
Header("Location: ../index.php");
exit;
}

global  $REL_LANG, $REL_CACHE, $CURUSER;

?>
<script language=javascript>
function SmileIT(smile,form,text){
   document.forms[form].elements[text].value = document.forms[form].elements[text].value+" "+smile+" ";
   document.forms[form].elements[text].focus();
}
function winop() {
   windop = window.open("moresmiles.php?form=shbox&text=shbox_text","mywin","height=500,width=450,resizable=no,scrollbars=yes");
}
</script>

<script LANGUAGE="JavaScript"><!--
function mySubmit() {
   setTimeout('document.shbox.reset()',10);
}
//--></SCRIPT>
<?

$content .= "<br><br><iframe style='border:1px;background: #ffffff url(http://www.torrentsbook.com/pic/chat.jpg) top right no-repeat;' cellspacing='0' cellpadding='0' height='280' width='100%' src='shoutbox.php' align='center' frameborder='1' name='sbox' marginwidth='0' marginheight='0'></iframe>";

$content .= "<table width='100%' border='2' cellspacing='0' cellpadding='0'><td class=text>";
$content .= "<form action='shoutbox.php' method='get' target='sbox' name='shbox' onSubmit=\"mySubmit()\">";
$content .= "<center><a href=\"javascript: SmileIT('<img src=pic/smile/1.gif >','shbox','shbox_text')\"><img src=pic/smile/1.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/2.gif >','shbox','shbox_text')\"><img src=pic/smile/2.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/3.gif >','shbox','shbox_text')\"><img src=pic/smile/3.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/4.gif >','shbox','shbox_text')\"><img src=pic/smile/4.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/5.gif >','shbox','shbox_text')\"><img src=pic/smile/5.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/6.gif >','shbox','shbox_text')\"><img src=pic/smile/6.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/7.gif >','shbox','shbox_text')\"><img src=pic/smile/7.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/8.gif >','shbox','shbox_text')\"><img src=pic/smile/8.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/9.gif >','shbox','shbox_text')\"><img src=pic/smile/9.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/10.gif >','shbox','shbox_text')\"><img src=pic/smile/10.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/11.gif >','shbox','shbox_text')\"><img src=pic/smile/11.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/12.gif >','shbox','shbox_text')\"><img src=pic/smile/12.gif   border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/13.gif >','shbox','shbox_text')\"><img src=pic/smile/13.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/14.gif >','shbox','shbox_text')\"><img src=pic/smile/14.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/15.gif >','shbox','shbox_text')\"><img src=pic/smile/15.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/16.gif >','shbox','shbox_text')\"><img src=pic/smile/16.gif  border=0></a>";

$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/17.gif >','shbox','shbox_text')\"><img src=pic/smile/17.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/18.gif >','shbox','shbox_text')\"><img src=pic/smile/18.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/19.gif >','shbox','shbox_text')\"><img src=pic/smile/19.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/20.gif >','shbox','shbox_text')\"><img src=pic/smile/20.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/21.gif >','shbox','shbox_text')\"><img src=pic/smile/21.gif border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/22.gif >','shbox','shbox_text')\"><img src=pic/smile/22.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/23.gif >','shbox','shbox_text')\"><img src=pic/smile/23.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/24.gif >','shbox','shbox_text')\"><img src=pic/smile/24.gif   border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/25.gif >','shbox','shbox_text')\"><img src=pic/smile/25.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/26.gif >','shbox','shbox_text')\"><img src=pic/smile/26.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/27.gif >','shbox','shbox_text')\"><img src=pic/smile/27.gif  border=0></a>";
$content .= "<a href=\"javascript: SmileIT('<img src=pic/smile/28.gif >','shbox','shbox_text')\"><img src=pic/smile/28.gif  border=0></center></a>";





$content .= "<p align=\"center\">".$REL_LANG->say_by_key('your_message')."<input type='text' class='input shadowFormss ac_input' name='shbox_text' size='80'>";
$content .= "<input type='hidden' name='sent' value='yes'>";
$content .= "<input  class='button' type='submit' value='".$REL_LANG->say_by_key('submit')."' style='margin-left:5px;'><input type='hidden' name='sent' value='yes'> &nbsp; <a href='shoutbox.php' target='sbox'><input  class='button' type='submit' value='".$REL_LANG->say_by_key('upd')." ' style='margin-left:5px;'></a></center>";


if($CURUSER){
}
$content .= "</td></table></form>";

?>