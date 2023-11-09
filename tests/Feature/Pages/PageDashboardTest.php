<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Laravel\get;

it('cannot be accessed by guest', function () {
    get(route('pages.dashboard'))
        ->assertRedirect(route('login'));
});

it('lists purchesed courses', function () {
    $user = User::factory()
        ->has(Course::factory()->count(2)->state(
            new Sequence(
                ['title' => 'Course A'],
                ['title' => 'Course B']
            )
        ), 'purchasedCourses')
        ->create();

    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText([
            'Course A',
            'Course B'
        ]);
});

it('does not list other courses', function () {
    $course = Course::factory()->create();

    loginAsUser();
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertDontSeeText($course->title);
});

it('shows latest purchased course first', function () {
    $user = User::factory()->create();
    $firstPurchasedCourse = Course::factory()->create();
    $lastPurchasedCourse = Course::factory()->create();

    $user->purchasedCourses()->attach($firstPurchasedCourse, ['created_at' => now()->yesterday()]);
    $user->purchasedCourses()->attach($lastPurchasedCourse, ['created_at' => now()]);

    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeInOrder([
            $lastPurchasedCourse->title,
            $firstPurchasedCourse->title
        ]);
});

it('includes link to product videos', function () {
    $user = User::factory()
        ->has(Course::factory(), 'purchasedCourses')
        ->create();

    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Watch videos')
        ->assertSee(route('page.course-videos', Course::first()));
});

it('includes logout', function () {
    loginAsUser();
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Log Out')
        ->assertSee(route('logout'));
});
