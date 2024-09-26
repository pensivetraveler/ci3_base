<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'/Common.php';

class Dashboard extends Common
{
	public function __construct()
	{
		parent::__construct();

		$this->titleList[] = 'Home';
		$this->href = base_url('/admin/'.$this->router->class);
		$this->viewPath = 'admin/'.$this->router->class;
	}

	public function index()
	{
		$data['subPage'] = $this->viewPath.'/index';
		$data['backLink'] = WEB_HISTORY_BACK;

		$this->viewApp($data);
	}
}
