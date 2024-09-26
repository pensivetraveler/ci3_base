<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['custom_upload_config_loaded'] = true;

/*
|--------------------------------------------------------------------------
| FILE CONFIG
|--------------------------------------------------------------------------
*/
$config['base_upload_config'] = [
	'max_size' => '5120',
	'overwrite' => false,
	'encrypt_name' => true,
];
