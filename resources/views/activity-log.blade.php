<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">📋 Activity Log</h1>
        <p class="text-gray-600 dark:text-gray-400">Riwayat semua aktivitas pergerakan stok inventaris</p>
    </div>

    <!-- Search Filter -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-slate-800 shadow p-6">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Cari Aktivitas
                </label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari aktivitas, produk, atau user..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-gray-100"
                >
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-slate-800 shadow p-6">
        <div class="space-y-4">
            @forelse($activities as $activity)
                        <div class="flex gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                                    @if($activity->type === 'in') bg-green-100 dark:bg-green-900
                                    @elseif($activity->type === 'out') bg-red-100 dark:bg-red-900
                                    @elseif($activity->type === 'adjustment') bg-yellow-100 dark:bg-yellow-900
                                    @else bg-blue-100 dark:bg-blue-900
                                    @endif">
                                    {{ $activity->type_icon }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <span class="font-semibold">{{ $activity->user->name }}</span>
                                            <span class="text-gray-600 dark:text-gray-400">melakukan</span>
                                            <span class="font-semibold
                                                @if($activity->type === 'in') text-green-600 dark:text-green-400
                                                @elseif($activity->type === 'out') text-red-600 dark:text-red-400
                                                @elseif($activity->type === 'adjustment') text-yellow-600 dark:text-yellow-400
                                                @else text-blue-600 dark:text-blue-400
                                                @endif">
                                                {{ $activity->type_label }}
                                            </span>
                                            <span class="text-gray-600 dark:text-gray-400">pada produk</span>
                                            <span class="font-semibold">{{ $activity->product->name }}</span>
                                        </p>
                                        
                                        <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span>SKU: {{ $activity->product->sku }}</span>
                                            <span>Jumlah: 
                                                <span class="font-semibold
                                                    @if($activity->type === 'in' || $activity->type === 'return') text-green-600 dark:text-green-400
                                                    @else text-red-600 dark:text-red-400
                                                    @endif">
                                                    @if($activity->type === 'in' || $activity->type === 'return')+@else-@endif{{ $activity->quantity }}
                                                </span>
                                            </span>
                                            @if($activity->reference)
                                                <span>Ref: {{ $activity->reference }}</span>
                                            @endif
                                        </div>

                                        @if($activity->notes)
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 italic">
                                                "{{ $activity->notes }}"
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $activity->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
        @empty
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📭</div>
                <p class="text-gray-500 dark:text-gray-400">Tidak ada aktivitas ditemukan</p>
            </div>
        @endforelse
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
