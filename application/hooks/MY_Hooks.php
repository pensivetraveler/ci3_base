<?php

class MY_Hooks
{
    protected array $config;

    function __construct()
    {
        $this->config =& get_config();
    }

    public function getConfig()
    {
        print_r('<pre>');
        print_r($this->config);
        exit;
    }

    public function setLifeCycle($param)
    {
        $CI =& get_instance();
        $CI->config->set_item('life_cycle', $param['life_cycle']);
    }

    public function loadEnv()
    {
        $dotenv = new Symfony\Component\Dotenv\Dotenv();
        $dotenv->usePutenv();
		if(getenv('CI_ENV') && file_exists(FCPATH.'.env.'.getenv('CI_ENV')))
        	$dotenv->load(FCPATH.'.env.'.getenv('CI_ENV'));
    }

    public function SystemOfInspection()
    {
        if(getenv('SYSTEM_INSPECTION') === 'true') {
            echo '시스템 점검 중입니다.';
            exit;
        }
    }

    public function setPearPath()
    {
        // on Apache
        ini_set ( 'include_path', ini_get ( 'include_path' ) . ':' . BASEPATH . 'application/web/pear/' );
    }

    public function checkPermission()
    {
        $CI =& get_instance();

        if(!isset($CI->session)){  //Check if session lib is loaded or not
            $CI->load->library('session');  //If not loaded, then load it here
        }

        if(!isset($CI->PHPtoJS)){  //Check if session lib is loaded or not
            $CI->load->library('PHPtoJS', $CI->config->item('phptojs.namespace')?['namespace' => $CI->config->item('phptojs.namespace')]:[]);  //If not loaded, then load it here
        }

        if (
            isset($CI->noLoginAllow)
            && (is_array($CI->noLoginAllow) === false
                || in_array($CI->router->method, $CI->noLoginAllow) === false)
        ) {
            // 로그인을 했는지 판단을 하는 로직을 넣으면 되겠죠.
            if (1) {
                // redirect url도 알아서...
//                redirect('/account/signin?next=' . urlencode($CI->uri->ruri_string()));
            }
        }
    }

    public function setPHPVars()
    {
        $CI =& get_instance();
        $token_prefix = $CI->config->item('token_prefix')?$CI->config->item('token_prefix').' ':'';

        $data = [
            'BASE_URI' => base_url(),
            'CURRENT_URI' => base_url().get_path().'/'.$CI->router->class,
            'HOOK_PHPTOJS_VAR_ISLOGIN' => $CI->session->userdata('logged_in'),
            'HOOK_PHPTOJS_VAR_TOKEN' => $token_prefix.$CI->session->userdata('token'),
            'HOOK_PHPTOJS_VAR_DIALOG' => $CI->session->flashdata('dialog'),
        ];

        if(property_exists($CI, 'jsVars')) $data = array_merge($data, $CI->jsVars);
        if(property_exists($CI, 'lang')) $data = array_merge($data, [
            'LOCALE' => $CI->lang->language
        ]);

        if(!array_key_exists('ERRORS', $data)) $data['ERRORS'] = [];

        $CI->phptojs->put($data);
    }

    public function setFormValidation()
    {
        $CI =& get_instance();

        foreach ($CI->config->item('regexp') as $name => $regexp)
            if(!method_exists($CI->form_validation, $name))
                $CI->form_validation->addMethod($name, $regexp);
    }

    public function setHeaderSecure()
    {
        // Get CI instance
        $CI =& get_instance();

        // Only allow HTTPS cookies (no JS)
        $CI->config->set_item('cookie_secure', TRUE);
        $CI->config->set_item('cookie_httponly', TRUE);

        // Set headers
        $CI->output->set_header("Strict-Transport-Security: max-age=31536000")
            ->set_header("X-Content-Type-Options: nosniff")
            ->set_header("Referrer-Policy: strict-origin")
            ->set_header("X-Frame-Options: DENY")
            ->set_header("X-XSS-Protection: 1; mode=block");
    }

    public function doYield()
    {
        global $OUT;
        $CI =& get_instance();
        $output = $CI->output->get_output();
        $CI->yield = isset($CI->yield) ? $CI->yield : TRUE;
        $CI->layout = isset($CI->layout) ? $CI->layout : 'default';
        if ($CI->yield === TRUE) {
            if (!preg_match('/(.+).php$/', $CI->layout)) {
                $CI->layout .= '.php';
            }
            $requested = APPPATH . 'views/layouts/' . $CI->layout;
            $layout = $CI->load->file($requested, true);
            $view = str_replace("{yield}", $output, $layout);
        } else {
            $view = $output;
        }
        $OUT->_display($view);
    }

    function compress()
    {
        ini_set("pcre.recursion_limit", "16777");
        $CI =& get_instance();
        $buffer = $CI->output->get_output();

        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

        $new_buffer = preg_replace($re, " ", $buffer);

        // We are going to check if processing has working
        if ($new_buffer === null)
        {
            $new_buffer = $buffer;
        }

        $CI->output->set_output($new_buffer);
        $CI->output->_display();
    }
}
