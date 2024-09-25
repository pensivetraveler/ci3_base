<?php
// upload folder 만들기
function make_directory($path): bool
{
    $path_list = explode(DIRECTORY_SEPARATOR, $path);
    $total_path = '';
    $result = true;
    for($i = 0; $i < count($path_list); $i++) {
        if(!$path_list[$i]) continue;

        $total_path .= ($total_path?'/':'').$path_list[$i];
        if(is_dir($total_path)) {
            continue;
        }else{
            $result = @mkdir($total_path, DIR_READ_MODE);
            if(!$result) continue;
        }
    }
    return $result;
}