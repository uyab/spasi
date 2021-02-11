<?php

return [
    /**
     * The book title.
     */
    'title' => 'Spasi di Bulan Juli',

    /**
     * The author name.
     */
    'author' => 'Uyab',

    /**
     * The list of fonts to be used in the different themes.
     */
    'fonts' => [
        'heading1' => 'Inter-SemiBold.ttf',
        'heading2' => 'Inter-SemiBold.ttf',
        'heading3' => 'Inter-Bold.ttf',
        'heading4' => 'Inter-Bold.ttf',
        'body' => 'Inter.ttf',
        // 'body' => 'NotoSerif-Regular.ttf',
        // 'body' => 'Literata.ttf',
        // 'monospace' => 'FiraCode.ttf',
        'monospace' => 'JetBrainsMono.ttf',
        'quote' => 'IBMPlexMono-LightItalic.ttf',
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

    /**
     * A notice printed at the final page of a generated sample.
     */
    'sample_notice' => 'Ini adalah contoh isi buku "Spasi di Bulan Juli" oleh Uyab. <br> 
                        <a href="https://theboringstack.com/">Klik disini untuk info lengkap</a>.',
];
