<?php include 'header.php';?>
<?php include 'upload_form.php';?>
<?php if ($flash['error']): ?>
      <div class="alert alert-danger"><?= spChars($flash['error']) ?></div>
      <?php endif; ?>
<?php include 'footer.php';?>