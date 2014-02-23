<?php 

class File {
	public $database;
	private $table = 'files';
	public $id;
	public $name;
	public $uniqName;
	public $type;
	public $date;
	public $size;
	public $link;
	public $thumbLink;
	
    public function __construct($database) {
		$this->database = $database;
	}
	
	public function saveData() {
		$data = array(
				      'id' => $this->id, 
				      'name' => $this->name, 
				      'uniqName' => $this->uniqName,
				      'type' => $this->type, 
				      'date' => $this->date, 
				      'size' => $this->size,
				      'link' => ''
		              );
		$sth = $this->database->prepare("
				                        INSERT INTO files (file_id, file_name, uniq_name, file_type, create_date, file_size, link) 
				                        VALUE (:id, :name, :uniqName, :type, :date, :size, :link)"
		                                );
		$sth->execute($data);
		$id = $this->database->lastInsertId();
		$this->id = $id;
	}
	
	public function findById($id) {
		
		$sth = $this->database->prepare("
				                      SELECT file_id, file_name, uniq_name, file_type, create_date, file_size, link, thumb_link  
				                      FROM {$this->table} WHERE file_id = :id"
		                              );
		$sth->bindParam(':id', $id);
		$sth->execute();
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row = $sth->fetch();
	
		$this->name = $row['file_name'];
		$this->uniqName = $row['uniq_name'];
		$this->type = $row['file_type'];
		$this->date = strval($row['create_date']);
		$this->size = $row['file_size'];
		$this->link = $row['link'];
		$this->thumbLink = $row['thumb_link'];
	}
	
	public function save($dbRow, $value = null) {
		
		$sth = $this->database->prepare("UPDATE {$this->table} SET {$dbRow} = :value WHERE file_id = :id");
		
		$sth->bindParam(':id', $this->id);
		$sth->bindParam(':value', $value);
		
		$sth->execute();
	}
	
	public function hasThumbnail() {
		if(is_null($this->thumbLink)) {
			return false;
		} else {
			return true;
		}
	}
}
?>