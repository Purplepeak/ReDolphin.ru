<?php 

class Helper {
	
    public static function formatBytes($size) {
		$base = log($size) / log(1024);
		$suffixes = array('Б', 'КБ', 'МБ');
		$precision = 2;
		
		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}
}
?>