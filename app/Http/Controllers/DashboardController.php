<?php

namespace App\Http\Controllers;

use App\Models\UserPerk;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Enrollment;
use App\Models\StudentLesson;
use App\Models\Rank;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Paginated coding news
        $news = News::paginate(5); // Change pagination number if needed

        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        // Get user's enrollments and lessons
        $userId = Auth::user()->id;

        $coursesEnrolled = Enrollment::where('user_id', $userId)->count();
        $lessonsCompleted = StudentLesson::where('user_id', $userId)->count();

        // Fetch the user's rank safely
        $userPerk = UserPerk::where('user_id', $userId)->first();

        // Safe access using optional()
        $rank = optional($userPerk->rank)->name ?? 'No Rank';

        // Paginate announcements
        $announcements = Announcement::latest()->paginate(5); // Change pagination number if needed

        // Pass data to the dashboard view
        return view('dashboard', compact('news', 'coursesEnrolled', 'lessonsCompleted', 'rank', 'announcements'));
    }
}
