<?php

namespace Novius\NovaVisualComposer\Templates;

class Separator extends RowTemplateAbstract
{
    public static $name = 'separator';

    public static function beforeSave($content)
    {
         return json_encode(['']);
    }
}
