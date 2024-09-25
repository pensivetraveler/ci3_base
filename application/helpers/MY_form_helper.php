<?php
if ( ! function_exists('validation_errors_array'))
{
    /**
     * Validation Error Array
     *
     * @return	array
     */
    function validation_errors_array()
    {
        if (FALSE === ($OBJ =& _get_validation_object()))
        {
            return '';
        }

        return $OBJ->error_array();
    }
}

if ( ! function_exists('form_custom'))
{
    /**
     * Custom Input Field
     *
     * @param	mixed
     * @param	string
     * @return	string
     */
    function form_custom($data, $value): string
    {
        return '';
    }
}

if ( ! function_exists('form_options_by_field'))
{
    /**
     * form_options_by_field
     * @param $field
     * @return array
     */
    function form_options_by_field($field): array
    {
        switch ($field) {
            case 'contract_status' :
                return [
                    1 => '계약 중',
                    2 => '계약 종료',
                ];
            case 'enroll_status' :
                return [
                    1 => '수강대기',
                    2 => '수강중',
                    3 => '수강종료',
                ];
            case 'class_per_week' :
                return [
                    1 => '주 1회',
                    2 => '주 2회',
                    3 => '주 3회',
                    4 => '주 4회',
                    5 => '주 5회',
                    0 => '기타',
                ];
            case 'class_meridian' :
                return [
                    1 => '오전',
                    2 => '오후',
                ];
            case 'class_dow' :
                return [
                    1 => '월요일',
                    2 => '화요일',
                    3 => '수요일',
                    4 => '목요일',
                    5 => '금요일',
                    6 => '토요일',
                    0 => '일요일',
                ];
            case 'class_type' :
                return [
                    1 => '1:1',
                    2 => '1:2',
                    3 => '1:3',
                    4 => '1:4',
                    5 => '1:5',
                    0 => '기타',
                ];
            case 'referral_type' :
                return [
                    1 => '지인추천',
                    2 => '블로그',
                    3 => '인스타그램',
                    4 => '유튜브',
                    6 => '그 외 온라인광고',
                    7 => '오프라인광고',
                    8 => '가족추가입회',
                    0 => '기타',
                ];
            default :
                return [
                    1 => 'Option 1',
                    2 => 'Option 2',
                ];
        }
    }
}

if ( ! function_exists('get_group_field_name'))
{
    function get_group_field_name($group_attr, $group_name, $field_name, $index = 0): string
    {
        $list = [];
        if ($group_attr['envelope_name']) {
            $list[] = $group_name;
            if ($group_attr['group_repeater']) $list[] = $index;
            $list[] = $field_name;
        } else {
            $list[] = $field_name;
            if ($group_attr['group_repeater']) $list[] = $index;
        }
        return array_to_brackets($list);
    }
}

if ( ! function_exists('replace_field_name_index'))
{
    function replace_field_name_index($original, $group_attr, $group_name, $field_name, $index = 0): string
    {
        if ($group_attr['envelope_name']) {
            $regex = "/({$group_name}\\[)\\d+(\\]\\[{$field_name}\\])/";
            return preg_replace($regex, "$1$index$2", $original);
        } else {
            $regex = "/({$group_name}\\[)\\d+(\\])/";
            return preg_replace($regex, "$1$index$2", $original);
        }
    }
}

if ( ! function_exists('get_group_field_id'))
{
    function get_group_field_id($group_attr, $group_name, $field_name, $index = 0): string
    {
        $list = [];
        if ($group_attr['envelope_name']) {
            $list[] = $group_name;
            if ($group_attr['group_repeater']) $list[] = $index;
            $list[] = $field_name;
        } else {
            $list[] = $field_name;
            if ($group_attr['group_repeater']) $list[] = $index;
        }
        return array_to_hyphens($list);
    }
}

if ( ! function_exists('replace_field_id_index'))
{
    function replace_field_id_index($original, $group_attr, $group_name, $field_name, $index = 0): string
    {
        if ($group_attr['envelope_name']) {
            $regex = "/(-{$group_name}-)\\d+(-{$field_name})/";
            return preg_replace($regex, "$1$index$2", $original);
        } else {
            $regex = "/(-{$field_name}-)\\d+/";
            return preg_replace($regex, "$1$index", $original);
        }
    }
}