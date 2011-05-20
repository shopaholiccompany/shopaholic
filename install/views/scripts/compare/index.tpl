
<script type="text/javascript">
  var toggleCompareList = function(el, code) {
    var childEl = el.getParent('li.package_compare_listitem').getElement('.package_compare_code_' + code);
    var childElPar = childEl.getParent('ul');
    if( childEl.getStyle('display') == 'none' ) {
      childEl.setStyle('display', '');
      childElPar.setStyle('display', '');
    } else {
      childEl.setStyle('display', 'none');
      childElPar.setStyle('display', 'none');
    }
  }
</script>


<h2>
  Modified Files
</h2>

<div>
  <a class="buttonlink" href="<?php echo $this->url(array('action' => 'upload')) ?>">
    Upload
  </a>
  <a class="buttonlink" href="<?php echo $this->url() ?>?clear=1">
    Refresh
  </a>
</div>
<br />
<br />

<ul class="package_compare_list">
  <?php foreach( $this->changed as $packageKey => $packageInfo ):
    $hasCompare = in_array($packageKey, $this->oldPackages);
    ?>
    <li class="package_compare_listitem<?php if( $hasCompare ): ?> package_compare_listitem_hascompare<?php endif ?>">
      <div class="package_compare_container">
        <h3 class="package_compare_title">
          <?php echo $packageKey ?>
          <span class="package_compare_count">
            <?php $count = 0; foreach( $packageInfo as $code => $codeInfo ) $count += count($codeInfo) ?>
            (<?php echo $count ?>)
          </span>
          <?php if( $hasCompare ): ?>
            <span class="package_compare_hascompare">
              (Can Diff)
            </span>
          <?php endif; ?>
        </h3>
        <div class="package_compare_summary">
          <ul>
            <?php foreach( $packageInfo as $code => $codeInfo ): ?>
              <li>
                <a href="javascript:void(0);" onclick="toggleCompareList($(this), '<?php echo $code ?>')">
                  <?php echo $code ?>
                </a>
                (<?php echo count($codeInfo) ?>)
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
      <ul class="package_compare_diffs" style="display: none;">
        <?php foreach( $packageInfo as $code => $codeInfo ): ?>
          <li class="package_compare_code_<?php echo $code ?>" style="display: none;">
            <?php /*
            <h3>
              <?php echo $code ?>
            </h3>
             */ ?>
            <ul>
              <?php foreach( $codeInfo as $index => $path ): ?>
                <li>
                  <?php if( $hasCompare ): ?>
                  <a class="smoothbox" href="<?php echo $this->url(array('action' => 'diff')) ?>?package=<?php echo urlencode($packageKey) ?>&file=<?php echo urlencode($path) ?>">
                    <?php echo $path ?>
                  </a>
                  <?php else: ?>
                    <?php echo $path ?>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php endforeach; ?>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

<?php /*
<a href="<?php echo $this->url(array('action' => 'upload')) ?>">Upload</a>

<form action="<?php echo $this->url(array('action' => 'installed')) ?>" method="get">
  <ul>
    <?php foreach( $this->installedPackages as $package ): ?>
      <li>
        <span>
          <input type="checkbox" name="packages[]" value="<?php echo $package->getKey() ?>" />
        </span>
        <span>
          <?php echo $package->getKey() ?>
        </span>
      </li>
    <?php endforeach; ?>
  </ul>
  <br />

  <div class="buttons">
    <button type="submit">Compare</button>
  </div>
</form>
 * 
 */ ?>