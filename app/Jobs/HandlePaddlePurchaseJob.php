<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class HandlePaddlePurchaseJob extends ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::create([
            'email' => $this->webhookCall->payload['email'],
            'name' => $this->webhookCall->payload['name'],
            'password' => bcrypt(Str::uuid()),
        ]);

        $course = Course::query()
            ->where('paddle_product_id', $this->webhookCall->payload['p_product_id'])
            ->first();

        $user->purchasedCourses()->attach($course);
    }
}
