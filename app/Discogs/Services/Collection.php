<?php

namespace App\Discogs\Services;

use App\Discogs\Service;

/**
 * @property string $name
 * @property int $folder_id
 * @property int $release_id
 * @property int $page
 * @property int $per_page
 * @property string $sort
 * @property string $sort_order
 * @property int $instance_id
 * @property int $rating
 * @property int $move_to_folder_id
 * @property int $field_id
 * @property string $value
 *
 * @method \Hsoderlind\Discogs\Api\Model\Collection getFolders()
 * @method \Hsoderlind\Discogs\Api\Model\Model createFolder()
 * @method \Hsoderlind\Discogs\Api\Model\Model getFolder()
 * @method \Hsoderlind\Discogs\Api\Model\Model editFolder()
 * @method \Hsoderlind\Discogs\Api\Model\Response deleteFolder()
 * @method \Hsoderlind\Discogs\Api\Model\Collection listItemsByRelease()
 * @method \Hsoderlind\Discogs\Api\Model\Collection listItemsByFolder()
 * @method \Hsoderlind\Discogs\Api\Model\Model addReleaseToFolder()
 * @method \Hsoderlind\Discogs\Api\Model\Model editRelease()
 * @method \Hsoderlind\Discogs\Api\Model\Response deleteReleaseInstance()
 * @method \Hsoderlind\Discogs\Api\Model\Collection listCustomFields()
 * @method \Hsoderlind\Discogs\Api\Model\Model editFieldByInstance()
 * @method \Hsoderlind\Discogs\Api\Model\Model getCollectionValue()
 */
class Collection extends Service
{
    //
}
