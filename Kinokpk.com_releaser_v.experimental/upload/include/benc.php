<?php
/**
 * BitTorrent encoding/decoding functions, multitracker/retracker functionality functions
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if(!defined("IN_ANNOUNCE") && !defined("IN_TRACKER")) die("Direct access to this page not allowed");

/**
 * Converts String to Hex
 * @param string $string String to be converted
 * @return string Converted string
 */
function hex($string){
	$hex='';
	for ($i=0; $i < strlen($string); $i++){
		$hex .= dechex(ord($string[$i]));
	}
	return $hex;
}

/**
 * Makes magnet link e.g. instead of downloading .torrent
 * @param string $info_hash SHA1 infohash
 * @param string $filename Name to display
 * @param string|array $trackers Announce url or array of announce-urls of trackers
 * @return string
 */
function make_magnet($info_hash,$filename,$trackers){
	if (is_array($trackers)) $trackers = implode('&tr=',array_map('urlencode',$trackers)); else $trackers = urlencode($trackers);
	return 'magnet:?xt=urn:btih:'.$info_hash.'&dn='.urlencode($filename).'&tr='.$trackers;

}

/**
 * Makes magnet link to a DChub
 * @param string $tiger_hash TIGER infohash
 * @param string $filename Name to display
 * @param int $filesize Filesize in bytes
 * @param string|array $trackers Announce url or array of announce-urls of trackers
 * @return string
 */
function make_dc_magnet($tiger_hash,$filename,$filesize,$hubs){
	if (is_array($hubs)) $hubs = implode('&xs=',array_map('urlencode',$hubs)); else $hubs = urlencode($hubs);
	return 'magnet:?xt=urn:tree:tiger:'.$tiger_hash.'&xl='.$filesize.'&dn='.urlencode($filename).'&xs='.$hubs;

}


/**
 * Gets available retrackers (DChubs, and other stuff) by user's ip
 * @param boolean $all Get All Retrackers, ignoring subnet mask default false
 * @param string $table Table used to get stuff
 * @return array Array of retrackers or empty array if no retrackers present
 */
function get_retrackers($all = false, $table = 'retrackers') {
	global $IPCHECK;
	$ip = getip();
	$res = sql_query("SELECT announce_url, mask FROM $table ORDER BY sort ASC");
	while ($row = mysql_fetch_assoc($res)) {
		$masks = explode(',',$row['mask']);
		foreach ($masks as $mask)
		$rtarray[] = array('announce_url'=>$row['announce_url'],'mask'=>$mask);
		if ($all) $return[] = $row['announce_url'];
	}

	if (!$rtarray) return array();

	if ($all) return $return;

	foreach ($rtarray as $retracker) {

		if (!empty($retracker['mask'])) {
			$RTCHECK = new IPAddressSubnetSniffer(array($retracker['mask']));

			if ($RTCHECK->ip_is_allowed($ip)) $retrackers[] = $retracker['announce_url'];
		}
		else $retrackers[] = $retracker['announce_url'];
	}

	if ($retrackers) return $retrackers; else return array();
}

/**
 * Gets dictionary value
 * @param array $d torrent dictionary
 * @param string $k dictionary key
 * @param string $t value type
 * @return void|multiple Return value
 * @see bdec_dict()
 */
function dict_get($d, $k, $t) {
	if ($d["type"] != "dictionary")
	print("not a dictionary");
	$dd = $d["value"];
	if (!isset($dd[$k]))
	return;
	$v = $dd[$k];
	if ($v["type"] != $t)
	print("invalid dictionary entry type");
	return $v["value"];
}

/**
 * Check that dicitionary is valid
 * @param array $d Dictionary
 * @param string $s Undocumented
 * @return Ambigous <multitype:, unknown> Undocumented
 */
function dict_check($d, $s) {
	if ($d["type"] != "dictionary")
	print("not a dictionary");
	$a = explode(":", $s);
	$dd = $d["value"];
	$ret = array();
	foreach ($a as $k) {
		unset($t);
		if (preg_match('/^(.*)\((.*)\)$/', $k, $m)) {
			$k = $m[1];
			$t = $m[2];
		}
		if (!isset($dd[$k]))
		print("dictionary is missing key(s)");
		if (isset($t)) {
			if ($dd[$k]["type"] != $t)
			print("invalid entry in dictionary");
			$ret[] = $dd[$k]["value"];
		}
		else
		$ret[] = $dd[$k];
	}
	return $ret;
}

/**
 * Binary encodes an value
 * @param mixed $obj Value to be encoded
 * @return string Encoded value
 * @see benc_str()
 * @see benc_int()
 * @see benc_list()
 * @see benc_dict()
 */
function benc($obj) {
	if (!is_array($obj) || !isset($obj["type"]) || !isset($obj["value"]))
	return;
	$c = $obj["value"];
	switch ($obj["type"]) {
		case "string":
			return benc_str($c);
		case "integer":
			return benc_int($c);
		case "list":
			return benc_list($c);
		case "dictionary":
			return benc_dict($c);
		default:
			return;
	}
}
/**
 * Binary encodes a string
 * @param string $s String to be encoded
 * @return string Encoded string
 */
function benc_str($s) {
	return strlen($s) . ":$s";
}
/**
 * Binary encodes an integer
 * @param int $i Integer to be encoded
 * @return string Encoded Integer
 */
function benc_int($i) {
	return "i" . $i . "e";
}
/**
 * Binary encodes a list
 * @param array $a List to be encoded
 * @return string Encoded list
 */
function benc_list($a) {
	$s = "l";
	foreach ($a as $e) {
		$s .= benc($e);
	}
	$s .= "e";
	return $s;
}

/**
 * Binary encodes a dictionary
 * @param array $d Dictionary to be encoded
 * @return string Encoded dictionary
 * @see benc() benc_str()
 */
function benc_dict($d) {
	$s = "d";
	$keys = array_keys($d);
	sort($keys);
	foreach ($keys as $k) {
		$v = $d[$k];
		$s .= benc_str($k);
		$s .= benc($v);
	}
	$s .= "e";
	return $s;
}
/**
 * Binary decodes a torrent file
 * @param string $f File path to be decoded
 * @return array Decoded file
 * @see bdec()
 */
function bdec_file($f) {
	$f = file_get_contents($f);
	if (!$f)
	return;
	return bdec($f);
}
/**
 * Binary decodes a Value
 * @param string $s Value to be decoded
 * @return array Decoded value
 */
function bdec($s) {
	if (preg_match('/^(\d+):/', $s, $m)) {
		$l = $m[1];
		$pl = strlen($l) + 1;
		$v = substr($s, $pl, $l);
		$ss = substr($s, 0, $pl + $l);
		if (strlen($v) != $l) return;
		return array('type' => "string", 'value' => $v, 'strlen' => strlen($ss), 'string' => $ss);
	}
	if (preg_match('/^i(\d+)e/', $s, $m)) {
		$v = $m[1];
		$ss = "i" . $v . "e";
		if ($v === "-0")
		return;
		if ($v[0] == "0" && strlen($v) != 1)
		return;
		return array('type' => "integer", 'value' => $v, 'strlen' => strlen($ss), 'string' => $ss);
	}
	switch ($s[0]) {
		case "l":
			return bdec_list($s);
		case "d":
			return bdec_dict($s);
		default:
			return;
	}
}
/**
 * Binary decodes a list
 * @param string $s List to be decoded
 * @return array Decoded list
 */
function bdec_list($s) {
	if ($s[0] != "l")
	return;
	$sl = strlen($s);
	$i = 1;
	$v = array();
	$ss = "l";
	for (;;) {
		if ($i >= $sl)
		return;
		if ($s[$i] == "e")
		break;
		$ret = bdec(substr($s, $i));
		if (!isset($ret) || !is_array($ret))
		return;
		$v[] = $ret;
		$i += $ret["strlen"];
		$ss .= $ret["string"];
	}
	$ss .= "e";
	return array('type' => "list", 'value' => $v, 'strlen' => strlen($ss), 'string' => $ss);
}
/**
 * Binary decodes a dictionary
 * @param string $s Dictionary to be decoded
 * @return array Decoded dictionary
 */
function bdec_dict($s) {
	if ($s[0] != "d")
	return;
	$sl = strlen($s);
	$i = 1;
	$v = array();
	$ss = "d";
	for (;;) {
		if ($i >= $sl)
		return;
		if ($s[$i] == "e")
		break;
		$ret = bdec(substr($s, $i));
		if (!isset($ret) || !is_array($ret) || $ret["type"] != "string")
		return;
		$k = $ret["value"];
		$i += $ret["strlen"];
		$ss .= $ret["string"];
		if ($i >= $sl)
		return;
		$ret = bdec(substr($s, $i));
		if (!isset($ret) || !is_array($ret))
		return;
		$v[$k] = $ret;
		$i += $ret["strlen"];
		$ss .= $ret["string"];
	}
	$ss .= "e";
	return array('type' => "dictionary", 'value' => $v, 'strlen' => strlen($ss), 'string' => $ss);
}

/**
 * Gets announce urls from DECODED torrent dicrionary
 * @param array $dict Decoded torrent dictionary
 * @return array|boolean Array of urls on success, false on fail
 */
function get_announce_urls($dict){
	if ($dict['value']['announce'] && !$dict['value']['announce-list']) {$anarray[0] = $dict['value']['announce']['value']; return $anarray; }

	if ($dict['value']['announce-list']) {

		if (!$dict['value']['announce-list']['value']) return false;
		foreach ($dict['value']['announce-list']['value'] as $urls) {
			// Remove retrackers
			if (!preg_match('/retracker/', $urls['value'][0]['value']))
			$anarray[] = $urls['value'][0]['value'];
		}

		return $anarray;

	}
}

/**
 * Puts announce urls into DECODED dictionary. DICT is global.
 * @param array $dict Decoded dictionary to be processed
 * @param array $anarray Array of announce urls. First element good to be a local announce-url
 * @return void Uses global $dict
 */
function put_announce_urls($dict,$anarray){
	global $dict;
	$liststring = '';
	unset($dict['value']['announce']);
	unset($dict['value']['announce-list']);
	$dict['value']['announce'] = bdec(benc_str($anarray[0]));


	if (is_array($anarray))
	foreach ($anarray as $announce) {
		$announces[] = array('type' => 'list', 'value' => array(bdec(benc_str($announce))), 'strlen' => strlen("l".$announce."e"), 'string' => "l".$announce."e");
		$liststring .= "l".$announce."e";
	}
	$dict['value']['announce-list']['type'] = 'list';
	$dict['value']['announce-list']['value'] = $announces;


	$dict['value']['announce-list']['string'] = "l".$liststring."e";
	$dict['value']['announce-list']['strlen'] = strlen($dict['value']['announce-list']['string']);

}

/**
 * Gets port from adress
 * @param string $urlInfo URL to be parsed
 * @return int Port
 */
function getUrlPort($urlInfo) {
	if( isset($urlInfo['port']) ) {
		$port = $urlInfo['port'];
	} else { // no port specified; get default port
		if (isset($urlInfo['scheme'])) {
			switch($urlInfo['scheme']) {
				case 'http':
					$port = 80; // default for http
					break;
				case 'udp': //default for udp
					$port = 80;
					break;
				case 'https':
					$port = 443; // default for https
					break;

				default:
					$port = 0; // error; unsupported scheme
					break;
			}
		} else {
			$port = 80; // error; unknown scheme, using default 80 port
		}
	}
	return $port;
}

/**
 * Checks that tracker returns failed event.
 * @param string $result Bencoded result to be parsed
 * @return string String to be used in remote tracker statistics
 */
function check_fail($result) {
	if ($result['value']['failure reason']['value']) return 'failed:'.$result['value']['failure reason']['value']; else return 'ok';
}

/**
 * Gets amout of remote tracker peers. May be recursivity.
 * @param string $url Announce url of request
 * @param string $info_hash Info-hash of torrent to be parsed
 * @param string $method Method of gathering amount of peers. May be scrape or announce. Default 'scrape'. If scrape fails, recursivety swithes to announce and executes again.
 * @return array Result array ('tracker','seeders','leechers','state');
 */
function get_remote_peers($url, $info_hash, $method = 'scrape') {

	if ($method == "announce") {
		$get_params = array(
    			"info_hash" => pack("H*", $info_hash),
    			"peer_id" => "-UT1820-5dmPcUOYGnrx",
    			"port" => rand(10000, 65535),
    			"uploaded" => 0,
				"no_peer_id" => 1,
    			"downloaded" => 0,
				"compact" => 1,
    			"left" => 1,
    			"numwant" => 9999
		);
	} else {
		$urlorig=$url;
		$url = str_replace('announce', 'scrape', $url);
		$get_params = array(
    			"info_hash" => pack("H*", $info_hash)
		);
	}


	$urlInfo = @parse_url($url);
	$http_host = $urlInfo['host'];
	$http_port = getUrlPort($urlInfo);
	$scheme = $urlInfo['scheme'];

	if ($http_port === 0)
	return array('tracker' => $http_host, 'state' => 'failed:no_port_detected', 'method' => $method, 'remote_method' => 'N/A');
	else
	$http_port = ':' . $http_port;

	$http_path = $urlInfo['path'];
	$get_request_params = explode('&', $urlInfo['query']);

	foreach (array_filter($get_request_params) as $array_value) {
		list($key, $value) = explode('=', $array_value);
		$new_get_request_params[$key] = $value;
	}

	if (!$new_get_request_params) $new_get_request_params=array();
	// Params gathering complete

	// Creating params
	$http_params = @http_build_query(@array_merge($new_get_request_params, $get_params));
	$opts = array($scheme =>
	array(
        'method' => 'GET',
    	'header' => 'User-Agent: uTorrent/1820',
    	'timeout' => 10
	//'Accept: text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2',
	)
	);
	$req_uri = $scheme.'://'.$http_host.$http_port.$http_path.($http_params ? '?'.$http_params : '');

	if ( function_exists('file_get_contents') && ini_get('allow_url_fopen') == 1 ) {
		$context = @stream_context_create($opts);
		$result = @file_get_contents($req_uri , false, $context);
		$remote_method = 'file';
	}
	elseif ( function_exists('curl_init') ) {
		if ($ch = @curl_init()) {
			@curl_setopt($ch, CURLOPT_URL, $req_uri);
			@curl_setopt($ch, CURLOPT_PORT, $http_port);
			@curl_setopt($ch, CURLOPT_HEADER, false);
			@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			@curl_setopt($ch, CURLOPT_BUFFERSIZE, 1000);
			@curl_setopt($ch, CURLOPT_USERAGENT, 'uTorrent/1820');

			$result = @curl_exec($ch);
			@curl_close($ch);
		}
		$remote_method = 'curl';
	} 	elseif ( function_exists('fsockopen') ){
		if ($fp = fsockopen($http_host, preg_replace("#[\D]#i", "", $http_port), $errno, $errstr, 10)) {
			$h  = "GET ".$http_path.($http_params ? '?'.$http_params : '')." HTTP/1.0\r\n";
			$h .= "Host: {$http_host}\r\n";
			$h .= "Connection: close\r\n";
			$h .= "User-Agent: uTorrent/1820\r\n\r\n";
			fputs($fp, $h);
			$buff = '';
			while (!feof($fp)) {
				$buff .= fgets($fp, 128);
			}
			fclose($fp);
			if ($buff) {
				$data = explode("\r\n\r\n", $buff);
				$result = $data[1];
			}
		}
		$remote_method = 'socket';
	}
	if (!$result)
	{
		if ($method=='scrape')
		return get_remote_peers($urlorig, $info_hash, "announce"); else
		return array('tracker' => $http_host, 'state' => 'failed:no_benc_result_or_timeout', 'method' => $method, 'remote_method' => $remote_method);

	}


	//var_dump($method);
	$resulttemp=$result;
	$result = @bdec($result);

	if (!is_array($result)) return array('tracker' => $http_host, 'state' => 'failed:unable_to_bdec:'.$resulttemp, 'method' => $method, 'remote_method' => $remote_method);
	unset($resulttemp);
	//    print('<pre>'); var_dump($result);
	if ($method == 'scrape') {

		if ($result['value']['files']['value']) {
			$peersarray = @array_shift($result['value']['files']['value']);
			return array('tracker' => $http_host, 'seeders' => $peersarray['value']['complete']['value'], 'leechers' => $peersarray['value']['incomplete']['value'], 'state' => check_fail($result), 'method' => $method, 'remote_method' => $remote_method);
		} else return get_remote_peers($urlorig, $info_hash, "announce");
	}

	if($method == 'announce') {
		return array('tracker' => $http_host, 'seeders' => (is_array($result['value']['peers']['value'])?count($result['value']['peers']['value']):(strlen($result['value']['peers']['value'])/6)), 'leechers' => 0, 'state'=> check_fail($result), 'method' => $method, 'remote_method' => $remote_method);
	}

}


?>