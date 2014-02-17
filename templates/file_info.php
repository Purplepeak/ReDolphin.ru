<?php include 'header.php';?>
    <div class="download">
      <h1><a class="download-string" href = "<?= BASE_URL ."/". spChars($fileData->link) ?>" ><?= spChars($fileData->name) ?> <small>(<?= spChars(Helper::formatBytes($fileData->size)) ?>)</small></a></h1>
    <?php if ($fileData->hasThumbnail()): ?>
      <a href = "<?= BASE_URL ."/". spChars($fileData->link) ?>"><img src="<?= spChars($fileData->thumbLink) ?>"></a>
    <?php endif; ?>
      <a class="button" href = "<?= BASE_URL ."/". spChars($fileData->link) ?>" >Скачать</a>
      <div class="file-info">
        <p>Дата загрузки: <?= spChars($fileData->date) ?></p>
      </div>
    </div>
<?php include 'footer.php';?>
