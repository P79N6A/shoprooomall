<?php

return [
    'app_begin' => [
        'App\Behavior\SaaSServiceBehavior',
    ],
    'frontend_init' => [
        'App\Behavior\ReplaceLangBehavior',
    ],
    'template_replace' => [
        'App\Behavior\ParseTemplateBehavior',
    ],
    'view_filter' => [
        'Behavior\TokenBuildBehavior'
    ]
];
