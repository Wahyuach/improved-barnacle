<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

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
