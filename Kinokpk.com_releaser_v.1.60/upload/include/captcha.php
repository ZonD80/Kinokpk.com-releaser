<?

# IMPORTANT: Do not edit below unless you know what you are doing!
if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

function create_captcha() {
	$randomstr = mksecret(5);
	$imagehash = md5($randomstr);
	sql_query("INSERT INTO captcha SET imagehash = ".sqlesc($imagehash).", imagestring = ".sqlesc($randomstr).", dateline = ".sqlesc(time())) or sqlerr(__FILE__,__LINE__);
	return $imagehash;
}

function my_strlen($string) {
	$string = preg_replace("#&\#(0-9]+);#", "-", $string);
	if(function_exists("mb_strlen")) {
		$string_length = mb_strlen($string);
	} else {
		$string_length = strlen($string);
	}

	return $string_length;
}

function get_extension($file) {
	return strtolower(my_substr(strrchr($file, "."), 1));
}

function my_substr($string, $start, $length="") {
	if(function_exists("mb_substr")) {
		if($length != "") {
			$cut_string = mb_substr($string, $start, $length);
		} else {
			$cut_string = mb_substr($string, $start);
		}
	} else {
		if($length != "") {
			$cut_string = substr($string, $start, $length);
		} else {
			$cut_string = substr($string, $start);
		}
	}

	return $cut_string;
}

?>