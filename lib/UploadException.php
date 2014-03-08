<?php 
class UploadException extends Exception 
{	
    public function __construct($message = null, $code = 0) { 
        parent::__construct($message, $code); 
    } 
    
    public static function fromErrorCode($code) 
    {
    	$fromCode = new self();
    	$fromCode->message = $fromCode->codeToMessage($code);
    	return $fromCode;
    	
    }

    private function codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "Загружаемый файл превышает допустимые размеры."; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "Загружаемый файл превышает допустимые ресурсом размеры."; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "Файл был загружен частично, пропробйте еще раз."; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "Загружаемый файл был получен только частично. Пропробйте еще раз."; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Ошибка на стороне сервера, попробуйте позже."; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Не удалось записать файл, попробуйте еще раз."; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "Непредвиденная ошибка, попробуйте еще раз."; 
                break; 

            default: 
                $message = "Что-то пошло не так. Неизвестная ошибка."; 
                break;  
        } 
        return $message; 
    } 
}