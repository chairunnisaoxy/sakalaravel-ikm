<?php
// app/Http/Controllers/AbsensiController.php
namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with('karyawan');
        
        // Filter berdasarkan tanggal
        if ($request->has('filter_tanggal') && $request->filter_tanggal) {
            $query->whereDate('tanggal', $request->filter_tanggal);
        }
        
        // Filter berdasarkan karyawan
        if ($request->has('filter_karyawan') && $request->filter_karyawan) {
            $query->where('id_karyawan', $request->filter_karyawan);
        }
        
        // Filter berdasarkan status
        if ($request->has('filter_status') && $request->filter_status) {
            $query->where('status_absensi', $request->filter_status);
        }
        
        $absensi = $query->latest('tanggal')->get();
        $karyawanList = Karyawan::where('status_karyawan', 'aktif')->get();
        
        return view('absensi.index', compact('absensi', 'karyawanList'));
    }

    public function create()
    {
        $karyawanList = Karyawan::where('status_karyawan', 'aktif')->get();
        
        // Jika tidak ada karyawan aktif
        if ($karyawanList->count() == 0) {
            return redirect()->route('absensi.index')
                ->with('error', 'Tidak ada karyawan aktif. Silakan tambah karyawan terlebih dahulu.');
        }
        
        return view('absensi.create', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:m_karyawan,id_karyawan',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status_absensi' => 'required|in:hadir,tidak hadir,cuti',
            'total_gaji' => 'required|numeric|min:0',
            'bonus_lembur' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
        ]);

        // Validasi jam keluar harus setelah jam masuk jika keduanya diisi
        if ($request->jam_masuk && $request->jam_keluar) {
            if (strtotime($request->jam_keluar) <= strtotime($request->jam_masuk)) {
                return back()->withErrors(['jam_keluar' => 'Jam keluar harus setelah jam masuk.'])->withInput();
            }
        }

        // Generate ID Absensi yang unik
        do {
            $id_absensi = 'ABS-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Absensi::where('id_absensi', $id_absensi)->exists());

        // Cek apakah sudah ada absensi untuk karyawan di tanggal tersebut
        $existing = Absensi::where('id_karyawan', $request->id_karyawan)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($existing) {
            return back()->with('error', 'Karyawan sudah memiliki absensi pada tanggal ini.')->withInput();
        }

        // Calculate total_aktual
        $total_aktual = ($request->total_gaji ?? 0) + ($request->bonus_lembur ?? 0) - ($request->potongan ?? 0);

        try {
            Absensi::create([
                'id_absensi' => $id_absensi,
                'id_karyawan' => $request->id_karyawan,
                'tanggal' => $request->tanggal,
                'jam_masuk' => $request->jam_masuk,
                'jam_keluar' => $request->jam_keluar,
                'status_absensi' => $request->status_absensi,
                'total_gaji' => $request->total_gaji ?? 100000,
                'bonus_lembur' => $request->bonus_lembur ?? 0,
                'potongan' => $request->potongan ?? 0,
                'total_aktual' => $total_aktual,
            ]);

            // Update total hadir karyawan jika hadir
            if ($request->status_absensi === 'hadir') {
                Karyawan::where('id_karyawan', $request->id_karyawan)
                    ->increment('total_hadir');
            }

            return redirect()->route('absensi.index')
                ->with('success', 'Absensi berhasil ditambahkan. ID: ' . $id_absensi);
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $absensi = Absensi::with('karyawan')->findOrFail($id);
        return view('absensi.show', compact('absensi'));
    }

    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);
        $karyawanList = Karyawan::where('status_karyawan', 'aktif')->get();
        
        // Format jam untuk input type="time"
        if ($absensi->jam_masuk) {
            $absensi->jam_masuk_formatted = substr($absensi->jam_masuk, 0, 5);
        }
        if ($absensi->jam_keluar) {
            $absensi->jam_keluar_formatted = substr($absensi->jam_keluar, 0, 5);
        }
        
        return view('absensi.edit', compact('absensi', 'karyawanList'));
    }

    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);
        
        $validated = $request->validate([
            'id_karyawan' => 'required|exists:m_karyawan,id_karyawan',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'status_absensi' => 'required|in:hadir,tidak hadir,cuti',
            'total_gaji' => 'required|numeric|min:0',
            'bonus_lembur' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
        ]);

        // Validasi jam keluar harus setelah jam masuk jika keduanya diisi
        if ($request->jam_masuk && $request->jam_keluar) {
            if (strtotime($request->jam_keluar) <= strtotime($request->jam_masuk)) {
                return back()->withErrors(['jam_keluar' => 'Jam keluar harus setelah jam masuk.'])->withInput();
            }
        }

        // Cek apakah tanggal dan karyawan berubah ke kombinasi yang sudah ada
        if ($absensi->id_karyawan != $request->id_karyawan || $absensi->tanggal != $request->tanggal) {
            $existing = Absensi::where('id_karyawan', $request->id_karyawan)
                ->whereDate('tanggal', $request->tanggal)
                ->where('id_absensi', '!=', $id)
                ->exists();

            if ($existing) {
                return back()->with('error', 'Sudah ada absensi untuk karyawan ini pada tanggal tersebut.')->withInput();
            }
        }

        $old_status = $absensi->status_absensi;
        $new_status = $request->status_absensi;

        // Calculate total_aktual
        $total_aktual = ($request->total_gaji ?? 0) + ($request->bonus_lembur ?? 0) - ($request->potongan ?? 0);

        try {
            $absensi->update([
                'id_karyawan' => $request->id_karyawan,
                'tanggal' => $request->tanggal,
                'jam_masuk' => $request->jam_masuk,
                'jam_keluar' => $request->jam_keluar,
                'status_absensi' => $new_status,
                'total_gaji' => $request->total_gaji,
                'bonus_lembur' => $request->bonus_lembur ?? 0,
                'potongan' => $request->potongan ?? 0,
                'total_aktual' => $total_aktual,
            ]);

            // Update total hadir karyawan jika status berubah
            if ($old_status !== $new_status) {
                $karyawan = Karyawan::find($request->id_karyawan);

                if ($old_status === 'hadir' && $new_status !== 'hadir') {
                    $karyawan->decrement('total_hadir');
                } elseif ($old_status !== 'hadir' && $new_status === 'hadir') {
                    $karyawan->increment('total_hadir');
                }
            }

            return redirect()->route('absensi.index')
                ->with('success', 'Absensi berhasil diperbarui. ID: ' . $absensi->id_absensi);
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            $absensi_id = $absensi->id_absensi;

            // Kurangi total hadir jika status hadir
            if ($absensi->status_absensi === 'hadir') {
                Karyawan::where('id_karyawan', $absensi->id_karyawan)
                    ->decrement('total_hadir');
            }

            $absensi->delete();

            return redirect()->route('absensi.index')
                ->with('success', 'Absensi berhasil dihapus. ID: ' . $absensi_id);
                
        } catch (\Exception $e) {
            return redirect()->route('absensi.index')
                ->with('error', 'Gagal menghapus absensi: ' . $e->getMessage());
        }
    }
}