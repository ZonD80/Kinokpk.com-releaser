<?php
$path = str_replace("js/tiny_mce/plugins/graffiti",'',dirname(__FILE__));
require_once ($path."include/bittorrent.php");
INIT();
if (!$CURUSER) die('Only users enabled');
if(isset($_FILES['Filedata']))
{
	$fHandle			= fopen($_FILES['Filedata']['tmp_name'], "rb");
	if($fHandle)
	{
		$fData			= bin2hex(fread($fHandle, 32));
		if($fData == "89504e470d0a1a0a0000000d494844520000024a0000012508060000001b69cd")
		{
			$fImageData	= getimagesize($_FILES['Filedata']['tmp_name']);
			if($fImageData[0] == 586 && $fImageData[1] == 293)
			{
				$file_name = $CURUSER['id'].'-'.trim((string)$_GET['imgcode']).".png";

				$origImage		= imagecreatefrompng($_FILES['Filedata']['tmp_name']);
				$newImage		= imagecreatetruecolor(272, 136);
				imagecopyresized($newImage, $origImage, 0, 0, 0, 0, 272, 136, $fImageData[0], $fImageData[1]);
				imagepng($newImage, ROOT_PATH."graffities/" . $file_name);
			}
			$_SESSION['graffiti_session'] = "emptyString";
		}
	}
}
if ($_GET['imgcode']) {
	$code = htmlspecialchars(trim((string)$_GET['imgcode']));
	if (preg_match('/_done/',$code)) $done = true; else $done=false;
	$imgcode = preg_replace('/_done/','',$code);
} else
$imgcode = md5(microtime()+rand(0,100));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{#graffiti_dlg.title}</title>
<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
<script type="text/javascript" src="js/graffiti.js"></script>
<script type="text/javascript" src="/js/swfobject.js"></script>
</head>
<body>
<?php if (!$done) {?>
<div id="graffiti"
	style="text-align: center; width: 100%; margin: auto;">Graffiti
loading...</div>
<script type="text/javascript">
//<![CDATA[

   var flashvars = {
'overstretch':'false',
'postTo':'graffiti.php?imgcode=<?=$imgcode?>',
'redirectTo':'graffiti.php?imgcode=<?=$imgcode?>_done',
   };

   var params = {
      'allowfullscreen':    'true',
      'allowscriptaccess':  'always',
      'bgcolor':            '#ffffff',
      'wmode': 'opaque'
   };

   var attributes = {
      'id':                 'graffiti',
      'name':               'graffiti'
   };

   swfobject.embedSWF('swf/Graffiti.swf?15','graffiti',"600","385",'9', 'false', flashvars, params, attributes);

//]]>
</script>
<?php } else print '<script type="text/javascript">GraffitiDialog.insert(\''.$CURUSER['id'].'-'.$imgcode.'.png\');</script>'; ?>
</body>
</html>
