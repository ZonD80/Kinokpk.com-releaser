<?php

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

if(!defined("IN_ANNOUNCE") && !defined("IN_TRACKER")) die("Direct access to this page not allowed");

function dict_get($d, $k, $t) {
	if ($d["type"] != "dictionary")
	bark("not a dictionary");
	$dd = $d["value"];
	if (!isset($dd[$k]))
	return;
	$v = $dd[$k];
	if ($v["type"] != $t)
	bark("invalid dictionary entry type");
	return $v["value"];
}

function dict_check($d, $s) {
	if ($d["type"] != "dictionary")
	bark("not a dictionary");
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
		bark("dictionary is missing key(s)");
		if (isset($t)) {
			if ($dd[$k]["type"] != $t)
			bark("invalid entry in dictionary");
			$ret[] = $dd[$k]["value"];
		}
		else
		$ret[] = $dd[$k];
	}
	return $ret;
}

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
function benc_str($s) {
	return strlen($s) . ":$s";
}
function benc_int($i) {
	return "i" . $i . "e";
}
function benc_list($a) {
	$s = "l";
	foreach ($a as $e) {
		$s .= benc($e);
	}
	$s .= "e";
	return $s;
}
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
function bdec_file($f, $ms) {
	$fp = fopen($f, "rb");
	if (!$fp)
	return;
	$e = fread($fp, $ms);
	fclose($fp);
	return bdec($e);
}
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

function get_announce_urls($dict){
	if ($dict['value']['announce'] && !$dict['value']['announce-list']) {$anarray[0] = $dict['value']['announce']['value']; return $anarray; }

	if ($dict['value']['announce-list']) {

		if (!$dict['value']['announce-list']['value']) return false;
		foreach ($dict['value']['announce-list']['value'] as $urls) {
			$anarray[] = $urls['value'][0]['value'];
		}

		return $anarray;

	}
}

function put_announce_urls($dict,$anarray,$announce_url){
	global $dict;
	$liststring = '';
	unset($dict['value']['announce']);
	unset($dict['value']['announce-list']);

	$dict['value']['announce'] = bdec(benc_str($announce_url));

	$announces[0] = array('type' => 'list', 'value' => array(bdec(benc_str($announce_url))), 'strlen' => strlen("l".$announce_url."e"), 'string' => "l".$announce_url."e");
	$liststring .= "l".$announce_url."e";

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

function getUrlPort($urlInfo) {
	if( isset($urlInfo['port']) ) {
		$port = $urlInfo['port'];
	} else { // no port specified; get default port
		if (isset($urlInfo['scheme'])) {
			switch($urlInfo['scheme']) {
				case 'http':
					$port = 80; // default for http
					break;
				case 'https':
					$port = 443; // default for https
					break;
				default:
					$port = 0; // error; unsupported scheme
					break;
			}
		} else {
			$port = 0; // error; unknown scheme
		}
	}
	return $port;
}

function get_remote_peers($url, $info_hash, $method = 'scrape') {

	if ($method == "announce") {
		$get_params = array(
    			"info_hash" => pack("H*", $info_hash),
    			"peer_id" => "-UT1820-5dmPcUOYGnrx",
    			"port" => rand(10000, 65535),
    			"uploaded" => 0,
    			"downloaded" => 0,
    			"left" => 1,
    			"numwant" => 20
		);
	} else {
		$url = str_replace('announce', 'scrape', $url);
		$get_params = array(
    			"info_hash" => pack("H*", $info_hash)
		);
	}


	$urlInfo = @parse_url($url);
	$http_host = $urlInfo['host'];
	$http_port = getUrlPort($urlInfo);

	if ($http_port === 0)
	return array('tracker' => $http_host, 'state' => 'false');
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

	$opts = array('http' =>
	array(
        'method' => 'GET',
    	'header' => 'User-Agent: uTorrent/1820'
    	//'Accept: text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2',
	)
	);

	$context = @stream_context_create($opts);
	$result = @file_get_contents('http://'.$http_host.$http_port.$http_path.($http_params ? '?'.$http_params : ''), false, $context);

	if (!$result)
	{
		return array('tracker' => $http_host, 'state' => 'false');
	}


	//var_dump($method);
	$result = @bdec($result);

	if (!is_array($result)) return array('tracker' => $http_host, 'state' => 'false');

	//    print('<pre>'); var_dump($result);
	if ($method == 'scrape') {

		if ($result['value']['files']['value']) {
			$peersarray = array_shift($result['value']['files']['value']);
			return array('tracker' => $http_host, 'seeders' => $peersarray['value']['complete']['value'], 'leechers' => $peersarray['value']['incomplete']['value'], 'state' => 'ok');
		} else return get_remote_peers($announce_url, $info_hash, $do_gzip, "announce");
	}

	if($method == 'announce') {
		return array('tracker' => $http_host, 'seeders' => count($result['value']['peers']['value']), 'leechers' => 0);
	}

}


?>