<?php

return [
    // Public disk where save files
    'upload_disk' => 'public',

    // Path where saving files
    'files_path' => 'vc-files',

    // Path where saving tmp files
    'tmp_files_path' => 'tmp-vc-files',

    // Minimum time before purge tmp file (in seconds)
    'seconds_before_purge_tmp_files' => (3600 * 24),

    // Installed and available templates to show in crud
    'templates' => [
        \Novius\NovaVisualComposer\Templates\Article::class,
        \Novius\NovaVisualComposer\Templates\ImageMultiple::class,
        \Novius\NovaVisualComposer\Templates\ImageSimple::class,
        \Novius\NovaVisualComposer\Templates\Separator::class,
        \Novius\NovaVisualComposer\Templates\Title::class,
    ],

    // Minimum time before purge filepond thumbs
    'filepond_thumb_cache_duration' => (3600 * 24),

    // Thumbs with in pixels
    'filepond_thumbs_width' => 150,
];
