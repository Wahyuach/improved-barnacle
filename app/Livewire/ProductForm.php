<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductForm extends Component
{
    #[\Livewire\Attributes\On('productFormMode')]
    public function onProductFormMode($mode, $productId = null)
    {
        if ($mode === 'add') {
            $this->resetFormState();
        } elseif ($mode === 'edit' && $productId) {
            $product = Product::find($productId);
            if ($product) {
                $this->loadProduct($product);
            }
        }
    }

    public ?Product $product = null;

    #[Validate('required|string|max:100')]
    public $name = '';

    #[Validate('required|string|max:50')]
    public $sku = '';

    #[Validate('required|integer|exists:categories,id')]
    public $category_id = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|numeric|min:0')]
    public $price = '';

    #[Validate('required|integer|min:0')]
    public $quantity = '';

    #[Validate('required|integer|min:0')]
    public $min_quantity = '10';

    #[Validate('required|integer|min:0')]
    public $max_quantity = '100';

    #[Validate('required|string')]
    public $unit = 'pcs';

    #[Validate('nullable|string')]
    public $supplier = '';

    public $isEditing = false;

    public function mount(?Product $product = null)
    {
        // Always initialize default state
        $this->resetFormState();

        if ($product) {
            $this->loadProduct($product);
        }
    }

    protected function resetFormState()
    {
        $this->product = null;
        $this->name = '';
        $this->sku = '';
        $this->category_id = '';
        $this->description = '';
        $this->price = '';
        $this->quantity = '';
        $this->min_quantity = '10';
        $this->max_quantity = '100';
        $this->unit = 'pcs';
        $this->supplier = '';
        $this->isEditing = false;
        $this->resetValidation();
    }

    protected function loadProduct(Product $product)
    {
        $this->product = $product;
        $this->fill($product->toArray());
        $this->isEditing = true;
    }

    public function save()
    {
        // Build validation rules dynamically for SKU uniqueness
        $skuRule = 'required|string|max:50|unique:products,sku';
        if ($this->isEditing) {
            $skuRule .= ',' . $this->product->id;
        }

        $this->validate([
            'name' => 'required|string|max:100',
            'sku' => $skuRule,
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'max_quantity' => 'required|integer|min:0',
            'unit' => 'required|string',
            'supplier' => 'nullable|string',
        ]);

        if ($this->isEditing) {
            $this->product->update($this->only([
                'name', 'sku', 'category_id', 'description',
                'price', 'quantity', 'min_quantity', 'max_quantity',
                'unit', 'supplier'
            ]));
        } else {
            $this->product = Product::create($this->only([
                'name', 'sku', 'category_id', 'description',
                'price', 'quantity', 'min_quantity', 'max_quantity',
                'unit', 'supplier'
            ]));
        }

        $this->dispatch('product-saved')->to(InventoryDashboard::class);
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->reset();
        $this->isEditing = false;
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}
