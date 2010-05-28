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
		if (strlen($v) != $l)
		return;
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

		foreach ($dict['value']['announce-list']['value'] as $urls) {
			$anarray[] = $urls['value'][0]['value'];
		}

		return $anarray;

	}
}
function get_remote_peers($announce_url, $hash, $do_gzip = false, $method = 'announce') {

	if ($method == 'announce')
	$options = array(
	//'passkey' => 'look at announce url!',
         'info_hash' => urlencode(pack("H*",$hash)),
         'peer_id' => urlencode('-UT1820-5dmPcUOYGnrx'),
         'port' => 50000,
         'uploaded' => 0,
         'downloaded' => 0,
         'left' => 0,
         'numwant' => 0,
	//'event' => 'started'
	//'no_peer_id' => 1,
	//'compact' => 1
	);
	else $options = array('info_hash' => urlencode(pack("H*",$hash)));
	 

	preg_match("#://(.*?)(:[0-9]+|)(/.*?)\\z#si", $announce_url, $url);

	$host = $url[1];
	$port = $url[2];
	$get = $url[3];

	if ($method == 'scrape') $get = str_replace("announce","scrape",$get);
	 

	if (strpos($get, "?") === false) $symbol = "?"; else $symbol = "&";

	$get .= $symbol;
	foreach ( $options AS $opt_name => $opt_value ) {
		$get .= '&' . $opt_name . '=' . $opt_value;
	}

	if (!$port) $port = 80; else $port = substr($port,1,strlen($port));

	$filePointer = @fsockopen($host, $port, $errorNumber, $errorString,5);
	if (!$filePointer)
	{
		return array('tracker' => $host, 'state' => 'false');
	}

	$requestHeader = "GET " . $get . "  HTTP/1.1\r\n";
	$requestHeader.= "User-Agent: uTorrent/1820;Windows XP;\r\n";
	$requestHeader.= "Connection: close\r\n";
	$requestHeader.= ( $do_gzip ? "Accept-Encoding: gzip\r\n" : '' );
	$requestHeader.= "Host: ".$host."\r\n";
	$requestHeader.= "Accept: text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2\r\n";
	$requestHeader.= "Content-type: application/x-www-form-urlencoded\r\n\r\n";

	fwrite($filePointer, $requestHeader);

	$responseHeader = '';
	$responseContent = '';

	do {
		$responseHeader.= fread($filePointer, 1);
	}
	while ( !preg_match('/\\r\\n\\r\\n$/', $responseHeader) );

	if (!strstr($responseHeader, "Transfer-Encoding: chunked")) {
		while (!feof($filePointer)) {
			$responseContent.= fgets($filePointer, 128);
		}
	}
	else {
		while ($chunk_length = hexdec(fgets($filePointer))) {
			$responseContentChunk = '';
			$read_length = 0;
			while ($read_length < $chunk_length) {
				$responseContentChunk .= fread($filePointer, $chunk_length - $read_length);
				$read_length = strlen($responseContentChunk);
			}

			$responseContent.= $responseContentChunk;
			fgets($filePointer);
		}

		$responseContent = bdec($responseContent);

	}

	if ($method == 'scrape') {

		$responseContent = bdec($responseContent);

		if ($responseContent['value']['files']['value']) {
			$peersarray = array_shift($responseContent['value']['files']['value']);
			return array('tracker' => $host, 'seeders' => $peersarray['value']['complete']['value'], 'leechers' => $peersarray['value']['incomplete']['value'], 'state' => 'ok');
		} else return array('tracker' => $host, 'state' => 'false');
	}
	if (!is_array($responseContent)) {

		return get_remote_peers($announce_url, $hash, $do_gzip, "scrape");
		 
	} else return array('tracker' => $host, 'seeders' => $responseContent['value']['complete']['value'], 'leechers' => $responseContent['value']['incomplete']['value'], 'state' => 'ok');
}
?>