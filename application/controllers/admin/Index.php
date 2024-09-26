<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'/Common.php';

class Index extends Common
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		redirect('/admin/dashboard');
	}
}
