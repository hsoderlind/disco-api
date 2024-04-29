<?php

namespace App\Traits\DiscogsTokenizer;

trait DiscogsTokenizer
{
    /**
     * Name of attribute holding token
     */
    protected string $tokenAttribute;

    /**
     * Name of attribute holding token secret
     */
    protected string $tokenSecretAttribute;

    /**
     * Name of attribute holding username
     */
    protected string $usernameAttribute;

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
