<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "libraries/RestController.php"; // ⭐ 추가

class MY_Controller_API extends RestController
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
    protected string $validateConfig;
    protected array $validateMessages;
    protected array $validateCallback;
    protected bool $indexAPI;

    public function __construct()
    {
        parent::__construct('extra/rest_config');

        if($this->router->class === 'common') redirect('/welcome');

        $this->load->library('authorization_token', ['config' => 'extra/jwt_config']);
        $this->lang->load('status_code');

        $this->identifier = '';
        $this->validateConfig = 'form_'.strtolower($this->router->class);
        $this->validateMessages = [];
        $this->validateCallback = [];
        $this->indexAPI = true;
    }

    public function index_get($key = 0)
    {
        $data = $this->beforeGet();

        $this->afterGet($key, $data);
    }

    public function index_post($key = 0)
    {
        $dto = $this->beforePost($key);

        $this->afterPost($key, $dto);
    }

    public function index_put($key = 0)
    {
        if($key === 0) {
            $this->keyNotExist();
        }else{
            $data = $this->beforePut($key);

            $this->afterPut($key);
        }
    }

    public function index_patch($key = 0)
    {
        if($key === 0) {
            $this->keyNotExist();
        }else{
            $data = $this->beforePatch($key);

            $this->afterPatch($key);
        }
    }

    public function index_delete($key = 0)
    {
        if($key === 0) {
            $this->keyNotExist();
        }else{
            $data = $this->beforeDelete($key);

            $this->afterDelete($key);
        }
    }

    /* --------------------------------------------------------------- */

    protected function beforeGet()
    {
        $data = [];
        foreach ($this->input->get() as $key=>$val) {
            if(in_array($key, ['_', 'select', 'format', 'draw', 'pageNo', 'limit', 'searchWord', 'searchCategory'])) continue;
            if($val) $data['where'][$key] = $this->input->get($key);
        }

        if($this->input->get('format') === 'datatable') {
            if($this->input->get('searchWord') && $this->input->get('searchCategory')) {
                $data['like']['searchCategory'] = $this->input->get('searchWord');
            }
        }

        $data['select'] = $this->input->get('select');
        return $data;
    }

    protected function afterGet($key, $data)
    {
        empty($key) ? $this->list($data) : $this->view($key);
    }

    protected function list($data = [])
    {
        $data = $this->listBefore($data);

        $list = $this->Model->getList(
            $data['select'] ?? [],
            $data['where'] ?? [],
            $data['like'] ?? [],
            $data['limit'] ?? [],
            $data['order_by'] ?? [],
        );

        $this->response([
            'code' => DATA_RETRIEVED,
            'data' => $this->listAfter($list),
            'extra' => $data['extraFields'] ?? [],
        ]);
    }

    protected function listBefore($data)
    {
        return $data;
    }

    protected function listAfter($list)
    {
        foreach ($list as $key=>$item) {
            $list[$key] = $this->viewAfter($item);
        }
        return $list;
    }

    protected function view($key)
    {
        $this->viewBefore($key);

        $data = $this->Model->getData([], [$this->identifier => $key]);

        if(!$data) {
            $this->response([
                'code' => DATA_NOT_EXIST,
                'data' => [],
            ], RestController::HTTP_NOT_FOUND);
        }else{
            $this->response([
                'code' => DATA_RETRIEVED,
                'data' => $this->viewAfter($data),
            ]);
        }
    }

    protected function viewBefore($key)
    {
        $this->checkIdentifierExist($key);
    }

    protected function viewAfter($data)
    {
        if(count($this->fileList) > 0) {
            foreach ($this->fileList as $key) {
                if($data->{$key}){
                    $file_id = $data->{$key};
                    $file_dto = $this->Model_File->getList([], ['file_id' => $file_id]);
                    if($file_dto) {
                        $data->{$key} = $file_dto;
                    }else{
                        $data->{$key} = null;
                    }
                }
            }
        }

        return $data;
    }

    protected function beforePost($key)
    {
        if($key) $this->checkIdentifierExist($key);

        $dto = $this->validate($this->input->post());

        $this->checkUniqueExist($dto);

        if(count($this->fileList) > 0) {
            $dto = $this->uploadFileInList($dto);
        }

        return $dto;
    }

    protected function afterPost($key, $dto)
    {
        if($key) {
            $this->modData($key, $dto, true);
        }else{
            $this->addData($dto, false);
        }
    }

    protected function beforePut($key)
    {
        return $key;
    }

    protected function afterPut($key)
    {
        $this->checkIdentifierExist($key);

        $dto = $this->validate($this->put());

        $this->modData($key, $dto, true);
    }

    protected function beforePatch($key)
    {
        return $key;
    }

    protected function afterPatch($key)
    {
        $this->checkIdentifierExist($key);

        $dto = $this->validate($this->patch());

        $this->modData($key, $dto, true);
    }

    protected function beforeDelete($key)
    {
        return $key;
    }

    protected function afterDelete($key)
    {
        $this->checkIdentifierExist($key);

        $this->delData($key, true);
    }

    /* --------------------------------------------------------------- */
    protected function addData($dto, $bool)
    {
        $key = $this->Model->addData($dto, $bool);

        $this->response([
            'code' => DATA_CREATED,
            'data' => [$this->identifier => $key],
        ], RestController::HTTP_CREATED);
    }

    protected function modData($key, $dto, $bool)
    {
        $this->Model->modData($dto, [$this->identifier => $key], $bool);

        $this->response([
            'code' => DATA_EDITED,
            'data' => [$this->identifier => $key],
        ]);
    }

    protected function delData($key, $bool)
    {
        $this->Model->delData([$this->identifier => $key], $bool);

        $this->response([
            'code' => DATA_DELETED,
        ]);
    }

    /* --------------------------------------------------------------- */

    public function response($data = null, $http_code = null)
    {
        if(is_empty($data, 'code') && $http_code === null)
            show_error('response : Insufficient response data provided');

        if($http_code === null) $http_code = floor((int)$data['code']/10);
        $http_big_code = floor($http_code/100);

        $response = new stdClass();
        $response->code = is_empty($data, 'code')?(int)$http_code*10:$data['code'];
        $response->msg = is_empty($data, 'msg')?$this->lang->status($response->code):$data['msg'];
        $response->data = [];
        if(!is_empty($data, 'data')) {
            if(is_array($data['data'])) {
                $response->data = $data['data'];
            }else{
                $response->data[] = $data['data'];
            }
        }
        $response->errors = [];
        if(in_array($http_big_code, [4,5])) {
            if(is_empty($data, 'errors')) {
                $response->errors = [[
                    'location' => 'body',
                    'param' => null,
                    'value' => null,
                    'type' => 'server error',
                    'msg' => 'error occurred',
                ]];
            }else{
                $response->errors = $data['errors'];
            }
        }

        if(!is_empty($data, 'extra')) foreach ($data['extra'] as $k=>$v) $response->{$k} = $v;

        RestController::response($response, $http_code);
        $this->output->_display();
        exit;
    }

    protected function keyNotExist()
    {
        $this->response([
            'code' => EMPTY_REQUIRED_KEY,
            'errors' => [
                'location' => 'keyNotExist',
                'param' => 'key',
                'value' => '',
                'type' => 'missing data',
                'msg' => 'required',
            ]
        ], RestController::HTTP_BAD_REQUEST);
    }

    protected function validate($data = [], $model = null)
    {
        $this->validateFormRules();

        if(!$model) $model = $this->Model;
        return $this->validateManually(
            $data,
            $model,
            $this->validateMessages,
            $this->validateCallback,
        );
    }

    protected function getValidateFormRulesConfig()
    {
        return $this->config->item($this->validateConfig);
    }

    protected function validateFormRules()
    {
        $method = __METHOD__;

        $config = $this->getValidateFormRulesConfig();
        if(is_empty($config)) {
            $this->response([
                'data' => $this->input->post(),
                'errors' => [
                    [
                        'location' => $method,
                        'param' => '',
                        'value' => '',
                        'type' => '',
                        'msg' => "Validation Rules Config Is Empty",
                    ]
                ],
            ], RestController::HTTP_BAD_REQUEST);
        }

        $errors = [];
        $this->form_validation->set_rules($config);
        if($this->form_validation->run() === false) {
            $errors = array_reduce(validation_errors_array(), function ($carry, $item) use ($method) {
                $carry[] = [
                    'location' => $method,
                    'param' => $item['field'],
                    'value' => $item['value'],
                    'type' => $item['rule'],
                    'msg' => $item['message'],
                ];
                return $carry;
            }, []);
        }

        foreach ($config as $item) {
            // Check if 'rules' exists in the array item
            if (isset($item['rules'])) {
                // Use regex to check
                foreach ($this->config->item('file_rules') as $rule=>$ruleData) {
                    $exp = $ruleData['exp'];
                    $flags = $ruleData['flags'];
                    if (preg_match("/$exp/$flags", $item['rules'], $matches)) {
                        $param = $matches[2]??null;
                        if($this->form_validation->{$rule}($item['field'], $matches[2]) === false){
                            $errors[] = [
                                'location' => $method,
                                'param' => $item['field'],
                                'value' => $param,
                                'type' => $rule,
                                'msg' => $this->form_validation->get_error_msg($rule, $item['label'], $param),
                            ];
                        }
                    }
                }
            }
        }

        if(count($errors)) {
            $this->response([
                'data' => $this->input->post(),
                'errors' => $errors,
            ], RestController::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    protected function validateJson($required = [], $optional = [], $strList = [], $intList = [], $msgList = [], $callbacks = [])
    {
        $json_data = $this->input->raw_input_stream;
        $parsed_data = (array)json_decode($json_data);
        if(empty($required)) $required = array_keys($parsed_data);

        $dto = new class {};
        $dto->table = '';
        $dto->identifier = '';
        $dto->primaryKeyList = [];
        $dto->notNullList = empty($required)?array_keys($parsed_data):$required;
        $dto->nullList = $optional;
        $dto->strList = $strList;
        $dto->intList = $intList;
        $dto->fileList = [];

        return $this->validateManually($parsed_data, $dto, $msgList, $callbacks);
    }

    protected function validateManually($data = [], $dto = null, $msgList = [], $callbacks = [])
    {
        if($dto === null || !isset($dto->notNullList))
            $this->response([
                'code' => EMPTY_REQUIRED_DATA,
                'msg' => lang('status_code.'.EMPTY_REQUIRED_DATA),
                'data' => $data,
                'errors' => [[
                    'location' => 'validateManually',
                    'param' => null,
                    'value' => null,
                    'type' => 'required',
                    'msg' => lang('status_code.'.EMPTY_REQUIRED_DATA),
                ]]
            ], RestController::HTTP_BAD_REQUEST);

        foreach ($dto->notNullList as $key) {
            if( $dto->identifier && $key === $dto->identifier ) continue;
            if( in_array($key, $dto->primaryKeyList) ) continue;

            if(array_key_exists($key, $callbacks)){
                $this->{$callbacks[$key]}();
            }else{
                $errorMsg = '';
                $value = null;

                if(array_key_exists($key, $msgList)) {
                    $msg = $msgList[$key];
                }else{
                    $lang = $dto->table?lang($dto->table.'.'.$key):$key;
                    if($this->request === 'post' && count($dto->fileList) > 0 && in_array($key, $dto->fileList)){
                        if(!is_file_posted($key)) {
                            $errorMsg = "File Data {$key} Is Missing.";
                            $data = $_FILES;
                            $msg = $this->josa->__conv("$lang{을} 업로드하세요.");
                        }
                    }else{
                        if(!array_key_exists($key, $data)) {
                            $errorMsg = 'Required';
                        }else if(empty($data[$key]) && gettype($data[$key]) !== 'integer') {
                            $value = $data[$key];
                            $errorMsg = 'notEmpty';
                        }
                        if($errorMsg) $msg = $this->josa->__conv("$lang{은} 필수 입력값 입니다.");
                    }
                }

                if($errorMsg) {
                    $this->response([
                        'code' => EMPTY_REQUIRED_DATA,
                        'msg' => array_key_exists($key, $msgList)?$msgList[$key]:$msg,
                        'data' => $data,
                        'errors' => [[
                            'location' => 'validateManually',
                            'param' => $key,
                            'value' => $value,
                            'type' => 'required',
                            'msg' => $errorMsg,
                        ]]
                    ], RestController::HTTP_BAD_REQUEST);
                }
            }
        }

        foreach ($data as $key => $val) {
            $columnList = array_unique(array_merge($dto->notNullList, $dto->nullList));

            if(!in_array($key, $columnList)){
                unset($data[$key]);
                continue;
            }

            if(!is_object($val) && !is_array($val)) $data[$key] = trim(preg_replace('/\s\s+/', ' ', $val));
            if(in_array($key, $dto->strList) && empty($val)) $data[$key] = '';
            if(in_array($key, $dto->intList) && empty($val)) $data[$key] = 0;
            if(in_array($key, $dto->intList) && $data[$key]) $data[$key] = (int)$data[$key];

            switch ($key) {
                case 'gender' :
                    $data[$key] = strtoupper($data[$key]);
                    break;
                case 'password' :
                    if($val) {
                        $data[$key] = $this->encryption->encrypt($val);
                    }else{
                        unset($data[$key]);
                    }
                    break;
            }
        }

        return $data;
    }

    protected function validateToken()
    {
        $headers = $this->input->request_headers();
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken();
            if($decodedToken['status'] === FALSE){
                switch ($decodedToken['message']) {
                    case 'Token Time Expire.':
                        $this->response([
                            'code' => TOKEN_EXPIRED,
                            'data' => ['token' => $headers['Authorization']],
                        ], RestController::HTTP_UNAUTHORIZED);
                    default:
                        $this->response([
                            'code' => WRONG_TOKEN,
                            'data' => ['token' => $headers['Authorization']],
                        ], RestController::HTTP_UNAUTHORIZED);
                }
            }else{
                return $decodedToken['data'];
            }
        }else{
            $this->response([
                'code' => EMPTY_TOKEN,
            ], RestController::HTTP_UNAUTHORIZED);
        }
    }

    protected function uploadFileInList($dto, $model = null)
    {
        if(!$model) $model = $this->Model;
        $key = null;
        try {
            $uploadPath = set_realpath('public/uploads/'.$this->router->class.'/'.date('Y').'/');
            if(!make_directory($uploadPath)) throw new Exception($this->upload->display_errors(), CREATE_FOLDER_FAIL);

            foreach ($model->fileList as $key) {
                if(is_file_posted($key)) {
                    $this->upload->initialize(
                        array_merge(
                            $this->config->item($key.'_upload_config')?:$this->config->item('base_upload_config'),
                            [
                                'upload_path' => $uploadPath,
                            ]
                        )
                    );

                    if(!$this->upload->do_upload($key)) throw new Exception($this->upload->display_errors(), UPLOAD_FILE_FAIL);

                    $dto[$key] = $this->Model_File->addData($this->upload->data(), false);

                    if(!$dto[$key]) throw new Exception('FILE DB Error', WRITE_FILEDB_FAIL);
                }
            }
        } catch (Exception $e) {
            $this->response([
                'code' => $e->getCode(),
                'msg' => strip_tags($e->getMessage()),
                'data' => $_FILES,
                'errors' => [
                    'location' => 'uploadFileInList',
                    'param' => $key,
                    'type' => 'upload error',
                ]
            ], RestController::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $dto;
    }

    protected function uploader($name, $fileDto = null)
    {
        $response = parent::uploader($name, $fileDto);

        if($response['result']) {
            return $response['data'];
        }else{
            if($response['code'] === UPLOAD_DATA_NOT_EXIST) {
                return [];
            }else{
                $this->response([
                    'code' => $response['code'],
                    'msg' => strip_tags($response['message']),
                    'data' => $_FILES,
                    'errors' => [
                        'location' => 'uploader',
                        'param' => $name,
                        'type' => 'upload error',
                    ]
                ], RestController::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    protected function setProperties($model, $model_parent = null)
    {
        $this->identifier = $model->identifier;
        $this->fileList = $model->fileList;

        if(count([...$model->notNullList, ...$model->nullList]) !== count([...$model->strList, ...$model->intList, ...$model->fileList])){
            $this->response([
                'code' => MODEL_DATA_NOT_COINCIDENCE,
                'errors' => [
                    'location' => 'model',
                    'type' => 'model error',
                ]
            ], RestController::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function checkIdentifierExist($key, $model = null)
    {
        if(!$model) $model = $this->Model;
        $this->checkCnt([$model->identifier => $key], $model);
    }

    protected function checkUniqueExist($dto, $model = null)
    {
        if(!$model) $model = $this->Model;
        if(count($model->uniqueKeyList) > 0){
            foreach ($model->uniqueKeyList as $key) {
                if($this->checkDuplicate([$key => $dto[$key]], $model)){
                    $lang = $model?lang($model->table.'.'.$key):$key;
                    $this->response([
                        'code' => DATA_ALREADY_EXIST,
                        'msg' => $this->josa->__conv("동일 $lang{이} 이미 존재합니다."),
                    ], RestController::HTTP_CONFLICT);
                    break;
                }
            }
        }
    }

    protected function checkDuplicate($dto, $model = null)
    {
        if(!$model) $model = $this->Model;
        return $model->checkDuplicate($dto) > 0;
    }

    protected function checkCnt($dto, $model = null)
    {
        if(!$model) $model = $this->Model;
        if($model->getCnt($dto) === 0){
            $this->response([
                'code' => DATA_NOT_EXIST,
            ], RestController::HTTP_NOT_FOUND);
        }
    }
}
