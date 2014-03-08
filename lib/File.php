<?php

class File
{
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
    private $host;
    
    public function __construct($database, $host = null)
    {
        $this->database = $database;
        $this->host = $host;
    }
    
    public function saveData()
    {
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'uniqName' => $this->uniqName,
            'type' => $this->type,
            'date' => $this->date,
            'size' => $this->size,
            'link' => ''
        );
        $sth  = $this->database->prepare("
				                        INSERT INTO {$this->table} (file_id, file_name, uniq_name, file_type, create_date, file_size, link) 
				                        VALUE (:id, :name, :uniqName, :type, :date, :size, :link)");
        $sth->execute($data);
        $id       = $this->database->lastInsertId();
        $this->id = $id;
    }
    
    public function findById($id)
    {
        
        $sth = $this->database->prepare("
				                      SELECT file_id, file_name, uniq_name, file_type, create_date, file_size, link, thumb_link  
				                      FROM {$this->table} WHERE file_id = :id");
        $sth->bindParam(':id', $id);
        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $row             = $sth->fetch();
        if (!$row) {
        	throw new Exception("File with id: {$id} not found");
        }
        $this->name      = $row['file_name'];
        $this->uniqName  = $row['uniq_name'];
        $this->type      = $row['file_type'];
        $this->date      = $row['create_date'];
        $this->size      = $row['file_size'];
        $this->link      = $row['link'];
        $this->thumbLink = $row['thumb_link'];
    }
    
    public function save($dbRow, $value = null)
    {
        
        $sth = $this->database->prepare("UPDATE {$this->table} SET {$dbRow} = :value WHERE file_id = :id");
        
        $sth->bindParam(':id', $this->id);
        $sth->bindParam(':value', $value);
        
        $sth->execute();
    }
    
    public function hasThumbnail()
    {
        if (is_null($this->thumbLink)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function getFilesInfo()
    {
        $sth1 = $this->database->prepare("SELECT COUNT(*) FROM {$this->table}");
        
        $sth1->execute();
        $sth1->setFetchMode(PDO::FETCH_NUM);
        $count = $sth1->fetch();
        $files = array();
        
        $sth2 = $this->database->prepare("SELECT * FROM {$this->table}");
        $sth2->setFetchMode(PDO::FETCH_ASSOC);
        $sth2->execute();
        $results = $sth2->fetchAll();
        
        $results = array_reverse($results);
        
        if ($count > 100) {
        	$results = array_slice($results, 0, 100);
        }
        
        return $results;
    }
    
    public function deleteFile($id)
    {
    	$sth = $this->database->prepare("DELETE FROM {$this->table} WHERE file_id= :id");
    	$sth->bindParam(':id', $id);
    	$sth->execute();
    }
    
    public function isMediaFile()
    {
    	$finfo = new finfo(FILEINFO_MIME_TYPE);
    	$mime = $finfo->file(encodeThis($this->link, $this->host));
    	$allowedMedia = array (
    			'audio/mpeg',
    			'audio/mp4',
    			'audio/ogg',
    			'audio/wav',
    			'audio/webm'
    	);
    	foreach ($allowedMedia as $value) {
    		if ($mime == $value) {
    			$fileExtension = pathinfo($this->link);
    			return $fileExtension['extension'];
    		}
    	}
    }
}