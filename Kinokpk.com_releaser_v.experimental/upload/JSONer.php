<pre>
<form method="post" action="JSONer.php">
<textarea name="t"></textarea>
<input type="submit">
</form>
<?php
//var_dump($_POST['t']);
var_dump(json_encode(iconv('cp1251','utf8',$_POST['t'])));
?>