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
     * return ['image', 'banner'];
     *
     * @return array
     */
    abstract protected static function imageFieldsIndexes(): array;

    public static function beforeSave($content)
    {
        if (!empty($content) && !empty(static::imageFieldsIndexes())) {

            $tmpContent = json_decode($content);
            if (empty($tmpContent) || json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            $content = collect($tmpContent)->map(function($field) {
                $fieldName = $field->name ?? '';
                if (empty($fieldName) || ! in_array($fieldName, static::imageFieldsIndexes())) {
                    return $field;
                }

                $newImagesPath = [];
                $images = $field->content ?? [];
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

                return [
                    'name' => $fieldName,
                    'content' => $newImagesPath,
                ];
            })->toJson();
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
            if (is_array($images)) {
                foreach ($images as $imagePath) {
                    if (!empty($imagePath) && Storage::disk($disk)->exists($imagePath)) {
                        Storage::disk($disk)->delete($imagePath);
                    }
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

        collect($content)->each(function($field) use ($disk, &$files) {
            $fieldName = $field->name ?? '';
            if (empty($fieldName) || ! in_array($fieldName, static::imageFieldsIndexes())) {
                return;
            }

            $images = (array) $field->content ?? [];
            foreach ($images as $imagePath) {
                if (!empty($imagePath) && Storage::disk($disk)->exists($imagePath)) {
                    $files[] = $imagePath;
                }
            }
        });

        return $files;
    }
}
