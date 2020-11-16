<?php

use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\RemoteAddr;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Cache\Storage\Adapter\Filesystem;

return [
    'session_config' => [
        'cookie_lifetime' => 60 * 60 * 1,
        'gc_maxlifetime' => 60 * 60 * 24 * 30,
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'caches' => [
        'FilesystemCache' => [
            'adapter' => [
                'name'    => Filesystem::class,
                'options' => [
                    'cache_dir' => './data/cache',
                    'ttl' => 60*60*1 
                ],
            ],
            'plugins' => [
                [
                    'name' => 'serializer',
                    'options' => [                        
                    ],
                ],
            ],
        ],
    ],
];
