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

# Constants
define(IMAGE_BASE, 'torrents/images/');
if ($_GET['for'] == 'index')
define(MAX_WIDTH, 160);
elseif ($_GET['for'] == 'browse')
define(MAX_WIDTH, 100);
elseif ($_GET['for'] == 'details')
define(MAX_WIDTH, 240);
elseif ($_GET['for'] == 'forum')
define(MAX_WIDTH, 240);
elseif ($_GET['for'] == 'rss')
define(MAX_WIDTH, 100);
elseif ($_GET['for'] == 'original')
define(MAX_WIDTH, 1000);

define(MAX_HEIGHT, 1000);

# Get image location
$image_file = $_GET['image'];
$image_path = IMAGE_BASE . "/$image_file";

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
$scale = min(MAX_WIDTH/$width, MAX_HEIGHT/$height);

# If the image is larger than the max shrink it
if ($scale < 1) {
if (($width > MAX_WIDTH) OR ($height > MAX_HEIGHT)) {
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
header("Location: $image_path");
exit;
}}

# Create error image if necessary
if (!$img) {
$img = imagecreate(MAX_WIDTH, MAX_HEIGHT);
imagecolorallocate($img,0,0,0);
$c = imagecolorallocate($img,70,70,70);
imageline($img,0,0,MAX_WIDTH,MAX_HEIGHT,$c2);
imageline($img,MAX_WIDTH,0,0,MAX_HEIGHT,$c2);
}

# Display the image
header("Content-type: image/jpeg");
imagejpeg($img);
?>