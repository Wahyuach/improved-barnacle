<div class="space-y-6">
    <!-- Notification -->
    @if ($showNotification && $notification)
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => { show = false; $wire.showNotification = false; }, 5000)" 
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" 
             role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ $notification }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" 
                  @click="show = false; $wire.showNotification = false">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Tutup</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $this->totalProducts }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $this->totalCategories }} Kategori</p>
                </div>
                <div class="text-4xl">📦</div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Butuh Reorder</p>
                    <p class="text-3xl font-bold text-red-600">{{ $this->productsNeedingReorder }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $this->lowStockProducts }} Stok Rendah</p>
                </div>
                <div class="text-4xl">⚠️</div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Nilai</p>
                    <p class="text-3xl font-bold text-green-600">Rp {{ number_format($this->totalValue, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Nilai Inventaris</p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pergerakan</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $this->movementsToday }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Hari ini | {{ $this->movementsThisWeek }} Minggu ini</p>
                </div>
                <div class="text-4xl">📊</div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Cari Produk
                </label>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Cari nama atau SKU..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Kategori
                </label>
                <select
                    wire:model.live="categoryFilter"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white"
                >
                    <option value="">Semua Kategori</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button
                    wire:click="openAddForm"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    + Tambah Produk
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700 border-b border-gray-200 dark:border-slate-600">
                    <tr>
                        <th
                            wire:click="updateSort('name')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-600"
                        >
                            Nama Produk
                            @if ($this->sortBy === 'name')
                                {{ $this->sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                        <th
                            wire:click="updateSort('quantity')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-600"
                        >
                            Stok
                            @if ($this->sortBy === 'quantity')
                                {{ $this->sortDirection === 'asc' ? '↑' : '↓' }}
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-600">
                    @forelse ($this->products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <code class="bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded">{{ $product->sku }}</code>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $product->category->name }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <span class="text-lg">{{ $product->quantity }} {{ $product->unit }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($product->stock_status === 'low')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                        ⚠️ Rendah
                                    </span>
                                @elseif ($product->stock_status === 'high')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                        📈 Berlebih
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                        ✓ Normal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2 flex">
                                <a
                                    href="{{ route('inventory.product', $product) }}"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                                >
                                    Detail
                                </a>
                                <button
                                    wire:click="openEditForm({{ $product->id }})"
                                    class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 font-medium"
                                >
                                    Edit
                                </button>
                                <button
                                    wire:click="deleteProduct({{ $product->id }})"
                                    wire:confirm="Yakin hapus produk ini?"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="text-4xl mb-2">📭</div>
                                <p>Tidak ada produk yang sesuai dengan filter</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-slate-700 px-6 py-4 border-t border-gray-200 dark:border-slate-600">
            {{ $this->products->links() }}
        </div>
    </div>

    <!-- Modal Form Produk -->
    @if ($showProductForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 p-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $editingProductId ? 'Edit Produk' : 'Tambah Produk Baru' }}
                    </h2>
                    <button 
                        wire:click="closeForm"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-2xl"
                    >
                        ✕
                    </button>
                </div>
                <div class="p-6">
                    {{-- Separate components for Add and Edit --}}
                    @if ($editingProductId)
                        {{-- Edit mode: EditProductForm component --}}
                        <livewire:edit-product-form :product="$this->editingProduct" wire:key="{{ $productFormKey }}" />
                    @else
                        {{-- Add mode: AddProductForm component --}}
                        <livewire:add-product-form wire:key="{{ $productFormKey }}" />
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    // Listen for close-form event from child components
    Livewire.on('close-form', () => {
        $wire.closeForm();
    });
</script>
@endscript
