<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['custom_upload_config'] = true;

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

$config['chapter_movie_upload_config'] = array_merge($config['base_upload_config'], [
    'allowed_types' => 'mp4',
    'max_size' => '20480',
]);

$config['chapter_sheet_upload_config'] = array_merge($config['base_upload_config'], [
    'allowed_types' => 'pdf',
    'max_size' => '10240',
]);

$config['image_upload_config'] = array_merge($config['base_upload_config'], [
    'allowed_types' => 'gif|jpg|jpeg|png',
]);

$config['user_list_upload_config'] = array_merge($config['base_upload_config'], [
    'allowed_types' => 'xlsx|xls|csv',
    'max_size' => '10240',
]);

$config['app_file_upload_config'] = array_merge($config['base_upload_config'], [
    'allowed_types' => 'zip',
    'max_size' => '51200',
]);

$config['uploads_upload_config'] = array_merge($config['base_upload_config'], [
    'allowed_types' => 'wav|mp4|gif|jpg|jpeg|png',
]);

$config['thumbnail_upload_config'] = array_merge($config['base_upload_config'], [
    'allowed_types' => 'gif|jpg|jpeg|png',
]);