<?php
// app/Http/Controllers/KaryawanController.php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

/**
 * @method \Illuminate\Routing\Controller middleware(callable $callback)
 */
class KaryawanController extends Controller
{
    /**
     * Constructor untuk middleware authorization
     */
    public function __construct()
    {
        // Apply middleware ke semua method kecuali yang dikecualikan
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            $user = Auth::user();

            // Hanya supervisor dan pemilik yang bisa akses
            if (!in_array($user->role, ['supervisor', 'pemilik'])) {
                abort(403, 'Unauthorized access. Hanya supervisor dan pemilik yang bisa mengakses halaman ini.');
            }

            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ORDER BY id_karyawan atau nama_karyawan (bukan created_at)
        $karyawans = Karyawan::orderBy('id_karyawan', 'desc')->get();
        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_karyawan' => 'required|string|max:100',
            'jabatan' => 'required|in:pemilik,supervisor,operator',
            'gaji_harian' => 'required|numeric|min:0',
            'email' => 'nullable|email|max:100|unique:m_karyawan,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status_karyawan' => 'required|in:aktif,nonaktif',
            'jml_target' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Karyawan::create([
                'nama_karyawan' => $request->nama_karyawan,
                'jabatan' => $request->jabatan,
                'gaji_harian' => $request->gaji_harian,
                'email' => $request->email,
                'password' => $request->password,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'status_karyawan' => $request->status_karyawan,
                'jml_target' => $request->jml_target,
                'total_hadir' => 0
            ]);

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_karyawan' => 'required|string|max:100',
            'jabatan' => 'required|in:pemilik,supervisor,operator',
            'gaji_harian' => 'required|numeric|min:0',
            'email' => 'nullable|email|max:100|unique:m_karyawan,email,' . $id . ',id_karyawan',
            'password' => 'nullable|min:6',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'status_karyawan' => 'required|in:aktif,nonaktif',
            'jml_target' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // Jika password kosong, hapus dari data
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $karyawan->update($data);

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui karyawan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            $karyawan->delete();

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Gagal menghapus karyawan: ' . $e->getMessage());
        }
    }
}
