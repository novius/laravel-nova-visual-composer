<?php

namespace Novius\NovaVisualComposer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Deletable;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Novius\NovaVisualComposer\Helpers\Templates;
use Novius\NovaVisualComposer\Traits\HasPrunableFiles;

class NovaVisualComposer extends Field implements \Laravel\Nova\Contracts\Deletable
{
    use Deletable;

    public $component = 'nova-visual-composer';

    public $showOnIndex = false;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->prunable = true;
        $this->deletable = false;
        $this->deleteCallback = $this->deletingModelCallback();
    }

    /**
     * Called by Nova on Model deletion (with field deleteCallback)
     *
     * @return \Closure
     */
    protected function deletingModelCallback()
    {
        return function ($request, $model) {
            if (empty($model->{$this->attribute})) {
                return true;
            }

            foreach ($model->{$this->attribute} as $row) {
                if (!class_exists($row->template) || !method_exists($row->template, 'prune') || empty($row->content)) {
                    continue;
                }

                $content = json_decode($row->content);
                if (!is_array($content) || empty($content)) {
                    return true;
                }

                $row->template::prune($content);
            }

            return true;
        };
    }

    protected function fillAttributeFromRequest(NovaRequest $request,
                                                $requestAttribute,
                                                $model,
                                                $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $rows = json_decode($request[$requestAttribute]);
            foreach ($rows as &$row) {
                $rowTemplate = $row->template;
                $row->content = $rowTemplate::beforeSave($row->content);
            }

            $model->{$attribute} = $rows;

            $this->pruneOldFiles($model, $attribute);
        }
    }

    /**
     * Delete old files on disk
     *
     * @param Model $model
     * @param string $attribute
     */
    protected function pruneOldFiles(Model $model, string $attribute)
    {
        if (!$model->exists) {
            return;
        }

        $newFiles = collect($this->getPrunableFiles($model, $attribute));
        $oldFiles = collect($this->getPrunableFiles($model, $attribute, true));
        $filesToPrune = $oldFiles->diff($newFiles);

        $disk = config('nova-visual-composer.upload_disk');
        foreach ($filesToPrune as $filePath) {
            Storage::disk($disk)->delete($filePath);
        }
    }

    protected function getPrunableFiles(Model $model, string $attribute, bool $original = false): array
    {
        $rows = $model->{$attribute};
        if ($original) {
            $rows = $model->getRawOriginal($attribute);
            if (empty($rows)) {
                return [];
            }
            $rows = json_decode($rows);
        }

        $files = [];
        if (!empty($rows) && $model->exists) {

            foreach ($rows as $row) {
                $rowContent = json_decode($row->content);
                if (!is_array($rowContent) || empty($rowContent) || !class_exists($row->template)) {
                    continue;
                }
                if (in_array(HasPrunableFiles::class, class_uses(new $row->template))) {
                    $rowTemplate = $row->template;
                    $files = array_merge($files, $rowTemplate::prunableFiles($rowContent));
                }
            }
        }

        return $files;
    }

    public function resolveAttribute($resource, $attribute = null)
    {
        $value = parent::resolveAttribute($resource, $attribute);

        return json_encode($value ?? []);
    }

    public function meta()
    {
        return array_merge($this->meta, [
            'templates' => Templates::templates()->toArray(),
            'addRowButtonLabel' => 'Ajouter une ligne',
        ]);
    }
}
