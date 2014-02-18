<?php
error_reporting(-1);
mb_internal_encoding('utf-8');
date_default_timezone_set('Europe/Moscow');

require 'framework/Slim/Slim.php';
require 'config.php';
require 'lib/Upload.php';
require 'lib/File.php';
require 'lib/Thumbnail.php';
require 'lib/UploadException.php';
require "lib/ThumbnailException.php";
require 'lib/Helper.php';
require 'lib/functions.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'dbInfo' => $dbInfo,
    'thumbSettings' => $thumbSettings,
    'maxFileSize' => $maxFileSize,
    'uploadPath' => $uploadPath,
	'host' => $host
));

$app->notFound(function() use ($app)
{
    $app->render('404.php');
});

$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => true,
    'name' => 'slim_session',
    'secret' => 'CHANGE_ME',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));

define('BASE_URL', $app->request->getRootUri());
$app->container->singleton('db', function() use ($app)
{
    $connetcionArray = $app->config('dbInfo');
    $dbDriver        = $connetcionArray['dbDriver'];
    $dbHost          = $connetcionArray['dbHost'];
    $dbName          = $connetcionArray['dbName'];
    $dbUser          = $connetcionArray['dbUser'];
    $dbPass          = $connetcionArray['dbPass'];
    $db              = new PDO($dbDriver . ":host=" . $dbHost . ";dbname=" . $dbName, $dbUser, $dbPass, array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ));
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $db;
});

$app->get('/', function() use ($app)
{
    $app->render('homepage.php', array(
        'maxFileSize' => $app->config('maxFileSize')
    ));
});

$app->post('/upload', function() use ($app)
{
    $uploader = new Upload($app->config('uploadPath'), $app->config('host'));
    $file     = new File($app->db);
    try {
        $uploader->saveUploadedFile($file);
    }
    catch (UploadException $e) {
        $app->flash('error', $e->getMessage());
        $app->redirect(BASE_URL . "/");
    }
    
    /**
     * Если файл является картинкой, создаем ссылку на ее уменьшенную копию
     * при помощи библиотеки Thumbnail.
     * По ссылке запускается скрипт, который
     * собствнно создает эту копию.
     */
    
    $thumbSettings = $app->config('thumbSettings');
    $thumbWidth    = $thumbSettings['thumbWidth'];
    $thumbHeight   = $thumbSettings['thumbHeight'];
    $thumbMode     = $thumbSettings['thumbMode'];
    
    if (getimagesize(encodeThis($file->link, $app->config('host')))) {
    	$thumbnail = new Thumbnail("{$app->config('uploadPath')}/{$file->id}");
    
    	/**
    	 * Если, по каким-либо причинам, создать ссылку не удается, пользователю
    	 * отсылается информация о файле без превью.
    	*/
    
    	try {
    		$thumbnail->setAllowedSizes(array(
    				"{$thumbWidth}x{$thumbHeight}"
    		));
    		$thumbLink = BASE_URL . '/' . $thumbnail->link($file->link, $thumbWidth, $thumbHeight, $thumbMode);
    		$file->addData('thumb_link', $thumbLink, $file->id);
    	} catch (ThumbnailException $e) {
    		error_log($e->getMessage());
    	}
    	
    } 
    
    $app->redirect(BASE_URL . "/files/{$file->id}");
});

$app->get('/files/:id', function($id) use ($app)
{
    $fileData = new File($app->db);
    $fileData->findById($id);
    if ($fileData->name == null) {
        $app->notFound();
    }
    
    $app->render('file_info.php', array(
    		'fileData' => $fileData
    ));
});

$app->get('/uploads/:id/:wh/:mode/:img+', function($id, $wh, $mode, $img) use ($app)
{
    $imagePath = implode('/', $img);
    $thumbPath = dirname($imagePath);
    
    $resReg = '{(\\d+)x(\\d+)}';
    
    try {
        if (!preg_match($resReg, $wh, $thumbRes)) {
            throw new ThumbnailException("Регулярное выражение {$resReg} для размера превью не соответствует ссылке.");
        }
        
        $thumbWidth  = $thumbRes[1];
        $thumbHeight = $thumbRes[2];
       
        $resizer = new Thumbnail("{$thumbPath}");
        // $resizer->setAllowedSizes(array("{$thumbWidth}x{$thumbHeight}"));
        $resizer->getResizedImage(encodeThis($imagePath, $app->config('host')), $thumbWidth, $thumbHeight, $mode);
    }
    catch (ThumbnailException $e) {
        $errorData = array(
            'error' => "Ошибка сервера"
        );
        $app->render('server_error.php', $errorData, 500);
        $e->getMessage();
        $e->getTraceAsString();
        break;
    }
});

$app->run();
?>