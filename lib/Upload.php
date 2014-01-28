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
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$filename = $_FILES['userfile']['name'];
			$newDir = $this->makeDir() . $filename;
			if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $newDir)) {
				return false;
			}
		} else {
			return false;
		}
		
		$file->name = $_FILES['userfile']['name'];
		$file->size = $_FILES['userfile']['size'];
		$file->date = date('Y-m-d G:i:s');
		$file->name = $_FILES['userfile']['name'];
		$file->type = $_FILES['userfile']['type'];
		$file->link = $this->fileDir . $_FILES['userfile']['name'];
	}
}
?>