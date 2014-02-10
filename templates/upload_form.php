    <div class="upload">
      <p class="warning"><h3>Максимальный размер загружаемого файла: <?= $maxFileSize ?> МБ</h3></p>
      <form name = "upload_form" class="upload_form" enctype="multipart/form-data" action="<?= BASE_URL ?>/upload"  method="POST" onsubmit="return showFileSize(<?= intval($maxFileSize)?>)" >
        <input type="hidden" name="MAX_FILE_SIZE" value="<?= $maxFileSize * pow(10, 6) ?>" >
        <input name="userfile" type="file" id='fileinput' >
        <input type="submit" value="Отправить">
      </form>
    </div>
    <div>
    </div>
