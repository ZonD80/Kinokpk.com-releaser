<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/


if (!defined("IN_TRACKER")) die('Direct access to this script not allowed');
function bark($text = ""){

	stderr("", $text);

}

function get_imagecodes($file){
	$pattern = '/<img.*? src=[\'"]?([^\'" >]+)[\'" >]/';
	preg_match_all($pattern, $file, $matches);

	return $matches[0];
}

function searchfields( $s )
{
	return preg_replace( array( "/[^a-z0-9]/si", "/^\\s*/s", "/\\s*\$/s", "/\\s+/s" ), array( " ", "", "", " " ), $s );
}

function uploadtorrent( $torrentid, $category = "1", $owner = "0")
{
	global $cookie;
	$opts = array(
		'http'=>array(
		'method'=>"GET",
		'header'=>"Accept-language: ru\r\n" //.
	//"Cookie: ".$cookie."\r\n"
	)
	);
	$context = stream_context_create($opts);

	$torrent = file_get_contents( "http://rutor.org/torrent/".$torrentid."/", FALSE, $context );
	$x = preg_match_all( "/.*<h1>(.*)<\\/h1>.*/isU", $torrent, $matches );
	$x = preg_match_all( iconv( "windows-1251", "UTF-8", "/.*<.*><td class='header'>Раздают<\\/td><.*>(.*)<\\/td><\\/tr>.*/isU"), $torrent, $matchesS );
	$x = preg_match_all(iconv( "windows-1251", "UTF-8",  "/.*<.*><td class='header'>Качают<\\/td><.*>(.*)<\\/td><\\/tr>.*/isU"), $torrent, $matchesL );
	$x = preg_match_all( iconv( "windows-1251", "UTF-8", "/.*<.*>Жанр: <.*\\>(.*)<\\/a>.*/isU"), $torrent, $matches5 );
	$x = preg_match_all(iconv( "windows-1251", "UTF-8",  "/.*<a href=\".*\"><img src=\".*\"> Скачать (.*)<\\/a>.*/isU"), $torrent, $matches6 );
	$x = preg_match_all( "/.*<tr><td style=\"vertical-align:top;\"><\\/td><td>(.*)<\\/td><\\/tr>.*/isU", $torrent, $matches3 );
	$name = $matches[1][0];
	$name = iconv( "UTF-8", "windows-1251", $name );
	$descr = $matches3[1][0];
	$descr = iconv( "UTF-8", "windows-1251", $descr );
	preg_match_all("/<b>Жанр: <\/b>(.*?)<br \/>/i",$descr,$genre);
	$genre = $genre[1][0];
	$descr = str_replace($genre,strip_tags($genre),$descr);
	//$descr = str_replace($genre,(preg_replace("/(.*?)<\/a>/","\\2",$genre)))
	//$descr = preg_replace_callback("/<b>Жанр: <\/b>(.*?)<br \/>/",'strip_tags',$descr);
	$descr = str_replace( "<div class=\"hidehead\" onclick=\"hideshow(\$(this))\">", "", $descr );
	$descr = str_replace( "<div class=\"hidebody\"></div><textarea class=\"hidearea\">", "[spoiler]", $descr );
	$descr = str_replace( "</textarea></div>", "[/spoiler]", $descr );
	$descr = preg_replace( "/\\<div class=\"hidewrap\">((\\s|.)+?)\\<\\/div>/i", "<br/>\\1", $descr );
	$seeders = (int)$matchesS[1][0];
	$leechers = (int)$matchesL[1][0];
	$fname = $matches6[1][0];
	$fp = fopen( "cache/".$torrentid.".torrent", "w" );
	fwrite( $fp, file_get_contents( "http://rutor.org/download/".$torrentid."" ) );
	fclose( $fp );
	$dict = bdec_file( "cache/".$torrentid.".torrent");
	if ( !isset( $dict ) )
	{
		return "Dict failed for #id.....skiping!<br>";
	}
	//list( $info ) = info
	//list( $dname, $plen, $plen ) = dname
	list($info) = dict_check($dict, "info");
	list($dname, $plen, $pieces) = @dict_check($info, "name(string):piece length(integer):pieces(string)");

	if ( mb_strlen( $pieces ) % 20 != 0 )
	{
		return "invalid pieces";
	}
	$filelist = array( );
	$totallen = dict_get( $info, "length", "integer" );
	if ( isset( $totallen ) )
	{
		$filelist[] = array(
		$dname,
		$totallen
		);
		$type = "single";
	}
	else
	{
		$flist = dict_get( $info, "files", "list" );
		if ( !isset( $flist ) )
		{
			return "missing both length and files";
		}
		if ( !count( $flist ) )
		{
			return "no files";
		}
		$totallen = 0;
		foreach ( $flist as $fn )
		{
			list( $ll, $ff ) = dict_check( $fn, "length(integer):path(list)" );
			$totallen += $ll;
			$ffa = array( );
			foreach ( $ff as $ffe )
			{
				if ( $ffe['type'] != "string" )
				{
					return "filename error";
				}
				$ffa[] = $ffe['value'];
			}
			if ( !count( $ffa ) )
			{
				return "filename error";
			}
			$ffe = implode( "/", $ffa );
			$filelist[] = array(
			$ffe,
			$ll
			);
			if ( $ffe == "Thumbs.db" )
			{
				return "Не держите Thumbs.db!<br>";
			}
		}
		$type = "multi";
	}
	$infohash = sha1( $info['string'] );
	$images = get_images($descr);
	$imagecodes = get_imagecodes($descr);
	$descr = str_replace($imagecodes[0],'',$descr);
	$descr = str_replace(' /><br />','',$descr);
	$online = get_trailer($descr);
	$ret = sql_query( "INSERT INTO torrents (filename,owner,visible,sticky,info_hash,name,size,numfiles,ismulti,descr,images,category,online,moderatedby,added,last_action) VALUES (".@implode( ",", @array_map( "sqlesc", array(
	$fname,
	$owner,
	1,
	0,
	$infohash,
	$name,
	$totallen,
	count( $filelist ),
	($type=='multi'?1:0),
	$descr,
	$images[0],
	$category,
	$online
	) ) ).", 0, ".@time( ).", ".@time( ).")" );
	if ( !$ret )
	{
		unlink("cache/".$torrentid.".torrent");
		return "Failed ".mysql_error();
	} else {
		$newid = mysql_insert_id( );
		foreach ($filelist as $file) {
			@sql_query("INSERT INTO files (torrent, filename, size) VALUES ($newid, ".sqlesc($file[0]).",".$file[1].")");
		}

	}
	@rename( "cache/".$torrentid.".torrent", "torrents/".$newid.".torrent" );
	$fps = @fopen( "torrents/".$newid.".torrent", "w" );
	if ( $fps )
	{
		@fwrite( $fps, @benc( $dict['value']['info'] ), @mb_strlen( @benc( $dict['value']['info'] ) ) );
		@fclose( $fps );
		//@chmod( $fps, 420 );
	}
	$anarray = get_announce_urls($dict);
	if ($anarray) {
		foreach ($anarray as $anurl)
		@mysql_query( "INSERT into trackers (torrent, tracker,seeders,leechers) VALUES ($newid, ".sqlesc($anurl).",$seeders,$leechers)" );
	}
	@mysql_query( "INSERT into trackers (torrent, tracker) VALUES ($newid, 'localhost')" );
	global $REL_CACHE;
	$REL_CACHE->clearGroupCache('block-indextorrents');
	return "OKAY!".($online?', with trailer':'');
}

?>