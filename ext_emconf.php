<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Shrink',
    'description' => 'Compress text/html output',
    'category' => 'fe',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'author' => 'Christoph Lehmann',
    'author_email' => 'post@christophlehmann.eu',
    'author_company' => '',
    'version' => '0.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
];
