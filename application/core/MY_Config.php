<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Config extends CI_Config
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($item, $default = null)
    {
        return $this->item($item) === null?$default:$this->item($item);
    }

    // Config 값을 가져오는 메서드 추가
    public function item($item, $index = '')
    {
        if(strpos($item, '.') === false || substr($item, -1, 1) === '.'){
            return parent::item($item, $index);
        }else{
            $config = $this->config;
            // 키를 '.'을 기준으로 분리하여 배열 형태로 만듭니다.

            $items = explode('.', $item);

            foreach ($items as $key) {
                if (isset($config[$key])) {
                    $config = $config[$key];
                } else {
                    // 해당 키가 존재하지 않으면 기본값을 반환
                    $config = null;
                    break;
                }
            }

            return $config;
        }
    }
}
