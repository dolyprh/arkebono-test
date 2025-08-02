@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="card-hover bg-white shadow-xl rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg text-white px-8 py-6">
        <div class="flex items-center">
            <i class="fas fa-edit text-3xl mr-4"></i>
            <div>
                <h2 class="text-2xl font-bold">Edit Transaksi</h2>
                <p class="text-blue-100 mt-1">Update informasi transaksi yang dipilih</p>
            </div>
        </div>
    </div>

    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" class="p-8" id="transaksiForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- NPK (Readonly) -->
            <div class="space-y-2">
                <label for="npk" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-user mr-2 text-blue-600"></i>NPK Karyawan
                </label>
                <input type="text" value="{{ $transaksi->npk }} - {{ $transaksi->karyawan->nama }}" readonly
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                <input type="hidden" name="npk" value="{{ $transaksi->npk }}">
            </div>

            <!-- Kode Item (Readonly) -->
            <div class="space-y-2">
                <label for="kode" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-box mr-2 text-green-600"></i>Kode Item
                </label>
                <input type="text" value="{{ $transaksi->kode }} - {{ $transaksi->item->nama_item }}" readonly
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                <input type="hidden" name="kode" value="{{ $transaksi->kode }}">
            </div>

            <!-- Tanggal Transaksi (Readonly) -->
            <div class="space-y-2">
                <label for="tanggal_transaksi" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-calendar mr-2 text-purple-600"></i>Tanggal Transaksi
                </label>
                <input type="text" value="{{ $transaksi->tanggal_transaksi->format('d F Y') }}" readonly
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                <input type="hidden" name="tanggal_transaksi" value="{{ $transaksi->tanggal_transaksi->format('Y-m-d') }}">
            </div>

            <!-- Quantity (Editable) -->
            <div class="space-y-2">
                <label for="qty" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-sort-numeric-up mr-2 text-orange-600"></i>Quantity
                </label>
                <input type="number" name="qty" id="qty" value="{{ old('qty', $transaksi->qty) }}" min="1" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
            </div>

            <!-- Harga (Readonly) -->
            <div class="space-y-2">
                <label for="harga" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-tag mr-2 text-red-600"></i>Harga (per unit)
                </label>
                <input type="text" value="Rp {{ number_format($transaksi->harga, 0, ',', '.') }}" readonly
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                <input type="hidden" name="harga" value="{{ $transaksi->harga }}">
            </div>

            <!-- Total (Readonly) -->
            <div class="space-y-2">
                <label for="total_display" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-calculator mr-2 text-indigo-600"></i>Total
                </label>
                <div id="total_display" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 text-gray-800 font-bold text-lg">
                    Rp {{ number_format($transaksi->qty * $transaksi->harga, 0, ',', '.') }}
                </div>
            </div>

            <!-- Bayar (Editable) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-credit-card mr-2 text-teal-600"></i>Tipe Bayar
                </label>
                <div class="space-y-3 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center">
                        <input type="radio" name="bayar" id="bayar_lunas" value="1" 
                               {{ old('bayar', $transaksi->bayar) == '1' ? 'checked' : '' }}
                               class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300">
                        <label for="bayar_lunas" class="ml-3 block text-sm font-medium text-gray-900">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>Lunas
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" name="bayar" id="bayar_cicil" value="0" 
                               {{ old('bayar', $transaksi->bayar) == '0' ? 'checked' : '' }}
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
                <i class="fas fa-undo mr-2"></i>Reset
            </button>
            <button type="submit" 
                    class="btn-primary text-white px-8 py-3 rounded-lg font-semibold text-lg">
                <i class="fas fa-save mr-2"></i>Update
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Calculate total when qty changes
    $('#qty').on('input', function() {
        calculateTotal();
    });

    function calculateTotal() {
        var qty = parseInt($('#qty').val()) || 0;
        var harga = parseFloat({{ $transaksi->harga }});
        var total = qty * harga;
        $('#total_display').text('Rp ' + total.toLocaleString('id-ID'));
    }

    // Clear button functionality (reset to original values)
    $('#clearBtn').click(function() {
        $('#qty').val({{ $transaksi->qty }});
        @if($transaksi->bayar)
            $('#bayar_lunas').prop('checked', true);
        @else
            $('#bayar_cicil').prop('checked', true);
        @endif
        calculateTotal();
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endsection 