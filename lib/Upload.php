<?php 
class Upload {
	private $uploadPath;
	private $fileDir;
	private $host;
	
	public function __construct($uploadPath, $host) {
		$this->uploadPath = $uploadPath;
		$this->host = $host;
	}
	
	private function makeDir($id) {
		
		$fileDir = sprintf("%s" . "/" . "%d" . "/", $this->uploadPath, $id);
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
			throw new UploadException('Вы не выбрали файл, который необходимо загрузить. Пожалуйста, повторите попытку.');
		}
		
		if (!($_FILES['userfile']['error'] === UPLOAD_ERR_OK)) {
			throw new UploadException(null, $_FILES['userfile']['error']);
		}
		
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			
			$newName = $this->getSafeName($_FILES['userfile']['name'], $_FILES['userfile']['tmp_name']);
			
			$file->name = $newName['pretty'];
			$file->uniqName = $newName['safe'];
			$file->size = $_FILES['userfile']['size'];
			$file->date = date('Y-m-d G:i:s');
			$file->type = $_FILES['userfile']['type'];
			
			$file->saveData();
	        
			$newDir = $this->makeDir($file->id) . $newName['safe'];
			$file->link = $newDir;
			$file->save('link', $file->link);
			
			if (!move_uploaded_file($_FILES['userfile']['tmp_name'], encodeThis($newDir, $this->host))) {
				throw new UploadException('Не удалось переместить загруженный файл');
			}
			
		} else {
			throw new UploadException('Недопустимый способ загрузки файла.');
		}
	}
	
	private function getSafeName($name, $tmpName) {
		
		//$finfo = new finfo(FILEINFO_MIME_TYPE);
		//$mime = $finfo->file($tmpName);
		//$mimeReg = '{([a-z]+)\\/(.+)}';
		
		$nameReg = '{(.*)(\\..+)}ui';
		if(preg_match($nameReg, $name, $nameArray)) {
			$fileName = $nameArray[1];
			$fileExt = $nameArray[2];
		} else {
			$fileName = $name;
			$fileExt = '';
		}
		
		$prettyName = $fileName . "{$fileExt}";
		
		if (!$fileName) {
			$fileName = uniqid();
			$prettyName = $fileExt;
		}
		
		$safeName = preg_replace('/[^a-zA-ZА-ЯЁа-яё0-9]/ui', '', $fileName) . "{$fileExt}";
		//$safeName = useTranslit($safeName);
		
		return array(
				     'pretty' => $prettyName, 
				     'safe' => $safeName
		            );
	}
}
?>