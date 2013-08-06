<?php
$this->includeAtTemplateBase('includes/header.php');
?>
  <h2 style="break: both">Mobiil-ID</h2>

  <?php
  // This page is shown only while in progress or some error occured
  if ($this->data['midStatus']['stage'] == 'progress'):
  ?>
    <p>
      <?php echo $this->t('{MobiilID:MobiilID:auth_progress_intro}') ?>
    </p>
    <p>
      <?php echo $this->t('{MobiilID:MobiilID:verification_code}') ?>:
      <strong id="mid-verification"><?php echo $this->data['midStatus']['message'] ?></strong> 
    </p>
    <p>
      <?php echo $this->t('{MobiilID:MobiilID:auth_progress_instruction}') ?>
    </p>
    <form method="get" name="f">
      <input type="hidden" name="AuthState" value="<?php echo htmlspecialchars($this->data['authstate']); ?>" />
      <input type="submit" value="<?php echo $this->t('{MobiilID:MobiilID:refresh}') ?>">
    </form>
  <?php else: ?>
    <p class="error"><?php echo $this->t('{MobiilID:MobiilID:auth_failure}') ?>:
      <br>
      <strong><?php echo $this->t('{MobiilID:MobiilID:'. $this->data['midStatus']['message'] .'}') ?></strong>
    </p>
  <?php endif ?>
<?php

$this->includeAtTemplateBase('includes/footer.php');
?>
