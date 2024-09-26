<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->encryption->initialize(
            array(
                'cipher' => 'aes-256',
                'mode' => 'ctr',
            )
        );
    }

    function _view($view, $array = [])
    {
        $this->load->view("includes/View_head");
        $this->load->view("includes/View_header");
        $this->load->view($view, $array);
        $this->load->view("includes/View_modal");
        $this->load->view("includes/View_footer");
    }

    function _view_pwd_reset($view, $array = [])
    {
        $this->load->view($view, $array);
    }

    function response($data = NULL, $http_code = NULL)
    {
        if($http_code === NULL) $http_code = HTTP_OK;
        $type = $data['type']??'object';

        $response = new StdClass();
        $response->code = $data['code']??2000;
        $response->msg = $data['msg']??'';
        $response->type = $type;
        if($type === 'object'){
            $response->data[] = $data['data']??null;
        }else{
            $response->data = $data['data']??[];
        }
        $response->errors = $data['errors']??null;

        $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    function web_sendmail($to, $subject, $message)
    {
        $this->email->initialize([
            'useragent' => 'CodeIgniter',
            'mailpath'  => '/usr/sbin/sendmail',
            'protocol'  => 'smtp',
            'smtp_host' => SMTP_HOST,
            'smtp_user' => SMTP_USER,
            'smtp_pass' => SMTP_PASS,
            'smtp_port' => SMTP_PORT,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'wordwrap'  => TRUE,
        ]);
        $this->email->from(FROM_EMAIL, FROM_NAME);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        return $this->email->send();
    }

    protected function uploader($name, $_files = null)
    {
        $response = [
            'result' => true,
            'data' => [],
            'code' => DATA_UPLOADED,
            'message' => 'success',
        ];

        if(is_null($_files)) $_files = $_FILES;

        if(array_key_exists($name, $_files) && $_files[$name] && $_files[$name]['name']) {
            $uploadPath = set_realpath('public/uploads/'.$this->router->class.'/'.date('Y').'/');
            if(!make_directory($uploadPath)) throw new Exception($this->upload->display_errors(), CREATE_FOLDER_FAIL);

            $this->upload->initialize(
                array_merge(
                    $this->config->item($name.'_upload_config')?:$this->config->item('base_upload_config'),
                    [
                        'upload_path' => $uploadPath,
                    ]
                )
            );

            if(is_array($_files[$name]['name'])) {
                $file_names = $_files[$name]['name'];

                for($i = 0; $i < count($file_names); $i++) {
                    $_FILES[$name] = [
                        'name' => $_files['name'][$i],
                        'type' => $_files['type'][$i],
                        'tmp_name' => $_files['tmp_name'][$i],
                        'error' => $_files['error'][$i],
                        'size' => $_files['size'][$i],
                    ];

                    try {
                        if(!$this->upload->do_upload($name)) throw new Exception($this->upload->display_errors(), UPLOAD_FILE_FAIL);

                        $data = $this->upload->data();
                        $key = $this->Model_File->addData(array_merge($data,
                            ['file_link' => get_filepath_from_link($data['full_path'])]
                        ), false);
                        if(!$key) throw new Exception('FILE DB Error', WRITE_FILEDB_FAIL);

                        $response['data'][] = [
                            'file_id' => $key,
                            'attach_cd' => $this->getAttachCd($this->upload->data()['file_ext']),
                        ];
                    } catch (Exception $e) {
                        return [
                            'result' => false,
                            'data' => [],
                            'code' => $e->getCode(),
                            'message' => $e->getMessage(),
                        ];
                    }
                }
            }else{
                $_FILES = $_files;

                try {
                    if(!$this->upload->do_upload($name)) throw new Exception($this->upload->display_errors(), UPLOAD_FILE_FAIL);

                    $key = $this->Model_File->addData($this->upload->data(), false);
                    if(!$key) throw new Exception('FILE DB Error', WRITE_FILEDB_FAIL);

                    $response['data'][] = [
                        'file_id' => $key,
                        'attach_cd' => $this->getAttachCd($this->upload->data()['file_ext']),
                    ];
                } catch (Exception $e) {
                    return [
                        'result' => false,
                        'data' => [],
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                    ];
                }
            }
        }else{
            $response['result'] = false;
            $response['code'] = UPLOAD_DATA_NOT_EXIST;
            $response['message'] = 'empty';
        }

        return $response;
    }

    public function downloader($key = 0)
    {
        $response = [
            'result' => false,
            'data' => [],
            'code' => ERROR_DOWNLOAD_NOTDATA,
            'message' => 'empty',
        ];

        if(!$key) return $response;

        $data = $this->Model_File->getData([], ['file_id' => $key]);
        if(!$data) return $response;

        if(!is_file($data->full_path)) {
            $response['code'] = ERROR_DOWNLOAD_NOTFILE;
            return $response;
        }

        try {
            $this->Model_File->modNumb('download_cnt', 1, ['file_id' => $key]);
            force_download($data->client_name, file_get_contents($data->full_path));
        } catch (Exception $e) {
            log_message('error', 'downloadr : modNumb error '.$this->db->last_query());
            return [
                'result' => false,
                'data' => [],
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        return true;
    }

    function getAttachCd($filename)
    {
        $attach_cd = '';
        if(is_video_file_ext($filename)){
            $attach_cd = 'ATT002';
        }
        if(is_image_file_ext($filename)){
            $attach_cd = 'ATT003';
        }
        if(!$attach_cd) $attach_cd = 'ATT001';

        return $attach_cd;
    }

    protected function getCodeName($dto)
    {
        $data = $this->getCodeData($dto);
        return $data?$data->cd_name:'';
    }

    protected function getCodeData($dto)
    {
        $list = $this->getCodeList($dto);
        return $list?$list[0]:null;
    }

    protected function getCodeList($dto)
    {
        $dto = array_merge($dto, ['use_yn' => 'Y']);
        return $this->Model_Sys_Code->getList([], $dto);
    }

    protected function getArticleFileLink($dto)
    {
        $this->db->select('article_attachment.*');
        $this->db->join('article_attachment', 'article_attachment.file_id=file.file_id', 'left');
        if(array_key_exists('article_id', $dto)){
            $this->db->where('article_attachment.article_id', $dto['article_id']);
            unset($dto['article_id']);
        }
        return $this->getFileLink($dto);
    }

    protected function getArticleFileData($dto)
    {
        $this->db->select('article_attachment.*');
        $this->db->join('article_attachment', 'article_attachment.file_id=file.file_id', 'left');
        if(array_key_exists('article_id', $dto)){
            $this->db->where('article_attachment.article_id', $dto['article_id']);
            unset($dto['article_id']);
        }
        return $this->getFileData($dto);
    }

    protected function getArticleFileList($dto)
    {
        $this->db->select('article_attachment.*');
        $this->db->join('article_attachment', 'article_attachment.file_id=file.file_id', 'left');
        if(array_key_exists('article_id', $dto)){
            $this->db->where('article_attachment.article_id', $dto['article_id']);
            unset($dto['article_id']);
        }
        return $this->getFileList($dto);
    }

    protected function getFileLink($dto)
    {
        $data = $this->getFileData($dto);
        if($data) {
            return get_filepath_from_link($data->full_path);
        }else{
            return '';
        }
    }

    protected function getFileData($dto)
    {
        return $this->Model_File->getData([], $dto);
    }

    protected function getFileList($dto)
    {
        return $this->Model_File->getList([], $dto);
    }

    protected function getFormOptions($item): array
    {
        $optionAttr = $item['option_attributes'] ?? [];
        if(empty($optionAttr)) $optionAttr['option_type'] = 'field';

        $type = $optionAttr['option_type'];
        $data = $optionAttr['option_data'] ?? [];
        $render = $optionAttr['render'] ?? [];

        $options = [];
        switch ($type) {
            case 'yn' :
                return ['Y', 'N'];
            case 'field' :
                $options = form_options_by_field($item['field'] ?? '');
                break;
            case 'code' :
                $list = $this->getCodeList($data['params']);
                if(empty($render)) $render = [ 'id' => 'cmb_cd', 'text' => 'cd_name' ];
                $options = $this->getOptionsFromDBList($list, $render);
                break;
            case 'static' :
                $options = $data;
                break;
            default :
                if(empty($data))
                    trigger_error("getFormOptions : {$item['field']} have no option data.", E_USER_ERROR);

                if(empty($render))
                    trigger_error("getFormOptions : {$item['field']} attributues have no render data.", E_USER_ERROR);

                switch ($type) {
                    case 'db' :
                        $list = $this->db
                            ->where($data['params'])
                            ->get($data['table'])->result_array();
                        $options = $this->getOptionsFromDBList($list, $render);
                        break;
                    case 'model' :
                        if(!property_exists($this, $data['model'])) $this->load->model($data['model']);

                        if(!method_exists($this->{$data['model']}, $data['method']))
                            trigger_error("getFormOptions : {$item['field']} attributues have type {$type}. But method {$data['method']} not exist.", E_USER_ERROR);

                        $params = array_merge([
                            'select' => [],
                            'where' => [],
                            'like' => [],
                            'limit' => [],
                            'orderBy' => [],
                        ], $data['params'] ?? []);
                        $list = call_user_func_array([$this->{$data['model']}, $data['method']], $params);
                        $options = $this->getOptionsFromDBList($list, $render);
                        break;
                    case 'custom' :
                        $options = call_user_func_array($data['method'], $data['params']);
                        break;
                }
        }
        return is_empty($options)?form_options_by_field($item['field']):$options;
    }

    protected function getOptionsFromDBList($list, $render)
    {
        return array_reduce($list, function($carry, $item) use($render) {
            $item = (array)$item;
            $id = $item[$render['id']]??'';
            $text = $item[$render['text']]??'';
            $carry[$id] = $text;
            return $carry;
        }, []);
    }
}

include_once __DIR__.'/MY_Controller_API.php';
include_once __DIR__.'/MY_Controller_WEB.php';
include_once __DIR__.'/MY_Controller_APP.php';
include_once __DIR__.'/MY_Controller_ADM.php';
