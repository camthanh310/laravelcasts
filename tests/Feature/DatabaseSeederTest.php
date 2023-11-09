<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('adds given courses', function () {
    assertDatabaseCount(Course::class, 0);

    artisan('db:seed');

    assertDatabaseCount(Course::class, 3);
    assertDatabaseHas(Course::class, ['title' => 'Laravel For Beginners']);
    assertDatabaseHas(Course::class, ['title' => 'Advanced Laravel']);
    assertDatabaseHas(Course::class, ['title' => 'TDD The Laravel Way']);
});

it('adds given courses only once', function () {
    artisan('db:seed');
    artisan('db:seed');

    assertDatabaseCount(Course::class, 3);
});

it('adds given videos', function () {
    assertDatabaseCount(Video::class, 0);

    artisan('db:seed');

    $laravelForBeginnersCourse = Course::where('title', 'Laravel For Beginners')->firstOrFail();
    $advancedLaravelCourse = Course::where('title', 'Advanced Laravel')->firstOrFail();
    $tddTheLaravelWayCourse = Course::where('title', 'TDD The Laravel Way')->firstOrFail();
    assertDatabaseCount(Video::class, 8);

    expect($laravelForBeginnersCourse)->videos->toHaveCount(3);
    expect($advancedLaravelCourse)->videos->toHaveCount(3);
    expect($tddTheLaravelWayCourse)->videos->toHaveCount(2);
});

it('adds given videos only once', function () {
    assertDatabaseCount(Video::class, 0);
    artisan('db:seed');
    artisan('db:seed');

    assertDatabaseCount(Video::class, 8);
});

it('adds local test user', function () {
    App::partialMock()->shouldReceive('environment')->andReturn('local');

    assertDatabaseCount(User::class, 0);

    artisan('db:seed');

    assertDatabaseCount(User::class, 1);
});

it('does not add test user for production', function () {
    App::partialMock()->shouldReceive('environment')->andReturn('production');

    assertDatabaseCount(User::class, 0);

    artisan('db:seed');

    assertDatabaseCount(User::class, 0);
});
