<?php

namespace App\Services\Note;

use App\Models\Note;
use App\Services\AbstractService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class NoteService extends AbstractService
{
    protected mixed $relationModel;

    public function setRelationModel(mixed $relationModelClass, ?int $relationModelId = null)
    {
        $providerClass = is_string($relationModelClass) && ! Str::startsWith($relationModelClass, '\\') ? config('note.providers.'.$relationModelClass) : null;

        if (! class_exists($relationModelClass)) {
            throw new InvalidArgumentException($relationModelClass.' does not exists');
        }

        if (is_string($relationModelClass) && is_null($relationModelId)) {
            throw new InvalidArgumentException('$relationModelId must be set if $relationModelClass is string');
        }

        if (! is_null($providerClass)) {
            $this->relationModel = $providerClass::findOrFail($relationModelId);
        } elseif (is_string($relationModelClass)) {
            $this->relationModel = $relationModelClass::findOrFail($relationModelId);
        } elseif ($relationModelClass instanceof Model) {
            $this->relationModel = $relationModelClass;
        }

        return $this;
    }

    public function list()
    {
        $this->data = $this->relationModel->notes()->orderBy('created_at', 'desc')->get();

        return $this;
    }

    public function create(array $data)
    {
        $this->data = DB::transaction(function () use ($data) {
            $model = new Note([
                'title' => $data['title'] ?? null,
                'content' => $data['content'],
            ]);

            $model->noteable()->associate($this->relationModel);

            $model->save();

            return $model;
        });

        return $this;
    }

    public function read(int $id)
    {
        $this->data = Note::findOrFail($id);

        return $this;
    }

    public function update(int $id, array $data)
    {
        $this->data = DB::transaction(function () use ($id, $data) {
            $model = $this->read($id)->get();

            $model->update([
                'title' => $data['title'] ?? null,
                'content' => $data['content'],
            ]);

            return $model;
        });

        return $this;
    }

    public function delete(int $id)
    {
        $model = $this->read($id)->get();
        $deleted = $model->delete();

        return $deleted;
    }
}
