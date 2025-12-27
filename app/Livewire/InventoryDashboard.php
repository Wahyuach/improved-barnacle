<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class InventoryDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $showProductForm = false;
    public $editingProductId = null;
    // key to force child ProductForm to remount when opening add/edit
    public $productFormKey = null;
    
    // Notification properties
    public $notification = '';
    public $showNotification = false;

    #[Computed]
    public function editingProduct()
    {
        return $this->editingProductId ? Product::find($this->editingProductId) : null;
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%");
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->where('is_active', true)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    #[Computed]
    public function categories()
    {
        return Category::all();
    }

    #[Computed]
    public function totalProducts()
    {
        return Product::where('is_active', true)->count();
    }

    #[Computed]
    public function lowStockProducts()
    {
        return Product::lowStock()->where('is_active', true)->count();
    }

    #[Computed]
    public function totalValue()
    {
        return Product::where('is_active', true)
            ->get()
            ->sum(fn ($p) => $p->quantity * $p->price);
    }

    #[Computed]
    public function totalCategories()
    {
        return Category::count();
    }

    #[Computed]
    public function totalSuppliers()
    {
        return Product::where('is_active', true)
            ->whereNotNull('supplier')
            ->distinct('supplier')
            ->count('supplier');
    }

    #[Computed]
    public function movementsToday()
    {
        return StockMovement::whereDate('created_at', today())->count();
    }

    #[Computed]
    public function movementsThisWeek()
    {
        return StockMovement::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
    }

    #[Computed]
    public function productsNeedingReorder()
    {
        return Product::whereColumn('quantity', '<=', 'min_quantity')
            ->where('is_active', true)
            ->count();
    }

    #[Computed]
    public function recentActivities()
    {
        return StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    #[Computed]
    public function topValueProducts()
    {
        return Product::where('is_active', true)
            ->get()
            ->sortByDesc(fn ($p) => $p->quantity * $p->price)
            ->take(5);
    }

    #[Computed]
    public function fastMovingProducts()
    {
        return Product::where('is_active', true)
            ->withCount(['stockMovements' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('stock_movements_count', 'desc')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function lowStockAlerts()
    {
        return Product::whereColumn('quantity', '<=', 'min_quantity')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function stockByCategory()
    {
        return Product::where('is_active', true)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($products, $categoryId) {
                $category = $products->first()->category;
                return [
                    'category' => $category->name,
                    'total_value' => $products->sum(fn ($p) => $p->quantity * $p->price),
                    'count' => $products->count(),
                ];
            })
            ->sortByDesc('total_value')
            ->take(6);
    }

    #[Computed]
    public function movementsTrend()
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = [
                'date' => $date->format('d M'),
                'in' => StockMovement::whereDate('created_at', $date)
                    ->whereIn('type', ['in', 'return'])->count(),
                'out' => StockMovement::whereDate('created_at', $date)
                    ->whereIn('type', ['out', 'adjustment'])->count(),
            ];
        }
        return $days;
    }

    #[Computed]
    public function suppliersList()
    {
        return Product::where('is_active', true)
            ->whereNotNull('supplier')
            ->get()
            ->groupBy('supplier')
            ->map(function ($products, $supplier) {
                return [
                    'name' => $supplier,
                    'product_count' => $products->count(),
                    'total_value' => $products->sum(fn ($p) => $p->quantity * $p->price),
                ];
            })
            ->sortByDesc('product_count')
            ->take(5);
    }

    public function updateSort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openAddForm()
    {
        $this->showProductForm = true;
        $this->editingProductId = null;
        // Ensure a fresh instance of the ProductForm component
        $this->productFormKey = 'product-form-add-'.time();
        // Notify child explicitly to mount in add mode (safeguard if instance reused)
        $this->dispatch('productFormMode', mode: 'add', productId: null);
    }

    public function openEditForm($productId)
    {
        $this->showProductForm = true;
        $this->editingProductId = $productId;
        // Ensure a fresh instance of the ProductForm component for the given product
        $this->productFormKey = 'product-form-edit-'.$productId.'-'.time();
        // Notify child explicitly to mount in edit mode with the product id
        $this->dispatch('productFormMode', mode: 'edit', productId: $productId);
    }

    public function closeForm()
    {
        $this->showProductForm = false;
        $this->editingProductId = null;
        $this->productFormKey = null;
    }

    #[On('product-saved')]
    public function handleProductSaved($message)
    {
        $this->notification = $message;
        $this->showNotification = true;
        $this->closeForm();
    }

    #[On('product-deleted')]
    public function handleProductDeleted()
    {
        $this->notification = 'Produk berhasil dihapus!';
        $this->showNotification = true;
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->update(['is_active' => false]);
            $this->dispatch('product-deleted');
        }
    }

    public function render()
    {
        return view('livewire.inventory-dashboard');
    }
}
