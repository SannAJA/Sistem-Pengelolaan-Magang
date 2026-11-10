<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menyusun data dashboard berdasarkan peran pengguna yang sedang login.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user?->role === 'admin') {
            return view('dashboard.admin', [
                'studentCount' => User::where('role', 'siswa')->count(),
                'attendanceCount' => Attendance::count(),
                'journalCount' => Journal::count(),
                'gradeCount' => Grade::count(),
                'latestAttendances' => Attendance::with('user')->latest()->take(5)->get(),
                'latestJournals' => Journal::with('user')->latest()->take(5)->get(),
            ]);
        }

        return view('dashboard.student', [
            'todayAttendance' => Attendance::where('user_id', $user?->id)->whereDate('date', now()->toDateString())->first(),
            'latestJournal' => Journal::where('user_id', $user?->id)->latest()->first(),
            'myAttendanceCount' => Attendance::where('user_id', $user?->id)->count(),
            'myJournalCount' => Journal::where('user_id', $user?->id)->count(),
            'myGrade' => Grade::where('user_id', $user?->id)->first(),
        ]);
    }
}
