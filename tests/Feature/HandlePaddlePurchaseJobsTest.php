<?php

use App\Models\User;
use App\Models\Course;
use App\Mail\NewPurchaseMail;
use App\Models\PurchasedCourse;
use Illuminate\Support\Facades\Mail;
use App\Jobs\HandlePaddlePurchaseJob;

use function Pest\Laravel\assertDatabaseHas;
use Spatie\WebhookClient\Models\WebhookCall;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;

beforeEach(function () {
    $this->dummyWebhookCall = WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'email' => 'test@test.at',
            'name' => 'Test User',
            'p_product_id' => '34779'
        ]
    ]);
});

it('store paddle purchase', function () {
    Mail::fake();

    assertDatabaseEmpty(User::class);
    assertDatabaseEmpty(PurchasedCourse::class);

    $course = Course::factory()->create(['paddle_product_id' => '34779']);

    (new HandlePaddlePurchaseJob($this->dummyWebhookCall))->handle();

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

it('stores paddle purchase with given user', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'test@test.at']);
    $course = Course::factory()->create(['paddle_product_id' => '34779']);

    (new HandlePaddlePurchaseJob($this->dummyWebhookCall))->handle();

    assertDatabaseCount(User::class, 1);
    assertDatabaseHas(User::class, [
        'email' => $user->email,
        'name' => $user->name
    ]);

    assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id
    ]);
});

it('sends out purchase email', function () {
    Mail::fake();

    Course::factory()->create(['paddle_product_id' => '34779']);

    (new HandlePaddlePurchaseJob($this->dummyWebhookCall))->handle();

    Mail::assertSent(NewPurchaseMail::class);
});
