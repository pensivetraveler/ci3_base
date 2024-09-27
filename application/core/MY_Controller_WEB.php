<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller_WEB extends MY_Controller
{
    protected string $table;
    protected string $identifier;
    protected array $primaryKeyList;
    protected array $uniqueKeyList;
    protected array $notNullList;
    protected array $nullList;
    protected array $strList;
    protected array $intList;
    protected array $fileList;
    protected array $validateMessages;
    protected array $validateCallback;

	public string $baseViewPath;
    public array $data;
    public array $titleList;
    public array $addCSS;
    public array $addJS;
    public array $jsVars;
	public int $perPage;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('html');

        if($this->router->class === 'common') redirect('/welcome');

        $this->load->library('Authorization_token', ['config' => 'extra/jwt_config']);
		$this->load->library('pagination');
		$this->load->helper('html');

		$this->form_validation->set_error_delimiters('', '');

        $this->identifier = '';
        $this->validateMessages = [];
        $this->validateCallback = [];

		$this->baseViewPath = 'web/layout/index';
        $this->data = [];
        $this->titleList = [];
        $this->addCSS = [];
		$this->addJS = ['head' => [], 'tail' => []];
        $this->jsVars = [];
		$this->perPage = 10;
    }

    protected function validateToken()
    {
        $token = $this->input->post('token')?:$this->session->userdata('token');
        if(is_empty($token)){
            $this->response([
                'code' => EMPTY_TOKEN,
                'msg' => "TOKEN 값이 없습니다.",
                'data' => ['token' => $token,],
            ], RestController::HTTP_UNAUTHORIZED);
        }else{
            $decodedToken = $this->authorization_token->tokenParamCheck($token);
            if($decodedToken['status'] === FALSE){
                switch ($decodedToken['message']) {
                    case 'Token Time Expire.':
                        $this->response([
                            'code' => TOKEN_EXPIRED,
                            'msg' => "TOKEN 유효 시간이 지났습니다.",
                            'data' => ['token' => $token,],
                        ], RestController::HTTP_UNAUTHORIZED);
                    default:
                        $this->response([
                            'code' => WRONG_TOKEN,
                            'msg' => "올바른 TOKEN 값이 아닙니다.",
                            'data' => ['token' => $token,],
                        ], RestController::HTTP_UNAUTHORIZED);
                }
            }else{
                $this->session->set_userdata('token', $token);
                return $decodedToken['data'];
            }
        }
    }

	protected function auth(): bool
	{
		return true;
	}


	protected function setTitleList($data = [])
	{
		$this->titleList = $data;
	}

	/**
     * 페이징 함수를 함수를 이용해서 가공해준다.
     * @param $url       유지할 URI
     * @param $totalRow  총 Row 수
     * @param $perPage   한번에 보여줄 페이징 수
     * @param $config    페이징 환경설저
     * @return mixed     가공된 Paging 정보
     */
	protected function getPaginationLinks($url, $totalRow, $perPage, $config = [])
	{
        $page = $this->input->get('page');
        $page = $page ? intVal($page) : 1;

        $numLinks = ($page <= 4) ? (9 - $page) : 4;

        // 페이징 환경설정
        $pagingConfig = [
            'base_url' => base_url($url),    // 각페이지 지정변수 : 현재 페이지 URL
            'total_rows' => $totalRow,       // 각페이지 지정변수 : 전체 목록갯수
            'per_page' => $perPage,          // 각페이지 지정변수 : 한번에 보여줄 갯수
            'num_links' => $numLinks,        // 페이징 좌우 노출 갯수
            'use_page_numbers' => true,     // 페이징번호로 파라메터 넘김
            'page_query_string' => true,    // 페이징 넘버 쿼리형태로
            'reuse_query_string' => true,   // 기존 파라메터 유지
            'first_link' => '&lt;&lt;',
            'last_link' => "&gt;&gt;",
            'query_string_segment' => 'page',
        ];

        $config = array_merge($pagingConfig, $config);

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

	protected function viewApp($data)
	{
		$data['title'] = get_site_title(APP_NAME_EN, $this->titleList);
		$data['addCSS'] = $this->addCSS;
		$data['addJS'] = $this->addJS;
		$data['dialog'] = $this->session->flashdata('dialog');
		$data['class'] = $this->router->class;
		$data['method'] = $this->router->method;
		$data['titleList'] = $this->titleList;

		$data = array_merge($this->data, $data);
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

		if(!$this->config->item('error_occurs')) $this->load->view($this->baseViewPath, $data);
	}

	protected function addJsVars($data, $afterConstruct = false)
	{
		if(!$afterConstruct) {
			foreach ($data as $key => $val) {
				$this->jsVars[$key] = $val;
			}
		}else{
			$this->phptojs->append($data);
		}
	}
}
