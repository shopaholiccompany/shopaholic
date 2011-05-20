<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.net/license/
 * @version    $Id: prepare.tpl 7244 2010-09-01 01:49:53Z john $
 * @author     John
 */
?>

<h3>Add Packages</h3>

<?php
  // Install Navigation
  echo $this->render('_installMenu.tpl')
?>

<br />




<?php // ERROR: No packages were selected ?>
<?php if( $this->selectError ): ?>

  <?php echo $this->translate('Please select a package.') ?>

<?php return; endif; // We have to return here or stuff below might blow up ?>




<?php // LIST: Selected actions ?>
<div class="admin_packages_actions">
  <h3 class="sep">
    <span>
      Actions
    </span>
  </h3>
  <p>
    These are the actions that will be performed during this installation:
  </p>
  <ul>
    <?php foreach( $this->operations as $operation ): ?>
      <li>
        &bull;
        <?php echo ucfirst($operation->getOperationType()) ?>
        package
        "<?php echo $operation->getPackage()->getGuid() ?>"
        <?php if( null !== ($previousPackage = $operation->getPreviousPackage()) ): ?>
          <?php echo $previousPackage->getVersion() ?> to
        <?php endif; ?>
        <?php echo $operation->getPackage()->getVersion() ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<br />




<?php // LIST: Failed dependencies ?>
<?php if( $this->settings['verbose'] || $this->dependencyError ): ?>

  <div class="admin_packages_dependencies">
    <h3 class="sep">
      <span>
        Dependency Check
      </span>
    </h3>
    <p>
      These items must already be installed to continue with this installation:
    </p>
    <?php if( count($this->dependencies) > 0 ): ?>
      <ul>
        <?php foreach( $this->dependencies as $guid => $dependencies ): ?>
          <?php foreach( $dependencies->getDependencies() as $dependency ): ?>
            <li>
              &bull;
              "<?php echo $dependencies->getPackage()->getKey() ?>"
              <?php echo $dependency->getRequired() ? 'requires' : 'recommends' ?> that
              <?php echo $dependency->getGuid() ?>
              <?php
                if( $dependency->getMinVersion() && $dependency->getMaxVersion() ) {
                  echo '(between ' . $dependency->getMinVersion() . ' and ' . $dependency->getMaxVersion(). ')';
                } else if( $dependency->getMinVersion() ) {
                  echo '(at least ' . $dependency->getMinVersion() . ')';
                } else if( $dependency->getMaxVersion() ) {
                  echo '(no greater than ' . $dependency->getMaxVersion(). ')';
                }
              ?>
              be installed.
              <?php if( $dependency->getStatus() != 0 ): ?>
                <div class="dependency-error">
                  <span>
                    <?php
                      switch( $dependency->getStatus() ) {
                        case 1:
                          echo 'Please upgrade this before continuing.';
                          break;
                        case 2:
                          echo 'Please wait for the developer to release a compatible version.';
                          break;
                        case 3:
                          echo 'Please install this before continuing.';
                          break;
                      }
                    ?>
                  </span>
                </div>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
    <ul>
      <li>
        &bull; No dependencies to run.
      </li>
    </ul>
    <?php endif; ?>
  </div>

  <br />

<?php endif; ?>




<?php // LIST: Failed sanity checks ?>
<?php if( $this->settings['verbose'] || $this->testsMaxErrorLevel >= 4 ): ?>
  <div class="admin_packages_requirements">
    <h3 class="sep">
      <span>
        System Requirements Check
      </span>
    </h3>
    <p>
      These system requirements must to continue with this installation:
    </p>
    <?php if( count($this->tests->getTests()) > 0 ): ?>
      <ul>
        <?php foreach( (array) $this->tests->getTests() as $packageTest ): ?>
          <?php foreach( $packageTest->getTests() as $test ): ?>
            <li>
              &bull;
              <?php echo $packageTest->getName() ?>
              requires:
              <?php echo $test->getName() ?>

              <?php if( !$test->hasMessages() ): ?>
                <div class='sanity-ok'>
                  <?php echo $test->getEmptyMessage(); ?>
              </div>
              <?php else: ?>
                <?php
                  $errLevel = $test->getMaxErrorLevel();
                  $errClass = ( $errLevel & 4 ? 'sanity-error' : ($errLevel & 3 ? 'sanity-notice' : 'sanity-ok' ));
                ?>
                <div class='<?php echo $errClass ?>'>
                  <?php foreach( $test->getMessages() as $message ): ?>
                    <?php echo $message->toString() ?> <br />
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <!-- module-storage requires: MySQL FakeDB Storage Engine
              <div class="error">
                <span>
                 MySQL FakeDB Storage Engine was not found.
                </span>
              </div>
              -->
            </li>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <ul>
        <li>
          &bull; No tests to run.
        </li>
      </ul>
    <?php endif; ?>
  </div>

  <br />

<?php endif; ?>




<?php // LIST: Failed diffs ?>
<?php if( $this->settings['verbose'] || $this->diffError ): ?>

  <div class="admin_packages_diff">
    <h3 class="sep">
      <span>
        Diff Check
      </span>
    </h3>
    <p>
      This is the list of files on your system that may have been modified since
      they were originally installed. If you decide to continue with this installation,
      they may be overwritten or deleted. To avoid losing any custom changes you've made
      to these files, you may want to cancel the installation and make backups. If you're
      not worried about losing custom changes to these files, you can continue with the
      installation.
    </p>
    <?php if( count($this->diffs) > 0 ): ?>
      <div class="admin_packages_diff_container">
        <?php if( !$this->settings['verbose'] ): ?>
          <ul>
            <?php foreach( $this->diffs as $batch ): ?>
              <?php foreach( $batch->getDiffs() as $code => $diff ):
                if( !$diff->isError() ) continue;
                ?>
                <li>
                  <span class="admin_packages_diff_fileresult">
                    (<?php echo $diff->getCode() ?>)
                  </span>
                  <span class="admin_packages_diff_filename">
                    <?php echo $diff->getRight()->getPath() ?>
                  </span>
                </li>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <?php foreach( $this->diffs as $batch ): ?>
            <div class="admin_packages_diff_package">
              <div class="admin_packages_diff_title">
                <?php echo $batch->packageKey ?> (<?php echo $batch->hasError() ? 'error' : 'okay' ?>)
              </div>
              <div class="admin_packages_diff_summary">
                <?php foreach( $batch->getDiffsByCode() as $code => $diffs ): ?>
                  <div>
                    <?php echo $code ?> (<?php echo count($diffs) ?>)
                  </div>
                <?php endforeach; ?>
              </div>
              <ul>
                <?php foreach( $batch->getDiffs() as $index => $diff ):
                  //if( !$diff->isError() ) continue;
                  ?>
                  <li>
                    <span class="admin_packages_diff_fileresult">
                      (<?php echo $diff->getCode() ?>)
                    </span>
                    <span class="admin_packages_diff_filename">
                      <?php echo $diff->getRight()->getPath() ?>
                    </span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div>
        No diffs to run.
      </div>
    <?php endif; ?>
  </div>

  <br />

<?php endif; ?>




<?php // CONTINUE || ERROR ?>
<?php if( !$this->prepareError ): // Okay ?>
  <div class="admin_packages_install_submit">
    <?php echo $this->formButton(null, 'Continue', array('onclick' => 'window.location.href = "'.$this->url(array('action' => 'prepare')).'?skip=0";')) ?>
    or <a href="<?php echo $this->url(array('action' => 'index')) ?>">cancel installation</a>
  </div>
<?php elseif( $this->diffErrorOnly ): // Diff error only ?>
  <div class="admin_packages_install_error">
    <div class="tip">
      <span>
        Warning: The files above will be overwritten if you choose to continue.
      </span>
    </div>
    <div class="admin_packages_install_submit">
      <?php echo $this->formButton(null, 'Continue & Overwrite', array('onclick' => 'if( confirm("Are you sure that you want to continue? If you do, you will lose any custom changes made to the files listed.") ) { window.location.href = "'.$this->url(array('action' => 'prepare')).'?skip=0"; }')) ?>
      or
      <?php echo $this->formButton(null, 'Continue & Skip', array('onclick' => 'if( confirm("Are you sure that you want to continue? If you do, your install may not work properly as not all of the files will be updated.") ) { window.location.href = "'.$this->url(array('action' => 'prepare')).'?skip=1"; }')) ?>
      or <a href="<?php echo $this->url(array('action' => 'index')) ?>">cancel installation</a>
    </div>
  </div>
<?php else: // Other errors ?>
  <div class="admin_packages_install_error">
    <div class="tip">
      <span>
        Please solve the above problems before proceeding.
      </span>
    </div>
    <?php if( $this->settings['force'] ): ?>
      <div class="admin_packages_install_submit">
        <?php echo $this->formButton(null, 'Continue', array('onclick' => 'window.location.href = "'.$this->url(array('action' => 'prepare')).'?skip=0"; }')) ?>
        or <a href="<?php echo $this->url(array('action' => 'index')) ?>">cancel installation</a>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
