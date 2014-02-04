<?php
class Thumbnail {
	const MODE_SCALE = "scale";
	const MODE_CROP = "crop";
	private $thumbPath;
	
	public function __construct($thumbPath) {
		$this->thumbPath = $thumbPath;
	}
	
	/**
	 * Массив $allowSize содержит допустимые размеры превью и  может быть получен методом setAllowedSizes().
	 * Если ограничение установлено не было, разрешается создавать превью любых размеров. 
	 * Формат передаваемых массиву значений 100x100, 100x100/230x250 и т.д.  
	 */
	
	public $allowSizes;
	
	public static function errorHeader($error) {
		$http_protocol = $_SERVER['SERVER_PROTOCOL'];
		$http = array(
				"200" => $http_protocol ." 200 OK",
				"404" => $http_protocol ." 404 Not Found",
				"500" => $http_protocol ." 500 Internal Server Error"
		);
		return header($http[$error]);
	}
	
	/**
	 * Метод создает ссылку на превью изображения. Проверяем правильность режима сжатия
	 * и было ли установлено ограничение по размерам превью.
	 */
	
	public function link($image, $thumbWidth, $thumbHeight, $mode) {
		$link = $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$image}";
		if (($mode != self::MODE_SCALE) && ($mode != self::MODE_CROP)) {
			self::errorHeader("404");
			throw new ThumbnailException("Указанный режим сжатия '{$mode}' не поддерживается.");
			return false;
		} elseif ($this->allowSizes) {
			if($this->checkAllowsizes($thumbWidth, $thumbHeight) === true) {
				return $link;
			} else {
				self::errorHeader("404");
				throw new ThumbnailException("Размеры превью '{$thumbWidth}x{$thumbHeight}' не соответствуют допустимым.");
				return false;
			}
		} else {
			return $link;
		}
	}
	
	/**
	 * Метод сохраняет превью в папку назначения.
	 */
	
	private function saveImage($thumb, $type, $thumbWidth, $thumbHeight, $mode, $imageName) {
		switch($type) {
			case "image/jpeg":
				self::errorHeader("200");
				return imagejpeg($thumb, $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
			case "image/png":
				self::errorHeader("200");
				return imagepng($thumb, $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
			case "image/gif":
				self::errorHeader("200");
				return imagegif($thumb, $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
		}
	}
	
	public function getResizedImage($image, $thumbWidth, $thumbHeight, $mode) {
		$nameReg = '{(.+\\/)(([^\\.\\/]+)[.][a-z]+)}';
		preg_match($nameReg, $image, $imagePath);
		$dir = $this->thumbPath . "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imagePath[1]}"; 
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		if(!is_readable($image)) {
			self::errorHeader("404");
			throw new ThumbnailException("Файл отсутствует или недоступен для чтения.");
			return false;
		}
		
		$size = getimagesize($image);
		
		if(!$size) {
			self::errorHeader("500");
			throw new ThumbnailException("Ошибка чтения фала. Убедитесь, что ваш файл является картинкой.");
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
				throw new ThumbnailException("Формат выбранного изображения не поддерживается.");
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
				$this->saveImage($thumb, $type, $thumbWidth, $thumbHeight, $mode, $image);
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
				$this->saveImage($thumb, $type, $thumbWidth, $thumbHeight, $mode, $image);
				break;
			default:
				return false;
		}
	}
	
	public function setAllowedSizes($allowSizes) {
		$sizeReg = '{(\\d+)x(\\d+)\\/?(\\d+)?x?(\\d+)?\\/?(\\d+)?x?(\\d+)?\\/?(\\d+)?x?(\\d+)?\\/?(\\d+)?x?(\\d+)?}';
		preg_match($sizeReg, $allowSizes, $sizeData);
		array_shift($sizeData);
		//var_dump($sizeData);
		$allowed = array();
		foreach ($sizeData as $key => $value) {
			array_push($allowed, intval($sizeData[$key]));
		}
		//var_dump($allowed);
		$this->allowSizes = $allowed;
	}

	/**
	 * Метод сравнивает допустимые параметры превью с указанными.
	 */
	
	public function checkAllowsizes($width, $height) {
		for($i=0; $i < count($this->allowSizes); $i++) {
			if(($width === $this->allowSizes[$i] && $height === $this->allowSizes[$i+1])) {
				return true;
			}
		}
	}
}
?>
