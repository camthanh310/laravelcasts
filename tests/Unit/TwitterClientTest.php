<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\TwitterClient;

use function Pest\Laravel\mock;

it('call oauth client for a tweet', function () {
    $status = 'My tweet message';
    $mock = mock(TwitterOAuth::class)
                ->shouldReceive('post')
                ->withArgs(['statuses/update', ['status' => $status]])
                ->andReturn(['status' => $status])
                ->getMock();

    $twitterClient = new TwitterClient($mock);

    expect($twitterClient->tweet($status))
        ->toEqual(['status' => $status]);
});

