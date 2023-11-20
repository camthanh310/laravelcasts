<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Str;
use App\Mail\NewPurchaseMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class HandlePaddlePurchaseJob extends ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::query()
                    ->where("email", $this->webhookCall->payload['email'])
                    ->firstOr(function () {
                        return User::create([
                            'email' => $this->webhookCall->payload['email'],
                            'name' => $this->webhookCall->payload['name'],
                            'password' => bcrypt(Str::uuid()),
                        ]);
                    });

        $course = Course::query()
            ->where('paddle_product_id', $this->webhookCall->payload['p_product_id'])
            ->first();

        $user->purchasedCourses()->attach($course);

        Mail::to($user->email)
                ->send(new NewPurchaseMail($course));
    }
}
