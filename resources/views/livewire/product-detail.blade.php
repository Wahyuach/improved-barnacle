<div class="space-y-6">
    <!-- Product Header -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">SKU: <code class="bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded">{{ $product->sku }}</code></p>
            </div>
            <a href="{{ route('inventory.dashboard') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">← Kembali</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <!-- Current Stock -->
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Stok Saat Ini</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->quantity }} {{ $product->unit }}</p>
            </div>

            <!-- Min/Max -->
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Min - Max</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product->min_quantity }} - {{ $product->max_quantity }}</p>
            </div>

            <!-- Price -->
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Harga</p>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>

            <!-- Total Value -->
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Nilai</p>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($product->quantity * $product->price, 0, ',', '.') }}</p>
            </div>
        </div>

        @if ($product->description)
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">Deskripsi</p>
                <p class="text-gray-900 dark:text-gray-100 mt-2">{{ $product->description }}</p>
            </div>
        @endif

        @if ($product->supplier)
            <div class="mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">Supplier</p>
                <p class="text-gray-900 dark:text-gray-100">{{ $product->supplier }}</p>
            </div>
        @endif
    </div>

    <!-- Stock Movement Form -->
    <livewire:stock-movement-form :product="$product" />

    <!-- Stock Movement History -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pergerakan Stok</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Referensi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-600">
                    @forelse ($this->stockMovements as $movement)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $movement->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($movement->type === 'in') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200
                                    @elseif($movement->type === 'out') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200
                                    @elseif($movement->type === 'adjustment') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
                                    @endif
                                ">
                                    {{ $movement->type_icon }} {{ $movement->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $movement->quantity }} {{ $product->unit }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $movement->user->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                @if ($movement->reference)
                                    <code class="bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded text-xs">{{ $movement->reference }}</code>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                @if ($movement->supplier)
                                    <span class="font-medium">{{ $movement->supplier }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $movement->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada riwayat pergerakan stok
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-slate-700 px-6 py-4 border-t border-gray-200 dark:border-slate-600">
            {{ $this->stockMovements->links() }}
        </div>
    </div>
</div>
