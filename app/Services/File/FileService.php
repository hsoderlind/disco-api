<?php

namespace App\Services\File;

use App\Http\Requests\FileRequest;
use App\Models\File;

class FileService
{
    protected File $model;

    public function __construct(
        protected readonly int $shopId,
        protected readonly FileRequest $request
    ) {
    }

    protected function getStorageProvider(): StorageProvider
    {
        if (isset($this->model)) {
            $storageProvider = new StorageProvider(inputName: $this->model->storage_resolver);
        } else {
            $storageProvider = new StorageProvider(request: $this->request);
        }

        return $storageProvider;
    }

    public function getPhysicalFileService(int $id)
    {
        $model = $this->get($id);
        $storageProvider = $this->getStorageProvider();

        return new PhysicalFileService($model, $storageProvider);
    }

    public function create(): File
    {
        $storageProvider = $this->getStorageProvider();
        $model = new File();

        $physicalFileService = new PhysicalFileService($model, $storageProvider);
        [$fileInput, $path] = $physicalFileService->store($this->request);
        $model->path = $path;
        $model->filename = $fileInput->getClientOriginalName();
        $model->extension = $fileInput->getClientOriginalExtension();
        $model->mimetype = $fileInput->getMimeType();
        $model->size = $fileInput->getSize();
        $model->save();

        return $model;
    }

    public function get(int $id): File
    {
        $file = File::inShop($this->shopId)->findOrFail($id);

        return $file;
    }

    public function delete(int $id): bool
    {
        $file = $this->get($id);
        $physicalFileService = new PhysicalFileService($file, $this->getStorageProvider());

        $physicalFileDeleted = $physicalFileService->delete();

        if (! $physicalFileDeleted) {
            return false;
        }

        $deleted = $file->delete();

        return $deleted;
    }
}
