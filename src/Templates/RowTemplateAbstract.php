<?php

namespace Novius\NovaVisualComposer\Templates;

use Novius\NovaVisualComposer\Helpers\Templates;

abstract class RowTemplateAbstract
{
    public static $name = 'Unknown row type';

    public static $description = 'This row type doesn’t have a description attached';

    /**
     * @return array
     * @throws \Exception
     */
    public static function details(): array
    {
        $templateDetails = Templates::template(get_called_class());

        if (empty($templateDetails)) {
            throw new \Exception('Unable to get template details.');
        }

        return $templateDetails;
    }

    public static function renderCrud()
    {
        $templateDetails = Templates::template(get_called_class());

        if (empty($templateDetails)) {
            throw new \Exception('Unable to render CRUD.');
        }

        return view(
            'nova-visual-composer::templates.'.class_basename(get_called_class()).'.crud',
            [
                'template' => get_called_class(),
                'templateName' => static::$name,
                'templateDetails' => $templateDetails,
            ]
        );
    }

    public static function renderFront(string $content)
    {
        $content = json_decode($content);
        if (empty($content) || json_last_error() !== JSON_ERROR_NONE) {
            $content = [];
        }

        return view(
            'nova-visual-composer::templates.'.class_basename(get_called_class()).'.front',
            [
                'content' => collect($content)->pluck('content', 'name')->all(),
            ]
        );
    }

    public static function beforeSave($content)
    {
        return $content;
    }

    /**
     * Prune files if needed on Model deletion
     */
    public static function prune(array $content): bool
    {
        return true; // You can implement your deletion logic here
    }
}
