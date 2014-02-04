<?php 
class Upload {
	private $uploadPath = "uploads";
	private $fileDir;
	
	private function makeDir() {
		$dirList = scandir($this->uploadPath);
		$dirNumber = 0;
		if (count($dirList) == 3) {
			$dirNumber = $dirNumber + 1;
		} else {
			$dirNumber = (count($dirList) - 3) + 1; 
		}
		$fileDir = sprintf("%s" . "/" . "%d" . "/", $this->uploadPath, $dirNumber);
		$this->fileDir = $fileDir;
		
		mkdir($fileDir);
		
		return $fileDir;
	}
	
	// Метод получает информацию о файле и присваивает ее объекту
	
	public function saveUploadedFile($file) {
		if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
			throw new UploadException('Размеры файла превышают допустимые.');
		}
		
		if(empty($_FILES['userfile']['tmp_name'])) {
			throw new UploadException('Вы не выбрали файл.');
		}
		
		if (!($_FILES['userfile']['error'] === UPLOAD_ERR_OK)) {
			throw new UploadException($message = null, $_FILES['userfile']['error']);
		}
		
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$filename = $_FILES['userfile']['name'];
			$newDir = $this->makeDir() . $filename;
			if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $newDir)) {
				throw new UploadException('Не удалось переместить загруженный файл');
			}
			
			$file->name = $_FILES['userfile']['name'];
			$file->size = $_FILES['userfile']['size'];
			$file->date = date('Y-m-d G:i:s');
			$file->name = $_FILES['userfile']['name'];
			$file->type = $_FILES['userfile']['type'];
			$file->link = $this->fileDir . $_FILES['userfile']['name'];
		} else {
			throw new UploadException('Недопустимый способ загрузки файла.');
		}
	}		
}
?>