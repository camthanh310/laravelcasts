<?php

use App\Jobs\HandlePaddlePurchaseJob;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use Spatie\WebhookClient\Models\WebhookCall;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;

it('store paddle purchase', function () {
    assertDatabaseEmpty(User::class);
    assertDatabaseEmpty(PurchasedCourse::class);

    $course = Course::factory()->create(['paddle_product_id' => '34779']);
    $webhookCall = WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'email' => 'test@test.at',
            'name' => 'Test User',
            'p_product_id' => '34779'
        ]
    ]);

    (new HandlePaddlePurchaseJob($webhookCall))->handle();

    assertDatabaseHas(User::class, [
        'email' => 'test@test.at',
        'name' => 'Test User'
    ]);

    $user = User::query()->where('email', 'test@test.at')->first();

    assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id
    ]);
});

it('stores paddle purchase for given user', function () {
    //expect()->
});

it('sends out purchase for given user', function () {
    //expect()->
});

it('sends out purchase email', function () {
    //expect()->
});
