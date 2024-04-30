<?php

namespace App\Discogs\Services;

use App\Discogs\Service;

/**
 * @property string $status
 * @property string $sort
 * @property string $sort_order
 * @property int $page
 * @property int $per_page
 * @property string $name
 * @property string $home_page
 * @property string $location
 * @property string $profile
 * @property string $curr_abbr
 *
 * @method \Hsoderlind\Discogs\Api\Model\Collection getInventory()
 * @method \Hsoderlind\Discogs\Api\Model\Model getIdentity()
 * @method \Hsoderlind\Discogs\Api\Model\Model getProfile()
 * @method \Hsoderlind\Discogs\Api\Model\Model editProfile()
 * @method \Hsoderlind\Discogs\Api\Model\Collection getSubmissions()
 * @method \Hsoderlind\Discogs\Api\Model\Collection getContributions()
 */
class User extends Service
{
    //
}
