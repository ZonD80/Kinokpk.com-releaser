<pre>
<?php

require_once('include/bittorrent.php');

INIT();

$cats = $REL_DB->query_return("SELECT id,image, name FROM categories");

foreach ($cats AS $c) {
	$array[] = "<a href=\"browse.php?cat={$c['id']}\"><img src=\"pic/cats/{$c['image']}\" title=\"{$c['name']}\"/></a>";
}

print implode("\n",$array);
?>
</pre>