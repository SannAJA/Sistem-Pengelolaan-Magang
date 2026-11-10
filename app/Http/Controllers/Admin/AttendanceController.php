<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        return view('admin.attendance.index', [
            'attendances' => Attendance::with('user')->latest()->paginate(15),
        ]);
    }
}

