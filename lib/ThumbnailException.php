<?php
class ThumbnailException extends Exception 
{
	public function __construct($message = null, $code = 0) {
		parent::__construct($message, $code);
	}
	
	function __toString() {
		return "{$this->getMessage()} \n {$this->getTraceAsString()}";
	}
}
?>
