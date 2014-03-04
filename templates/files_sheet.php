<?php include 'header.php';?>
<div class="rd-files-sheet">
  <div class="page-header rd-page-header">
    <h3>Список последних добавленных файлов:</h3>
  </div>
  <ul>
    <?php foreach ($files as $vaulue): ?>
        <li><a class="file-name-sheet" href="<?=  BASE_URL ."/files/". spChars($vaulue['file_id']) ?>"><?= spChars($vaulue['file_name']) ?></a> <span class="size-sheet"><?= spChars(Helper::formatBytes($vaulue['file_size'])) ?></span></li>
    <?php endforeach?>
  </ul>
</div>
<?php include 'footer.php';?>

