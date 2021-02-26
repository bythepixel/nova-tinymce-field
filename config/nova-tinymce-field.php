<?php

return [
    'options' => [
        'init' => [
            'menubar' => false,
            'branding' => false,
            'image_caption' => true,
            'paste_as_text' => true,
            'paste_word_valid_elements' => 'b,strong,i,em,h1,h2',
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
