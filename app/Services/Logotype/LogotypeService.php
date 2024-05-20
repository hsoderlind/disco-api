<?php

namespace App\Services\Logotype;

use App\Models\Logotype;
use App\Services\AbstractService;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;

class LogotypeService extends AbstractService
{
    public function create(array $data)
    {
        $this->data = DB::transaction(function () use ($data) {
            $model = new Logotype();
            $model->meta()->save(FileService::staticGet($this->shopId, $data['meta']['id']));

            $model->save();

            return $model;
        });

        return $this;
    }

    public function read(int $id)
    {
        $this->data = Logotype::findOrFail($id);

        return $this;
    }

    public function update(int $id, array $data)
    {
        $this->read($id);

        if ($data['meta']['id'] == $this->data->meta->id) {
            return $this;
        }

        $this->data->meta()->save(FileService::staticGet($this->shopId, $id));

        $this->data->save();

        return $this;
    }

    public function delete(int $id)
    {
        $model = $this->read($id)->get();
        $fileId = $model->meta->id;

        $deleted = $model->delete();

        if ($deleted) {
            $fileService = new FileService($this->shopId);
            $deleted = $fileService->delete($fileId);
        }

        return $deleted;
    }
}
