<?php

return [
    // Which theme you want to use. You can find all of the themes at
    // https://torchlight.dev/themes.
    // 'theme' => 'serendipity-light',
    'theme' => 'dark-404',

    // Your API token from torchlight.dev. You can set it as an ENV variable
    // (shown below), or just hardcode it if your repo is private.
    'token' => env('TORCHLIGHT_TOKEN'),

    // No blade components for an Ibis book.
    'blade_components' => true,

    // The Host of the API.
    'host' => 'https://api.torchlight.dev',

    // If you want to specify the cache path, you can do so here.
    'cache_path' => 'cache',

    // Because of the way Ibis works as a static generator, the code
    // blocks for an entire chapter will be sent as one request. We
    // increase the timeout to 15 seconds to cover for that.
    'request_timeout' => 30,

    // Global options to control blocks-level settings.
    // https://torchlight.dev/docs/options
    'options' => [
        // Turn line numbers on or off globally.
        'lineNumbers' => false,

        // Control the `style` attribute applied to line numbers.
        // 'lineNumbersStyle' => '',

        // To generate the PDF from HTML Ibis uses mPDF, which does not
        // support treating spans as block level elements. That means
        // we're unable to set right margin for line numbers and git
        // diffs, so we instruct the API to add an extra space
        // to create some breathing room.
        'lineNumberAndDiffIndicatorRightPadding' => 2,

        // Turn on +/- diff indicators.
        'diffIndicators' => true,

        // If there are any diff indicators for a line, put them
        // in place of the line number to save horizontal space.
        'diffIndicatorsInPlaceOfLineNumbers' => true,
    ]
];
