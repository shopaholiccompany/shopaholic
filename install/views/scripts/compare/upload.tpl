
<?php if( empty($this->diff) ): ?>

  <form action="<?php echo $this->url(array()) ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="Filedata" />
    <br />
    <button type="submit">Submit</button>
  </form>

<?php else: ?>

  <ul>
    <?php foreach( $this->diff->getDiffs() as $diff ): ?>
      <?php //if( !$diff->isError() ) continue; ?>
      <li>
        <?php echo $diff->getRight()->getPath() ?>
        <span>
          <?php echo $diff->getCode() ?>
        </span>
        <span>
          <a class="smoothbox" href="<?php echo $this->url(array('action' => 'diff')) ?>?file=<?php echo urlencode($diff->getRight()->getPath()) ?>">
            diff
          </a>
        </span>
      </li>
    <?php endforeach; ?>
  </ul>


<?php endif; ?>