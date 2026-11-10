<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JournalController extends Controller
{
    /**
     * Menampilkan daftar jurnal milik siswa yang sedang login.
     */
    public function index(Request $request): View
    {
        return view('journals.index', [
            'journals' => Journal::where('user_id', $request->user()->id)->latest()->paginate(10),
        ]);
    }

    /**
     * Menyimpan atau memperbarui jurnal harian beserta foto tugas jika ada.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'work_summary' => ['required', 'string', 'max:5000'],
            'task_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $photoPath = null;

        if ($request->hasFile('task_photo')) {
            $photoPath = Storage::disk('public')->putFile('journals', $request->file('task_photo'));
        }

        Journal::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'date' => Carbon::today()->toDateString(),
            ],
            [
                'work_summary' => $data['work_summary'],
                'task_photo_path' => $photoPath,
            ]
        );

        return back()->with('success', 'Jurnal harian berhasil disimpan.');
    }
}
