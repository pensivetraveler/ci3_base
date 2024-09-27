<?php defined('BASEPATH') or exit('No Direct script access allowed');

require_once __DIR__ . '/Common.php';

class Home extends Common
{
	function __construct()
	{
		parent::__construct();
	}

	function index($key = 0)
	{
		$this->load->view('welcome_message');
	}
}
