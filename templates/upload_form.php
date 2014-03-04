<div class="table">
  <div class="cell">
    <div class="rd-upload">
      <div id='alert-message' class="rd-alert">
        <?php if ($flash['error']): ?>
            <div class="alert alert-danger"><?= spChars($flash['error']) ?></div>
        <?php endif; ?>
      </div>
      <form name = "upload_form" class="upload_form"  enctype="multipart/form-data" action="<?= BASE_URL ?>/upload"  method="POST" onsubmit="return showFileSize(<?= intval($maxFileSize)?>, this)" >
          <input type="hidden" name="MAX_FILE_SIZE" value="<?= $maxFileSize * pow(10, 6) ?>" >
          <div class="file-wrapper">
            <button type="button" class="file-button">Выберите файл</button>
            <input type="file" name="userfile" class="rd-file" />
          </div>
          <span class="file-holder"></span>
          <input type="submit" value="Отправить" class="rd-submit">
      </form>
    </div>
  </div>
</div>

