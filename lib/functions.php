<?php
function spChars($variable) {
	return htmlspecialchars ( $variable, ENT_QUOTES );
}

function encodeThis($text, $host) {
	switch ($host) {
		case 'windows' :
			return iconv ( "utf-8", "windows-1251//IGNORE", $text );
			break;
		case 'linux' :
			return $text;
			break;
		default :
			throw new Exception ( "Переменная host в файле config.php указана неверно." );
			break;
	}
}
?>