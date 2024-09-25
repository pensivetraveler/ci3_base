<?php
/**
 * TODO costom_constant 이후에 admin_constant가 선언될 수 있도록 조정이 필요. get_path method 이용해서 admin 관련 common class 에서 관련 helper, config, const를 별도 로드할 수 있도록 하는 것도 좋을 것으로 보임.
 * const 파일의 경우 아래와 같이 로드 가능
 * class Welcome extends CI_Controller {
 *     public function index() {
 *         $this->load->config('constant');  // constant.php 파일 로드
 *         echo SITE_NAME;  // 상수 출력
 *     }
 * }
 */

const ADMIN_ASSET_URI = 'public/assets/admin/';
const ADMIN_ASSET_PATH = FCPATH . ADMIN_ASSET_URI;

const ADMIN_ASSET_AUDIO_URI = ADMIN_ASSET_URI . 'audio/';
const ADMIN_ASSET_AUDIO_PATH = FCPATH . ADMIN_ASSET_AUDIO_URI;

const ADMIN_ASSET_CSS_URI = ADMIN_ASSET_URI . 'css/';
const ADMIN_ASSET_CSS_PATH = FCPATH . ADMIN_ASSET_CSS_URI;

const ADMIN_ASSET_IMG_URI = ADMIN_ASSET_URI . 'img/';
const ADMIN_ASSET_IMG_PATH = FCPATH . ADMIN_ASSET_IMG_URI;

const ADMIN_ASSET_JS_URI = ADMIN_ASSET_URI . 'js/';
const ADMIN_ASSET_JS_PATH = FCPATH . ADMIN_ASSET_JS_URI;

const ADMIN_ASSET_JSON_URI = ADMIN_ASSET_URI . 'json/';
const ADMIN_ASSET_JSON_PATH = FCPATH . ADMIN_ASSET_JSON_URI;

const ADMIN_ASSET_SVG_URI = ADMIN_ASSET_URI . 'svg/';
const ADMIN_ASSET_SVG_PATH = FCPATH . ADMIN_ASSET_SVG_URI;

const ADMIN_ASSET_VENDOR_URI = ADMIN_ASSET_URI . 'vendor/';
const ADMIN_ASSET_VENDOR_PATH = FCPATH . ADMIN_ASSET_VENDOR_URI;

const APP_AOS_URL = 'https://play.google.com/store/apps/details?id=com.engflip.www.engflip';
const APP_IOS_URL = 'https://apps.apple.com/kr/app/whatsapp-messenger/id310633997';
