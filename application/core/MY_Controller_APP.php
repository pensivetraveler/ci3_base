<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller_APP extends MY_Controller_WEB
{
    public function __construct()
    {
        parent::__construct();

		$this->baseViewPath = 'app/layout/index';
		$this->perPage = 5;
	}

    protected function setTitleList($data = [])
    {
        $this->titleList = $data?:[$this->categoryTitle, $this->headerTitle];
    }

	protected function getPaginationLinks($url, $totalRow, $perPage, $config = [])
	{
		return $this->getPaginationLinks($url, $totalRow, $perPage, array_merge([
			'base_url' => $url,
			'total_rows' => $totalRow,
			'per_page' => $perPage,
			'page_query_string' => true,
			'full_tag_open' => '<div class="pagination">',
			'full_tag_close' => '</div>',
			'first_link' => false,
			'prev_link' => '<img src="'.base_url('/public/assets/app/img/button-before.png').'" alt="이전">',
			'next_link' => '<img src="'.base_url('/public/assets/app/img/button-next.png').'" alt="다음">',
			'last_link' => false,
			'reuse_query_string' => true,
		], $config));
	}
}
