<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::orderBy('nama')->paginate(10);
        return view('karyawan.index', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'npk' => 'required|string|max:10|unique:master_karyawan,npk',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255'
        ], [
            'npk.required' => 'NPK harus diisi',
            'npk.unique' => 'NPK sudah ada',
            'nama.required' => 'Nama karyawan harus diisi',
            'alamat.required' => 'Alamat harus diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Karyawan::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Karyawan berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error creating karyawan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan karyawan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $npk)
    {
        \Log::info('Update karyawan request received', [
            'npk' => $npk,
            'data' => $request->all()
        ]);
        
        try {
            $karyawan = Karyawan::findOrFail($npk);
        } catch (\Exception $e) {
            \Log::error('Karyawan not found: ' . $npk);
            return response()->json([
                'status' => 'error',
                'message' => 'Karyawan tidak ditemukan'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255'
        ], [
            'nama.required' => 'Nama karyawan harus diisi',
            'alamat.required' => 'Alamat harus diisi'
        ]);

        if ($validator->fails()) {
            \Log::warning('Validation failed for karyawan update', [
                'npk' => $npk,
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only(['nama', 'alamat']);
            \Log::info('Updating karyawan with data', [
                'npk' => $npk,
                'update_data' => $updateData
            ]);
            
            $karyawan->update($updateData);
            
            \Log::info('Karyawan updated successfully', ['npk' => $npk]);
            return response()->json([
                'status' => 'success',
                'message' => 'Karyawan berhasil diperbarui'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error updating karyawan: ' . $e->getMessage(), [
                'npk' => $npk,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui karyawan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($npk)
    {
        try {
            $karyawan = Karyawan::findOrFail($npk);
            $karyawan->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Karyawan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting karyawan: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus karyawan: ' . $e->getMessage()
            ], 500);
        }
    }
} 