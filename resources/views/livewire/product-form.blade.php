<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $isEditing ? 'Edit Produk' : 'Tambah Produk Baru' }}
        </h2>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Row 1: Nama & SKU -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Produk -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    wire:model="name"
                    placeholder="Masukkan nama produk"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- SKU -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    SKU <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    wire:model="sku"
                    placeholder="Masukkan SKU"
                    @if($isEditing) disabled @endif
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sku') border-red-500 @enderror @if($isEditing) opacity-60 bg-gray-100 dark:bg-slate-600 @endif"
                />
                @error('sku')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Row 2: Kategori & Harga -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select
                    wire:model="category_id"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Harga (Rp) <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    step="0.01"
                    wire:model="price"
                    placeholder="Masukkan harga"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                />
                @error('price')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Row 3: Stok Saat Ini & Unit -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Stok Saat Ini -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Stok Saat Ini <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    wire:model="quantity"
                    placeholder="Masukkan jumlah stok"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                />
                @error('quantity')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Unit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Unit <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    wire:model="unit"
                    placeholder="pcs, box, kg, botol, dll"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unit') border-red-500 @enderror"
                />
                @error('unit')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Row 4: Min & Max Stok -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Stok Minimum -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Stok Minimum <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    wire:model="min_quantity"
                    placeholder="Masukkan stok minimum"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('min_quantity') border-red-500 @enderror"
                />
                @error('min_quantity')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Stok Maksimum -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Stok Maksimum <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    wire:model="max_quantity"
                    placeholder="Masukkan stok maksimum"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_quantity') border-red-500 @enderror"
                />
                @error('max_quantity')
                    <p class="mt-1 text-sm text-red-500 flex items-center">
                        <span class="mr-1">❌</span> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Deskripsi
            </label>
            <textarea
                wire:model="description"
                placeholder="Masukkan deskripsi produk"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
            ></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-500 flex items-center">
                    <span class="mr-1">❌</span> {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Supplier -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Supplier
            </label>
            <input
                type="text"
                wire:model="supplier"
                placeholder="Masukkan nama supplier"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <!-- Form Info Alert -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <p class="text-sm text-blue-800 dark:text-blue-200">
                <span class="font-semibold">ℹ️ Informasi:</span> Pastikan semua field dengan tanda <span class="text-red-500">*</span> sudah terisi.
                @if ($isEditing)
                    <br>SKU tidak bisa diubah setelah produk dibuat.
                @endif
            </p>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button
                type="submit"
                class="flex-1 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2"
            >
                <span>💾</span>
                {{ $isEditing ? 'Perbarui Produk' : 'Simpan Produk' }}
            </button>
            <button
                type="button"
                wire:click="closeModal"
                class="flex-1 bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-900 dark:text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2"
            >
                <span>❌</span>
                Batal
            </button>
        </div>
    </form>
</div>

@script
<script>
    Livewire.on('close-modal', () => {
        // Modal akan ditutup oleh parent component
    });
</script>
@endscript
