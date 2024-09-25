<?php
// --------------------------------------------------------------------

if ( ! function_exists('format_phone'))
{
    function format_phone($phone)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $length = strlen($phone);
        switch($length){
            case 11 :
                return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
            case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            default :
                return $phone;
        }
    }
}

// --------------------------------------------------------------------

if ( ! function_exists('format_date'))
{
    function format_date($birth)
    {
        return date('Y-m-d', strtotime($birth));
    }
}