<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['form_side_prefix'] = 'form_side-';
$config['form_page_prefix'] = 'form_page-';

$config['base_form_config'] = [
    [
        'field' => 'field',
        'label' => 'lang:field',
        'form' => true,
        'rules' => 'trim|required',
        'errors' => [
            'required' => 'Enter the field'
        ],
        'category' => 'basic',
        'type' => 'text',
        'subtype' => 'basic',
        'default' => 'sample',
        'icon' => 'ri-user-line',
        'form_text' => '영문, 숫자를 포함한 4글자 이상으로 입력해주세요.',
        'attributes' => [
            'autocapitalize' => 'none',
            'autocomplete' => 'off',
            'placeholder' => '아이디를 입력하세요',
        ],
        'form_attributes' => [
            'view_mod' => '',
            'with_btn' => true,
            'btn_type' => 'dup_check',
            'btn_params' => '{"key":"id", "title":"아이디"}',
            'text_type' => 'eng|num',
        ],
        'option_attributes' => [
            'option_type' => 'db',
            'option_data' => [
                'table' => 'program',
                'params' => [],
            ],
            'render' => [
                'id' => 'program_id',
                'text' => 'program_name',
            ],
        ],
        'group_key' => '',
        'group_attributes' => [
            'label' => 'lang:user.password',
            'form_text' => '',
            'type' => 'new_password',
        ],
        'list' => true,
        'list_attributes' => [
            'format' => 'img',
            'icon' => 'ri-file-fill',
            'render' => [
                'callback' => 'articleListRender',
                'params' => [
                    'article_cd' => 'ARC004',
                ]
            ]
        ]
    ],
];
