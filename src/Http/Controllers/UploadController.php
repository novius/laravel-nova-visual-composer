<?php

namespace Novius\NovaVisualComposer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    protected $allowedMime = ['image/jpeg', 'image/png'];

    protected $diskName;

    protected $tmpPath;

    public function __construct()
    {
        $this->diskName = config('nova-visual-composer.upload_disk');
        $this->tmpPath = config('nova-visual-composer.tmp_files_path');
    }

    public function imageUpload(Request $request)
    {
        if (!$request->hasFile('filepond')) {
            return abort(405);
        }

        $request->validate([
            'filepond' => 'required|mimetypes:'.implode(',', $this->allowedMime),
        ]);

        $tmpPath = Storage::disk($this->diskName)
            ->put($this->tmpPath, $request->file('filepond'));

        return response($tmpPath, 200)
            ->header('Content-Type', 'text/plain');
    }

    public function imageShow(Request $request)
    {
        if (!$request->get('load', '')) {
            return abort(405);
        }

        $fileWanted = $request->get('load', '');
        $fileExists = Storage::disk($this->diskName)->exists($fileWanted);

        if (!$fileExists) {
            abort(404);
        }

        $fileMime = Storage::disk($this->diskName)->mimeType($fileWanted);
        if (!in_array($fileMime, $this->allowedMime)) {
            abort(404);
        }

        $thumbsCacheDuration = config('nova-visual-composer.filepond_thumb_cache_duration', (3600 * 24));
        $extension = pathinfo($fileWanted, PATHINFO_EXTENSION);
        $imgEncoded = \Cache::remember('vc.upload_thumb.'.md5($fileWanted), $thumbsCacheDuration, function () use ($fileWanted, $extension) {
            return (string) \Image::make(Storage::disk($this->diskName)
                ->get($fileWanted))
                ->resize(config('nova-visual-composer.filepond_thumbs_width', 150), null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode($extension);
        });

        return \Image::make($imgEncoded)->response($extension);
    }

    public function imageDelete(Request $request)
    {
        $filePath = $request->getContent();
        $fileExists = Storage::disk($this->diskName)->exists($filePath);

        if (!$fileExists || !Storage::disk($this->diskName)->delete($filePath)) {
            return response('', 500)
                ->header('Content-Type', 'text/plain');
        }

        return response('', 200)
            ->header('Content-Type', 'text/plain');
    }
}
