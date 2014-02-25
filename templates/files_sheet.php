<?php include 'header.php';?>
<div class="rd-files_sheet">
  <div class="page-header rd-page-header">
    <h3>Список последних добавленных файлов:</h3>
  </div>
  <ul>
    <?php foreach ($files as $vaulue): ?>
        <li><a class="fileNameSheet" href="<?=  BASE_URL ."/files/". spChars($vaulue[0]) ?>"><?= spChars($vaulue[1]) ?></a> <span class="size-sheet"><?= spChars(Helper::formatBytes($vaulue[2])) ?></span></li>
    <?php endforeach?>
  </ul>
</div>
<?php include 'footer.php';?>

