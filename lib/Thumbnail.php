<?php
class Thumbnail {
	const MODE_SCALE = "scale";
	const MODE_CROP = "crop";
	private $thumbPath;
	
	public function __construct($thumbPath) {
		$this->thumbPath = $thumbPath;
	}
	
	/**
	 * Массив $allowedSizes содержит допустимые размеры превью и  может быть получен методом setAllowedSizes().
	 * Если ограничение установлено не было, разрешается создавать превью любых размеров. 
	 * Допустимые значения передаются setAllowedSizes() в виде массива:
	 * array('100x100', '230x250' и т.д).  
	 */
	
	public $allowedSizes = array();
	
	public static function errorHeader($error) {
		$http_protocol = $_SERVER['SERVER_PROTOCOL'];
		$http = array(
				"200" => $http_protocol ." 200 OK",
				"404" => $http_protocol ." 404 Not Found",
				"500" => $http_protocol ." 500 Internal Server Error"
		);
		header($http[$error]);
	}
	
	/**
	 * Метод создает ссылку на превью изображения. Проверяем правильность режима сжатия
	 * и было ли установлено ограничение по размерам превью.
	 */
	
	public function link($image, $thumbWidth, $thumbHeight, $mode) {
		$link = $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$image}";
		if (($mode != self::MODE_SCALE) && ($mode != self::MODE_CROP)) {
			throw new ThumbnailException("Указанный режим сжатия '{$mode}' не поддерживается.");
		} elseif ($this->allowedSizes) {
			if($this->isAllowedSize($thumbWidth, $thumbHeight) === true) {
				return $link;
			} else {
				throw new ThumbnailException("Preview size '{$thumbWidth}x{$thumbHeight}' not allowed.");
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
				imagepng($thumb, $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				header('Content-Type: image/jpeg');
				readfile($this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
			case "image/png":
				self::errorHeader("200");
				imagepng($thumb, $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				header('Content-Type: image/png');
				readfile($this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
			case "image/gif":
				self::errorHeader("200");
				imagepng($thumb, $this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				header('Content-Type: image/gif');
				readfile($this->thumbPath. "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$imageName}");
				break;
		}
	}
	
	public function getResizedImage($image, $thumbWidth, $thumbHeight, $mode) {
		$srcPath = dirname($image);
		$dir = $this->thumbPath . "/{$thumbWidth}x{$thumbHeight}/{$mode}/{$srcPath}"; 
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		if(!is_readable($image)) {
			self::errorHeader("404");
			throw new ThumbnailException("Файл {$image} отсутствует или недоступен для чтения.");
		}
		
		$size = getimagesize($image);
		
		if(!$size) {
			self::errorHeader("500");
			throw new ThumbnailException("Ошибка чтения фалйа {$image}. Убедитесь, что ваш файл является картинкой.");
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
				throw new ThumbnailException("Формат {$type} выбранного изображения не поддерживается.");
		}
		
		$sourceRatio = $sourceWidth / $sourceHeight;
		$thumbRatio = $thumbWidth / $thumbHeight;
		$thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
		
		imagealphablending($thumb, false);
		$transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
		imagesavealpha($thumb, true);
		imagefilledrectangle($thumb, 0, 0, $thumbWidth, $thumbHeight, $transparent);
		
	    
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
	
	public function setAllowedSizes($allowedSizes) {
		foreach ($allowedSizes as $value) {
			$sizeReg = '{(\\d+)x(\\d+)}';
			if(!preg_match($sizeReg, $value, $regArray)) {
				throw new ThumbnailException(
						                     "Неверный формат передаваемых методу setAllowedSizes() размеров превью. 
						                     Аргумент должен быть массивом: array('100x100', '230x250' и т.д)."
		                                    );
			}
			array_shift($regArray);
			$sizes = array_map('intval', $regArray);
			array_push($this->allowedSizes, $sizes);
		}
	}

	/**
	 * Метод сравнивает допустимые параметры превью с указанными.
	 */
	
	public function isAllowedSize($width, $height) {
		
		foreach ($this->allowedSizes as $value) {
			if($width === $value[0] && $height === $value[1]) {
				return true;
			}
		}
	}
}
?>
