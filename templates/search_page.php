<?php include 'header.php';?>
<div class="rd-search">
  <div class="page-header rd-page-header">
    <h3>По вашему запросу мы нашли <?= count($results) ?> <?= Helper::getEnding(count($results), array('рузультат', 'рузультата', 'рузультов')) ?>:</h3>
  </div>
  <ol>
    <?php foreach ($results as $vaulue): ?>
        <li><a class="search-results" href="<?=  BASE_URL ."/files/". spChars($vaulue[0]) ?>"><?= spChars($vaulue[1]) ?></a> <span class="size-sheet"><?= spChars($vaulue[2]) ?></span></li>
    <?php endforeach?>
  </ol>
</div>
<?php include 'footer.php';?>

