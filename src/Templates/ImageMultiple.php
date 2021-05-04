<?php

namespace Novius\NovaVisualComposer\Templates;

use Novius\NovaVisualComposer\Traits\HasImageField;
use Novius\NovaVisualComposer\Traits\HasPrunableFiles;

class ImageMultiple extends RowTemplateAbstract
{
    use HasImageField;
    use HasPrunableFiles;

    public static $name = 'image-multiple';

    protected static function imageFieldsIndexes(): array
    {
        return ['images'];
    }
}
