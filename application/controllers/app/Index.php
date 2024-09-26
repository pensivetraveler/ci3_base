<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once __DIR__.'/Common.php';

class Index extends Common
{
	public function index()
	{
		$this->load->view('welcome_message');
	}
}
