<?php

namespace App\Services\File;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PhysicalFileService
{
    public function __construct(
        protected readonly File $model,
        protected readonly StorageProvider $storageProvider)
    {

    }

    public function store(Request $request)
    {
        $file = $request->file($this->storageProvider->inputName);
        $storageResolver = $this->getStorageResolver();

        $path = $file->store($storageResolver->getPath($request), [
            'visibility' => $storageResolver->getVisibility(),
            'disk' => $storageResolver->getDisk(),
        ]);

        return [$file, $path];
    }

    public function read()
    {
        $storageResolver = $this->getStorageResolver();

        return Storage::disk($storageResolver->getDisk())->get($this->model->path);
    }

    public function download(bool $asAttachment = true)
    {
        $fileContent = $this->read();

        return response($fileContent)
            ->header('Content-Disposition', ($asAttachment ? 'attachment' : 'inline').'; filename='.$this->model->filename)
            ->header('Content-Type', $this->model->mimetype);
    }

    public function delete(): bool
    {
        $storageResolver = $this->getStorageResolver();

        return Storage::disk($storageResolver->getDisk())->delete($this->model->path);
    }

    public function createTemporaryUrl(int $userId)
    {
        return URL::temporarySignedRoute(
            'signed_download',
            now()->addMinutes(5),
            [
                'shopId' => $this->model->shop_id,
                'userId' => $userId,
                'id' => $this->model->getKey(),
                'storageProvider' => $this->model->storage_resolver,
            ],
        );
    }

    protected function getStorageResolver()
    {
        return $this->storageProvider->resolve();
    }
}
