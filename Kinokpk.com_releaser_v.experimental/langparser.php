<?php
/*
parses language to a database
usage:
1. upload lauguages folder to your tracker root (3.19+ version)
2. run script langparser.php?lang={LANGUAGE}, e.g. langparser.php?lang=en
*/
require_once('include/bittorrent.php');
dbconn();
headers(true);
print '<pre>';
chdir('languages/'.$_GET['lang'].'/');
foreach (glob('*.lang') as $language) {
var_dump ($REL_LANG->import_langfile($language,$_GET['lang']));

}
?>