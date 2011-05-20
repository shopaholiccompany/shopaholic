<table class="package_text_diff">
  <tr class="package_text_diff_title">
    <td colspan="4">
      <?php echo $this->file ?>
    </td>
  </tr>
<?php

$li = 0;
$ri = 0;
foreach( $this->textDiff->getDiff() as $textDiffOp ) {
  $operation = strtolower(get_class($textDiffOp));
  $type = str_replace('text_diff_op_', '', $operation);
  $keys = ( $textDiffOp->orig ? array_keys($textDiffOp->orig) : ($textDiffOp->final ? array_keys($textDiffOp->final) : false) );
  if( !$keys ) continue;

  foreach( $keys as $key ) {
    $originalLine = ( is_array($textDiffOp->orig) && isset($textDiffOp->orig[$key]) ? $textDiffOp->orig[$key] : null );
    $finalLine = ( is_array($textDiffOp->final) && isset($textDiffOp->final[$key]) ? $textDiffOp->final[$key] : null );

    $cli = ( $originalLine !== null ? ++$li : '' );
    $cri = ( $finalLine !== null ? ++$ri : '' );
    $delta = '';
    ?>
      <tr class="text_diff_op_line <?php echo $operation ?>">
        <td class="package_text_diff_line">
          <?php echo htmlspecialchars($cli); ?>
        </td>
        <td class="package_text_diff_left">
          <pre style="margin: 0;"><?php echo htmlspecialchars($originalLine) ?></pre>
        </td>
        <td class="package_text_diff_line">
          <?php echo htmlspecialchars($cri); ?>
        </td>
        <td class="package_text_diff_right">
          <pre style="margin: 0;"><?php echo htmlspecialchars($finalLine) ?></pre>
        </td>
      </tr>
    <?php
  }
}

?>
</table>



