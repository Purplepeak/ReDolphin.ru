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
require "lib/ThumbnailException.php";
require 'lib/Helper.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'dbInfo' => $dbInfo
    ));

$app->add(new \Slim\Middleware\SessionCookie(array(
		'expires' => '20 minutes',
		'path' => '/',
		'domain' => null,
		'secure' => false,
		'httponly' => false,
		'name' => 'slim_session',
		'secret' => 'CHANGE_ME',
		'cipher' => MCRYPT_RIJNDAEL_256,
		'cipher_mode' => MCRYPT_MODE_CBC
)));

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
		$app->flash('error', $e->getMessage());
		$app->redirect(BASE_URL."/");
	}
	
	$file->saveData();
	$id = $file->id;

	$app->redirect(BASE_URL."/files/{$id}");
});

$app->get('/files/:id', function($id) use ($app) {
	
	$fileData = new File($app->db);
	$fileData->findById($id);
	
	/**
	 * Если файл является картинкой, создаем ссылку на ее уменьшенную копию
	 * при помощи библиотеки Thumbnail. По ссылке запускается скрипт, который 
	 * собствнно создает эту копию.
	 */
	
	if (getimagesize($fileData->link)) {
		$thumbnail = new Thumbnail("uploads/{$id}");
		$thumbnail->setAllowedSizes(THUMBNAIL_WIDTH . 'x' . THUMBNAIL_HEIGHT);
		$thumbLink = BASE_URL .'/'. $thumbnail->link($fileData->link, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, MODE);
		
		$app->render('file_info.php', array(
				'fileData' => $fileData,
				'thumbnail' => $thumbLink
		));
	} else {
		$app->render('file_info.php', array(
				'fileData' => $fileData
		));
	}
});

$app->get('/error', function() use ($app) {
	$app->render('error_upload.php', array());
});

$app->get('/uploads/:id/:wh/:mode/:img+', function($id, $wh, $mode, $img) use($app) {
	$imgPath = implode('/', $img);
	$imgReg = '{(\\w+\\/\\d+\\/)(.+)}';
	
	preg_match($imgReg, $imgPath, $imageData);
	
	$imagePath = $imageData[0];
	$thumbPath = $imageData[1];
	
    $resReg = '{(\\d+)x(\\d+)}';
    
    preg_match($resReg, $wh, $thumbRes);
    
    $thumbWidth = $thumbRes[1];
    $thumbHeight = $thumbRes[2];
    
    $resizer = new Thumbnail("{$thumbPath}");
    
	$resizer->getResizedImage($imagePath, $thumbWidth, $thumbHeight, $mode);
    
});


$app->run();
?>