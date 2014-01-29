<?php 
class UploadException extends Exception 
{ 
    public function __construct($message = null, $code = 0) { 
    	if ($message == null) {
    		$message = $this->codeToMessage($code);
    	}
        parent::__construct($message, $code); 
    } 

    private function codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "Загружаемый файл превышает допустимые значения."; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "Загружаемый файл превышает допустимые ресурсом размеры."; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "Файл был загружен частично."; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "Загружаемый файл был получен только частично."; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Отсутствует временная папка."; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Не удалось записать файл на диск."; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = " PHP-расширение остановило загрузку файла."; 
                break; 

            default: 
                $message = "Что-то пошло не так. Неизвестная ошибка."; 
                break;  
        } 
        return $message; 
    } 
} 
?>