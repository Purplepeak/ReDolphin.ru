<?php 
error_reporting(-1);
mb_internal_encoding('utf-8');
date_default_timezone_set('Europe/Moscow');

require 'framework/Slim/Slim.php';
require 'lib/functions.php';
require 'config.php';
require 'lib/Upload.php';
require 'lib/File.php';
require 'lib/Thumbnail.php';
require 'lib/UploadException.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'dbInfo' => $dbInfo
    ));

define('BASE_URL', $app->request->getRootUri());

$app->container->singleton('db', function() use($app) {
	$connetcionArray = $app->config('dbInfo');
	$dbDriver = $connetcionArray['dbDriver'];
	$dbHost = $connetcionArray['dbHost'];
	$dbName = $connetcionArray['dbName'];
	$dbUser = $connetcionArray['dbUser'];
	$dbPass = $connetcionArray['dbPass'];
	$db = new PDO(
			      $dbDriver . ":host=" . $dbHost . ";dbname=" . $dbName, 
			      $dbUser, 
			      $dbPass,
                  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	return $db;
	
});

$app->get('/', function() use ($app) {
	$app->render('homepage.php', array());
	
});

$app->post('/upload', function() use ($app) {
	$uploader = new Upload;
	$file = new File($app->db);
	try {
		$uploader->saveUploadedFile($file);
	} catch (UploadException $e) {
		echo $e->getMessage();
	}
	$file->saveData();
	$id = $file->id;
	if (getimagesize($file->link)) {
		Thumbnail::getResizedImage($file->link, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, "scale", $file->name);
	}
	
	
	$app->redirect(BASE_URL."/files/{$id}");
});

$app->get('/files/:id', function($id) use ($app) {
	$fileData = new File($app->db);
	$fileData->findById($id);
	$thumbnail = BASE_URL .'/'. Thumbnail::link($fileData->link, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, 'scale');
	$app->render('file_info.php', array(
			'fileData' => $fileData,
			'thumbnail' => $thumbnail
	));
});


$app->run();
?>