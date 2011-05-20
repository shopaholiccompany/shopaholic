
<ul>
  <?php foreach( $this->diff->getDiffs() as $diff ): ?>
    <?php if( !$diff->isError() ) continue; ?>
    <li>
      <?php echo $diff->getRight()->getPath() ?>
      <span>
        <?php echo $diff->getCode() ?>
      </span>
    </li>
  <?php endforeach; ?>
</ul>