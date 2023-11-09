<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\View\View;

class PageHomeController extends Controller
{
    public function __invoke(): View
    {
        $courses = Course::query()
            ->released()
            ->orderByDesc('released_at')
            ->get();

        return view('pages.home', compact('courses'));
    }
}
