<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Upload extends CI_Upload
{
    function data($index = NULL)
    {
        $data = parent::data($index);
        $data['file_link'] = get_filepath_from_link($data['full_path']);
        return $data;
    }
}