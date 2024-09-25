<?php
$config['admin_menu'] = [
    'Dashboard' => [
        'icon' => 'ri-home-smile-line',
        'title' => 'Home',
        'route' => '/admin/dashboard',
        'method' => 'dashboard',
        'router' => 'index',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [],
    ],
    'Administrators' => [
        'icon' => 'ri-team-line',
        'title' => 'Administrators Management',
        'route' => '',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'SuperAdministrators' => [
                'icon' => '',
                'title' => 'Super Administrators Management',
                'route' => '/admin/administrators',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 1,
                ],
                'className' => [],
            ],
            'Managers' => [
                'icon' => '',
                'title' => 'Managers Management',
                'route' => '/admin/managers',
                'params' => [
                    'layout' => 'side-menu',
                ],
                'className' => [],
            ],
        ],
    ],
    'Branches' => [
        'icon' => 'ri-community-line',
        'title' => 'Branches Management',
        'route' => '/admin/branches',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'SingingCrayon' => [
                'icon' => '',
                'title' => 'Singing Crayon',
                'route' => '/admin/branches',
                'params' => [
                    'layout' => 'side-menu',
                    'branch_cd' => 'BRN001',
                ],
                'className' => [],
            ],
            'PianoBranch' => [
                'icon' => '',
                'title' => 'Piano Branch',
                'route' => '/admin/branches',
                'params' => [
                    'layout' => 'side-menu',
                    'branch_cd' => 'BRN002',
                ],
                'className' => [],
            ],
            'PianoCenter' => [
                'icon' => '',
                'title' => 'Piano Center',
                'route' => '/admin/branches',
                'params' => [
                    'layout' => 'side-menu',
                    'branch_cd' => 'BRN003',
                ],
                'className' => [],
            ],
        ],
    ],
    'Students' => [
        'icon' => 'ri-graduation-cap-line',
        'title' => 'Students Management',
        'route' => '',
        'params' => [],
        'className' => [],
        'subMenu' => [
            'Students Info' => [
                'icon' => '',
                'title' => 'Students Info',
                'route' => '/admin/students',
                'params' => [
                    'layout' => 'side-menu',
                ],
                'className' => [],
            ],
            'Enrolls Info' => [
                'icon' => '',
                'title' => 'Enrolls Info',
                'route' => '/admin/enrolls',
                'params' => [
                    'layout' => 'side-menu',
                ],
                'className' => [],
            ],
        ],
    ],
    'Chapters' => [
        'icon' => 'ri-file-copy-2-line',
        'title' => 'Chapters Management',
        'route' => '/admin/chapters',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [],
    ],
    'Stages' => [
        'icon' => 'ri-book-3-line',
        'title' => 'Stages Management',
        'route' => '/admin/stages',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [],
    ],
    'EpMagazine' => [
        'icon' => 'ri-folder-image-line',
        'title' => 'epMagazine Management',
        'route' => '',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'Knock' => [
                'icon' => '',
                'title' => 'Knock Management',
                'route' => '/admin/knock',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 0,
                ],
                'className' => [],
            ],
            'EpMagazine' => [
                'icon' => '',
                'title' => 'epMagazine Management',
                'route' => '/admin/epMagazine',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 1,
                ],
                'className' => [],
            ],
        ],
    ],
    'EpCoaching' => [
        'icon' => 'ri-folder-chart-2-line',
        'title' => 'epCoaching',
        'route' => '',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'Activities' => [
                'icon' => '',
                'title' => 'Activities Management',
                'route' => '/admin/activities',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 1,
                ],
                'className' => [],
            ],
            'Reports' => [
                'icon' => '',
                'title' => 'Reports Management',
                'route' => '/admin/reports',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 0,
                ],
                'className' => [],
            ],
        ],
    ],
    'EpFactory' => [
        'icon' => 'ri-folder-music-line',
        'title' => 'epFactory',
        'route' => '',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'PlaySheet' => [
                'icon' => '',
                'title' => 'PlaySheet Management',
                'route' => '/admin/playSheets',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 1,
                ],
                'className' => [],
            ],
            'EpCollection' => [
                'icon' => '',
                'title' => 'epCollection Management',
                'route' => '/admin/epCollection',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 0,
                ],
                'className' => [],
            ],
        ],
    ],
    'EpNews' => [
        'icon' => 'ri-folder-warning-line',
        'title' => 'epNews',
        'route' => '/admin/epNews',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'Notices' => [
                'icon' => '',
                'title' => 'Notices Management',
                'route' => '/admin/notices',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 1,
                ],
                'className' => [],
            ],
            'Events' => [
                'icon' => '',
                'title' => 'Events Management',
                'route' => '/admin/events',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 0,
                ],
                'className' => [],
            ],
        ],
    ],
    'Messages' => [
        'icon' => 'ri-message-2-line',
        'title' => 'Messages Management',
        'route' => '',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'MessageGroups' => [
                'icon' => '',
                'title' => 'Message Groups Management',
                'route' => '/admin/messageGroups',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 1,
                ],
                'className' => [],
            ],
            'Messages' => [
                'icon' => '',
                'title' => 'Messages Management',
                'route' => '/admin/messages',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 0,
                ],
                'className' => [],
            ],
        ],
    ],
    'System' => [
        'icon' => 'ri-function-line',
        'title' => 'System',
        'route' => '',
        'params' => [
            'layout' => 'side-menu',
        ],
        'className' => [],
        'subMenu' => [
            'manuals' => [
                'icon' => '',
                'title' => 'Manuals Management',
                'route' => '/admin/manuals',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 1,
                ],
                'className' => [],
            ],
            'version' => [
                'icon' => '',
                'title' => 'Versions Management',
                'route' => '/admin/versions',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 0,
                ],
                'className' => [],
            ],
            'inquires' => [
                'icon' => '',
                'title' => 'Inquiries Management',
                'route' => '/admin/inquiries',
                'params' => [
                    'layout' => 'side-menu',
                    'isSuper' => 0,
                ],
                'className' => [],
            ],
        ],
    ],
];