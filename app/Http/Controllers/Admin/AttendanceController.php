<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\OfficeLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Menampilkan daftar monitoring absensi seluruh siswa dan daftar lokasi kantor cabang.
     */
    public function index(): View
    {
        return view('admin.attendance.index', [
            'attendances' => Attendance::with('user')->latest('date')->latest('id')->paginate(15),
            'locations' => OfficeLocation::orderBy('name')->get(),
        ]);
    }

    /**
     * Menyimpan data lokasi kantor/cabang baru.
     */
    public function storeLocation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:office_locations,name'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meters' => ['required', 'integer', 'min:10', 'max:10000'],
        ]);

        OfficeLocation::create($validated);

        return back()->with('success', 'Lokasi cabang "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    /**
     * Memperbarui data lokasi kantor/cabang yang sudah ada.
     */
    public function updateLocation(Request $request, OfficeLocation $location): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:office_locations,name,' . $location->id],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meters' => ['required', 'integer', 'min:10', 'max:10000'],
        ]);

        $location->update($validated);

        return back()->with('success', 'Lokasi cabang "' . $location->name . '" berhasil diperbarui.');
    }

    /**
     * Menghapus lokasi kantor/cabang.
     */
    public function destroyLocation(OfficeLocation $location): RedirectResponse
    {
        if (OfficeLocation::count() <= 1) {
            return back()->withErrors(['location' => 'Minimal harus ada 1 lokasi kantor/cabang aktif.']);
        }

        $name = $location->name;
        $location->delete();

        return back()->with('success', 'Lokasi cabang "' . $name . '" berhasil dihapus.');
    }
}
