<?php

namespace App\Services\Twitter;

use Illuminate\Support\Facades\Facade;
use Tests\Feature\Fakes\TwitterFake;

/**
 * @see TwitterFake
 * @see TwitterClient
 */
class TwitterFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwitterClientInterface::class;
    }

    public static function fake()
    {
        self::swap(new TwitterFake);
    }
}
