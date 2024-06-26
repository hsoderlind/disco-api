<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponseCode;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Services\File\FileService;
use App\Services\Shop\ShopSession;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(FileRequest $request)
    {
        $file = $this->getService($request)->create();

        return new FileResource($file);
    }

    public function download(FileRequest $request, int $id)
    {
        return $this->getService($request)
            ->getPhysicalFileService($id)
            ->download(
                $request->query('disposition') === 'attachment'
            );
    }

    public function publicDownload(Request $request, int $shopId, int $id)
    {
        return $this->getService()
            ->getPhysicalFileService($id)
            ->download(
                $request->query('disposition') === 'attachment'
            );
    }

    public function signedDownload(Request $request, int $shopId, int $userId, int $id)
    {
        ShopSession::setId($shopId);
        abort_if(! $request->hasValidSignature(), HttpResponseCode::FORBIDDEN, 'Du har inte behörighet att ladda ner filen.');

        return $this->getService()
            ->getPhysicalFileService($id)
            ->download(true);
    }

    public function createSignedUrl(FileRequest $request, int $id)
    {
        return $this->getService($request)
            ->getPhysicalFileService($id)
            ->createTemporaryUrl($request->user()->id);
    }

    public function delete(FileRequest $request, int $id)
    {
        $deleted = $this->getService($request)->delete($id);

        abort_if(! $deleted, HttpResponseCode::METHOD_NOT_ALLOWED, 'Filen raderades inte.');
    }

    protected function getService(?FileRequest $request = null)
    {
        return new FileService(ShopSession::getId(), $request);
    }
}
