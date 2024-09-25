<?php
function get_site_title($site_name, $title_list = [])
{
    if(isset($title_list) && count($title_list) > 0){
        return implode(' < ', array_reverse($title_list)).' - '.$site_name;
    }else{
        return $site_name;
    }
}

function is_active_page($nav_link, $params = [], $current_uri = '')
{
    if(!$current_uri) $current_uri = $_SERVER['PATH_INFO'];
    if(count($params) > 0) {
        $queries = array_reduce(explode('&', $_SERVER['QUERY_STRING']),
            function($carry, $item) {
                if(!is_empty($item)) {
                    $data = explode('=', $item);
                    $carry[$data[0]] = $data[1];
                }
                return $carry;
            }, []);
        return $current_uri === $nav_link && empty(array_diff_assoc($params, $queries));
    }else{
        return $current_uri === $nav_link;
    }
}

function add_stylesheet($list)
{
    foreach ($list as $item) {
        if(is_array($item)) {
            foreach ($item as $subitem) echo '<link type="text/css" rel="stylesheet" media="all" href="' . $subitem . '">'."\n";
        }else{
            echo '<link type="text/css" rel="stylesheet" media="all" href="' . $item . '">'."\n";
        }
    }
}

function add_javascript($list)
{
    foreach ($list as $item) {
        if(is_array($item)) {
            foreach ($item as $subitem) echo '<script type="text/javascript" src="' . $subitem . '"></script>'."\n";
        }else{
            echo '<script type="text/javascript" src="' . $item . '"></script>'."\n";
        }
    }
}

function alert_back($msg)
{
    $script = "<script>".PHP_EOL;
    if($msg) $script .= "alert('{$msg}');".PHP_EOL;
    $script .= "history.back();".PHP_EOL;
    $script .= "</script>".PHP_EOL;
    echo $script;exit;
}

function get_redirect_script($link): string
{
    return "location.href='$link'";
}

function get_sample_img_src($width = 200, $height = 0, $option = []): string
{
    $src = "https://picsum.photos/$width";
    if($height > 0) $src .= "/$height";
    if(count($option) > 0) $src .= '?'.implode('&', $option);
    return $src;
}

function get_sample_img($width = 200, $height = 0, $option = [], $classname = ''): string
{
    $src = get_sample_img_src($width, $height, $option);
    if(is_array($classname)) $classname = implode(' ', $classname);
    return img($src, [ 'class' => $classname ]);
}

function get_icon($icon_class_name, $svg = false, $size = 18, $classname = ''): string
{
    if(!$icon_class_name) return '';
    if($classname) {
        if(is_array($classname)) {
            $classname = implode('.', $classname);
        }else{
            $classname = str_replace(' ', '.', $classname);
        }
        if($classname && substr($classname, 0, 1) === '.') $classname = substr($classname, 1);
    }
    if(!$size) $size = 18;
    return $svg?file_get_contents(ADMIN_ASSET_SVG_PATH.'icons/'.$icon_class_name.'.svg'):convert_selector_to_html("i.$icon_class_name.$classname.ri-{$size}px");
}

function get_icon_by_type($type, $svg = false, $size = 18): string
{
    $classname = get_icon_classname_by_type($type);
    return get_icon($classname, $svg, $size);
}

function get_icon_classname_by_type($type): string
{
    switch (strtolower($type)) {
        case "file" :
            return 'ri-attachment-line';
        case "zipcode" :
            return 'ri-building-line';
        case "text" :
            return 'ri-text';
        case "tel" :
            return 'ri-phone-fill';
        case "textarea" :
            return 'ri-chat-4-line';
        case "date" :
            return 'ri-calendar-line';
        case "pdf" :
            return 'ri-file-pdf-2-fill';
        default :
            return '';
    }
}

function convert_selector_to_html($selector, $wrap = true, $child_html = '')
{
    // 클래스 및 ID 패턴을 처리하는 정규식
    $regex = '/([.#])?([a-zA-Z0-9_-]+)/';

    // 속성 패턴을 처리하는 정규식 (예: [attr] 또는 [attr="value"])
    $attrRegex = '/\[([a-zA-Z0-9_-]+)(?:=\"([^\"]*)\")?\]/';

    // 태그 이름 추출 (태그가 명시되지 않으면 div로 기본 설정)
    preg_match('/^[a-zA-Z0-9]+/', $selector, $matches);
    $tagName = $matches ? $matches[0] : 'div';

    // 나머지 클래스, ID, 속성 추출
    preg_match_all($regex, $selector, $matches, PREG_SET_ORDER);
    preg_match_all($attrRegex, $selector, $attrMatches, PREG_SET_ORDER);

    $id = '';
    $class = [];
    $attributes = [];

    // 클래스와 ID 처리
    foreach ($matches as $match) {
        if ($match[1] == '#') {
            $id = $match[2]; // ID
        } elseif ($match[1] == '.') {
            $class[] = $match[2]; // 클래스
        }
    }

    // 속성 처리
    foreach ($attrMatches as $attrMatch) {
        $attrName = $attrMatch[1];
        $attrValue = isset($attrMatch[2]) ? $attrMatch[2] : null;
        $attributes[] = $attrValue ? "$attrName=\"$attrValue\"" : $attrName;
    }

    // HTML 조립
    $html = "<$tagName";

    if ($id) {
        $html .= " id=\"$id\"";
    }
    if ($class) {
        $html .= " class=\"" . implode(' ', $class) . "\"";
    }
    if ($attributes) {
        $html .= " " . implode(' ', $attributes);
    }

    if($wrap) {
        $html .= ">{$child_html}</$tagName>";
    }else{
        $html .= ">";
    }

    return $html;
}

function insert_html_inside_tag($parent_html, $child_html)
{
    // 정규식을 사용하여 태그의 닫는 부분을 찾는다
    $pattern = '/(<[^\/>]+>)(<\/[^>]+>)/';

    // 부모 태그의 닫는 태그 바로 앞에 자식 HTML을 삽입
    if (preg_match($pattern, $parent_html, $matches)) {
        return $matches[1] . $child_html . $matches[2];
    }

    // 태그 구조가 비정상적인 경우 그대로 반환
    return $parent_html;
}

function modify_html_attributes($html, $changes, $type) {
    foreach ($changes as $attribute => $newValue) {
        $pattern = '/(' . preg_quote($attribute) . '=")(.*?)(")/';

        if ($type === 'replace') {
            // Replace the entire attribute value
            $html = preg_replace($pattern, '$1' . $newValue . '$3', $html);
        } elseif ($type === 'append') {
            // Append the new value to the existing one
            $html = preg_replace_callback($pattern, function($matches) use ($newValue) {
                return $matches[1] . $matches[2] . ' ' . $newValue . $matches[3];
            }, $html);
        }
    }
    return $html;
}