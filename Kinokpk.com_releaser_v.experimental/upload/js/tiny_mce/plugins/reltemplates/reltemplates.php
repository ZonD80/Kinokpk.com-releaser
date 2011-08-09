<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{#reltemplates_dlg.title}</title>
<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
<script type="text/javascript" src="js/reltemplates.js"></script>
</head>
<body style="display: none">
<div align="center">
<div class="title">{#reltemplates_dlg.title}:<br />
<br />
</div>

<table border="0" cellspacing="0" cellpadding="4">
<?php
$path = str_replace("js/tiny_mce/plugins/reltemplates",'',dirname(__FILE__));
require_once ($path."include/bittorrent.php");
INIT();
if (!$CURUSER) die('Only users enabled');
$reltemplatearray = sql_query("SELECT id,name,content FROM reltemplates");
while ($reltemplate = mysql_fetch_assoc($reltemplatearray)) {
	$reltemplates[]=$reltemplate;
}
if (!$reltemplates) die('<tr><td>Шаблонов в данный момент нет</td></tr></table></div></body></html>');

print('<script language="javascript" type="text/javascript">
//<![CDATA[
var t = new Array();');

foreach ($reltemplates as $reltemplate) print ('t['.$reltemplate['id'].'] = "'.preg_replace("#\t|\n|\r|\x0B#si","",addslashes(format_comment($reltemplate['content'])))."\";\n");

print('
function Addtemplate(id) {
   ReltemplatesDialog.insert(t[id]);
}
//]]>
</script>');
foreach ($reltemplates as $reltemplate)
print('<tr><td><a href="javascript:Addtemplate('.$reltemplate['id'].');">'.$reltemplate['name'].'</a></td></tr>');

?>
</table>
</div>
</body>
</html>
