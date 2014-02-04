<?php
class ThumbnailException extends Exception {
	function __toString() {
		return "<div><p><h1>{$this->getMessage()}</h1></p></div>";
	}
}
?>
