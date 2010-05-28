<?

require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
	stderr($tracker_lang["error"], $tracker_lang["access_denied"]);

function bark($msg) {
	stdhead();
	stdmsg("Ошибка!", $msg);
	stdfoot();
	exit;
}

$var_list = "name:poster:genre:director:actors:descr:quality:video_codec:video_size:video_kbps:audio_lang:audio_trans:audio_codec:audio_kbps:time:torrentid:cat";
$int_list = "quality:video_codec:video_kbps:audio_lang:audio_trans:audio_codec:audio_kbps:torrentid:cat";

foreach (explode(":", $var_list) as $x)
	if (empty($_POST[$x]))
		stderr($tracker_lang["error"], "Вы не заполнили все поля!");
	else
		$GLOBALS[$x] = $_POST[$x];

foreach (explode(":", $int_list) as $x)
	if (!is_valid_id($GLOBALS[$x]))
		stderr($tracker_lang["error"], "Вы ввели не число в следующее поле: $x");
$video_kbps = $_POST["video_kbps"];
$time = $_POST["time"];
$imdb = $_POST["imdb"];
$added = sqlesc(get_date_time());
sql_query("INSERT INTO indexreleases (".implode(", ", explode(":", $var_list)).($imdb ? ", imdb" : "").", added) VALUES (".implode(", ", array_map("sqlesc", array($name, $poster, $genre, $director, $actors, $descr, $quality, $video_codec, $video_size, $video_kbps, $audio_lang, $audio_trans, $audio_codec, $audio_kbps, $time, $torrentid, $cat))).($imdb ? ", ".sqlesc($imdb) : "").", $added)") or sqlerr(__FILE__, __LINE__);

header("Refresh: 0; url=index.php");

?>