<?php

namespace App\Discogs\Traits;

trait DiscogsTokenizer
{
    /**
     * Name of attribute holding token
     */
    protected string $tokenAttribute = 'token';

    /**
     * Name of attribute holding token secret
     */
    protected string $tokenSecretAttribute = 'token_secret';

    /**
     * Name of attribute holding username
     */
    protected string $usernameAttribute = 'username';

    public function getToken(): string
    {
        return $this->getAttribute($this->tokenAttribute);
    }

    public function getTokenSecret(): string
    {
        return $this->getAttribute($this->tokenSecretAttribute);
    }

    public function getUsername(): string
    {
        return $this->getAttribute($this->usernameAttribute);
    }
}
