<?php

namespace App\Discogs\Enums;

enum SearchType: string
{
    case Release = 'release';
    case Master = 'master';
    case Artist = 'artist';
    case Label = 'label';
}
