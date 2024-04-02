<?php

namespace Bythepixel\NovaTinymceField\Models;

use Illuminate\Support\Str;

class Panel
{
    /**
     * The panel's <input> label
     */
    public string $label;

    public function __construct(
        public string $attribute,
        public string $labelPrefix = "",
    ){
        $this->label = $this->setLabel();
    }

    /**
     * Creates a title-cased version of $attribute
     */
    private function setLabel(): string
    {
        // Converts an attribute name to a title-cased string
        // ex: "foo_bar" => "Foo Bar"
        $formattedAttributeName = array_map(
            fn($str) => Str::title($str),
            explode("_", $this->attribute)
        );

        $joinedAttribute = join(" ", $formattedAttributeName);

        if (!$this->labelPrefix) {
            return $joinedAttribute;
        }

        return "{$this->labelPrefix} {$joinedAttribute}";
    }

    public function toArray(): array
    {
        return [
            'name' => $this->attribute,
            'label' => $this->label,
        ];
    }
}
