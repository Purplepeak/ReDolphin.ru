<?php include 'header.php';?>
    <div class="download">
      <h1><a href = "<?= BASE_URL ."/". spChars($fileData->link) ?>" ><?= spChars($fileData->name) ?> <small>(<?= spChars($fileData->formatBytes()) ?>)</small></a></h1>
    <?php if (getimagesize($fileData->link)): ?>
      <a href = "<?= BASE_URL ."/". spChars($fileData->link) ?>"><img src="<?= spChars($thumbnail) ?>"></a>
    <?php endif; ?>
      <a href = "<?= BASE_URL ."/". spChars($fileData->link) ?>" >
        <div class="button">
          <p><span class="bold">Скачать</span></p>
        </div>
      </a>
      <div class="file-info">
        <p>Дата загрузки: <?= spChars($fileData->date) ?></p>
      </div>
    </div>
  </body>
</html>
<?php include 'footer.php';?>
