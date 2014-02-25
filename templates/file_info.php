<?php include 'header.php';?>
<script type="text/javascript">
  var name = "<?= spChars($fileData->name) ?>";
  $(document).ready(function() {
      $('.download-string').text(truncate(name, 40));
  });
</script>
<script src="<?= BASE_URL ?>/stylecontent/modal/jquery-modal-fix.js"></script>

<div class="modal" id="test-modal" style="display: none;">
	<a href="#" class="close">&times;</a> <img class="src-image" src="<?= BASE_URL ."/". spChars($fileData->link) ?>">
</div>
<div class="rd-download">
	<div class="page-header">
		<h1>
			<a class="download-string" href="<?= BASE_URL ."/". spChars($fileData->link) ?>"></a><small> (<?= spChars(Helper::formatBytes($fileData->size)) ?>)</small>
		</h1>
	</div>
    <?php if ($fileData->hasThumbnail()): ?>
      <a href="#" class="trigger"><img src="<?= spChars($fileData->thumbLink) ?>"></a>
    <?php endif; ?>
      <a class="rd-button" href="<?= BASE_URL ."/". spChars($fileData->link) ?>">Скачать</a>
	<div class="file-info">
		<p>Дата загрузки: <?= spChars($fileData->date) ?></p>
	</div>

	<!--
      <div class="maxImageWidth"></div>
      <div class="maxImageHeight"></div>
      <div class="modalWidth"></div>
      <div class="modalHeight"></div>
      -->

</div>
<?php include 'footer.php';?>
    
