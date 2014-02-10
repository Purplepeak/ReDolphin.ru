<?php
require "ThumbnailException.php";
require 'Thumbnail.php';
$path = $_SERVER["REQUEST_URI"];

$regExp = '{\\/(\\d+)x(\\d+)\\/(\\w+)\\/(.+)}';

if(!preg_match($regExp, $path, $imageData)) {
	Thumbnail::errorHeader("404");
	throw new ThumbnailException("Формат ссылки на изображение некорректен.");
	return false;
}
$image = $imageData[4];
$thumbWidth = $imageData[1];
$thumbHeight = $imageData[2];
$mode = $imageData[3];
$resizer = new Thumbnail('thumbnails');
try {
	$resizer->getResizedImage($image, $thumbWidth, $thumbHeight, $mode);
} catch (ThumbnailException $e) {
	echo $e;
}
?>