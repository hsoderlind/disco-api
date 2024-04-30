<?php

namespace App\Discogs\Services;

use App\Discogs\Service;

/**
 * @property int $listing_id
 * @property string $curr_abbr
 * @property int $release_id
 * @property string $condition
 * @property string $sleeve_condition
 * @property float $price
 * @property string $comments
 * @property bool $allow_offers
 * @property string $status
 * @property string $external_id
 * @property string $location
 * @property float $weight
 * @property float $format_quantity
 * @property string $sleeve_condition
 * @property string $order_id
 * @property float $shipping
 * @property string $created_after
 * @property string $created_before
 * @property bool $archived
 * @property string $sort
 * @property string $sort_order
 * @property int $page
 * @property int $per_page
 * @property string $message
 * @property string $currency
 * @property int $release_id
 *
 * @method \Hsoderlind\Discogs\Api\Model\Model getListing()
 * @method \Hsoderlind\Discogs\Api\Model\Model updateListing()
 * @method \Hsoderlind\Discogs\Api\Model\Response deleteListing()
 * @method \Hsoderlind\Discogs\Api\Model\Model getOrder()
 * @method \Hsoderlind\Discogs\Api\Model\Model editOrder()
 * @method \Hsoderlind\Discogs\Api\Model\Collection listOrders()
 * @method \Hsoderlind\Discogs\Api\Model\Collection listOrderMessages()
 * @method \Hsoderlind\Discogs\Api\Model\Model addOrderMessage()
 * @method \Hsoderlind\Discogs\Api\Model\Model getFee()
 * @method \Hsoderlind\Discogs\Api\Model\Model getFeeWithCurrency()
 * @method \Hsoderlind\Discogs\Api\Model\Model getPriceSuggestions()
 * @method \Hsoderlind\Discogs\Api\Model\Model getReleaseStats()
 */
class Marketplace extends Service
{
    //
}
