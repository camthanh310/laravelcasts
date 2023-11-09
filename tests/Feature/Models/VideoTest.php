<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;

it('belongs to a course', function () {
    $video = Video::factory()
        ->has(Course::factory())
        ->create();

    expect($video->course)
        ->toBeInstanceOf(Course::class);
});


it('gives back readable video duration', function () {
    $video = Video::factory()->create(['duration_in_min' => 10]);

    expect($video->getReadableDuration())
        ->toEqual('10min');
});

it('tells if current user has not yet watched a given video', function () {
    $video = Video::factory()->create();

    loginAsUser();
    expect($video->alreadyWatchedByCurrentUser())
        ->toBeFalse();
});

it('tells if current user has already watched a given video', function () {
    $user = User::factory()->has(Video::factory(), 'watchedVideos')
        ->create();

    loginAsUser($user);
    expect($user->watchedVideos()->first()->alreadyWatchedByCurrentUser())
        ->toBeTrue();
});
