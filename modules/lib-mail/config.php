<?php

return [
    '__name' => 'lib-mail',
    '__version' => '0.1.0',
    '__git' => 'git@github.com:getmim/lib-mail.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'modules/lib-mail' => ['install','update','remove'],
        'theme/mail' => ['install','remove']
    ],
    '__dependencies' => [
        'required' => [],
        'optional' => [
            [
                'lib-mail-phpmailer' => NULL
            ]
        ]
    ],
    'autoload' => [
        'classes' => [
            'LibMail\\Iface' => [
                'type' => 'file',
                'base' => 'modules/lib-mail/interface'
            ],
            'LibMail\\Library' => [
                'type' => 'file',
                'base' => 'modules/lib-mail/library'
            ]
        ],
        'files' => []
    ],
    'libMail' => []
];