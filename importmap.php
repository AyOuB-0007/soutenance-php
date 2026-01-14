<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    // Manually added entry for product_carousel Stimulus controller
    'product_carousel' => [
        'path' => './assets/controllers/product_carousel_controller.js',
        'entrypoint' => true,
    ],
    // Modern animations controller
    'modern_animations' => [
        'path' => './assets/controllers/modern_animations_controller.js',
        'entrypoint' => true,
    ],
];