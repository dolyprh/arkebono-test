@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="card-hover bg-white shadow-xl rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg text-white px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-shopping-cart text-3xl mr-4"></i>
                <div>
                    <h2 class="text-2xl font-bold">Daftar Transaksi Koperasi</h2>
                    <p class="text-blue-100 mt-1">Kelola semua transaksi koperasi dengan mudah</p>
                </div>
            </div>
            <a href="{{ route('transaksi.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-plus mr-2"></i>Tambah Transaksi
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
        <form method="GET" action="{{ route('transaksi.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-2"></i>Cari Nama Karyawan/Item
                </label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama karyawan atau item..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2"></i>Tanggal
                </label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label for="npk" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2"></i>NPK Karyawan
                </label>
                <select name="npk" id="npk" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua NPK</option>
                    @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->npk }}" {{ request('npk') == $karyawan->npk ? 'selected' : '' }}>
                            {{ $karyawan->npk }} - {{ $karyawan->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tipe_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-credit-card mr-2"></i>Tipe Bayar
                </label>
                <select name="tipe_bayar" id="tipe_bayar" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Tipe</option>
                    <option value="1" {{ request('tipe_bayar') === '1' ? 'selected' : '' }}>Lunas</option>
                    <option value="0" {{ request('tipe_bayar') === '0' ? 'selected' : '' }}>Cicil</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary text-white py-3 rounded-lg font-semibold flex-1">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <a href="{{ route('transaksi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Transaction List Table -->
    <div class="p-8">
        @if($transaksis->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada data transaksi</h3>
                <p class="text-gray-500">Mulai dengan menambahkan transaksi baru</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-2"></i>No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-id-card mr-2"></i>NPK
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-user mr-2"></i>Nama Karyawan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-box mr-2"></i>Item
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-sort-numeric-up mr-2"></i>Qty
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-tag mr-2"></i>Harga
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-calculator mr-2"></i>Total
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-credit-card mr-2"></i>Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Tanggal
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transaksis as $transaksi)
                            <tr class="hover:bg-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $transaksi->npk }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaksi->karyawan->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $transaksi->kode }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaksi->item->nama_item ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $transaksi->qty }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($transaksi->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($transaksi->qty * $transaksi->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaksi->bayar)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Cicil
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('transaksi.edit', $transaksi->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                           title="Edit Transaksi">
                                            <i class="fas fa-edit text-lg"></i>
                                        </a>
                                        <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" 
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="delete-btn text-red-600 hover:text-red-900 transition-colors duration-200"
                                                    title="Hapus Transaksi">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 