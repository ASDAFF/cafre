<?php
return array (
  'utf_mode' => 
  array (
    'value' => false,
    'readonly' => true,
  ),
  'cache_flags' => 
  array (
    'value' => 
    array (
      'config_options' => 3600,
      'site_domain' => 3600,
    ),
    'readonly' => false,
  ),
  'cookies' => 
  array (
    'value' => 
    array (
      'secure' => false,
      'http_only' => true,
    ),
    'readonly' => false,
  ),
  'exception_handling' => 
  array (
    'value' => 
    array (
      'debug' => true,
      'handled_errors_types' => 4437,
      'exception_errors_types' => 4437,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
      'log' => NULL,
    ),
    'readonly' => false,
  ),
  'connections' => 
  array (
    'value' => 
    array (
      'default' => 
      array (
        'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
        'host' => 'localhost',
        'database' => 'new_cafre',
        'login' => 'new_cafre',
        'password' => '7U8t9V9h',
        'options' => 2,
      ),
    ),
    'readonly' => true,
  ),
  
  'cache' => array(
    'value' => array(
      'type' => 'apc',
	  'readonly' => false,
      'sid' => $_SERVER["DOCUMENT_ROOT"]."#02"
    ),
  ),
);
