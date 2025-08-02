@extends('layouts.app')

@section('title', 'Master Karyawan')

@section('content')
<div class="card-hover bg-white shadow-xl rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="gradient-bg text-white px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-users text-3xl mr-4"></i>
                <div>
                    <h2 class="text-2xl font-bold">Master Karyawan</h2>
                    <p class="text-blue-100 mt-1">Kelola data master karyawan koperasi</p>
                </div>
            </div>
            <button onclick="openAddModal()" class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-plus mr-2"></i>Tambah Karyawan
            </button>
        </div>
    </div>

    <!-- Search Form -->
    <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Cari karyawan..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>
    </div>

    <!-- Karyawan List Table -->
    <div class="p-8">
        @if($karyawans->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada data karyawan</h3>
                <p class="text-gray-500">Mulai dengan menambahkan karyawan baru</p>
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
                                <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="karyawanTableBody">
                        @foreach($karyawans as $karyawan)
                            <tr class="hover:bg-blue-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration + ($karyawans->currentPage() - 1) * $karyawans->perPage() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                        {{ $karyawan->npk }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $karyawan->nama }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $karyawan->alamat }}">
                                        {{ $karyawan->alamat }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openEditModal('{{ $karyawan->npk }}', '{{ $karyawan->nama }}', '{{ $karyawan->alamat }}')" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                title="Edit Karyawan">
                                            <i class="fas fa-edit text-lg"></i>
                                        </button>
                                        <button onclick="deleteKaryawan('{{ $karyawan->npk }}')" 
                                                class="delete-btn text-red-600 hover:text-red-900 transition-colors duration-200"
                                                title="Hapus Karyawan">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $karyawans->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="karyawanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Tambah Karyawan Baru</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="karyawanForm">
                @csrf
                <div class="mb-4">
                    <label for="npk" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-card mr-2"></i>NPK
                    </label>
                    <input type="text" id="npk" name="npk" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan NPK">
                    <div id="npkError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Nama Karyawan
                    </label>
                    <input type="text" id="nama" name="nama" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan nama karyawan">
                    <div id="namaError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div class="mb-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                    </label>
                    <textarea id="alamat" name="alamat" required rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Masukkan alamat karyawan"></textarea>
                    <div id="alamatError" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" 
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="successToast" class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 mr-3"></i>
        <span id="successMessage"></span>
    </div>
</div>

<div id="errorToast" class="fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
        <span id="errorMessage"></span>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus karyawan ini?</p>
            <div class="flex justify-center space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button onclick="confirmDelete()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let isEditMode = false;
let currentKaryawanNpk = null;
let karyawanToDelete = null;

function openAddModal() {
    isEditMode = false;
    currentKaryawanNpk = null;
    document.getElementById('modalTitle').textContent = 'Tambah Karyawan Baru';
    document.getElementById('karyawanForm').reset();
    document.getElementById('npk').disabled = false;
    clearErrors();
    document.getElementById('karyawanModal').classList.remove('hidden');
}

function openEditModal(npk, nama, alamat) {
    isEditMode = true;
    currentKaryawanNpk = npk;
    document.getElementById('modalTitle').textContent = 'Edit Karyawan';
    document.getElementById('npk').value = npk;
    document.getElementById('npk').disabled = true;
    document.getElementById('nama').value = nama;
    document.getElementById('alamat').value = alamat;
    clearErrors();
    document.getElementById('karyawanModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('karyawanModal').classList.add('hidden');
    clearErrors();
}

function clearErrors() {
    document.querySelectorAll('[id$="Error"]').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
}

function showToast(message, type = 'success') {
    const toast = document.getElementById(type + 'Toast');
    const messageElement = document.getElementById(type + 'Message');
    
    messageElement.textContent = message;
    
    // Show toast
    toast.classList.remove('translate-x-full');
    toast.classList.add('translate-x-0');
    
    // Hide toast after 4 seconds
    setTimeout(() => {
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
    }, 4000);
}

function showSuccess(message) {
    showToast(message, 'success');
}

function showError(message) {
    showToast(message, 'error');
}

function deleteKaryawan(npk) {
    karyawanToDelete = npk;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    karyawanToDelete = null;
}

function confirmDelete() {
    if (!karyawanToDelete) return;
    
    fetch(`/karyawan/${karyawanToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            showSuccess(data.message);
            closeDeleteModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showError('Terjadi kesalahan: ' + data.message);
            closeDeleteModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menghapus karyawan');
        closeDeleteModal();
    });
}

document.getElementById('karyawanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = isEditMode ? `/karyawan/${currentKaryawanNpk}` : '/karyawan';
    const method = isEditMode ? 'PUT' : 'POST';
    
    // Tambahkan _method untuk Laravel method spoofing
    if (isEditMode) {
        formData.append('_method', 'PUT');
    }
    
    // Tambahkan CSRF token ke formData
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch(url, {
        method: 'POST', // Selalu gunakan POST untuk Laravel method spoofing
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            showSuccess(data.message);
            closeModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else if (data.errors) {
            clearErrors();
            Object.keys(data.errors).forEach(field => {
                const errorElement = document.getElementById(field + 'Error');
                if (errorElement) {
                    errorElement.textContent = data.errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            });
        } else {
            // Jika tidak ada status success tapi juga tidak ada errors, kemungkinan berhasil
            showSuccess('Karyawan berhasil disimpan');
            closeModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan dalam komunikasi. Silakan coba lagi.');
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#karyawanTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection 