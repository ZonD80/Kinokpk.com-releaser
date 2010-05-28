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

//if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");


# Constants
define("IMAGE_BASE", 'torrents/images');
define("THUMBS_BASE", 'cache/thumbnail');  //Xlab

if (isset($_GET['for'])) {
	if ($_GET['for'] == 'index')
	define("MAX_WIDTH", 160);
	elseif ($_GET['for'] == 'browse')
	define("MAX_WIDTH", 100);
	elseif ($_GET['for'] == 'details')
	define("MAX_WIDTH", 240);
	elseif ($_GET['for'] == 'forum')
	define("MAX_WIDTH", 240);
	elseif ($_GET['for'] == 'rss')
	define("MAX_WIDTH", 100);
	elseif ($_GET['for'] == 'pzindex')
	define("MAX_WIDTH", 60);
}

if (!defined("MAX_WIDTH")) create_error();

// Check DIR

if (!is_dir(THUMBS_BASE)) mkdir(THUMBS_BASE);

# Get image location
$image_file = trim($_GET['image']);
$image_path = IMAGE_BASE . "/".$image_file;
//File extention
$ext = substr($image_file, strlen($image_file)-3, 3);
$id = substr($image_file, 0, strlen($image_file)-4);

$thumb_path = THUMBS_BASE."/".$id.$_GET['for'].".".$ext;

if (file_exists($thumb_path)) {
	header("Location: ".$thumb_path);
	die();
}

# Load image
$img = null;
$ext = strtolower(end(explode('.', $image_path)));
if ($ext == 'jpg' || $ext == 'jpeg') {
	$img = @imagecreatefromjpeg($image_path);
} else if ($ext == 'png') {
	$img = @imagecreatefrompng($image_path);
	# Only if your version of GD includes GIF support
} else if ($ext == 'gif') {
	$img = @imagecreatefromgif($image_path);
}

# If an image was successfully loaded, test the image for size
if ($img) {

	# Get image size and scale ratio
	$width = imagesx($img);
	$height = imagesy($img);
	$scale = MAX_WIDTH/$width;

	# If the image is larger than the max shrink it
	if ($scale < 1) {
		if ($width > MAX_WIDTH) {
			$new_width = floor($scale*$width);
			$new_height = floor($scale*$height);
			# Create a new temporary image
			$tmp_img = imagecreatetruecolor($new_width, $new_height);
			# Copy and resize old image into new image
			imagecopyresampled($tmp_img, $img, 0, 0, 0, 0,
			$new_width, $new_height, $width, $height);
			imagedestroy($img);
			$img = $tmp_img;
		}
	}else{
		header("Location: ".$image_path);
		die();
	}} else create_error();

	# Create error image if necessary
	function create_error() {

		$img = imagecreate(100,100);
		imagecolorallocate($img,0,0,0);
		$c = imagecolorallocate($img,255,255,255);
		$str = "Error creating";
		$str2 = "thumbnail";
		// определяем координаты вывода текста
		$size = 2; // размер шрифта
		$x_text = imagefontwidth($size)*strlen($str)-3;
		$y_text = imagefontheight($size)-3;

		imagestring($img, $size, 3, 3, $str,$c);
		imagestring($img, $size, 3, 10, $str2,$c);
		header("Content-type: image/jpeg");
		imagejpeg($img);
		die();
	}

	//--------------------------------------------------Xlab---------------
	function save_image($image, $file, $extention){

		if (file_exists($file)){
			@unlink($file);
			save_image($image, $file, $extention);
		}else{

			switch ($extention){

				case "jpg" :
					imagejpeg($image, $file);
					header("Content-type: image/jpeg");
					imagejpeg($image);
					break;

				case "png" :
					imagepng($image, $file);
					header("Content-type: image/png");
					imagepng($image);
					break;

				case "gif" :
					imagegif($image, $file);
					header("Content-type: image/gif");
					imagegif($image);
					break;

			}

			return true;

		}
	}

	save_image($img,$thumb_path,$ext);


	?>