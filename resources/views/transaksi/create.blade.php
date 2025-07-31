@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Transaksi Baru</h2>
    </div>

    <form action="{{ route('transaksi.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- NPK -->
            <div>
                <label for="npk" class="block text-sm font-medium text-gray-700 mb-2">NPK Karyawan</label>
                <select name="npk" id="npk" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih NPK</option>
                    @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->npk }}" {{ old('npk') == $karyawan->npk ? 'selected' : '' }}>
                            {{ $karyawan->npk }} - {{ $karyawan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kode Item -->
            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode Item</label>
                <select name="kode" id="kode" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->kode }}" data-harga="{{ $item->harga }}" 
                                {{ old('kode') == $item->kode ? 'selected' : '' }}>
                            {{ $item->kode }} - {{ $item->nama_item }} (Rp {{ number_format($item->harga, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity -->
            <div>
                <label for="qty" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="qty" id="qty" value="{{ old('qty', 1) }}" min="1" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Harga -->
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga', 0) }}" step="0.01" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Tanggal Transaksi -->
            <div>
                <label for="tanggal_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
                <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" 
                       value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Bayar -->
            <div class="flex items-center">
                <input type="checkbox" name="bayar" id="bayar" value="1" 
                       {{ old('bayar') ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="bayar" class="ml-2 block text-sm text-gray-900">
                    Sudah Bayar
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('transaksi.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Auto-fill harga when item is selected
    $('#kode').change(function() {
        var selectedOption = $(this).find('option:selected');
        var harga = selectedOption.data('harga');
        if (harga) {
            $('#harga').val(harga);
        }
    });

    // Calculate total when qty or harga changes
    $('#qty, #harga').on('input', function() {
        var qty = parseInt($('#qty').val()) || 0;
        var harga = parseFloat($('#harga').val()) || 0;
        var total = qty * harga;
        // You can display total somewhere if needed
    });
});
</script>
@endsection 