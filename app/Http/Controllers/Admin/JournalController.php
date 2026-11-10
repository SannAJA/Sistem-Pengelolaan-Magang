<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(): View
    {
        return view('admin.journals.index', [
            'journals' => Journal::with('user')->latest()->paginate(15),
        ]);
    }
}

