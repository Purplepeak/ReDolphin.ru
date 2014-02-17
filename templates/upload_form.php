 <div class="table">
  <div class="cell">
    <div class="upload">
      <p><h3 class="warning">Максимальный размер загружаемого файла: <?= $maxFileSize ?> МБ</h3></p>
      <div class="upload_form">
        <form name = "upload_form"  enctype="multipart/form-data" action="<?= BASE_URL ?>/upload"  method="POST" onsubmit="return showFileSize(<?= intval($maxFileSize)?>, this)" >
          <input type="hidden" name="MAX_FILE_SIZE" value="<?= $maxFileSize * pow(10, 6) ?>" >
          <div class="file-input-wrapper">
            <div class="btn-file-input">Обзор</div>
            <input type="file" name="userfile" class="file">
          </div>
          <input type="submit" value="Отправить" class="submit">
        </form>
      </div>
    </div>
  </div>
</div>   
<div id='alert-message'>
</div>