<?

function end_chmod($dir, $chm) {
	if (file_exists($dir) && intval($chm)) {
		#chmod($dir, "0".$chm."");
		$pdir = decoct(fileperms($dir));
		$per = substr($pdir, -3);
		if ($per != $chm) return "".$dir." не имеет нужных разрешений для записи на сервере.<br />Установите нужные атрибуты CHMOD - ".$chm."";
	}
}

?>