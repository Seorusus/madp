<?php

if (file_exists(__DIR__ . '/settings.local.php')) {
    include __DIR__ . '/settings.local.php';
}

$databases['default']['default'] = array (
  'database' => 'drupal',
  'username' => 'root',
  'password' => 'root',
  'prefix' => '',
  'host' => 'drupaldb',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);
$settings['install_profile'] = 'standard';
$config_directories['sync'] = 'sites/default/config_jznILp5mhCAHN4qjEmXREL8zlDe1ECT3ePJJeCZ7pXmLA4rxwkTP3sHzkUUqCCwyvn_Q1loxqQ/sync';


$settings['trusted_host_patterns'] = array(
    '^netapsys\.fr$',
    '^madp-complchomage-recette.netapsys\.fr$',
    '^localhost$',
    '^front.madp.localhost$'
);


