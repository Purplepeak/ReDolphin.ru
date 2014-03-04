<!DOCTYPE html>

<!--[if IE 7]><html class="ie-7"><![endif]-->
<!--[if IE 8]><html class="ie-8"><![endif]-->
<!--[if gt IE 9]><!--><html><!--<![endif]-->
  <head>
    <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ReDolphin - ЗАЛИВАЙ</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>/stylecontent/icon.png">
    <link href="<?= BASE_URL ?>/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/stylecontent/custom-input.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/stylecontent/home.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/stylecontent/modal/the-modal.css" rel="stylesheet">
	<link href="<?= BASE_URL ?>/stylecontent/modal/modals.css" rel="stylesheet">
	<link href="<?= BASE_URL ?>/jplayer/skin/jplayer.blue.monday.css" rel="stylesheet" />
    
    <script src="<?= BASE_URL ?>/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>/jplayer/jquery.jplayer.min.js"></script>
    <script src="<?= BASE_URL ?>/js/js-custom-input-file.js"></script>
    <script src="<?= BASE_URL ?>/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>/stylecontent/modal/jquery-modal-lib.js"></script>
    <script src="<?= BASE_URL ?>/stylecontent/modal/jquery-modal-fix.js"></script>
    
  </head>
  <body>
    <div class="navbar navbar-default navbar-fixed-top rd-nav-fix">
		<div class="navbar-header rd-nav-header">
			<a class="navbar-brand rd-nav-brand" href="<?= BASE_URL ?>/">ReDolphin</a>
		</div>
		<ul class="nav navbar-nav rd-navbar-nav">
			<li><a href="<?= BASE_URL ?>/">Главная</a></li>
			<li><a href="<?= BASE_URL ?>/files">Файлы</a></li>
		</ul>
		<form class="search-form" action="<?= BASE_URL ?>/search" method="get">
		  <button class="search-button"><img src="<?= BASE_URL ?>/stylecontent/search-icon.png" style="vertical-align: middle"></button>
	      <span><input type="search" name="s" class="search rounded rd-search-field" placeholder="Поиск...." value=""></span>
        </form>
	</div>