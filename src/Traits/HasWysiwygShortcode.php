<?php

namespace Bythepixel\NovaTinymceField\Traits;

use Bythepixel\NovaTinymceField\Models\Panel;

/**
 * Adds two helper methods for generating TinyMCE dialog panels for our
 * custom shortcode plugin. The class consuming this trait should include
 * several public static properties:
 *
 *  -> $name (the shortcode's display name)
 *  -> $slug
 *  -> $attributes (a non-associative array of attributes required by the shortcode)
 *
 */

trait HasWysiwygShortcode
{
    /**
     * Builds the core dialog panel for the shortcode plugin
     */
    public static function getWysiwygPanels(): array
    {
        return [
            'tabTitle' => self::$name,
            'slug' => self::$slug,
            'panels' => self::buildAttributePanels()
        ];
    }

    /**
     * Builds the individual panels for each attribute required by the shortcode
     */
    private static function buildAttributePanels(): array
    {
        if (empty(self::$attributes)) {
            throw new \Exception("No attributes defined for " . self::class);
        }

        return array_map(function($attribute) {
            $panel = new Panel($attribute, self::$name);
            return $panel->toArray();
        }, self::$attributes);
    }
}
