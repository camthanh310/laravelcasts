<?php

use App\Models\Course;
use App\Models\Video;

use function Pest\Laravel\get;

it('gives back successful response for home page', function () {
    get(route('pages.home'))
        ->assertOk();
});

it('gives back successful response for course details page', function () {
    $course = Course::factory()->released()->create();

    get(route('pages.course-details', $course))
        ->assertOk();
});

it('gives back successful response for dashboard page', function () {
    loginAsUser();
    get(route('pages.dashboard'))
        ->assertOk();
});

it('dose not find JetStream registration page', function () {
    get('register')->assertNotFound();
});

it('gives successful response for videos page', function () {
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    loginAsUser();
    get(route('page.course-videos', $course))
        ->assertOk();
});
