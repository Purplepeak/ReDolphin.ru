<?php

class Helper
{
    public static function formatBytes($size)
    {
        $base      = log($size) / log(1024);
        $suffixes  = array(
            'Б',
            'КБ',
            'МБ'
        );
        $precision = 2;
        
        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }
    
    
    
    public static function getEnding($number, $endingArray)
    {
        $number = $number % 100;
        if ($number >= 11 && $number <= 19) {
            $ending = $endingArray[2];
        } else {
            $i = $number % 10;
            switch ($i) {
                case 1:
                    $ending = $endingArray[0];
                    break;
                case 2:
                case 3:
                case 4:
                    $ending = $endingArray[1];
                    break;
                default:
                    $ending = $endingArray[2];
                    break;
            }
        }
        return $ending;
    }
    
    public static function useTranslit($name)
    {
        $translit = array(
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
        
        return strtr($name, $translit);
    }
    
}