<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">📊 Laporan Pergerakan Stok</h1>
        <p class="text-gray-600 dark:text-gray-400">Laporan lengkap pergerakan stok dengan filter dan export PDF</p>
    </div>
    <!-- Filters -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-slate-800 shadow p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cari Produk / Referensi
                            </label>
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="search"
                                placeholder="Cari berdasarkan nama produk, SKU, referensi..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-gray-100"
                            >
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Pergerakan
                            </label>
                            <select 
                                wire:model.live="type"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-gray-100"
                            >
                                <option value="">Semua Jenis</option>
                                <option value="in">Masuk</option>
                                <option value="out">Keluar</option>
                                <option value="adjustment">Penyesuaian</option>
                                <option value="return">Return</option>
                            </select>
                        </div>

                        <!-- Export Button -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Export
                            </label>
                            <button 
                                wire:click="exportPdf"
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg focus:ring-2 focus:ring-red-500 transition-colors"
                            >
                                📄 Export PDF
                            </button>
                        </div>
                    </div>
                </div>

    <!-- Table -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-slate-800 shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jenis
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Referensi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Supplier
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Catatan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        User
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($movements as $movement)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $movement->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <div>
                                                <div class="font-medium">{{ $movement->product->name }}</div>
                                                <div class="text-gray-500 dark:text-gray-400 text-xs">{{ $movement->product->sku }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($movement->type === 'in') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                @elseif($movement->type === 'out') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                @elseif($movement->type === 'adjustment') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                @else bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                                @endif">
                                                {{ $movement->type_icon }} {{ $movement->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                            @if($movement->type === 'in' || $movement->type === 'return') text-green-600 dark:text-green-400
                                            @else text-red-600 dark:text-red-400
                                            @endif">
                                            @if($movement->type === 'in' || $movement->type === 'return')+@else-@endif{{ $movement->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->reference ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->supplier ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->notes ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->user->name }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="text-4xl mb-2">📭</div>
                                            <p>Tidak ada data pergerakan stok</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $movements->links() }}
                    </div>
                </div>
            </div>
</div>
