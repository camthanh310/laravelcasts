<?php
use App\Services\Twitter\NullTwitterClient;

it('returns empty array for a tweet call', function () {
    expect(new NullTwitterClient)
        ->tweet('Our tweet')
        ->toBeArray()
        ->toBeEmpty();
});

