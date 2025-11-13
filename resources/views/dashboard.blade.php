<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <!-- Inventory Management Card -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-slate-800 shadow hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📦 Manajemen Inventaris</h2>
                        <div class="text-4xl">🏪</div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Kelola stok barang, monitor level inventaris, dan catat pergerakan stok untuk kantin Kenanga.
                    </p>
                    <a href="{{ route('inventory.dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        Buka Inventaris
                    </a>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-slate-800 shadow">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📊 Statistik Cepat</h2>
                    @php
                        $totalProducts = \App\Models\Product::where('is_active', true)->count();
                        $lowStockCount = \App\Models\Product::lowStock()->where('is_active', true)->count();
                        $totalValue = \App\Models\Product::where('is_active', true)->get()->sum(fn ($p) => $p->quantity * $p->price);
                    @endphp
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Total Produk:</span>
                            <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $totalProducts }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Stok Rendah:</span>
                            <span class="font-bold text-lg text-red-600">{{ $lowStockCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Total Nilai:</span>
                            <span class="font-bold text-lg text-green-600">Rp {{ number_format($totalValue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
