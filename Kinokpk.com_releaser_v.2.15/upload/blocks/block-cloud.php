<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}
$blocktitle = "Наши фильмы";

$content = cloud();
$content .='<br/><div align="center">[<a href="alltags.php">Большие теги</a>]</div>'

?>