<?php

namespace Novius\NovaVisualComposer\Templates;

use Novius\NovaVisualComposer\Traits\HasImageField;
use Novius\NovaVisualComposer\Traits\HasPrunableFiles;

class ImageSimple extends RowTemplateAbstract
{
    use HasImageField;
    use HasPrunableFiles;

    public static $name = 'image-simple';

    protected static function imageFieldsIndexes(): array
    {
        return ['images'];
    }
}
