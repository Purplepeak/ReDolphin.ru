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
function useTranslit($name) {
	$translit = array (
			'а' => 'a',
			'А' => 'A',
			'б' => 'b',
			'Б' => 'B',
			'в' => 'v',
			'В' => 'V',
			'г' => 'g',
			'Г' => 'G',
			'д' => 'd',
			'Д' => 'D',
			'е' => 'e',
			'Е' => 'E',
			'ё' => 'yo',
			'Ё' => 'Jo',
			'ж' => 'zh',
			'Ж' => 'Zh',
			'з' => 'z',
			'З' => 'Z',
			'и' => 'i',
			'И' => 'I',
			'й' => 'j',
			'Й' => 'J',
			'к' => 'k',
			'К' => 'K',
			'л' => 'l',
			'Л' => 'L',
			'м' => 'm',
			'М' => 'M',
			'н' => 'n',
			'Н' => 'N',
			'о' => 'o',
			'О' => 'O',
			'п' => 'p',
			'П' => 'P',
			'р' => 'r',
			'Р' => 'R',
			'с' => 's',
			'С' => 'S',
			'т' => 't',
			'Т' => 'T',
			'у' => 'u',
			'У' => 'U',
			'ф' => 'f',
			'Ф' => 'F',
			'х' => 'h',
			'Х' => 'H',
			'ц' => 'c',
			'Ц' => 'C',
			'ч' => 'ch',
			'Ч' => 'Ch',
			'ш' => 'sh',
			'Ш' => 'Sh',
			'щ' => 'shh',
			'Щ' => 'Shh',
			'ъ' => '',
			'Ъ' => '',
			'ы' => 'y',
			'Ы' => 'Y',
			'ь' => "'",
			'Ь' => "''",
			'э' => 'je',
			'Э' => 'Je',
			'ю' => 'yu',
			'Ю' => 'Ju',
			'я' => 'ya',
			'Я' => 'Ja' 
	);
	
	return strtr ( $name, $translit );
}
?>