<?php
class Thumbnail {
	const MODE_SCALE = "scale";
	const MODE_CROP = "crop";
	private static $thumbPath = "thumbnails";
	public static $sourcePath;
	public static $imageInfo;
	
	public static function errorHeader($error) {
		$http_protocol = $_SERVER['SERVER_PROTOCOL'];
		$http = array(
				"200" => $http_protocol ." 200 OK",
				"404" => $http_protocol ." 404 Not Found",
				"500" => $http_protocol ." 500 Internal Server Error"
		);
		return header($http[$error]);
	}
	
	public static function link($image, $thumbWidth, $thumbHeight, $mode) {
		if (($mode != self::MODE_SCALE) && ($mode != self::MODE_CROP)) {
			self::errorHeader("404");
			return false;
		} else {
			$nameReg = '{([^\\.\\/]+)\\/([^\\.\\/]+[.][a-z]+)}';
			$imageInfo = array();
			preg_match($nameReg, $image, $imageInfo);
			self::$sourcePath = $imageInfo[1];
			$filename = $imageInfo[2];
			if(($thumbWidth === 100 && $thumbHeight === 100) || ($thumbWidth === 200 && $thumbHeight === 200) || ($thumbWidth === 250 && $thumbHeight === 250) || ($thumbWidth === 130 && $thumbHeight === 200)) {
				$link = self::$thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$filename}";
				return $link;
			} else {
				self::errorHeader("404");
				return false;
			}
		}
	}
	
	private static function saveImage($thumb, $type, $thumbWidth, $thumbHeight, $mode, $imageName) {
		switch($type) {
			case "image/jpeg":
				self::errorHeader("200");
				return imagejpeg($thumb, self::$thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
			case "image/png":
				self::errorHeader("200");
				return imagepng($thumb, self::$thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
			case "image/gif":
				self::errorHeader("200");
				return imagegif($thumb, self::$thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
		}
	}
	
	public static function getResizedImage($image, $thumbWidth, $thumbHeight, $mode, $imageName) {
	
		if(!is_readable($image)) {
			self::errorHeader("404");
			header('Content-Type: text/html; charset=utf-8');
			return false;
		}
		
		$size = getimagesize($image);
		self::$imageInfo = $size;
		
		if(!$size) {
			self::errorHeader("500");
			header('Content-Type: text/html; charset=utf-8');
			return false;
		}
		
		$type = $size['mime'];
			
		list($sourceWidth, $sourceHeight) = $size;
		
		switch($type) {
			case "image/jpeg":
				$sourceImage = imagecreatefromjpeg($image);
				break;
			case "image/png":
				$sourceImage = imagecreatefrompng($image);
				break;
			case "image/gif":
				$sourceImage = imagecreatefromgif($image);
				break;
			default:
				self::errorHeader("500");
				header('Content-Type: text/html; charset=utf-8');
				return false;
		}
		
		$sourceRatio = $sourceWidth / $sourceHeight;
		$thumbRatio = $thumbWidth / $thumbHeight;
		$thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
		
	
		switch($mode) {
			case self::MODE_CROP:
				if ($sourceWidth <= $thumbWidth && $sourceHeight <= $thumbHeight) {
					$newWidth = $sourceWidth;
					$newHeight = $sourceHeight;
				} elseif ($sourceRatio >= $thumbRatio) {
					$newHeight = $thumbHeight;
					$newWidth = $thumbHeight * $sourceRatio;
				} else {
					$newWidth = $thumbWidth;
					$newHeight = $thumbWidth / $sourceRatio;
				}
				imagecopyresampled(
				    $thumb,
				    $sourceImage,
				    0 - floor(($newWidth - $thumbWidth) / 2),
				    0 - floor(($newHeight - $thumbHeight) / 2), 
				    0, 
				    0, 
				    $newWidth, 
				    $newHeight, 
				    $sourceWidth, 
				    $sourceHeight
		);
				self::saveImage($thumb, $type, $thumbWidth, $thumbHeight, $mode, $imageName);
				break;
		
			case self::MODE_SCALE:
				if ($sourceWidth <= $thumbWidth && $sourceHeight <= $thumbHeight) {
					$newWidth = $sourceWidth;
					$newHeight = $sourceHeight;
				} elseif ($sourceRatio >= $thumbRatio) {
					$newWidth = $thumbWidth;
					$newHeight = $thumbWidth / $sourceRatio;
				} else {
					$newHeight = $thumbHeight;
					$newWidth = $thumbHeight * $sourceRatio;
				}
				imagecopyresampled(
				    $thumb,
				    $sourceImage,
				    0 - floor(($newWidth - $thumbWidth) / 2),
				    0 - floor(($newHeight - $thumbHeight) / 2), 
				    0, 
				    0, 
				    $newWidth, 
				    $newHeight, 
				    $sourceWidth, 
				    $sourceHeight
		);
				self::saveImage($thumb, $type, $thumbWidth, $thumbHeight, $mode, $imageName);
				break;
			default:
				return false;
		}
	}
}
?>
