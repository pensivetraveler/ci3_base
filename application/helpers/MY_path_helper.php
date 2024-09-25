<?php
if ( ! function_exists('get_filepath_from_link'))
{
    // path 로부터 link return
    function get_filepath_from_link($path): string
    {
        return base_url('/'.str_replace(FCPATH, '', $path));
    }
}

if ( ! function_exists('get_path'))
{
    function get_path(): string
    {
        $whole_uri = _HTTP.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $path_info = explode('/', str_replace(base_url(), '', $whole_uri));
        return empty($path_info[0])?'':array_values(array_filter($path_info))[0];
    }
}

if ( ! function_exists('get_error_views_path'))
{
    function get_error_views_path(): string
    {
        if(is_dir(VIEWPATH.get_path().DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR.'html')) {
            return VIEWPATH.get_path().DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR;
        }else{
            return '';
        }
    }
}