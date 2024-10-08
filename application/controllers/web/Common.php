<?php defined('BASEPATH') or exit('No direct script access allowed');

class Common extends MY_Controller_WEB
{
	function __construct()
	{
		parent::__construct();

		$this->defaultController = 'home';
	}

	public function index()
	{
		if($this->defaultController) {
			redirect("/".(get_path()?get_path().'/':'').$this->defaultController);
		}else{
			$this->load->view('welcome_message');
		}
	}
}
