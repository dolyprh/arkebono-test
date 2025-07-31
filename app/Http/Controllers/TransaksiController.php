<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Models\Item;
use App\Models\Karyawan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['karyawan', 'item']);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('karyawan', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('item', function($q) use ($search) {
                $q->where('nama_item', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_transaksi', $request->tanggal);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawans = Karyawan::orderBy('nama')->get();
        $items = Item::orderBy('nama_item')->get();
        
        return view('transaksi.create', compact('karyawans', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransaksiRequest $request)
    {
        $data = $request->validated();
        $data['tanggal_transaksi'] = $data['tanggal_transaksi'] ?? now()->toDateString();
        $data['bayar'] = $request->has('bayar');

        Transaksi::create($data);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $karyawans = Karyawan::orderBy('nama')->get();
        $items = Item::orderBy('nama_item')->get();
        
        return view('transaksi.edit', compact('transaksi', 'karyawans', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransaksiRequest $request, Transaksi $transaksi)
    {
        $data = $request->validated();
        $data['bayar'] = $request->has('bayar');

        $transaksi->update($data);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Get item price for AJAX request
     */
    public function getItemPrice($kode)
    {
        $item = Item::find($kode);
        return response()->json(['harga' => $item ? $item->harga : 0]);
    }
}
