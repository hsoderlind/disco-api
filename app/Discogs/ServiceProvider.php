<?php

namespace App\Discogs;

use Hsoderlind\Discogs\Client\Client;
use Hsoderlind\Discogs\Client\ClientFactory;
use RuntimeException;

/**
 * @property-read \Hsoderlind\Discogs\Client\Client $client
 */
class ServiceProvider
{
    protected Client $client;

    public function __construct(public readonly \Illuminate\Database\Eloquent\Model $model)
    {
        $this->createClient();
    }

    public function __get($name)
    {
        if (! property_exists($this, $name)) {
            throw new RuntimeException('No property '.$name.' on '.get_class($this));
        }

        return $this->$name;
    }

    protected function createClient()
    {
        $oauthToken = $this->model->getToken();
        $oauthTokenSecret = $this->model->getTokenSecret();

        $consumerKey = config('discogs.consumerKey');
        $consumerSecret = config('discogs.consumerSecret');
        $signature = $consumerSecret.'&'.$oauthTokenSecret;

        $this->client = ClientFactory::create([
            'oauth_consumer_key' => $consumerKey,
            'oauth_signature' => $signature,
            'oauth_token' => $oauthToken,
        ]);
    }
}
