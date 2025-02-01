<?php

namespace Jengo\Config;

use CodeIgniter\Config\BaseConfig;

class Jengo extends BaseConfig
{
    public string $releaseVersion = "1.0.0";

    public ?object $user_model = null;

    public array $libraries = [
        'abac' => false,
        'notification' => false,
        'payment' => false,
    ];

    public array $views = [
        'page' => '\Jengo\Views\Jengo\templates\page',
        'layout' => '\Jengo\Views\Jengo\templates\layout',
    ];

    public array $validationRules = [
        'file_name' => [
            'label' => "File name",
            'rules' => 'if_exist|alpha_numeric_space',
            'errors' => [
                'alpha_numeric_space' => 'The {field} must only contain letters, numbers and spaces.',
            ]
        ]
    ];
}
