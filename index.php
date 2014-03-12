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
require 'lib/Searcher.php';
require 'lib/functions.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'dbInfo' => $dbInfo,
    'dbSphinx' => $dbSphinx,
    'thumbSettings' => $thumbSettings,
    'maxFileSize' => $maxFileSize,
    'uploadPath' => $uploadPath,
    'host' => $host,
	'cookies.encrypt' => true,
	'cookies.secret_key' => 'hjhfyr289NiOj',
	'cookies.cipher' => MCRYPT_RIJNDAEL_256,
	'cookies.cipher_mode' => MCRYPT_MODE_CBC
));

$app->notFound(function() use ($app)
{
    $app->render('404.php');
});

$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '6 month',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => true,
    'name' => 'rd_session',
    'secret' => 'hjhfyr289NiOj',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));


//$app->add(new \Slim\Middleware\Translit($_GET['translit']));


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

$app->container->singleton('dbSphinx', function() use ($app)
{
    $connetcionArray = $app->config('dbSphinx');
    $driver          = $connetcionArray['driver'];
    $host            = $connetcionArray['host'];
    $port            = $connetcionArray['port'];
    $db              = new PDO($driver . ':host=' . $host . ';port=' . $port);
    
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
    $type          = getimagesize(encodeThis($file->link, $app->config('host')));
    
    if ($type == true and ($type['mime'] != 'image/tiff' and $type['mime'] != 'image/x-ms-bmp')) {
        $thumbnail = new Thumbnail("{$app->config('uploadPath')}/{$file->id}");
        
        /**
         * Если, по каким-либо причинам, создать ссылку не удается, пользователю
         * отсылается информация о файле без превью.
         */
        
        try {
            $thumbnail->setAllowedSizes(array(
                "{$thumbWidth}x{$thumbHeight}"
            ));
            $thumbLink = $thumbnail->link($file->link, $thumbWidth, $thumbHeight, $thumbMode);
            $file->save('thumb_link', $thumbLink);
        }
        catch (ThumbnailException $e) {
            error_log($e->getMessage());
        }
        
    }
    $searcher = new Searcher($app->dbSphinx);
    $searcher->updateRtIndex($file);
    if (count($_SESSION) == 1) {
    	$_SESSION['userfiles'] = array($file->id);
    } else {
    	array_push($_SESSION['userfiles'], $file->id);
    }
    
    $app->redirect(BASE_URL . "/files/{$file->id}");
});

$app->get('/files/:id', function($id) use ($app)
{
    $fileData = new File($app->db, $app->config('host'));
    try {
        $fileData->findById($id);
    }
    catch (Exception $e) {
        $app->notFound();
    }
    $app->render('file_info.php', array(
        'fileData' => $fileData,
        'id' => $id
    ));
});

$app->get('/uploads/:id/:wh/:mode/:img+', function($id, $wh, $mode, $img) use ($app)
{
    $imagePath = implode('/', $img);
    $thumbPath = dirname($imagePath);
    //var_dump($imagePath, $img, $thumbPath);
    $resReg = '{(\\d+)x(\\d+)}';
    
    try {
        if (!preg_match($resReg, $wh, $thumbRes)) {
            Thumbnail::errorHeader("500");
            throw new ThumbnailException("Regular expression {$resReg} for the thumbnail size does not match the link.");
        }
        
        $thumbWidth  = $thumbRes[1];
        $thumbHeight = $thumbRes[2];
        
        $resizer = new Thumbnail("{$thumbPath}");
        // $resizer->setAllowedSizes(array("{$thumbWidth}x{$thumbHeight}"));
        
        $resizer->getResizedImage(encodeThis($imagePath, $app->config('host')), $thumbWidth, $thumbHeight, $mode, $app);
    }
    catch (ThumbnailException $e) {
        error_log($e->getMessage());
        break;
    }
});

$app->get('/files', function() use ($app)
{
    $fileInfo = new File($app->db);
    $files    = $fileInfo->getFilesInfo();
    
    $app->render('files_sheet.php', array(
        'files' => $files
    ));
});

$app->get('/search', function() use ($app)
{
    $searchQuery = $_GET['s'];
    $file = new File($app->db, $app->config('host'));
    $results     = new Searcher($app->dbSphinx);
    $results     = $results->getSearchResults($searchQuery);
    $filesInfo = array();
    if (!empty($results)) {
    	$filesId = array();
    	foreach ($results as $value) {
    		array_push($filesId, $value['id']);
    	}
    	$filesInfo = $file->getMenyFiles($filesId);
    }
    
    
    $app->render('search_page.php', array(
        'results' => $filesInfo
    ));
});

$app->post('/delete/:id/:name', function($id, $name) use ($app)
{
    $delete             = new File($app->db);
    $deleteFromSearcher = new Searcher($app->dbSphinx);
    $filePath = $app->config('uploadPath') .'/'. $id;
    $delete->deleteFolder($filePath);
    $delete->deleteFromSession($id);
    $delete->deleteFile($id);
    $deleteFromSearcher->delete($id);
    
    $app->render('deleted.php', array(
        'name' => $name
    ));
});

$app->get('/debug', function() use($app) 
{
    var_dump($_COOKIE, $_SESSION);
});

$app->run();