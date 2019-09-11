<?php

namespace Novius\NovaVisualComposer\Traits;

trait HasPrunableFiles
{
    /**
     * @param array $content
     * @return array : prunable files path
     */
    abstract public static function prunableFiles(array $content): array;
}
