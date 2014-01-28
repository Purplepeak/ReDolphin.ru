<?php 

class File {
	private $database;
	public $id;
	public $name;
	public $type;
	public $date;
	public $size;
	public $link;
	
    public function __construct($database) {
		$this->database = $database;
	}
	
	public function saveData() {
		$data = array('id' => $this->id, 
				      'name' => $this->name, 
				      'type' => $this->type, 
				      'date' => $this->date, 
				      'size' => $this->size, 
				      'link' => $this->link
		              );
		$sth = $this->database->prepare("
				                        INSERT INTO files (file_id, file_name, file_type, create_date, file_size, link) 
				                        VALUE (:id, :name, :type, :date, :size, :link)"
		                                );
		$sth->execute($data);
		$id = $this->database->lastInsertId();
		$this->id = $id;
	}
	
	public function findById($id) {
		$sth = $this->database->query("
				                      SELECT file_id, file_name, file_type, create_date, file_size, link  
				                      FROM files WHERE file_id = ('$id') "
		                              );
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$row = $sth->fetch();
	
		$this->name = $row['file_name'];
		$this->type = $row['file_type'];
		$this->date = $row['create_date'];
		$this->size = $row['file_size'];
		$this->link = $row['link'];
	
	}
	
    public function formatBytes() {
		$base = log($this->size) / log(1024);
		$suffixes = array('Б', 'КБ', 'МБ');
		$precision = 2;
		
		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}
}
?>