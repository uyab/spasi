<?php

require_once __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    /**
     * The book title.
     */
    'title' => 'Spasi',

    /**
     * The author name.
     */
    'author' => 'Uyab',

    /**
     * The list of fonts to be used in the different themes.
     */
    'fonts' => [
        'heading1' => 'AmaticSC-Regular.ttf',
        'heading2' => 'AmaticSC-Regular.ttf',
        'heading3' => 'Inter-Bold.ttf',
        'heading4' => 'Inter-Bold.ttf',
        'body' => 'Inter.ttf',
        'monospace' => 'JetBrainsMono.ttf',
        'quote' => 'JetBrainsMono.ttf',
    ],

    /**
     * Document Dimensions.
     */
    'document' => [
        'format' => [210, 297],
        'margin_left' => 27,
        'margin_right' => 27,
        'margin_bottom' => 14,
        'margin_top' => 14,
    ],

    /**
     * Cover photo position and dimensions
     */
    'cover' => [
        'position' => 'position: absolute; left:0; right: 0; top: -.2; bottom: 0;',
        'dimensions' => 'width: 210mm; height: 297mm; margin: 0;',
    ],

    /**
     * Page ranges to be used with the sample command.
     */
    'sample' => [
        [1, 3],
        [80, 85],
        [100, 103],
    ],

    'toc_levels' => [
        'H1' => 0,
        'H2' => 1,
    ],
    /**
     * A notice printed at the final page of a generated sample.
     */
    'sample_notice' => 'Ini adalah contoh isi buku "Spasi" oleh Uyab. <br> 
                        <a href="https://theboringstack.com/">Klik disini untuk info lengkap</a>.',

    'configure_commonmark' => function ($environment) {
        \Torchlight\Ibis\TorchlightExtension::make()->register($environment);
    },
];
