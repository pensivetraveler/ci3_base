<?php
if ( ! function_exists('escstr'))
{
    function escstr($str): string
    {
        $str=str_replace("\r\n","",$str);
        return trim($str);
    }
}


if ( ! function_exists('print_data'))
{
    function print_data($data, $exit = true)
    {
        print_r('<pre>');
        print_r($data);
        print_r('</pre>');
        if($exit) exit;
    }
}

if ( ! function_exists('is_list_type'))
{
    function is_list_type($data): bool
    {
        if(!empty($data) && count($data) > 0) {
            $keys = array_keys($data);
            return array_reduce($keys, function($result, $key) {
                return $result && is_numeric($key);
            }, true);
        }else{
            return true;
        }
    }
}

if ( ! function_exists('is_empty'))
{
    function is_empty($data, $key = ''): bool
    {
        if(is_null($data)) return true;
        if(!trim($key)) {
            switch(gettype($data)) {
                case 'boolean' :
                    return !$data;
                case 'object' :
                    $data = get_object_vars($data);
                case 'array' :
                    if(empty($data)) return true;
                    break;
                default :
                    // 0 을 구분하기 위해 strlen 조건 추가
                    if(empty($data) && strlen((string)$data) === 0) return true;
            }
            return false;
        }else{
            switch (gettype($data)) {
                case 'object' :
                    if(!property_exists($data, $key)) return true;
                    return is_empty($data->{$key});
                case 'array' :
                    if(!array_key_exists($key, $data)) return true;
                    return is_empty($data[$key]);
                default :
                    return true;
            }
        }
    }
}

if ( ! function_exists('get_yn'))
{
    function get_yn($bool): string
    {
        return $bool?'Y':'N';
    }
}


// --------------------------------------------------------------------

if ( ! function_exists('array_to_brackets'))
{
    function array_to_brackets($array): string
    {
        $result = array_shift($array);
        foreach ($array as $value) {
            $result .= "[$value]";
        }
        return $result;
    }
}

// --------------------------------------------------------------------

if ( ! function_exists('array_to_hyphens'))
{
    function array_to_hyphens($array): string
    {
        return implode('-', $array);
    }
}