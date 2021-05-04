<?php

namespace Novius\NovaVisualComposer\Templates;

use Novius\NovaVisualComposer\Traits\HasImageField;
use Novius\NovaVisualComposer\Traits\HasPrunableFiles;

class Article extends RowTemplateAbstract
{
    use HasImageField;
    use HasPrunableFiles;

    public static $name = 'article';

    protected static function imageFieldsIndexes(): array
    {
        return ['image'];
    }
}
