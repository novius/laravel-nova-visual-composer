<?php

namespace Novius\NovaVisualComposer\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Templates
{
    /**
     * Get all template details
     *
     * @return \Illuminate\Support\Collection
     */
    public static function templates(): Collection
    {
        $templates = [];
        foreach (config('nova-visual-composer.templates') as $templateClass) {

            $nameTranslated = trans("nova-visual-composer::templates.{$templateClass::$name}.name");
            if (Str::startsWith($nameTranslated, 'nova-visual-composer::templates.')) {
                // Fallback to original name if no translation found
                $nameTranslated = $templateClass::$name;
            }

            $templates[] = [
                'name' => $templateClass::$name,
                'name_trans' => $nameTranslated,
                'description' => $templateClass::$description,
                'classname' => $templateClass,
            ];
        }

        return collect($templates);
    }

    /**
     * Get template details
     *
     * @param string $className
     * @return array|null
     */
    public static function template(string $className): ?array
    {
        $templates = static::templates();

        return $templates->firstWhere('classname', $className);
    }
}
