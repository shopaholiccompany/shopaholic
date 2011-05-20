<?php return array(
  'package' => array(
    'type' => 'library',
    'name' => 'zend',
    'version' => '4.0.3',
    'revision' => '$Revision: 7242 $',
    'path' => 'application/libraries/Zend',
    'repository' => 'socialengine.net',
    'meta' => array(
      'title' => 'Zend Framework',
      'author' => 'Webligo Developments',
      'changeLog' => array(
        '4.0.3' => array(
          'Translate/Adapter.php' => 'Prevents language files that do not end in CSV from being read',
          'manifest.php' => 'Incremented version',
        ),
        '4.0.2' => array(
          'Ldap/Filter/*' => 'Fixed unmerged conflict',
          'Search/Lucene/Storage/File.php' => 'Fixed IonCube encoding errors',
          'Search/Lucene/Storage/File/Memory.php' => 'Fixed IonCube encoding errors',
          'manifest.php' => 'Incremented version',
        ),
        '4.0.1' => array(
          'manifest.php' => 'Incremented version',
          'Db/Select.php' => 'PHP 5.1 compatibility fix',
        ),
      ),
    ),
    'directories' => array(
      'application/libraries/Zend',
    )
  )
) ?>