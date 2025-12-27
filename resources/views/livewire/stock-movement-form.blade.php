<div class="space-y-4">
    <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Input Stok</h3>
            <button
                wire:click="$toggle('showModal')"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
            >
                ✕
            </button>
        </div>

        @if ($showModal)
            <form wire:submit="save" class="space-y-4">
                <!-- Jenis Pergerakan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Pergerakan <span class="text-red-500">*</span>
                    </label>
                    <select
                        wire:model="type"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white"
                    >
                        <option value="in">📥 Masuk (Pembelian)</option>
                        <option value="out">📤 Keluar (Penjualan)</option>
                        <option value="adjustment">⚙️ Penyesuaian</option>
                        <option value="return">↩️ Pengembalian</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jumlah ({{ $product->unit }}) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        wire:model="quantity"
                        placeholder="Masukkan jumlah"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white @error('quantity') border-red-500 @enderror"
                    />
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Referensi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Referensi (Invoice/PO)
                    </label>
                    <input
                        type="text"
                        wire:model="reference"
                        placeholder="No. Invoice atau PO"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white"
                    />
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
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white"
                    />
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Catatan
                    </label>
                    <textarea
                        wire:model="notes"
                        placeholder="Masukkan catatan tambahan"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-slate-700 dark:text-white"
                    ></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition"
                    >
                        Simpan Pergerakan
                    </button>
                    <button
                        type="button"
                        wire:click="$toggle('showModal')"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-900 dark:text-white font-medium py-2 px-4 rounded-lg transition"
                    >
                        Batal
                    </button>
                </div>
            </form>
        @else
            <button
                wire:click="$toggle('showModal')"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition"
            >
                + Input Pergerakan Stok
            </button>
        @endif
    </div>
</div>

@script
<script>
    Livewire.on('stock-updated', () => {
        $wire.$refresh();
        alert('Pergerakan stok berhasil dicatat!');
    });
</script>
@endscript
