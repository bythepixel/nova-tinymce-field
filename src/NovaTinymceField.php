<?php

namespace Bythepixel\NovaTinymceField;

use Laravel\Nova\Contracts\Storable as StorableContract;
use Laravel\Nova\Fields\{
    Field,
    Storable
};

class NovaTinymceField extends Field implements StorableContract
{
    use Storable;

    public $component = 'nova-tinymce-field';

    public function __construct(string $name, $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->withMeta([
            'options' => config('nova.nova-tinymce-field.options'),
        ]);
    }

    public function withFiles(string $disk = null, string $path = '/')
    {
        $this->withFiles = true;

        $this->disk($disk)->path($path);

        return $this;
    }
}
