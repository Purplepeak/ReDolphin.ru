<?php

class Searcher
{
	private $database;
	private $rtTable = 'rt_files';
	private $table = 'index_files';
	
	public function __construct($database)
	{
		$this->database = $database;
	}
	
    public function updateRtIndex($object) 
    {
    	
    	$data = array(
    			'id' => $object->id,
    			'name' => $object->name,
    			'uniqName' => $object->uniqName,
    			'type' => $object->type,
    			'date' => time(),
    			'size' => $object->size
    	);
    	
    	$sth = $this->database->prepare("INSERT INTO {$this->rtTable} (id, file_name, uniq_name, file_type, create_date, file_size) 
				                        VALUES (:id, :name, :uniqName, :type, :date, :size)");
    	
    	$sth->execute($data);
    }
    
    public function getSearchResults($searchQuery) 
    {
    	$sth = $this->database->prepare("SELECT * FROM {$this->table}, {$this->rtTable} WHERE MATCH(:searchQuery) limit 0, 1000");
    	$sth->bindParam(':searchQuery', $searchQuery);
    	$sth->execute();
    	$sth->setFetchMode(PDO::FETCH_ASSOC);
    	$results = $sth->fetchAll();
    	
    	return $results;
    }
    
    public function delete($id) {
    	$sth = $this->database->prepare("DELETE FROM {$this->rtTable} WHERE id IN ({$id})");
    	$sth->execute();
    }
}
?>