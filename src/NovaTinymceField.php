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

        $options = config('nova.nova-tinymce-field.options');

        // If we have shortcodes in the config, build out the panels for our
        // custom shortcodes plugin
        if (!empty($options['shortcodes'])) {
            $options['shortcodes'] = $this->buildShortcodePanels($options['shortcodes']);
        }

        $this->withMeta([
            'options' => $options,
        ]);
    }

    public function withFiles(string $disk = null, string $path = '/')
    {
        $this->withFiles = true;

        $this->disk($disk)->path($path);

        return $this;
    }

    /**
     * Takes an array of shortcode classes and converts them to individual
     * panels to be consumed by our custom TinyMCE shortcode plugin
     *
     * @param string[] $shortcodes - an array of fully-qualified class names
     *
     * @return array
     */
    private function buildShortcodePanels(array $shortcodes): array
    {
        $panels = array_map(
            fn($shortcodeClass) => $shortcodeClass::getWysiwygPanels() ?? null,
            $shortcodes
        );

        // return array with null values removed
        return array_filter($panels);
    }
}
