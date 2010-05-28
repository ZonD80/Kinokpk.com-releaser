<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>{#stamps_dlg.title}</title>
<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
<script type="text/javascript" src="js/stamps.js"></script>
</head>
<body style="display: none">
<div align="center">
<div class="title">{#stamps_dlg.title}:<br />
<br />
</div>

<table border="0" cellspacing="0" cellpadding="4">
<?php
$path = str_replace("js/tiny_mce/plugins/stamps",'',dirname(__FILE__));
require_once ($path."include/bittorrent.php");
dbconn();
if (!$CURUSER) die('Only users enabled');
$stamparray = sql_query("SELECT image FROM stamps WHERE class <= ".get_user_class()." ORDER BY sort ASC");
while ((list($img) = mysql_fetch_array($stamparray))) print('<tr><td><a href="javascript:StampsDialog.insert(\''.$img.'\');"><img src="'.$CACHEARRAY['defaultbaseurl'].'/pic/stamp/'.$img.'" border="0" alt="Stamp" title="Stamp" /></a></td></tr>');

?>
</table>
</div>
</body>
</html>
