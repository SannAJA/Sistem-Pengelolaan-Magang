<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Menampilkan daftar nilai siswa beserta feedback terakhir.
     */
    public function index(): \Illuminate\View\View
    {
        // Memastikan seluruh siswa magang terdaftar memiliki record di tabel grades
        $studentsWithoutGrade = User::where('role', 'siswa')
            ->whereDoesntHave('grade')
            ->get();

        foreach ($studentsWithoutGrade as $student) {
            Grade::create(['user_id' => $student->id]);
        }

        return view('admin.grades.index', [
            'grades' => Grade::with('user')
                ->whereHas('user', function ($query) {
                    $query->where('role', 'siswa');
                })
                ->latest('updated_at')
                ->paginate(15),
        ]);
    }

    /**
     * Mengarahkan kembali ke daftar nilai karena form ubah ada di modal.
     */
    public function edit(Grade $grade): RedirectResponse
    {
        return redirect()->route('admin.grades.index');
    }

    /**
     * Menyimpan perubahan nilai akhir dan feedback siswa.
     */
    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $data = $request->validate([
            'final_score' => ['nullable', 'numeric', 'between:0,100'],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ]);

        $grade->update($data);

        return redirect()->route('admin.grades.index')->with('success', 'Data nilai berhasil diperbarui.');
    }
}
