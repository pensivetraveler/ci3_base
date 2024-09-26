<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['admin_base_config_loaded'] = true;

$config['admin_menu_top_default'] = [

];

$config['admin_menu_side_default'] = [
	'dashboard' => [
		'icon' => 'ri-home-smile-line',
		'title' => 'Home',
		'route' => '/admin/dashboard',
		'method' => 'dashboard',
		'router' => 'index',
		'params' => [
			'layout' => 'side-menu',
		],
		'className' => [],
		'subMenu' => [],
	],
	'welcome' => [
		'icon' => 'ri-user-line',
		'title' => 'Welcome',
		'route' => '',
		'method' => 'dashboard',
		'router' => 'index',
		'params' => [
			'layout' => 'side-menu',
		],
		'className' => [],
		'subMenu' => [
			'Welcome Sub 1' => [
				'icon' => '',
				'title' => 'Welcome Sub 1',
				'route' => '/admin/',
				'params' => [
					'welcome' => 1,
				],
				'className' => [],
			],
			'Welcome Sub 2' => [
				'icon' => '',
				'title' => 'Welcome Sub 2',
				'route' => '/admin/',
				'params' => [
					'welcome' => 2,
				],
				'className' => [],
			],

		],
	],
	'user' => [
		'icon' => 'ri-user-line',
		'title' => 'User',
		'route' => '/admin/users',
		'method' => 'dashboard',
		'router' => 'index',
		'params' => [
			'layout' => 'side-menu',
		],
		'className' => [],
		'subMenu' => [],
	],

];
