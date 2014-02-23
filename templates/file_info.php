<?php include 'header.php';?>
<script type="text/javascript">
    var name = "<?= spChars($fileData->name) ?>";
</script>
    <div class="rd-download">
      <h1><a class="download-string" href = "<?= BASE_URL ."/". spChars($fileData->link) ?>" ></a><small> (<?= spChars(Helper::formatBytes($fileData->size)) ?>)</small></h1>
    <?php if ($fileData->hasThumbnail()): ?>
      <a class='rd-thumbnail' href = "<?= BASE_URL ."/". spChars($fileData->link) ?>"><img src="<?= spChars($fileData->thumbLink) ?>"></a>
    <?php endif; ?>
      <a class="rd-button" href = "<?= BASE_URL ."/". spChars($fileData->link) ?>" >Скачать</a>
      <div class="file-info">
        <p>Дата загрузки: <?= spChars($fileData->date) ?></p>
      </div>
    </div>
<?php include 'footer.php';?>
