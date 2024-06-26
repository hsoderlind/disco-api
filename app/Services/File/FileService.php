<?php

namespace App\Services\File;

use App\Http\Requests\FileRequest;
use App\Models\File;

class FileService
{
    protected File|null $model = null;

    public function __construct(
        protected readonly int $shopId,
        protected readonly ?FileRequest $request = null
    ) {
    }

    public static function staticGet(int $shopId, int $id): File
    {
        return File::inShop($shopId)->findOrFail($id);
    }

    protected function getStorageProvider(): StorageProvider
    {
        if (! is_null($this->model)) {
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
        $model->storage_resolver = $this->request->input('storage_resolver');
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
        $this->model = File::inShop($this->shopId)->findOrFail($id);

        return $this->model;
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
