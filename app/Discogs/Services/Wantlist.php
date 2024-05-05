<?php

namespace App\Discogs\Services;

use App\Discogs\Service;

/**
 * @property int $page
 * @property int $per_page
 * @property int $release_id
 * @property string $notes
 * @property int $rating
 *
 * @method \Hsoderlind\Discogs\Api\Model\Collection listItems()
 * @method \Hsoderlind\Discogs\Api\Model\Model addRelease()
 * @method \Hsoderlind\Discogs\Api\Model\Response deleteRelease()
 */
class Wantlist extends Service
{
    protected array $reservedAttributes = ['username'];
}
