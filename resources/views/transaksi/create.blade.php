@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="card-hover bg-white shadow-xl rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg text-white px-8 py-6">
        <div class="flex items-center">
            <i class="fas fa-plus-circle text-3xl mr-4"></i>
            <div>
                <h2 class="text-2xl font-bold">Tambah Transaksi Baru</h2>
                <p class="text-blue-100 mt-1">Isi form di bawah untuk menambahkan transaksi baru</p>
            </div>
        </div>
    </div>

    <form action="{{ route('transaksi.store') }}" method="POST" class="p-8" id="transaksiForm">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- NPK -->
            <div class="space-y-2">
                <label for="npk" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-user mr-2 text-blue-600"></i>NPK Karyawan
                </label>
                <select name="npk" id="npk" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="">Pilih NPK Karyawan</option>
                    @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->npk }}" {{ old('npk') == $karyawan->npk ? 'selected' : '' }}>
                            {{ $karyawan->npk }} - {{ $karyawan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kode Item -->
            <div class="space-y-2">
                <label for="kode" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-box mr-2 text-green-600"></i>Kode Item
                </label>
                <select name="kode" id="kode" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                    <option value="">Pilih Item</option>
                    @foreach($items as $item)
                        <option value="{{ $item->kode }}" data-harga="{{ $item->harga }}" 
                                {{ old('kode') == $item->kode ? 'selected' : '' }}>
                            {{ $item->kode }} - {{ $item->nama_item }} (Rp {{ number_format($item->harga, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Transaksi -->
            <div class="space-y-2">
                <label for="tanggal_transaksi" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-calendar mr-2 text-purple-600"></i>Tanggal Transaksi
                </label>
                <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" 
                       value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
            </div>

            <!-- Quantity -->
            <div class="space-y-2">
                <label for="qty" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-sort-numeric-up mr-2 text-orange-600"></i>Quantity
                </label>
                <input type="number" name="qty" id="qty" value="{{ old('qty', 1) }}" min="1" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
            </div>

            <!-- Harga -->
            <div class="space-y-2">
                <label for="harga" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-tag mr-2 text-red-600"></i>Harga (per unit)
                </label>
                <input type="number" name="harga" id="harga" value="{{ old('harga', 0) }}" step="0.01" required readonly
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>

            <!-- Total -->
            <div class="space-y-2">
                <label for="total_display" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-calculator mr-2 text-indigo-600"></i>Total
                </label>
                <div id="total_display" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 text-gray-800 font-bold text-lg">
                    Rp 0
                </div>
            </div>

            <!-- Bayar -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-credit-card mr-2 text-teal-600"></i>Tipe Bayar
                </label>
                <div class="space-y-3 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center">
                        <input type="radio" name="bayar" id="bayar_lunas" value="1" 
                               {{ old('bayar', '1') == '1' ? 'checked' : '' }}
                               class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300">
                        <label for="bayar_lunas" class="ml-3 block text-sm font-medium text-gray-900">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>Lunas
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="bayar" id="bayar_cicil" value="0" 
                               {{ old('bayar') == '0' ? 'checked' : '' }}
                               class="h-5 w-5 text-yellow-600 focus:ring-yellow-500 border-gray-300">
                        <label for="bayar_cicil" class="ml-3 block text-sm font-medium text-gray-900">
                            <i class="fas fa-clock text-yellow-600 mr-2"></i>Cicil
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <button type="button" id="clearBtn" 
                    class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-eraser mr-2"></i>Clear
            </button>
            <button type="submit" 
                    class="btn-primary text-white px-8 py-3 rounded-lg font-semibold text-lg">
                <i class="fas fa-save mr-2"></i>Simpan
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
            calculateTotal();
        }
    });

    // Calculate total when qty or harga changes
    $('#qty').on('input', function() {
        calculateTotal();
    });

    function calculateTotal() {
        var qty = parseInt($('#qty').val()) || 0;
        var harga = parseFloat($('#harga').val()) || 0;
        var total = qty * harga;
        $('#total_display').text('Rp ' + total.toLocaleString('id-ID'));
    }

    // Clear button functionality
    $('#clearBtn').click(function() {
        $('#transaksiForm')[0].reset();
        $('#harga').val('');
        $('#total_display').text('Rp 0');
        $('#tanggal_transaksi').val('{{ date('Y-m-d') }}');
        $('#bayar_lunas').prop('checked', true);
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endsection 