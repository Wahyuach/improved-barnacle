<x-layouts.app :title="__('Dashboard')">
    @php
        $totalProducts = \App\Models\Product::where('is_active', true)->count();
        $totalCategories = \App\Models\Category::count();
        $totalSuppliers = \App\Models\Product::where('is_active', true)->whereNotNull('supplier')->distinct('supplier')->count('supplier');
        $lowStockProducts = \App\Models\Product::lowStock()->where('is_active', true)->count();
        $productsNeedingReorder = \App\Models\Product::whereColumn('quantity', '<=', 'min_quantity')->where('is_active', true)->count();
        $totalValue = \App\Models\Product::where('is_active', true)->get()->sum(fn ($p) => $p->quantity * $p->price);
        $movementsToday = \App\Models\StockMovement::whereDate('created_at', today())->count();
        $movementsThisWeek = \App\Models\StockMovement::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        $recentActivities = \App\Models\StockMovement::with(['product', 'user'])->orderBy('created_at', 'desc')->take(10)->get();
        
        $lowStockAlerts = \App\Models\Product::whereColumn('quantity', '<=', 'min_quantity')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();
    @endphp

    <div class="space-y-6">

        <!-- Header Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $totalCategories }} Kategori</p>
                    </div>
                    <div class="text-4xl">📦</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Butuh Reorder</p>
                        <p class="text-3xl font-bold text-red-600">{{ $productsNeedingReorder }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $lowStockProducts }} Stok Rendah</p>
                    </div>
                    <div class="text-4xl">⚠️</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Nilai</p>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Nilai Inventaris</p>
                    </div>
                    <div class="text-4xl">💰</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pergerakan</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $movementsToday }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Hari ini | {{ $movementsThisWeek }} Minggu ini</p>
                    </div>
                    <div class="text-4xl">📊</div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        @if($lowStockAlerts->count() > 0)
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg p-6 shadow">
            <div class="flex items-start">
                <div class="text-3xl mr-4">🚨</div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-3">Peringatan Stok Rendah</h3>
                    <div class="space-y-2">
                        @foreach($lowStockAlerts as $product)
                            <div class="flex items-center justify-between bg-white dark:bg-slate-800 rounded p-3">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">({{ $product->category->name }})</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-red-600 dark:text-red-400 font-bold">{{ $product->quantity }} {{ $product->unit }}</span>
                                    <span class="text-xs text-gray-500">Min: {{ $product->min_quantity }}</span>
                                    <a href="{{ route('inventory.product', $product) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Restock →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity (2 columns) -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">📋 Aktivitas Terbaru</h2>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div class="space-y-3">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start gap-3 p-3 rounded hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                <div class="text-2xl">{{ $activity->type_icon }}</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-semibold">{{ $activity->product->name }}</span>
                                        <span class="text-gray-600 dark:text-gray-400">
                                            - {{ $activity->type_label }}
                                            <span class="font-medium @if($activity->type === 'in' || $activity->type === 'return') text-green-600 @else text-red-600 @endif">
                                                @if($activity->type === 'in' || $activity->type === 'return')+@else-@endif{{ $activity->quantity }}
                                            </span>
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $activity->user->name }} • {{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-8">Belum ada aktivitas</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">⚡ Aksi Cepat</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('inventory.dashboard') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition text-center">
                        📦 Kelola Inventaris
                    </a>
                    <a href="{{ route('inventory.stock-movements') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 px-4 rounded-lg transition text-center">
                        📊 Laporan Pergerakan Stok
                    </a>
                    <a href="{{ route('inventory.activity-log') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition text-center">
                        📋 Lihat Activity Log
                    </a>
                    <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📦 Supplier: {{ $totalSuppliers }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $totalProducts }} produk aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
