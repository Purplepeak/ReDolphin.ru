<?php include 'header.php';?>
    <div class="upload">
      <p class="warning"><h3>Максимальный размер загружаемого файла: 60 Mb</h3></p>
      <form class="upload_form" enctype="multipart/form-data" action="<?= BASE_URL ?>/upload"  method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="60000000" >
        <input name="userfile" type="file" >
        <input type="submit" value="Отправить" >
      </form>
    </div>
<?php include 'footer.php';?>