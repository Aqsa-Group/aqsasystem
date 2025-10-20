<?php

return [
    'default' => 'gd',
    'drivers' => [
        'gd' => [
            'renderer' => BaconQrCode\Renderer\ImageRenderer::class,
            'options' => [
                'width' => 256,
                'height' => 256,
            ],
        ],
    ],
];
