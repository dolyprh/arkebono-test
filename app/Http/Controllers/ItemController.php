<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('nama_item')->paginate(10);
        return view('item.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:10|unique:master_item,kode',
            'nama_item' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0'
        ], [
            'kode.required' => 'Kode item harus diisi',
            'kode.unique' => 'Kode item sudah ada',
            'nama_item.required' => 'Nama item harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga minimal 0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Item::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Item berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error creating item: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $kode)
    {
        \Log::info('Update item request received', [
            'kode' => $kode,
            'data' => $request->all()
        ]);
        
        try {
            $item = Item::findOrFail($kode);
        } catch (\Exception $e) {
            \Log::error('Item not found: ' . $kode);
            return response()->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_item' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0'
        ], [
            'nama_item.required' => 'Nama item harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga minimal 0'
        ]);

        if ($validator->fails()) {
            \Log::warning('Validation failed for item update', [
                'kode' => $kode,
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only(['nama_item', 'harga']);
            \Log::info('Updating item with data', [
                'kode' => $kode,
                'update_data' => $updateData
            ]);
            
            $item->update($updateData);
            
            \Log::info('Item updated successfully', ['kode' => $kode]);
            return response()->json([
                'status' => 'success',
                'message' => 'Item berhasil diperbarui'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating item: ' . $e->getMessage(), [
                'kode' => $kode,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($kode)
    {
        try {
            $item = Item::findOrFail($kode);
            $item->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Item berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting item: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus item: ' . $e->getMessage()
            ], 500);
        }
    }
} 