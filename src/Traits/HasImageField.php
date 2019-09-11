<?php

namespace Novius\NovaVisualComposer\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasImageField
{
    /**
     * Get image fields indexes
     *
     * Example :
     * return [0, 3];
     *
     * @return array
     */
    abstract protected static function imageFieldsIndexes(): array;

    public static function beforeSave($content)
    {
        if (!empty($content) && !empty(static::imageFieldsIndexes())) {
            $tmpContent = json_decode($content);

            foreach (static::imageFieldsIndexes() as $index) {
                if (empty($tmpContent[$index])) {
                    continue;
                }

                $newImagesPath = [];
                $images = $tmpContent[$index];
                foreach ($images as $imagePath) {
                    $disk = config('nova-visual-composer.upload_disk');
                    if (!empty($imagePath)
                        && Str::startsWith($imagePath, config('nova-visual-composer.tmp_files_path'))
                        && Storage::disk($disk)->exists($imagePath)
                    ) {
                        $filename = pathinfo($imagePath, PATHINFO_FILENAME);
                        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                        $destPath = config('nova-visual-composer.files_path').'/'.date('Y/m/d').'/'.$filename.'.'.$extension;
                        if (Storage::disk($disk)->move($imagePath, $destPath)) {
                            $newImagesPath[] = $destPath;
                        }
                    } elseif (!empty($imagePath) && Storage::disk($disk)->exists($imagePath)) {
                        $newImagesPath[] = $imagePath;
                    }
                }

                $tmpContent[$index] = $newImagesPath;
            }

            $content = json_encode($tmpContent);
        }

        return $content;
    }

    public static function prune(array $content): bool
    {
        $disk = config('nova-visual-composer.upload_disk');

        foreach (static::imageFieldsIndexes() as $index) {
            if (empty($content[$index])) {
                continue;
            }

            $images = $content[$index];
            foreach ($images as $imagePath) {
                if (!empty($imagePath) && Storage::disk($disk)->exists($imagePath)) {
                    Storage::disk($disk)->delete($imagePath);
                }
            }
        }

        return true;
    }

    /**
     * @param array $content
     * @return array
     */
    public static function prunableFiles(array $content): array
    {
        $files = [];
        $disk = config('nova-visual-composer.upload_disk');
        foreach (static::imageFieldsIndexes() as $index) {
            if (empty($content[$index])) {
                continue;
            }

            $images = $content[$index];
            foreach ($images as $imagePath) {
                if (!empty($imagePath) && Storage::disk($disk)->exists($imagePath)) {
                    $files[] = $imagePath;
                }
            }
        }

        return $files;
    }
}
