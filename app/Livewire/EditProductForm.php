<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Component;

class EditProductForm extends Component
{
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

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->fill($product->toArray());
    }

    public function save()
    {
        $this->validate();

        $this->product->update($this->only([
            'name', 'category_id', 'description',
            'price', 'quantity', 'min_quantity', 'max_quantity',
            'unit', 'supplier'
        ]));

        $this->dispatch('product-saved')->to(InventoryDashboard::class);
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.edit-product-form');
    }
}
