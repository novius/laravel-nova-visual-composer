<?php

namespace Novius\NovaVisualComposer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
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

        $filename = pathinfo($fileWanted, PATHINFO_FILENAME);

        return Storage::disk($this->diskName)->download($fileWanted, $filename, [], 'inline');
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
