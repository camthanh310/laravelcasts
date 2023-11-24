<?php

use App\Services\Twitter\NullTwitterClient;
use App\Services\Twitter\TwitterClientInterface;

it('returns null twitter client for testing env', function () {
    expect(app(TwitterClientInterface::class))
        ->toBeInstanceOf(NullTwitterClient::class);
});

