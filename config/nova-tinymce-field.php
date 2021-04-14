<?php

/**
 * Nova TinyMCE Configuration
 *
 * For more details, see: https://www.tiny.cloud/docs/configure/
 */
return [
    'options' => [
        'init' => [
            'allow_html_in_named_anchor' => false,
            'branding' => false,
            'extended_valid_elements' => 'a[href]',
            'image_caption' => true,
            'menubar' => false,
            'paste_as_text' => false,
            'paste_retain_style_properties' => false,
            'valid_elements' => '*',
        ],
        'plugins' => [
            'advlist autolink lists link image imagetools media',
            'paste code wordcount autoresize table',
        ],
        'toolbar' => [
            'formatselect forecolor | bold italic underline |
                 bullist numlist outdent indent | link image media insertmedialibrary | code',
        ],
        'apiKey' => env('TINYMCE_API_KEY', ''),
    ],
];
