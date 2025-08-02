<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Koperasi Arkebono - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        .toast.show {
            transform: translateX(0);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .delete-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .delete-modal.show {
            display: flex;
        }
        .delete-modal-content {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('transaksi.index') }}" class="flex items-center hover:text-blue-200 transition-colors duration-200">
                        <i class="fas fa-store text-2xl mr-3"></i>
                        <h1 class="text-xl font-bold">Sistem Koperasi Arkebono</h1>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('transaksi.index') }}" class="hover:text-blue-200 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('transaksi.index') }}" class="hover:text-blue-200 transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>Transaksi
                    </a>
                    <a href="{{ route('item.index') }}" class="hover:text-blue-200 transition-colors duration-200">
                        <i class="fas fa-box mr-2"></i>Master Item
                    </a>
                    <a href="{{ route('karyawan.index') }}" class="hover:text-blue-200 transition-colors duration-200">
                        <i class="fas fa-users mr-2"></i>Master Karyawan
                    </a>
                    <a href="{{ route('transaksi.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah Transaksi
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="toast bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    <span class="font-semibold">Terjadi kesalahan:</span>
                </div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="delete-modal" id="deleteModal">
        <div class="delete-modal-content">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600">Apakah Anda yakin ingin menghapus transaksi ini?</p>
                <p class="text-sm text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button type="button" id="cancelDelete" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="button" id="confirmDelete" 
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        // Toast notification
        $(document).ready(function() {
            $('.toast').each(function() {
                var toast = $(this);
                toast.addClass('show');
                
                setTimeout(function() {
                    toast.removeClass('show');
                    setTimeout(function() {
                        toast.remove();
                    }, 300);
                }, 4000);
            });

            // Delete confirmation modal
            let deleteForm = null;
            
            // Handle delete button clicks
            $('.delete-btn').click(function(e) {
                e.preventDefault();
                deleteForm = $(this).closest('form');
                $('#deleteModal').addClass('show');
            });

            // Handle cancel button
            $('#cancelDelete').click(function() {
                $('#deleteModal').removeClass('show');
                deleteForm = null;
            });

            // Handle confirm delete button
            $('#confirmDelete').click(function() {
                if (deleteForm) {
                    deleteForm.submit();
                }
                $('#deleteModal').removeClass('show');
                deleteForm = null;
            });

            // Close modal when clicking outside
            $('#deleteModal').click(function(e) {
                if (e.target === this) {
                    $('#deleteModal').removeClass('show');
                    deleteForm = null;
                }
            });

            // Close modal with Escape key
            $(document).keydown(function(e) {
                if (e.key === "Escape" && $('#deleteModal').hasClass('show')) {
                    $('#deleteModal').removeClass('show');
                    deleteForm = null;
                }
            });
        });
    </script>
</body>
</html> 