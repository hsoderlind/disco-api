<?php

namespace App\Discogs\Services;

use App\Discogs\Service;

/**
 * @property int $list_id
 *
 * @method \Hsoderlind\Discogs\Api\Model\Collection getLists()
 * @method \Hsoderlind\Discogs\Api\Model\Collection getList()
 */
class Lists extends Service
{
    protected $serviceName = 'list';
}
