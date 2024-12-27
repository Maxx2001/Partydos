<?php

namespace App\Web\Files\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use League\Glide\ServerFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Support\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController  extends Controller
{
    public function show(Request $request, Media $media): StreamedResponse
    {
        $path = $media->getKey() . '/' . $media->file_name;

        $filesystem = Storage::disk($media->collection_name);

        if (!$filesystem->exists($path)) {
            \Log::error("File not found: $path");
            abort(404, 'File not found');
        }

        $server = ServerFactory::create(
            [
                'source' => $filesystem->getDriver(),
                'cache' => $filesystem->getDriver(),
                'cache_path_prefix' => '.cache',
                'base_url' => 'img',
            ]
        );

        return Response::stream(function () use ($server, $path, $request) {
            $server->outputImage($path, $request->all());
        });
    }
}
