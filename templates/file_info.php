<?php include 'header.php';?>

<script type="text/javascript">
  var srcImg = "<?= BASE_URL ."/". spChars($fileData->link) ?>";
</script>
<script>
  $(document).ready(function() {
  	  $("#rd-jplayer").jPlayer( {
  	    ready: function () {
  	      $(this).jPlayer("setMedia", {
  	    	<?= $fileData->isMediaFile() ?>: "<?= BASE_URL ."/". spChars($fileData->link) ?>" 
  	      });
  	    },
  	  supplied: "<?= $fileData->isMediaFile() ?>",
  	  swfPath: "<?= BASE_URL ?>/jplayer/"
  	  });
  	});
</script>
<div class="modal" id="test-modal" style="display: none;">
  <button type="button" class="close">&times;</button>
</div>
<div class="rd-download">
  <div class="page-header">
    <h1>
      <a class="download-string" href="<?= BASE_URL ."/". spChars($fileData->link) ?>"><?= spChars($fileData->name) ?></a><small> (<?= spChars(Helper::formatBytes($fileData->size)) ?>)</small>
    </h1>
  </div>
  <?php if ($fileData->hasThumbnail()): ?>
  <button type="button" class="trigger"><img src="<?= BASE_URL ."/". spChars($fileData->thumbLink) ?>"></button> 
  <?php endif; ?>
  <?php if ($fileData->isMediaFile()): ?>
  <div id="rd-jplayer" class="jp-jplayer"></div>
  <div id="jp_container_1" class="jp-audio">
    <div class="jp-type-single">
      <div class="jp-gui jp-interface">
        <ul class="jp-controls">
          <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
          <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
          <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
          <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
          <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
          <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
        </ul>
        <div class="jp-progress">
          <div class="jp-seek-bar">
            <div class="jp-play-bar"></div>
          </div>
        </div>
        <div class="jp-volume-bar">
          <div class="jp-volume-bar-value"></div>
        </div>
        <div class="jp-time-holder">
          <div class="jp-current-time"></div>
          <div class="jp-duration"></div>
          <ul class="jp-toggles">
            <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
            <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
          </ul>
        </div>
      </div>
      <div class="jp-title">
        <ul>
          <li><?= spChars($fileData->name) ?></li>
        </ul>
      </div>
      <div class="jp-no-solution">
        <span>Update Required</span>
        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
      </div>
    </div>
  </div>
  <?php endif; ?>
  <div class="rd-buttons">
  <a class="rd-button" href="<?= BASE_URL ."/". spChars($fileData->link) ?>">Скачать</a>
  <form name = "delete-form" class="delete-form"  enctype="multipart/form-data" action="<?= BASE_URL ?>/delete/<?= spChars($id) ?>/<?= spChars($fileData->name) ?>"  method="POST" >
    <input type="submit" value="Удалить" class="delete-btn">
  </form>
  </div>
  <div class="file-info">
    <p>Дата загрузки: <?= spChars($fileData->date) ?></p>
  </div>
  <!--  
    <div class="maxImageWidth"></div>
    <div class="maxImageHeight"></div>
    <div class="modalWidth"></div>
    <div class="modalHeight"></div>
    <div class="x"></div>
    -->
</div>
<?php include 'footer.php';?>