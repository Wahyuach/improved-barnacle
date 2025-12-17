<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddProductForm extends Component
{
    #[Validate('required|string|max:100')]
    public $name = '';

    #[Validate('required|string|max:50|unique:products,sku')]
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

    public function save()
    {
        $this->validate();

        Product::create($this->only([
            'name', 'sku', 'category_id', 'description',
            'price', 'quantity', 'min_quantity', 'max_quantity',
            'unit', 'supplier'
        ]));

        $this->dispatch('product-saved', message: 'Produk berhasil ditambahkan!')->to(InventoryDashboard::class);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.add-product-form');
    }
}
