<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Inventaris - Kantin Kenanga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-slate-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-slate-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            📦 Manajemen Inventaris Kantin Kenanga
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                            Dashboard
                        </a>
                        <span class="text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <livewire:inventory-dashboard />
            </div>
        </div>
    </div>

    <!-- Modal backdrop and content will be rendered by Livewire -->
    <script>
        // Global event listener untuk membuka modal form
        window.addEventListener('open-product-form', () => {
            alert('Modal untuk tambah produk akan dibuka di sini');
        });
    </script>
</body>
</html>
