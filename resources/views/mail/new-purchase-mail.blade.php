<x-mail::message>
# Thanks for purchasing {{ $course->title }}

If this is your first purchase on {{ config('app.name') }}, then a new account was created for you, and you just need to reset your password.
Have fun with your new course.

<x-mail::button :url="route('login')">
Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
