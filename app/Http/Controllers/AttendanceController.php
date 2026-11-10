<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficeLocation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman absensi siswa beserta data absensi hari ini dan rekap absensi.
     */
    public function index(Request $request): View
    {
        $officeLocation = $this->getOfficeLocation();

        $query = Attendance::where('user_id', $request->user()->id);

        if ($request->filled('month')) {
            $parts = explode('-', $request->input('month'));
            if (count($parts) === 2) {
                $query->whereYear('date', (int) $parts[0])
                    ->whereMonth('date', (int) $parts[1]);
            }
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();

        return view('attendance.index', [
            'todayAttendance' => Attendance::where('user_id', $request->user()->id)
                ->whereDate('date', now()->toDateString())
                ->first(),
            'attendances' => $attendances,
            'officeLatitude' => (float) $officeLocation->latitude,
            'officeLongitude' => (float) $officeLocation->longitude,
            'maxRadiusMeters' => (int) $officeLocation->radius_meters,
        ]);
    }

    /**
     * Menyimpan absensi siswa setelah validasi radius lokasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $officeLocation = $this->getOfficeLocation();

        $data = $request->validate([
            'action' => ['required', 'in:clock_in,clock_out'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $distanceMeters = $this->calculateDistanceMeters(
            (float) $officeLocation->latitude,
            (float) $officeLocation->longitude,
            (float) $data['latitude'],
            (float) $data['longitude']
        );

        if ($distanceMeters > (int) $officeLocation->radius_meters) {
            return back()
                ->withErrors(['location' => 'Jarak Terlalu Jauh, Tidak Bisa Melakukan Absensi'])
                ->withInput();
        }

        $today = Carbon::today()->toDateString();
        $attendance = Attendance::firstOrNew([
            'user_id' => $request->user()->id,
            'date' => $today,
        ]);

        if ($data['action'] === 'clock_in' && $attendance->clock_in) {
            return back()->withErrors(['action' => 'Absen masuk hari ini sudah tercatat.'])->withInput();
        }

        if ($data['action'] === 'clock_out' && ! $attendance->clock_in) {
            return back()->withErrors(['action' => 'Absen masuk belum tercatat.'])->withInput();
        }

        if ($data['action'] === 'clock_out' && $attendance->clock_out) {
            return back()->withErrors(['action' => 'Absen pulang hari ini sudah tercatat.'])->withInput();
        }

        $attendance->fill([
            'clock_in' => $data['action'] === 'clock_in' ? now() : $attendance->clock_in,
            'clock_out' => $data['action'] === 'clock_out' ? now() : $attendance->clock_out,
            'status' => 'hadir',
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        $attendance->save();

        return back()->with('success', $data['action'] === 'clock_in'
            ? 'Absen masuk berhasil disimpan.'
            : 'Absen pulang berhasil disimpan.');
    }

    /**
     * Menghitung jarak antara koordinat siswa dan kantor menggunakan rumus Haversine.
     */
    private function calculateDistanceMeters(float $officeLatitude, float $officeLongitude, float $latitude, float $longitude): float
    {
        $earthRadius = 6371000;

        $latitudeFrom = deg2rad($officeLatitude);
        $longitudeFrom = deg2rad($officeLongitude);
        $latitudeTo = deg2rad($latitude);
        $longitudeTo = deg2rad($longitude);

        $latitudeDelta = $latitudeTo - $latitudeFrom;
        $longitudeDelta = $longitudeTo - $longitudeFrom;

        $a = sin($latitudeDelta / 2) ** 2
            + cos($latitudeFrom) * cos($latitudeTo) * sin($longitudeDelta / 2) ** 2;

        return 2 * $earthRadius * asin(min(1, sqrt($a)));
    }

    /**
     * Mengambil lokasi kantor aktif dari database untuk dipakai sebagai titik absensi.
     */
    private function getOfficeLocation(): OfficeLocation
    {
        return OfficeLocation::query()->firstOrFail();
    }
}
