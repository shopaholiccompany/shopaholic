<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'cabinet',
    'version' => '4.0.0',
    'path' => 'application/modules/Cabinet',
    'meta' => 
    array (
      'title' => 'cabinet',
      'description' => 'cabinet - это модуль шкафа в который и будут пропадать все покупки.',
      'author' => 'Dmytro Ovcharenko',
    ),
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' => 
    array (
      0 => 'application/modules/Cabinet',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/cabinet.csv',
    ),
  ),
); ?>