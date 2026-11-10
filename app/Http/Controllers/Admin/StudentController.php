<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Menampilkan daftar siswa magang yang terdaftar di sistem.
     */
    public function index(): \Illuminate\View\View
    {
        return view('admin.students.index', [
            'students' => User::where('role', 'siswa')->latest()->paginate(15),
        ]);
    }

    /**
     * Mengarahkan kembali ke daftar siswa karena form tambah ada di modal.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('admin.students.index');
    }

    /**
     * Menyimpan data siswa baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->where(fn ($query) => $query->where('role', 'siswa')),
            ],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.unique' => 'Nama siswa sudah tersedia.',
        ]);

        $student = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'plain_password' => $data['password'],
            'role' => 'siswa',
        ]);

        \App\Models\Grade::create(['user_id' => $student->id]);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Mengarahkan kembali ke daftar siswa karena form ubah ada di modal.
     */
    public function edit(User $student): RedirectResponse
    {
        abort_unless($student->role === 'siswa', 404);

        return redirect()->route('admin.students.index');
    }

    /**
     * Memperbarui data akun siswa, termasuk password bila diisi.
     */
    public function update(Request $request, User $student): RedirectResponse
    {
        abort_unless($student->role === 'siswa', 404);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')
                    ->where(fn ($query) => $query->where('role', 'siswa'))
                    ->ignore($student->id),
            ],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.unique' => 'Nama siswa sudah tersedia.',
        ]);

        $student->name = $data['name'];
        $student->email = $data['email'];

        if (! empty($data['password'])) {
            $student->password = Hash::make($data['password']);
            $student->plain_password = $data['password'];
        }

        $student->save();

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa dari sistem.
     */
    public function destroy(User $student): RedirectResponse
    {
        abort_unless($student->role === 'siswa', 404);

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
