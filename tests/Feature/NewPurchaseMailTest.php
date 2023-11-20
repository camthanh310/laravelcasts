<?php

use App\Mail\NewPurchaseMail;
use App\Models\Course;

it('includes purchase details', function () {
    $course = Course::factory()->create();

    $mail = new NewPurchaseMail($course);

    $mail->assertSeeInHtml('Thanks for purchasing ' . $course->title);
    $mail->assertSeeInText('Login');
    $mail->assertSeeInHtml(route('login'));
});

