<?php
return [
    'frontend' => [
        'lemming/shrink/html' => [
            'target' => \Lemming\Shrink\Middleware\ShrinkOutput::class,
            'before' => [
                'typo3/cms-frontend/output-compression'
            ],
            'after' => [
                'typo3/cms-adminpanel/renderer'
            ]
        ]
    ]
];
