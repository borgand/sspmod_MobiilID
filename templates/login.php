<?php
$this->data['autofocus'] = 'midnumber';
$this->includeAtTemplateBase('includes/header.php');
?>
  <h2 style="break: both">Mobiil-ID</h2>

  <?php if (empty($this->data['midStatus'])): ?>
    <form action="?" method="post" name="f">
      <table>
        <td><?php echo $this->t('{MobiilID:MobiilID:mobile_no}'); ?>:</td>
        <td>
          <input type="hidden" name="AuthState" value="<?php echo htmlspecialchars($this->data['authstate']); ?>" />
          <input type="text" tabindex="1" id="midnumber" name="midnumber"/>
        </td>
        <td><input type="submit" value="<?php echo $this->t('{MobiilID:MobiilID:enter}') ?>"></td>
      </table>
    </form>
  <?php else:  // we should only get to this point if there was some error ?>
    <p class="error"><?php echo $this->t('{MobiilID:MobiilID:auth_failure}') ?>:
      <br>
      <strong><?php echo $this->t('{MobiilID:MobiilID:'. $this->data['midStatus']['message'] .'}') ?></strong>
    </p>
  <?php endif ?>
<?php

$this->includeAtTemplateBase('includes/footer.php');
?>
