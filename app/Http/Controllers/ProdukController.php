<?php
// app/Http/Controllers/ProdukController.php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|max:50|unique:m_produk,id_produk',
            'nama_produk' => 'required|max:100',
            'status_produk' => 'required|in:aktif,nonaktif',
            'satuan' => 'required|max:20',
        ]);

        Produk::create([
            'id_produk' => strtoupper($request->id_produk),
            'nama_produk' => $request->nama_produk,
            'status_produk' => $request->status_produk,
            'satuan' => strtolower($request->satuan),
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|max:100',
            'status_produk' => 'required|in:aktif,nonaktif',
            'satuan' => 'required|max:20',
        ]);

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'status_produk' => $request->status_produk,
            'satuan' => strtolower($request->satuan),
        ]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
